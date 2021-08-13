<?
	require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
	?>


<?

if (isset($_POST["org"])){
	
	$postData = file_get_contents('php://input');
	$data = json_decode($postData, true);

	$org = $_POST["org"];
	$list = $_POST["list"];
	$area = $_POST["area"];
	$agro = $_POST["agro"];
	$hyst = $_POST["hyst"];
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
		array('IBLOCK_ID' => 91),
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
			"IBLOCK_ID" => 91,
			"NAME" => $cnt,
			"CREATED_BY" => "user_".$GLOBALS["USER"]->GetID(),
			)
	);

	$arErrorsTmp = array();

	$wfId = CBPDocument::StartWorkflow(
		341,
		array("lists", "BizprocDocument", $documentId),
		array_merge(array("userid"=>"user_".$GLOBALS["USER"]->GetID(), "org"=>$org, "list"=>$list, "area"=>$area, "agro"=>$agro, "hyst"=>$hyst, "cmnt"=>$cmnt, "file"=>$files, "cnt"=>$cnt), array("TargetUser" => "user_".intval($GLOBALS["USER"]->GetID()),
		CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
		CBPDocumentEventType::Manual)),
		$arErrorsTmp
	   );
} else {
	$org = array();
	$id = 67;
	$arFilter = Array(
		'IBLOCK_ID'=>$id
	);
	$res = CIBlockElement::GetList(Array('NAME'=>'ASC'), $arFilter);
	while($el = $res->GetNext()):
		$arElementID[] = $el["ID"];
		$db_props = CIBlockElement::GetProperty($id,$el["ID"], array());
		$PROPS = array();
		while($ar_props = $db_props->Fetch()):
		if($ar_props[VALUE] != NULL){						
			$PROPS[$ar_props[CODE]] = $ar_props[VALUE];
		}
		endwhile;
		{
			$org[] = array('ID' => $el['ID'], 'NAME' => $el['NAME'], 'RDT' => $el['SOKRASHCHENIE']);
		}
		endwhile;
	echo json_encode($org);
}	
?>
