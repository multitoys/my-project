<?php
    include(DIR_FUNC.'/competitors_report_function.php');

    class CompetitorsController extends ActionsController
    {

        /**
         * @var DataBase
         */
        var $DBHandler;
        var $enabled = '';
        var $ukraine = '';
        var $competitor = ' AND (Alliance OR Divoland OR Dreamtoys OR Mixtoys)';
        var $conc = '';
        var $currency = '';
        var $brand = '';
        var $brands = array();
        var $category = '';
        var $categories = array();
        var $bestsellers = '';
        var $new = '';
        var $table = 'Conc__analogs';

        function __setEnabled()
        {
            $this->enabled = ' AND enabled = 1';
        }

        function __setUkraine()
        {
            $this->ukraine = ' AND ukraine = 1';
        }

        function __setCurrency()
        {
            $this->currency = 'usd_';
        }

        function __setBrand()
        {
            $this->brand = ' AND brand LIKE "%'.xEscapeSQLstring($_GET['brand']).'%"';
        }

        function __setCategory()
        {
            $category_name = xEscapeSQLstring($_GET['category']);
            $categoryID = $this->__getCategory($category_name);
            $this->category = ' AND categoryID = '.$categoryID.'';
        }

        function __setCompetitor()
        {
            $this->conc = xEscapeSQLstring($_GET['competitor']);
            $this->competitor = ' AND '.$this->conc;
        }

        function __getBestsellers()
        {
            $query = 'SELECT code_1c FROM SC_products WHERE items_sold > 0';
            $res = mysql_query($query) or die(mysql_error().$query);

            $ids = array();

            while ($row = mysql_fetch_object($res)) {
                $ids[] = $row->code_1c;
            }
            $ids = implode(',', $ids);

            $this->bestsellers = ' AND code_1c IN ('.$ids.')';
        }

        function __getNew()
        {
            $query = "SELECT code_1c FROM SC_products WHERE enabled = 1 ORDER BY code_1c DESC LIMIT 500";
            $res = mysql_query($query) or die(mysql_error().$query);

            $ids = array();

            while ($row = mysql_fetch_object($res)) {
                $ids[] = $row->code_1c;
            }
            $ids = implode(',', $ids);

            $this->new = ' AND code_1c IN ('.$ids.')';
        }

        function __getBrandsArray()
        {
            $query = "SELECT DISTINCT brand FROM $this->table";
            $res = mysql_query($query) or die(mysql_error().$query);

            while($Brands = mysql_fetch_object($res)) {
                if ($Brands->brand !== '') {
                    $this->brands[] = $Brands->brand;
                }
            }
            sort($this->brands);
        }
        
        function __getCategoriesArray()
        {
            $query = 'SELECT categoryID, name_ru FROM SC_categories';
            $res = mysql_query($query) or die(mysql_error().$query);

            while ($Categories = mysql_fetch_object($res)) {
                $this->categories[] = $Categories->name_ru;
            }
            sort($this->categories);
        }

        function __getCategory($what)
        {
            $query = "SELECT categoryID FROM SC_categories WHERE name_ru LIKE '$what' LIMIT 1";
            $result = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);
            $row = mysql_fetch_row($result);
            return $row[0];
        }
        
        function CompetitorsController()
        {

            $Register = &Register::getInstance();
            $this->DBHandler = &$Register->get(VAR_DBHANDLER);

            parent::ActionsController();

            $this->__getBrandsArray();
            $this->__getCategoriesArray();
        }

        function main()
        {

            $Register = &Register::getInstance();
            /*@var $Register Register*/
            $smarty = &$Register->get(VAR_SMARTY);
            /*@var $smarty Smarty*/

            $grid = ClassManager::getInstance('grid');

            if (isset($_GET['enabled'])) {
                $this->__setEnabled();
            }
            if (isset($_GET['ukraine'])) {
                $this->__setUkraine();
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
            if (isset($_GET['brand']) && $_GET['brand'] !== 'all') {
                $this->__setBrand();
            }
            if (isset($_GET['category']) && $_GET['category'] !== 'all') {
                $this->__setCategory();
            }
            if (isset($_GET['competitor']) && $_GET['competitor'] !== 'all') {
                $this->__setCompetitor();
            }

            $grid->query_total_rows_num = "
                SELECT COUNT(*) FROM $this->table
                WHERE 1
                $this->enabled $this->ukraine $this->brand $this->category $this->bestsellers $this->new $this->competitor";

            $grid->query_select_rows = "
                SELECT * FROM $this->table
                WHERE 1
                $this->enabled $this->ukraine $this->brand $this->category $this->bestsellers $this->new $this->competitor";

            $grid->show_rows_num_select = false;
            $grid->default_sort_direction = 'DESC';
            $grid->rows_num = 100;

            $grid->registerHeader('Код 1С', 'code_1c', false, 'ASC');
            $grid->registerHeader('Артикул', 'product_code', false, 'ASC');
            $grid->registerHeader('Наименование', 'name_ru', true, 'ASC');
            $grid->registerHeader('Торговая Марка', 'brand', false, 'ASC');
            $grid->registerHeader('Мультитойс', 'Price', false, 'ASC', 'right');
            
            switch ($this->conc) {
                
                case 'Alliance':
                    $grid->registerHeader('Альянс', 'Alliance', false, 'ASC', 'right');
                    $grid->registerHeader('разница', 'diff_Alliance', false, 'ASC', 'right');
                    break;
                case 'Divoland':
                    $grid->registerHeader('Диволенд', 'Divoland', false, 'ASC', 'right');
                    $grid->registerHeader('разница', 'diff_Divoland', false, 'ASC', 'right');
                    break;
                case 'Dreamtoys':
                    $grid->registerHeader('Веселка', 'Dreamtoys', false, 'ASC', 'right');
                    $grid->registerHeader('разница', 'diff_Dreamtoys', false, 'ASC', 'right');
                    break;
                case 'Mixtoys':
                    $grid->registerHeader('Микстойс', 'Mixtoys', false, 'ASC', 'right');
                    $grid->registerHeader('разница', 'diff_Mixtoys', false, 'ASC', 'right');
                    break;
                default:
                    $grid->registerHeader('Альянс', 'Alliance', false, 'ASC', 'right');
                    $grid->registerHeader('разница', 'diff_alliance', false, 'ASC', 'right');
                    $grid->registerHeader('Диволенд', 'Divoland', false, 'ASC', 'right');
                    $grid->registerHeader('разница', 'diff_divoland', false, 'ASC', 'right');
                    $grid->registerHeader('Веселка', 'Dreamtoys', false, 'ASC', 'right');
                    $grid->registerHeader('разница', 'diff_dreamtoys', false, 'ASC', 'right');
                    $grid->registerHeader('Микстойс', 'Mixtoys', false, 'ASC', 'right');
                    $grid->registerHeader('разница', 'diff_mixtoys', false, 'ASC', 'right');
            }
            $grid->prepare();

            $rows = $smarty->get_template_vars('GridRows');

            for ($k = count($rows) - 1; $k >= 0; $k--) {
                
                $rows[$k]['code_1c'] = $rows[$k]['code_1c'];
                $rows[$k]['product_code'] = $rows[$k]['product_code'];
                $rows[$k]['name_ru'] = $rows[$k]['name_ru'];
                $rows[$k]['brand'] = $rows[$k]['brand'];
                $rows[$k]['Price'] = $rows[$k][$this->currency.'Price'];

                $rows[$k]['Alliance'] = ($rows[$k][$this->currency.'Alliance']?$rows[$k][$this->currency.'Alliance']:'-----');
                $rows[$k]['diff_alliance'] = $rows[$k]['diff_alliance'].($rows[$k]['diff_alliance']?'%':'-----');
                $rows[$k]['Divoland'] = ($rows[$k][$this->currency.'Divoland'] ? $rows[$k][$this->currency.'Divoland'] : '-----');
                $rows[$k]['diff_divoland'] = $rows[$k]['diff_divoland'].($rows[$k]['diff_divoland']?'%':'-----');
                $rows[$k]['Dreamtoys'] = ($rows[$k][$this->currency.'Dreamtoys'] ? $rows[$k][$this->currency.'Dreamtoys'] : '-----');
                $rows[$k]['diff_dreamtoys'] = $rows[$k]['diff_dreamtoys'].($rows[$k]['diff_dreamtoys']?'%':'-----');
                $rows[$k]['Mixtoys'] = ($rows[$k][$this->currency.'Mixtoys'] ? $rows[$k][$this->currency.'Mixtoys'] : '-----');
                $rows[$k]['diff_mixtoys'] = $rows[$k]['diff_mixtoys'].($rows[$k]['diff_mixtoys']?'%':'-----');
            }

            $count_rows = array('100' => 100, '500' => 500, '1000' => 1000);

            $smarty->assign('Brands', $this->brands);
            $smarty->assign('Categories', $this->categories);
            $smarty->assign('GridRows', $rows);
            $smarty->assign('rows', $count_rows);
            $smarty->assign('TotalFound', str_replace('{N}', $grid->total_rows_num, 'Найдено товаров: {N}'));

            $smarty->display(DIR_TPLS.'/backend/competitors_report.html');
        }
    }

    ActionsController::exec('CompetitorsController');