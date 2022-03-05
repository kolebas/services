<?
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
$userId = $data["userId"];
$type = $data["type"];
$cmpname = $data["cmpname"];


	$cnt = CIBlockElement::GetList(
		array(),
		array('IBLOCK_ID' => 55),
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
			"IBLOCK_ID" => 55,
			"NAME" => $cnt,
			"CREATED_BY" => "user_".$GLOBALS["USER"]->GetID(),
		)
	   );

	   $arErrorsTmp = array();

	   $wfId = CBPDocument::StartWorkflow(
	   112,
		array("lists", "BizprocDocument", $documentId),
		array_merge(array("userid"=>"user_".$userId, "cnt"=>$cnt), array("TargetUser" => "user_".intval($GLOBALS["USER"]->GetID()),
		CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
		CBPDocumentEventType::Manual)),
		$arErrorsTmp
	  );
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/epilog_after.php');    
?>
