<?
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
$IBLOCK_ID = 120;

if(isset($_POST[0])){
	$type = $_POST[100];
	$rezident = $_POST[101];
	if($rezident === "true"){
		$rezident = "Да";
	} else {
		$rezident = "Нет";
	} 
	
	$db = array();
	foreach(explode(",",$_POST[0]) as &$value) {
		array_push($db, getEnumFieldId($value, 'DB'));
	}
	
	$segment = $_POST[2];
	$klaster = $_POST[3];
	$organ = $_POST[7];
	$paishik = $_POST[33];
	$valuta = $_POST[38];
  $channel = $_POST[49];
  $bitrhday = getFieldValue(41);
  $dateExtract = getFieldValue(47);
	
	if(isset($bitrhday)){
		$bitrhday = date("d.m.Y", strtotime(getFieldValue(41)));
	}

  if(isset($dateExtract)){
		$dateExtract = date("d.m.Y", strtotime(getFieldValue(47)));
	}

	//Работа с файлами
	$files = $_FILES['file'];
	if (isset($files)){
		$countfiles = count($_FILES['file']['name']);
		for ($i=0; $i<$countfiles; $i++){
			$filename = $_FILES['file']['name'][$i];
			move_uploaded_file($_FILES["file"]["tmp_name"][$i], "/home/bitrix/www/ahstep/upload/".$filename);
			$arrFiles['n'.$i] = array("VALUE"=>CFile::MakeFileArray("/home/bitrix/www/ahstep/upload/".$filename));
			
		}
	}
} 


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
	global $_POST;
	if(isset($_POST[0])){		
		$val = $_POST[$id];
		if($val){
			return $val;		
		}		
	} else {
		$val = array_search($id, array_column($data, 'id'));
		if($val){
			return $data[$val]['value'];		
		}
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
$PROP["TIP_KONTRAGENTA"] = Array("VALUE" => getEnumFieldId($type, 'TIP_KONTRAGENTA'));
$PROP["REZIDENT"] = Array("VALUE" => getEnumFieldId($rezident, 'REZIDENT'));
$PROP["DB"] = $db;
$PROP["SEGMENT"] = Array("VALUE" => getEnumFieldId($segment, 'SEGMENT'));
$PROP["KLASTER"] = Array("VALUE" => getEnumFieldId($klaster, 'KLASTER'));
$PROP["KRATKOE_NAIMENOVANIE"] = getFieldValue(4);
$PROP["POLNOE_NAIMENOVANIE"] = getFieldValue(5);
$PROP["VID_GOSUDARSTVENNOGO_ORGANA"] = Array("VALUE" => getEnumFieldId($organ, 'VID_GOSUDARSTVENNOGO_ORGANA'));
$PROP["KOD_GOSUDARSTVENOGO_ORGANA"] = getFieldValue(8);
$PROP["INN"] = getFieldValue(9);
$PROP["KPP"] = getFieldValue(10);
$PROP["LITSEVOY_SCHYET"] = getFieldValue(12);
$PROP["RASCHETNYY_SCHYET"] = getFieldValue(13);
$PROP["BIK"] = getFieldValue(14);
$PROP["YURIDICHESKIY_ADRES"] = getFieldValue(19);
$PROP["FAKTICHESKIY_ADRES"] = getFieldValue(20);
$PROP["POCHTOVYY_ADRES"] = getFieldValue(21);
$PROP["OTVESTVENNYY"] = getFieldValue(22);
$PROP["TELEFON"] = getFieldValue(23);
$PROP["EMAIL"] = getFieldValue(24);
$PROP["KONTAKTNOE_LITSO"] = getFieldValue(25);
$PROP["PAISHIK"] = Array("VALUE" => getEnumFieldId($paishik, 'PAISHIK'));
$PROP["OPISANIE"] = getFieldValue(32);
$PROP["FIZICHESKOE_LITSO"] = getFieldValue(34);
$PROP["KOD_FIZICHESKOGO_LITSA"] = getFieldValue(35);
$PROP["BANK"] = getFieldValue(36);
$PROP["VALYUTA_SCHETA"] = Array("VALUE" => getEnumFieldId($valuta, 'VALYUTA_SCHETA'));
$PROP["STRANA_REGISTRATSII"] = getFieldValue(39);
$PROP["NALOGOVYY_NOMER_NALOGOPLATELSHCHIKA"] = getFieldValue(40);
$PROP["SNILS"] = getFieldValue(43);
$PROP["PASPORT"] = getFieldValue(44);
$PROP["KEM_VYDAN_PASPORT"] = getFieldValue(45);
$PROP["KOD_PODRAZDELENIYA"] = getFieldValue(46);
$PROP["DATA_VYDACHI_PASPORTA"] = $dateExtract;
$PROP["MESTO_ROZHDENIYA"] = getFieldValue(48);
$PROP["KANAL_SBYTA"] = Array("VALUE" => getEnumFieldId($channel, 'KANAL_SBYTA'));
$PROP["DATA_ROZHDENIYA"] = $bitrhday;
$PROP["FILE"] = $arrFiles;

$arLoadDocumentArray = Array(
  "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
  "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
  "IBLOCK_ID"      => $IBLOCK_ID,
  "PROPERTY_VALUES"=> $PROP,
  "NAME"           => "(NS-002) Добавление контрагента №".$cnt,
  "ACTIVE"         => "Y",            // активен
  );

if($DOCUMENT_ID = $el->Add($arLoadDocumentArray)){
	echo $cnt;
	$wfId = CBPDocument::StartWorkflow(
	   492,
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
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/epilog_after.php');
?>