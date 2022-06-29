<?
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
$type = $_GET["type"];
$iblock_id = 121;
$inputs = $_POST;

if($type == "getForm"){
  $properties = CIBlockProperty::GetList(Array("sort"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$iblock_id, "CODE"=> "FORM%"));
  $arr_input_form = array();
  $arr_list_values = array();
  while ($prop_fields = $properties->GetNext())
  {
	  if($prop_fields["PROPERTY_TYPE"] === "L") {
		  $property_enums = CIBlockPropertyEnum::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => $iblock_id,"PROPERTY_ID" => $prop_fields["ID"]));
			while($enum_fields = $property_enums->GetNext())
				{
				  $arr_list_values[] = array("NAME" => $enum_fields["VALUE"]);
				}
	  }
	  $arr_input_form[] = array("id" => $prop_fields["ID"], "name" => $prop_fields["NAME"],  "list_values" => $arr_list_values, "code" => $prop_fields["CODE"], "type" => ($prop_fields["ROW_COUNT"] > "2") ? "textarea" : $prop_fields["PROPERTY_TYPE"], "multiple" => $prop_fields["MULTIPLE"], "user_type" => $prop_fields["USER_TYPE"], "is_required" => $prop_fields["IS_REQUIRED"]);
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

	//$inputs[958] = Array(712, 713, 714, 715);
	$inputs[958] = getEnumFieldId($inputs[958], 958, 121);

	//print_r($inputs);
	
	$arLoadDocumentArray = Array(
		"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
		"IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
		"IBLOCK_ID"      => $iblock_id,
		"PROPERTY_VALUES"=> $inputs,
		"NAME"           => "(NS-003) Редактирование контрагента №".$cnt,
		"ACTIVE"         => "Y",            // активен
	);
	
	if($DOCUMENT_ID = $el->Add($arLoadDocumentArray)){
		echo $cnt;
		$wfId = CBPDocument::StartWorkflow(
		   521,
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

function getEnumFieldId($fields, $code, $iblock_id){
	$arr_fileds_id = array();
	foreach ($fields as &$field) {
		$enum_ar = CIBlockPropertyEnum::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>$iblock_id));
		while($enum_fields = $enum_ar->GetNext())		
		{	
			if($enum_fields["VALUE"] == $field){
				array_push($arr_fileds_id, $enum_fields["ID"]);
			}
		}
	}
	return $arr_fileds_id;	
}
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/epilog_after.php');
?>