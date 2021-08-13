<?php

    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }

    #echo "You have CORS!";
?>
<?
	require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
?>


<?
//$postData = file_get_contents('php://input');
$data = json_decode($_POST['input'], true);

	$org = $data[input][0]['value'];
	$contactUser = $data[input][1]['value'];
	$contactInfo = $data[input][2]['value'];
	$theme = $data[input][3]['value'];
	$target = $data[input][4]['value'];
	$task = $data[input][5]['value'];
	$object = $data[input][6]['value'];
	$effective = $data[input][7]['value'];
	$orgInfo = $data[input][8]['value'];
	$agroName = $data[params][0]['name'];
	$agroAmount = $data[params][0]['amount'];
	$agroDv = $data[params][0]['dv'];
	$agroPhase = $data[params][0]['phase'];
	$agroPrice = $data[params][0]['price'];

	$file = $_FILES['file'];

	if (isset($file)){
		$files = array();
		$countfiles = count($_FILES['file']['name']);
		for ($i=0; $i<$countfiles; $i++){
			$filename = $_FILES['file']['name'][$i];
			move_uploaded_file($_FILES["file"]["tmp_name"][$i], "./upload/op001/".$filename);
			array_push($files, "./upload/op001/".$filename);
		}	
	}


	$cnt = CIBlockElement::GetList(
		array(),
		array('IBLOCK_ID' => 101),
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
			"IBLOCK_ID" => 101,
			"NAME" => $cnt,
			"CREATED_BY" => "user_".$GLOBALS["USER"]->GetID(),
			)
	);

	$arErrorsTmp = array();

	$wfId = CBPDocument::StartWorkflow(
		369,
		array("lists", "Bitrix\\Lists\\BizprocDocumentLists", $documentId),
		array_merge(array("org"=>$org, "contactUser"=>$contactUser, "contactInfo"=>$contactInfo, "theme"=>$theme, "target"=>$target, "task"=>$task, "object"=>$object, "effective"=>$effective, "agroName"=>$agroName, "orgInfo"=>$orgInfo, "agroItem"=>$agroAmount, "agroDv"=>$agroDv, "agroPhase"=>$agroPhase, "agroPrice"=>$agroPrice, "orgInfo"=>$orgInfo, "file"=>$files, "cmnt"=>$cmnt,"cnt"=>$cnt), array("TargetUser" => "user_".intval($GLOBALS["USER"]->GetID()),
		CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
		CBPDocumentEventType::Manual)),
		$arErrorsTmp
	   );	
?>
