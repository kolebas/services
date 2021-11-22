<?
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
$IBLOCK_ID = 28;


$Nomenklatura = $data[array_search(0, array_column($data, 'id'))]['value'];
$Statya = $data[array_search(1, array_column($data, 'id'))]['value'];
$Nds = $data[array_search(7, array_column($data, 'id'))]['value'];
$File = $data[array_search(11, array_column($data, 'id'))]['value'];


$cnt = CIBlockElement::GetList(
    array(),
    array('IBLOCK_ID' => $IBLOCK_ID),
    array(),
    false,
    array('ID', 'NAME')
); 
$cnt = $cnt + '1';

#Поиск значения в массиве
function getFieldValue($id){
    global $data;
	$val = array_search($id, array_column($data, 'id'));
	if($val){
		return $data[$val]['value'];;		
	}
}
 
#Возвращает список вариантов значений свойств типа "список" по фильтру arFilter отсортированные в порядке arOrder
function getEnumFieldId($field, $code){
    global $IBLOCK_ID;
	$enum_ar = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$IBLOCK_ID, "CODE"=>$code));
	while($enum_fields = $enum_ar->GetNext())		
	{		
		if($enum_fields["VALUE"] == $field){
			return $enum_fields["ID"];
		}
	}
}

#Добавление элемента инфоблока
$el = new CIBlockElement;

$PROP = array();
$PROP[137] = Array("VALUE" => getEnumFieldId($Nomenklatura, 'VID_NOMENKLATURY'));
$PROP[138] = getFieldValue(2);
$PROP[139] = getFieldValue(3);
$PROP[140] = getFieldValue(4);
$PROP[141] = getFieldValue(5);
$PROP[142] = getFieldValue(6);
$PROP[143] = Array("VALUE" => getEnumFieldId($Nds, 'STAVKA_NDS'));
$PROP[145] = getFieldValue(9);
$PROP[147] = getFieldValue(8);
$PROP[148] = getFieldValue(10);
$PROP[883] = getFieldValue(12);
$PROP[399] = Array("VALUE" => getEnumFieldId($Statya, 'STATYA_BYUDZHETA_ZAKUPOK'));

$arLoadDocumentArray = Array(
  "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
  "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
  "IBLOCK_ID"      => $IBLOCK_ID,
  "PROPERTY_VALUES"=> $PROP,
  "NAME"           => "Редактирование справочника номеклатуры (NS-001) №".$cnt,
  "ACTIVE"         => "Y",            // активен
  );

if($DOCUMENT_ID = $el->Add($arLoadDocumentArray)){
	echo $cnt;
	$wfId = CBPDocument::StartWorkflow(
	   11,
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
?>