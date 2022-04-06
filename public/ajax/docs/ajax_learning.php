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
//Добавление нвого элемента
if($_POST["type"] == 'newElement'){
	$files = $_FILES['file'];
	$name = $_POST["name"];	
	$el = new CIBlockElement;
  $section_id = false;


  //Добавление файлов
  if (isset($files)){
		$countfiles = count($_FILES['file']['name']);
		for ($i=0; $i<$countfiles; $i++){
			$filename = $_FILES['file']['name'][$i];
			move_uploaded_file($_FILES["file"]["tmp_name"][$i], "/home/bitrix/www/ahstep/upload/".$filename);
			$arrFiles['n'.$i] = array("VALUE"=>CFile::MakeFileArray("/home/bitrix/www/ahstep/upload/".$filename));
			
		}
	}
	print_r($arrFiles);

  //Свойства
  $PROP = array();
	$PROP[728] = $_POST["partner"];
	$PROP[724] = $_POST["contact"];
	$PROP[725] = $_POST["anotation"];
	$PROP[726] = $_POST["resolution"];
	$PROP[727] = $_POST["responsible"];
	$PROP[729] = $_POST["link"];
	$PROP['FILE'] = $arrFiles;

	$fields = array(
        "NAME" => $name,
        "CREATED_BY" => $GLOBALS['USER']->GetID(),//Передаем ID пользователя кто добавляет
        "IBLOCK_ID" => $id, //ID информационного блока 
        "PROPERTY_VALUES" => $PROP, // Передаем массив значении для свойств
    );
    $order_id = $el->Add($fields);
}
?>