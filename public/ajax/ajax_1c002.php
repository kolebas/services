<?
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

if (isset($_POST["cmnt"])){

	$postData = file_get_contents('php://input');
	$data = json_decode($postData, true);

	$cmnt = $_POST["cmnt"];
	$db = $_POST["db"];
	$file = $_FILES['file'];

	if (isset($file)){
		$files = array();
		$countfiles = count($_FILES['file']['name']);
		for ($i=0; $i<$countfiles; $i++){
			$filename = $_FILES['file']['name'][$i];
			move_uploaded_file($_FILES["file"]["tmp_name"][$i], "./upload/ms001/".$filename);
			array_push($files, "./upload/ms001/".$filename);
		}	
	}
	
	$cnt = CIBlockElement::GetList(
		array(),
		array('IBLOCK_ID' => 69),
		array(),
		false,
		array('ID', 'NAME')
	); 
	$cnt = $cnt + '1';
	echo $cnt;
	 
	CModule::IncludeModule('bizproc');
	$documentId = CBPVirtualDocument::CreateDocument(
		0,
		array(
			"IBLOCK_ID" => 69,
			"NAME" => $cnt,
			"CREATED_BY" => "user_".$GLOBALS["USER"]->GetID(),
			)
	);

	$arErrorsTmp = array();

	$wfId = CBPDocument::StartWorkflow(
		178,
		array("lists", "BizprocDocument", $documentId),
		array_merge(array("cmnt"=>$cmnt, "db"=>$db, "file"=>$files, "cnt"=>$cnt), array("TargetUser" => "user_".intval($GLOBALS["USER"]->GetID()),
		CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
		CBPDocumentEventType::Manual)),
		$arErrorsTmp
	   );
} else {
	$dbExport= array();
	$arFilter = Array(
		"IBLOCK_ID"=> 70,
		"IBLOCK_SECTION_ID" => 877
	);
	$res = CIBlockElement::GetList(Array('NAME'=>'ASC'), $arFilter);
	while($ar_fields = $res->GetNext())
		{
			$dbExport[] = array('ID' => $ar_fields['ID'], 'NAME' => $ar_fields['NAME']);
		}
	echo json_encode($dbExport);
}	
?>
