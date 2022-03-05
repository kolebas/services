<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 
$postData = file_get_contents('php://input');
	$data = json_decode($postData, true);
$org = $_POST['org'];
$dep = $_POST['dep'];
$fld_name = $_POST['fld_name'];
$bp_usr = $_POST['bp_usr'];
$ar_rw_usr_all = explode (" ", $_POST['rw_usr_all']);
$ar_ro_usr_all = explode (" ", $_POST['ro_usr_all']);
$cmnt = $_POST['cmnt'];

  $cnt = CIBlockElement::GetList(
    array(),
    array('IBLOCK_ID' => 85),
    array(),
    false,
    array('ID', 'NAME')
); 
 $cnt = $cnt + '1';
 
 CModule::IncludeModule('bizproc');
	$documentId = CBPVirtualDocument::CreateDocument(
    0,
    array(
     "IBLOCK_ID" => 85,
	 "NAME" => $cnt,
   "CREATED_BY" => "user_".$GLOBALS["USER"]->GetID(),
    )
   );

   $arErrorsTmp = array();

   $wfId = CBPDocument::StartWorkflow(
   294,
    array("lists", "BizprocDocument", $documentId),
    array_merge(array("org"=>$org, "dep"=>$dep, "fld_name"=>$fld_name, "bp_usr"=>$bp_usr, "rw_usr"=>$ar_rw_usr_all, "ro_usr"=>$ar_ro_usr_all, "cmnt"=>$cmnt, "cnt"=>$cnt), array("TargetUser" => "user_".intval($GLOBALS["USER"]->GetID()),
	CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
	CBPDocumentEventType::Manual)),
    $arErrorsTmp
   );
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/epilog_after.php');   
?>