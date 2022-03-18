<?
	require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
?>

<?
	$postData = file_get_contents('php://input');
	$data = json_decode($postData, true);

	$org_name = $data[input][0]['value'];
	$os_name = $data[input][1]['value'];
	$reg_nmbr = $data[input][2]['value'];
	$arenda = $data[input][3]['value'];
	$code_1c = $data[input][4]['value'];

	$cnt = CIBlockElement::GetList(
		array(),
		array('IBLOCK_ID' => 94),
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
			"IBLOCK_ID" => 94,
			"NAME" => $cnt,
			"CREATED_BY" => "user_".$GLOBALS["USER"]->GetID(),
		)
	   );

	   $arErrorsTmp = array();

	   $wfId = CBPDocument::StartWorkflow(
	   345,
		array("lists", "BizprocDocument", $documentId),
		array_merge(array("org_name"=>$org_name, "os_name"=>$os_name, "reg_nmbr"=>$reg_nmbr, "arenda"=>$arenda, "code_1c"=>$code_1c, "cnt"=>$cnt), array("TargetUser" => "user_".intval($GLOBALS["USER"]->GetID()),
		CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
		CBPDocumentEventType::Manual)),
		$arErrorsTmp
	  );
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/epilog_after.php');    
?>