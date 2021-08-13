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
$data = array();
$id = 99;
$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

if($_GET['type'] == 'get'){
	//Получаем список пунов
		$arSelect = [
	'ID',
	'NAME',
	'PROPERTY_738',
	'PROPERTY_717',
	'PROPERTY_718',
	'PROPERTY_719',
	'PROPERTY_720',
	'PROPERTY_721',
	'PROPERTY_722',
	'PROPERTY_723',
	'PROPERTY_739',
	'PROPERTY_740',
	'PROPERTY_741',
	'PROPERTY_742',
	'PROPERTY_743',
	'PROPERTY_744',
	'PROPERTY_745',
	'PROPERTY_746',
	'PROPERTY_747',
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
		//print_r($files);
		//print_r ($arrElems[$i][PROPERTY_731_VALUE]);
		$arrElems[$i][PROPERTY_731_VALUE] = $files;
		$files = array();

	}
	$i++;
}

echo json_encode($arrElems);
}
if($data['type'] == 'NewPun'){
	echo $data['data'][0]['value'];
	//Добавление ПУНа
	$name = $data['data'][1]['value'];	
	$el = new CIBlockElement;
    $section_id = false;
    //$section_id[$i] = $_POST['section_id']; //Разделы для добавления

    //Свойства
    $PROP = array();
	$PROP[717] = $data['data'][0]['value'];
	$PROP[718] = $data['data'][2]['value'];
	$PROP[719] = $data['data'][3]['value'];
	$PROP[720] = $data['data'][4]['value'];
	//
	$fields = array(
        "NAME" => $name,
        "CREATED_BY" => $GLOBALS['USER']->GetID(),//Передаем ID пользователя кто добавляет
        "IBLOCK_ID" => $id, //ID информационного блока 
        "PROPERTY_VALUES" => $PROP, // Передаем массив значении для свойств
    );
    $order_id = $el->Add($fields);
}
if($data['type'] == 'NewItemPun'){
	//Добавление задачи на пункт ПУНа
	//var_dump($data);
	echo "<br>";
	var_dump($data['data']);
	echo "<br>";
	$NewItemPun = $data['data'];
	echo $NewItemPun[0]['value'];
	echo "<br>";

	$name = "Пункт отчета" . " №" . $data['data'][0]['value'];
	if (CModule::IncludeModule("tasks"))
	{
	    $arFields = Array(
	        "TITLE" => $name,
	        "DESCRIPTION" => "Task description",
	        "RESPONSIBLE_ID" => 1
	    );

	    $obTask = new CTasks;
	    $ID = $obTask->Add($arFields);
	    $success = ($ID>0);

	    if($success)
	    {
	        echo $ID;
	    }
	    else
	    {
	        if($e = $APPLICATION->GetException())
	            echo "Error: ".$e->GetString();  
	    }

	}

	//Добавление пункта ПУНа
	$el = new CIBlockElement;
    $section_id = false;
    //$section_id[$i] = $_POST['section_id']; //Разделы для добавления

    //Свойства
    $PROP = array();
	$PROP[720] = $NewItemPun[9]['value'];
	$PROP[738] = $NewItemPun[1]['value'];
	$PROP[739] = $NewItemPun[2]['value'];
	$PROP[740] = $NewItemPun[3]['value'];
	$PROP[741] = $NewItemPun[4]['value'];
	$PROP[742] = $NewItemPun[5]['value'];
	$PROP[743] = $NewItemPun[6]['value'];
	$PROP[744] = $NewItemPun[7]['value'];
	$PROP[745] = $NewItemPun[8]['value'];
	$PROP[746] = $NewItemPun[9]['value'];

	$PROP[722] = $data['data'][0]['id'];
	$PROP[723] = $ID;
	//
	$fields = array(
        "NAME" => $data['data'][0]['value'],
        "CREATED_BY" => $GLOBALS['USER']->GetID(),//Передаем ID пользователя кто добавляет
        "IBLOCK_ID" => $id, //ID информационного блока 
        "IBLOCK_SECTION_ID" => 866, //Раздел
        "PROPERTY_VALUES" => $PROP, // Передаем массив значении для свойств
    );
    $order_id = $el->Add($fields);
}
if($data['type'] == 'DeletePun'){
	//Удаление ПУНа	
	$id = $data["data"];
	$el = new CIBlockElement;
    CIBlockElement::Delete($id);
}
	
?>