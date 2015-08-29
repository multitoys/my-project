<?php
class Open_ListController extends ActionsController {

	function main(){

		$Register = &Register::getInstance();
		/*@var $Register Register*/
		$smarty = &$Register->get(VAR_SMARTY);
		/*@var $smarty Smarty*/
		
		
		$o_users = $this->Get_List();
		$smarty->assign('o_users', $o_users);
		$smarty->display(DIR_TPLS.'/backend/open_list.html');
	}

	function Get_List() {
//		$sql = "select * from ?#CUSTOMERS_TABLE where may_order_until > CURRENT_TIMESTAMP";
		$sql = "select * from ?#CUSTOMERS_TABLE where logged != '0000-00-00 00:00:00'";
		$q = db_phquery($sql);
		$open = array();
		while( $row = db_fetch_row($q) ) {
			$open[] = $row;
		}
		return $open;	
	}
}	
ActionsController::exec('Open_ListController');
?>