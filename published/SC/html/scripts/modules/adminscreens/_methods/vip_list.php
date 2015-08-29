<?php
class Vip_ListController extends ActionsController {

	function main(){

		$Register = &Register::getInstance();
		/*@var $Register Register*/
		$smarty = &$Register->get(VAR_SMARTY);
		/*@var $smarty Smarty*/
		
		
		$v_users = $this->Get_List();
		$smarty->assign('v_users', $v_users);
		$smarty->display(DIR_TPLS.'/backend/vip_list.html');
	}

	function Get_List() {
		$sql = "select * from ?#CUSTOMERS_TABLE where vip=1";
		$q = db_phquery($sql);
		$vip = array();
		while( $row = db_fetch_row($q) ) {
			$vip[] = $row;
		}
		return $vip;	
	}
}	
ActionsController::exec('Vip_ListController');
?>