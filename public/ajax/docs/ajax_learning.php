<?
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
$data = array();
$id = 100;
$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

if($_GET['type'] == 'get'){
	//Получаем список элементов инфоблока

	$arSelect = [
	'ID',
	'NAME',
	'PROPERTY_728',
	'PROPERTY_724',
	'PROPERTY_725',
	'PROPERTY_726',
	'PROPERTY_727',
	'PROPERTY_729',
	'PROPERTY_731',
];

$element = CIBlockElement::GetList([], ["IBLOCK_ID" => $id], false, false, $arSelect);
$i = 0;
while($arElements = $element->Fetch())
{
	$arrElems[] = $arElements;
	if ($arElements[PROPERTY_731_VALUE] != NULL){
		foreach ($arElements[PROPERTY_731_VALUE] as $key => $value) {			
			$rsFile = CFile::GetByID($value);
			$arFile = $rsFile->Fetch();
			$files[] = array('ID' => $value, 'NAME' => $arFile[FILE_NAME], 'PATH' => CFile::GetPath($value));
			
		}
		unset($value);
		$arrElems[$i][PROPERTY_731_VALUE] = $files;
		$files = array();

	}
	$i++;
}

echo json_encode($arrElems);

}
if($data['type'] == 'NewElement'){
	echo $data['data'][0]['value'];

	$file = $_FILES[$data['data'][7]['value']];

	//Добавление ПУНа
	$name = $data['data'][0]['value'];	
	$el = new CIBlockElement;
    $section_id = false;
    //$section_id[$i] = $_POST['section_id']; //Разделы для добавления

    //Свойства
    $PROP = array();
	$PROP[728] = $data['data'][1]['value'];
	$PROP[724] = $data['data'][2]['value'];
	$PROP[725] = $data['data'][3]['value'];
	$PROP[726] = $data['data'][4]['value'];
	$PROP[727] = $data['data'][5]['value'];
	$PROP[729] = $data['data'][8]['value'];
	$PROP[731] = $_FILES['data'][7]['value'];

	print_r($PROP[731]);
	//
	$fields = array(
        "NAME" => $name,
        "CREATED_BY" => $GLOBALS['USER']->GetID(),//Передаем ID пользователя кто добавляет
        "IBLOCK_ID" => $id, //ID информационного блока 
        "PROPERTY_VALUES" => $PROP, // Передаем массив значении для свойств
    );
    $order_id = $el->Add($fields);
}
?>