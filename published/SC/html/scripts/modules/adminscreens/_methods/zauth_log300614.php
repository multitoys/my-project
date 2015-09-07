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
class ZAuth_LogController extends ActionsController {

	function main(){

		$Register = &Register::getInstance();
		/*@var $Register Register*/
		$smarty = &$Register->get(VAR_SMARTY);
		/*@var $smarty Smarty*/
		
		$gridEntry = ClassManager::getInstance('grid');
		$gridEntry->query_total_rows_num = 'SELECT COUNT(*) FROM SC_auth_log';
		$gridEntry->query_select_rows = 'SELECT * FROM SC_auth_log';
		$gridEntry->show_rows_num_select = false;
		$gridEntry->default_sort_direction = 'ASC';
		//$gridEntry->rows_num = 20;

		$gridEntry->registerHeader('Логин', 'Login', true, 'asc');
		$gridEntry->registerHeader('IP адресс', 'IP_address', true, 'asc');
		$gridEntry->registerHeader('Облать', 'region', true, 'asc');
		$gridEntry->registerHeader('Город', 'city', true, 'asc');
		$gridEntry->registerHeader('Тип', 'type_event', true, 'asc');
		$gridEntry->registerHeader('Дата', 'date_event', true, 'desc');
		$gridEntry->prepare();
		
		$rows = $smarty->get_template_vars('GridRows');
		for($k = count($rows)-1; $k>=0; $k--){
			$rows[$k]['customerID'] = $rows[$k]['customerID'];
			$rows[$k]['Login'] = $rows[$k]['Login'];
			$rows[$k]['IP_address'] = $rows[$k]['IP_address'];
			$rows[$k]['region'] = $rows[$k]['region'];
			$rows[$k]['city'] = $rows[$k]['city'];
			$rows[$k]['type_event'] = $rows[$k]['type_event'];
			$rows[$k]['date_event'] = $rows[$k]['date_event'];
		}
		
		$count_rows = array('100'=>100, '200'=>200, '500'=>500, '1000'=>1000);

		$smarty->assign('GridRows', $rows);
		$smarty->assign('rows', $count_rows);
		$smarty->assign('TotalFound',str_replace('{N}',$gridEntry->total_rows_num,translate('msg_n_customers_found')));
		
		$smarty->display(DIR_TPLS.'/backend/zauth_log.html');
	}
}	
ActionsController::exec('ZAuth_LogController');
?>