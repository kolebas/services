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
    
    if(isset($_GET["getItems"])){
        $id=101;
        $arFilter = array(
        "IBLOCK_ID" => $id,
        "ACTIVE" => "Y"
        );
        //Получим массив всех элементов информационного блока
        $res = CIBlockElement::GetList(Array('NAME'=>'ASC'), $arFilter);
        //Переберём все элементы информационного блока из запишем их ID
        while ($el = $res -> GetNext()):
        $arElementID[] = $el["ID"];
        //Получим свойства элемента инфоблока
        $db_props = CIBlockElement::GetProperty($id,$el["ID"], array());
        $PROPS = array();
        while($ar_props = $db_props->Fetch()):
        if($ar_props[VALUE] != NULL){                       
            $PROPS[$ar_props[CODE]] = $ar_props[VALUE];
            //Получим ФИО отвественного
			$oUserinfo = CUser::GetByID($PROPS['OTVESTVENNYY']);
			$rs = $oUserinfo->getNext();
            $responsibleID = $rs["ID"];		
			$responsible = $rs["LAST_NAME"] ." ". $rs["NAME"];
            $photo = $rs["PERSONAL_PHOTO"];

        }

        endwhile;
        //По элементу информационного блока выведем значение свойства
        $get_result[] = array('CREATED_BY' => $el['CREATED_USER_NAME'], 'ID' => $el['ID'], 'DATE' => $el['DATE_CREATE'], 'NAME' => $el['NAME'], 'STATUS' => $PROPS['STATUS'], 'RESPONSIBLEID' => $responsibleID, 'NAZVANIE_ORGANIZATSII' => $PROPS['NAZVANIE_ORGANIZATSII'], 'TASK' => $PROPS['ZADACHA'], 'RESPONSIBLE' => $responsible, 'PHOTO' => CFile::GetPath($photo));
        endwhile;
        echo json_encode($get_result);      
    }
    if(isset($_GET["getTask"])){
        $id=101;
        $arFilter = array(
        "IBLOCK_ID" => $id,
        "ACTIVE" => "Y",
        "ID" => $_GET["getTask"]
        );
        //Получим массив всех элементов информационного блока
        $res = CIBlockElement::GetList(Array('NAME'=>'ASC'), $arFilter);
        //Переберём все элементы информационного блока из запишем их ID
        while ($el = $res -> GetNext()):
        $arElementID[] = $el["ID"];
        //Получим свойства элемента инфоблока
        $db_props = CIBlockElement::GetProperty($id,$el["ID"], array());
        $PROPS = array();
            while($ar_props = $db_props->Fetch()):
            if($ar_props[VALUE] != NULL){                       
                $PROPS[$ar_props[CODE]] = $ar_props[VALUE];
                if ($ar_props[CODE] == 'FAYL'){
                    //Работа с полем содержащим файлы
                    $file = CFile::GetByID($PROPS['FAYL']);
                    $file_value = $file -> Fetch();
                    $file_name = $file_value['FILE_NAME'];
                    $file_path = CFile::GetPath($PROPS['FAYL']);
                    $file_attach[] = array('path' => $file_path, 'name' => $file_name, 'props' => $PROPS['FAYL'] );
                }
            }
            endwhile;
        
        
            
        //По элементу информационного блока выведем значение свойства
        $get_result[] = array('CREATED_BY' => $el['CREATED_USER_NAME'], 'DATE' => $el['DATE_CREATE'], 'NAME' => $el['NAME'], 'STATUS' => $PROPS['STATUS'], 'OTVESTVENNYY' => $PROPS['OTVESTVENNYY'], 'NAZVANIE_ORGANIZATSII' => $PROPS['NAZVANIE_ORGANIZATSII'], 'KONTAKTNOE_LITSO' => $PROPS['KONTAKTNOE_LITSO'], 'TEMA_OPYTA' => $PROPS['TEMA_OPYTA'], 'TSELI_OPYTA' => $PROPS['TSELI_OPYTA'], 'ZADACHI_OPYTA' => $PROPS['ZADACHI_OPYTA'], 'OBEKT_OPYTA' => $PROPS['OBEKT_OPYTA'], 'EKONOMICHESKAYA_EFFEKTIVNOST' => $PROPS['EKONOMICHESKAYA_EFFEKTIVNOST'], 'REKVIZITY_ORGANIZATSII' => $PROPS['REKVIZITY_ORGANIZATSII'], 'FILE' => $file_attach, 'KONTAKTNAYA_INFORMATSIYA' => $PROPS['KONTAKTNAYA_INFORMATSIYA'], 'ZADACHA' => $PROPS['ZADACHA'], 'LOG' => $PROPS['ISTORIYA_ZAYAVKI']);
        endwhile;
        echo json_encode($get_result);      
    }
?>