<?
require_once($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php');
$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
//Получение списка номенклатуры для магазина
if ($_GET[type] == "getStoreItems") {
    $id = $_GET[id];
    $sectionID = $_GET[sectionID];

    $arFilter = array(
        "IBLOCK_ID" => $id,
        "ACTIVE" => "Y",
        "IBLOCK_SECTION_ID" => array($sectionID, $_GET[sectionId]),
    );
    //Получим массив всех элементов информационного блока
    $res = CIBlockElement::GetList(array('NAME' => 'ASC'), $arFilter);
    $get_result = array();
    while ($el = $res->GetNext()):
      $db_props = CIBlockElement::GetProperty($id, $el["ID"], array());
            $PROPS = array();
      while ($ar_props = $db_props->Fetch()):
                if ($ar_props[VALUE] != NULL) {
                    $PROPS[$ar_props[CODE]] = $ar_props[VALUE];
                }
        endwhile;
        $img = CFile::GetPath($PROPS['IMG']);
        $get_result[] = array("id" => $el['ID'], "text" => $el['NAME'], "price" => $PROPS['PRICE'], "tara" => $PROPS['TARA'],"img" => $img);
  endwhile;
    echo json_encode($get_result);
}
//Получение списка заказов
if ($_GET[type] == "getStoreOrders") {
    $id = $_GET[id];
    $sectionID = $_GET[sectionID];

    $arFilter = array(
        "IBLOCK_ID" => $id,
        "ACTIVE" => "Y",
        "IBLOCK_SECTION_ID" => array($sectionID, $_GET[sectionId]),
    );
    //Получим массив всех элементов информационного блока
    $res = CIBlockElement::GetList(array('DATE_CREATE' => 'DESC'), $arFilter);
    $get_result = array();
    while ($el = $res->GetNext()):
        $value = array();
        $db_props = CIBlockElement::GetProperty($id, $el["ID"], array());
            $PROPS = array();
        while ($ar_props = $db_props->Fetch()):
                if ($ar_props[VALUE] != NULL) {
                    $PROPS[$ar_props[CODE]] = $ar_props[VALUE];
                    if ($ar_props[CODE] == 'ITEMS')
                    {                        
                        array_push($value, $ar_props[VALUE]);
                    }
                }
        endwhile;
        $get_result[] = array("ID" => $el['ID'], "NUMBER" => $el['NAME'], "DATE" => $el['DATE_CREATE'], "USER" => $el['CREATED_USER_NAME'], "ORDER" => $value, "ORDER_STATUS" => $PROPS['ORDER_STATUS']);        
    endwhile;
    echo json_encode($get_result);
}
//Добавление заказа
 if ($data['type'] == 'addOrder') {

      $id = 95; 
      $section = 857;

      $el = new CIBlockElement;

      foreach ($data['items'] as $orderItems) {
        foreach ($orderItems as $key => $value) {
          if ($key != 'id') {
            if ($key == 'text') {
              $text = $value;
            }
            if ($key == 'value') {
              $newOrderItems[] = array('VALUE' => $text." --- ".$value);
            }
          };
        }
      }
      print_r($newOrderItems);

      $PROP = array();
      $PROP[807] = $newOrderItems;
      $PROP[808] = 'Новый';

      $orderName = 'Заказ №'.getElementCount ($id, $section);


      $arLoadProductArray = Array(
        "MODIFIED_BY"    => $USER->GetID(),
        "IBLOCK_SECTION_ID" => $section,
        "IBLOCK_ID"      => $id,
        "PROPERTY_VALUES"=> $PROP,
        "NAME"           => $orderName,
        "ACTIVE"         => "Y",
        );

      if($PRODUCT_ID = $el->Add($arLoadProductArray))
        sendEmail ($orderName, "<a href='https://portal.ahstep.ru/company/personal/user/".$USER->GetID()."/'>".$USER->GetFullName()."</a>");
      else
        echo "Error: ".$el->LAST_ERROR;
       $arErrorsTmp = array();              
  }
  print_r($data['data']['type']);
//Oбновление статуса заказа
if ($data['data']['type'] == 'updateStoreOrders') {

  $id = 95; 
  $section = 857;

  print_r($data['data']['order']);

  $el = new CIBlockElement;


  $PROP = array();
  $PROP[807] = $data['data']['order']['ORDER'];
  $PROP[808] = $data['data']['order']['ORDER_STATUS'];


  $arLoadProductArray = Array(
    "MODIFIED_BY"    => $USER->GetID(),
    "IBLOCK_SECTION_ID" => $section,
    "IBLOCK_ID"      => $id,
    "PROPERTY_VALUES"=> $PROP,
    );

  if($PRODUCT_ID = $el->update($data['data']['order']['ID'], $arLoadProductArray))
    echo "<br>Update ID: ".$PRODUCT_ID;
  else
    echo "Error: ".$el->LAST_ERROR;
   $arErrorsTmp = array();              
}

  function getElementCount ($id, $section)
  {
    $cnt = CIBlockElement::GetList(
        array(),
        array('IBLOCK_ID' => $id, "IBLOCK_SECTION_ID" => $section),
        array(),
        false,
        array('ID', 'NAME')
    ); 
     $cnt = $cnt + '1';

     return $cnt;
  }

  function sendEmail ($subject, $user)
  {
    $to      = 'Zavorueva.II@ahstep.ru';
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
     
    // Create email headers
    $headers .= 'From: '.$from."\r\n".
        'Reply-To: '.$from."\r\n" .
        'X-Mailer: PHP/' . phpversion();
     
    // Compose a simple HTML email message
    $message = '<html><body>';
    //$message .= '<h1 style="color:#f40;">Hi Jane!</h1>';
    $message .= '<p style="font-size:18px;">Пользователь ';
    $message .= $user;
    $message .= ' добавил новый заказ на молочную продукцию.</p>';
    $message .= 'Более подробная информация находится в консоли управления <a href="https://portal.ahstep.ru/ahstep/services/storeadmin"> заказами</a></body></html>';

    mail($to, $subject, $message, $headers);
  }



