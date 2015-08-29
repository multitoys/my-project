<?php
/*	
	ZUnlimited_ListController 
	By Zerg Solutions
	http://zerg-solutions.com.ua
*/

/**
 * @package Modules
 * @subpackage AdministratorScreens
 */
class ZUnlimited_ListController extends ActionsController {

	function main(){

		$Register = &Register::getInstance();
		/*@var $Register Register*/
		$smarty = &$Register->get(VAR_SMARTY);
		/*@var $smarty Smarty*/
		
		
		$u_users = $this->Get_List();
		$smarty->assign('u_users', $u_users);
		$smarty->display(DIR_TPLS.'/backend/zunlimited_list.html');
	}

	function Get_List() {
		$sql = "select * from ?#CUSTOMERS_TABLE where unlimited_order=1";
		$q = db_phquery($sql);
		$unlimited_users = array();
		while( $row = db_fetch_row($q) ) {
			$unlimited_users[] = $row;
		}
		return $unlimited_users;	
	}
}	
ActionsController::exec('ZUnlimited_ListController');
?>