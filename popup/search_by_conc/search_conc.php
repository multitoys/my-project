<?php
    //$ip = $_SERVER['REMOTE_ADDR'];
    //$class_c = substr($ip, 0, strrpos($ip, '.'));
    //echo $class_c;
    //exit(0);
    $mode = (isset($_GET['mode'])) ? $_GET['mode'] : '';
    if ($mode) {
        ini_set('display_errors', true);
        define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'].'/published/SC/html/scripts');
        $DebugMode = false;
        $Warnings = array();
        include_once(DIR_ROOT.'/includes/init.php');
        include_once(DIR_FUNC.'/functions.php');
        include_once(DIR_CFG.'/connect.inc.wa.php');
        include(DIR_FUNC.'/setting_functions.php');

        $DB_tree = new DataBase();
        $DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));

        $DB_tree->selectDB(SystemSettings::get('DB_NAME'));
        define('VAR_DBHANDLER', 'DBHandler');
    }
    $auxpage = $_SESSION['auxpage'];
    switch ($mode) {
        case '1':
            findAnalogs();
            break;
        case '2':
            setAnalogs('Conc_search__'.$auxpage, $auxpage);
            break;
        case '3':
            unsetAnalogs('Conc_search__'.$auxpage, $auxpage);
            break;
        default:
            break;
    }

    function findAnalogs()
    {
        $k = 0.5;
        $conc_args = array();
        if (isset($_GET['conc'], $_GET['code'], $_GET['price'])) {
            $conc = $_GET['conc'];
            $code = $_GET['code'];
            $price = $_GET['price'];
            $limit = 30;
        } else {
            $conc_args = func_get_args();
            $conc = $conc_args[0];
            $code = $conc_args[1];
            $price = $conc_args[2];
            //$price = 0;
            $limit = 2;
        }

        $ajax = (!count($conc_args));
        
        $replace = array(',', '.', ')', '(', '\'');
        $match_str = preg_replace('/\s\s+/', ' ', str_replace('|', ' ', str_replace($replace, ' ', $conc)));
        $match_str = mysql_real_escape_string($match_str);
        $tmp = explode('|', str_replace($replace, '', $conc));
        $searchstring = array();
        $search = '';
        $category_name = getCategories();

        foreach ($tmp as $key => $val) {
            if (strlen($val) > 2) {
                $searchstring[] = $val;
            }
        }
        $no = 0;
        //if ($res = getProductLike($match_str, $price, $k, $limit)) {
        if ($res = getProductLike($match_str, 0, 0, $limit)) {
            $auxpage = $_SESSION['auxpage'];
            while ($Product = mysql_fetch_object($res)) {

                if (!getValue('code_1c', "code_1c = '$Product->code_1c'", 'Conc_search__'.$auxpage)) {

                    $score = round($Product->score, 1);
                    $min_score = ($ajax) ? 5 : 7;

                    if ($score > $min_score) {

                        $no++;
                        $name_ru = mb_strtolower($Product->name_ru, 'UTF-8');
                        $product_code = mb_strtolower($Product->product_code, 'UTF-8');
                        $category = $category_name[$Product->categoryID];
                        $searchstrings = array_unique($searchstring);

                        foreach ($searchstrings as $keys => $match) {

                            $match = mb_strtolower($match, 'UTF-8');
                            $name_ru = str_replace($match, '<mark class="mark_name">'.$match.'</mark>', $name_ru);
                            $product_code = str_replace($match, '<span class="mark_code">'.$match.'</span>', $product_code);
                        }

                        $price_diff = round(($Product->Price / $price - 1) * 100, 1);
                        $marked = ($price_diff > 0) ? '#BD2626' : 'green';
                        $disabled = '';
                        $button = 'font-size:1.3em;height: 2.2em;width: 3em;';
                        $art = 'font-weight:700;color:orangered;';

                        if ($ajax) {
                            if (!$Product->enabled) {
                                //$disabled = 'color:grey;text-decoration:line-through;';
                                $button .= 'background-color: #848484;';
                            }
                        } else {
                            $button .= 'background-color: lime;';
                        }

                        $search .= "<div style='max-width: 800px;'><div style='float:left;'><button class='blue-button' title=''
                        onclick='setAnalogs(\"$conc\",\"$code\",\"$Product->code_1c\",\"$price\")' type=button
                         style=\"$button\">$score</button></div><div style=\"$disabled\">
                        $no) $Product->code_1c  <span style=$art>[$product_code]</span> $name_ru
                        <span style='color:$marked'>$Product->Price</span> &#8372; <br><span style='color: #03A9F4;font-style: italic;'>разница: </span><span style='color:$marked;font-weight:700'>$price_diff%</span>
                        <br><small style='font-size:0.7em;'>категория: &laquo;$category&raquo;</small>";
                        if (!count($conc_args)) {
                            $search .= '<hr>';
                        }
                        $search .= '</div></div>';
                    }
                }
            }
            if (!$ajax) {
                return $search;
            }
            if ($no < 1) {
                $search .= 'Ничего не найдено...';
            }
            echo $search;
        }
//            else {
//                while ($k < 0.76) {
//                    $k += 0.25;
//                    findAnalogs($k);
//                }
//            }
//        if ($search === '') {
////            $search .= "<p>$match_str</p>";
//            while ($k < 0.51) {
//                $k += 0.25;
//                findAnalogs($k);
//            }
//        }

    }

    function setAnalogs($table, $auxpage)
    {
        $category_name = getCategories();
        if (isset($_GET['conc'], $_GET['code'], $_GET['code1c'], $_GET['price'])) {
            $conc = $_GET['conc'];
            $code = $_GET['code'];
            $code1c = $_GET['code1c'];
            $price = $_GET['price'];
            $analog = getValue('code_1c', "code = '$code'", 'Conc_search__'.$auxpage);
            if (!$analog) {
                $query = "INSERT INTO $table
								 (  code,    code_1c )
						VALUES   ('$code', '$code1c')";
                $res = mysql_query($query) or die(mysql_error()."<br>$query");
            } else {
                $query = "UPDATE $table
						SET    code_1c = '$code1c'
						WHERE  code   = '$code'";
                $res = mysql_query($query) or die(mysql_error()."<br>$query");
            }
            $query3 = "SELECT categoryID, code_1c, product_code, name_ru, Price
                   FROM Search_products WHERE code_1c LIKE '$code1c'";
            $res3 = mysql_query($query3) or die(mysql_error().$query3);
            $M_Product = mysql_fetch_object($res3);
            $category = $category_name[$M_Product->categoryID];
            $search = "
                    <p class='productname newpostup'>
                       <button class='blue-button' title=''
                           style='font-size:2em;background-color: tomato;'
                           onclick='unsetAnalogs(\"$conc\",\"$code\",\"$code1c\",\"$price\")'
                            type=button>!=</button>
                        $M_Product->code_1c&nbsp;&nbsp;<span style='color: #03A9F4'>$M_Product->product_code</span>&nbsp;&nbsp;
                        $M_Product->name_ru&nbsp;&nbsp;$M_Product->Price&nbsp;&#8372;
                      <br><small>категория: &laquo;$category&raquo;</small>
                    </p>
                   ";
            echo $search;
        }
    }

    function unsetAnalogs($table, $auxpage = '')
    {
        if (isset($_GET['conc'], $_GET['code'], $_GET['code1c'], $_GET['price'])) {
            $conc = $_GET['conc'];
            $code = $_GET['code'];
            $code1c = $_GET['code1c'];
            $price = $_GET['price'];
            $analog = getValue('code_1c', "code = '$code'", 'Conc_search__'.$auxpage);
            if ($analog) {
                $query = "DELETE FROM $table
                      WHERE code = '$code'";
                $res = mysql_query($query) or die(mysql_error()."<br>$query");
            }
            $search = "<a class='blue-button fancybox fancybox.ajax find' title=''
href='/popup/search_by_conc/search_conc.php?mode=1&conc=$conc&code=$code&price=$price'>Найти совпадения</a>
  <input type='text' class='input_message search-concs' rel='Поиск аналогов' value='Поиск аналогов' name='searchstring' data-conc=$conc data-code=$code data-price=$price>
        ";
            echo $search;
        }
    }

    function getProductLike($value, $price = 0, $k = 9, $limit = 50)
    {
        $condition = '';
        if ($price) {
            $price1 = $price / (1 + $k);
            $price2 = $price / (1 - $k);
            $condition = ' AND (Price BETWEEN '.$price1.' AND '.$price2.')';
        }
        $query = 'SELECT categoryID, code_1c, product_code, name_ru, Price, enabled, MATCH (product_code, name_ru)
             AGAINST (\''.$value.'\')  AS score
             FROM Search_products
             WHERE MATCH (product_code, name_ru)
             AGAINST (\''.$value.'\') '.$condition.' LIMIT '.$limit;
        $res = mysql_query($query) or die(mysql_error()."<br>$query");

        return $res;
    }

    function getValue($what, $condition, $auxpage)
    {
        $query = "SELECT $what FROM $auxpage WHERE $condition LIMIT 1";
        $result = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);
        $row = mysql_fetch_row($result);

        return $row[0];
    }

    function getCategories()
    {
        $query = 'SELECT categoryID, name_ru FROM SC_categories';
        $res = mysql_query($query) or die(mysql_error().$query);

        $category_name = array();

        while ($Categories = mysql_fetch_object($res)) {
            $category_name[$Categories->categoryID] = $Categories->name_ru;
        }

        return $category_name;
    }
