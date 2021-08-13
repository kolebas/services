<?
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
global $USER;
?>


<?

$db_props = CIBlockElement::GetProperty(88,132671, array());
		$PROPS = array();
		while($ar_props = $db_props->Fetch()):
		if($ar_props[VALUE] != NULL){						
			$PROPS[$ar_props[CODE]] = $ar_props[VALUE];
		}
		endwhile;
		$get_result[] = array('ZADACHA' => $PROPS[ZADACHA][TEXT]);
		echo $get_result[0][ZADACHA];		

?>


<?
#Получение списка задач
/*if (CModule::IncludeModule("tasks"))
{
    $res = CTasks::GetList(
        Array("CREATED_DATE" => "ASC"),
        Array(
        	"GROUP_ID" => 4,
        	">CREATED_DATE" => "01.01.2020",
        	//"TITLE" => "Заявка на доработку 1С%",
        	"UF_AUTO_815639398196" => "INC-1C"
        ),
    );
    ?>
    <table id="table">    	
    	
    <?
    while ($arTask = $res->GetNext())
    {
    	$rsUser = CUser::GetByID($arTask["RESPONSIBLE_ID"]);
    	$arUser = $rsUser->Fetch();
    	print_r($arTask);
        echo "<tr><td>".$arTask["TITLE"]."</td><td>". $arTask["CREATED_DATE"] ."</td><td>". strip_tags($arTask["DESCRIPTION"]) ."</td><td>".$arUser["LAST_NAME"] . "  ". $arUser["NAME"] ."</td></tr>";
    }
    ?>
		
	</table>
    <?
}*/
?>

<script>
//Выгрузка в файл excel	
/*function exportTableToFile() {
  let htmltable = document.getElementById("table");
  let html = htmltable.outerHTML;
  let downloadLink = document.createElement("a");
  let uri = "data:application/vnd.ms-excel;base64,",
    template =
      '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body>' +
      html +
      "</body></html>",
    base64 = function (s) {
      return window.btoa(unescape(encodeURIComponent(s)));
    },
    format = function (s, c) {
      return s.replace(/{(\w+)}/g, function (m, p) {
        return c[p];
      });
    };

  let ctx = { worksheet: "Worksheet", table: html };
  downloadLink.href = uri + base64(format(template, ctx));
  downloadLink.download = this.titleDataTable + ".xls";

  document.body.appendChild(downloadLink);
  downloadLink.click();
  document.body.removeChild(downloadLink);
}
exportTableToFile();
*/
</script>

<?
#Работа с бизнес процессами
/*
function GetStatusProcess($blockId,$id)
{	
	if(CModule::IncludeModule("bizproc"))
	{
	$documentType = array("bizproc", "CBPVirtualDocument", "type_".$blockId);
	$documentId = array('lists', 'BizprocDocument', $id);
	
	$arDocumentStates = CBPDocument::GetDocumentStates($documentType, $documentId);

	print_r($arDocumentStates);
	echo "<br>";
	foreach ($arDocumentStates as $arDocumentState)
		{
		   $arDocumentStateTasks = CBPDocument::GetUserTasksForWorkflow(1840, $arDocumentState["ID"]);
		   print_r($arDocumentStateTasks);
		   foreach ($arDocumentStateTasks as $arDocumentStateTask) {
		   		echo "<br><br><br><br><br><br>";
		   		echo $arDocumentStateTask["DESCRIPTION"];
		   }
		}
	}	
}

GetStatusProcess(101, 113481);
*/
?>

<?
//Добавление группы в Active Directory
/*
function createADGroup($object_name, $dn, $members, $ldap_con, $description)
{
    $addgroup_ad['cn']="$object_name";
    $addgroup_ad['objectClass'][0] = "top";
    $addgroup_ad['objectClass'][1] ="group";
    $addgroup_ad['groupType']="2";
    $addgroup_ad["sAMAccountName"] =$object_name;
    $addgroup_ad["description"] =$description;

    $result = ldap_add($ldap_con,$dn,$addgroup_ad);
   
    if($result){
    		echo "Успех";    		
    	}
    else {
    		echo ldap_error($ldap_con);
    	}	
}

function addADGroup($groupName, $description)
{
	
	$ldap_srv = "ldap://ahstep-kd.ahstep.ru/";
	$ldap_dn = 'CN=bx_user,OU=ServiceAccounts,OU=Groups,DC=ahstep,DC=ru';
	$ldap_password = "Vbuhfwbz31";
	$ldap_con = ldap_connect($ldap_srv);
	$dn = "DC=ahstep,DC=ru";	
	ldap_bind($ldap_con, $ldap_dn, $ldap_password);
	ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($ldap_con, LDAP_OPT_REFERRALS, 0);
	
	$dn="CN=".$groupName.",OU=NFS,OU=Groups,DC=AHSTEP,DC=RU";

	createADGroup($groupName, $dn, $members, $ldap_con, $description);
	ldap_close($ldap_con);
}
$fldNname = "123454";
$description = "тестирование";
AddADGroup ("NFS-".$fldNname."-RO", $description);
*/
?>

<?
//Получение комментариев для задачи
/*if (CModule::IncludeModule("tasks"))
	{
		$taskId = 18113;
		$userId = $USER->getId();
		$task = CTaskItem::getInstance($taskId, $userId);
		//print_r($task->getData());
		$forumId =  $task['FORUM_TOPIC_ID'];
		//echo $userId;

		$db_res = CForumMessage::GetList(array("ID"=>"ASC"), array("TOPIC_ID"=>$forumId));
			while ($ar_res = $db_res->Fetch())
			{
			//	print_r($ar_res);
			  echo $ar_res["AUTHOR_NAME"].": <b>".$ar_res["POST_MESSAGE"]."</b><br>";
			}
	}
*/
?>

<?/*
$dir = 'test';
$folder = '/home/bitrix/mnt/ahs-nfs001/'.$dir;

if (!mkdir($folder, 0777, true)) {
	echo 'failed';
    //$this->SetVariable('Status_auto_create_folder', 'Неудача');
}
else {
	echo 'success';
    //$this->SetVariable('Status_auto_create_folder', 'Успех');
}

*/
?>

<?

//Получаем ID пользователя
/*$id=$USER->GetID();
$oUserinfo = CUser::GetByID($id);
$rs = $oUserinfo->getNext();
echo $rs["LAST_NAME"] ." ". $rs["NAME"];
*/

/*
// Получаем название подразделения человека
$oDep = CIntranetUtils::GetDepartmentsData($rs["UF_DEPARTMENT"]);
$dep = current($oDep);
$depId = $rs["UF_DEPARTMENT"][0];
echo $dep . '<br>';
*/
?>

<?
//Получение групп пользователя
/*$arGroupAvalaible = array(4); // массив групп, которые в которых нужно проверить доступность пользователя
$arGroups = CUser::GetUserGroup(2385); // массив групп, в которых состоит пользователь
$result_intersect = array_intersect($arGroupAvalaible, $arGroups);// далее проверяем, если пользователь вошёл хотя бы в одну из групп, то позволяем ему что-либо делать
print_r($result_intersect);

$arGroupAvalaible = array(4);
$arGroups = CUser::GetUserGroup($USER->GetID());
$result_intersect = array_intersect($arGroupAvalaible, $arGroups);
$pl_users = array();
    $userInfo[] = array('ID' => $USER->GetID(), 'GROUP' => $arGroups);
echo "<br>".json_encode($userInfo);*/
?>

<?
/*
if(CModule::IncludeModule("socialnetwork"))
	{ 
		$Role=CSocNetUserToGroup::GetUserRole(1940,40);
    	echo $Role;
} 
*/
?>

<?
//Работа со строками
/*
$text = 'Проблема: ООО "Цимлянское" Офис | R-CML-CIM-1';
$abr = substr($text, -9, -6);
echo $abr;
*/
?>

<?
/*
//Получаем список элементов инфоблока
$id = 87;
$fld = "AHS-ФЭК-Оценка Волгодонской элеватор";
$arSelect = [
	NAME,
	'PROPERTY_487_VALUE',
];
$arFilter = [
	'IBLOCK_ID' => $id,
	'PROPERTY_483' => $fld,
];

$element = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
$i = 0;
while($arElements = $element->Fetch())
{
	$arrElems[] = $arElements;
	if($arrElems[$i][NAME] != NULL)
		{
			$user = $arrElems[$i][PROPERTY_487];
			echo $user;
			//$this->SetVariable('ING', $user);
		}	
	$i++;
}

//АЛЬТЕРНАТИВНЫЙ СПОСОБ ПОЛУЧЕНИЯ ЭЛЕИМЕНТА ИФНОБОКА ВМЕСТЕ СО СВОЙСТВАМИ
while ($arElements = $element->GetNextElement())
{
	$arFields = $arElements->GetFields();
 	print_r($arFields);
 	$arProps = $arElements->GetProperties();
	print_r($arProps);
	echo $arProps[NAME] .' -- '. $arProps[SOKRASHCHENIE][VALUE] .' -- '. $arProps[OTVETSTVENNYY_SPETSIALIST_IT][VALUE] . '<br>';
}
*/
?>

<?/*php
function rus2translit($string) {
		$converter = array(
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => '',  'ы' => 'y',   'ъ' => '',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
        
        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => '',  'Ы' => 'Y',   'Ъ' => '',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
		);
		return strtr($string, $converter);
	}
	
	$str = "{=Document:PROPERTY_FIO}";
	$str_org = explode(" ", $str);
	$str = rus2translit($str);
	$sn_org = $str_org[0];
	$name_org =  $str_org[1];
	$subname_org = $str_org[2];
	//$str = preg_replace('~[^-A-Z-a-z0-9_]+~u', '.', $str);
	
	
	echo $str."<br>";
	$str1 = explode(" ", $str);
	$sn = $str1[0];
	$name =  substr($str1[1], 0, 1);
	$subname = substr($str1[2], 0, 1);
	$cn = $sn.".".$name. $subname;
	
	$ldap_srv = "ldap://ahstep-kd.ahstep.ru/";
	$ldap_dn = "CN=bx_user,CN=Users,DC=ahstep,DC=ru";
	$ldap_password = "Vbuhfwbz31";
	$ldap_con = ldap_connect($ldap_srv);
	$ldap_tree = "CN=Users,DC=ahstep,DC=ru";
	ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
	$company = '{=Document:PROPERTY_ORG_PRINTABLE}';
	echo $company;
	if ($company == "АО Агрохолдинг «СТЕПЬ»") {
		if ($dep != NULL){
			$dep = '{=Document:PROPERTY_DEP}';
			if ($dep == 'Департамент стратегии'){
				$ou = 'OU=Департамент стратегии,OU=Агрохолдинг \"СТЕПЬ\"';
			}
			if ($dep == 'Производственный комплекс'){
				$ou = 'OU=Производственный комплекс,OU=Агрохолдинг \"СТЕПЬ\"';
			}
			if ($dep == 'Производственный комплекс - Территориальная группа по растениеводству'){
				$ou = 'OU=Территориальная группа по растениеводству,OU=Производственный комплекс,OU=Агрохолдинг \"СТЕПЬ\"';
			}
			if ($dep == 'Финансово-экономический комплекс'){
				$ou = 'OU=Финансово-экономический комплекс,OU=Агрохолдинг \"СТЕПЬ\"';
			}
			if ($dep == 'Финансово-экономический комплекс - Департамент казначейских операций'){
				$ou = 'OU=Департамент казначейских операций,OU=Финансово-экономический комплекс,OU=Агрохолдинг \"СТЕПЬ\"';
			}
			if ($dep == 'Финансово-экономический комплекс - Департамент корпоративного финансирования'){
				$ou = 'OU=Департамент корпоративного финансирования,OU=Финансово-экономический комплекс,OU=Агрохолдинг \"СТЕПЬ\"';
			}
			if ($dep == 'Финансово-экономический комплекс - Департамент отчетности по МСФО'){
				$ou = 'OU=Департамент отчетности по МСФО,OU=Финансово-экономический комплекс,OU=Агрохолдинг \"СТЕПЬ\"';
			}
			if ($dep == 'Финансово-экономический комплекс - Департамент отчетности по РСБУ'){
				$ou = 'OU=Департамент отчетности по РСБУ,OU=Финансово-экономический комплекс,OU=Агрохолдинг \"СТЕПЬ\"';
			}
			if ($dep == 'Финансово-экономический комплекс - Департамент планирования и контроля бюджета'){
				$ou = 'OU=Департамент планирования и контроля бюджета,OU=Финансово-экономический комплекс,OU=Агрохолдинг \"СТЕПЬ\"';
			}
			if ($dep == 'Департамент закупок'){
				$ou = 'OU=Департамент закупок,OU=Агрохолдинг \"СТЕПЬ\"';
			}
			if ($dep == 'Департамент корпоративного управления'){
				$ou = 'OU=Департамент корпоративного управления,OU=Агрохолдинг \"СТЕПЬ\"';
			}
			if ($dep == 'Департамент корпоративного управления - Отдел корпоративного управления'){
				$ou = 'OU=Отдел корпоративного управления,OU=Департамент корпоративного управления,OU=Агрохолдинг \"СТЕПЬ\"';
			}
			if ($dep == 'Департамент корпоративного управления - Юридическая служба'){
				$ou = 'OU=Юридическая служба,OU=Департамент корпоративного управления,OU=Агрохолдинг \"СТЕПЬ\"';
			}
			if ($dep == 'Департамент информационных технологий'){
				$ou = 'OU=Департамент информационных технологий,OU=Агрохолдинг \"СТЕПЬ\"';
			}
			if ($dep == 'Департамент информационных технологий - Отдел информационных систем'){
				$ou = 'OU=Департамент информационных технологий,OU=Агрохолдинг \"СТЕПЬ\"';
			}
			if ($dep == 'Департамент информационных технологий - Отдел инфраструктуры ИТ'){
				$ou = 'OU=Департамент информационных технологий,OU=Агрохолдинг \"СТЕПЬ\"';
			}
			if ($dep == 'Департамент внутреннего контроля и аудита'){
				$ou = 'OU=Департамент внутреннего контроля и аудита,OU=Агрохолдинг \"СТЕПЬ\"';
			}
			if ($dep == 'Департамент развития бизнеса'){
				$ou = 'OU=Департамент развития бизнеса,OU=Агрохолдинг \"СТЕПЬ\"';
			}
			if ($dep == 'Департамент управления персоналом'){
				$ou = 'OU=Департамент управления персоналом,OU=Агрохолдинг \"СТЕПЬ\"';
			}
			if ($dep == 'Департамент корпоративной безопасности'){
				$ou = 'OU=Департамент корпоративной безопасности,OU=Агрохолдинг \"СТЕПЬ\"';
			}
			if ($dep == 'Департамент корпоративной безопасности - Дирекция экономической безопасности и противодействия коррупции'){
				$ou = 'OU=Дирекция экономической безопасности и противодействия коррупции,OU=Департамент корпоративной безопасности,OU=Агрохолдинг \"СТЕПЬ\"';
			}
			if ($dep == 'Департамент корпоративной безопасности - Региональная дирекция по безопасности'){
				$ou = 'OU=Региональная дирекция по безопасности,OU=Департамент корпоративной безопасности,OU=Агрохолдинг \"СТЕПЬ\"';
			}
			if ($dep == 'Департамент по работе с государственными органами'){
				$ou = 'OOU=Департамент по работе с государственными органами,OU=Агрохолдинг \"СТЕПЬ\"';
			}
			if ($dep == 'Департамент науки и инновационного развития'){
				$ou = 'OU=Департамент науки и инновационного развития,OU=Агрохолдинг \"СТЕПЬ\"';
			}
			$ldaprecord['department'] = $dep;
		} else {
		$ou = 'OU=Агрохолдинг \"СТЕПЬ\"';
		}
	} elseif ($company == "АО «Белое»"){
		$ou = 'OU=АО \"Белое\",OU=Краснодарский кластер';
		} elseif ($company == 'АО «Агрохлебопродукт»'){
		$ou = 'OU=АО \"Агрохлебопродукт\",OU=Ставропольский кластер';
		} elseif ($company == 'АО «Зимхлебопродукт»'){
		$ou = 'OU=АО \"Зимхлебопродукт\",OU=Ростовский кластер';
		} elseif ($company == 'АО «Имени Героя ВОВ Данильченко В.И.»'){
		$ou = 'OU=ПАО \"Имени Героя ВОВ Данильченко В.И.\",OU=Краснодарский кластер';
		} elseif ($company == 'АО «Новопластуновское»'){
		$ou = 'OU=ПАО \"Новопластуновское\",OU=Краснодарский кластер';
		} elseif ($company == 'АО «Племенной завод «Гашунский»'){
		$ou = 'OU=АО \"Племенной завод \"Гашунский\",OU=Ростовский кластер';
		} elseif ($company == 'АО «Племенной завод «Прогресс»'){
		$ou = 'OU=ОАО \"Племенной завод \"Прогресс\",OU=Ростовский кластер';
		} elseif ($company == 'АО «СКЗ»'){
		$ou = 'OU=АО «Сальский комбикормовый завод»,OU=Ростовский кластер';
		} elseif ($company == 'АО «Труд»'){
		$ou = 'OU=ПАО \"Труд\",OU=Краснодарский кластер';
		} elseif ($company == 'АО «Трудовое»'){
		$ou = 'OU=ПАО \"Трудовое\",OU=Краснодарский кластер';
		} elseif ($company == 'АО Агрокомбинат «Южный»'){
		$ou = 'OU=АО Агрокомбинат \"Южный\"';
		} elseif ($company == 'АО Молочный завод \"Орловский\"'){
		$ou = 'OU=АО молочный завод Орловский,OU=Ростовский кластер';
		} elseif ($company == 'БСХП'){
		$ou = 'OU=ООО \"Бирюлинское сельскохозяйственное предприятие\",OU=Республика Алтай';
		} elseif ($company == 'ООО «Бешпагир»'){
		$ou = 'OU=ООО \"Бешпагир\",OU=Ставропольский кластер';
		} elseif ($company == 'ООО \"БСХП\"'){
		$ou = 'OU=ООО \"Бирюлинское сельскохозяйственное предприятие\",OU=Республика Алтай';
		} elseif ($company == 'ООО «Агро-Мичуринское»'){
		$ou = 'OU=ООО \"Агро-Мичуринское\",OU=Ростовский кластер';
		} elseif ($company == 'ООО «Азоврыба»'){
		$ou = 'OU=ООО \"Азоврыба\",OU=Ростовский кластер';
		} elseif ($company == 'ООО «Волгодонской элеватор»'){
		$ou = 'OU=ООО \"Элеватор \"Волгодонский\",OU=Ростовский кластер';
		} elseif ($company == 'ООО «Заветное»'){
		$ou = 'OU=ООО \"Заветное\",OU=Ставропольский кластер';
		} elseif ($company == 'ООО «Коломийцевское»'){
		$ou = 'OU=ООО \"Коломийцевское\",OU=Ростовский кластер';
		} elseif ($company == 'ООО «МТС-ГК «Степь»'){
		$ou = 'OU=ООО \"МТС-ГК Степь\",OU=Краснодарский кластер';
		} elseif ($company == 'ООО «Новожуковский»'){
		$ou = 'OU=ООО \"Новожуковский\",OU=Ростовский кластер';
		} elseif ($company == 'ООО «Пролетарский КХП»'){
		$ou = 'OU=ООО \"Элеватор \"Пролетарский\" (АО КХП АО ПХП),OU=Ростовский кластер';
		} elseif ($company == 'ООО «Сельхозпроминвест»'){
		$ou = 'OU=Агрохолдинг \"СТЕПЬ\"';
		} elseif ($company == 'ООО «Ставропольское Руно»'){
		$ou = 'OU=ООО \"Ставропольское Руно\",OU=Ставропольский кластер';
		} elseif ($company == 'ООО «СТЕПЬ Агрострой»'){
		$ou = 'OU=ООО «СТЕПЬ Агрострой»,OU=Ростовский кластер';
        } elseif ($company == 'ООО Зерно Дон (ОП Гашунский)'){
		$ou = 'OU=ОП АО \"ПЗ Гашунский\",OU=Ростовский кластер';
        } elseif ($company == 'ООО Велес (ОП Гашунский)'){
		$ou = 'OU=ОП АО \"ПЗ Гашунский\",OU=Ростовский кластер';
        } elseif ($company == 'ООО «Родная Земля»'){
		$ou = 'OU=ООО \"Родная Земля\",OU=Ростовский кластер';
		} elseif ($company == 'ООО «ТД» Инвестпром-Опт»'){
		$ou = 'OU=ООО ТД \"ИНВЕСТПРОМ-ОПТ\",OU=Ростовский кластер';
		} elseif ($company == 'ООО «Техюгснаб»'){
			if ($dep != NULL){
				$dep = '{=Document:PROPERTY_DEP}';
				if ($dep == 'АУП'){
					$ou = 'OU=Бухгалтерия,OU=ООО \"ТехЮгСнаб\",OU=Ростовский кластер';
				}
				elseif ($dep == 'Департамент учета и отчетности'){
					$ou = 'OU=Департамент Учета и отчетности,OU=ООО \"ТехЮгСнаб\"';
				}
				elseif ($dep == 'ОП г. Ставрополь'){
					$ou = 'OU=Техюгснаб,OU=Офис Ставрополь \"АХП\",OU=АО \"Агрохлебопродукт\"';
				}
				elseif ($dep == 'Дистанционные работники (с.Кевсала)'){
					$ou = 'OU=ТехЮгСнаб,OU=Офис Кевсалинский,OU=АО \"Агрохлебопродукт\",OU=Ставропольский кластер';
				}
				$ldaprecord['department'] = $dep;
			} else {			
			$ou = 'OU=ООО \"ТехЮгСнаб\",OU=Ростовский кластер';
			}		
		} elseif ($company == 'ООО «Торговый Дом Агрохолдинг «СТЕПЬ»'){
			$dep = '{=Document:PROPERTY_DEP}';
			if ($dep != NULL){
				if ($dep == 'Департамент ВЭД'){
					$ou = 'OU=Департамент ВЭД,OU=Торговый Дом Агрохолдинг \"СТЕПЬ\"';
				}
				elseif ($dep == 'Департамент ВЭД - Отдел сопровождения ВЭД'){
					$ou = 'OU=Отдел сопровождения ВЭД,OU=Департамент ВЭД,OU=Торговый Дом Агрохолдинг \"СТЕПЬ\"';
				}
				elseif ($dep == 'Департамент закупок'){
					$ou = 'OU=Департамент закупок,OU=Торговый Дом Агрохолдинг \"СТЕПЬ\"';
				}
				elseif ($dep == 'Департамент закупок - ОП Ставрополь'){
					$ou = 'OU=ОП Ставрополь,OU=Департамент закупок,OU=Торговый Дом Агрохолдинг \"СТЕПЬ\"';
				}
				elseif ($dep == 'Департамент закупок - Отдел по закупкам нишевых культур'){
					$ou = 'OU=Отдел по закупкам нишевых культур,OU=Департамент закупок,OU=Торговый Дом Агрохолдинг \"СТЕПЬ\"';
				}
				elseif ($dep == 'Департамент корпоративной безопасности'){
					$ou = 'OU=Департамент корпоративной безопасности,OU=Торговый Дом Агрохолдинг \"СТЕПЬ\"';
				}
				elseif ($dep == 'Департамент по реализации продукции'){
					$ou = 'OU=Департамент реализации продукции,OU=Торговый Дом Агрохолдинг \"СТЕПЬ\"';
				}
				elseif ($dep == 'Отдел автотранспортной логистики'){
					$ou = 'OU=Отдел автотранспортной логистики,OU=Торговый Дом Агрохолдинг \"СТЕПЬ\"';
				}
				elseif ($dep == 'Отдел по работе с персоналом'){
					$ou = 'OU=Отдел по работе с персоналом,OU=Торговый Дом Агрохолдинг \"СТЕПЬ\"';
				}
				elseif ($dep == 'Финансовый департамент'){
					$ou = 'OU=Финансовый департамент,OU=Торговый Дом Агрохолдинг \"СТЕПЬ\"';
				}
				elseif ($dep == 'Финансовый департамент - Бухгалтерия'){
					$ou = 'OU=Бухгалтерия,OU=Финансовый департамент,OU=Торговый Дом Агрохолдинг \"СТЕПЬ\"';
				}
				elseif ($dep == 'Финансовый департамент - Отдел сопровождения торговых операций'){
					$ou = 'OU=Отдел сопровождения торговых операций,OU=Финансовый департамент,OU=Торговый Дом Агрохолдинг \"СТЕПЬ\"';
				}
				$ldaprecord['department'] = $dep;
			} else {			
			$ou = 'OU=Торговый Дом Агрохолдинг \"СТЕПЬ\"';
			}
		} elseif ($company == 'ООО «Чапаевское»'){
		$ou = 'OU=ООО \"Чапаевское\",OU=Ставропольский кластер';
		} elseif ($company == 'ЗАО «Заря»'){
		$ou = 'OU=ЗАО \"Заря\",OU=Ставропольский кластер';
		} elseif ($company == 'ООО «Высоцкое»'){
		$ou = 'OU=ООО \"Высоцкое\",OU=Ставропольский кластер';
		} elseif ($company == 'ООО «Ульяновец»'){
		$ou = 'OU=ООО \"Ульяновец\",OU=Ставропольский кластер';
		} elseif ($company == 'ООО «Цимлянское»'){
		$ou = 'OU=ООО \"Цимлянское\",OU=Ставропольский кластер';
		} elseif ($company == 'ООО «Элеватор Пролетарский».'){
		$ou = 'OU=ООО \"Элеватор \"Пролетарский\" (АО КХП АО ПХП),OU=Ростовский кластер';
		} elseif ($company == 'ООО Торговый Дом «БСХП»'){
		$ou = 'OU=ООО \"Торговый дом \"БСХП\",OU=МСК';
		} elseif ($company == 'ОП г. Москва АО Агрокомбинат «Южный»'){
		$ou = 'OU=АО Агрокомбинат \"Южный\",OU=МСК';
		} elseif ($company == 'ОП Торговый Дом Агрохолдинг \"СТЕПЬ\"'){
		$ou = 'OU=ОП Торговый Дом Агрохолдинг \"СТЕПЬ\",OU=Краснодарский кластер';
		} elseif ($company == 'ПАО «Кубанская степь»'){
		$ou = 'OU=ПАО \"Кубанская степь\",OU=Краснодарский кластер';
		} elseif ($company == 'ПАО «Родина»'){
		$ou = 'OU=ПАО \"Родина\",OU=Краснодарский кластер';
		} else {
		$ou = 'OU=Агрохолдинг \"СТЕПЬ\"';
	}
	
	
	
	if(ldap_bind($ldap_con, $ldap_dn, $ldap_password)) {
		
		$ldaprecord["cn"] = $sn_org." ".$name_org." ".$subname_org;
		$ldaprecord["givenName"] = $name_org." ".$subname_org;
		$ldaprecord["sn"] = $sn_org;
		$ldaprecord["displayname"] = $sn_org." ".$name_org." ".$subname_org;
		$ldaprecord['objectclass'][0] = "top";
		$ldaprecord['objectclass'][1] = "person";
		$ldaprecord['objectclass'][1] = "organizationalPerson";
		$ldaprecord['objectclass'][2] = "user";
		$ldaprecord['mail'] = $cn."@ahstep.ru";    
		$ldaprecord['mobile'] = "{=Document:PROPERTY_KONTAKTNYY_TELEFON}";
                $ldaprecord['company'] = "{=Document:PROPERTY_ORG_PRINTABLE}";
		$ldaprecord['title'] = "{=Document:PROPERTY_DOLZHNOST}";
		$ldaprecord["sAMAccountName"] = $cn;
		$ldaprecord["userPrincipalName"] = $cn."@ahstep.ru";
		//$ldaprecord["unicodepwd"] = "test-portal";
		//$ldaprecord["UserAccountControl"] = "512";
		
		
		
		$dn = "CN=".$sn_org." ".$name_org." ".$subname_org.",".$ou.",DC=ahstep,DC=ru";
		$this->SetVariable('Status_auto_user_create', 'Успех');
		if(!($r = ldap_add($ldap_con,$dn,$ldaprecord))){
			$this->SetVariable('Status_auto_user_create', 'Неудача');
		}
		
		
		} else {
		$this->SetVariable('Status_auto_user_create', 'Неудача');
	}*/
?>

<?
//Информация о пользователе
/*$cnt = 0;
$data = CUser::GetList(($by="LAST_NAME"), ($order="ASC"),
            array(
                '!UF_DEPARTMENT' => NULL,
                'ACTIVE' => 'Y',
                'LAST_NAME' => '%_'
            )
        );

while($arUser = $data->Fetch()) {	
	$cnt = $cnt + 1;
	//print_r($arUser);
    echo $arUser['ID'].' '.$arUser['LAST_NAME'].' '.$arUser['PERSONAL_BIRTHDAY']."<br>";
}
echo $cnt;
*/
?>

<?
/*
$cnt = CIBlockElement::GetList(
					array(),
					array(
						'IBLOCK_ID' => 72,
						'NAME' => '%(AR-001)%'
					),
					array(),
					false,
					array('ID', 'NAME')
				); 
				 $cnt = $cnt + '1';
				 echo $cnt;
*/

?>

<?
//Поиск групп пользователя в LDAP
	/*$usr = '{=Document:PROPERTY_FIO_SOTRUDNIKA_PRINTABLE}';
	preg_match('/\((.+)\)/', $usr, $usr_m);
	
	$ldap_srv = 'ldap://ahstep-kd.ahstep.ru/';
	$ldap_dn = 'CN=bx_user,CN=Users,DC=ahstep,DC=ru';
	$ldap_password = 'Vbuhfwbz31';
	$ldap_con = ldap_connect($ldap_srv) or die($this->SetVariable('Status_auto', 'Could not connect to LDAP server.'));
	$dn = 'DC=ahstep,DC=ru';
	$person = $usr_m[1];	
	$filter_usr = '(samaccountname='.$person.')';
	$member_info = array('memberof');

	ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($ldap_con, LDAP_OPT_REFERRALS, 0);	
	ldap_bind($ldap_con, $ldap_dn, $ldap_password);
	$sr_usr = ldap_search($ldap_con, $dn, $filter_usr, $member_info) or die ($this->SetVariable('Status_auto', 'Неудача - ' . ldap_error($ldap_con)));	
	
	$data = ldap_get_entries($ldap_con, $sr_usr);
	if(!($data) || $data["count"] === 0){
		$this->SetVariable('Status_auto', 'Пользователь не найден');		
	}
	else{
		echo $data["count"];
		$user = $data[0];
		$this->SetVariable('Status_auto', $data["count"]);
	        for ($i=0; $i < $data["count"]; $i++) {
	 			for ($y=0; $y < $data[$i]["memberof"]["count"]; $y++) {
	 				$group = $data[$i]["memberof"][$y];
	 				$removal = array(
	 					"member" => $user["dn"],
	 				);
	 				/*if(!($r = ldap_mod_del($ldap_con, $group, $removal))){
	 					$this->SetVariable('Status_auto', 'Неудача - ' . ldap_error($ldap_con));
	 				}
	 				else{
	 					$this->SetVariable('Status_auto', 'Успех');
	 				}
	 			}
	        }	
	}	
	ldap_close($ldap_con);*/
?>

<?/*
//Добавление пользователя в группу LDAP
	$usr = '{=Document:PROPERTY_FIO_SOTRUDNIKA_PRINTABLE}';
	preg_match('/\((.+)\)/', $usr, $usr_m);
	
	$ldap_srv = 'ldap://ahstep-kd.ahstep.ru/';
	$ldap_dn = 'CN=bx_user,CN=Users,DC=ahstep,DC=ru';
	$ldap_password = 'Vbuhfwbz31';
	$ldap_con = ldap_connect($ldap_srv);
	$dn = 'DC=ahstep,DC=ru';
	$person = $usr_m[1];	
	$filter_usr = '(samaccountname='.$person.')';
	$dn_info = array('dn');
	ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($ldap_con, LDAP_OPT_REFERRALS, 0);
	
	ldap_bind($ldap_con, $ldap_dn, $ldap_password);
	
	$sr_usr = ldap_search($ldap_con, $dn, $filter_usr, $dn_info );
	
	$data = ldap_get_entries($ldap_con, $sr_usr);
        for ($i=0; $i<$data["count"]; $i++) {
		$dn_usr = $data[$i]["dn"];
        }
	$info_usr['member'] = $dn_usr;
	$group_rds = 'CN=RDS,OU=RDS,OU=Cервер,DC=ahstep,DC=ru';
		if(!($r = ldap_mod_add($ldap_con, $group_rds, $info_usr))){
				if (ldap_error($ldap_con) == 'Already exists') {
							echo 'Пользователь уже имеет терминальный доступ';
							$this->SetVariable('Status_auto', 'Предоставлен ранее');
						}
				else {
					echo 'Добавление прав на треминальный доступ - НЕУДАЧА';
					$this->SetVariable('Status_auto', 'Неудача');
				}			
		} else {
		echo 'Добавление пользователя УСПЕХ';
		$this->SetVariable('Status_auto', 'Успех');
		}
	ldap_close($ldap_con);*/
?>


<?
//Получаем корневое подразделение с уровнем -1 подразделений
/*$arFilter = Array(
	'IBLOCK_ID'=>'5', 
	'GLOBAL_ACTIVE'=>'Y',
);
$order = Array(
	'NAME'=>'ASC'
);
$db_list = CIBlockSection::GetList(Array($by=>$order), $arFilter, true);
	while($ar_result = $db_list->GetNext())
	{	
		$rsParentSection = CIBlockSection::GetByID($ar_result['ID']);
		if ($arParentSection = $rsParentSection->GetNext())
		{
		   $arFilter = array('IBLOCK_ID' => $arParentSection['IBLOCK_ID'],'>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],'<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],'>DEPTH_LEVEL' => '1'); // выберет потомков без учета активности
		   $rsSect = CIBlockSection::GetList(array('NAME' => 'asc'),$arFilter);
		   while ($arSect = $rsSect->GetNext())
		   {		
		   		if($arSect[ID] == $depId &&  $arParentSection[DEPTH_LEVEL] == 2){
		   			echo $arParentSection[NAME].'<br>';
		   			//print_r($arParentSection);
		   			}   	
		   	}
		}   
	}
	*/
?>


<?


//Получаем массив пользователей
/*$data = CUser::GetList(($by="ID"), ($order="ASC"),
            array(
                'ACTIVE' => 'Y'
            )
        );

while($arUser = $data->Fetch()) {
    echo $arUser['ID'].' '.$arUser['NAME'].' '.$arUser['WORK_DEPARTMENT'].'<img width="30px" height="30px" src='.cfile::getpath($arUser['PERSONAL_PHOTO']).'><br>';
}
print_r($arUser);*/

//$this->SetVariable('WORK_DEPARTMENT', $dep);

/* $arFilter = Array(
		'IBLOCK_ID'=>'64'
	);
	$res = CIBlockElement::GetList(Array('NAME'=>'ASC'), $arFilter);
	while($ar_fields = $res->GetNext())
		{
			//echo $ar_fields['NAME'].'<br>';
		}
	*/
?>
	
	
<?
# Фильтр по принципу "Информационный блок с ID - 67"
/*$id = 87;
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
			   //print_r($PROPS[ZADACHA]);
			}
		endwhile;
		//По элементу информационного блока выведем значение свойства
			   echo '<tr><td>'.$PROPS[NAIMENOVANIE_USLUGI].'</td><td>'.$PROPS[INITSIATOR_ZAYAVKI].'</td><td>'.$PROPS[ZADACHA][TEXT].'</td><td>'.$PROPS[STATUS][TEXT].'</td></tr>';
endwhile;
*/
?>