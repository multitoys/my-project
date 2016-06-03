<?php
    class CompetitorsController extends ActionsController
    {
        /**
         * @var DataBase
         */
        protected $DBHandler;
        protected $manufactured = '';
        protected $competitor = '';
        protected $competitors_where = '';
        protected $conc = array();
        protected $cc = 1;
        protected $currency = '';
        protected $brand = '';
        protected $brands = array();
        protected $category = '';
        protected $categories = array();
        protected $bestsellers = '';
        protected $new = '';
        protected $new_items_postup = '';
        protected $akcia = '';
        protected $search = '';
        protected $disc_usd = 27;
        protected $disc_ua = 20;
        protected $disc_conc = 0;
        protected $table = 'Conc__analogs';
        protected $table_conc = 'Conc__competitors';
        protected $competitors_name = array();
        protected $competitors_array = array();
        protected $competitors_params = array();
    
        private function _updatePrice($id, $price)
        {
            //safeMode(true);
            db_query( "UPDATE ".$this->table." SET Price='$price' "." WHERE productID=".$id );
        }
        
        public function __construct()
        {
            $Register = &Register::getInstance();
            $this->DBHandler = &$Register->get(VAR_DBHANDLER);
    
//            if (isset($_POST['price']) && count($_POST['price'])) {
//                foreach ($_POST['price'] as $id => $new_price) {
//                    $this->_updatePrice($id, $new_price);
//                }
//                unset($_POST['price']);
//                Redirect($_SERVER['HTTP_REFERER']);
//            }
    
            parent::__construct();
            
            //контроллер выбора функций конструктора путем обхода массива $_GET
            foreach ($_GET as $get_key => $get_key) {
                
                switch ($get_key) {

                    case 'update':
                        $this->__updateAnalogs();
                        break;

                    case 'disc_usd':
                        $this->__setDisc_usd();
                        break;
                    
                    case 'disc_ua':
                        $this->__setDisc_ua();
                        break;
                    
                    case 'disc_conc':
                        $this->__setDisc_conc();
                        break;
                    
                    case 'currency':
                        $this->__setCurrency();
                        break;
                    
                    case 'bestsellers':
                        $this->__getProductsList('items_sold', 150);
                        break;
                    
                    case 'new':
                        $this->__getProductsList('code_1c', 500);
                        break;
                     
                    case 'akcia':
                        $this->__getProductsFromLists('akcia');
                        break;
                    
                    case 'new_items_postup':
                        $this->__getProductsFromLists('newitemspostup');
                        break;
                    
                    case 'manufactured':
                        if ($_GET['manufactured'] !== 'all') {
                            $this->__setManufactured();
                        }
                        break;
                    
                    case 'brand':
                        if ($_GET['brand'] !== 'all') {
                            $this->__setBrand();
                        }
                        break;
                    
                    case 'category':
                        if ($_GET['category'] !== 'all') {
                            $this->__setCategory();
                        }
                        break;
    
                    case 'competitors':
                        //                        if ($_GET['competitors'][0] !== 'all') {
                            $this->__setCompetitor();
                        //                        }
                        break;
                    
                    case 'searchstring':
                        if ($_GET['searchstring'] !== '') {
                            $this->__setSearch();
                        }
                        break;
                }
            }
//            unset($_GET);
            $this->_setCompetitorsParams();
        }
    
        protected function _setCompetitorsParams()
        {
            
            
            $query = "SELECT * FROM $this->table_conc ORDER BY CCID";
            $res = mysql_query($query) or die(mysql_error()."<br>$query");

            while ($row = mysql_fetch_object($res)) {
//
//                if (in_array($row->competitor, $_GET['competitors'])) {
                $this->competitors_params[] = array(
                    'conc' => $row->competitor,
                    'diff' => 'diff_' . $row->competitor,
                    'name' => $row->name_ru,
                    'included' => ($this->competitor)?(in_array($row->competitor, $this->conc))?1:0:1
                );
//                if (isset($_GET['competitors'])) {
//                    $this->competitors_params[]['included'] = (in_array($row->competitor, $this->conc))?1:0;
//                } else {
//                    $this->competitors_params[]['included'] = 1;
//                }
                $this->competitors_name[$row->competitor] = $row->name_ru;
                $this->competitors_array[$row->competitor] = $row->competitor;
                $this->cc++;
//                }
//                foreach ($_GET['competitors'] as $conc) {
//                    if ($conc === 'all') {
//                        $this->competitor = '';
//                        return;
//                    }
//                    $this->conc[] = $conc;
//                    unset($this->competitors_name[$conc], $this->competitors_array[$conc]);
                    
//                }
                //                if ($not_null == '') {
                //                    $not_null .= ' AND (';
                //                    $not_null .= $row->competitor.' IS NOT NULL';
                //                } else {
                //                    $not_null .= ' OR '.$row->competitor.' IS NOT NULL';
                //                }
            }
            //            $this->competitor = $not_null.')';
        }
    
        protected function __updateAnalogs()
        {
            include(DIR_FUNC.'/import_functions.php');
        
            $query = 'SELECT competitor, currency_value FROM Conc__competitors';
            $res = mysql_query($query) or die(mysql_error()."<br>$query");
        
            $competitors = array();
            $concs = array();
        
            while ($Currs = mysql_fetch_object($res)) {
                $competitors[$Currs->competitor] = $Currs->currency_value;
                $concs[] = $Currs->competitor;
            }
    
            $query = 'UPDATE '.$this->table.' SET enabled=1 WHERE enabled = 2';
            $res = mysql_query($query) or die(mysql_error().$query);
    
            $diff_conc = array();
    
            foreach ($concs as $unic_conc) {
                
                $diff_conc[] = 'diff_'.$unic_conc;
            
                $query = "SELECT code, code_1c FROM Conc_search__$unic_conc";
                $res = mysql_query($query) or die(mysql_error().$query);
            
                $usd_conc = $competitors[$unic_conc];
            
                while ($Codes = mysql_fetch_object($res)) {
                
                    $query2
                        = "SELECT
                        price_uah
                    FROM
                        Conc__$unic_conc
                    WHERE
                        code = '$Codes->code' AND enabled=1";
                    $res2 = mysql_query($query2) or die(mysql_error().$query2);
                
                    if ($analog = mysql_fetch_row($res2)) {
                    
                        $query3
                            = "UPDATE $this->table
                            SET    enabled         = 2,
                                   $unic_conc      = $analog[0],
                                   usd_$unic_conc  = $analog[0]/$usd_conc,
                                   diff_$unic_conc = ROUND((Price/$analog[0]-1)*100, 1)
                            WHERE  code_1c         = '$Codes->code_1c'
                                   AND enabled > 0";
                        $res3 = mysql_query($query3) or die(mysql_error()."<br>$query");
                    }
                }
            
                optimizeTable('Conc__'.$unic_conc);
                optimizeTable('Conc_search__'.$unic_conc);
            }
    
            //            $query = "DELETE FROM $table WHERE 1 $delete_null";
            //            $res = mysql_query($query) or die(mysql_error().$query);
        
            $diff_conc = implode(',', $diff_conc);
            $query = "UPDATE $this->table SET max_diff = GREATEST($diff_conc) WHERE enabled = 2";
            $res = mysql_query($query) or die(mysql_error().$query);
        
            if ($res) {
                optimizeTable($this->table);
            }
        }
    
        protected function __setDisc_usd()
        {
            $this->disc_usd = (int)$_GET['disc_usd'];
        }
    
        protected function __setDisc_ua()
        {
            $this->disc_ua = (int)$_GET['disc_ua'];
        }
    
        protected function __setDisc_conc()
        {
            $this->disc_conc = (int)$_GET['disc_conc'];
        }
    
        protected function __setCurrency()
        {
            $this->currency = 'usd_';
        }
    
        protected function __getProductsList($field_for_list, $limit = 150)
        {
            $query = 'SELECT productID FROM SC_products WHERE enabled = 1 ORDER BY '.$field_for_list.' DESC LIMIT '.$limit;
            $res = mysql_query($query) or die(mysql_error().$query);
        
            $ids = array();
        
            while ($row = mysql_fetch_object($res)) {
                $ids[] = $row->productID;
            }
        
            $ids = implode(',', $ids);
    
            $this->bestsellers = ' AND productID IN ('.$ids.')';
        }
    
        protected function __getProductsFromLists($list)
        {
            $query = "SELECT productID FROM SC_product_list_item
                    WHERE list_id = $list";
            $res = mysql_query($query) or die(mysql_error().$query);
        
            $ids = array();
        
            while ($row = mysql_fetch_object($res)) {
                $ids[] = $row->productID;
            }
        
            $ids = implode(',', $ids);
        
            $this->new_items_postup = ' AND productID IN ('.$ids.')';
        }
    
        protected function __setManufactured()
        {
            $made_in = '< 1';
        
            if (xEscapeSQLstring($_GET['manufactured']) === 'Ukraine') {
                $made_in = '> 0';
            }
        
            $this->manufactured = ' AND ukraine '.$made_in;
        }
    
        protected function __setBrand()
        {
            $this->brand = ' AND brand = "'.xEscapeSQLstring($_GET['brand']).'"';
        }
    
        protected function __setCategory()
        {
            $this->category = ' AND category = "'.xEscapeSQLstring($_GET['category']).'"';
        }
    
        protected function __setCompetitor()
        {
            $this->competitor = '';
            
            foreach ($_GET['competitors'] as $conc) {
                if ($conc === 'all') {
                    return;
                }
                $this->conc[] = $conc;
            }

            if (count($this->conc)) {
                $this->competitor = ' AND ' . xEscapeSQLstring(implode(' OR ', $this->conc));
            }
        }

        protected function __setSearch()
        {
            $search = xEscapeSQLstring(trim($_GET['searchstring']));
            $this->search = ' AND (product_code LIKE "%'.$search.'%" OR code_1c="'.$search.'" OR name_ru LIKE "%'.$search.'%")';
        }

        public function main()
        {
            $Register = &Register::getInstance();
            /*@var $Register Register*/
            $smarty = &$Register->get(VAR_SMARTY);
//            $smarty = &Core::getSmarty();
            /*@var $smarty Smarty*/

//            $time = microtime(true);
                
            $this->__getBrandsArray();
            $this->__getCategoriesArray();
    
            $Grid = new Grid();
            $Grid->__debug = true;
            
            $Grid->query_total_rows_num = "
                SELECT COUNT(*) FROM $this->table
                WHERE enabled = 2
                    $this->manufactured $this->brand $this->category $this->bestsellers 
                    $this->new $this->new_items_postup $this->competitor $this->search";

            $Grid->query_select_rows = "
                SELECT * FROM $this->table
                WHERE enabled = 2
                    $this->manufactured $this->brand $this->category $this->bestsellers 
                    $this->new  $this->new_items_postup $this->competitor $this->search";

            $Grid->show_rows_num_select = true;
            $Grid->default_sort_direction = 'DESC';
            $Grid->rows_num = 100;

//            $smarty->assign('debug', $this->debugging($time));
            
            //выбор и установка заголовков для таблицы отчета
            $Grid->registerHeader('№');
            $Grid->registerHeader('Код 1С', 'code_1c', false, 'ASC');
            $Grid->registerHeader('Артикул', 'product_code', false, 'ASC');
            $Grid->registerHeader('Фото');
            $Grid->registerHeader('Наименование', 'name_ru', true, 'ASC');
            $Grid->registerHeader('Категория', 'category', false, 'ASC');
            $Grid->registerHeader('Бренд', 'brand', true, 'ASC');
            $Grid->registerHeader('Закупка', 'purchase', false, 'ASC', 'right');
            $Grid->registerHeader('Наценка', 'margin', false, 'ASC', 'right');
            $Grid->registerHeader('Мультитойс', 'Price', false, 'ASC', 'right');
            $Grid->registerHeader('MAX-%', 'max_diff', false, 'ASC', 'right');

            if ($this->conc) {
    
                $diff_header = (count($this->conc) > 3)?'%':'Разница';
    
                foreach ($this->conc as $conc) {
                    $Grid->registerHeader($this->competitors_name[$conc], $conc, false, 'ASC', 'right');
                    $Grid->registerHeader($diff_header, 'diff_'.$conc, false, 'ASC', 'right');
                }
                
            } else {

                foreach ($this->competitors_name as $conc => $name) {

                    $Grid->registerHeader($name, $conc, false, 'ASC', 'right');
                    $Grid->registerHeader('%', 'diff_'.$conc, false, 'ASC', 'right');
                }
            }

            $Grid->prepare();

            $rows = &$smarty->get_template_vars('GridRows');

            for ($k = count($rows) - 1; $k >= 0; $k--) {

                $rows[$k]['num'] = $k + 1;
                $rows[$k]['img'] = '/published/publicdata/MULTITOYS/attachments/SC/search_pictures/'.$rows[$k]['code_1c'].'_s.jpg';
                $rows[$k]['purchase'] = $rows[$k][$this->currency.'purchase'];
                $rows[$k]['Price'] = $this->__priceDiscount($rows[$k][$this->currency.'Price'], $rows[$k]['ukraine']);

                $min = array();

                foreach ($this->competitors_name as $conc => $name) {

                    $price_conc = '--';
                    $diff_conc = '--';

                    if (!is_null($rows[$k][$this->currency.$conc])) {

                        $price_conc = $this->__priceConc($rows[$k][$this->currency.$conc]);
                        $diff_conc = $this->__priceDiff($rows[$k]['Price'], $price_conc);
                        $min[$conc] = (float)$price_conc;
                    }

                    $rows[$k][$conc] = $price_conc;
                    $rows[$k]['diff_'.$conc] = $diff_conc;
                }

                asort($min, SORT_NUMERIC);
                $min_diff = array_shift($min);
    
                $rows[$k]['margin'] = $this->__priceDiff($rows[$k]['Price'], $rows[$k]['purchase'], 0);
                $rows[$k]['max_diff'] = $this->__priceDiff($rows[$k]['Price'], $min_diff, 0);
            }

            $count_rows = array('100' => 100, '500' => 500, '1000' => 1000);

            if (isset($_GET['export'])) {

                $headers = array(
                    'num'          => '№',
                    'code_1c'      => 'Код 1С',
                    'product_code' => 'Артикул',
                    'name_ru'      => 'Наименование',
                    'category'     => 'Категория',
                    'brand'        => 'Бренд',
                    'purchase'     => 'Закупка',
                    'margin'       => 'Наценка',
                    'Price'        => 'Мультитойс',
                    'max_diff'     => 'MAX-%',
                );

                foreach ($this->competitors_params as $params) {

                    $headers[$params['conc']] = $params['name'];
                    $headers['diff_'.$params['conc']] = '%';
                }
                
                $this->__getExportXLS($headers, $rows);
            }

            if ($this->search) {
                $smarty->assign('search_word', 'По запросу "'.xEscapeSQLstring(trim($_GET['searchstring'])).'"');
            }
            $smarty->assign('Competitors', $this->competitors_params);
            $smarty->assign('cc', $this->cc);
            $smarty->assign('Brands', $this->brands);
            $smarty->assign('Categories', $this->categories);
            $smarty->assign('disc_usd', $this->disc_usd);
            $smarty->assign('disc_ua', $this->disc_ua);
            $smarty->assign('disc_conc', $this->disc_conc);
            $smarty->assign('GridRows', $rows);
            $smarty->assign('rows', $count_rows);
            $smarty->assign('TotalFound', str_replace('{N}', $Grid->total_rows_num, 'Найдено товаров: {N}'));

            $smarty->display(DIR_TPLS.'/backend/competitors_report.html');

        }

        protected function __getBrandsArray()
        {
            $query = "SELECT DISTINCT brand FROM $this->table WHERE enabled = 2  $this->competitor";
            $res = mysql_query($query) or die(mysql_error().$query);

            while ($Brands = mysql_fetch_object($res)) {
                if ($Brands->brand !== '') {
                    $this->brands[] = $Brands->brand;
                }
            }
            sort($this->brands);
        }
    
        protected function __getCategoriesArray()
        {
            $query = "
                      SELECT DISTINCT 
                          category 
                      FROM 
                          $this->table 
                      WHERE enabled = 2 
                          $this->competitor 
                          $this->manufactured
                     ";
            $res = mysql_query($query) or die(mysql_error().$query);

            while ($Categories = mysql_fetch_object($res)) {
                $this->categories[] = $Categories->category;
            }
            sort($this->categories);
        }

        protected function __priceDiscount($Price, $ua)
        {
            $real_skidka = ($ua) ? $this->disc_ua : $this->disc_usd;
            $outPrice = round($Price - ($Price * $real_skidka / 100), 2);

            return number_format($outPrice, 2, $dec_point = '.', $thousands_sep = '');
        }

        protected function __priceConc($Price)
        {
            $outPrice = round($Price - ($Price * $this->disc_conc / 100), 2);

            return number_format($outPrice, 2, $dec_point = '.', $thousands_sep = '');
        }

        protected function __priceDiff($Price, $price_conc, $decimals = 0)
        {
            $outDiff = round(($Price / $price_conc - 1) * 100, 1);

            return number_format($outDiff, $decimals, $dec_point = '.', $thousands_sep = '');
        }

        private function __getExportXLS($headers, $rows)
        {
            if ($_GET['competitors']) {
                foreach ($this->conc as $conc) {
                    if (in_array($conc, $this->competitors_array)) {
                
                        unset($this->competitors_array[$conc]);
                    }
                }
                foreach ($this->competitors_array as $competitor) {
            
                    unset(
                        $headers[$competitor],
                        $headers['diff_'.$competitor]
                    );
                }
            }
    
            $replace = array(' ', 'AND', 'category');
            $date = date('d-m-Y', time());
            new MakeXLS($headers, $rows, str_replace($replace, '-', implode('-', $this->conc).'-'.$_GET['manufactured'].'-'.$this->brand.'-'.$this->category).'-'.$date);
        }
    }

    ActionsController::exec('CompetitorsController');