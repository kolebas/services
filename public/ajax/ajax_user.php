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

	$pl_users = array();
		$data = CUser::GetList(($by="LAST_NAME"), ($order="ASC"),
            array(
                'ACTIVE' => 'Y',
                '!UF_DEPARTMENT' => NULL,
                'LAST_NAME' => '%_'
            )
        );
	while($arUser = $data->Fetch()) {
				$pl_users[] = array('ID' => $arUser['ID'], 'NAME' => $arUser['LAST_NAME']. " " .$arUser['NAME'], 'PHOTO' => CFile::GetPath($arUser['PERSONAL_PHOTO']), 'POSITION'=> $arUser['WORK_POSITION']);
			}
	echo json_encode($pl_users);
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/epilog_after.php');
?>
