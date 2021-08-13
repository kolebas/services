<?
	require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
	?>

<?

	$postData = file_get_contents('php://input');
	$data = json_decode($postData, true);

	$title_inc = $_POST["title_inc"];
	$type_1c_slct = $_POST["type_1c_slct"];
	$switch_mon = $_POST["switch_mon"];
	$cmnt = $_POST["cmnt"];	
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
		array('IBLOCK_ID' => 88),
		array(),
		false,
		array('ID', 'NAME')
	); 
	$cnt = $cnt + '1';
	 
	CModule::IncludeModule('bizproc');
		$documentId = CBPVirtualDocument::CreateDocument(
		0,
		array(
			"IBLOCK_ID" => 88,
			"NAME" => $title_inc,
			"CREATED_BY" => "user_".$GLOBALS["USER"]->GetID(),
			)
	);

	$arErrorsTmp = array();

	$wfId = CBPDocument::StartWorkflow(
		313,
		array("lists", "BizprocDocument", $documentId),
		array_merge(array("author"=>$GLOBALS["USER"]->GetID(), "title"=>$title_inc, "type_1s"=>$type_1c_slct, "switch_mon"=>$switch_mon, "body"=>$cmnt, "file"=>$files, "file_new"=>$new_path, "cnt"=>$cnt), array("TargetUser" => "user_".intval($GLOBALS["USER"]->GetID()),
		CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
		CBPDocumentEventType::Manual)),
		$arErrorsTmp
	);

?>
