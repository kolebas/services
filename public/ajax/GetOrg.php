
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
$org = array();
$id = 67;
	$arFilter = Array(
		'IBLOCK_ID'=>$id
	);
	$res = CIBlockElement::GetList(Array('NAME'=>'ASC'), $arFilter);
	while($el = $res->GetNext()):
		$arElementID[] = $el["ID"];
		$db_props = CIBlockElement::GetProperty($id,$el["ID"], array());
		$PROPS = array();
		while($ar_props = $db_props->Fetch()):
			if($ar_props[VALUE] != NULL){						
				$PROPS[$ar_props[CODE]] = $ar_props[VALUE];
			}
		endwhile;
	$org[] = array('ID' => $el['ID'], 'NAME' => $el['NAME'], 'RDT' => $PROPS[SOKRASHCHENIE]);
	endwhile;
	echo json_encode($org);
?>
