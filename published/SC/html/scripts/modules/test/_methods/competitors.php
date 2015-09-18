<?php
    include(DIR_FUNC.'/competitors_report_function.php');

    class CompetitorsController extends ActionsController
    {

        /**
         * @var DataBase
         */
        var $DBHandler;
//        var $enabled = '';
        var $manufactured = '';
        var $competitor = ' AND (kindermarket OR divoland OR dreamtoys OR mixtoys)';
        var $conc = '';
        var $currency = '';
        var $brand = '';
        var $brands = array();
        var $category = '';
        var $categories = array();
        var $bestsellers = '';
        var $new = '';
        var $new_items_postup = '';
        var $table = 'Conc__analogs';

//        function __setEnabled()
//        {
//            $this->enabled = ' AND enabled = 1';
//        }

        function __setCurrency()
        {
            $this->currency = 'usd_';
        }

        function __setManufactured()
        {
            $made_in = '< 1';

            if (xEscapeSQLstring($_GET['manufactured']) === 'Ukraine') {
                $made_in = '> 0';
            }

            $this->manufactured = ' AND ukraine '.$made_in;
        }

        function __setBrand()
        {
            $this->brand = ' AND brand LIKE "%'.xEscapeSQLstring($_GET['brand']).'%"';
        }

        function __setCategory()
        {
            $this->category = ' AND category LIKE "%'.xEscapeSQLstring($_GET['category']).'%"';
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

        function __getNewItemsPostup()
        {
            $query = "SELECT t1.code_1c FROM SC_products t1 
                    LEFT JOIN SC_product_list_item t2  USING(productID)
                    WHERE t2.list_id = 'newitemspostup'";
            $res = mysql_query($query) or die(mysql_error().$query);

            $ids = array();

            while ($row = mysql_fetch_object($res)) {
                $ids[] = $row->code_1c;
            }
            $ids = implode(',', $ids);

            $this->new_items_postup = ' AND code_1c IN ('.$ids.')';
        }

        function __getBrandsArray()
        {
            $query = "SELECT DISTINCT brand FROM $this->table WHERE (kindermarket OR divoland OR dreamtoys OR mixtoys)";
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
            $query = "SELECT DISTINCT category FROM $this->table WHERE (kindermarket OR divoland OR dreamtoys OR mixtoys)";
            $res = mysql_query($query) or die(mysql_error().$query);

            while ($Categories = mysql_fetch_object($res)) {
                $this->categories[] = $Categories->category;
            }
            sort($this->categories);
        }

        function __getCategory()
        {
            $query = "SELECT DISTINCT category FROM $this->table";
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

//            if (isset($_GET['enabled'])) {
//                $this->__setEnabled();
//            }
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

            $grid->query_total_rows_num = "
                SELECT COUNT(*) FROM $this->table
                WHERE 1
                $this->manufactured $this->brand $this->category $this->bestsellers $this->new $this->new_items_postup $this->competitor";

            $grid->query_select_rows = "
                SELECT * FROM $this->table
                WHERE 1
                $this->manufactured $this->brand $this->category $this->bestsellers $this->new  $this->new_items_postup$this->competitor";

            $grid->show_rows_num_select = true;
            $grid->default_sort_direction = 'DESC';
            $grid->rows_num = 100;

            $grid->registerHeader('№');
            $grid->registerHeader('Код 1С', 'code_1c', false, 'ASC');
            $grid->registerHeader('Артикул', 'product_code', false, 'ASC');
            $grid->registerHeader('Фото');
            $grid->registerHeader('Наименование', 'name_ru', true, 'ASC');
            $grid->registerHeader('Категория', 'category', false, 'ASC');
            $grid->registerHeader('Мультитойс', 'Price', false, 'ASC', 'right');
            $grid->registerHeader('MAX-разница', 'max_diff', false, 'ASC', 'right');

            switch ($this->conc) {

                case 'kindermarket':
                    $grid->registerHeader('Киндер-Маркет', 'kindermarket', false, 'ASC', 'right');
                    $grid->registerHeader('разница', 'diff_kindermarket', false, 'ASC', 'right');
                    break;
                case 'divoland':
                    $grid->registerHeader('Диволенд', 'divoland', false, 'ASC', 'right');
                    $grid->registerHeader('разница', 'diff_divoland', false, 'ASC', 'right');
                    break;
                case 'dreamtoys':
                    $grid->registerHeader('Веселка', 'dreamtoys', false, 'ASC', 'right');
                    $grid->registerHeader('разница', 'diff_dreamtoys', false, 'ASC', 'right');
                    break;
                case 'mixtoys':
                    $grid->registerHeader('Микстойс', 'mixtoys', false, 'ASC', 'right');
                    $grid->registerHeader('разница', 'diff_mixtoys', false, 'ASC', 'right');
                    break;
                default:
                    $grid->registerHeader('Киндер-Маркет', 'kindermarket', false, 'ASC', 'right');
                    $grid->registerHeader('разница', 'diff_kindermarket', false, 'ASC', 'right');
                    $grid->registerHeader('Диволенд', 'divoland', false, 'ASC', 'right');
                    $grid->registerHeader('разница', 'diff_divoland', false, 'ASC', 'right');
                    $grid->registerHeader('Веселка', 'dreamtoys', false, 'ASC', 'right');
                    $grid->registerHeader('разница', 'diff_dreamtoys', false, 'ASC', 'right');
                    $grid->registerHeader('Микстойс', 'mixtoys', false, 'ASC', 'right');
                    $grid->registerHeader('разница', 'diff_mixtoys', false, 'ASC', 'right');
            }
            $grid->prepare();

            $rows = $smarty->get_template_vars('GridRows');

            for ($k = count($rows) - 1; $k >= 0; $k--) {

                $rows[$k]['num'] = $k + 1;
                $rows[$k]['code_1c'] = $rows[$k]['code_1c'];
                $rows[$k]['product_code'] = $rows[$k]['product_code'];
                $rows[$k]['img'] = '/published/publicdata/MULTITOYS/attachments/SC/search_pictures/'.$rows[$k]['code_1c'].'_s.jpg';
                $rows[$k]['img_big'] = '/published/publicdata/MULTITOYS/attachments/SC/products_pictures/'.$rows[$k]['code_1c'].'.jpg';
                $rows[$k]['name_ru'] = $rows[$k]['name_ru'];
                $rows[$k]['category'] = $rows[$k]['category'];
                $rows[$k]['Price'] = $rows[$k][$this->currency.'Price'];

//                $max_diff = max(
                //                    (int)$rows[$k]['diff_kindermarket'],
//                    (int)$rows[$k]['diff_divoland'],
//                    (int)$rows[$k]['diff_dreamtoys'],
//                    (int)$rows[$k]['diff_mixtoys']
//                );

                $rows[$k]['max_diff'] = ($rows[$k]['max_diff'] > 0)?$rows[$k]['max_diff'].'%':'-----';

                $rows[$k]['kindermarket'] = ($rows[$k][$this->currency.'kindermarket'] != 0)?$rows[$k][$this->currency.'kindermarket']:'-----';
                $rows[$k]['diff_kindermarket'] = ($rows[$k]['diff_kindermarket'] != 0)?$rows[$k]['diff_kindermarket'].'%':'-----';
                $rows[$k]['divoland'] = ($rows[$k][$this->currency.'divoland'] != 0)?$rows[$k][$this->currency.'divoland']:'-----';
                $rows[$k]['diff_divoland'] = ($rows[$k]['diff_divoland'] != 0)?$rows[$k]['diff_divoland'].'%':'-----';
                $rows[$k]['dreamtoys'] = ($rows[$k][$this->currency.'dreamtoys'] != 0)?$rows[$k][$this->currency.'dreamtoys']:'-----';
                $rows[$k]['diff_dreamtoys'] = ($rows[$k]['diff_dreamtoys'] != 0)?$rows[$k]['diff_dreamtoys'].'%':'-----';
                $rows[$k]['mixtoys'] = ($rows[$k][$this->currency.'mixtoys'] != 0)?$rows[$k][$this->currency.'mixtoys']:'-----';
                $rows[$k]['diff_mixtoys'] = ($rows[$k]['diff_mixtoys'] != 0)?$rows[$k]['diff_mixtoys'].'%':'-----';

            }

            $count_rows = array('100' => 100, '500' => 500, '1000' => 1000);

//            $smarty->assign('IMG_URL', '/published/publicdata/MULTITOYS/attachments/SC/search_pictures');
            $smarty->assign('Brands', $this->brands);
            $smarty->assign('Categories', $this->categories);
            $smarty->assign('GridRows', $rows);
            $smarty->assign('rows', $count_rows);
            $smarty->assign('TotalFound', str_replace('{N}', $grid->total_rows_num, 'Найдено товаров: {N}'));

            $smarty->display(DIR_TPLS.'/backend/competitors_report.html');
        }
    }

    ActionsController::exec('CompetitorsController');