<?
require_once($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php');

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

//Добавление заявки на доступ в БД 1С
if ($data[type] == "1c001") {

    $org = $data[input][7]['value'];
    $temptime = $data[input][1]['value'];
    $db = $data[input][2]['value'];
    $permision = $data[input][3]['value'];
    $access = $data[input][4]['value'];
    $cmnt = $data[input][5]['value'];
    $user = $data[input][6]['value'];


    $cnt = CIBlockElement::GetList(
        array(),
        array('IBLOCK_ID' => 54),
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
            "IBLOCK_ID" => 54,
            "NAME" => $cnt,
            "CREATED_BY" => "user_" . $GLOBALS["USER"]->GetID(),
        )
    );

    $arErrorsTmp = array();

    $wfId = CBPDocument::StartWorkflow(
        111,
        array("lists", "BizprocDocument", $documentId),
        array_merge(array("temptime" => $temptime, "db" => $db, "permision" => $permision, "access" => $access, "cmnt" => $cmnt, "user" => $user, "org" => $org, "cnt" => $cnt), array("TargetUser" => "user_" . intval($GLOBALS["USER"]->GetID()),
            CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
            CBPDocumentEventType::Manual)),
        $arErrorsTmp
    );
}
//Получение списка БД 1С
if ($_GET[type] == "getDB") {
    $id = 70;
    if (!isset($_GET[sectionId])) {
        $sectionID = 877;
    }
    $arFilter = array(
        "IBLOCK_ID" => $id,
        "ACTIVE" => "Y",
        "IBLOCK_SECTION_ID" => array($sectionID, $_GET[sectionId]),
    );
    //Получим массив всех элементов информационного блока
    $res = CIBlockElement::GetList(array('NAME' => 'ASC'), $arFilter);
    $get_result = array();
    //Переберём все элементы информационного блока из запишем их ID
    while ($el = $res->GetNext()):
        $arElementID[] = $el["ID"];
        if ($_GET[getDB] == "getDbList") {
            $db_props = CIBlockElement::GetProperty($id, $el["ID"], array());
            $PROPS = array();
            while ($ar_props = $db_props->Fetch()):
                if ($ar_props[VALUE] != NULL) {
                    $PROPS[$ar_props[CODE]] = $ar_props[VALUE];
                }

            endwhile;
            $get_result[] = array("ID" => $el['ID'], "NAME" => $el['NAME'], "VID" => $PROPS["VID_BAZY"], "TYPE" => $PROPS["TIP_BAZY"], "CLUSTER" => $PROPS["KLASTER_SERVEROV"], "NAME_DB" => $PROPS["STATUS"], "ADDRESS_DB" => $PROPS["ADRES_IB"], "KATALOG_IB" => $PROPS["KATALOG_IB"], "USER" => $PROPS["POLZOVATEL"], "LOGIN" => $PROPS["LOGIN"], "CONFIG" => $PROPS["KONFIGURATSIYA"], "RELEASE" => $PROPS["RELIZ"], "LICENSE" => $PROPS["LITSENZIYA"], "COMMENT" => $PROPS["COMMENT"],);
        } else if ($_GET[sectionId] == 877) {
            $db_props = CIBlockElement::GetProperty($id, $el["ID"], array());
            $PROPS = array();
            while ($ar_props = $db_props->Fetch()):
                if ($ar_props[VALUE] != NULL) {
                    $PROPS[$ar_props[CODE]] = $ar_props[VALUE];
                }

            endwhile;
            $get_result[] = array("ID" => $el['ID'], "NAME" => $el['NAME'], "VID" => $PROPS["VID_BAZY"], "TYPE" => $PROPS["TIP_BAZY"], "CLUSTER" => $PROPS["KLASTER_SERVEROV"], "ADDRESS_DB" => $PROPS["ADRES_IB"], "KATALOG_IB" => $PROPS["KATALOG_IB"], "USER" => $PROPS["POLZOVATEL"], "LOGIN" => $PROPS["LOGIN"], "CONFIG" => $PROPS["KONFIGURATSIYA"], "RELEASE" => $PROPS["RELIZ"], "LICENSE" => $PROPS["LITSENZIYA"], "COMMENT" => $PROPS["COMMENT"],);
        } else if ($_GET[sectionId] == 875) {
            $db_props = CIBlockElement::GetProperty($id, $el["ID"], array());
            $PROPS = array();
            while ($ar_props = $db_props->Fetch()):
                if ($ar_props[VALUE] != NULL) {
                    $PROPS[$ar_props[CODE]] = $ar_props[VALUE];
                }

            endwhile;
            $get_result[] = array("ID" => $el['ID'], "NAME" => $el['NAME'], "TYPE" => $PROPS["LIC_TIP_LITSENZII"], "VID" => $PROPS["LIC_VID_LITSENZII"], "LICENSE" => $PROPS["LIC_OSNOVNAYA_LITSENZIYA"], "NUMBER" => $PROPS["LIC_KOL_VO_RABOCHIKH_MEST"], "ORG" => $PROPS["LIC_YUR_LITSO_VLADELETS"], "DATE_BUY" => $PROPS["LIC_DATA_PRIOBRETENIYA"], "LOCATION" => $PROPS["LIC_MESTO_AKTIVATSII"], "CONTRACT" => $PROPS["LOC_DEYSTVUYUSHCHIY_DOGOVOR_ITS"]);
        } else if ($_GET[sectionId] == 876) {
            $db_props = CIBlockElement::GetProperty($id, $el["ID"], array());
            $PROPS = array();
            while ($ar_props = $db_props->Fetch()):
                if ($ar_props[VALUE] != NULL) {
                    $PROPS[$ar_props[CODE]] = $ar_props[VALUE];
                }

            endwhile;
            $get_result[] = array("ID" => $el['ID'], "NAME" => $el['NAME'], "ORG" => $PROPS["CONT_YUR_LITSO"], "AGENT" => $PROPS["CONT_AGENT"], "DOGOVOR_TYPE" => $PROPS["CONT_TIP_DOGOVORA"], "DOGOVOR" => $PROPS["CONT_OSNOVNOY_DOGOVOR"], "VLADELEC" => $PROPS["CONT_VLADELETS"], "DATE_START" => $PROPS["CONT_DATA_NACHALA"], "DATE_END" => $PROPS["CONT_DATA_OKONCHANIYA"]);
        } else {
            array_push($get_result, $el["NAME"]);
        }
    endwhile;
    echo json_encode($get_result);
}

//Получение сведений о пользователях которым предоставлен доступ к базам 1С
if ($_GET[type] == "getUsers") {
	$id = 54;
    $arFilter = array(
        "IBLOCK_ID" => $id,
        "ACTIVE" => "Y",
        "IBLOCK_SECTION_ID" => array($sectionID, $_GET[sectionId]),
    );
    $res = CIBlockElement::GetList(array('NAME' => 'ASC'), $arFilter);
    $get_result = array();
    while ($el = $res->GetNext()):
        $arElementID[] = $el["ID"];
        $db_props = CIBlockElement::GetProperty($id, $el["ID"], array());
            $PROPS = array();
            while ($ar_props = $db_props->Fetch()):
                if ($ar_props[VALUE] != NULL) {
                    $PROPS[$ar_props[CODE]] = $ar_props[VALUE];
                }

            endwhile;
            if ($PROPS['ZADACHI'] != null){
                $rsUser = CUser::GetByID($PROPS['FIO_SOTRUDNIKA']);
                $arUser = $rsUser->Fetch();
            	$get_result[] = array("ID" => $el['ID'], "USER" => $arUser['LAST_NAME'] . " " . $arUser['NAME'], "DATE_CONNECT" => $el['CREATED_DATE'], "TASK_CONNECT" => $PROPS['ZADACHI']['TEXT'], "DATE_DISCONNECT" => $PROPS['NAME'], "TASK_DISCONNECT" => $PROPS['NAME']);
            }
        
    endwhile;
    echo json_encode($get_result);
}

//Добавление элементов справочники dashboard	
//Добавляем новую БД 1С	
if ($data[type] == "addDB") {
    print_r($data);
    $db = $data[item];
    $el = new CIBlockElement;

    $PROP = array();
    $PROP[778] = $data[item][2]['value'];
    $PROP[789] = $data[item][3]['value'];
    $PROP[780] = $data[item][4]['value'];
    $PROP[781] = $data[item][5]['value'];
    $PROP[782] = $data[item][6]['value'];
    $PROP[783] = $data[item][7]['value'];
    $PROP[784] = $data[item][8]['value'];
    $PROP[785] = $data[item][9]['value'];
    $PROP[786] = $data[item][10]['value'];
    $PROP[787] = $data[item][11]['value'];
    $PROP[788] = $data[item][12]['value'];
    //
    $fields = array(
        "NAME" => $data[item][1]['value'],
        "CREATED_BY" => $GLOBALS['USER']->GetID(),//Передаем ID пользователя кто добавляет
        "IBLOCK_ID" => 70, //ID информационного блока 
        "IBLOCK_SECTION_ID" => 877, //Раздел
        "PROPERTY_VALUES" => $PROP, // Передаем массив значении для свойств
    );
    $order_id = $el->Add($fields);
}
//Добавляем новую лицензию	
if ($data[type] == "addLic") {
    print_r($data);
    $db = $data[item];
    $el = new CIBlockElement;

    $PROP = array();
    $PROP[790] = $data[item][2]['value'];
    $PROP[791] = $data[item][3]['value'];
    $PROP[792] = $data[item][4]['value'];
    $PROP[793] = $data[item][5]['value'];
    $PROP[795] = $data[item][6]['value'];
    $PROP[794] = $data[item][7]['value'];
    $PROP[796] = $data[item][8]['value'];
    $PROP[797] = $data[item][9]['value'];

    //
    $fields = array(
        "NAME" => $data[item][1]['value'],
        "CREATED_BY" => $GLOBALS['USER']->GetID(),//Передаем ID пользователя кто добавляет
        "IBLOCK_ID" => 70, //ID информационного блока 
        "IBLOCK_SECTION_ID" => 875, //Раздел
        "PROPERTY_VALUES" => $PROP, // Передаем массив значении для свойств
    );
    $order_id = $el->Add($fields);
}
//Добавляем новый договор		
if ($data[type] == "addContract") {
    print_r($data);
    $db = $data[item];
    $el = new CIBlockElement;

    $PROP = array();
    $PROP[798] = $data[item][2]['value'];
    $PROP[799] = $data[item][3]['value'];
    $PROP[800] = $data[item][4]['value'];
    $PROP[801] = $data[item][5]['value'];
    $PROP[802] = $data[item][6]['value'];
    $PROP[803] = $data[item][7]['value'];
    $PROP[804] = $data[item][8]['value'];
    //
    $fields = array(
        "NAME" => $data[item][1]['value'],
        "CREATED_BY" => $GLOBALS['USER']->GetID(),//Передаем ID пользователя кто добавляет
        "IBLOCK_ID" => 70, //ID информационного блока 
        "IBLOCK_SECTION_ID" => 876, //Раздел
        "PROPERTY_VALUES" => $PROP, // Передаем массив значении для свойств
    );
    $order_id = $el->Add($fields);
}
if ($data[type] == "updDB") {
    $db = $data[item];
    $id = $data[item][0]['value'];
    $el = new CIBlockElement;
    $PROP = array();
    print_r($data);
    //Обновление лицензии в dashboard
    if ($data[item][1]['name'] == "название лицензии") {
        $sectionIDupd = 875;
        $PROP[790] = $data[item][2]['value'];
        $PROP[791] = $data[item][3]['value'];
        $PROP[792] = $data[item][4]['value'];
        $PROP[793] = $data[item][5]['value'];
        $PROP[794] = $data[item][7]['value'];
        $PROP[795] = $data[item][6]['value'];
        $PROP[796] = $data[item][8]['value'];
        $PROP[797] = $data[item][9]['value'];
    } //Обновление договора в dashboard
    else if ($data[item][1]['name'] == "название договора") {
        $sectionIDupd = 876;
        $PROP[798] = $data[item][2]['value'];
        $PROP[799] = $data[item][3]['value'];
        $PROP[800] = $data[item][4]['value'];
        $PROP[801] = $data[item][5]['value'];
        $PROP[802] = $data[item][6]['value'];
        $PROP[803] = $data[item][7]['value'];
        $PROP[804] = $data[item][8]['value'];
    } //Если не задано другого, то обновляем БД в dashboard
    else {
        $sectionIDupd = 877;
        $PROP[778] = $data[item][2]['value'];
        $PROP[789] = $data[item][3]['value'];
        $PROP[780] = $data[item][4]['value'];
        $PROP[781] = $data[item][5]['value'];
        $PROP[782] = $data[item][6]['value'];
        $PROP[783] = $data[item][7]['value'];
        $PROP[784] = $data[item][8]['value'];
        $PROP[785] = $data[item][9]['value'];
        $PROP[786] = $data[item][10]['value'];
        $PROP[787] = $data[item][11]['value'];
        $PROP[788] = $data[item][12]['value'];
    }
    //
    $fields = array(
        "NAME" => $data[item][1]['value'],
        "CREATED_BY" => $GLOBALS['USER']->GetID(),//Передаем ID пользователя кто добавляет
        "IBLOCK_ID" => 70, //ID информационного блока 
        "IBLOCK_SECTION_ID" => $sectionIDupd, //Раздел
        "PROPERTY_VALUES" => $PROP, // Передаем массив значении для свойств
    );
    $order_id = $el->Update($id, $fields);
}
//Удаление элемента справочника		
if ($data[type] == "remDB") {

    $id = $data[item][ID];
    $el = new CIBlockElement;
    CIBlockElement::Delete($id);
}
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/epilog_after.php');
?>
