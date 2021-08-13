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

	$data = $_GET['name'];
    if ($data != null) {
        $pl_users = array();
		$data = CUser::GetList(($by="LAST_NAME"), ($order="ASC"),
            array(
                'ACTIVE' => 'Y',
                '!UF_DEPARTMENT' => NULL,
                'LAST_NAME' => $data,
            )
        );
	    while($arUser = $data->Fetch()) {
				$pl_users[] = array('ID' => $arUser['ID'], 'NAME' => $arUser['LAST_NAME']. " " .$arUser['NAME'], 'PHOTO' => CFile::GetPath($arUser['PERSONAL_PHOTO']), 'POSITION'=> $arUser['WORK_POSITION']);
			}            
	    echo json_encode($pl_users);
    }
	
	$telegram = $_GET['telegram'];
    if ($telegram != null) {
        $pl_users = array();
		$data = CUser::GetList(($by="LAST_NAME"), ($order="ASC"),
            array(
                'ACTIVE' => 'Y',
                '!UF_DEPARTMENT' => NULL,
                'PERSONAL_NOTES' => $telegram,
            )
        );
	    while($arUser = $data->Fetch()) {
				$pl_users[] = array('ID' => $arUser['ID'], 'NAME' => $arUser['LAST_NAME']. " " .$arUser['NAME'], 'PHOTO' => CFile::GetPath($arUser['PERSONAL_PHOTO']), 'POSITION'=> $arUser['WORK_POSITION']);
			}            
	    echo json_encode($pl_users);
    }

    if ($_POST['inc'] != null) {   
        $title_inc = mb_strimwidth($_POST['inc'], 0, 75, '...');
        $body_inc = $_POST['inc'];
        $user_id = $_POST['user_id'];

        $cnt = CIBlockElement::GetList(
            array(),
            array('IBLOCK_ID' => 88),
            array(),
            false,
            array('ID', 'NAME')
        ); 
        $cnt = $cnt + '1';
         
        CModule::IncludeModule('bizproc');
            $documentId = CBPVirtualDocument::CreateDocument(
            0,
            array(
                "IBLOCK_ID" => 88,
                "NAME" => $title_inc,
                "CREATED_BY" => "user_".$user_id,
                )
        );
    
        $arErrorsTmp = array();
    
        $wfId = CBPDocument::StartWorkflow(
            313,
            array("lists", "BizprocDocument", $documentId),
            array_merge(array("author"=>$user_id, "title"=>"[BOT] ".$title_inc, "type_1s"=>$type_1c_slct, "switch_mon"=>$switch_mon, "body"=>$body_inc, "file"=>$files, "file_new"=>$new_path, "cnt"=>$cnt), array("TargetUser" => $user_id,
            CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
            CBPDocumentEventType::Manual)),
            $arErrorsTmp
        );

        $db_props = CIBlockElement::GetProperty(88,$documentId, array());
		$PROPS = array();
		while($ar_props = $db_props->Fetch()):
		if($ar_props[VALUE] != NULL){						
			$PROPS[$ar_props[CODE]] = $ar_props[VALUE];
		}
		endwhile;
		$get_result[] = array('ZADACHA' => $PROPS[ZADACHA][TEXT]);
		echo $get_result[0][ZADACHA];	
    }
?>
