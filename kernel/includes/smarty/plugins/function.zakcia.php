<?php
/**
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     zakcia
 * Purpose:  akcia
 * -------------------------------------------------------------
 */
function smarty_function_zakcia($params, &$smarty) {
	if((isset($_GET['categoryID']) && (int)($_GET['categoryID']) === 33424 && isset($_SESSION['log'])) || (!isset($_GET['categoryID']) && isset($_SESSION['log']))) {

		$text = get_akcia_body();
		if($text) {
			$smarty->assign("za_body", $text['text_ru']);
			$seconds = (int)($text['act_time']);

			$smarty->assign("seconds", $seconds);
			$output = $smarty->fetch(DIR_TPLS."/frontend/zakcia.html");
			return $output;
		} else return '';
	} else return '';
}

function get_akcia_body() {
	// $q = db_query("SELECT text_ru, TIME_TO_SEC(TIMEDIFF(`end_date`, now())) as act_time from SC_akcia where end_date>now() and start_date<now() and enabled=1");
	$q = db_query('SELECT text_ru, TIME_TO_SEC(TIMEDIFF(`end_date`, Now())) as act_time from SC_akcia where end_date > now() and enabled
 = 1');
	$r = db_fetch_row($q);
    return $r;
}
