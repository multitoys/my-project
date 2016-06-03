<?php
    require_once(DIR_FUNC.'/export_products_function.php');
    
    class Market extends Module
    {
        const MARKET_FILE = "yandex.xml";
        public $_var = array();
        public $cs_skidka = 0;
        public $cs_skidka_ua = 0;
        private $_file_to_download;
        private $_url_path = '/published/SC/html/scripts/get_file.php?getFileParam=R2V0WWFuZGV4&download=1&did=73';
        
        public function __construct()
        {
            parent::__construct();
//            if (!isset($_POST["market_export"]) && isset($GET["market_file"])) {
//                $_POST["market_export"] = '';
//                $var = '{"market_export":"market_export",
//                         "base_url":"http:\/\/multitoys.com.ua\/",
//                         "expandID":"",
//                         "unexpandID":"",
//                         "showProducts":"", 
//                         "updateCategory":"",
//                         "CHECKED_CATEGORIES":"{"22699":"1"}",
//                         "market_uah_rate":"1.00",
//                         "market_export_description":"0",
//                         "market_export_product_name":"only_name",
//                         "market_export_sales_notes":"",
//                         "market_export_local_delivery_cost":"0.0",
//                         "dpt":"modules",
//                         "sub":"market",
//                         "_export":""
//                        }';
//            $this->_var = json_decode($var, true);
//            foreach($this->_var as $key => $value) {
//                $_POST[$key] = $value;
//            }
//            }
//            array (
//                'market_export' => 'market_export',
//                'base_url' => 'http://my-project/',
//                'expandID' => '',
//                'unexpandID' => '',
//                'showProducts' => '',
//                'updateCategory' => '',
//                'CHECKED_CATEGORIES' =>
//                    array (
//                        22699 => '1',
//                        41041 => '1',
//                        42229 => '1',
//                        10775 => '1',
//                        14951 => '1',
//                        14044 => '1',
//                        6086 => '1',
//                        569 => '1',
//                        10773 => '1',
//                        10774 => '1',
//                        7767 => '1',
//                        10772 => '1',
//                        2427 => '1',
//                    ),
//                'market_uah_rate' => '1',
//                'market_export_description' => '0',
//                'market_export_product_name' => 'only_name',
//                'market_export_sales_notes' => '',
//                'market_export_local_delivery_cost' => '0.0',
//                'dpt' => 'modules',
//                'sub' => 'market',
//                '_export' => '',
//            )
            
            $_POST = array
            (
                'market_export'      => 'market_export',
                'base_url'           => $this->getStoreUrl(),
                //'expandID'           => '',
                //'unexpandID'         => '',
                //'showProducts'       => '',
                //'updateCategory'     => '',
                'CHECKED_CATEGORIES' => array
                (
                    '22699' => '1',
                    '41041' => '1',
                    '42229' => '1',
                    '10775' => '1',
                    '14951' => '1',
                    '14044' => '1',
                    '6086'  => '1',
                    '569'   => '1',
                    '10773' => '1',
                    '10774' => '1',
                    '7767'  => '1',
                    '10772' => '1',
                    '2427'  => '1'
                ),
                
                'market_uah_rate'                   => '1',
                'market_export_description'         => '0',
                'market_export_product_name'        => 'only_name',
                'market_export_sales_notes'         => '',
                'market_export_local_delivery_cost' => '0.0',
                'dpt'                               => 'modules',
                'sub'                               => 'market',
                '_export'                           => ''
            );
    
            if (isset($_GET['discount'])) {
                
                $this->cs_skidka = (int)$_SESSION['cs_skidka'];
                $this->cs_skidka_ua = (int)$_SESSION['cs_skidka_ua'];
                $this->_url_path .= '&discount=1';
            }
    
            $this->_file_to_download = DIR_TEMP.'/'.date('Y-m-d').'_'.((int)(date('H') / 4)).
                '_disc_'.$this->cs_skidka.'_'.$this->cs_skidka_ua.'_'.Market::MARKET_FILE;
            
            $this->methodExport();
        }
        
        function initInterfaces()
        {
            $this->Interfaces = array(
                'export_page'     => array(
                    'name'   => 'Страница экспорта продуктов для интернет-магазинов',
                    'method' => 'methodExport'
                ),
                'xml_file_access' => array(
                    'name'   => 'Доступ к файлу Маркет',
                    'method' => 'methodXMLFileAccess'
                )
            );
        }
        
        function methodXMLFileAccess()
        {
            
            //доступ к файлу для Яндекс.Маркет
            $fileToDownLoad = DIR_TEMP."/".date("Y-m-d")."_".((int)(date("H") / 4))."_".Market::MARKET_FILE;
            
            if (isset($_GET["download"])) {
                if (file_exists($fileToDownLoad)) {
                    
                    header('Content-type: application/force-download');
                    header('Content-Transfer-Encoding: Binary');
                    header('Content-length: '.filesize($fileToDownLoad));
                    header('Content-disposition: attachment; filename='.basename($fileToDownLoad));
                    readfile($fileToDownLoad);
                } else {
                    $this->methodExport();
                }
            } /*else {
                echo implode("", file($fileToDownLoad));
            }
                exit(1);
            } else {
                if (function_exists('error404page')) error404page();
            }*/
        }
        
        function methodExport()
        {
            global $smarty;
            
            //show successful save confirmation message
            
            if (file_exists($this->_file_to_download)) {
                
                if (filesize($this->_file_to_download) > 1024 * 1024) {
                    
                    $file_info = array(
                        'size'  => (string)round(filesize($this->_file_to_download) / 1024),
                        'mtime' => Time::standartTime(filemtime($this->_file_to_download)),
                    );
                    
                    $smarty->assign("market_file", $file_info);
                    
                    if (isset($_GET["market_export_successful"])) {
                        
                        //set_query('market_export_successful=yes', '', true);
                        echo '<script>window.location.href="'.$this->_url_path.'";</script>';
                        $smarty->assign("market_export_successful", 1);
                        $smarty->assign('base_url', $this->getStoreUrl());
                    }
                } else {
                    unlink($this->_file_to_download);
                }
            } else {
//                $_XXX = '_POST';

//                if (!isset($_POST["market_export"])) {
//                    $_POST["market_export"] = '';
//                    $_XXX = 'var';
//                }
//            if ($$_XXX["market_export"]) //save payment gateways_settings
//            {
                $uah_rate = (float)$_POST["market_uah_rate"];
                $market_export_product_name = isset($_POST['market_export_product_name'])?$_POST['market_export_product_name']:'only_name';
                
                if ($uah_rate <= 0) {
                    $smarty->assign("market_errormsg", "Курс гривны указан неверно. Пожалуйста, вводите положительное число");
                } else {//экспортировать товары
                    $f = @fopen($this->_file_to_download, "wb");
                    if (flock($f, LOCK_EX)) { // выполняем эксклюзивную блокировку
                        ftruncate($f, 0); // очищаем файл
                        $this->_exportToMarket($f, $uah_rate, $market_export_product_name);
                        fflush($f);        // очищаем вывод перед отменой блокировки
                        flock($f, LOCK_UN); // отпираем файл
                        fclose($f);
                        iconv_file('utf-8', 'cp1251', $this->_file_to_download, true);
                        RedirectSQ('market_export_successful=yes');
                    } else {
                        $smarty->assign("market_errormsg", "Ошибка при создании файла ".Market::MARKET_FILE);
                        echo "Не удалось получить блокировку !";
                    }
                    //if ($f) {
                    //    $this->_exportToMarket($f, $uah_rate, $market_export_product_name);
                    //    fclose($f);
                    //    iconv_file('utf-8', 'cp1251', DIR_TEMP."/".date("Y-m-d")."_".((int)(date("H") / 4))."_".Market::MARKET_FILE, true);
                    //    RedirectSQ('market_export_successful=yes');
                    //} else {
                    //    $smarty->assign("market_errormsg", "Ошибка при создании файла ".Market::MARKET_FILE);
                    //}
                }
            }
            require_once(DIR_ROOT.'/includes/modules.export_products.php');
            $smarty->assign("admin_sub_dpt", "modules_market.tpl.html");
        }
        
        private function getStoreUrl()
        {
//            $var = json_decode('{"market_export":"market_export","base_url":"http:\/\/my-project\/","expandID":"","unexpandID":"","showProducts":"","updateCategory":"","CHECKED_CATEGORIES":{
//            "22699":"1"},"market_uah_rate":"1","market_export_description":"0","market_export_product_name":"only_name","market_export_sales_notes":"","market_export_local_delivery_cost":"0.0","dpt":"modules","sub":"market","_export":""}', true);
//var_dump($var);
//            $_XXX = '_POST';
//            $_XXX = 'var';
            
            static $store_url = null;
            if (!is_null($store_url)) {
                return $store_url;
            }
            $store_url = correct_URL(isset($_POST['base_url'])?$_POST['base_url']:CONF_FULL_SHOP_URL);
            
            return $store_url;
        }
        
        function _exportToMarket($f, $rate, $export_product_name)
        {
//            $var = json_decode('{"market_export":"market_export","base_url":"http:\/\/my-project\/","expandID":"","unexpandID":"","showProducts":"","updateCategory":"","CHECKED_CATEGORIES":{
//            "22699":"1"},"market_uah_rate":"1","market_export_description":"0","market_export_product_name":"only_name","market_export_sales_notes":"","market_export_local_delivery_cost":"0.0","dpt":"modules","sub":"market","_export":""}', true);
//var_dump($var);
//            $_XXX = '_POST';
//            $_XXX = 'var';
            
            $spArray = array(
                'exprtUNIC' => array(
                    'mode'        => 'toarrays',
                    'expProducts' => array()
                )
            );
            $exportCategories = array(array(), array());
            export_exportSubcategories(0, $exportCategories, $spArray);
            $this->_exportBegin($f);
            $this->_exportAllCategories($f, $spArray['exprtUNIC']['expProducts']);
            $local_delivery_cost = isset($_POST['market_export_local_delivery_cost'])?floatval(str_replace(',', '.', $_POST['market_export_local_delivery_cost'])):false;
            if (isset($_POST['market_export_local_delivery_cost_enabled']) && $_POST['market_export_local_delivery_cost_enabled']) {
                fputs($f, "				<local_delivery_cost>".$local_delivery_cost."</local_delivery_cost>\n");
            }
            $this->_exportProducts($f, $rate, $export_product_name, $spArray['exprtUNIC']['expProducts']);
            $this->_exportEnd($f);
        }
        
        function _exportBegin($f)
        {
            fputs($f, "<?xml version=\"1.0\" encoding=\"windows-1251\"?>\n");
            fputs($f, "	<!DOCTYPE yml_catalog SYSTEM \"shops.dtd\">\n");
            fputs($f, "		<yml_catalog date=\"".date("Y-m-d H:i")."\">\n");
            fputs($f, "			<shop>\n");
            fputs($f, "				<name>".$this->_deleteHTML_Elements(CONF_SHOP_NAME)."</name>\n");
            fputs($f, "				<company>".$this->_deleteHTML_Elements(CONF_SHOP_NAME)."</company>\n");
            fputs($f, "				<url>".$this->getStoreUrl()."</url>\n");
            fputs($f, "				<currencies>\n");
            fputs($f, "					<currency id=\"UAH\" rate=\"1\"/>\n");
            fputs($f, "					<currency id=\"USD\" rate=\"CB\"/>\n");
            fputs($f, "					<currency id=\"EUR\" rate=\"CB\"/>\n");
            fputs($f, "				</currencies>\n");
        }
        
        function _deleteHTML_Elements($str, $strip_tags = true)
        {
            if ($strip_tags) {
                $str = strip_tags($str);
            }
            $str = str_replace('&nbsp;', ' ', $str);
            $str = str_replace("&", "&amp;", $str);
            $str = str_replace("<", "&lt;", $str);
            $str = str_replace(">", "&gt;", $str);
            $str = str_replace("\"", "&quot;", $str);
            $str = str_replace("'", "&apos;", $str);
            $str = str_replace("\r", "", $str);
            
            return $str;
        }
        
        function _exportAllCategories($f, &$_ProductIDs)
        {
//            $var = json_decode('{"market_export":"market_export","base_url":"http:\/\/my-project\/","expandID":"","unexpandID":"","showProducts":"","updateCategory":"","CHECKED_CATEGORIES":{
//            "22699":"1"},"market_uah_rate":"1","market_export_description":"0","market_export_product_name":"only_name","market_export_sales_notes":"","market_export_local_delivery_cost":"0.0","dpt":"modules","sub":"market","_export":""}', true);
//var_dump($var);
//            $_XXX = '_POST';
//            $_XXX = 'var';
            
            if (!count($_ProductIDs)) return 0;
            $Cats = array();
            $execCats = array();
            $sql = "
					SELECT catt.categoryID, ".LanguagesManager::sql_prepareField('catt.name')." AS name, catt.parent AS parent, catt.slug FROM ".CATEGORIES_TABLE." as catt
					LEFT JOIN ".PRODUCTS_TABLE." as prot ON catt.categoryID=prot.categoryID
					WHERE prot.productID IN (".implode(", ", $_ProductIDs).")
					GROUP BY prot.categoryID
					ORDER BY parent, name
				";
            $q = db_query($sql);
            fputs($f, "				<categories>\n");
            
            while ($row = db_fetch_row($q)) {
                
                if (!in_array($row[0], $execCats)) {
                    
                    $execCats[] = $row[0];
                }
                
                if (!in_array($row[2], $Cats) && $row[2] > 1) {
                    
                    $Cats[] = $row[2];
                }
                
                $row[1] = $this->_deleteHTML_Elements($row[1]);
                
                if ($row[2] <= 1) {
                    fputs($f, "					<category id=\"".$row[0]."\">".$row[1].
                        "</category>\n");
                } else {
                    fputs($f, "					<category id=\"".$row[0]."\" parentId=\"".$row[2]."\">".$row[1]."</category>\n");
                }
            }
            
            while (count($Cats)) {
                
                $sql = "
						SELECT categoryID, ".LanguagesManager::sql_prepareField('name')." AS name, parent FROM ".CATEGORIES_TABLE." WHERE categoryID IN (".implode(", ", $Cats).")
						";
                $q = db_query($sql);
                $Cats = array();
                
                while ($row = db_fetch_row($q)) {
                    
                    $Disp = false;
                    if (!in_array($row[0], $execCats)) {
                        
                        $execCats[] = $row[0];
                        $Disp = true;
                    }
                    if (!in_array($row[2], $execCats) && !in_array($row[2], $Cats) && $row[2] > 1) {
                        
                        $Cats[] = $row[2];
                    }
                    $row[1] = $this->_deleteHTML_Elements($row[1]);
                    if ($row[2] <= 1 && $Disp) {
                        fputs($f, "					<category id=\"".$row[0]."\">".$row[1].
                            "</category>\n");
                    } elseif ($Disp) {
                        fputs($f, "					<category id=\"".$row[0]."\" parentId=\"".$row[2]."\">".$row[1]."</category>\n");
                    }
                }
            }
            
            fputs($f, "				</categories>\n");
        }
        
        function _exportProducts($f, $rate, $export_product_name, &$_ProductIDs)
        {
//            $var = json_decode('{"market_export":"market_export","base_url":"http:\/\/my-project\/","expandID":"","unexpandID":"","showProducts":"","updateCategory":"","CHECKED_CATEGORIES":{
//            "22699":"1"},"market_uah_rate":"1","market_export_description":"0","market_export_product_name":"only_name","market_export_sales_notes":"","market_export_local_delivery_cost":"0.0","dpt":"modules","sub":"market","_export":""}', true);
//var_dump($var);
//            $_XXX = '_POST';
//            $_XXX = 'var';
            fputs($f, "				<offers>\n");
            
            //товары с нулевым остатком на складе
            $clause = isset($_POST["market_dont_export_negative_stock"])?" and in_stock>0":"";
            //комментарии к товарам
            $sales_notes = isset($_POST['market_export_sales_notes'])?$this->_deleteHTML_Elements($_POST['market_export_sales_notes'], false):false;
            //
            $local_delivery_cost_enabled = (isset($_POST['market_export_local_delivery_cost_override']) && $_POST['market_export_local_delivery_cost_override']);
            
            //какое описание экспортировать
            if ($_POST["market_export_description"] == 1) {
                $dsc = "description";
                $dsc_q = ", ".LanguagesManager::sql_prepareField($dsc)." as ".$dsc;
            } else if ($_POST["market_export_description"] == 2) {
                $dsc = "brief_description";
                $dsc_q = ", ".LanguagesManager::sql_prepareField($dsc)." as ".$dsc;
            } else {
                $dsc = "";
                $dsc_q = "";
            }
            
            //выбрать товары
            $proCount = count($_ProductIDs);
            $chunk_size = 100;
            $iter = 0;
            
            for (; $iter < $proCount; $iter += $chunk_size) {
                
                $sql = "select productID, product_code, ".LanguagesManager::sql_prepareField('name')." AS name, Price, categoryID, default_picture".$dsc_q.", in_stock, slug, eproduct_filename, skidka, code_1c, ukraine, min_order_amount".($local_delivery_cost_enabled?', free_shipping, shipping_freight':'')." from ".PRODUCTS_TABLE."
					where ".(count($_ProductIDs)?"productID IN(".implode(", ", array_slice($_ProductIDs, $iter, $chunk_size)).") AND ":"")."enabled=1 AND ordering_available>0 ".$clause;
                
                $q = db_query($sql);
                
                $store_url = $this->getStoreUrl();
                
//                $picture_url = (MOD_REWRITE_SUPPORT&&false)?$store_url.'products_pictures/':BASE_URL.URL_PRODUCTS_PICTURES.'/';
                $picture_url = BASE_URL.'products_pictures/';
                $picture_url = preg_replace('@([^:]{1})//@', '\\1/', $picture_url);
                
                while ($product = db_fetch_row($q)) {
                    
                    fputs($f, "					<offer available=\"".(($product['in_stock'] || !CONF_CHECKSTOCK)?'true':'false')."\" id=\"".$product["productID"]."\">\n");
                    fputs($f, "						<url>".str_replace('&', '&amp;', set_query('ukey=product'.(MOD_REWRITE_SUPPORT?'&furl_enable=1':'').'&product_slug='.$product['slug'].'&productID='.$product['productID'], $store_url))."</url>\n");
                    fputs($f, "						<price>".RoundFloatValueStr(priceDiscount($product["Price"], $product["skidka"], $product["ukraine"]) * $rate)."</price>\n");
                    fputs($f, "						<currencyId>UAH</currencyId>\n");
                    fputs($f, "						<categoryId>".$product["categoryID"]."</categoryId>\n");
                    
                    if ($product["default_picture"] != null) {
//                        $pic_clause = " and photoID=".((int)$product["default_picture"]);
                        $pic_clause = "photoID=".((int)$product["default_picture"]);
                    } else
                        $pic_clause = "";

                    if ($pic_clause) { //                    $q1 = db_query("SELECT filename, enlarged, thumbnail FROM ".PRODUCT_PICTURES." WHERE productID=".$product["productID"].$pic_clause.' ORDER BY priority');//.' ORDER BY priority');                    
                        $q1 = db_query("SELECT filename FROM " . PRODUCT_PICTURES . " WHERE $pic_clause");//.' ORDER BY priority');
                        $pic_row = db_fetch_row($q1);
//                    if ($pic_row) {
//                        if (strlen($pic_row["filename"]) && file_exists("/products_pictures/".$pic_row["filename"]))
//                            fputs($f, "						<picture>".$picture_url.str_replace(' ', '%20', $this->_deleteHTML_Elements($pic_row["filename"]))."</picture>\n");
//                        else
//                            if (strlen($pic_row["filename"]) && file_exists(DIR_PRODUCTS_PICTURES."/".$pic_row["enlarged"]))
//                                fputs($f, "						<picture>".$store_url.URL_PRODUCTS_PICTURES.'/'.str_replace(' ', '%20', $this->_deleteHTML_Elements($pic_row["enlarged"]))."</picture>\n");
//                    }
                        if ($pic_row) {
                            if (strlen($pic_row["filename"]) && file_exists(WBS_DIR . "/products_pictures/" . $pic_row["filename"])) {
                                fputs($f, "						<picture>" . $picture_url . str_replace(' ', '%20', $this->_deleteHTML_Elements($pic_row["filename"])) . "</picture>\n");
                            }
                            //                        else
                            //                            if (strlen($pic_row["filename"]) && file_exists(DIR_PRODUCTS_PICTURES."/".$pic_row["enlarged"]))
                            //                                fputs($f, "						<picture>".$store_url.URL_PRODUCTS_PICTURES.'/'.str_replace(' ', '%20', $this->_deleteHTML_Elements($pic_row["enlarged"]))."</picture>\n");
                        }
}
                    
                    switch ($export_product_name) {
                        default:
                        case 'only_name':
                            $_NameAddi = '';
                            break;
                        case 'path_and_name':
                            $_NameAddi = '';
                            $_t = catCalculatePathToCategory($product['categoryID']);
                            foreach ($_t as $__t)
                                if ($__t['categoryID'] != 1)
                                    $_NameAddi .= $__t['name'].':';
                            break;
                    }

//				fputs( $f, "                        <delivery>true</delivery>\n" );
                    if ($local_delivery_cost_enabled && ($product['free_shipping'] || $product['shipping_freight'])) {
                        
                        fputs($f, "						<local_delivery_cost>".($product['free_shipping']?'0':(float)$product['shipping_freight'])."</local_delivery_cost>\n");
                    }
                    
                    $product["name"] = $this->_deleteHTML_Elements($_NameAddi.$product["name"]);
                    
                    fputs($f, "						<name>".$product["name"]."</name>\n");
                    
                    $product["product_code"] = $product["product_code"] = $this->_deleteHTML_Elements($product["product_code"]);
                    
                    fputs($f, "						<vendorCode>".$product["product_code"]."</vendorCode>\n");
                    
                    $product["product_code"] = $product["product_code"] = $this->_deleteHTML_Elements($product["product_code"]);
                    
                    fputs($f, "						<code_1c>".$product["code_1c"]."</code_1c>\n");
                    
                    if (strlen($dsc) > 0) {
                        $product[$dsc] = $this->_deleteHTML_Elements($product[$dsc]);
                        fputs($f, "						<description>".$product[$dsc]."</description>\n");
                    } else {
                        fputs($f, "						<description></description>\n");
                    }

//                    if ($sales_notes) {
//                        fputs($f, "						<sales_notes>" . $sales_notes . "</sales_notes>\n");
//                    } elseif ($product["min_order_amount"] > 1) {
//                        fputs($f, "						<sales_notes>Минимальный заказ: " . $product["min_order_amount"] . " шт.</sales_notes>\n");
//                    }
//                    if (trim($product["eproduct_filename"]) != "") {
//
//                        fputs($f, "						<downloadable>true</downloadable>\n");
//                    } else {
//                        fputs($f, "						<downloadable>false</downloadable>\n");
//                    }
                    fputs($f, "					</offer>\n");
                }
                db_free_result($q);
            }
            fputs($f, "				</offers>\n");
        }
        
        function _exportEnd($f)
        {
            fputs($f, "			</shop>\n");
            fputs($f, "		</yml_catalog>\n");
        }
    }