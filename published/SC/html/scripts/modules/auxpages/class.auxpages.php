<?php
function cptsettingview_auxpagegroup($params){

	$moduleInstance = &ModulesFabric::getModuleObjByKey('aux_pages');
	/*@var $moduleInstance AuxPages*/

	$pages = $moduleInstance->__getEnabledPages();
	$params['options'] = array();
	foreach ($pages as $page){
		$params['options'][$page['id']] = $page['name'];
	}

	if (is_string($params['value']))$params['value'] = explode(':', $params['value']);
	return cptsettingview_checkboxgroup($params);
}

function cptsettingserializer_auxpagegroup($params, $post){

	$Register = &Register::getInstance();

	if(!$Register->is_set('__AUXNAV_SERIALIZED') && is_array($post[$params['name']])){
		$post[$params['name']] = implode(':', $post[$params['name']]);
		$reg = 1;
		$Register->set('__AUXNAV_SERIALIZED', $reg);
	}
	return cptsettingserializer_checkboxgroup($params, $post);
}

class AuxAdministrationController extends ActionsController {

	function save_order(){

		$scan_result = scanArrayKeysForID($_POST, 'priority');
		$sql = '
			UPDATE ?#AUX_PAGES_TABLE SET aux_page_priority=? WHERE aux_page_ID=?
		';

		foreach ($scan_result as $aux_id=>$scan_info){

			db_phquery($sql, $scan_info['priority'], $aux_id);
		}

		Message::raiseAjaxMessage(MSG_SUCCESS, '', 'order_saved');
		die;
	}

	function main(){

		$moduleEntry = &$this->__params['module'];
		/*@var $moduleEntry AuxPages*/

		global $smarty;
		set_query('safemode=','',true);

		if ( isset($_GET['delete']) ){

			safeMode(true);
			$moduleEntry->auxpgDeleteAuxPage( $_GET['delete'] );
			RedirectSQ('delete=');
		}

		if ( isset($_GET['add_new']) ){

			if ( isset($_POST['save']) ){
				$AuxDivision = new Division();
				$max_priority = db_phquery_fetch(DBRFETCH_FIRST, 'SELECT MAX(aux_page_priority) FROM ?#AUX_PAGES_TABLE')+1;
				if(!isset($_POST['aux_page_slug'])||trim($_POST['aux_page_slug'])==''){
					$_POST['aux_page_slug'] = LanguagesManager::ml_getFieldValue('aux_page_name',$_POST);
					$_POST['aux_page_slug'] = make_clean_slug($_POST['aux_page_slug'],'',AUX_PAGES_TABLE,'aux_page_slug');
				}else{
					$_POST['aux_page_slug'] = make_clean_slug($_POST['aux_page_slug'],'',AUX_PAGES_TABLE,'aux_page_slug');
				}
				$AuxID = $moduleEntry->auxpgAddAuxPage( $_POST, $_POST, $_POST, $_POST, isset($_POST['aux_page_enabled'])?1:0, $max_priority,$_POST['aux_page_slug'] );
				$TitlePageID = DivisionModule::getDivisionIDByUnicKey('TitlePage');

				$moduleEntry->addAuxPageNameLocal($AuxID, $_POST);

				$AuxDivision->setName($moduleEntry->getAuxPageLocalID($AuxID));
				$AuxDivision->setEnabled(0);
				$AuxDivision->setParentID($TitlePageID);

				$AuxDivision->setUnicKey('auxpage_'.$_POST['aux_page_slug']);
				//$AuxDivision->setUnicKey('auxpage_'.$AuxID);//or set aux_slug
				$AuxDivision->save();
				$AuxDivision->addInterface($moduleEntry->getConfigID().'_auxpage_'.$AuxID);

				RedirectSQ('add_new=');
			}
			$smarty->assign( 'add_new', 1 );
		}elseif ( isset($_GET['edit']) ){
			if ( isset($_POST['save']) ){

				safeMode(true);
				if(!isset($_POST['aux_page_slug'])||strlen(trim($_POST['aux_page_slug']))==0){
					$_POST['aux_page_slug'] = LanguagesManager::ml_getFieldValue('aux_page_name',$_POST);
					$_POST['aux_page_slug'] = make_clean_slug($_POST['aux_page_slug'],'auxpage_',DIVISIONS_TBL,'xUnicKey','xName',$moduleEntry->getAuxPageLocalID($_GET['edit']));
				}else{
					$_POST['aux_page_slug'] = make_clean_slug($_POST['aux_page_slug'],'auxpage_',DIVISIONS_TBL,'xUnicKey','xName',$moduleEntry->getAuxPageLocalID($_GET['edit']));
				}
				$moduleEntry->auxpgUpdateAuxPage( $_GET['edit'], $_POST, 	$_POST,$_POST, $_POST, isset($_POST['aux_page_enabled'])?1:0, $_POST['aux_page_slug']);
				$moduleEntry->updateAuxPageNameLocal( $_GET['edit'], $_POST);
				RedirectSQ('edit=');
			}



			$aux_page = $moduleEntry->auxpgGetAuxPage( $_GET['edit'] );

			$smarty->assign( 'aux_page', $aux_page );

			$smarty->assign( 'edit', 1 );
		}else{

			$aux_pages = $moduleEntry->auxpgGetAllPageAttributes();
			$smarty->assign( 'aux_pages', $aux_pages );
		}

		//set sub-department template
		$smarty->assign('admin_sub_dpt', 'conf_aux_pages.tpl.html');
	}
}


class AuxPages extends ComponentModule {

	function getInterface(){

		$Args = func_get_args();
		$_InterfaceName = array_shift($Args);
		$Results = array();
		if(isset($this->Interfaces[$_InterfaceName])){

			$SubPatterns = array();
			if(preg_match('|auxpage_(\d+)|',$_InterfaceName, $SubPatterns)){

				global $smarty;
				$AuxInfo = $this->auxpgGetAuxPage($SubPatterns[1]);
				if(!$AuxInfo['aux_page_enabled'])RedirectSQ('?');
				$page_title = $AuxInfo["aux_page_name"]." ― ".CONF_SHOP_NAME;
				$meta_tags = "";
				if  ( $AuxInfo["meta_description"] != "" )
					$meta_tags .= '<meta name="description" content="'.xHtmlSpecialChars($AuxInfo["meta_description"]).'">'."\n";
				if  ( $AuxInfo["meta_keywords"] != "" )
					$meta_tags .= '<meta name="keywords" content="'.xHtmlSpecialChars($AuxInfo["meta_keywords"]).'">'."\n";

				$smarty->assign("page_title",	$page_title );
				$smarty->assign("page_meta_tags", $meta_tags );
				$smarty->assign('aux_page', $AuxInfo['aux_page_text']);
				$smarty->assign('main_content_template', $this->getTemplatePath('frontend/aux_page.html'));
				return '';
			}
			$ParamsNum = count($Args);
			$EvalParams = array();

			for($_i = 0; $_i<$ParamsNum; $_i++){

				$EvalParams[] = '$Args['.$_i.']';
			}

			$this->__clearInterfaceStack();
			$this->__pushToStack('info', $this->Interfaces[$_InterfaceName]);
			$this->__pushToStack('call_params', $Args);

			eval('$Results = $this->'.$this->Interfaces[$_InterfaceName]['method'].'('.implode(',', $EvalParams).');');
		}
		return $Results;
	}

	function initInterfaces(){

		$this->Interfaces = array(
			'fauxpage'=> array(
				'name' => 'pgn_auxpages',
				'method' => 'methodFAuxPage',
			),
			'bauxpage'=> array(
				'name' => 'pgn_auxpages_admin',
				'method' => 'methodBAuxPage',
			),
		);

		$sql = '
			SELECT *, '.LanguagesManager::sql_prepareField('aux_page_name').' AS aux_page_name FROM ?#AUX_PAGES_TABLE ORDER BY aux_page_name ASC
		';
		$Result = $this->dbHandler->ph_query($sql);
		while ($_Row = $Result->fetchAssoc()) {

			$this->Interfaces['auxpage_'.$_Row['aux_page_ID']] = array(
				'name' => $_Row['aux_page_name'],
				'method' => 'auxpage_'.$_Row['aux_page_ID'],
			);
		}

		$this->__registerComponent('auxpages_navigation', 'cpt_lbl_auxpages_navigation', array(TPLID_GENERAL_LAYOUT), null,
			array(
				'select_pages' => array('type' => 'select', 'params' => array('name' => 'select_pages', 'title' => '', 'options' => array('all' => 'cpt_lbl_selectaux_type_all', 'selected' => 'cpt_lbl_selectaux_type_selected'), 'onchange' => 'var objDiv = getLayer("cpt-layer-auxpages"); objDiv.style.display=select_getCurrValue(this)=="all"?"none":"";', 'default_value' => 'all')),
				'auxpages' => array('type' => 'auxpagegroup', 'params' => array('name' => 'auxpages', 'title'=> 'cpt_lbl_selectauxpages','value'=> '', 'options'=> array(), 'before_load' => '<script type="text/javascript">var objDiv = getLayer("cpts-select_pages-select_pages");getLayer("cpt-layer-auxpages").style.display = select_getCurrValue(objDiv)=="all"?"none":"";</script>')),
				'view' => array('type' => 'radiogroup', 'params' => array('name' => 'view', 'title'=> 'cpt_lbl_view','value'=> 'vertical', 'options'=> array('vertical' => 'cpt_lbl_vertical', 'horizontal' => 'cpt_lbl_horizontal'))),
				));
	}

	function __getEnabledPages(){

		$sql = 'SELECT '.LanguagesManager::sql_prepareField('aux_page_name').' AS name, `aux_page_ID` AS `id`, `aux_page_slug` FROM ?#AUX_PAGES_TABLE WHERE aux_page_enabled=1 ORDER BY `aux_page_priority` ASC';
//		return db_phquery_array($sql);
		$Register = &Register::getInstance();
		$DBHandler = &$Register->get(VAR_DBHANDLER);
		/* @var $DBHandler DataBase */

		$DBRes = $DBHandler->ph_query($sql);

		$pages = $DBRes->fetchArrayAssoc();

		return $pages;
	}

	function cpt_auxpages_navigation(){

		list($local_settings) = $this->__getFromStack('call_params');
		if(isset($local_settings['local_settings']))$local_settings = $local_settings['local_settings'];

		$pages = $this->__getEnabledPages();

		if(!count($pages))return ;

		$allowed_pages = explode(':', $local_settings['auxpages']);
		print '<ul class="'.($local_settings['view'] == 'horizontal'?'horizontal':'vertical').'">';
		foreach ($pages as $page){
			if($local_settings['select_pages'] == 'selected' && !in_array($page['id'], $allowed_pages))continue;
			//print '<li><a href="'.xHtmlSetQuery('?ukey=auxpage_'.$page['id']).'">'.xHtmlSpecialChars($page['name']).'</a></li>';
			print '<li><a href="'.xHtmlSetQuery('?ukey=auxpage_'.($page['aux_page_slug']?$page['aux_page_slug']:$page['id'])).'">'.xHtmlSpecialChars($page['name']).'</a></li>';
		}
		print '</ul>';
	}

	function methodBAuxPage(){

		ActionsController::exec('AuxAdministrationController', array(ACTCTRL_POST, ACTCTRL_GET, ACTCTRL_AJAX, ACTCTRL_CUST), array('module' => &$this));
	}

	function methodFAuxPage(){

		global $smarty;
		$aux_page = $this->auxpgGetAuxPage( $_GET['show_aux_page'] );

		if ( $aux_page ){

			$smarty->assign('page_body', $aux_page['aux_page_text'] );
			$smarty->assign('show_aux_page', $_GET['show_aux_page'] );
			$smarty->assign('main_content_template', 'show_aux_page.tpl.html' );
		}
		else
		{
			$smarty->assign('main_content_template', 'page_not_found.tpl.html' );
		}
	}

	function auxpgGetAllPageAttributes(){

		$sql = '
			SELECT * FROM ?#AUX_PAGES_TABLE ORDER BY aux_page_priority ASC
		';
	 	$q = db_phquery($sql);
		$data = array();
		while( $row = db_fetch_row( $q ) ){

			LanguagesManager::ml_fillFields(AUX_PAGES_TABLE, $row);
			$data[] = $row;
		}
		return $data;
	}

    /**
     * @param $aux_page_ID
     * @return array|string
     */
    function auxpgGetAuxPage( $aux_page_ID )
    {
		$sql = 'SELECT * FROM ?#AUX_PAGES_TABLE WHERE aux_page_ID=?';
	  	$q = db_phquery($sql,$aux_page_ID);
		$row=db_fetch_row($q);

		LanguagesManager::ml_fillFields(AUX_PAGES_TABLE, $row);

		if(!strlen($row['aux_page_slug'])) {
			$row['aux_page_slug'] = $row['aux_page_ID'];
		}
        global $smarty;
        $a = $smarty->get_template_vars('CurrentDivision');
        $ajax = false;

        if ('auxpage' === substr($a['ukey'], 0, 7)) {
            if (array_key_exists('count_show', $_POST) || $_POST['count_show'] || $_POST['p']) {
                $ajax = true;
            }
            if (array_key_exists('ajax', $_POST)) {
                $ajax = (int)$_POST['ajax'];
            }
            switch ($a['ukey']) {
                case 'auxpage_divoland':
                    //include(DIR_ROOT . '/../../../../popup/search_by_conc/conc_auxpages.php');
                    //$row = transform_auxpage_conc($a['ukey'], $row);
                    //break;
                case 'auxpage_mixtoys':
                    //include(DIR_ROOT . '/../../../../popup/search_by_conc/conc_auxpages.php');
                    //$row = transform_auxpage_conc($a['ukey'], $row);
                    //break;
                case 'auxpage_dreamtoys':
                    //include(DIR_ROOT . '/../../../../popup/search_by_conc/conc_auxpages.php');
                    //$row = transform_auxpage_conc($a['ukey'], $row);
                    //break;
                case 'auxpage_kindermarket':
                    $row = $this->transform_auxpage_conc($a['ukey'], $row);
                    //include(DIR_ROOT . '/../../../../popup/search_by_conc/conc_auxpages.php');
                    //$row = transform_auxpage_conc($a['ukey'], $row);
                    break;
                default:
                    //include(DIR_ROOT . '/../../../../popup/ajax_auxpages.php');
                    //$row = transform_auxpage($a['ukey'], $row, $ajax);
                    $row = $this->transform_auxpage($a['ukey'], $row, $ajax);
            }
        }
        if ($ajax) {

            if (!$row) {
                echo json_encode(array(
                    'result' => 'finish'
                ));
                //echo('Nofing to show!');
            } else {
                echo json_encode(array(
                    'result' => 'success',
                    'html'   => $row
                ));
                //echo $row;
            }

            return false;
        } else {
           return $row;
	    }
    }

	function auxpgUpdateAuxPage( $aux_page_ID, $aux_page_name, $aux_page_text, $meta_keywords, $meta_description, $aux_page_enabled,$aux_page_slug ){
		$fields='';
		$name_inj = LanguagesManager::sql_prepareFields('aux_page_name', $aux_page_name);
		foreach ($name_inj['fields'] as $field) $fields.=$field.'=?,';
		$text_inj = LanguagesManager::sql_prepareFields('aux_page_text', $aux_page_text);
		foreach ($text_inj['fields'] as $field) $fields.=$field.'=?,';
		$mkeywords_inj = LanguagesManager::sql_prepareFields('meta_keywords', $meta_keywords);
		foreach ($mkeywords_inj['fields'] as $field) $fields.=$field.'=?,';
		$mdescription_inj = LanguagesManager::sql_prepareFields('meta_description', $meta_description);
		foreach ($mdescription_inj['fields'] as $field) $fields.=$field.'=?,';
		$sql = 'UPDATE ?#AUX_PAGES_TABLE SET '.$fields.'`aux_page_enabled`=?, `aux_page_slug`=?	WHERE aux_page_ID=?';
		db_phquery_array($sql,$name_inj['values'],$text_inj['values'],$mkeywords_inj['values'],$mdescription_inj['values'],$aux_page_enabled, $aux_page_slug, $aux_page_ID);

	}

	function auxpgAddAuxPage($aux_page_name, $aux_page_text, $meta_keywords, $meta_description, $aux_page_enabled, $aux_page_priority,$aux_page_slug  ){

		$name_inj = LanguagesManager::sql_prepareFields('aux_page_name', $aux_page_name, true);
		$text_inj = LanguagesManager::sql_prepareFields('aux_page_text', $aux_page_text,true);
		$mkeywords_inj = LanguagesManager::sql_prepareFields('meta_keywords', $meta_keywords,true);
		$mdescription_inj = LanguagesManager::sql_prepareFields('meta_description', $meta_description,true);
		$fields=$name_inj['fields_list'].','.$text_inj['fields_list'].',';
		$fields.=$mkeywords_inj['fields_list'].','.$mdescription_inj['fields_list'];
		$values_place=str_repeat('?,',
			count($name_inj['values'])+count($text_inj['values'])+
			count($mkeywords_inj['values'])+count($mdescription_inj['values']));

		$sql = "INSERT ?#AUX_PAGES_TABLE ( {$fields}, aux_page_enabled, aux_page_priority, aux_page_slug ) ";
		$sql.="VALUES({$values_place}?,?,?)";

		db_phquery_array($sql,$name_inj['values'],$text_inj['values'],$mkeywords_inj['values'],$mdescription_inj['values'],$aux_page_enabled, $aux_page_priority,$aux_page_slug);
		return db_insert_id();
	}

	function auxpgDeleteAuxPage( $aux_page_ID ){
		$DivIDs = DivisionModule::getDivisionIDsWithInterface($this->getConfigID().'_auxpage_'.$_GET['delete']);
		DivisionModule::disconnectInterfaces(array($this->getConfigID()=>array('auxpage_'.$_GET['delete'])));
		foreach ($DivIDs as $_ID){
			$Division = new Division($_ID);
			$Division->delete();
		}
		$sql = '
			DELETE FROM ?#AUX_PAGES_TABLE WHERE aux_page_ID=?
		';
		db_phquery($sql, $aux_page_ID);

		$languages = LanguagesManager::getLanguages();
		foreach ($languages as $languageEntry){
			/*@var $languageEntry Language*/
			$languageEntry->deleteLocal($this->getAuxPageLocalID($aux_page_ID));
		}
	}

	function updateAuxPageNameLocal($aux_page_ID, $data){
		$divisionID = DivisionModule::getDivisionIDByName('pgn_ap_'.$aux_page_ID);
		if($divisionID){
			$AuxDivision = new Division();
			/* @var $AuxDivision Division */
			$AuxDivision->load($divisionID);
			$AuxDivision->setUnicKey('auxpage_'.(strlen($data['aux_page_slug'])?$data['aux_page_slug']:$aux_page_ID));
			$AuxDivision->save();
		}

		$languages = LanguagesManager::getLanguages();
		foreach ($languages as $languageEntry){
			/*@var $languageEntry Language*/
			$languageEntry->updateLocal($this->getAuxPageLocalID($aux_page_ID), isset($data['aux_page_name'.'_'.$languageEntry->iso2])?$data['aux_page_name'.'_'.$languageEntry->iso2]:'');
		}
	}

	function addAuxPageNameLocal($aux_page_ID, $data){

		$languages = LanguagesManager::getLanguages();
		foreach ($languages as $languageEntry){
			/*@var $languageEntry Language*/
			$languageEntry->addLocal($this->getAuxPageLocalID($aux_page_ID), isset($data['aux_page_name'.'_'.$languageEntry->iso2])?$data['aux_page_name'.'_'.$languageEntry->iso2]:'', LOCALTYPE_HIDDEN, 'lsgr_general');
		}
	}

	function getAuxPageLocalID($aux_page_ID){

		return "pgn_ap_{$aux_page_ID}";
	}

    function transform_auxpage($name, $text, $ajax)
    {
        $log = isset($_SESSION['log']) ? $_SESSION['log'] : '';
        $vip = isset($_SESSION['cs_vip']) ? $_SESSION['cs_vip'] : '';

        $CustomerID = '';
        $may_order = '';
        $newitems = '';
        $shop_count_cart = '';
        if ($log !== '') {
            $may_order = isset($_SESSION['cs_may_order']) ? $_SESSION['cs_may_order'] : '0';
        }

        if (array_key_exists('auxpage', $_SESSION)) {
            unset($_SESSION['auxpage']);
        }
        $_SESSION['auxpage'] = substr($name, 8);
        $auxpage = $_SESSION['auxpage'];

        if ($may_order) {

            if (isset($_SESSION['cs_id'])) {
                $buy_enabled = true;
                $CustomerID = $_SESSION['cs_id'];
            } else {
                $buy_enabled = false;
            }

            //Собираем правила сортировки
            $default_sort = '';
            $sort = 'name';
            $ajax_sort = $sort;
            $direction = '';
            $sort_class_name = 'z_sort_inactive';
            $sort_class_pc = 'z_sort_inactive';
            $sort_class_bonus = 'z_sort_inactive';
            $sort_class_price = 'z_sort_inactive';
            $arrow_name = 'z_sort_asc';
            $arrow_product_code = 'z_sort_asc';
            $arrow_bonus = 'z_sort_asc';
            $arrow_price = 'z_sort_asc';
            $new_dir = 'ASC';

            if (isset($_GET['sort'])) {
                $sort = $_GET['sort'];
                $ajax_sort = $sort;
            } elseif (isset($_POST['sort'])) {
                $sort = $_POST['sort'];
            }

            if (isset($_REQUEST['direction'])) {
                $direction = $_REQUEST['direction'];
                // NEW DIRECTION
                if ($direction === 'ASC') {
                    $new_dir = 'DESC';
                    $arrow_name = 'z_sort_desc';
                    $arrow_product_code = 'z_sort_desc';
                    $arrow_bonus = 'z_sort_desc';
                    $arrow_price = 'z_sort_desc';
                }
            }
            // NAME

            if ($sort === 'name_ru') {
                $sort_class_name = 'z_sort_active';
                $arrow_name = ($direction === 'DESC') ? 'z_sort_desc' : 'z_sort_asc';
            }
            // PRODUCT_CODE
            if ($sort === 'product_code') {
                $sort_class_pc = 'z_sort_active';
                $arrow_product_code = ($direction === 'DESC') ? 'z_sort_desc' : 'z_sort_asc';
            }
            // BONUS
            if ($sort === 'Bonus') {
                $arrow_bonus = ($direction === 'DESC') ? 'z_sort_desc' : 'z_sort_asc';
                $sort_class_bonus = 'z_sort_active';
            }
            // PRICE
            if ($sort === 'Price') {
                $sort_class_price = 'z_sort_active';
                $arrow_price = ($direction === 'DESC') ? 'z_sort_desc' : 'z_sort_asc';
            }
            if ($sort === 'name') {
                $ajax_sort = $sort;
                $sort = 'name_ru';
                $default_sort = 't1.sort_order, t1.ukraine, t1.categoryID,';
            }

            $date = 0;
            $selected_date = '';
            if (isset($_GET['date']) && (int)$_GET['date'] > 0) {
                $date = (int)$_GET['date'];
                $selected_date = " AND date = $date";
            } elseif (isset($_POST['date']) && (int)$_POST['date'] > 0) {
                $date = (int)$_POST['date'];
                $selected_date = " AND t2.date = $date";
            }
            
            // Количество выводимых товаров на один запрос
            //$add_count = 0;
            $tov_count = 50;

            // Количество выводимых товаров на странице
            $p_count = 150;

            //if (isset($_POST['count_add'])) {
            //    $add_count = (int)$_POST['count_add'];
            //}

            $query1 = '
                        SELECT settings_value
                        FROM SC_settings
                        WHERE settings_constant_name=\'CONF_PRODUCTS_PER_PAGE\'';
            $res1 = mysql_query($query1) or die(mysql_error().$query1);
            $settings1 = mysql_fetch_object($res1);

            if ($settings1) {
                $tov_count = (int)($settings1->settings_value);
            }

            $query2 = 'SELECT settings_value
                  FROM SC_settings
                  WHERE settings_constant_name=\'CONF_NEWTOV_COUNT\'';
            $res2 = mysql_query($query2) or die(mysql_error().$query2);
            $settings2 = mysql_fetch_object($res2);

            if ($settings2) {
                $p_count = (int)($settings2->settings_value);
            }

            // Общее количество товаров
            $query = "
                    SELECT count(*) AS tov_all_count
                    FROM SC_product_list_item
                    WHERE list_id = 'newitemspostup' $selected_date";
            $res = mysql_query($query) or die(mysql_error().$query);
            $product_list_item = mysql_fetch_object($res);
            $tov_all_count = (int)($product_list_item->tov_all_count);

            $start_row = 0;

            if (isset($_REQUEST['p'])) {
                $start_row = (int)$_REQUEST['p'];
                $start_row = ($start_row === -1) ? 0 : $start_row;
            }

            $url = '/auxpage_new_items/'.$date.'/';
            $out = SimpleNavigator($tov_all_count, $start_row, $p_count, $url, $out);
            $start = $start_row;
            $direction_nav = 'ASC';
            if ($direction) {
                $direction_nav = $direction;
            }
            $newitems_start = "
                                <div
                                    id='light-pagination'
                                    class='simple-pagination compact-theme'
                                    data-items       = $tov_all_count
                                    data-itemsOnPage = $p_count
                                    data-add         = $tov_count
                                    data-show        = 0
                                    data-page        = $start
                                    data-date        = $date
                                    data-sort        = $ajax_sort
                                    data-direction   = $direction_nav
                                >$out</div>
                                ";

            $newitems_start .= '
                        <div class=shapka>
                            <table class=cs_product_info  style="padding-left: 5px">
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class='.$sort_class_name.'>
                                                <div class="arbopr sort_name">
                                                    <a href="/auxpage_new_items/'.$date.'/name_ru/'.$new_dir.'/">Наименование</a>
                                                </div>
                                                <div class='.$arrow_name.'></div>
                                            </div>
                                        </td>
                                        <td width=100px>
                                            <div class="'.$sort_class_pc.' ">
                                                <div class=arbopr>
                                                    <a href="/auxpage_new_items/'.$date.'/product_code/'.$new_dir.'/">Артикул</a>
                                                </div>
                                                <div class='.$arrow_product_code.'></div>
                                            </div>
                                        </td>
                                        <td width=60px>
                                            <div class='.$sort_class_bonus.'>
                                                <div class=arbopr>
                                                    <a href="/auxpage_new_items/'.$date.'/Bonus/'.$new_dir.'/">Баллы</a>
                                                </div>
                                                <div class='.$arrow_bonus.'></div>
                                            </div>
                                        </td>
                                        <td width=80px>
                                            <div class='.$sort_class_price.'>
                                                <div class=arbopr>
                                                    <a href="/auxpage_new_items/'.$date.'/Price/'.$new_dir.'/">Цена</a>
                                                </div>
                                                <div class='.$arrow_price.'></div>
                                            </div>
                                        </td>
                                        <td width=110px>
                                            <div class=ost>Остаток</div>
                                        </td>
                                        <td width=65px>
                                            <div class=zakaz>Заказано</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class=scroll-pane1>
                        <div id=content>
            ';
            if ($vip) {
                $newitems_start .= '<hr style=\'border-color: coral\'>';
            } else {
                $newitems_start .= '<div class=delimiter></div>';
            }

            /**************************************************************************************************************/
            if ($vip) {
                $auxpages = array('divoland', 'mixtoys', 'dreamtoys', 'kindermarket');

                foreach ($auxpages as $aux) {
                    $query = "SELECT code, code_1c FROM Conc_search__$aux";
                    $res = mysql_query($query) or die(mysql_error().$query);
                    $codes_multi[$aux] = array();

                    while ($Codes = mysql_fetch_object($res)) {
                        $codes_multi[$aux][$Codes->code_1c] = $Codes->code;
                    }
                }
            }
            /**************************************************************************************************************/
            $query = 'SELECT categoryID, name_ru FROM SC_categories';
            $res = mysql_query($query) or die(mysql_error().$query);
            $category_name = array();
            while ($Categories = mysql_fetch_object($res)) {
                $category_name[$Categories->categoryID] = $Categories->name_ru;
            }

            $query = 'SELECT productID FROM SC_products WHERE enabled = 1 ORDER BY code_1c DESC LIMIT 500';
            $res = mysql_query($query) or die(mysql_error().$query);
            $new = array();
            while ($New_items = mysql_fetch_object($res)) {
                $new[$New_items->productID] = $New_items->productID;
            }

            if (isset($_POST['count_show'])) {
                $start_row = (int)$_POST['count_show'];
            }

            $query = "
                    SELECT
                          t1.productID, t1.categoryID, t1.product_code,  t1.code_1c, t1.sort_order, t1.Price,
                          t1.SpecialPrice, t1.list_price, t1.skidka, t1.name_ru, t1.default_picture, t1.ostatok,
                          t1.Bonus, t1.zakaz, t1.slug, t3.filename, t3.thumbnail
                    FROM SC_products t1
                    LEFT JOIN SC_product_list_item t2  USING(productID)
                    LEFT JOIN SC_product_pictures t3 ON t1.default_picture = t3.photoID
                    WHERE t2.list_id = 'newitemspostup' $selected_date
                    ORDER BY $default_sort  t1.$sort $direction
                    LIMIT $start_row, $tov_count
                ";
            $res = mysql_query($query) or die(mysql_error().$query);

            if ($CustomerID) $shop_count_cart = get_shop_couts($CustomerID);

            while ($Product = mysql_fetch_object($res)) {
                /**********************************************************************************************************/
                $add2cart_conc = '';

                if ($vip) {
                    //$codes_multi = array();
                    $auxpages = array('divoland', 'mixtoys', 'dreamtoys', 'kindermarket');
                    foreach ($auxpages as $aux) {
                        if ($matched_product = $codes_multi[$aux][$Product->code_1c]) {
                            $query3
                                = "SELECT
                        category, code, product_code, name, price_uah
                    FROM
                        Conc__$aux
                    WHERE
                        code LIKE '$matched_product'";
                            $res3 = mysql_query($query3) or die(mysql_error().$query3);

                            if ($M_Product = mysql_fetch_object($res3)) {
                                $price_diff = round(($Product->Price / $M_Product->price_uah - 1) * 100, 1);
                                $marked = ($price_diff > 0) ? 'red' : 'green';
                                $mark_conc = ($price_diff > 0) ? 'font-weight:bold;background:yellow; box-shadow:
                             2px 2px 4px #9999aa;' : '';
                                $add2cart_conc .= "
												<div style='font-size: 11px; padding-left: 5px;clear: both' id=$aux$M_Product->code>
													<div style='min-width: 75px !important;
													color:#03A9F4;$mark_conc;text-transform: capitalize; float: left'>$aux:</div>
													<div style='min-width: 95px; float: left'>цена <span style='color:$marked;'>$M_Product->price_uah</span> &#8372; | </div>
													<div style='float: left'>разница <span style='color:$marked;font-weight:700'>$price_diff%</span></div>
												</div>
											";
                            }
                        }
                    }
                }
                /**********************************************************************************************************/
                $price = show_price(ZCalcPrice($Product->Price, $Product->SpecialPrice, $Product->skidka));
                $category = $category_name[$Product->categoryID];
                $label_new = ($new[$Product->productID]) ? '<div class="corner color_newitem"><span></span>Новинка!</div>' : '';
                //$label_new = '<div class="corner color_newitemspostup"><span></span>Новинка!</div>';
                //$zakaz = $Product->zakaz;
                $bonus = $Product->Bonus;

                $shop_count = 0;
                if (($shop_count_cart[$Product->productID])) {
                    $shop_count = $shop_count_cart[$Product->productID];
                }
                $add2cart = ($buy_enabled) ? "
                                        <table width=175px>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input class=cart_product_quantity id=qty$Product->productID name=product_qty title='Количество' value='' size=2 data-id=$Product->productID onkeypress='if (event.keyCode == 13){add_2cart(\"#qty$Product->productID\")}'>
                                                    </td>
                                                    <td style='vertical-align:middle;white-space:nowrap;'>
                                                        <div class=ostatok_div>&nbsp;$Product->ostatok&nbsp;шт.</div>
                                                    </td>
                                                    <td>
                                                        <button class=z_add_cart title='добавить в корзину' onclick='add_2cart(\"#qty$Product->productID\")' type=button>
                                                            <div id=zpid_$Product->productID  class=in_cart>
                                                                <div class='animated zoomInDown'>$shop_count</div>
                                                            </div>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        " : '';
                $q = '
                    SELECT count(*) AS pics_all_count
                    FROM SC_product_pictures
                    WHERE productID = '.$Product->productID;
                $r = mysql_query($q) or die(mysql_error().$q);
                $pics_count = mysql_fetch_object($r);
                $pics_all_count = (int)($pics_count->pics_all_count);
                $pics_for_slider = $pics_all_count - 1;
                if ($pics_for_slider > 0) {
                    $pictures = '
                        <div class="slider visual">
                            <div class=controls  data-pid='.URL_PRODUCTS_PICTURES.'/'.$Product->filename.'>
                                <div class="label prev_pic" onclick="changePic('.$Product->code_1c.',-1)"></div>
                                <div class="label next_pic" onclick="changePic('.$Product->code_1c.', 1)"></div>
                            </div>
                            <img id=pic'.$Product->code_1c.' data-pics='.$pics_for_slider.' data-current=0 src='.URL_PRODUCTS_PICTURES.'/'.$Product->thumbnail.' />
                            '.$label_new.'
                        </div>';
                } else {
                    $pictures = ((strlen($Product->thumbnail) > 4) && (strlen($Product->filename) > 4)) ? "
                            <div class=visual><a href='/product/$Product->slug'><img width=160 height=120 class=preview  alt='$Product->name_ru' src='".URL_PRODUCTS_PICTURES."/$Product->thumbnail' data-pid='".URL_PRODUCTS_PICTURES."/$Product->filename'></a>$label_new</div>" : "<div class=visual><a href='/product/$Product->slug'><img width=153 height=117 alt='no foto' src='/img/nophoto.jpg'></a>$label_new</div>";
                }
                //$pictures = '<div class=div_izobrag><img width=153 height=117 alt=\'no foto\' src=\'/img/nophoto.jpg\' /></div>';
                //if ($zakaz === 1) {
                //    $akc = "<span style='color: red;font-size: 14px;'><i>под заказ!</i></span><br /><span style='color: grey;'><b>$price</b></span>";
                //} else {
                //    $akc = "<span class=totalPrice>$price</span>";
                //}

                $newitems .= "
                                <table class=cs_product_info>
                                    <tbody>
                                        <tr>
                                            <td width=165px>
                                                $pictures
                                            </td>
                                            <td>
                                                <div class='productname newpostup'>
                                                    <a href='/product/$Product->slug'>$Product->name_ru</a><br>
                                                    <small>категория: &laquo;$category&raquo;</small>
                                                </div>
                                                $add2cart_conc
                                            </td>
                                            <td width=100px>
                                                <a href='/product/$Product->slug'>$Product->product_code</a>
                                            </td>
                                            <td width=60px>
                                                <div class=totalPrice>$bonus</div>
                                            </td>
                                            <td width=80px>
                                                <div class=totalPrice>$price</div>
                                            </td>
                                            <td width=175px>
                                                $add2cart
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                              ";
                if ($vip) {
                    $newitems .= '<hr style=\'border-color: coral\'>';
                } else {
                    $newitems .= '<div class=delimiter></div>';
                }
            }
            $out_end = SimpleNavigator($tov_all_count, $start, $tov_count, $url, $out_end);
            $newitems .= '
                                </div>';
            //            $newitems_end = "</div><div
            //                                    id='light-pagination'
            //                                    class='simple-pagination compact-theme'
            //                                    data-items       = $tov_all_count
            //                                    data-itemsOnPage = $p_count
            //                                    data-add         = $tov_count
            //                                    data-show        = 0
            //                                    data-page        = $start
            //                                    data-sort        = $ajax_sort
            //                                    data-direction   = $direction_nav
            //                                >$out_end</div>";
            $newitems_end = '</div><div style="text-align: right;">
                                        <button
                                            class="addall_pp blue-button" onclick="add_all2cart();">Заказать все</button>
                                    </div>';

            if (!$ajax) {
                foreach ($text as $key => $oneline) {
                    $text[$key] = str_replace('%new_list%', $newitems_start.$newitems.$newitems_end, $oneline);
                }
                if ($buy_enabled) {
                    return $text;
                } else {
                    return '</div>';
                }
            } else {
                return $newitems;
            }
        }
    }

    function transform_auxpage_conc($name, $text)
    {
        if (!isset($_SESSION['cs_vip']) || $_SESSION['cs_vip'] == 0) {
            return false;
        }
        if (array_key_exists('auxpage', $_SESSION)) {
            unset($_SESSION['auxpage']);
        }
        $_SESSION['auxpage'] = substr($name, 8);
        $auxpage = $_SESSION['auxpage'];
        // Количество выводимых товаров на странице
//    $query = 'SELECT settings_value FROM SC_settings where settings_constant_name=\'CONF_NEWTOV_COUNT\'';
//    $res = mysql_query($query) or die(mysql_error() . $query);
//    $settings = mysql_fetch_object($res);
//    if ($settings) {
//                    $tov_count = intval($settings->settings_value);
//    } else {
        $tov_count = 50;
//    }
        $div_cat = '';
        //$cat_div = '';
        //$selected_category = '';
        if (isset($_GET['div_cat'])) {
            $selected_category = $_GET['div_cat'];
            $div_cat = " AND category LIKE '$selected_category'";
            $cat_div = 'div_cat';
        } elseif (isset($_GET['div_par'])) {
            $selected_category = $_GET['div_par'];
            $div_cat = " AND parent LIKE '$selected_category'";
            $cat_div = 'div_par';
        }
        // Общее количество товаров
        $query = '
            SELECT count(*) AS tov_all_count
            FROM Conc__'.$auxpage.' WHERE enabled '.$div_cat;
        $res = mysql_query($query) or die(mysql_error().$query);
        $product_list_item = mysql_fetch_object($res);
        $tov_all_count = (int)$product_list_item->tov_all_count;
        if (isset($_GET['p']))
            $start_row = (int)$_GET['p'];
        else
            $start_row = 0;
        $url = '/'.$name.'/';
        //$pag_content = pagination($tov_all_count, $tov_count, 50, $start_row, $cat_div, $url, $selected_category);
        $out = SimpleNavigator($tov_all_count, $start_row, $tov_count, $url, $out);
        //$newitems    = $pag_content;
        $newitems = "<div class='simple-pagination compact-theme'>$out</div>";
        $newitems .= '</div>
                    <div class=scroll-pane1>';

        $query1 = "SELECT code, code_1c FROM Conc_search__$auxpage";
        $res1 = mysql_query($query1) or die(mysql_error().$query1);
        $codes_multi = array();
        while ($Codes = mysql_fetch_object($res1)) {
            $codes_multi[$Codes->code] = $Codes->code_1c;
        }

        $query = 'SELECT categoryID, name_ru FROM SC_categories';
        $res = mysql_query($query) or die(mysql_error().$query);
        $category_name = array();
        while ($Categories = mysql_fetch_object($res)) {
            $category_name[$Categories->categoryID] = $Categories->name_ru;
        }

        $order = 'name ASC';

        if ($div_cat === '') {
            $order = 'code DESC';
        }
        $query2 = "SELECT
					category, code, product_code, name, price_uah
				FROM
					Conc__$auxpage WHERE enabled $div_cat
				ORDER BY
					$order
				LIMIT
					$start_row, $tov_count";
        $res2 = mysql_query($query2) or die(mysql_error().$query2);

        while ($Product = mysql_fetch_object($res2)) {

            $category_conc = escape($Product->category);
            $name = escape($Product->name);
            $product_code = escape($Product->product_code);
            $replace_arr = array('+', '.', '\'', '/', '"', ',', '  ', '  ');
            $name_conc = str_replace($replace_arr, ' ', trim($name).' '.trim($product_code));;
            $name_conc = escape(str_replace(' ', '|', $name_conc), 'javascript');

            if ($matched_product = $codes_multi[$Product->code]) {
                $query3 = "SELECT categoryID, code_1c, product_code, name_ru, Price, enabled
                      FROM SC_products WHERE code_1c LIKE '$matched_product'";
                $res3 = mysql_query($query3) or die(mysql_error().$query3);

                if ($M_Product = mysql_fetch_object($res3)) {
                    $category = $category_name[$M_Product->categoryID];
                    $price_diff = round(($M_Product->Price / $Product->price_uah - 1) * 100, 1);
                    $marked = ($price_diff > 0) ? 'red' : 'green';
//                $disabled = 'line-height:65px;';
                    $disabled = '';
                    $button = 'gainsboro';

                    if (!$M_Product->enabled) {
                        $disabled .= 'color:grey;text-decoration:line-through;';
//                    $button = '#848484';
                    }
//                $analog = "
//                    <div class='productname newpostup' id=multi_$M_Product->code_1c $style>
//                        $M_Product->code_1c  [$M_Product->product_code]  $M_Product->name_ru
//                        <span style='color:$marked;'>$M_Product->Price</span> &#8372;  <br>
//                        разница: <span style='color:$marked;font-weight:700'>$price_diff%</span><br>
//                        <small>категория: &laquo;$category&raquo;</small>
//                    </div>";
                    $analog = "
                    <p class='productname newpostup' style=\"$disabled\"><button class='blue-button' title=''
                     style='background-color: $button'
                     onclick='unsetAnalogs(\"$name_conc\",\"$Product->code\",\"$M_Product->code_1c\",\"$Product->price_uah\")' type=button>
                   X</button>($M_Product->code_1c)  $M_Product->name_ru<br>
                        <small>арт.: </small><span style='color: #008BFF'>$M_Product->product_code</span><br>
                        <small>цена: </small><span class=totalPrice
                        style='color:$marked;'>$M_Product->Price&nbsp;&#8372;&nbsp;|&nbsp;</span>
                        <small>разница: </small><span style='color:$marked;font-weight:700'>$price_diff%</span>
                    </p>";
                }
            } else {

                $analog = "<input type='text' class='input_message search-concs' rel='Поиск аналогов' value='Поиск аналогов' name='searchstring'   data-conc=$name_conc data-code=$Product->code data-price=$Product->price_uah >
                            <a class='blue-button fancybox fancybox.ajax find' title=''
                            href='/popup/search_by_conc/search_conc.php?mode=1&conc=$name_conc&code=$Product->code&price=$Product->price_uah' onclick=\"this.style.backgroundColor = 'transparent'\">Найти совпадения</a>";
            }
//        $pictures  = (strlen($Product->foto)) ? "<img width=160 height=120  class=preview alt='$name' src='/$auxpage/$Product->foto' pid='/$auxpage/$Product->foto'>" : "<img width=153 height=117 alt='no foto' src='/img/nophoto.jpg'>";
            $newitems .= "
            <hr style='border-color: coral;'>
            <div class=cs_product_info style='height: auto'>
                <div class='productname newpostup'>
                        $name <small>&laquo;$category_conc&raquo;</small><br>
                        <small>арт.: </small><span style='color: #008BFF'>$product_code</span><br>
                        <small>цена: </small><span class=totalPrice>$Product->price_uah&nbsp;&#8372;</span>
                </div>
                <div class=delimiter></div>
                <div id=conc_$Product->code style='display: flex;'>
                    $analog
                </div>
            </div>
		";
        }

        $newitems .= '</div>';
        foreach ($text as $key => $oneline) {
            $text[$key] = str_replace('%new_list%', $newitems, $oneline);
        }

        return $text;

//    $newitems .= '</div>';
    }
}