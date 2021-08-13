<?
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
$userId = $data["userId"];
$date = $data["ardate"];
$cmnt = $data["cmnt"];

	$cnt = CIBlockElement::GetList(
					array(),
					array(
						'IBLOCK_ID' => 72,
						'NAME' => '%(AR-001)%'
					),
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
			"IBLOCK_ID" => 72,
			"NAME" => $cnt,
			"CREATED_BY" => "user_".$GLOBALS["USER"]->GetID(),
		)
	   );

	   $arErrorsTmp = array();

	   $wfId = CBPDocument::StartWorkflow(
	   191,
		array("lists", "BizprocDocument", $documentId),
		array_merge(array("userid"=>"user_".$userId, "date"=>$date, "cmnt"=>$cmnt, "cnt"=>$cnt), array("TargetUser" => "user_".intval($GLOBALS["USER"]->GetID()),
		CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
		CBPDocumentEventType::Manual)),
		$arErrorsTmp
	   );
?>
