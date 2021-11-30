<?
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
$type = $data[0]['value'];
if(count($data)>3){
	$telnumber = $data[1]['value'];
	$cmnt = $data[2]['value'];
}
else {
	$cmnt = $data[1]['value'];
}
$cnt = CIBlockElement::GetList(
	array(),
	array('IBLOCK_ID' => 58),
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
		"IBLOCK_ID" => 58,
		"NAME" => $cnt,
		"CREATED_BY" => "user_".$GLOBALS["USER"]->GetID(),
	)
	);

	$arErrorsTmp = array();

	$wfId = CBPDocument::StartWorkflow(
	115,
	array("lists", "BizprocDocument", $documentId),
	array_merge(array("userid"=>"user_".$GLOBALS["USER"]->GetID(), "type"=>$type, "telnumber"=>$telnumber, "cmnt"=>$cmnt,"cnt"=>$cnt), array("TargetUser" => "user_".intval($GLOBALS["USER"]->GetID()),
	CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
	CBPDocumentEventType::Manual)),
	$arErrorsTmp
	);
?>
