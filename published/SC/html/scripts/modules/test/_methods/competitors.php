<?php
    class CompetitorsController extends ActionsController
    {
        /**
         * @var DataBase
         */
        protected $DBHandler;
        protected $manufactured = '';
        protected $competitor = '';
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
        protected $disc_usd = 27;
        protected $disc_ua = 20;
        protected $disc_conc = 0;
        protected $table = 'Conc__analogs';

        public function __construct()
        {
            $Register = &Register::getInstance();
            $this->DBHandler = &$Register->get(VAR_DBHANDLER);

            parent::__construct();

            //контроллер выбора функций конструктора путем обхода массива $_GET
            foreach (array_keys($_GET) as $get_key) {
                
                switch ($get_key) {
                    
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
                    
                    case 'new_items_postup':
                        $this->__getNewItemsPostup();
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
                    
                    case 'competitor':
                        if ($_GET['competitor'] !== 'all') {
                            $this->__setCompetitor();
                        }
                        break;
                    
                    case 'searchstring':
                        if ($_GET['searchstring'] !== '') {
                            $this->__setSearch();
                        }
                        break;
                }
            }
        }

        public function main()
        {
            $Register = &Register::getInstance();
            /*@var $Register Register*/
            $smarty = &$Register->get(VAR_SMARTY);
            /*@var $smarty Smarty*/

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

            //выбор и установка заголовков для таблицы отчета
            $grid->registerHeader('№');
            $grid->registerHeader('Код 1С', 'code_1c', false, 'ASC');
            $grid->registerHeader('Артикул', 'product_code', false, 'ASC');
            $grid->registerHeader('Фото');
            $grid->registerHeader('Наименование', 'name_ru', true, 'ASC');
            $grid->registerHeader('Бренд', 'brand', true, 'ASC');
            $grid->registerHeader('Закупка', 'purchase', false, 'ASC', 'right');
            $grid->registerHeader('Наценка', 'margin', false, 'ASC', 'right');
            $grid->registerHeader('Мультитойс', 'Price', false, 'ASC', 'right');
            $grid->registerHeader('MAX-%', 'max_diff', false, 'ASC', 'right');

            switch ($this->conc) {

                case 'divoland':
                    $grid->registerHeader('Диволенд', 'divoland', false, 'ASC', 'right');
                    $grid->registerHeader('%', 'diff_divoland', false, 'ASC', 'right');
                    break;
                case 'dreamtoys':
                    $grid->registerHeader('Веселка', 'dreamtoys', false, 'ASC', 'right');
                    $grid->registerHeader('%', 'diff_dreamtoys', false, 'ASC', 'right');
                    break;
                case 'mixtoys':
                    $grid->registerHeader('Микстойс', 'mixtoys', false, 'ASC', 'right');
                    $grid->registerHeader('%', 'diff_mixtoys', false, 'ASC', 'right');
                    break;
                case 'grandtoys':
                    $grid->registerHeader('Г.-Тойс', 'grandtoys', false, 'ASC', 'right');
                    $grid->registerHeader('%', 'diff_grandtoys', false, 'ASC', 'right');
                    break;
                case 'kindermarket':
                    $grid->registerHeader('К.-Маркет', 'kindermarket', false, 'ASC', 'right');
                    $grid->registerHeader('%', 'diff_kindermarket', false, 'ASC', 'right');
                    break;
                default:
                    $grid->registerHeader('Диволенд', 'divoland', false, 'ASC', 'right');
                    $grid->registerHeader('%', 'diff_divoland', false, 'ASC', 'right');
                    $grid->registerHeader('Веселка', 'dreamtoys', false, 'ASC', 'right');
                    $grid->registerHeader('%', 'diff_dreamtoys', false, 'ASC', 'right');
                    $grid->registerHeader('Микстойс', 'mixtoys', false, 'ASC', 'right');
                    $grid->registerHeader('%', 'diff_grandtoys', false, 'ASC', 'right');
                    $grid->registerHeader('Г.Тойс', 'grandtoys', false, 'ASC', 'right');
                    $grid->registerHeader('%', 'diff_grandtoys', false, 'ASC', 'right');
                    $grid->registerHeader('К.-Маркет', 'kindermarket', false, 'ASC', 'right');
                    $grid->registerHeader('%', 'diff_kindermarket', false, 'ASC', 'right');
            }
            $grid->prepare();

            $rows = $smarty->get_template_vars('GridRows');

            for ($k = count($rows) - 1; $k >= 0; $k--) {

                $rows[$k]['num'] = $k + 1;
                $rows[$k]['img'] = '/published/publicdata/MULTITOYS/attachments/SC/search_pictures/'.$rows[$k]['code_1c'].'_s.jpg';
                //$rows[$k]['img_big'] = '/published/publicdata/MULTITOYS/attachments/SC/products_pictures/'.$rows[$k]['code_1c'].'.jpg';

                $rows[$k]['purchase'] = $rows[$k][$this->currency.'purchase'];
                $rows[$k]['Price'] = $this->__priceDiscount($rows[$k][$this->currency.'Price'], $rows[$k]['ukraine']);

                $rows[$k]['divoland'] = (is_null($rows[$k][$this->currency.'divoland']))?'--':$this->__priceConc($rows[$k][$this->currency.'divoland']);
                $rows[$k]['diff_divoland'] = ($rows[$k]['divoland'] === '--')?'--':$this->__priceDiff($rows[$k]['Price'], $rows[$k]['divoland']);

                $rows[$k]['dreamtoys'] = (is_null($rows[$k][$this->currency.'dreamtoys']))?'--':$this->__priceConc($rows[$k][$this->currency.'dreamtoys']);
                $rows[$k]['diff_dreamtoys'] = ($rows[$k]['dreamtoys'] === '--')?'--':$this->__priceDiff($rows[$k]['Price'], $rows[$k]['dreamtoys']);

                $rows[$k]['mixtoys'] = (is_null($rows[$k][$this->currency.'mixtoys']))?'--':$this->__priceConc($rows[$k][$this->currency.'mixtoys']);
                $rows[$k]['diff_mixtoys'] = ($rows[$k]['mixtoys'] === '--')?'--':$this->__priceDiff($rows[$k]['Price'], $rows[$k]['mixtoys']);

                $rows[$k]['grandtoys'] = (is_null($rows[$k][$this->currency.'grandtoys']))?'--':$this->__priceConc($rows[$k][$this->currency.'grandtoys']);
                $rows[$k]['diff_grandtoys'] = ($rows[$k]['grandtoys'] === '--')?'--':$this->__priceDiff($rows[$k]['Price'], $rows[$k]['grandtoys']);

                $rows[$k]['kindermarket'] = (is_null($rows[$k][$this->currency.'kindermarket']))?'--':$this->__priceConc($rows[$k][$this->currency.'kindermarket']);
                $rows[$k]['diff_kindermarket'] = ($rows[$k]['kindermarket'] === '--')?'--':$this->__priceDiff($rows[$k]['Price'], $rows[$k]['kindermarket']);


                if ($this->conc) {

                    $diff = $this->__priceDiff($rows[$k]['Price'], $rows[$k][$this->conc], 1);
                    
                } else {

                    $competitors = array('kindermarket', 'divoland', 'dreamtoys', 'mixtoys', 'grandtoys');
                    $min = array();

                    foreach ($competitors as $competitor) {
                        $min[$competitor] = ($rows[$k][$competitor] == '--')?100000:(float)$rows[$k][$competitor];
                    }

                    asort($min, SORT_NUMERIC);
                    $min_diff = array_shift($min);

                    $diff = $this->__priceDiff($rows[$k]['Price'], $min_diff, 1);
                }

                $rows[$k]['margin'] = $this->__priceDiff($rows[$k]['Price'], $rows[$k]['purchase'], 1);
                $rows[$k]['max_diff'] = $diff;

            }

            $count_rows = array('100' => 100, '500' => 500, '1000' => 1000);

            if (isset($_GET['export'])) {

                $headers = array(
                    'num'               => '№',
                    'code_1c'           => 'Код 1С',
                    'product_code'      => 'Артикул',
                    'name_ru'           => 'Наименование',
                    'brand'             => 'Бренд',
                    'purchase'          => 'Закупка',
                    'margin'            => 'Наценка',
                    'Price'             => 'Мультитойс',
                    'max_diff'          => 'MAX-%',
                    'divoland'          => 'Диволенд',
                    'diff_divoland'     => '%',
                    'dreamtoys'         => 'Веселка',
                    'diff_dreamtoys'    => '%',
                    'mixtoys'           => 'Микстойс',
                    'diff_mixtoys'      => '%',
                    'kindermarket'      => 'К.-Маркет',
                    'diff_kindermarket' => '%',
                    'grandtoys'         => 'Г.-Тойс',
                    'diff_grandtoys'    => '%'
                );

                $this->__getExportXLS($headers, $rows);
            }

            $all_competitors = array(
                array('conc' => 'divoland', 'diff' => 'diff_divoland', 'name' => 'Диволенд'),
                array('conc' => 'dreamtoys', 'diff' => 'diff_dreamtoys', 'name' => 'Веселка'),
                array('conc' => 'mixtoys', 'diff' => 'diff_mixtoys', 'name' => 'Микстойс'),
                array('conc' => 'grandtoys', 'diff' => 'diff_grandtoys', 'name' => 'ГрандТойс'),
                array('conc' => 'kindermarket', 'diff' => 'diff_kindermarket', 'name' => 'Киндер-Маркет')
            );
            
            $smarty->assign('Competitors', $all_competitors);
            $smarty->assign('Brands', $this->brands);
            $smarty->assign('Categories', $this->categories);
            $smarty->assign('disc_usd', $this->disc_usd);
            $smarty->assign('disc_ua', $this->disc_ua);
            $smarty->assign('disc_conc', $this->disc_conc);
            $smarty->assign('GridRows', $rows);
            $smarty->assign('rows', $count_rows);
            $smarty->assign('TotalFound', str_replace('{N}', $grid->total_rows_num, 'Найдено товаров: {N}'));

            $smarty->display(DIR_TPLS.'/backend/competitors_report.html');

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
            $this->conc = xEscapeSQLstring($_GET['competitor']);
            $this->competitor = ' AND '.$this->conc;
        }

        protected function __setSearch()
        {
            $this->search = ' AND (product_code LIKE "%'.xEscapeSQLstring($_GET['searchstring']).'%" OR code_1c='.xEscapeSQLstring($_GET['searchstring']).' OR name_ru LIKE "%'.xEscapeSQLstring($_GET['searchstring']).'%")';
        }

        protected function __getCompetitorsList()
        {
            $query = 'SELECT * FROM Conc__competitors';
            $res = mysql_query($query) or die(mysql_error()."<br>$query");

            $ids = array();

            while ($row = mysql_fetch_object($res)) {
                $ids[] = $row->code_1c;
            }
            $ids = implode(',', $ids);

            $this->bestsellers = ' AND code_1c IN ('.$ids.')';
        }

        protected function __getProductsList($field_for_list, $limit = 150)
        {
            $query = 'SELECT code_1c FROM SC_products WHERE enabled = 1 ORDER BY '.$field_for_list.' DESC LIMIT '.$limit;
            $res = mysql_query($query) or die(mysql_error().$query);

            $ids = array();

            while ($row = mysql_fetch_object($res)) {
                $ids[] = $row->code_1c;
            }

            $ids = implode(',', $ids);

            $this->bestsellers = ' AND code_1c IN ('.$ids.')';
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
                      WHERE 1 
                          $this->manufactured 
                          $this->brand 
                          $this->category 
                          $this->bestsellers 
                          $this->new 
                          $this->new_items_postup 
                          $this->competitor 
                          $this->search
                     ";
            $res = mysql_query($query) or die(mysql_error().$query);

            while ($Categories = mysql_fetch_object($res)) {
                $this->categories[] = $Categories->category;
            }
            sort($this->categories);
        }

        protected function __priceDiscount($Price, $ua)
        {
            $real_skidka = ($ua)?$this->disc_ua:$this->disc_usd;
            $outPrice = round($Price - ($Price * $real_skidka / 100), 2);

            return number_format($outPrice, 2);
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
        
        protected function __getExportXLS($headers, $rows)
        {
            if ($_GET['competitor'] !== 'all') {

                $competitors = array('kindermarket' => 'kindermarket', 'divoland' => 'divoland', 'dreamtoys' => 'dreamtoys', 'mixtoys' => 'mixtoys', 'grandtoys' => 'grandtoys', 'diff_kindermarket' => 'diff_kindermarket', 'diff_divoland' => 'diff_divoland', 'diff_dreamtoys' => 'diff_dreamtoys', 'diff_mixtoys' => 'diff_mixtoys', 'diff_grandtoys' => 'diff_grandtoys');

                $current_competitor = $_GET['competitor'];

                if (in_array($current_competitor, $competitors)) {

                    unset(
                        $competitors[$current_competitor],
                        $competitors['diff_'.$current_competitor]
                    );

                    foreach ($competitors as $competitor) {

                        unset(
                            $headers[$competitor],
                            $headers['diff_'.$competitor]
                        );
                    }
                }
            }
            
            $replace = array(' ', 'AND', 'category');
            $date = date('d-m-Y', time());
            new MakeXLS($headers, $rows, str_replace($replace, '-', $this->competitor.'-'.xEscapeSQLstring($_GET['manufactured']).'-'.$this->brand.'-'.$this->category).'-'.$date);
        }
    }

    ActionsController::exec('CompetitorsController');