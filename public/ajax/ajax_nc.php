<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

if($data['type'] == "nc002"){
  $org = $data['org'];
  $dep = $data['dep'];
  $fld_name = $data['fld_name'];
  $bp_usr = 'user_' . $data['bp_usr'];
  $ar_rw_usr_all = $data['rw_usr_all'];
  $ar_ro_usr_all = $data['ro_usr_all'];
  $cmnt = $data['cmnt'];

  foreach ($ar_rw_usr_all as &$value) {
        $value = 'user_'.$value;
  }
  unset($value);

  foreach ($ar_ro_usr_all as &$value) {
        $value = 'user_'.$value;
  }
  unset($value);

    $cnt = CIBlockElement::GetList(
      array(),
      array('IBLOCK_ID' => 85),
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
} elseif ($data['type'] == "nc003"){ 
    $fld_name = $data['fld_name'];
    $ar_rw_usr_all = $data['rw_usr_all'];
    $ar_ro_usr_all = $data['ro_usr_all'];
    $ar_rm_usr_all = $data['rm_usr_all'];
    $cmnt = $data['cmnt'];

    foreach ($ar_rw_usr_all as &$value) {
        $value = 'user_'.$value;
    }
    unset($value);

    foreach ($ar_ro_usr_all as &$value) {
          $value = 'user_'.$value;
    }
    unset($value);
    foreach ($ar_rm_usr_all as &$value) {
          $value = 'user_'.$value;
    }
    unset($value);

      $cnt = CIBlockElement::GetList(
        array(),
        array('IBLOCK_ID' => 86),
        array(),
        false,
        array('ID', 'NAME')
    ); 
    echo $cnt = $cnt + '1';
     
     CModule::IncludeModule('bizproc');
      $documentId = CBPVirtualDocument::CreateDocument(
        0,
        array(
          "IBLOCK_ID" => 86,
          "NAME" => $cnt,
          "CREATED_BY" => "user_".$GLOBALS["USER"]->GetID(),
        )
       );

       $arErrorsTmp = array();

       $wfId = CBPDocument::StartWorkflow(
       295,
        array("lists", "BizprocDocument", $documentId),
        array_merge(array("fld_name"=>$fld_name, "rw_usr"=>$ar_rw_usr_all, "ro_usr"=>$ar_ro_usr_all, "rm_usr"=>$ar_rm_usr_all, "cmnt"=>$cmnt, "cnt"=>$cnt), array("TargetUser" => "user_".intval($GLOBALS["USER"]->GetID()),
      CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
      CBPDocumentEventType::Manual)),
        $arErrorsTmp
       );  

} elseif ($data['type'] == "nc005"){ 
    $fld_name = $data['input'][1]['value'];
    $fld_quota = $data['input'][2]['value'];    

    $cnt = CIBlockElement::GetList(
        array(),
        array('IBLOCK_ID' => 103),
        array(),
        false,
        array('ID', 'NAME')
    ); 
    echo $cnt = $cnt + '1';
     
     CModule::IncludeModule('bizproc');
      $documentId = CBPVirtualDocument::CreateDocument(
        0,
        array(
          "IBLOCK_ID" => 103,
          "NAME" => $cnt,
          "CREATED_BY" => "user_".$GLOBALS["USER"]->GetID(),
        )
       );

       $arErrorsTmp = array();

       $wfId = CBPDocument::StartWorkflow(
       376,
        array("lists", "BizprocDocument", $documentId),
        array_merge(array("fld_name"=>$fld_name, "fld_quota"=>$fld_quota, "cnt"=>$cnt), array("TargetUser" => "user_".intval($GLOBALS["USER"]->GetID()),
      CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
      CBPDocumentEventType::Manual)),
        $arErrorsTmp
       );  

} elseif ($data['type'] == "getFldr"){
    $dir = '/home/bitrix/mnt/ahs-nfs001/';
    $dir = scandir($dir);
    unset($dir[2], $dir[3]);
    echo "test";
    print_r($dir);
    echo json_encode($dir);
  }
else {
    $dir = '/home/bitrix/mnt/ahs-nfs001/';
    $dir = scandir($dir);
    unset($dir[2], $dir[3]);
    foreach ($dir as $key => $value) {
       $get_result[] = array('NAME' => $value);
     } 
    unset($value);   
    echo json_encode($get_result);
  }
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/epilog_after.php');
?>