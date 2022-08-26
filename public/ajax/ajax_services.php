<?php
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }

    #echo "You have CORS!";
?>
<?
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
$type = $_GET["type"];
$post_type = $_POST["type"];
$iblock_id = $_GET["ib_id"];
$inputs = $_POST;

if($type == "getForm"){
  $properties = CIBlockProperty::GetList(Array("sort"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$iblock_id, "CODE"=> "FORM%"));
  $arr_input_form = array();
  while ($prop_fields = $properties->GetNext())
  {	  
	$arr_list_values = array();
	  if($prop_fields["PROPERTY_TYPE"] === "L") {
		  $property_enums = CIBlockPropertyEnum::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => $iblock_id,"PROPERTY_ID" => $prop_fields["ID"]));
		  $arr_list_values = array();
			while($enum_fields = $property_enums->GetNext())
				{
				  $arr_list_values[] = array("NAME" => $enum_fields["VALUE"]);
				}
	  }
	  
	  $arr_input_form[] = array("id" => $prop_fields["ID"], "name" => $prop_fields["NAME"],  "list_values" => $arr_list_values, "code" => $prop_fields["CODE"], "type" => ($prop_fields["ROW_COUNT"] > "2") ? "textarea" : $prop_fields["PROPERTY_TYPE"], "multiple" => $prop_fields["MULTIPLE"], "user_type" => $prop_fields["USER_TYPE"], "is_required" => $prop_fields["IS_REQUIRED"], "is_invisible" => $prop_fields["HINT"]);
  }
  echo json_encode($arr_input_form);
} elseif  ($type == "get1cReposibles"){
	$result = array();
	$arFilter = array(
        "IBLOCK_ID" => $iblock_id,
        "ACTIVE" => "Y",
        "IBLOCK_SECTION_ID" => array($sectionID, $_GET[sectionId]),
    );
    $res = CIBlockElement::GetList(array('NAME' => 'ASC'), $arFilter);
	while ($el = $res->GetNextElement())
	{	
		$fileds = $el->GetFields();
		$props = $el->GetProperties();
		$responsibles = array();
		foreach($props["FORM_OTVESTVENNYY"]["VALUE"] as $value){
			$rsUser = CUser::GetByID($value);
			$arUser = $rsUser->Fetch();
			array_push($responsibles, " " . $arUser["LAST_NAME"] . " " . $arUser["NAME"]);
		}
		
		$result[] = array("ID" => $fileds["ID"], "TITLE" => $fileds["NAME"], "RESPONSIBLES" => $responsibles);
	}
	echo json_encode($result);
} elseif ($post_type === "updateData"){
	print_r($post_type);
	$el = new CIBlockElement;
	
	$iblock_id = $_POST["ib_id"];
	
	$props = setProps($inputs, $iblock_id);
	
	if(isset($_FILES)){
		$filesArr = $_FILES;
		foreach($filesArr as $key => $files) {			
			for ($i=0; $i<count($files); $i++){
				$filename = $filesArr[$key]['name'][$i];
				move_uploaded_file($filesArr[$key]["tmp_name"][$i], "/home/bitrix/www/ahstep/upload/".$filename);
				$props[$key]['n'.$i] = array("VALUE"=>CFile::MakeFileArray("/home/bitrix/www/ahstep/upload/".$filename));
			}
		}	
	}
		
	$arLoadDocumentArray = Array(
		"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
		"IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
		"IBLOCK_ID"      => $iblock_id,
		"PROPERTY_VALUES"=> $props,
		"ACTIVE"         => "Y",            // активен
	);
	
	$order_id = $el->Update($id, $arLoadDocumentArray);
} else {
	$iblock_id = $_POST["ib_id"];
	$wf_id = $_POST["wf_id"];
	$el = new CIBlockElement;
	
	$cnt = CIBlockElement::GetList(
		array(),
		array('IBLOCK_ID' => $iblock_id),
		array(),
		false,
		array('ID', 'NAME')
	); 
	$cnt = $cnt + '1';
		
	$props = setProps($inputs, $iblock_id);
	
	if(isset($_FILES)){
		$filesArr = $_FILES;
		foreach($filesArr as $key => $files) {			
			for ($i=0; $i<count($files); $i++){
				$filename = $filesArr[$key]['name'][$i];
				move_uploaded_file($filesArr[$key]["tmp_name"][$i], "/home/bitrix/www/ahstep/upload/".$filename);
				$props[$key]['n'.$i] = array("VALUE"=>CFile::MakeFileArray("/home/bitrix/www/ahstep/upload/".$filename));
			}
		}	
	}
		
	$arLoadDocumentArray = Array(
		"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
		"IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
		"IBLOCK_ID"      => $iblock_id,
		"PROPERTY_VALUES"=> $props,
		"NAME"           => "(NS-004) Заявка на создание физлица №".$cnt,
		"ACTIVE"         => "Y",            // активен
	);
	
	if($DOCUMENT_ID = $el->Add($arLoadDocumentArray)){
		echo $cnt;
		$wfId = CBPDocument::StartWorkflow(
		   $wf_id,
			array("lists", "BizprocDocument", $DOCUMENT_ID),
			array_merge(array("userid"=>"user_".$USER->GetID()), array("TargetUser" => "user_".intval($GLOBALS["USER"]->GetID()),
			CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
			CBPDocumentEventType::Manual)),
			$arErrorsTmp
		   );
	}
	else{
	  echo "Error: ".$el->LAST_ERROR;
	}
}


function setProps($fieldsArr, $iblock_id){	
	$arr_fileds_id = array();
	foreach ($fieldsArr as $key => $field) {
		$properties = CIBlockProperty::GetList(Array("sort"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$iblock_id, "ID"=> $key));
		while ($property = $properties->GetNext()){
			if($property["PROPERTY_TYPE"] === "L"){
				if(is_array($field)){
					$tempArr = array();
					foreach ($field as &$item){
						array_push($tempArr, getEnumFieldIdNew($item, $key));
					}
					$arr_fileds_id[$key] = $tempArr;				
				} else {
					$arr_fileds_id[$key] = getEnumFieldIdNew($field, $key);
				}
			}
			if($property["PROPERTY_TYPE"] === "S" && $property["USER_TYPE"] === "Date"){
				$arr_fileds_id[$key] = date("d.m.Y", strtotime($field));
			}
			if($property["PROPERTY_TYPE"] === "S" && ($property["USER_TYPE"] === "" || $property["USER_TYPE"] === null )){
				$arr_fileds_id[$key] = $field;
			}
		}		
	}				
	return $arr_fileds_id;
}

function getEnumFieldIdNew($field, $code){
	global $iblock_id;
	$enum_ar = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$iblock_id, "PROPERTY_ID"=>$code));
	while($enum_fields = $enum_ar->GetNext())		
	{	
		if($enum_fields["VALUE"] == $field){
			return $enum_fields["ID"];
		}
	}
}

require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/epilog_after.php');
?>