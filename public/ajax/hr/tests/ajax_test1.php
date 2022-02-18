<?
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');	
$postData = file_get_contents('php://input');
$data = json_decode($postData, true); 

if ($postData) {
	$user = $data['user'];
	$org = $data['org'];
	$prof = $data['prof'];
	$quest = "";
	$questRow = "";
	foreach ($data['quest'] as $value){
					foreach ($value as $key => $questData){
						$quest .="<td>" . $questData . "</td>";
					}
				$questRow .= "<tr>" . $quest . "</tr>";
				$quest = "";				
			} 
	$table = "<html><body>
	 <table border='1' style='border-color:#263238; border-style:solid'>
		<tr>
			<td>ФИО</td>
			<th>". $user ."</th>
		</tr>
		<tr>
			<td>Организация</td>
			<th>" . $org ."</th>
		</tr>
		<tr>
			<td>Должность</td>
			<th>" . $prof . "</th>
		</tr>
		 " . $questRow ."
		</table></body></html>";


	echo $table;

	$el = new CIBlockElement;

	$PROP = array();
	$PROP[809][0] = Array("VALUE" => Array ("TEXT" => $table, "TYPE" => "html"));  // свойству с кодом 12 присваиваем значение "Белый"

	 CModule::IncludeModule('bizproc');
	    $documentId = CBPVirtualDocument::CreateDocument(
	        0,
	        array(
	            "IBLOCK_ID" => 105,
	            "NAME" => "Тестирование " . $user,
	            "CREATED_BY" => "user_" . $GLOBALS["USER"]->GetID(),
	            "PROPERTY_VALUES"=> $PROP,
	            "IBLOCK_SECTION" => 881,
	        )
	    );

	    $arErrorsTmp = array();

	    $wfId = CBPDocument::StartWorkflow(
	        398,
	        array("lists", "BizprocDocument", $documentId),
	        array_merge(array(), array("TargetUser" => "user_" . intval($GLOBALS["USER"]->GetID()),
	            CBPDocument::PARAM_DOCUMENT_EVENT_TYPE =>
	            CBPDocumentEventType::Manual)),
	        $arErrorsTmp
	    );

	$to      = 'Tsupko.IA@ahstep.ru';
	$subject = "Тестирование " . $user;
	$message = $table;

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
	 
	// Create email headers
	$headers .= 'From: '.$from."\r\n".
	    'Reply-To: '.$from."\r\n" .
	    'X-Mailer: PHP/' . phpversion();
	 
	// Compose a simple HTML email message
	$message = '<html><body>';
	$message .= '<h1 style="color:#f40;">Hi Jane!</h1>';
	$message .= '<p style="color:#080;font-size:18px;">Will you marry me?</p>';
	$message .= '</body></html>';

	mail($to, $subject, $table, $headers);


}
?>