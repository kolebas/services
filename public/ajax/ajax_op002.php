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
            //Получим информацию о задаче
            if($PROPS['ZADACHA'] != ""){
                if (CModule::IncludeModule("tasks")){
                    $taskStatus = NULL;
                    $taskId = $PROPS['ZADACHA'];
                    $userId = $USER->getId();
                    $task = CTaskItem::getInstance($taskId, $userId);
                    if($task['STATUS'] == '-1'){
                        $taskStatus = 'Просрочено';
                    }
                    else{
                        $taskStatus = $PROPS['STATUS'];
                    }                   
                }    
            }            
            else{
                $taskStatus = $PROPS['STATUS'];
            } 
        }

        endwhile;
        //По элементу информационного блока выведем значение свойства
        $get_result[] = array('CREATED_BY' => $el['CREATED_USER_NAME'], 'ID' => $el['ID'], 'DATE' => $el['DATE_CREATE'], 'NAME' => $el['NAME'], 'STATUS' => $taskStatus, 'RESPONSIBLEID' => $responsibleID, 'NAZVANIE_ORGANIZATSII' => $PROPS['NAZVANIE_ORGANIZATSII'], 'TASK' => $PROPS['ZADACHA'], 'RESPONSIBLE' => $responsible, 'PHOTO' => CFile::GetPath($photo));
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