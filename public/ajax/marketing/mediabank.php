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

    //echo "You have CORS!";
?>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 
$postData = file_get_contents('php://input');
	$data = json_decode($postData, true);
  if ($data['type'] == 1) {
      $fio = $data['fio'];
      $tel = $data['tel'];
      $address = $data['address'];
      $id = 98;
      $items = [];
        foreach ($data['items'] as &$value) {
            array_push($items, $value[text]." ". $value[value]);
            echo $value[text];
        }

    unset($value);
     
     CModule::IncludeModule('bizproc');
      $documentId = CBPVirtualDocument::CreateDocument(
        0,
        array(
          "IBLOCK_ID" => $id,
          "NAME" => getElementCount ($id, $section),
          "CREATED_BY" => "user_".$GLOBALS["USER"]->GetID(),
        )
       );

       $arErrorsTmp = array();

       $wfId = CBPDocument::StartWorkflow(
       364,
        array("lists", "BizprocDocument", $documentId),
        array_merge(array("ITEMS"=>$items, "FIO"=>"$fio", "TEL"=>"$tel", "ADDRESS"=>"$address", "CNT"=>$cnt), array("TargetUser" => "user_".intval($GLOBALS["USER"]->GetID()),
        CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
        CBPDocumentEventType::Manual)),
          $arErrorsTmp
       );
  }
  if ($data['type'] == 2) {

      $id = 95; 
      $section = 857;

      //$orderItems = $data['items'];

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
       echo "<br>";
      print_r($newOrderItems);

      $PROP = array();
      $PROP[807] = $newOrderItems;
      $PROP[808] = 'Новый';


      $arLoadProductArray = Array(
        "MODIFIED_BY"    => $USER->GetID(),
        "IBLOCK_SECTION_ID" => $section,
        "IBLOCK_ID"      => $id,
        "PROPERTY_VALUES"=> $PROP,
        "NAME"           => 'Заказ №'.getElementCount ($id, $section),
        "ACTIVE"         => "Y",
        );

      if($PRODUCT_ID = $el->Add($arLoadProductArray))
        echo "<br>New ID: ".$PRODUCT_ID;
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
	
?>