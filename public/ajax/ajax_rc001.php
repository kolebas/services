<?
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
$id = 102;

if (isset($data)){
	
	$title = $data[0]['value'];
	$partner = $data[1]['value'];
	$contact = $data[2]['value'];
	$date = $data[3]['value'];
	$access = $data[4]['value'];


	$cnt = CIBlockElement::GetList(
		array(),
		array('IBLOCK_ID' => $id),
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
			"IBLOCK_ID" => $id,
			"NAME" => $cnt,
			"CREATED_BY" => "user_".$GLOBALS["USER"]->GetID(),
			)
	);

	$arErrorsTmp = array();

	$wfId = CBPDocument::StartWorkflow(
		370,
		array("lists", "BizprocDocument", $documentId),
		array_merge(array("title"=>$title, "partner"=>$partner, "contact"=>$contact, "date"=>$date, "access"=>$access, "cnt"=>$cnt), array("TargetUser" => "user_".intval($GLOBALS["USER"]->GetID()),
		CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
		CBPDocumentEventType::Manual)),
		$arErrorsTmp
	   );
} 	
?>
