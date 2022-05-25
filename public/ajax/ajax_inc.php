<?
	require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
	?>

<?

	$postData = file_get_contents('php://input');
	$data = json_decode($postData, true);

	$title_inc = $_POST["title_inc"];
	$type_1c_slct = $_POST["type_1c_slct"];
	$switch_mon = $_POST["switch_mon"];
  $switch_nsi = $_POST["switch_nsi"];
  $type_nsi = $_POST["type_nsi_slct"];
  $db = $_POST["db"];
	$cmnt = $_POST["cmnt"];
	$user = $_POST["responsible"];
	if($user != NULL){
		$author = $user;
	} else {
		$author = $GLOBALS["USER"]->GetID();
	}	
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
			"CREATED_BY" => "user_".$author,
			)
	);

	$arErrorsTmp = array();

	$wfId = CBPDocument::StartWorkflow(
		313,
		array("lists", "BizprocDocument", $documentId),
		array_merge(array("author"=>$author, "title"=>$title_inc, "type_1s"=>$type_1c_slct, "switch_mon"=>$switch_mon, "switch_nsi"=>$switch_nsi, "type_nsi"=>$type_nsi, "db"=>$db,  "body"=>$cmnt, "file"=>$files, "cnt"=>$cnt), array("TargetUser" => "user_".intval($author),
		CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
		CBPDocumentEventType::Manual)),
		$arErrorsTmp
	);
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/epilog_after.php');
?>