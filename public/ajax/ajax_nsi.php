<?

require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

$Nomenklatura = $data[0]['value'];
$PreviewName = $data[1]['value'];
$Parametrs = $data[2]['value'];
$AditionalParametrs = $data[3]['value'];
$Artikul = $data[4]['value'];
$Ed = $data[5]['value'];
$Nds = $data[6]['value'];
$FinalName = $data[7]['value'];
$Cmnt = $data[8]['value'];
$Cnt = $data[9]['value'];
$File = $data[10]['value'];

$cnt = CIBlockElement::GetList(
    array(),
    array('IBLOCK_ID' => 28),
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
        "IBLOCK_ID" => 28,
        "NAME" => $cnt,
        "CREATED_BY" => "user_".$GLOBALS["USER"]->GetID(),
    )
   );

   $arErrorsTmp = array();

   $wfId = CBPDocument::StartWorkflow(
   11,
    array("lists", "BizprocDocument", $documentId),
    array_merge(array("userid"=>"user_".$userId,"Nomenklatura"=>$Nomenklatura,"PreviewName"=>$PreviewName,"Parametrs"=>$Parametrs,"AditionalParametrs"=>$AditionalParametrs,"Artikul"=>$Artikul,"Ed"=>$Ed, "Nds"=>$Nds,"Ed"=>$Ed,"FinalName"=>$FinalName,"Cmnt"=>$Cmnt,"File"=>$File,"cnt"=>$cnt), array("TargetUser" => "user_".intval($GLOBALS["USER"]->GetID()),
    CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
    CBPDocumentEventType::Manual)),
    $arErrorsTmp
   );
?>