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
						'IBLOCK_ID' => 118,
						'NAME' => '%(CU-002)%'
					),
					array(),
					false,
					array('ID', 'NAME')
				); 
				$cnt = $cnt + '1';
				#Добавление элемента инфоблока
				$el = new CIBlockElement;

				$PROP = array();
				$PROP[879] = $employee;
				$PROP[880] = $workPosition;

				$arLoadDocumentArray = Array(
				  "MODIFIED_BY"    => $USER->GetID(),
				  "IBLOCK_SECTION_ID" => false,
				  "IBLOCK_ID"      => 118,
				  "PROPERTY_VALUES"=> $PROP,
				  "NAME"           => "(CU-002)",
				  "ACTIVE"         => "Y",            // активен
				  );

				if($DOCUMENT_ID = $el->Add($arLoadDocumentArray)){
					echo $cnt;
					$wfId = CBPDocument::StartWorkflow(
					   431,
						array("lists", "BizprocDocument", $DOCUMENT_ID),
						array_merge(array("userid"=>"user_".$USER->GetID()), array("TargetUser" => "user_".intval($GLOBALS["USER"]->GetID()),
						CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
						CBPDocumentEventType::Manual)),
						$arErrorsTmp
					   );
				}
				else{
				  echo "Error: ".$el->LAST_ERROR;
				}
			}
			if(empty($arUser)){
				$file = "/home/bitrix/mnt/ahs-nfs001/AHS-Департамент управления персоналом-УК_Общий/Измененные сотрудники.txt";
				$text = $employee . " - " . $department  . " - " . $workPosition . " - " . $workType . "\r\n";
				$fp = fopen($file, "a+");
				fwrite($fp, $text);
				fclose($fp);
			}
}	 
?>
