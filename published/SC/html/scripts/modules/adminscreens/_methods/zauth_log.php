<?php
    
/**
 * @package Modules
 * @subpackage AdministratorScreens
 */
class ZAuth_LogController extends ActionsController {

	function main(){

		$Register = &Register::getInstance();
		/*@var $Register Register*/
		$smarty = &$Register->get(VAR_SMARTY);
		/*@var $smarty Smarty*/
		
		$gridEntry = ClassManager::getInstance('grid');
		
		if (isset($_GET["type_event"])){
			if ($_GET["type_event"] == '-1'){
				unset($_GET["type_event"]);
			}
		}
		
		$gridEntry->query_total_rows_num = 'SELECT COUNT(*) FROM SC_auth_log'.
		' WHERE 1 '.
		(isset($_GET['login'])?' AND Login LIKE "%'.xEscapeSQLstring($_GET['login']).'%"':'').
		(isset($_GET['last_name'])?' AND last_name LIKE "%'.xEscapeSQLstring($_GET['last_name']).'%"':'').
		(isset($_GET['first_name'])?' AND first_name LIKE "%'.xEscapeSQLstring($_GET['first_name']).'%"':'').
		(isset($_GET['IP_address'])?' AND IP_address LIKE "%'.xEscapeSQLstring($_GET['IP_address']).'%"':'').
		(isset($_GET['type_event'])?' AND type_event LIKE "%'.xEscapeSQLstring($_GET['type_event']).'%"':'');
		
		
		$gridEntry->query_select_rows = 'SELECT * FROM SC_auth_log'.
		' WHERE 1 '.
		(isset($_GET['login'])?' AND Login LIKE "%'.xEscapeSQLstring($_GET['login']).'%"':'').
		(isset($_GET['last_name'])?' AND last_name LIKE "%'.xEscapeSQLstring($_GET['last_name']).'%"':'').
		(isset($_GET['first_name'])?' AND first_name LIKE "%'.xEscapeSQLstring($_GET['first_name']).'%"':'').
		(isset($_GET['IP_address'])?' AND IP_address LIKE "%'.xEscapeSQLstring($_GET['IP_address']).'%"':'').
		(isset($_GET['type_event'])?' AND type_event LIKE "%'.xEscapeSQLstring($_GET['type_event']).'%"':'');
		
		$gridEntry->show_rows_num_select = false;
		$gridEntry->default_sort_direction = 'DESC';
		//$gridEntry->rows_num = 20;
		$gridEntry->registerHeader('Дата', 'date_event', true, 'desc');
		$gridEntry->registerHeader('Фамилия', 'last_name', false, 'asc');
		$gridEntry->registerHeader('Имя', 'first_name', false, 'asc');
		$gridEntry->registerHeader('Логин', 'Login', false, 'asc');
		$gridEntry->registerHeader('IP адресс', 'IP_address', false, 'asc');
		$gridEntry->registerHeader('Облать', 'region', false, 'asc');
		$gridEntry->registerHeader('Город', 'city', false, 'asc');
		$gridEntry->registerHeader('Тип', 'type_event', false, 'asc');
		$gridEntry->registerHeader('User Agent', 'user_agent', false, 'asc');
//		$gridEntry->registerHeader('Все IP', 'all_ip_info', false, 'asc');
		// $gridEntry->registerHeader('Доступ до:', 'may_order_until', false, 'asc');
		// $gridEntry->registerHeader('Авторизован до:', 'logged', false, 'asc');
		$gridEntry->prepare();
		
		$rows = $smarty->get_template_vars('GridRows');
		for($k = count($rows)-1; $k>=0; $k--){
			$rows[$k]['date_event'] = $rows[$k]['date_event'];
			$rows[$k]['customerID'] = $rows[$k]['customerID'];
			$rows[$k]['last_name'] = $rows[$k]['last_name'];
			$rows[$k]['first_name'] = $rows[$k]['first_name'];
			$rows[$k]['Login'] = $rows[$k]['Login'];
			$rows[$k]['IP_address'] = $rows[$k]['IP_address'];
			$rows[$k]['region'] = $rows[$k]['region'];
			$rows[$k]['city'] = $rows[$k]['city'];
			$rows[$k]['type_event'] = $rows[$k]['type_event'];
			$rows[$k]['user_agent'] = $rows[$k]['user_agent'];
//			$rows[$k]['all_ip_info'] = $rows[$k]['all_ip_info'];
			// $rows[$k]['may_order_until'] = $rows[$k]['may_order_until'];
			// $rows[$k]['logged'] = $rows[$k]['logged'];
		}
		
		$count_rows = array('500'=>500, '1000'=>1000, '5000'=>5000, '1000'=>10000);

		$smarty->assign('GridRows', $rows);
		$smarty->assign('rows', $count_rows);
		$smarty->assign('TotalFound',str_replace('{N}',$gridEntry->total_rows_num,translate('msg_n_customers_found')));
		
		$smarty->display(DIR_TPLS.'/backend/zauth_log.html');
	}
}	
ActionsController::exec('ZAuth_LogController');