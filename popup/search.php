<?php
    /**
     * Created by PhpStorm.
     * User: multi
     * Date: 27.06.2015
     * Time: 10:35
     */
    define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'].'/published/SC/html/scripts');
    $DebugMode = false;
    $Warnings = array();
    include_once(DIR_ROOT . '/includes/init.php');
    include_once(DIR_FUNC . '/functions.php');
    include_once(DIR_CFG . '/connect.inc.wa.php');
    include(DIR_FUNC . '/setting_functions.php');

    $DB_tree = new DataBase();
    $DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));

    $DB_tree->selectDB(SystemSettings::get('DB_NAME'));
    define('VAR_DBHANDLER','DBHandler');

    $vip = isset($_SESSION['cs_vip']) ? $_SESSION['cs_vip'] : '';

    $search = $_POST['search'];
    $search = addslashes($search);
    $search = htmlspecialchars($search);
    $search = stripslashes($search);
    $search = trim($search);

    if ($search === '') {
        exit;
    }

    $limit = 'LIMIT 100';
    $order = 't1.name_ru';
    $enabled = 't1.enabled AND';
    $close = '<button class=\'search_close blue-button\' onclick=document.getElementById(\'live_search\').innerHTML=\'\';>&times;</button>';
    $all_res = '<ul><li><label for=search_ok>Просмотреть все результаты поиска...</label>'.$close.'</li>';

    if (array_key_exists('conc', $_POST)) {
        $limit = '';
        $order = 't1.product_code';
        $enabled = '';
        $all_res = '<ul><li>Результаты поиска</li>';
    }

    $query = mysql_query("SELECT t1.productID, t1.product_code, t1.Price, t1.name_ru, t1.code_1c,
                t1.default_picture, t1.slug, t3.filename
                FROM SC_products t1
                LEFT JOIN SC_product_pictures t3 ON t1.default_picture = t3.photoID
                WHERE $enabled (t1.product_code LIKE '%$search%' OR  t1.name_ru LIKE '%$search%')  ORDER BY $order ASC $limit") or die('<ul><li>Мы ничего не нашли по Вашему запросу...
              Попробуйте изменить запрос.</li></ul>');

    if (mysql_num_rows($query) > 0) {
        echo $all_res;
        while ($sql = mysql_fetch_array($query)) {

            $set_conc = "href='/product/{$sql['slug']}'";
            if (isset($_POST['conc'])) {
                $conc = $_POST['conc'];
                $code = $_POST['code'];
                $code1c = $sql['code_1c'];
                $price_conc = $_POST['priceConc'];
                $set_conc = "onclick=setAnalogs(\"$conc\",\"$code\",\"$code1c\",\"$price_conc\")";
            }
            $search = mb_strtolower($search, 'UTF-8');
            $name_ru = mb_strtolower($sql['name_ru'], 'UTF-8');
            $product_code = mb_strtolower($sql['product_code'], 'UTF-8');
            $picture = substr($sql['filename'], 0, -4).'_s.jpg';
            $price = ($vip)?'<p><span style="color:#008DD9">цена: '.$sql['Price'].'</span></p>':'';
            $name_ru = str_replace($search, "<mark class=mark_name>$search</mark>", $name_ru);
            $product_code = str_replace($search, "<span class=mark_code>$search</span>", $product_code);
            echo "
                <li>
                    <a $set_conc>
                        <img width=64px height=48px
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
        echo '<ul><li><label>Мы ничего не нашли по Вашему запросу...</label>' . $close . '</li></ul>';
    }
