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
$iblock_id = 122;
$inputs = $_POST;

if($type == "getForm"){
  $properties = CIBlockProperty::GetList(Array("sort"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$iblock_id, "CODE"=> "FORM%"));
  $arr_input_form = array();
  $arr_list_values = array();
  while ($prop_fields = $properties->GetNext())
  {
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
}
else {

	$el = new CIBlockElement;
	
	$cnt = CIBlockElement::GetList(
		array(),
		array('IBLOCK_ID' => $iblock_id),
		array(),
		false,
		array('ID', 'NAME')
	); 
	$cnt = $cnt + '1';
	
	$files = $_FILES['file'];
		if (isset($files)){
			$countfiles = count($_FILES['file']['name']);
			for ($i=0; $i<$countfiles; $i++){
				$filename = $_FILES['file']['name'][$i];
				move_uploaded_file($_FILES["file"]["tmp_name"][$i], "/home/bitrix/www/ahstep/upload/".$filename);
				$inputs['FORM_FAYL']['n'.$i] = array("VALUE"=>CFile::MakeFileArray("/home/bitrix/www/ahstep/upload/".$filename));
				
			}
		}
	
	$props = setProps($inputs);
	print_r($props);
	
	$arLoadDocumentArray = Array(
		"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
		"IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
		"IBLOCK_ID"      => $iblock_id,
		"PROPERTY_VALUES"=> $props,
		"NAME"           => "(1C-003) Заявка на изменение маршрута в 1С №".$cnt,
		"ACTIVE"         => "Y",            // активен
	);
	
	if($DOCUMENT_ID = $el->Add($arLoadDocumentArray)){
		echo $cnt;
		/*$wfId = CBPDocument::StartWorkflow(
		   521,
			array("lists", "BizprocDocument", $DOCUMENT_ID),
			array_merge(array("userid"=>"user_".$USER->GetID()), array("TargetUser" => "user_".intval($GLOBALS["USER"]->GetID()),
			CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
			CBPDocumentEventType::Manual)),
			$arErrorsTmp
		   );*/
	}
	else{
	  echo "Error: ".$el->LAST_ERROR;
	}
}

function setProps($fieldsArr){	
	$arr_fileds_id = array();
	foreach ($fieldsArr as $key => $field) {
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