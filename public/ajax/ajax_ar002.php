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

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

for ($i=0; $i < count($data); $i++) {
	$date = $data[$i]["Дата"];
	$dateBirth = $data[$i]["ДатаРождения"];
	$employee = $data[$i]["Сотрудник"];
	$department = $data[$i]["Организация"];
	$workPosition = $data[$i]["Должность"];
	$workType = $data[$i]["ВидЗанятости"];

	$userData = CUser::GetList(($by="ID"), ($order="ASC"),
            array(
                'LAST_NAME' => preg_replace("/\ .+/", "", $employee),
                'NAME' => stristr($employee, " "),
                'PERSONAL_BIRTHDAY_1' => $dateBirth,
                'PERSONAL_BIRTHDAY_2' => $dateBirth,
                'ACTIVE' => 'Y'
            )
        );
		$arUser = $userData->Fetch();
			if($arUser){
				$cnt = CIBlockElement::GetList(
					array(),
					array(
						'IBLOCK_ID' => 72,
						'NAME' => '%(AR-002)%'
					),
					array(),
					false,
					array('ID', 'NAME')
				); 
				 $cnt = $cnt + '1';

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
				   377,
					array("lists", "BizprocDocument", $documentId),
					array_merge(array("userid"=>"user_".$arUser['ID'], "date"=>$date, "department"=>$department, "workPosition"=>$workPosition, "workType"=>$workType, "cnt"=>$cnt), array("TargetUser" => "user_".intval($GLOBALS["USER"]->GetID()),
					CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
					CBPDocumentEventType::Manual)),
					$arErrorsTmp
				   );
			}
			if(empty($arUser)){
				$userDataNotFound = CUser::GetList(($by="ID"), ($order="ASC"),
					array(
						'LAST_NAME' => preg_replace("/\ .+/", "", $employee),
						'NAME' => stristr($employee, " "),
					)
				);
				$arUserNotFound = $userDataNotFound->Fetch();
				if($arUserNotFound){
					$cnt = CIBlockElement::GetList(
						array(),
						array(
							'IBLOCK_ID' => 72,
							'NAME' => '%(AR-003)%'
						),
						array(),
						false,
						array('ID', 'NAME')
					); 
					$cnt = $cnt + '1';

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
					   378,
						array("lists", "BizprocDocument", $documentId),
						array_merge(array("userid"=>$employee, "date"=>$date, "department"=>$department, "workPosition"=>$workPosition, "workType"=>$workType, "cnt"=>$cnt), array("TargetUser" => "user_".intval($GLOBALS["USER"]->GetID()),
						CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
						CBPDocumentEventType::Manual)),
						$arErrorsTmp
					   );
				}
				$file = "/home/bitrix/mnt/ahs-nfs001/AHS-Департамент управления персоналом-УК_Общий/Уволенные сотрудники.txt";
				$text = $employee . " - " . $department  . " - " . $workPosition . " - " . $workType . "\r\n";
				$fp = fopen($file, "a+");
				fwrite($fp, $text);
				fclose($fp);
			}
}	 
?>
