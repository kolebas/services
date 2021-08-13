<?
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
$arGroupAvalaible = array(4);
$arGroups = CUser::GetUserGroup($USER->GetID());
$result_intersect = array_intersect($arGroupAvalaible, $arGroups);
if(CModule::IncludeModule("socialnetwork"))
	{ 
		$SocGROUP=CSocNetUserToGroup::GetUserRole($USER->GetID(),4);
    	//echo $SocGROUP;
}
$pl_users = array();
    $userInfo[] = array('ID' => $USER->GetID(), 'GROUP' => $arGroups, 'SocGROUP' => $SocGROUP);
echo json_encode($userInfo);
?>
