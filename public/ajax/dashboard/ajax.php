<?
	require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
	
	if(isset($_GET["getVesy"])){
		$id=97;
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
		}
		endwhile;
		//По элементу информационного блока выведем значение свойства
		$get_result[] = array('CREATED_BY' => $el['CREATED_USER_NAME'], 'ID' => $el['ID'], 'DATE' => $el['DATE_CREATE'], 'CODE' => $PROPS[CODE], 'NAME_UZ' => $PROPS[NAME_UZ], 'COMPANY' => $PROPS[COMPANY], 'ADD_NAME' => $PROPS[ADD_NAME], 'PC_NAME' => $PROPS[PC_NAME], 'CONTACT_IT' => $PROPS[CONTACT_IT], 'CONTACT_VESY' => $PROPS[CONTACT_VESY], 'PASS_WIN' => $PROPS[PASS_WIN], 'ID_TEAM' => $PROPS[ID_TEAM], 'PASS_TEAM' => $PROPS[PASS_TEAM], 'IP_MSRA' => $PROPS[IP_MSRA], 'ZBX_STATUS' => $PROPS[ZBX_STATUS], 'ADDRESS' => $PROPS[ADDRESS], 'VESY_1C' => $PROPS[VESY_1C], 'MODEL_TERM' => $PROPS[MODEL_TERM], 'ZAZEML_STATUS' => $PROPS[ZAZEML_STATUS], 'MODEL_CAMERA' => $PROPS[MODEL_CAMERA], 'DRIVER' => $PROPS[DRIVER]);
		endwhile;
		echo json_encode($get_result);		
	}
	
	if(isset($_GET["result"])){
		if($_GET["result"] == "req"){
			$id = 64;
		}
		if($_GET["result"] == "inc"){
			$id = 88;
			//Список задач
			/*$id = "inc";
				CModule::IncludeModule('Tasks');			
				$task = new \Bitrix\Tasks\Item\Task();
				
				$afFilter = array(
				"GROUP_ID" => "4",
				"ID" => "15667"
				#"RESPONSIBLE_ID" => "2459"
				);
				
				$rsTask = CTasks::GetList(array(), $afFilter, array("*"), array("*"));
				while($arTask = $rsTask->GetNext()){
				$get_result[] = array('TASK_ID' => $arTask['ID'],'NAME' => $arTask['TITLE'], 'DATE' => $arTask['CREATED_DATE'], 'STATUS' => $arTask['REAL_STATUS'], 'CREATED_BY' => $arTask['CREATED_BY_LAST_NAME']." ".$arTask['CREATED_BY_NAME'], 'RESPONSIBLE' => $arTask['RESPONSIBLE_LAST_NAME']." ".$arTask['RESPONSIBLE_NAME'], 'TYPE' => $arTask['FIELD_NAME'], 'DESCRIPTION' => $arTask['DESCRIPTION']);
			};*/
		}
		if($_GET["result"] == "vesy"){
			$id = 97;
		}
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
		}
		endwhile;
		//По элементу информационного блока выведем значение свойства
		if($id == 64){
			$get_result[] = array('CREATED_BY' => $el['CREATED_USER_NAME'], 'ID' => $el['ID'], 'DATE' => $el['DATE_CREATE'], 'POLUCHATEL' => $PROPS[POLUCHATEL], 'NAME' => $PROPS[NAIMENOVANIE_USLUGI], 'STATUS' => $PROPS[STATUS][TEXT], 'HYSTORY' => $PROPS[ISTORIYA_ZAYAVKI], 'TASK_ID' => substr($PROPS[ZADACHA][TEXT], -10, 5), 'LOG' => $PROPS[ZHURNAL_VYPOLNENIYA_ZAYAVKI]);
		}
		if($id == 88){
			/*$get_result[] = array('CREATED_BY' => $el['CREATED_USER_NAME'], 'ID' => $el['ID'], 'NAME' => $el['NAME'], 'DATE' => $el['DATE_CREATE'], 'HYSTORY' => $PROPS[ISTORIYA_ZAYAVKI], 'TASK_ID' => $PROPS[ZADACHA][TEXT]);*/
			CModule::IncludeModule('Tasks');			
				$task = new \Bitrix\Tasks\Item\Task();
				
				$afFilter = array(
				"ID" => substr($PROPS[ZADACHA][TEXT], -9, 5)
				);
				
				$rsTask = CTasks::GetList(array(), $afFilter, array("*"), array("*"));
					while($arTask = $rsTask->GetNext()){
						$get_result[] = array('TASK_ID' => $arTask['ID'],'NAME' => $arTask['TITLE'], 'DATE' => $arTask['CREATED_DATE'], 'STATUS' => $arTask['REAL_STATUS'], 'CREATED_BY' => $arTask['CREATED_BY_LAST_NAME']." ".$arTask['CREATED_BY_NAME'], 'RESPONSIBLE' => $arTask['RESPONSIBLE_LAST_NAME']." ".$arTask['RESPONSIBLE_NAME'], 'TYPE' => $arTask['FIELD_NAME'], 'DESCRIPTION' => $arTask['DESCRIPTION']);
					}
		}
		if($id == 97){
			$get_result[] = array('CREATED_BY' => $el['CREATED_USER_NAME'], 'ID' => $el['ID'], 'DATE' => $el['DATE_CREATE'], 'CODE' => $PROPS[CODE], 'NAME_UZ' => $PROPS[NAME_UZ], 'COMPANY' => $PROPS[COMPANY], 'ADD_NAME' => $PROPS[ADD_NAME], 'PC_NAME' => $PROPS[PC_NAME], 'CONTACT_IT' => $PROPS[CONTACT_IT], 'CONTACT_VESY' => $PROPS[CONTACT_VESY], 'PASS_WIN' => $PROPS[PASS_WIN], 'ID_TEAM' => $PROPS[ID_TEAM], 'PASS_TEAM' => $PROPS[PASS_TEAM], 'IP_MSRA' => $PROPS[IP_MSRA], 'ZBX_STATUS' => $PROPS[ZBX_STATUS], 'ADDRESS' => $PROPS[ADDRESS], 'VESY_1C' => $PROPS[VESY_1C], 'MODEL_TERM' => $PROPS[MODEL_TERM], 'ZAZEML_STATUS' => $PROPS[ZAZEML_STATUS], 'MODEL_CAMERA' => $PROPS[MODEL_CAMERA], 'DRIVER' => $PROPS[DRIVER]);
		}
		endwhile;
		echo json_encode($get_result);
	}
	
	$postData = file_get_contents('php://input');
	$data = json_decode($postData, true); 
	
	print_r($data);
	
	//Добавление заказа
	
	$name = $data[0][name];
	
	$el = new CIBlockElement;
    $iblock_id = $id;
    $section_id = false;
	
    //Свойства
    $PROP = array();
	$PROP[683] = $data[0][value];
	$PROP[684] = $data[1][value];
	$PROP[685] = $data[2][value];
	$PROP[686] = $data[3][value];
	$PROP[687] = $data[4][value];
	$PROP[688] = $data[5][value];
	$PROP[689] = $data[6][value];
	$PROP[690] = $data[7][value];
	$PROP[691] = $data[8][value];
	$PROP[692] = $data[9][value];
	//
	$fields = array(
	"NAME" => $name,
	"CREATED_BY" => $GLOBALS['USER']->GetID(),//Передаем ID пользователя кто добавляет
	"IBLOCK_ID" => $iblock_id, //ID информационного блока 
	"PROPERTY_VALUES" => $PROP, // Передаем массив значении для свойств
    );
    $order_id = $el->Add($fields);
	
?>