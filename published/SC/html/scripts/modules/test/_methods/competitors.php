<?php

    class CompetitorsController extends ActionsController
    {

        /**
         * @var DataBase
         */

        protected $DBHandler;
        protected $manufactured = '';
        protected $competitor = ' AND (kindermarket OR divoland OR dreamtoys OR mixtoys OR grandtoys OR grandtoys2 OR grandtoys3)';
        protected $conc = '';
        protected $currency = '';
        protected $brand = '';
        protected $brands = array();
        protected $category = '';
        protected $categories = array();
        protected $bestsellers = '';
        protected $new = '';
        protected $new_items_postup = '';
        protected $search = '';
        protected $disc_usd = 0;
        protected $disc_ua = 0;
        protected $table = 'Conc__analogs';
//        protected $enabled = '';

//        protected function __setEnabled()
//        {
//            $this->enabled = ' AND enabled = 1';
//        }

        protected function __setDisc_usd()
        {
            $this->disc_usd = (int)$_GET['disc_usd'];
        }
        
        protected function __setDisc_ua()
        {
            $this->disc_ua = (int)$_GET['disc_ua'];
        }
        
        protected function __setCurrency()
        {
            $this->currency = 'usd_';
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
            $this->brand = ' AND brand LIKE "%'.xEscapeSQLstring($_GET['brand']).'%"';
        }

        protected function __setCategory()
        {
            $this->category = ' AND category LIKE "%'.xEscapeSQLstring($_GET['category']).'%"';
        }

        protected function __setCompetitor()
        {
            $this->conc = xEscapeSQLstring($_GET['competitor']);
            $this->competitor = ' AND '.$this->conc;
        }

        protected function __setSearch()
        {
            $this->search = ' AND (product_code LIKE "%'.xEscapeSQLstring($_GET['searchstring']).'%" OR name_ru LIKE "%'.xEscapeSQLstring($_GET['searchstring']).'%")';
        }

        protected function __getBestsellers()
        {
            $query = 'SELECT code_1c FROM SC_products ORDER BY items_sold DESC LIMIT 150';
            $res = mysql_query($query) or die(mysql_error().$query);

            $ids = array();

            while ($row = mysql_fetch_object($res)) {
                $ids[] = $row->code_1c;
            }
            $ids = implode(',', $ids);

            $this->bestsellers = ' AND code_1c IN ('.$ids.')';
        }

        protected function __getNew()
        {
            $query = 'SELECT code_1c FROM SC_products WHERE enabled = 1 ORDER BY code_1c DESC LIMIT 500';
            $res = mysql_query($query) or die(mysql_error().$query);

            $ids = array();

            while ($row = mysql_fetch_object($res)) {
                $ids[] = $row->code_1c;
            }
            $ids = implode(',', $ids);

            $this->new = ' AND code_1c IN ('.$ids.')';
        }

        protected function __getNewItemsPostup()
        {
            $query = "SELECT productID FROM SC_product_list_item
                    WHERE list_id = 'newitemspostup'";
            $res = mysql_query($query) or die(mysql_error().$query);

            $ids = array();

            while ($row = mysql_fetch_object($res)) {
                $ids[] = $row->productID;
            }
            $ids = implode(',', $ids);

            $this->new_items_postup = ' AND productID IN ('.$ids.')';
        }

        protected function __getBrandsArray()
        {
            $query = "SELECT DISTINCT brand FROM $this->table WHERE 1  $this->competitor";
            $res = mysql_query($query) or die(mysql_error().$query);

            while($Brands = mysql_fetch_object($res)) {
                if ($Brands->brand !== '') {
                    $this->brands[] = $Brands->brand;
                }
            }
            sort($this->brands);
        }

        protected function __getCategoriesArray()
        {
            $query = "SELECT DISTINCT category FROM $this->table WHERE 1  $this->manufactured $this->brand $this->category $this->bestsellers $this->new $this->new_items_postup $this->competitor $this->search";
            $res = mysql_query($query) or die(mysql_error().$query);

            while ($Categories = mysql_fetch_object($res)) {
                $this->categories[] = $Categories->category;
            }
            sort($this->categories);
        }

        protected function __getCategory()
        {
            $query = "SELECT DISTINCT category FROM $this->table";
            $result = mysql_query($query) or die(mysql_error().$query);
            $row = mysql_fetch_row($result);
            return $row[0];
        }

        public function __construct()
        {

            $Register = &Register::getInstance();
            $this->DBHandler = &$Register->get(VAR_DBHANDLER);

            parent::ActionsController();
            
            if (isset($_GET['disc_usd'])) {
                $this->__setDisc_usd();
            }            
            if (isset($_GET['disc_ua'])) {
                $this->__setDisc_ua();
            }            
            if (isset($_GET['currency'])) {
                $this->__setCurrency();
            }
            if (isset($_GET['bestsellers'])) {
                $this->__getBestsellers();
            }
            if (isset($_GET['new'])) {
                $this->__getNew();
            }
            if (isset($_GET['new_items_postup'])) {
                $this->__getNewItemsPostup();
            }
            if (isset($_GET['manufactured']) && $_GET['manufactured'] !== 'all') {
                $this->__setManufactured();
            }
            if (isset($_GET['brand']) && $_GET['brand'] !== 'all') {
                $this->__setBrand();
            }
            if (isset($_GET['category']) && $_GET['category'] !== 'all') {
                $this->__setCategory();
            }
            if (isset($_GET['competitor']) && $_GET['competitor'] !== 'all') {
                $this->__setCompetitor();
            }
            if (isset($_GET['searchstring']) && $_GET['searchstring'] !== null) {
                $this->__setSearch();
            }
        }
        
        protected function __priceDiscount($Price, $ua, $disc_usd = 0, $disc_ua = 0)
        {
//            if ($conc !== 'grandtoys') {
//                
//                return $Price;
//            }
            
            $real_skidka = ($ua)?$disc_ua:$disc_usd;
            $outPrice = round($Price - ($Price * $real_skidka / 100), 2);

            return $outPrice;
        }


        public function main()
        {

            $Register = &Register::getInstance();
            /*@var $Register Register*/
            $smarty = &$Register->get(VAR_SMARTY);
            /*@var $smarty Smarty*/

//            $grid = ClassManager::getInstance('grid');
            $grid = new Grid();

            $this->__getBrandsArray();
            $this->__getCategoriesArray();
            
            $grid->query_total_rows_num = "
                SELECT COUNT(*) FROM $this->table
                WHERE 1
                $this->manufactured $this->brand $this->category $this->bestsellers $this->new $this->new_items_postup $this->competitor $this->search";

            $grid->query_select_rows = "
                SELECT * FROM $this->table
                WHERE 1
                $this->manufactured $this->brand $this->category $this->bestsellers $this->new  $this->new_items_postup $this->competitor $this->search";

            $grid->show_rows_num_select = true;
            $grid->default_sort_direction = 'DESC';
            $grid->rows_num = 100;

            $grid->registerHeader('№');
            $grid->registerHeader('Код 1С', 'code_1c', false, 'ASC');
            $grid->registerHeader('Артикул', 'product_code', false, 'ASC');
            $grid->registerHeader('Фото');
            $grid->registerHeader('Наименование', 'name_ru', true, 'ASC');
//            $grid->registerHeader('Категория', 'category', false, 'ASC');
            $grid->registerHeader('Закупка', 'purchase', false, 'ASC', 'right');
            $grid->registerHeader('Наценка', 'margin', false, 'ASC', 'right');
            $grid->registerHeader('Мультитойс', 'Price', false, 'ASC', 'right');
            $grid->registerHeader('MAX-%', 'max_diff', false, 'ASC', 'right');

            switch ($this->conc) {

                case 'divoland':
                    $grid->registerHeader('Диволенд', 'divoland', false, 'ASC', 'right');
                    $grid->registerHeader('раз-ца', 'diff_divoland', false, 'ASC', 'right');
                    break;
                case 'dreamtoys':
                    $grid->registerHeader('Веселка', 'dreamtoys', false, 'ASC', 'right');
                    $grid->registerHeader('раз-ца', 'diff_dreamtoys', false, 'ASC', 'right');
                    break;
                case 'mixtoys':
                    $grid->registerHeader('Микстойс', 'mixtoys', false, 'ASC', 'right');
                    $grid->registerHeader('раз-ца', 'diff_mixtoys', false, 'ASC', 'right');
                    break;
                case 'grandtoys':
                    $grid->registerHeader('Г.-Тойс', 'grandtoys', false, 'ASC', 'right');
                    $grid->registerHeader('раз-ца', 'diff_grandtoys', false, 'ASC', 'right');
                    $grid->registerHeader('Г.-Тойс-2', 'grandtoys2', false, 'ASC', 'right');
                    $grid->registerHeader('раз-ца', 'diff_grandtoys2', false, 'ASC', 'right');
                    $grid->registerHeader('Г.-Тойс-3', 'grandtoys3', false, 'ASC', 'right');
                    $grid->registerHeader('раз-ца', 'diff_grandtoys3', false, 'ASC', 'right');
                    break;
                case 'kindermarket':
                    $grid->registerHeader('К.-Маркет', 'kindermarket', false, 'ASC', 'right');
                    $grid->registerHeader('раз-ца', 'diff_kindermarket', false, 'ASC', 'right');
                    break;
                default:
                    $grid->registerHeader('Диволенд', 'divoland', false, 'ASC', 'right');
                    $grid->registerHeader('раз-ца', 'diff_divoland', false, 'ASC', 'right');
                    $grid->registerHeader('Веселка', 'dreamtoys', false, 'ASC', 'right');
                    $grid->registerHeader('раз-ца', 'diff_dreamtoys', false, 'ASC', 'right');
                    $grid->registerHeader('Микстойс', 'mixtoys', false, 'ASC', 'right');
                    $grid->registerHeader('раз-ца', 'diff_grandtoys', false, 'ASC', 'right');
                    $grid->registerHeader('Г.Тойс', 'grandtoys', false, 'ASC', 'right');
                    $grid->registerHeader('раз-ца', 'diff_grandtoys', false, 'ASC', 'right');
                    $grid->registerHeader('Г.-Тойс-2', 'grandtoys2', false, 'ASC', 'right');
                    $grid->registerHeader('раз-ца', 'diff_grandtoys2', false, 'ASC', 'right');
                    $grid->registerHeader('Г.-Тойс-3', 'grandtoys3', false, 'ASC', 'right');
                    $grid->registerHeader('раз-ца', 'diff_grandtoys3', false, 'ASC', 'right');
                    $grid->registerHeader('К.-Маркет', 'kindermarket', false, 'ASC', 'right');
                    $grid->registerHeader('раз-ца', 'diff_kindermarket', false, 'ASC', 'right');
            }
            $grid->prepare();

            $rows = $smarty->get_template_vars('GridRows');

            for ($k = count($rows) - 1; $k >= 0; $k--) {

                $rows[$k]['num'] = $k + 1;
                $rows[$k]['img'] = '/published/publicdata/MULTITOYS/attachments/SC/search_pictures/'.$rows[$k]['code_1c'].'_s.jpg';
                $rows[$k]['img_big'] = '/published/publicdata/MULTITOYS/attachments/SC/products_pictures/'.$rows[$k]['code_1c'].'.jpg';
                $rows[$k]['purchase'] = $rows[$k][$this->currency.'purchase'];
                $rows[$k]['Price'] = $this->__priceDiscount($rows[$k][$this->currency.'Price'], $rows[$k]['ukraine'], $this->disc_usd, $this->disc_ua);
                
//                if ($this->conc !== 'grandtoys') {
//                    $rows[$k]['margin'] .= '%';
//                    $rows[$k]['max_diff'] = ($rows[$k]['max_diff'] > 0)?$rows[$k]['max_diff'].'%':'';
//                }
                

                $rows[$k]['divoland'] = (is_null($rows[$k][$this->currency.'divoland']))?'-----':$rows[$k][$this->currency.'divoland'];
                $rows[$k]['diff_divoland'] = ($rows[$k]['divoland'] === '-----')?'-----':$rows[$k]['diff_divoland'].'%';
                $rows[$k]['dreamtoys'] = (is_null($rows[$k][$this->currency.'dreamtoys']))?'-----':$rows[$k][$this->currency.'dreamtoys'];
                $rows[$k]['diff_dreamtoys'] = ($rows[$k]['dreamtoys'] === '-----')?'-----':$rows[$k]['diff_dreamtoys'].'%';
                $rows[$k]['mixtoys'] = (is_null($rows[$k][$this->currency.'mixtoys']))?'-----':$rows[$k][$this->currency.'mixtoys'];
                $rows[$k]['diff_mixtoys'] = ($rows[$k]['mixtoys'] === '-----')?'-----':$rows[$k]['diff_mixtoys'].'%';
                $rows[$k]['grandtoys'] = (is_null($rows[$k][$this->currency.'grandtoys']))?'-----':$rows[$k][$this->currency.'grandtoys'];
                $rows[$k]['diff_grandtoys'] = ($rows[$k]['grandtoys'] === '-----')?'-----':$rows[$k]['diff_grandtoys'].'%';
                $rows[$k]['grandtoys2'] = (is_null($rows[$k][$this->currency.'grandtoys2']))?'-----':$rows[$k][$this->currency.'grandtoys2'];
                $rows[$k]['diff_grandtoys2'] = ($rows[$k]['grandtoys2'] === '-----')?'-----':$rows[$k]['diff_grandtoys2'].'%';
                $rows[$k]['grandtoys3'] = (is_null($rows[$k][$this->currency.'grandtoys3']))?'-----':$rows[$k][$this->currency.'grandtoys3'];
                $rows[$k]['diff_grandtoys3'] = ($rows[$k]['grandtoys3'] === '-----')?'-----':$rows[$k]['diff_grandtoys3'].'%';
                $rows[$k]['kindermarket'] = (is_null($rows[$k][$this->currency.'kindermarket']))?'-----':$rows[$k][$this->currency.'kindermarket'];
                $rows[$k]['diff_kindermarket'] = ($rows[$k]['kindermarket'] === '-----')?'-----':$rows[$k]['diff_kindermarket'].'%';

                $competitors = array(kindermarket, divoland, dreamtoys, mixtoys, grandtoys, grandtoys2, grandtoys3);
                $min = array();
                foreach ($competitors as $competitor) {
                    $min[$competitor] = ($rows[$k][$competitor] == '-----')?100000:doubleval($rows[$k][$competitor]);
                }
//                if ($this->conc === 'grandtoys') {
                    $rows[$k]['margin'] = (round(($rows[$k]['Price'] / $rows[$k]['purchase'] - 1)*100, 1)).'%';
                    $diff = (round(($rows[$k]['Price'] / min($min['grandtoys'], $min['grandtoys2'], $min['grandtoys3'], $min['divoland'], $min['dreamtoys'], $min['mixtoys'], $min['kindermarket'])-1)*100, 1));
                    $rows[$k]['max_diff'] = $diff.'%';
                    //$rows[$k]['max_diff'] = (round((1 - min($rows[$k]['grandtoys'], $rows[$k]['grandtoys2'], $rows[$k]['grandtoys3']) / $rows[$k]['Price'])*100, 1)).'%';
//                }
                
            }
            $count_rows = array('100' => 100, '500' => 500, '1000' => 1000);

            $smarty->assign('Brands', $this->brands);
            $smarty->assign('Categories', $this->categories);
            $smarty->assign('disc_usd', $this->disc_usd);
            $smarty->assign('disc_ua', $this->disc_ua);
            $smarty->assign('GridRows', $rows);
            $smarty->assign('rows', $count_rows);
            $smarty->assign('TotalFound', str_replace('{N}', $grid->total_rows_num, 'Найдено товаров: {N}'));

            $smarty->display(DIR_TPLS.'/backend/competitors_report.html');
        }
    }

    ActionsController::exec('CompetitorsController');