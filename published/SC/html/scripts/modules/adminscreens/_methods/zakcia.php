<?php
/*	
	ZAkcia 
	By Zerg Solutions
	http://zerg-solutions.com.ua
*/

/**
 * @package Modules
 * @subpackage AdministratorScreens
 */
class ZAkciaController extends ActionsController {

	function main(){

		$Register = &Register::getInstance();
		/*@var $Register Register*/
		$smarty = &$Register->get(VAR_SMARTY);
		/*@var $smarty Smarty*/

		if(isset($_POST['akcia_action']) && $_POST['akcia_action'] == 'add') {
			// Добавить новую акцию
			$smarty->display(DIR_TPLS.'/backend/zakcia_detailed.html');
		} elseif(isset($_POST['akcia_action']) && $_POST['akcia_action'] == 'update_akcia') {
			// Изменения сохранить
			$id = $_POST['akciaID'];
			$name = $_POST['akcia_name'];
			$start = $_POST['akcia_start'];
			$end = $_POST['akcia_end'];
			$text = $_POST['za_body'];
			$enabled = $_POST['akcia_enabled'] == 'on' ? 1:0;
			$this->update_akcia($id, $name, $start, $end, $text, $enabled); 

			$this->update_table($smarty);

		} elseif(isset($_POST['akcia_action']) && $_POST['akcia_action'] == 'save_akcia') {
			// Сохранение новой акции
			$name = $_POST['akcia_name'];
			$start = $_POST['akcia_start'];
			$end = $_POST['akcia_end'];
			$text = $_POST['za_body'];
			$enabled = $_POST['akcia_enabled'] == 'on' ? 1:0;
			$this->add_akcia($name, $start, $end, $text, $enabled); 
			$this->update_table($smarty);
			
		} elseif(isset($_GET['akciaID'])) {
			$akcia = $this->get_akcia_by_id(intval($_GET['akciaID']));
			$smarty->assign('AkciaData', $akcia);
			$smarty->display(DIR_TPLS.'/backend/zakcia_detailed.html');
		} elseif(isset($_GET['delete'])) {
			$id = intval($_GET['delete']);
			$this->akcia_delete($id);
			$this->update_table($smarty);
		} elseif(isset($_GET['unpublish'])) {
			$id = intval($_GET['unpublish']);
			$this->akcia_unpublish($id);
			$this->update_table($smarty);
		} elseif(isset($_GET['publish'])) {
			$id = intval($_GET['publish']);
			$this->akcia_publish($id);
			$this->update_table($smarty);
		} else {
			// Вывод списка
			$this->update_table($smarty);
		}
	}

	function update_table($smarty) {
		$akcia_list = $this->get_akcia();
		$smarty->assign('GridRows', $akcia_list);
		$smarty->display(DIR_TPLS.'/backend/zakcia.html');
	}

	function get_akcia() {
		$sql = "select * from SC_akcia";
		$q = db_phquery($sql);
		$res = array();
		while( $row = db_fetch_row($q) ) {
			$res[] = $row;			
		}
		return $res;
	}

	function get_akcia_by_id($id) {
		$sql = "select * from SC_akcia where akciaID = $id";
		$q = db_phquery($sql);
		$res = array();
		while( $row = db_fetch_row($q) ) {
			$res[] = $row;			
		}
		return $res;
	}

	function add_akcia($name, $start, $end, $text, $enabled) {
		
		$sql = "insert into SC_akcia (name_ru, text_ru, enabled, start_date, end_date) values ('$name', '$text', $enabled, str_to_date('$start', '%d.%m.%Y %H:%i'), str_to_date('$end','%d.%m.%Y %H:%i'))";
		db_phquery($sql);
	}

	function update_akcia($id, $name, $start, $end, $text, $enabled) {
		$sql = "update SC_akcia set name_ru='$name', text_ru='$text', enabled=$enabled, start_date=str_to_date('$start', '%d.%m.%Y %H:%i'), end_date=str_to_date('$end', '%d.%m.%Y %H:%i') where akciaID=$id";
		db_phquery($sql);
	}

	function akcia_delete($id) {
		$sql = "delete from SC_akcia where akciaID=$id";
		db_phquery($sql);
	}

	function akcia_publish($id) {
		$sql = "update SC_akcia set enabled=1 where akciaID=$id";
		db_phquery($sql);
	}

	function akcia_unpublish($id) {
		$sql = "update SC_akcia set enabled=0 where akciaID=$id";
		db_phquery($sql);
	}
}	
ActionsController::exec('ZAkciaController');
