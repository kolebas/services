<?
#Создание заявки на проведение Агрономического опыта

require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

$data = json_decode($_POST['input'], true);
$dataParams = json_decode($_POST['params'], true);

	$org = $data[0]['value'];
	$category = $data[1]['value'];
	$contactUser = $data[2]['value'];
	$contactInfo = $data[3]['value'];
	$theme = $data[4]['value'];
	$target = $data[5]['value'];
	$task = $data[6]['value'];
	$object = $data[7]['value'];
	$effective = $data[8]['value'];
	$orgInfo = $data[9]['value'];
	$agroName = $dataParams[0]['name'];
	$agroAmount = $dataParams[0]['amount'];
	$agroDv = $dataParams[0]['dv'];
	$agroPhase = $dataParams[0]['phase'];
	$agroPrice = $dataParams[0]['price'];

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
		array("lists", "BizprocDocument", $documentId),
		array_merge(array("org"=>$org, "category"=>$category, "contactUser"=>$contactUser, "contactInfo"=>$contactInfo, "theme"=>$theme, "target"=>$target, "task"=>$task, "object"=>$object, "effective"=>$effective, "agroName"=>$agroName, "orgInfo"=>$orgInfo, "agroItem"=>$agroAmount, "agroDv"=>$agroDv, "agroPhase"=>$agroPhase, "agroPrice"=>$agroPrice, "orgInfo"=>$orgInfo, "file"=>$files, "cmnt"=>$cmnt,"cnt"=>$cnt), array("TargetUser" => "user_".intval($GLOBALS["USER"]->GetID()),
		CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
		CBPDocumentEventType::Manual)),
		$arErrorsTmp
	  );
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/epilog_after.php');	
?>