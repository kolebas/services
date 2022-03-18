<?
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

if($data["type"] == "adm001"){
	$user = $data['input'][0]['value'];
	$accessLevel = $data['input'][1]['value'];
	$cmnt = $data['input'][2]['value'];

	$cnt = CIBlockElement::GetList(
		array(),
		array('IBLOCK_ID' => 104),
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
			"IBLOCK_ID" => 104,
			"NAME" => $cnt,
			"CREATED_BY" => "user_".$GLOBALS["USER"]->GetID(),
			)
	);

	$arErrorsTmp = array();

	$wfId = CBPDocument::StartWorkflow(
		391,
		array("lists", "BizprocDocument", $documentId),
		array_merge(array("user"=>"user_".$user, "accessLevel"=>$accessLevel, "cmnt"=>$cmnt, "cnt"=>$cnt), array("TargetUser" => "user_".intval($GLOBALS["USER"]->GetID()),
		CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
		CBPDocumentEventType::Manual)),
		$arErrorsTmp
	   );	

}
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/epilog_after.php');	
?>