<?
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');	
$postData = file_get_contents('php://input');
$data = json_decode($postData, true); 

if ($postData) {
	$user = $data['user'];
	$org = $data['org'];
	$job = $data['prof'];
  $questions = $data['questions'];

  $PROP = array();
	$PROP['HR_ANKETA_QST1'] = $questions[0]['value'];
  $PROP['HR_ANKETA_QST2'] = $questions[1]['value'];
  $PROP['HR_ANKETA_QST3'] = $questions[2]['value'];
  $PROP['HR_ANKETA_QST4'] = $questions[3]['value'];
  $PROP['HR_ANKETA_QST5'] = $questions[4]['value'];

  CModule::IncludeModule('bizproc');
    $documentId = CBPVirtualDocument::CreateDocument(
        0,
        array(
            "IBLOCK_ID" => 105,
            "NAME" => "Анкета " . $user,
            "CREATED_BY" => "user_" . $GLOBALS["USER"]->GetID(),
            "PROPERTY_VALUES"=> $PROP,
            "IBLOCK_SECTION" => 927,
        )
    );

    $arErrorsTmp = array();

    $wfId = CBPDocument::StartWorkflow(
        398,
        array("lists", "BizprocDocument", $documentId),
        array_merge(array(), array("TargetUser" => "user_" . intval($GLOBALS["USER"]->GetID()),
            CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
            CBPDocumentEventType::Manual)),
        $arErrorsTmp
    );
} 
?>