<?php
    /**
     * Created by PhpStorm.
     * User: multi
     * Date: 27.06.2015
     * Time: 10:35
     */
    define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/published/SC/html/scripts');
    $DebugMode = false;
    $Warnings = array();
    include_once(DIR_ROOT . '/includes/init.php');
    include_once(DIR_FUNC . '/functions.php');
    include_once(DIR_FUNC . '/product_functions.php');
    include_once(DIR_CFG . '/connect.inc.wa.php');
    include(DIR_FUNC . '/setting_functions.php');

    $DB_tree = new DataBase();
    $DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));

    $DB_tree->selectDB(SystemSettings::get('DB_NAME'));
    define('VAR_DBHANDLER', 'DBHandler');

    //    $vip = isset($_SESSION['cs_vip']) ? $_SESSION['cs_vip'] : '';
    $usd = 1;
    $currency = ' грн.';

    $may_order = isset($_SESSION['cs_may_order']) ? $_SESSION['cs_may_order'] : '0';

    if (isset($_SESSION['cs_id'])) {
        $query = '
                            SELECT settings_value
                            FROM SC_settings
                            WHERE settings_constant_name=\'CONF_SHOW_ADD2CART\'';
        $res = mysql_query($query) or die();
        $settings = mysql_fetch_row($res);

        if ($settings[0] == 1) {
            $buy_enabled = true;
        }

        $CustomerID = (int)$_SESSION['cs_id'];
    } else {
        $buy_enabled = false;
    }

    if ($may_order) {
        if (isset($_SESSION['current_currency']) && $_SESSION['current_currency'] == 10) {
            $usd = isset($_SESSION['usd']) ? $_SESSION['usd'] : 1;
            $currency = ' у.е.';
        }
        $search = stripAll($_POST['search']);
        //    $search = addslashes($search);
        //    $search = htmlspecialchars($search);
        //    $search = stripslashes($search);
        //    $search = trim($search);

        if ($search === '') {
            exit;
        }
        //    $search_array = explode(' ', $search);

        $limit = 'LIMIT 100';
        $order = 't1.name_ru';
        $enabled = 't1.enabled AND';
        $close = '<button class=\'search_close blue-button\' onclick=document.querySelector(\'div.container\').innerHTML=\'\';>&times;</button>';
        $all_res = '<ul><li><label for=search_ok>Все результаты поиска</label>... ' . $close . '</li>';

        $ukraine = '';

        if (array_key_exists('conc', $_POST)) {
            $search = mb_strtolower($search, mb_detect_encoding($search));
            $limit = '';
            $order = 't1.product_code';
            $enabled = '';
            $search_array = array(' ', '-', '/', '\\');
            if (preg_match('/#1#/', $search)) {
                $search = str_replace('#1#', '', $search);
                $ukraine = ' AND ukraine = 1';
            } elseif (preg_match('/#0#/', $search)) {
                $search = str_replace('#0#', '', $search);
                $ukraine = ' AND ukraine = 0';
            }
            $search = str_replace('+++', '.+', $search);
            $search = mysql_real_escape_string(str_replace($search_array, '.?', $search));
            $condition = "(LOWER(t1.product_code) REGEXP '$search' OR  LOWER(t1.name_ru) REGEXP '$search' OR  LOWER(t1.brand) = '$search')";
            $all_res = '<ul><li>Результаты поиска</li>';
        } else {
            $search = _searchPatternReplace($search);
            $search = mysql_real_escape_string($search);
            $condition = "(t1.product_code LIKE '%$search%' OR  t1.name_ru LIKE '%$search%' OR  t1.brand LIKE '%$search%')";
        }

        $query = mysql_query("SELECT t1.productID, t1.product_code, t1.Price, t1.skidka, t1.ukraine, t1.name_ru, t1.code_1c,
                    t1.default_picture, t1.slug, t1.brand, t3.filename
                    FROM SC_products t1
                    LEFT JOIN SC_product_pictures t3 ON t1.default_picture = t3.photoID
                    WHERE t1.in_stock>0  AND $enabled $condition $ukraine 
                    ORDER BY $order ASC $limit") or die('<ul><li>Мы ничего не нашли...:(</li></ul>');

        if (mysql_num_rows($query) > 0) {
            echo $all_res;
            while ($sql = mysql_fetch_array($query)) {

                $too_long = false;
                $name_ru = $sql['name_ru'];

                if (mb_strlen($name_ru) > 105) {
                    $too_long = true;
                    $name_ru = mb_substr($name_ru, 0, 105);
                }

                $search = mb_strtolower($search, 'UTF-8');
                $name_ru = mb_strtolower($name_ru, 'UTF-8');
                $product_code = mb_strtolower($sql['product_code'], 'UTF-8');
                $name_ru = str_replace($search, "<mark class=mark_name>$search</mark>", $name_ru);
                $name_ru = ucfirst_utf8($name_ru);
                $name_ru .= ($too_long) ? '...' : '';
                $product_code = str_replace($search, "<span class=mark_code>$search</span>", $product_code);

                $set_conc = "href='/product/{$sql['slug']}'";
                $picture = ($sql['filename']) ? substr($sql['filename'], 0, -4) . '_s' : 'no_photo';
                $picture .= '.jpg';
                $price = '';

                if (isset($_POST['conc'])) {
                    $conc = stripAll($_POST['conc']);
//                    $code = stripAll($_POST['code']);
                    $code = 0;
                    $code1c = $sql['code_1c'];
                    $price_conc = stripAll($_POST['priceConc']);
                    $set_conc = "onclick=setAnalogs(\"$conc\",\"$code\",\"$code1c\",\"$price_conc\")";
                    $price = $sql['Price'];
                } else {
                    if ($buy_enabled) {
                        $price = round(priceDiscount($sql['Price'], $sql['skidka'], $sql['ukraine']) / $usd, 2);
                    }
                }
                if ($buy_enabled) {
                    $price = '<p><span style="color:#008DD9">цена: ' . $price . $currency . '</span></p>';
                }
                //$price = ($vip)?'<p><span style="color:#008DD9">цена: '.$sql['Price'].'</span></p>':'';
                echo "
                    <li>
                        <a $set_conc>
                            <img width=80px height=60px
                                  src='/published/publicdata/MULTITOYS/attachments/SC/search_pictures/{$picture}'
                                  alt='{$sql['name_ru']}'>
                            <span>{$name_ru}</span>
                            <p>арт. {$product_code}</p>$price
                        </a>
                    </li>
                ";
            }
            echo '</ul>';
        } else {
            echo '<ul><li><label>Мы ничего не нашли... </label> :(' . $close . '</li></ul>';
        }
    } else {
        die();
    }
