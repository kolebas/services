<?
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
if($data != NULL){
	$userId = $data["userId"];
	$type = $data["type"];
	$cmnt = $data["cmnt"];

	$cnt = CIBlockElement::GetList(
		array(),
		array('IBLOCK_ID' => 59),
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
			"IBLOCK_ID" => 59,
			"NAME" => $cnt,
			"CREATED_BY" => "user_".$GLOBALS["USER"]->GetID(),
		)
	   );

	   $arErrorsTmp = array();

	   $wfId = CBPDocument::StartWorkflow(
	   114,
		array("lists", "BizprocDocument", $documentId),
		array_merge(array("userid"=>"user_".$userId, "type"=>$type, "cmnt"=>$cmnt, "cnt"=>$cnt), array("TargetUser" => "user_".intval($GLOBALS["USER"]->GetID()),
		CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
		CBPDocumentEventType::Manual)),
		$arErrorsTmp
	   );
	}
?>
