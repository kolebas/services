<?
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

if (isset($data[arr][fio])){

	$fio = $data[arr][fio];
	$wp = $data[arr][wp];
	$out_u_mail = $data[arr][out_user_mail];
	$out_u_date = $data[arr][out_user_date];
	$org = $data[arr][org];
	$dep = $data[arr][dep];
	$mng = $data[arr][mng];
	$tel = $data[arr][tel];
	$start = $data[arr][start];
	$burn = $data[arr][burn];
	$cmnt = $data[arr][cmnt];
	
	$cnt = CIBlockElement::GetList(
		array(),
		array('IBLOCK_ID' => 65),
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
			"IBLOCK_ID" => 65,
			"NAME" => $cnt,
			"CREATED_BY" => "user_".$GLOBALS["USER"]->GetID(),
			)
	);

	$arErrorsTmp = array();

	$wfId = CBPDocument::StartWorkflow(
		144,
		array("lists", "BizprocDocument", $documentId),
		array_merge(array("fio"=>$fio, "wp"=>$wp, "out_u_mail"=>$out_u_mail, "out_u_date"=>$out_u_date,"org"=>$org, "dep"=>$dep, "mng"=>"user_".$mng, "tel"=>$tel, "start"=>$start, "burn"=>$burn, "cnt"=>$cnt, "cmnt"=>$cmnt), array("TargetUser" => "user_".intval($GLOBALS["USER"]->GetID()),
		CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
		CBPDocumentEventType::Manual)),
		$arErrorsTmp
	   );
} else {
	print_r($data[arr]);
	$param = $_GET["param"];
	if($param=="АО Агрохолдинг «СТЕПЬ»"){
		$org = '1. Управляющая компания';
	}
	if($param=="ООО «Торговый Дом Агрохолдинг «СТЕПЬ»"){
		$org = '2. Торговый Дом Агрохолдинг "СТЕПЬ"';
	}
	if($param=="ООО «Техюгснаб»"){
		$org = 'ООО ТехЮгСнаб';
	}

	$dep = array();
	$arIBTYPE = CIBlockType::GetByIDLang($type, LANGUAGE_ID);
	$arFilter = Array(
		'IBLOCK_ID'=>'5', 
		'GLOBAL_ACTIVE'=>'Y',
		'NAME' => $org
	);
	$order = Array(
		'NAME'=>'ASC'
	);
	$db_list = CIBlockSection::GetList(Array($by=>$order), $arFilter, true);
		while($ar_result = $db_list->GetNext())
		{	
			$rsParentSection = CIBlockSection::GetByID($ar_result['ID']);
			if ($arParentSection = $rsParentSection->GetNext())
			{
			   $arFilter = array('IBLOCK_ID' => $arParentSection['IBLOCK_ID'],'>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],'<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],'>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL']); // выберет потомков без учета активности
			   $rsSect = CIBlockSection::GetList(array('NAME' => 'asc'),$arFilter);
			   while ($arSect = $rsSect->GetNext())
			   {
			   		$dep[] = array('ID' => $arSect['ID'], 'NAME' => $arSect['NAME']);
			       	//echo $arSect[NAME].'<br>';
			   }
			}   
		}
	echo json_encode($dep);
}	
?>
