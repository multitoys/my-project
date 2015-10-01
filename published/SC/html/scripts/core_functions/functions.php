<?php
    if (!isset($_SERVER['REQUEST_URI'])) {
        $req = $_SERVER['PHP_SELF'];
        if (isset($_SERVER['QUERY_STRING']) && (strlen($_SERVER['QUERY_STRING']) > 0)) {
            $req .= '?'.$_SERVER['QUERY_STRING'];
        }
        $_SERVER['REQUEST_URI'] = $GLOBALS['REQUEST_URI'] = $req;
    }

//frequently used functions
    function MagicQuotesRuntimeSetting()
    {
        ini_set("magic_quotes_runtime", 0);
    }

// function session_is_registered($x) {
    // return isset($_SESSION[$x]);
// }

    function correct_URL($url, $mode = "http")
    {

        $URLprefix = trim($url);
        $URLprefix = str_replace(array('http://', 'https://', 'index.php'), '', $URLprefix);
        if ($URLprefix[strlen($URLprefix) - 1] == '/') {
            $URLprefix = substr($URLprefix, 0, strlen($URLprefix) - 1);
        }

        return ($mode."://".$URLprefix."/");
    }

    /**
     * Sets access rights to files which uploaded with help move_uploaded_file
     * @param string $file_name
     */
    function SetRightsToUploadedFile($file_name)
    {
        @chmod($file_name, 0666);
    }

    function Redirect($url)
    {
        $softwareInfo = getServerInfo();

        $winIIS = strstr(php_uname(), 'Windows') && ($softwareInfo === 'IIS');

        if ($winIIS) {
            $str_redirect = 'Refresh: 0;url=%s';
        } else {
            $str_redirect = 'Location: %s';
        }
        header(sprintf($str_redirect, escapeCRLF($url)));
        exit(1);
        //header("location: ".escapeCRLF($url), true, 302);
        //exit(1);
    }

    function getServerInfo()
    {
        $ssoft = strtolower($_SERVER['SERVER_SOFTWARE']);
        if (strstr($ssoft, 'apache')) {
            $sos = 'Apache';
        } elseif (strstr($ssoft, 'iis')) {
            $sos = 'IIS';
        } else {
            $sos = 'Apache';
        }

        return $sos;
    }

    function RedirectSQ($_params = '', $_url = '')
    {
        Redirect(renderURL($_params, $_url));
    }

    /**
     * round float value to 0.01 precision
     *
     * @param float $float_value
     * @return float
     */
    function RoundFloatValue($float_value)
    {
        return round(100 * $float_value) / 100;
    }

// Purpose	round float value to 0.01 precision
// Inputs   $float_value - value to float
// Remarks	this function returns string value.
//				Two digits locate after decimal point always.
// Returns	rounded value
    function RoundFloatValueStr($float_value)
    {
        $str = RoundFloatValue($float_value);
        $index = strpos($str, '.');
        if ($index === false) {
            return $str.'.00';
        } else {
            if (strlen($str) - 1 - $index == 1)
                return $str.'0';
            else
                return $str;
        }
    }

// Purpose	gets all files in specified directory
// Inputs   $dir - full path directory
    function GetFilesInDirectory($dir, $extension = '', $name_template = null)
    {
        if (!file_exists($dir)) return array();

        $dh = opendir($dir);
        $files = array();
        $pattern = '|'.($name_template ? $name_template : '').'\.'.$extension.'$|msi';
        while (false !== ($filename = readdir($dh))) {
            if (!is_dir($dir.'/'.$filename) && $filename != '.' && $filename != '..') {

                if (preg_match($pattern, $filename)) {
                    $files[] = $dir.'/'.$filename;
                }
            }
        }

        return $files;
    }


    /**
     * Show a number and selected currency sign
     *
     * @param float $price - is in universal currency
     * @param mixed $custom_currency - if $custom_currency != 0 show price this currency with ID = $custom_currency
     * @param boolean $priceInUC - notify about price is in UC format
     * @return string
     */
    function show_price($price, $custom_currency = 0, $priceInUC = true)
    {


        if ($custom_currency) {
            $currencyEntry = new Currency();
            $currencyEntry->loadByCID($custom_currency);
        } else {
            $Register = &Register::getInstance();
            /*@var $Register Register*/
            $currencyEntry = $Register->get('admin_mode') ? Currency::getDefaultCurrencyInstance() : Currency::getSelectedCurrencyInstance();
            /*@var $currencyEntry Currency*/
        }

        $price = $priceInUC ? $currencyEntry->convertUnits($price) : $price;

        return $currencyEntry->getView($price);
    }

    function ConvertPriceToUniversalUnit($priceWithOutUnit)
    {

        $currencyEntry = Currency::getSelectedCurrencyInstance();

        return $currencyEntry->convertToUnits($priceWithOutUnit, true);
    }

    function show_priceWithOutUnit($price)
    {

        $currencyEntry = Currency::getSelectedCurrencyInstance();

        return $currencyEntry->convertUnits($price, true);
    }

    //    function AuxpageNavigator($a, $p, $q, $path, &$out)
    //    {
    //        //shows navigator [prev] 1 2 3 4 � [next]
    //        //$a - count of elements in the array, which is being navigated
    //        //$p - current p in array (showing elements [$p ... $p+$q])
    //        //$q - quantity of items per page
    //        //$path - link to the page (f.e: "index.php?categoryID=1&")
    //
    //        if ($a > $q) //if all elements couldn't be placed on the page
    //        {
    //            $c = (int)($p + $q) / $q;
    //            $out .= "<ul class=uk-pagination data-uk-pagination=\"{items:$a, itemsOnPage:$q, currentPage:$c}\">";
    //            //[prev]
    //            if ($p > 0) $out .= '<li><a href="'.xHtmlSetQuery($path.'&p='.($p - $q)).'">&lt;&lt; '.translate('str_previous').'</a></li> &nbsp;&nbsp;';
    //
    //            //digital links
    //            $k = $p / $q;
    //
    //            //not more than 4 links to the left
    //            $min = $k - 4;
    //            if ($min < 0) {
    //                $min = 0;
    //            } else {
    //                if ($min >= 1) { //link on the 1st page
    //                    $out .= "<li><a href=\"".xHtmlSetQuery($path.'&p=0')."\">1</a></li> &nbsp;&nbsp;";
    //                    if ($min != 1) {
    //                        $out .= '<li><span class=pagination>...</span></li>&nbsp;';
    //                    };
    //                }
    //            }
    //
    //            for ($i = $min; $i < $k; $i++) {
    //                $m = $i * $q + $q;
    //                if ($m > $a) $m = $a;
    //
    //                $out .= '<li><a href="'.xHtmlSetQuery($path.'&p='.($i * $q))."\">".($i + 1).'</a></li> &nbsp;&nbsp;';
    //            }
    //
    //            //# of current page
    //            if (strcmp($p, 'show_all')) {
    //                $min = $p + $q;
    //                if ($min > $a) $min = $a;
    //                $out .= '<li class=uk-active><span>'.($k + 1).'</span></li> &nbsp;&nbsp;';
    //            } else {
    //                $min = $q;
    //                if ($min > $a) $min = $a;
    //                $out .= '<li><a href="'.xHtmlSetQuery($path.'&p=0').'">1</a> </li>&nbsp;&nbsp;';
    //            }
    //
    //            //not more than 5 links to the right
    //            $min = $k + 4;
    //            if ($min > ceil($a / $q)) {
    //                $min = ceil($a / $q);
    //            };
    //            for ($i = $k + 1; $i < $min; $i++) {
    //                $m = $i * $q + $q;
    //                if ($m > $a) $m = $a;
    //
    //                $out .= '<li><a href="'.xHtmlSetQuery($path.'&p='.($i * $q))."\">".($i + 1).'</a></li> &nbsp;&nbsp;';
    //            }
    //
    //            if ($min * $q < $a) { //the last link
    //                if ($min * $q < $a - $q) $out .= '<li><span class=pagination>...</span></li>&nbsp;&nbsp;';
    //                if (!($a % $q == 0))
    //                    $out .= '<li><a href=\''.xHtmlSetQuery($path.'&p='.($a - $a % $q)).'\'>'.(floor($a / $q) + 1).'</a></li> &nbsp;&nbsp;';
    //                else //$a is divided by $q
    //                    $out .= '<li><a href=\''.xHtmlSetQuery($path.'&p='.($a - $q)).'\'>'.(floor($a / $q)).'</a></li> &nbsp;&nbsp;';
    //            }
    //
    //            //[next]
    //            if (strcmp($p, 'show_all'))
    //                if ($p < $a - $q) $out .= '<li><a href="'.xHtmlSetQuery($path.'&p='.($p + $q)).'">'.translate('str_next').' &gt;&gt;</a></li> ';
    //
    //            //[show all]
    //            if (SHOWALL_ALLOWED_RECORDS_NUM >= $a || (!SystemSettings::is_hosted() && SystemSettings::is_backend())) {
    //                if (strcmp($p, 'show_all'))
    //                    $out .= ' |&nbsp; <li><a href="'.xHtmlSetQuery($path.'&p=&show_all=yes').'">'.translate('str_showall').'</a></li>';
    //                else
    //                    $out .= ' |&nbsp; <li><a class=pagination></a><B>'.translate('str_showall').'</B></li>';
    //            }
    //            $out .= '</ul>';
    //
    //            return $out;
    //        }
    //    }

    function SimpleNavigator($a, $p, $q, $path, &$out)
    {
        //shows navigator [prev] 1 2 3 4 � [next]
        //$a - count of elements in the array, which is being navigated
        //$p - current p in array (showing elements [$p ... $p+$q])
        //$q - quantity of items per page
        //$path - link to the page (f.e: "index.php?categoryID=1&")

        if ($a > $q) //if all elements couldn't be placed on the page
        {
            $c = (int)($p + $q) / $q;
            $out .= '<ul>';
            //[prev]
            if ($p > 0) $out .= '<li><a href="'.xHtmlSetQuery($path.'&p='.($p - $q)).'" class="page-link prev">&lt;&lt;</a></li> &nbsp;&nbsp;';

            //digital links
            $k = $p / $q;

            //not more than 4 links to the left
            $min = $k - 4;
            if ($min < 0) {
                $min = 0;
            } else {
                if ($min >= 1) { //link on the 1st page
                    $out .= '<li><a href="'.xHtmlSetQuery($path.'&p=0').'" class=page-link>1</a></li> &nbsp;&nbsp;';
                    if ($min != 1) {
                        $out .= '<li><span class=disabled>...</span></li>&nbsp;';
                    };
                }
            }

            for ($i = $min; $i < $k; $i++) {
                $m = $i * $q + $q;
                if ($m > $a) $m = $a;

                $out .= '<li><a href="'.xHtmlSetQuery($path.'&p='.($i * $q)).'" class=page-link>'.($i + 1).'</a></li> &nbsp;&nbsp;';
            }

            //# of current page
            if (strcmp($p, 'show_all')) {
                $min = $p + $q;
                if ($min > $a) $min = $a;
                $out .= '<li class=active><span class=current>'.($k + 1).'</span></li> &nbsp;&nbsp;';
            } else {
                $min = $q;
                if ($min > $a) $min = $a;
                $out .= '<li><a href="'.xHtmlSetQuery($path.'&p=0').'" class=page-link>1</a> </li>&nbsp;&nbsp;';
            }

            //not more than 5 links to the right
            $min = $k + 4;
            if ($min > ceil($a / $q)) {
                $min = ceil($a / $q);
            };
            for ($i = $k + 1; $i < $min; $i++) {
                $m = $i * $q + $q;
                if ($m > $a) $m = $a;

                $out .= '<li><a href="'.xHtmlSetQuery($path.'&p='.($i * $q)).'" class=page-link>'.($i + 1).'</a></li> &nbsp;&nbsp;';
            }

            if ($min * $q < $a) { //the last link
                if ($min * $q < $a - $q) $out .= '<li><span class=disabled>...</span></li>&nbsp;&nbsp;';
                if (!($a % $q == 0))
                    $out .= '<li><a href="'.xHtmlSetQuery($path.'&p='.($a - $a % $q)).'" class=page-link>'.(floor($a / $q) + 1).'</a></li> &nbsp;&nbsp;';
                else //$a is divided by $q
                    $out .= '<li><a href="'.xHtmlSetQuery($path.'&p='.($a - $q)).'" class=page-link>'.(floor($a / $q)).'</a></li> &nbsp;&nbsp;';
            }

            //[next]
            if (strcmp($p, 'show_all'))
                if ($p < $a - $q) $out .= '<li><a href="'.xHtmlSetQuery($path.'&p='.($p + $q)).'" class="page-link next">&gt;&gt;</a></li> ';

            //[show all]
            if (SHOWALL_ALLOWED_RECORDS_NUM >= $a || (!SystemSettings::is_hosted() && SystemSettings::is_backend())) {
                if (strcmp($p, 'show_all'))
                    $out .= ' |&nbsp; <li><a href="'.xHtmlSetQuery($path.'&p=&show_all=yes').'" class=page-link>'.translate('str_showall').'</a></li>';
                else
                    $out .= ' |&nbsp; <li><a  class=page-link></a><B>'.translate('str_showall').'</B></li>';
            }
            $out .= '</ul>';

            return $out;
        }
    }

    function ShowNavigator($a, $offset, $q, $path, &$out)
    {
        //shows navigator [prev] 1 2 3 4 � [next]
        //$a - count of elements in the array, which is being navigated
        //$offset - current offset in array (showing elements [$offset ... $offset+$q])
        //$q - quantity of items per page
        //$path - link to the page (f.e: "index.php?categoryID=1&")

        if ($a > $q) //if all elements couldn't be placed on the page
        {

            //[prev]
            if ($offset > 0) $out .= '<a class=no_underline href="'.xHtmlSetQuery($path.'&offset='.($offset - $q)).'">&lt;&lt; '.translate('str_previous').'</a>&nbsp;&nbsp;';

            //digital links
            $k = $offset / $q;

            //not more than 4 links to the left
            $min = $k - 5;
            if ($min < 0) {
                $min = 0;
            } else {
                if ($min >= 1) { //link on the 1st page
                    $out .= '<a class=no_underline href="'.xHtmlSetQuery($path.'&offset=0').'">1</a>&nbsp;&nbsp;';
                    if ($min != 1) {
                        $out .= '... &nbsp;';
                    };
                }
            }

            for ($i = $min; $i < $k; $i++) {
                $m = $i * $q + $q;
                if ($m > $a) $m = $a;

                $out .= '<a class=no_underline href="'.xHtmlSetQuery($path.'&offset='.($i * $q)).'">'.($i + 1).'</a>&nbsp;&nbsp;';
            }

            //# of current page
            if (strcmp($offset, 'show_all')) {
                $min = $offset + $q;
                if ($min > $a) $min = $a;
                $out .= '<span class=faq><b>'.($k + 1).'</b></span>&nbsp;&nbsp;';
            } else {
                $min = $q;
                if ($min > $a) $min = $a;
                $out .= '<a class=no_underline href="'.xHtmlSetQuery($path.'&offset=0').'">1</a>&nbsp;&nbsp;';
            }

            //not more than 5 links to the right
            $min = $k + 6;
            if ($min > ceil($a / $q)) {
                $min = ceil($a / $q);
            };
            for ($i = $k + 1; $i < $min; $i++) {
                $m = $i * $q + $q;
                if ($m > $a) $m = $a;

                $out .= '<a class=no_underline href="'.xHtmlSetQuery($path.'&offset='.($i * $q)).'">'.($i + 1).'</a>&nbsp;&nbsp;';
            }

            if ($min * $q < $a) { //the last link
                if ($min * $q < $a - $q) $out .= ' ... &nbsp;&nbsp;';
                if (!($a % $q == 0))
                    $out .= '<a class=no_underline href="'.xHtmlSetQuery($path.'&offset='.($a - $a % $q)).'">'.(floor($a / $q) + 1).'</a>&nbsp;&nbsp;';
                else //$a is divided by $q
                    $out .= '<a class=no_underline href="'.xHtmlSetQuery($path.'&offset='.($a - $q)).'">'.(floor($a / $q)).'</a>&nbsp;&nbsp;';
            }

            //[next]
            if (strcmp($offset, 'show_all'))
                if ($offset < $a - $q) $out .= '<a class=no_underline href="'.xHtmlSetQuery($path.'&offset='.($offset + $q)).'">'.translate('str_next').' &gt;&gt;</a> ';

            //[show all]
            if (SHOWALL_ALLOWED_RECORDS_NUM >= $a || (!SystemSettings::is_hosted() && SystemSettings::is_backend())) {
                if (strcmp($offset, 'show_all'))
                    $out .= ' |&nbsp; <a class=no_underline href="'.xHtmlSetQuery($path.'&offset=&show_all=yes').'">'.translate('str_showall').'</a>';
                else
                    $out .= ' |'.'&nbsp;<b>'.translate('str_showall').'</b>';
            }
        }
    }
    function SuperNavigator($a, $offset, $q, $path, &$out)
    {
        //shows navigator [prev] 1 2 3 4 � [next]
        //$a - count of elements in the array, which is being navigated
        //$offset - current offset in array (showing elements [$offset ... $offset+$q])
        //$q - quantity of items per page
        //$path - link to the page (f.e: "index.php?categoryID=1&")

        if ($a > $q) //if all elements couldn't be placed on the page
        {


            $out .= '<ul id=navigation>';
            //[prev]
            if ($offset > 0) $out .= '<li><a class="page-link prev" href="'.xHtmlSetQuery($path.'&offset='.($offset - $q)).'">&lt;&lt;</a></li>&nbsp;&nbsp;';

            //digital links
            $k = $offset / $q;

            //not more than 4 links to the left
            $min = $k - 3;
            if ($min < 0) {
                $min = 0;
            } else {
                if ($min >= 1) { //link on the 1st page
                    $out .= '<li><a class=page-link href="'.xHtmlSetQuery($path.'&offset=0').'">1</a></li>&nbsp;&nbsp;';
                    if ($min != 1) {
                        $out .= '<li><span class=disabled>...</span></li>&nbsp;';
                    };
                }
            }

            for ($i = $min; $i < $k; $i++) {
                $m = $i * $q + $q;
                if ($m > $a) $m = $a;

                $out .= '<li><a class=page-link href="'.xHtmlSetQuery($path.'&offset='.($i * $q)).'">'.($i + 1).'</a></li>&nbsp;&nbsp;';
            }

            //# of current page
            if (strcmp($offset, 'show_all')) {
                $min = $offset + $q;
                if ($min > $a) $min = $a;
                $out .= '<li class=active><span class=current>'.($k + 1).'</span></li> &nbsp;&nbsp;';
            } else {
                $min = $q;
                if ($min > $a) $min = $a;
                $out .= '<li><a class=page-link href="'.xHtmlSetQuery($path.'&offset=0').'">1</a></li>&nbsp;&nbsp;';
            }

            //not more than 5 links to the right
            $min = $k + 4;
            if ($min > ceil($a / $q)) {
                $min = ceil($a / $q);
            };
            for ($i = $k + 1; $i < $min; $i++) {
                $m = $i * $q + $q;
                if ($m > $a) $m = $a;

                $out .= '<li><a class=page-link href="'.xHtmlSetQuery($path.'&offset='.($i * $q)).'">'.($i + 1).'</a></li>&nbsp;&nbsp;';
            }

            if ($min * $q < $a) { //the last link
                if ($min * $q < $a - $q) $out .= '<li class=disabled><span class=ellipse>...</span></li>&nbsp;&nbsp;';
                if (!($a % $q == 0))
                    $out .= '<li><a class=no_underline href="'.xHtmlSetQuery($path.'&offset='.($a - $a % $q)).'">'.(floor($a / $q) + 1).'</a></li>&nbsp;&nbsp;';
                else //$a is divided by $q
                    $out .= '<li><a class=page-link href="'.xHtmlSetQuery($path.'&offset='.($a - $q)).'">'.(floor($a / $q)).'</a></li>&nbsp;&nbsp;';
            }

            //[next]
            if (strcmp($offset, 'show_all'))
                if ($offset < $a - $q) $out .= '<li><a class="page-link next" href="'.xHtmlSetQuery($path.'&offset='.($offset + $q)).'">&gt;&gt;</a></li> ';

            //[show all]
            if (SHOWALL_ALLOWED_RECORDS_NUM >= $a || (!SystemSettings::is_hosted() && SystemSettings::is_backend())) {
                if (strcmp($offset, 'show_all'))
                    $out .= ' |&nbsp; <li><a class=page-link href="'.xHtmlSetQuery($path.'&offset=&show_all=yes').'">'.translate('str_showall').'</a></li>';
                else
                    $out .= ' |&nbsp; <li><a  class=page-link></a>&gt;&gt;</li>';
            }
            $out .= '</ul>';
        }
    }
    function GetNavigatorHtml($url, $countRowOnPage = CONF_PRODUCTS_PER_PAGE,
                              $callBackFunction, $callBackParam, &$tableContent,
                              &$offset, &$count)
    {
        if (isset($_GET['offset']))
            $offset = (int)$_GET['offset'];
        else
            $offset = 0;
        $offset -= $offset % $countRowOnPage;//CONF_PRODUCTS_PER_PAGE;
        if ($offset < 0) $offset = 0;
        $count = 0;

        $url = preg_replace('@^[^\?\&]+@', '', $url);

        $Register = &Register::getInstance();
        if (!$Register->is_set('show_all') || !$Register->get('show_all')) //show 'CONF_PRODUCTS_PER_PAGE' products on this page
        {
            $tableContent = $callBackFunction($callBackParam, $count,
                array(
                    'offset'         => $offset,
                    'CountRowOnPage' => $countRowOnPage
                )
            );
        } else { //show all products

            $tableContent = $callBackFunction($callBackParam, $count, null);
            $offset = 'show_all';
        }

        //ShowNavigator($count, $offset, $countRowOnPage, $url, $out);
        SuperNavigator($count, $offset, $countRowOnPage, $url, $out);

        return $out;
    }

    function moveCartFromSession2DB() //all products in shopping cart, which are in session vars, move to the database
    {
        if (isset($_SESSION["gids"]) && isset($_SESSION["log"])) {

            $customerID = regGetIdByLogin($_SESSION["log"]);
            $q = db_query("SELECT itemID FROM ".SHOPPING_CARTS_TABLE." WHERE customerID=".$customerID);
            $items = array();
            while ($item = db_fetch_row($q))
                $items[] = $item["itemID"];

            //$i=0;
            foreach ($_SESSION["gids"] as $key => $productID) {
                if ($productID == 0)
                    continue;

                // search product in current user's shopping cart content
                $itemID = null;
                for ($j = 0; $j < count($items); $j++) {
                    $q = db_query("SELECT count(*) FROM ".SHOPPING_CART_ITEMS_TABLE." WHERE productID=".$productID." AND ".
                        " itemID=".$items[$j]);
                    $count = db_fetch_row($q);
                    $count = $count[0];
                    if ($count != 0) {
                        // compare configuration
                        $configurationFromSession = $_SESSION["configurations"][$key];
                        $configurationFromDB = GetConfigurationByItemId($items[$j]);
                        if (CompareConfiguration($configurationFromSession, $configurationFromDB)) {
                            $itemID = $items[$j];
                            break;
                        }
                        $itemID = $items[$j];

                    }
                }


                if ($itemID == null) {
                    // create new item
                    db_query("INSERT INTO ".SHOPPING_CART_ITEMS_TABLE.
                        " (productID) VALUES('".$productID."')\n") or die (db_error());
                    $itemID = db_insert_id();

                    // set content item
                    foreach ($_SESSION["configurations"][$key] as $var) {
                        db_query("INSERT INTO ".
                            SHOPPING_CART_ITEMS_CONTENT_TABLE." ( itemID, variantID ) ".
                            " VALUES( '".$itemID."', '".$var."' )\n") or die (db_error());
                    }

                    // insert item into cart
                    db_query("insert ".SHOPPING_CARTS_TABLE.
                        "(customerID, itemID, Quantity)".
                        "values( '".$customerID."', '".$itemID."', '".$_SESSION["counts"][$key].
                        "' )\n") or die (db_error());
                } else {
                    db_query("update ".SHOPPING_CARTS_TABLE.
                        " set Quantity=Quantity + ".$_SESSION["counts"][$key]." ".
                        " where customerID=".$customerID." and itemID=".$itemID."\n") or die (db_error());
                }

            }

            unset($_SESSION["gids"]);
            unset($_SESSION["counts"]);
            unset($_SESSION["configurations"]);
            // session_unregister("gids"); //calling session_unregister() is required since unset() may not work on some systems
            // session_unregister("counts");
            // session_unregister("configurations");
        }
    }

    /**
     * Reprganize array from array('hello_<some>'=>123) to array(<some>=>array('hello'=>123))
     *
     * @param array $a
     * @param array|string $varnames
     * @return array
     */
    function scanArrayKeysForID($a, $varnames)
    {

        if (!is_array($varnames)) {
            $varnames = array($varnames);
        }
        $data = array();
        foreach ($varnames as $name) {
            foreach ($a as $key => $value) {

                if (preg_match("/({$name})_/", $key, $kp)) {

                    $key = preg_replace("/{$name}_/", "", $key);
                    $data[$key][$kp[1]] = $value;
                }
            }
        }

        return $data;
    }

    define('URLRENDMODE_MODIFY', 1);
    define('URLRENDMODE_RESET', 2);

    function renderGetVars($URL)
    {

        $GetVars = array();
        $parsedURL = parse_url($URL);

        if (isset($parsedURL['query']) && $parsedURL['query']) {

            $r_TokenStrs = explode('&', $parsedURL['query']);

            foreach ($r_TokenStrs as $TokenStr) {

                $r_Token = explode('=', $TokenStr, 2);
                if (isset($r_Token[1])) {
                    $GetVars[$r_Token[0]] = $r_Token[1];
                }
            }
        }

        return $GetVars;
    }

    function renderURL($_vars = '', $_request = '', $_store = false, $furl = null, $external = false)
    {

        $RenderedURL = '';


        if (!$_request) {

            $_request = $_SERVER['REQUEST_URI'];
            $GetVars = $_GET;
        } else {

            $GetVars = renderGetVars($_request);
        }

        if (!MOD_REWRITE_SUPPORT) {
            if (strpos($_request, 'index.php') === false && !$external) {
                if (strpos($_request, '?')) $_request = str_replace('?', 'index.php?', $_request);
                else $_request .= 'index.php';
            };
            if (preg_match("/^\?categoryID=(\d+)\&category_slug=[a-z0-9_]+$/i", $_vars, $matches)) {
                $_vars = '?categoryID='.$matches[1];
            };

            if (preg_match("/^\?ukey=product\&productID=(\d+)\&product_slug=[a-z0-9_\-]+$/i", $_vars, $matches)) {
                $_vars = '?productID='.$matches[1];
            };
        };

        $anchor = preg_match('@(#[^#]*)$@', $_request, $sp) ? $sp[1] : '';
        $anchor = preg_match('@(#[^#]*)$@', $_vars, $sp) ? $sp[1] : $anchor;

        /**
         * Set render mode
         */
        if (strpos($_vars, '?') !== false) {

            $Mode = URLRENDMODE_RESET;
            $_vars = substr($_vars, 1, strlen($_vars) - 1).'&lang_iso2=';
        } else {

            $Mode = URLRENDMODE_MODIFY;
        }

        /**
         * trim first ampersand
         */
        if (strpos($_vars, '&') === 0) $_vars = substr($_vars, 1, strlen($_vars) - 1);

        /**
         * Render new get-tokens
         */
        $ReceivedTokens = array();
        $r_TokenStrs = explode('&', $_vars);
        $widgets_token = false;
        foreach ($r_TokenStrs as $TokenStr) {

            $r_Token = explode('=', $TokenStr, 2);
            if ($r_Token[0] == 'widgets') $widgets_token = true;
            if (isset($r_Token[1]) && strlen($r_Token[1])) {

                $ReceivedTokens[$r_Token[0]] = $r_Token[1];
                if ($Mode == URLRENDMODE_MODIFY) {

                    $GetVars[$r_Token[0]] = $r_Token[1];
                }
            } else {

                switch ($Mode) {
                    case URLRENDMODE_MODIFY:

                        if (isset($GetVars[$r_Token[0]]))
                            unset($GetVars[$r_Token[0]]);
                        break;
                    case URLRENDMODE_RESET:
                        if (isset($GetVars[$r_Token[0]]) && $r_Token[0] != 'product_slug' && $r_Token[0] != 'category_slug')
                            $ReceivedTokens[$r_Token[0]] = $GetVars[$r_Token[0]];
                        break;
                }
            }
        }
        /**
         * Render URL
         */
        $newGetVars = array();
        switch ($Mode) {
            case URLRENDMODE_MODIFY:
                $newGetVars = &$GetVars;
                break;
            case URLRENDMODE_RESET:
                $newGetVars = &$ReceivedTokens;
                break;
        }
        if (!$widgets_token && count($newGetVars)) {
            $Register = &Register::getInstance();
            /*@var $Register Register*/
            if ($Register->get('widgets')) $newGetVars['widgets'] = 1;
        }

        if ($_store) {

            $_GET = $newGetVars;
        }
        if (class_exists('fURL') && $furl !== false)
            fURL::convertGetToPath($_request, $newGetVars);

        foreach ($newGetVars as $TokenName => $TokenValue) {

            $newGetVars[$TokenName] = $TokenName.'='.$TokenValue;
        }
        $RenderedURL = implode('&', $newGetVars);
        if (strpos($_request, '?') !== false) {

            $RenderedURL = preg_replace('/\?.*$/', '?'.$RenderedURL, $_request);
        } else {

            $RenderedURL = $_request.'?'.$RenderedURL;
        }

        $RenderedURL = preg_replace('@[\?\&]{1,2}$@', '', $RenderedURL);

        if (strlen($anchor) > 1) $RenderedURL = preg_replace('@#[^#]*$@', '', $RenderedURL).$anchor;
        /**
         * Strore URL
         */
        if ($_store) {

//		$_SERVER['REQUEST_URI'] = $RenderedURL;
        }

        return $RenderedURL;
    }

    function set_query($_vars = '', $_request = '', $_store = false, $external = false)
    {

        return renderURL($_vars, $_request, $_store, null, $external);
    }

    function xHtmlSetQuery($_vars = '', $_request = '', $_store = false)
    {

        return xHtmlSpecialChars(renderURL($_vars, $_request, $_store));
    }

    function getListerRange($_pagenumber, $_totalpages, $_lister_num = 20)
    {

        if ($_pagenumber <= 0) return array('start' => 1, 'end' => 1);
        $lister_start = $_pagenumber - floor($_lister_num / 2);
        $lister_start = ($lister_start + $_lister_num <= $_totalpages ? $lister_start : $_totalpages - $_lister_num + 1);
        $lister_start = ($lister_start > 0 ? $lister_start : 1);
        $lister_end = $lister_start + $_lister_num - 1;
        $lister_end = ($lister_end <= $_totalpages ? $lister_end : $_totalpages);

        return array('start' => $lister_start, 'end' => $lister_end);
    }

    function getLister($_pagenumber, $_totalpages, $_lister_num = 20)
    {

        if ($_pagenumber <= 0) return array(
            'CurrentPage' => 1,
            'LastPage'    => 1,
            'Range'       => array(1),
        );
        $Lister = array(
            'CurrentPage' => $_pagenumber,
            'LastPage'    => $_totalpages,
            'Range'       => array(),
        );
        $lister_start = $_pagenumber - floor($_lister_num / 2);
        $lister_start = ($lister_start + $_lister_num <= $_totalpages ? $lister_start : $_totalpages - $_lister_num + 1);
        $lister_start = ($lister_start > 0 ? $lister_start : 1);
        $lister_end = $lister_start + $_lister_num - 1;
        $lister_end = ($lister_end <= $_totalpages ? $lister_end : $_totalpages);
        for (; $lister_start <= $lister_end; $lister_start++)
            $Lister['Range'][] = $lister_start;

        return $Lister;
    }

    /**
     *Strip slashes if magic_quotes_gpc is On
     *
     * @param mixed
     * return mixed
     */
    function xStripSlashesGPC($_data)
    {

        if (!get_magic_quotes_gpc()) return $_data;
        if (is_array($_data)) {

            foreach ($_data as $_ind => $_val) {

                $_data[$_ind] = xStripSlashesGPC($_val);
            }

            return $_data;
        }

        return stripslashes($_data);
    }

    /**
     * mail txt message from template
     * @param string email
     * @param string email subject
     * @param string template name
     */
    function xMailTxt($_Email, $_Subject, $_TemplateName, $_AssignArray = array(), $html = false)
    {

        if (!$_Email) return 0;
        $mailSmarty = new View();
        foreach ($_AssignArray as $_var => $_val) {

            $mailSmarty->assign($_var, $_val);
        }
        $_t = $mailSmarty->fetch('email/'.$_TemplateName);
        ss_mail($_Email, $_Subject, $_t, true);
    }

    /**
     * replace newline symbols to &lt;br /&gt;
     * @param mixed data for action
     * @param array which elements test
     * @return mixed
     */
    function xNl2Br($_Data, $_Key = array())
    {


        if (!is_array($_Data)) {

            return nl2br($_Data);
        }

        if (!is_array($_Key)) $_Key = array($_Key);
        foreach ($_Data as $__Key => $__Data) {

            if (count($_Key) && !is_array($__Data)) {

                if (in_array($__Key, $_Key)) {

                    $_Data[$__Key] = xNl2Br($__Data, $_Key);
                }
            } else $_Data[$__Key] = xNl2Br($__Data, $_Key);

        }

        return $_Data;
    }

    function xStrReplace($_Search, $_Replace, $_Data, $_Key = array())
    {

        if (!is_array($_Data)) {

            return str_replace($_Search, $_Replace, $_Data);
        }

        if (!is_array($_Key)) $_Key = array($_Key);
        foreach ($_Data as $__Key => $__Data) {

            if (count($_Key) && !is_array($__Data)) {

                if (in_array($__Key, $_Key)) {

                    $_Data[$__Key] = xStrReplace($_Search, $_Replace, $__Data, $_Key);
                }
            } else $_Data[$__Key] = xStrReplace($_Search, $_Replace, $__Data, $_Key);

        }

        return $_Data;
    }

    function xHtmlSpecialChars($_Data, $_Params = array(), $_Key = array())
    {


        if (!is_array($_Data)) {

            return htmlspecialchars($_Data, ENT_QUOTES);
        }

        if (!is_array($_Key)) $_Key = array($_Key);
        foreach ($_Data as $__Key => $__Data) {

            if (count($_Key) && !is_array($__Data)) {

                if (in_array($__Key, $_Key)) {

                    $_Data[$__Key] = xHtmlSpecialChars($__Data, $_Params, $_Key);
                }
            } else $_Data[$__Key] = xHtmlSpecialChars($__Data, $_Params, $_Key);

        }

        return $_Data;
    }

    function xEscapeSQLstring($_Data, $_Params = array(), $_Key = array())
    {

        if (!is_array($_Data)) {

            return mysql_real_escape_string($_Data);
        }

        if (!is_array($_Key)) $_Key = array($_Key);
        foreach ($_Data as $__Key => $__Data) {

            if (count($_Key) && !is_array($__Data)) {

                if (in_array($__Key, $_Key)) {

                    $_Data[$__Key] = xEscapeSQLstring($__Data, $_Params, $_Key);
                }
            } else $_Data[$__Key] = xEscapeSQLstring($__Data, $_Params, $_Key);

        }

        return $_Data;
    }

    function xSaveData($_ID, $_Data, $_TimeControl = 0)
    {

        if (!session_is_registered('_xSAVE_DATA')) {

            session_register('_xSAVE_DATA');
            $_SESSION['_xSAVE_DATA'] = array();
        }

        if (intval($_TimeControl)) {

            $_SESSION['_xSAVE_DATA'][$_ID] = array(
                $_ID.'_DATA'      => $_Data,
                $_ID.'_TIME_CTRL' => array(
                    'timetag'   => time(),
                    'timelimit' => $_TimeControl,
                ),
            );
        } else {
            $_SESSION['_xSAVE_DATA'][$_ID] = $_Data;
        }
    }

    function xPopData($_ID)
    {

        if (!isset($_SESSION['_xSAVE_DATA'][$_ID])) {
            return null;
        }

        if (is_array($_SESSION['_xSAVE_DATA'][$_ID])) {

            if (isset($_SESSION['_xSAVE_DATA'][$_ID][$_ID.'_TIME_CTRL'])) {

                if (($_SESSION['_xSAVE_DATA'][$_ID][$_ID.'_TIME_CTRL']['timetag'] + $_SESSION['_xSAVE_DATA'][$_ID][$_ID.'_TIME_CTRL']['timelimit']) < time()) {
                    return null;
                } else {

                    $Return = $_SESSION['_xSAVE_DATA'][$_ID][$_ID.'_DATA'];
                    unset($_SESSION['_xSAVE_DATA'][$_ID]);

                    return $Return;
                }
            }
        }

        $Return = $_SESSION['_xSAVE_DATA'][$_ID];
        unset($_SESSION['_xSAVE_DATA'][$_ID]);

        return $Return;
    }

    function xDataExists($_ID)
    {

        if (!isset($_SESSION['_xSAVE_DATA'][$_ID])) return 0;

        if (is_array($_SESSION['_xSAVE_DATA'][$_ID])) {

            if (isset($_SESSION['_xSAVE_DATA'][$_ID][$_ID.'_TIME_CTRL'])) {

                if (($_SESSION['_xSAVE_DATA'][$_ID][$_ID.'_TIME_CTRL']['timetag'] + $_SESSION['_xSAVE_DATA'][$_ID][$_ID.'_TIME_CTRL']['timelimit']) >= time()) {
                    return 1;
                } else {
                    return 0;
                }
            } else {
                return 1;
            }
        } else {
            return 1;
        }
    }

    function xGetData($_ID)
    {

        if (!isset($_SESSION['_xSAVE_DATA'][$_ID])) {
            return null;
        }

        if (is_array($_SESSION['_xSAVE_DATA'][$_ID])) {

            if (isset($_SESSION['_xSAVE_DATA'][$_ID][$_ID.'_TIME_CTRL'])) {

                if (($_SESSION['_xSAVE_DATA'][$_ID][$_ID.'_TIME_CTRL']['timetag'] + $_SESSION['_xSAVE_DATA'][$_ID][$_ID.'_TIME_CTRL']['timelimit']) < time()) {
                    return null;
                } else {

                    $Return = $_SESSION['_xSAVE_DATA'][$_ID][$_ID.'_DATA'];

                    return $Return;
                }
            }
        }

        $Return = $_SESSION['_xSAVE_DATA'][$_ID];

        return $Return;
    }

    function xCall($func_name, $data, $params = null)
    {

        if (!is_array($data)) return call_user_func_array($func_name, array($data, $params));

        foreach ($data as $k => $v) $data[$k] = xCall($func_name, $v, $params);

        return $data;
    }

    function isWindows()
    {
        if (defined('IS_WINDOWS')) {
            return constant('IS_WINDOWS');
        }
        if (isset($_SERVER["WINDIR"]) || isset($_SERVER["windir"])) return true;
        if (isset($_SERVER['SERVER_SOFTWARE']) && (strpos(strtolower($_SERVER['SERVER_SOFTWARE']), 'microsoft') !== false)) return true;

        return false;
    }

    function generateRndCode($_RndLength, $_RndCodes = 'qwertyuiopasdfghjklzxcvbnm0123456789')
    {

        $l_name = '';
        $top = strlen($_RndCodes) - 1;
        srand((double)microtime() * 1000000);
        for ($j = 0; $j < $_RndLength; $j++) $l_name .= $_RndCodes{rand(0, $top)};

        return $l_name;
    }

    function get_NOTempty_elements_count($arr) //required for excel import
//gets how many NOT NULL (not empty strings) elements are there in the $arr
    {
        $n = 0;
        for ($i = 0; $i < count($arr); $i++)
            if (trim($arr[$i]) != "") $n++;

        return $n;
    } //get_NOTempty_elements_count

    function mark_as_selected($a, $b) //required for excel import
//returns " selected" if $a == $b
    {
        return !strcmp($a, $b) ? " selected" : "";

    } //mark_as_selected

    /**
     * Authorized access check
     *
     */
// function checkLogin(){

    // //authorized access check
    // if (isset($_SESSION["log"])){ //look for user in the database

    // $sql = '
    // SELECT cust_password FROM ?#CUSTOMERS_TABLE WHERE Login=?
    // ';

    // $row = db_phquery_fetch(DBRFETCH_ROW, $sql, $_SESSION["log"]); //found customer - check password

    // if (!$row || !isset($_SESSION["pass"]) || strcmp($row[0], $_SESSION["pass"] )) //unauthorized access
    // {
    // unset($_SESSION["log"]);
    // unset($_SESSION["pass"]);
    // session_unregister("log"); //calling session_unregister() is required since unset() may not work on some systems
    // session_unregister("pass");
    // }

    // }
// }
    function GetCustomerByCustomerLogin($customerLogin)
    {

        $query = "SELECT * FROM SC_customers WHERE Login='".$customerLogin."'";
        $Customer = mysql_fetch_object(mysql_query($query));

        return $Customer;
    }

    function getUSDvalue()
    {

        $query = "SELECT * FROM SC_currency_types WHERE CID=10";
        $currency = mysql_fetch_object(mysql_query($query));

        $usd = $currency->currency_value;
        $usd = 1 / $usd;
        $usd = number_format($usd, 2);

        $_SESSION['usd'] = $usd;
    }

    function checkLogin()
    {

        if (isset($_SESSION['log'])) {

            if ($numargs = func_num_args()) {
                $_SESSION['enter'] = func_get_arg(0);
            }
            
            $Customer = GetCustomerByCustomerLogin($_SESSION['log']);

            if (!$Customer->unlimited_order) {
                $cust_may_order = (strtotime($Customer->may_order_until) > $_SERVER['REQUEST_TIME']) ? 1 : 0;
            } else {
                $cust_may_order = 1;
            }
            if ($Customer->token && $Customer->token !== xEscapeSQLstring($_SESSION['enter'])) {
                unset($_SESSION['log'], $_SESSION['pass'], $_SESSION['enter']);
                db_query("UPDATE SC_customers SET token = '', logged = TIMESTAMP(0) WHERE Login='$Customer->Login'");
            }
            $_SESSION['cs_id'] = $Customer->customerID;
            $_SESSION['cs_first_name'] = $Customer->first_name;
            $_SESSION['cs_last_name'] = $Customer->last_name;
            $_SESSION['cs_may_order_until'] = date('H:i d/m/Y', strtotime($Customer->may_order_until));
            $_SESSION['cs_skidka'] = $Customer->skidka;
            $_SESSION['cs_special_price'] = $Customer->is_special_price;
            $_SESSION['cs_margin'] = $Customer->ignore_skidka;
            $_SESSION['cs_may_order'] = $cust_may_order;
            $_SESSION['cs_unlimited'] = $Customer->unlimited_order;
            $_SESSION['cs_vip'] = $Customer->vip;
            //            $_SESSION['cs_bonus'] = $Customer->1C;

            getUSDvalue();

            if (!$Customer->token || !$Customer->cust_password || !isset($_SESSION['pass']) || strcmp($Customer->cust_password, $_SESSION['pass'])) //unauthorized access
            {
                unset($_SESSION['log'], $_SESSION['pass'], $_SESSION['enter']);
                //unset($_SESSION['pass']);
                // session_unregister("log"); //calling session_unregister() is required since unset() may not work on some systems
                // session_unregister("pass");
            }

        }
    }

    function isHTTPS()
    {

        return isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) !== 'off');
    }

    function escapeCRLF($str)
    {

        return str_replace(array("\r\n", '%0d%0a', "\n", '%0a', "\r", '%0d'), '', $str);
    }

    /**
     * Cut string
     *
     * @param string $String - source
     * @param int $Length - target length
     * @param string $EndString
     */
    function str_cut($String, $Length, $EndString = '...')
    {

        $origlength = strlen($String);
        if ($origlength <= $Length) return $String;
        $String = substr($String, 0, $Length);
        $lastspace_i = strrpos($String, ' ');
        if ($lastspace_i !== false)
            $String = substr($String, 0, $lastspace_i);

        return $String.($origlength > $Length ? $EndString : '');
    }

    function getUniqueWDataID($Length = 4)
    {

        $ID = '';
        do {

            $ID = rand_name($Length);
        } while (issetWData($ID));

        return $ID;
    }

    function rand_name($_length = 4)
    {

        $rand_simb = "qwertyuiopasdfghjklzxcvbnm0123456789";
        $l_name = '';
        $top = strlen($rand_simb) - 1;
        srand((double)microtime() * 1000000);
        for ($j = 0; $j < $_length; $j++) $l_name .= $rand_simb{rand(0, $top)};

        return $l_name;
    }

    function getUnicFile($_length = 4, $_tpl = "%s", $_path = "./")
    {

        $fname = $_tpl;
        $limit = 0;
        do {

            $fname = sprintf($_tpl, rand_name($_length));
        } while (file_exists($_path.$fname) && 300 < $limit++);

        return $fname;
    }

    function issetWData($VarName)
    {

        return isset($_SESSION['xPOST'][$VarName]);
    }

    function storeWData($_VarName, $_VarData)
    {

        storePOST($_VarName, $_VarData);
    }

    function loadWData($_VarName)
    {

        return loadPOST($_VarName);
    }

    function unsetWData($VarName)
    {

        unset($_SESSION['xPOST'][$VarName]);
    }

    function popWData($VarName)
    {

        $WData = loadWData($VarName);
        unsetWData($VarName);

        return $WData;
    }

    function storePOST($_VarName, $_VarData)
    {

        if (!session_is_registered('xPOST'))
            session_register('xPOST');
        $_SESSION['xPOST'][$_VarName] = $_VarData;
    }

    function loadPOST($_VarName)
    {

        if (!isset($_SESSION['xPOST'][$_VarName])) return null;

        return $_SESSION['xPOST'][$_VarName];
    }

    function unsetPOST()
    {

        if (isset($_SESSION['xPOST']))
            unset($_SESSION['xPOST']);
    }

    /**
     * is used all around the software
     * $headers = array('From'=>'from_value','FromName'=>'FromName_value','Sender'=>'Sender_value')
     *
     * @param string $email
     * @param string $subject
     * @param string $text
     * @param boolean $is_html
     * @param array $headers
     * @return boolean
     */
    function ss_mail($email, $subject, $text, $is_html = true, $headers = array())
    {

        //$mailer = new PHPMailer();
        $mailer = new SSMailer();
        if (isset($headers['From']))
            $mailer->From = $headers['From'];
        else
            $mailer->From = CONF_GENERAL_EMAIL;
        if (isset($headers['Sender']))
            $mailer->Sender = $headers['Sender'];
        else
            $mailer->Sender = CONF_GENERAL_EMAIL;
        if (isset($headers['FromName']))
            $mailer->FromName = $headers['FromName'];
        else
            $mailer->FromName = CONF_SHOP_NAME;
        $emails = explode(',', $email);
        foreach ($emails as $email) {
            $mailer->AddAddress($email);
        }
        $mailer->Subject = $subject;
        $mailer->Body = $text;

        $mailer->CharSet = 'utf-8';
        $mailer->IsHTML($is_html === true || $is_html === 2);
        if ($is_html === true) {

            $mailer->AltBody = str_replace("\n", "\r\n", str_replace("\r", '', strip_tags($text)));
        }

        return $mailer->Send();
    }

    /**
     * @param string $string
     * @param bool $in_false_source - if true and translation not found, return original constant
     * @return unknown
     */
    function translate($string, $in_false_constant = true)
    {

        /**
         * Old language localization
         */
        //if(defined($string))return constant($string);

        $Register = &Register::getInstance();
        $currlang_locals = &$Register->get('CURRLANG_LOCALS');
        if (isset($currlang_locals[$string]) && $currlang_locals[$string]) return $currlang_locals[$string];
        $deflang_locals = &$Register->get('DEFLANG_LOCALS');
        if (isset($deflang_locals[$string]) && $deflang_locals[$string]) return $deflang_locals[$string];


        //DEBUG:
        if (false && ($fp = fopen(DIR_TEMP.'/missed_locals.log', 'a'))) {
            $backtrace = debug_backtrace();
            $backtrace = $backtrace[0];
            $file = str_replace(WBS_DIR, '', str_replace('\\', '/', $backtrace['file']));
            fwrite($fp, "{$string}\t{$file}\t{$backtrace['line']}\n");
            fclose($fp);
        }

        return $in_false_constant ? $string : '';
    }

    /**
     * Check safe mode and redirect to some page with message about safe mode
     *
     * @param bool $check - check safe mode
     * @param string $query - query for http redirect
     * @return false|null
     */
    function safeMode($check, $query = '')
    {

        if (!$check || !CONF_BACKEND_SAFEMODE) return false;

        Message::raiseMessageRedirectSQ(MSG_ERROR, $query, translate("msg_safemode_warning"));
    }

    function pear_dump($data, $comment = '')
    {

        ob_start();
        print '<pre>';
        print_r($data);
        print '</pre>';
        PEAR::raiseError($comment.' - '.ob_get_contents());
        ob_end_clean();
    }

    function is_image($file)
    {

        if (!preg_match('/\.(jpg|jpeg|jpe|gif|pcx|bmp|png)$/i', $file, $r)) return false;

        return $r[1];
    }

    function checkPath($_path, $dont_check_path = '')
    {

        if (file_exists($_path)) return true;
        $dont_check_path = realpath($dont_check_path);

        $_path = str_replace('\\', '/', $_path);
        $explFolders = explode('/', $_path);
        $fldNum = count($explFolders);
        for ($wer = 0; $wer < $fldNum; $wer++) {

            $tPath = '';
            for ($qwe = 0; $qwe <= $wer; $qwe++) $tPath .= $explFolders[$qwe].'/';

            if ($dont_check_path && strpos($dont_check_path, $tPath) === 0) continue;
            if (!file_exists($tPath) && $tPath) {
                mkdir($tPath);
            }
        }

        return true;
    }

    function copy_dir($src, $dest)
    {

        static $max;
        if (!isset($max)) $max = 0;
        $max++;
        if ($max > 25) return false;

        checkPath($dest);

        $handle = opendir($src);
        $C = 0;
        while (false !== ($file = readdir($handle)) && $C++ < 100) {

            if ($file == '.' || $file == '..') continue;

            if (is_dir($src.'/'.$file)) {
                copy_dir($src.'/'.$file, $dest.'/'.$file);
            } else {
                copy($src.'/'.$file, $dest.'/'.$file);
            }
        }

        if ($C >= 100) {
            print "$src, $dest<br>";
        }
        @closedir($handle);
    }

    function delete_file($path)
    {

        if (is_file($path)) {
            unlink($path);
        } else {

            if (!($handle = @opendir($path)))
                return;

            while (false !== ($file = readdir($handle))) {

                if ($file == "." || $file == "..") continue;

                if (is_file($path.'/'.$file)) {

                    unlink($path.'/'.$file);
                } else {

                    delete_file($path.'/'.$file);
                }
            }

            @closedir($handle);

            rmdir($path);
        }
    }

    function getMonthDays($time)
    {


        $time = strtotime(date('Y-m-15', $time));

        return date('d', strtotime('-1 day', strtotime(date('Y-m-01', strtotime('+1 month', $time)))));
    }

    function getWeekdayName($n)
    {

        global $rWeekDays;

        return isset($rWeekDays[$n]) ? $rWeekDays[$n] : '';
    }

    /**
     * Transforms cyrillic symbols that string contains into latin with regard for transliteration
     *
     * @param string $str
     * @return string
     */
    function translit($str)
    {
        //if($UTF8)
        //$str = iconv("UTF-8", "WINDOWS-1251",$str);
        $result = "";

        $compliances = array("а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ё" => "yo", "ж" => "zh", "з" => "z", "и" => "i", "й" => "j", "к" => "k",
                             "л" => "l", "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h", "ц" => "c", "ч" => "ch",
                             "ш" => "sh", "щ" => "sh", "ы" => "y", "ь" => "'", "ю" => "ju", "я" => "ja", "э" => "e",

                             "А" => "A", "Б" => "B", "В" => "V", "Г" => "G", "Д" => "D", "Е" => "E", "Ё" => "Yo", "Ж" => "Zh", "З" => "Z", "И" => "I", "Й" => "J", "К" => "K",
                             "Л" => "L", "М" => "M", "Н" => "N", "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T", "У" => "U", "Ф" => "F", "Х" => "H", "Ц" => "C", "Ч" => "Ch",
                             "Ш" => "Sh", "Щ" => "Sh", "Ы" => "Y", "Ь" => "'", "Ю" => "Ju", "Я" => "Ja", "Э" => "E");
//Use ASCII Page codes
        /*$compliances = array(184=>'yo',168=>'Yo',
                            192=>"A","B","V","G","D","E","Zh","Z","I","J","K",
                                "L","M","N","O","P","R","S","T","U","F","H","C","Ch",
                                "Sh","Sh","","Y","'","E","Ju","Ja",
                                "a","b","v","g","d","e","zh","z","i","j","k",
                                "l","m","n","o","p","r","s","t","u","f","h","c","ch",
                                "sh","sh",'',"y","'","e","ju","ja");*/


        $strlen = mb_strlen($str, 'UTF-8');
        for ($i = 0; $i < $strlen; $i++) {
            $char = mb_substr($str, $i, 1, 'UTF-8');
            //$symbol = ord($char);
            //$symbol_ = (int)$char;
            $result .= isset($compliances[$char]) ? $compliances[$char] : $char;

        }

        return $result;
    }

    function utf8_bad_replace($str, $replace = '?')
    {
        $UTF8_BAD =
            '([\x00-\x7F]'.                          # ASCII (including control chars)
            '|[\xC2-\xDF][\x80-\xBF]'.               # non-overlong 2-byte
            '|\xE0[\xA0-\xBF][\x80-\xBF]'.           # excluding overlongs
            '|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}'.    # straight 3-byte
            '|\xED[\x80-\x9F][\x80-\xBF]'.           # excluding surrogates
            '|\xF0[\x90-\xBF][\x80-\xBF]{2}'.        # planes 1-3
            '|[\xF1-\xF3][\x80-\xBF]{3}'.            # planes 4-15
            '|\xF4[\x80-\x8F][\x80-\xBF]{2}'.        # plane 16
            '|(.{1}))';                              # invalid byte
        ob_start();
        while (preg_match('/'.$UTF8_BAD.'/S', $str, $matches)) {
            if (!isset($matches[2])) {
                echo $matches[0];
            } else {
                echo $replace;
            }
            $str = substr($str, strlen($matches[0]));
        }
        $result = ob_get_contents();
        ob_end_clean();

        return $result;
    }

    function make_slug($str)
    {

        $str = strtolower(translit($str));
        $str = preg_replace('/ /ui', '-', $str);
        $str = preg_replace('/[^a-z0-9\-\_]/ui', '', $str);
        $str = preg_replace('/\-+/u', '-', $str);
        $str = preg_replace('/\_+/u', '_', $str);

        return $str == '-' ? '' : $str;
    }

    function encodeArray($src_array, $excludes = null)
    {
        if (is_null($excludes))
            $excludes = array();

        $result = array();

        foreach ($src_array as $key => $value)
            if (!in_array($key, $excludes))
                $result[$key] = base64_encode($value);
            else
                $result[$key] = $value;

        return $result;
    }

    function decodeArray($src_array, $excludes = null)
    {

        if (is_null($excludes))
            $excludes = array();

        $result = array();

        foreach ($src_array as $key => $value)
            if (!in_array($key, $excludes))
                $result[$key] = base64_decode($value);
            else
                $result[$key] = $value;

        return $result;
    }


    function valid_email($email)
    {

        // First, we check that there's one @ symbol, and that the lengths are right
        if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
            // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
            return false;
        }
        // Split it into sections to make life easier
        $email_array = explode("@", $email);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if (!ereg("^(([A-Za-z0-9!#$%&#038;'*+/=?^_`{|}~-][A-Za-z0-9!#$%&#038;'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
                return false;
            }
        }
        if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return false; // Not enough parts to domain
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param mixed $space - file size
     * @param string $from_units - file size units (B,KB,MB,GB)
     * @param string $to_units - display string units
     * @return string
     */
    function getDisplayFileSize($space, $from_units, $to_units = null)
    {

        $allowed_units = array('B', 'KB', 'MB', 'GB');
        $units_view = array('B' => 'bytes', 'KB' => 'Kb', 'MB' => 'Mb', 'GB' => 'Gb');
        if (!in_array($from_units, $allowed_units)) return $space;
        if (!is_null($to_units) && !in_array($to_units, $allowed_units)) return $space;

        if (is_null($to_units)) {

            $begin_conversion = false;
            $last_units = $from_units;
            $last_space = $space;
            foreach ($allowed_units as $curr_units) {

                if ($begin_conversion) {

                    $tspace = $last_space / 1000;
                    if ($tspace < 1) {
                        break;
                    } else {
                        $last_units = $curr_units;
                        $last_space = ceil($tspace * 100) / 100;
                    }
                } elseif ($curr_units == $from_units) {

                    $begin_conversion = true;
                }
            }

            return $last_space.' '.$units_view[$last_units];
        }
    }

    function detectPDA()
    {
        $container = $_SERVER['HTTP_USER_AGENT'];
        $useragents = array(
            'iPhone', 'iPod', "Elaine/3.0", "Palm", "EudoraWeb", "Blazer", "AvantGo", "Windows CE", "Cellphone", "Small", "MMEF20", "Danger", "hiptop"
        , "Proxinet", "ProxiNet", "Newt", "PalmOS", "NetFront", "SHARP-TQ-GX10", "SonyEricsson", "SymbianOS", "UP.Browser"
        , "UP.Link", "TS21i-10", "BlackBerry", "MOT-V", 'portalmmm', 'Nokia', 'DoCoMo', 'Opera Mini'
        , "Palm", "Handspring", "Nokia", "Kyocera", "Samsung", "Motorola", "Mot", "Smartphone", "Blackberry"
        , "WAP", "PlayStation Portable", "LG", "MMP", "OPWV", "Symbian", "EPOC");
        $pda = false;
        foreach ($useragents as $useragent) {
            if (!eregi($useragent, $container)) continue;
            $pda = true;
            break;
        }

        return false;
    }

    function detectMSIE()
    {
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $msie = 'MSIE';
        $match = stripos($useragent, $msie);
        if ($match === false) {
            return true;
        }
        return false;
    }

    function make_clean_slug($string, $prefix, $table, $slug_field, $id_field = '', $id = null)
    {
        $slug = make_slug($string);
        $used_string = array();
        $Register = &Register::getInstance();
        $DBHandler = &$Register->get(VAR_DBHANDLER);
        /* @var DBHandler DataBase */
        $query = "SELECT DISTINCT`{$slug_field}` as slug FROM {$table} WHERE `{$slug_field}` LIKE ?".($id ? " AND NOT(`{$id_field}` LIKE ?)" : '');
        $DBHandler->ph_query($query, $prefix.$slug.'%', $id);
        while ($row = $DBHandler->fetch_assoc()) {
            $used_slug[] = $row['slug'];
        }
        $DBHandler->freeResult();

        $max_i = 100;
        $_slug = $slug;
        while (($max_i--) > 0 && in_array($prefix.$_slug, $used_slug)) {
            $_slug = $slug.'_'.rand_name(3);
        }

        return $_slug;
    }

    function initCurlProxySettings(&$ch)
    {
        $options = getProxySettings();

        if (isset($options['host']) && strlen($options['host'])) {
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);
            curl_setopt($ch, CURLOPT_PROXY, sprintf("%s%s", $options['host'], (isset($options['port']) && $options['port']) ? ':'.$options['port'] : ''));
            //  print(sprintf("%s%s",$options['host'],(isset($options['port'])&&($options['port'])) ? ':'.$options['port'] : '').'<br><hr>');

            if (isset($options['user']) && strlen($options['user'])) {
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, sprintf("%s:%s", $options['user'], $options['password']));
                //	print(sprintf("%s:%s",$options['user'],$options['password']).'<br><hr>');
            }
        }
    }

    function getProxySettings()
    {
        $Register = &Register::getInstance();
        /* @var $Register Register */
        $options = $Register->get('PROXY');
        if (is_null($options)) {
            $options = SystemSettings::get(array(
                'host' => 'PROXY_HOST', 'port' => 'PROXY_PORT',
                'user' => 'PROXY_USER', 'password' => 'PROXY_PASS'));
            $Register->set('PROXY', $options);
        }

        return $options;
    }

    function error404page($debug = null)
    {
        global $error404;
        $register = Register::getInstance();
        $smarty = $register->get(VAR_SMARTY);
        $error404 = true;
        header("HTTP/1.1 404 Not Found;");
        header("Status: 404 Not Found;");
        $smarty->assign('link404', $_SERVER['REDIRECT_URL']);
        $smarty->assign('page_not_found404', true);
    }

// Расчет цены
    /*
        $is_special_price - Признак расчета со спецценой
        $margin - Признак наценки
        $SpecialPrice - Спеццена
        $Price - Цена
        $customer_skidka - Пользовательская скидка
        $product_skidka - Значение из столбца "Скидка" файла product.xls
    */
    function ZCalcPrice($Price, $SpecialPrice, $product_skidka)
    {

        $is_special_price = $_SESSION['cs_special_price'];
        $margin = $_SESSION['cs_margin'];
        $customer_skidka = $_SESSION['cs_skidka'];
        if (!$is_special_price && !$customer_skidka && !$margin) return $Price;
        if ($is_special_price && !$customer_skidka && !$margin) return $SpecialPrice;
        if (!$is_special_price && $customer_skidka && !$margin) {
            if ($product_skidka > $customer_skidka) $outPrice = $Price - ($Price * $customer_skidka / 100);
            else $outPrice = $Price - ($Price * $product_skidka / 100);

            return $outPrice;
        }
        if ($is_special_price && $customer_skidka && !$margin) {
            if ($product_skidka > $customer_skidka) $outPrice = $SpecialPrice - ($SpecialPrice * $customer_skidka / 100);
            else $outPrice = $SpecialPrice - ($SpecialPrice * $product_skidka / 100);

            return $outPrice;
        }
        if ($margin) $outPrice = $Price + ($Price * $customer_skidka / 100);

        return $outPrice;
    }

    function showMeDump($see, $get_sales)
    {
        echo '<pre><b>'.strtoupper($get_sales).'=></b>';
        print_r($see);
        echo '</pre><br/>';

        return;
    }

    function get_shop_couts($CustomerID)
    {
        $query = "
            SELECT SC_products.productID, SC_shopping_carts.Quantity
            FROM SC_shopping_carts
            LEFT JOIN SC_shopping_cart_items ON SC_shopping_carts.itemID = SC_shopping_cart_items.itemID
            LEFT JOIN SC_products ON SC_shopping_cart_items.productID = SC_products.productID
            WHERE SC_shopping_carts.customerID  = $CustomerID";
        $res = mysql_query($query) or die(mysql_error()."<br>$query");
        $in_cart = array();
        while ($row = mysql_fetch_object($res)) {
            $in_cart[$row->productID] = (int)($row->Quantity);
        }

        return $in_cart;
    }

    function pagination($total, $per_page, $num_links, $start_row, $url = '', $sort, $direction)
    {
        $output = '';
        //Получаем общее число страниц
        $num_pages = ceil($total / $per_page);
        // Если страница одна, то ничего не выводим
        if ($num_pages == 1)
            return '';
        $cur_page = $start_row;
        //Если количество элементов на страницы больше чем общее число элементов
        // то текущая страница будет равна последней
        if ($cur_page > $total) {
            $cur_page = ($num_pages - 1) * $per_page;
        }
        //Получаем номер текущей страницы
        $cur_page = floor(($cur_page / $per_page) + 1);
        $start = (($cur_page - $num_links) > 0) ? $cur_page - $num_links : 0;
        if ($cur_page != 1) {
            $i = $start_row - $per_page;
            if ($i <= 0)
                $i = 0;
            $z_sort = isset($_REQUEST['sort']) ? '&amp;sort='.$sort.'&amp;direction='.$direction : "";
            $output .= '<i>←</i><a href="'.$url.'&amp;p='.$i.$z_sort.'">Предыдущая</a>';
        } else {
            $output .= '<span><i>←</i>Предыдущая</span>';
        }
        $output .= '<span class=divider>&nbsp;|&nbsp;</span>';
        if ($cur_page < $num_pages) {
            $z_sort = isset($_REQUEST['sort']) ? '&amp;sort='.$sort.'&amp;direction='.$direction : "";
            $output .= '<a href="'.$url.'&amp;p='.($cur_page * $per_page).$z_sort.'">Следующая</a><i>→</i>';
        } else {
            $output .= '<span>Следующая<i>→</i></span>';
        }
        //  $output .= '<span></span>';
        if ($cur_page > ($num_links + 1)) {
            $output .= '<a href="'.$url.'" title="Первая"></a>';
        }
        for ($loop = 0; $loop <= $num_pages - 1; $loop++) {
            $i = ($loop * $per_page);
            if ($i >= 0) {
                if (($cur_page - 1) == $loop) {
                    $output .= '<span style="color: purple;">&nbsp;'.($loop + 1).'&nbsp;</span>'; // Текущая страница
                } else {
                    $n = ($i == 0) ? '' : $i;
                    $z_sort = isset($_REQUEST['sort']) ? '&amp;sort='.$sort.'&amp;direction='.$direction : "";
                    $output .= '<a href="'.$url.'&amp;p='.$n.$z_sort.'">&nbsp;'.($loop + 1).'&nbsp;</a>';
                }
            }
        }

        return '<strong>Страницы:&nbsp;</strong>'.$output;
    }

    function escape($string, $esc_type = 'html')
    {
        switch ($esc_type) {
            case 'html':
                return htmlspecialchars($string, ENT_QUOTES);

            case 'htmlall':
                return htmlentities($string, ENT_QUOTES);

            case 'url':
                return rawurlencode($string);

            case 'quotes':
                // escape unescaped single quotes
                return preg_replace("%(?<!\\\\)'%", "\\'", $string);

            case 'hex':
                // escape every character into hex
                $return = '';
                for ($x = 0; $x < strlen($string); $x++) {
                    $return .= '%'.bin2hex($string[$x]);
                }

                return $return;

            case 'hexentity':
                $return = '';
                for ($x = 0; $x < strlen($string); $x++) {
                    $return .= '&#x'.bin2hex($string[$x]).';';
                }

                return $return;

            case 'decentity':
                $return = '';
                for ($x = 0; $x < strlen($string); $x++) {
                    $return .= '&#'.ord($string[$x]).';';
                }

                return $return;

            case 'javascript':
                // escape quotes and backslashes, newlines, etc.
                return strtr($string, array('\\' => '\\\\', "'" => "\\'", '"' => '\\"', "\r" => '\\r', "\n" => '\\n', '</' => '<\/'));

            case 'mail':
                // safe way to display e-mail address on a web page
                return str_replace(array('@', '.'), array(' [AT] ', ' [DOT] '), $string);

            case 'nonstd':
                // escape non-standard chars, such as ms document quotes
                $_res = '';
                for ($_i = 0, $_len = strlen($string); $_i < $_len; $_i++) {
                    $_ord = ord($string{$_i});
                    // non-standard char, escape it
                    if ($_ord >= 126) {
                        $_res .= '&#'.$_ord.';';
                    } else {
                        $_res .= $string{$_i};
                    }
                }

                return $_res;

            default:
                return $string;
        }
    }
