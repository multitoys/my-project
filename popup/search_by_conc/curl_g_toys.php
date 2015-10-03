<?php
    /**
     * Created by PhpStorm.
     * User: multi
     * Date: 20.09.2015
     * Time: 21:58
     */

    $start = microtime(true);
    ini_set('display_errors', true);

    define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'].'/published/SC/html/scripts');
    define('DIR_COMPETITORS', $_SERVER['DOCUMENT_ROOT'].'/popup/search_by_conc');

    include_once(DIR_ROOT.'/includes/init.php');
    include_once(DIR_CFG.'/connect.inc.wa.php');
    include(DIR_FUNC.'/setting_functions.php');
    include_once(DIR_COMPETITORS.'/curl_competitors.php');
    include(DIR_COMPETITORS.'/urls_grandtoys.php');

    $DB_tree = new DataBase();
    $DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));
    $DB_tree->selectDB(SystemSettings::get('DB_NAME'));

    echo(<<<TAG

			<html>
				<head>
					<link rel='stylesheet' type='text/css' href='css/import.css'>
				</head>
				<body>
                <div id='products'>
                    <div style='width:0;'>&nbsp;</div>
                </div>

TAG
    );

    define('SLASH', '|');
    define('NAME_PATTERN', '<div\s+class="block[0-9]*?"[^<>]*?>[^<>]*?<div\s+class="product-overview-image"[^<>]*?>[^<>]*?<div\s+id="img-radius"[^<>]*?>[^<>]*?<a[^<>]*?>[^<>]*?</a>[^<>]*?</div>[^<>]*?</div>[^<>]*?<div\s+class="product-title"[^<>]*?>[^<>]*?<a[^<>]*?>[\s]*([^<>]+?)[\s]*</a>[^<>]*?</div>[^<>]*?');
    define('PRICE_PATTERN', '<div\s+class="product-price"[^<>]*?>[\s]+([0-9.]+?)[^<>]*?');
    define('CODE_PATTERN', '<div\s+class="available"[^<>]*?>[^<>]*?</div>[^<>]*?<form[^<>]*?>[^<>]*?<input\s+type="[^"]*?"\s+value="([0-9]+?)"[^<>]*?>');

    $headers = array
    (
        ''
    );
    $categories = array(
        'Детские игрушки (4516)'      => array(
            'Конструкторы (489)'          => '/ru/566-Konstruktori',
            'Летний ассортимент (46)'     => '/ru/569-Letnii-assortiment',
            'Мягкие игрушки (85)'         => '/ru/549-Myagkie-igrushki',
            'Настольные игры (438)'       => '/ru/735-Nastolnie-igri',
            'Развивающие игры (263)'      => '/ru/568-Razvivayuschie-igri',
            'Творчество (53)'             => '/ru/1060-05Detskoe-tvorchestvo',
            'Товары для девочек (717)'    => '/ru/570-Tovari-dlya-devochek',
            'Товары для детей (728)'      => '/ru/571-Tovari-dlya-detei',
            'Товары для мальчиков (1284)' => '/ru/572-Tovari-dlya-malchikov',
            'Украинский пластик (412)'    => '/ru/577-Ukr-plastik'
        ),
        'Велосипеды (18)'             => array(
            '2-х колесные (9)'  => '/ru/979-2-h-kolesnie',
            '3-х колесные (5)'  => '/ru/977-3-h-kolesnie',
            'Электромобили (4)' => '/ru/1142-Elektromobili'
        ),
        'Спорттовары (82)'            => array(
            'Аксессуары для спорта (9)'   => '/ru/1074-Aksessuari-dlya-sporta',
            'Бадминтоны (4)'              => '/ru/734-Badmintoni',
            'Баскетбол (2)'               => '/ru/796-Basketbol',
            'Боксерские наборы (1)'       => '/ru/535-Bokserskie-nabori',
            'Дартс (4)'                   => '/ru/541-Darts',
            'Защита, шлемы (1)'           => '/ru/249-Zaschita-shlemi',
            'Мячи (7)'                    => '/ru/544-Myachi',
            'Мячи резиновые детские (13)' => '/ru/396-Myachi-rezinovie-detskie',
            'Ролики (11)'                 => '/ru/536-Roliki',
            'Самокаты (15)'               => '/ru/538-Samokati',
            'Скакалки (2)'                => '/ru/43-Skakalki',
            'Скейты (5)'                  => '/ru/344-Skeiti',
            'Теннис (5)'                  => '/ru/543-Tennis-',
            'Хулахупы и обручи (3)'       => '/ru/61-Hulahupi-i-obruchi'
        ),
        'Надувные изделия (129)'      => array(
            'BK Toys Ltd. (10)' => '/ru/1110-BK-Toys-Ltd',
            'INTEX (119)'       => '/ru/1059-04INTEX'
        ),
        'Офисная канцелярия (1)'      => array(
            'Блоки для заметок (1)' => '/ru/1030-Bloki-dlya-zametok'
        ),
        'Школьные принадлежности (7)' => array(
            'Детское творчество (1)'       => '/ru/442-Detskoe-tvorchestvo',
            'Закладки (1)'                 => '/ru/217-Zakladki',
            'Рюкзаки и сумки (4)'          => '/ru/435-Ryukzaki',
            'Чертежные принадлежности (1)' => '/ru/439-Chertezhnie-prinadlezhnosti'
        ),
        'Печатная продукция (25)'     => array(
            'Книги (25)' => '/ru/426-Knigi'
        ),
        'Товары для детей (6)'        => array(
            'Мебель для детей (6)' => '/ru/1016-11Mebel-dlya-detei'
        ),
        'Сувенирная продукция (6)'    => array(
            'Наборы (1)'            => '/ru/1139-Podarochnie-paketi',
            'Небесные фонарики (3)' => '/ru/1136-Nebesnie-fonariki',
            'Открытки (2)'          => '/ru/1078-Otkritki'
        )
    );

//    define('START_URL', 'http://gtoys.com.ua');
//    define('END_URL', '/page_size');
    define('URL_COMPETITORS', 'http://gtoys.com.ua');
    define('URL_POSTFIX', '/page_size');
    define('EXT', '.html');

    $login_url = URL_COMPETITORS.'/ru/user/login';
    $refferer = URL_COMPETITORS;
    postAuth($login_url, 'UserLogin[username]=Elenna&UserLogin[password]=0675230623', $headers);

    UpdateValue('Conc__grandtoys', 'enabled = 0');

    //    DeleteRow('Conc__grandtoys');
    //    DeleteRow('Conc_search__grandtoys');

    $no = 0;
    $new = 0;
    $part = 0;
    $percent = 0;
    $products_cnt = 2000;
    $replace_name = array('&quot;', '\'', '"');

    foreach ($categories as $parent => $cats) {

        set_time_limit(0);
        $category_urls = $cats;

        foreach ($category_urls as $category => $url) {

            $category_url = URL_COMPETITORS.$url.URL_POSTFIX.$products_cnt;
            $filename = Rus2Translit(trim($category));
            $filename = DIR_COMPETITORS.'/'.$filename.EXT;
            $products = '';

            readUrl($category_url, $filename, '', $headers);

            $html = file_get_contents($filename);
            preg_match_all(
                SLASH.NAME_PATTERN.PRICE_PATTERN.CODE_PATTERN.SLASH.'U',
                $html,
                $products,
                PREG_PATTERN_ORDER
            );

            $rowcount = count($products[1]);
            echo('<p>обновление цен категории <b>&laquo;'.$category.'&raquo;</b>...(<i>'.$rowcount.' товаров</i>)</p>');
            BuferOut();

            $category = mysql_real_escape_string($category);

            for ($j = 0; $j < $rowcount; $j++) {
                set_time_limit(0);
                $name = mysql_real_escape_string(trim(str_replace($replace_name, '', DecodeCodepage($products[1][$j]))));
                $price = (double)$products[2][$j];
                $code = mysql_real_escape_string(DecodeCodepage($products[3][$j]));
                $price_usd = $price / 20.51;
                $productID = GetValue('productID', 'Conc__grandtoys', "code = '$code'");

                if ($productID) {
                    $query
                        = "
                                    UPDATE  Conc__grandtoys
                                    SET     parent       = '$parent',
                                            category     = '$category',
                                            name         = '$name',
                                            price_uah    = $price,
                                            price_usd    = $price_usd,
                                            enabled      = 1
                                    WHERE   productID    =  $productID
                        ";
                    $res = mysql_query($query) or die(mysql_error()."<br>$query");
                } else {
                    $query
                        = "
                                INSERT INTO Conc__grandtoys
                                            (parent, category, code, name, price_uah, price_usd)
                                VALUES      ('$parent', '$category', $code, '$name', $price, $price_usd)
                              ";
                    $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    $new++;
                }
                $no++;
            }
        }
        $part++;
        $progress = round(($part / $parts * 100), 0, PHP_ROUND_HALF_DOWN);

        if ($progress > $percent) {
            $percent = $progress.'%';
            ProgressBar('products', $percent);
            BuferOut();
        }
    }
    ProgressBar('products', $percent, true);
    echo('<hr><span style="color:blue;">Обработано '.$no.' товаров</span><br><br>Новых '.$new.' товаров</span><br>');

    // Оптимизация таблиц
    $query = "UPDATE Conc__grandtoys SET parent='', category='' WHERE enabled=0";
    $res = mysql_query($query) or die(mysql_error()."<br>$query");

    $query = 'OPTIMIZE TABLE `Conc__grandtoys`, `Conc_search__grandtoys`';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    mysql_close();

    echo('
        <br>
          <div id=\'end\'>Импорт завершен!</div>
      ');

    Debugging($start);