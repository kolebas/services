
<?
	require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
	?>
<?
$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
$id = 95;

//Добавление заказа
if(isset($data[arr_order])){
	$name = $data[arr_order][0][ID];
	
	$el = new CIBlockElement;
    $iblock_id = $id;
    $section_id = false;
    //$section_id[$i] = $_POST['section_id']; //Разделы для добавления

    //Свойства
    $PROP = array();
	$PROP[670] = $tara;
	$PROP[671] = $price;
	//
	$fields = array(
        "NAME" => $name,
        "CREATED_BY" => $GLOBALS['USER']->GetID(),//Передаем ID пользователя кто добавляет
		"IBLOCK_SECTION" => 857, //Раздел
        "IBLOCK_ID" => $iblock_id, //ID информационного блока 
        "PROPERTY_VALUES" => $PROP, // Передаем массив значении для свойств
    );
    $order_id = $el->Add($fields);

	$UPD_PROP =array();
    $UPD_PROP[679] = $order_id;

    $el->Update($order_id,array("PROPERTY_VALUES" => $UPD_PROP));
    //echo $order_id;

    //Доавление записи в промежуточный инфоблок для связи заказа и номенклатуры
    foreach ($data[arr_order] as $key => $value) {
    	if(isset($value[VALUE])){
    		echo $value[ID] ."<br>";
    		$PROP[674] = $value[ID];
			$PROP[675] = $value[VALUE];
			$PROP[679] = $order_id;
    		$fields = array(
		        "NAME" => $order_id,
		        "CREATED_BY" => $GLOBALS['USER']->GetID(),//Передаем ID пользователя кто добавляет
				"IBLOCK_SECTION" => 861, //Раздел
		        "IBLOCK_ID" => $iblock_id, //ID информационного блока 
		        "PROPERTY_VALUES" => $PROP, // Передаем массив значении для свойств
		    );
		    $el->Add($fields);
    	}    	
    }	
}
//Добавление номеклатуры
if(isset($data["name"])){
	$name = $data["name"];
	$price = $data["price"];
	$tara = $data["tara"];
	
	$cnt = CIBlockElement::GetList(
		array(),
		array('IBLOCK_ID' => $id),
		array(),
		false,
		array('ID', 'NAME')
	); 
	$cnt = $cnt + '1';
	echo $cnt;
	
	$el = new CIBlockElement;
    $iblock_id = $id;
    $section_id = false;
    $section_id[$i] = $_POST['section_id']; //Разделы для добавления

    //Свойства
    $PROP = array();
	$PROP[677] = $tara;
	$PROP[676] = $price;
	//
	$fields = array(
        "NAME" => $name,
        "CREATED_BY" => $GLOBALS['USER']->GetID(),//Передаем ID пользователя кто добавляет
		"IBLOCK_SECTION" => 856, //Раздел
        "IBLOCK_ID" => $iblock_id, //ID информационного блока 
        "PROPERTY_VALUES" => $PROP, // Передаем массив значении для свойств
    );
	print_r($PROP);
	$item_id = $el->Add($fields);

	$UPD_PROP =array();
    $UPD_PROP[674] = $item_id;

    $el->Update($item_id,array("PROPERTY_VALUES" => $UPD_PROP));
    echo $item_id;	
}
//Удаление номенклатуры 
if(isset($data["type"]) && $data["type"] == "delete"){
	
	$id = $data["id"];
	$el = new CIBlockElement;
    CIBlockElement::Delete($id);
	
} 

//Получение списка заказов
if(isset($_GET["param"])){
		$arFilter = array(
		"IBLOCK_ID" => $id,
		//"SECTION_ID" => $_GET["param"],
		"SECTION_ID" => 857,
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
			   $orders[] = array('CREATED_BY' => $el['CREATED_USER_NAME'], 'ID' => $el['ID'], 'ORDER_ID' => $PROPS[ORDER_ID], 'DATE' => $el['DATE_CREATE']);
		endwhile;
	echo json_encode($orders);		
	}
if(isset($_GET["param1"])){
		$arFilter = array(
		"IBLOCK_ID" => $id,
		//"SECTION_ID" => 857,
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
			   $products[] = array('CREATED_BY' => $el['CREATED_USER_NAME'], 'ID' => $el['ID'], 'ORDER_ID' => $PROPS[ORDER_ID], 'DATE' => $el['DATE_CREATE'], 'PRODUCT_NAME' => $PROPS[PRODUCT_NAME],'PRICE' => $PROPS[PRICE], 'TARA' => $PROPS[TARA], 'VALUE' => $PROPS[ITEM_VALUE], 'PRODUCT_ID' => $PROPS[ITEM_ID], 'ORDER' => $PROPS[ORDER]);
		endwhile;
	echo json_encode($products);		
	}

if(isset($_GET["test"])) {
	//Фильтр по принципу "Информационный блок с ID - $id"
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
				//$PROPS = array('NAME' => $el['NAME']);
				while($ar_props = $db_props->Fetch()):
					if($ar_props[VALUE] != NULL){						
					   $PROPS[$ar_props[CODE]] = $ar_props[VALUE];
					}
				endwhile;
				//По элементу информационного блока выведем значение свойства
			   $item[] = array('CREATED_BY' => $el['CREATED_USER_NAME'], 'ID' => $el['ID'], 'ORDER_ID' => $PROPS[ORDER_ID], 'DATE' => $el['DATE_CREATE'], 'PRODUCT_NAME' => $PROPS[PRODUCT_NAME],'PRICE' => $PROPS[PRICE], 'TARA' => $PROPS[TARA], 'VALUE' => $PROPS[ITEM_VALUE], 'PRODUCT_ID' => $PROPS[ITEM_ID]);
		endwhile;
	echo json_encode($item);		
	}	
?>