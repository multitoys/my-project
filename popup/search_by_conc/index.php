<?php
header("Content-Type: text/html; charset=utf-8");
echo "
    <!DOCTYPE html>
    <html>
    <head>
    <meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">
    <title>Open Server</title>
    </head>
    <body style=\"background: url(fon.png) top left repeat-x\">";
//define('BRACKETS', '#^\(.*\)$#');
//define('SIZES', '#^[0-9]+(-|,|[0-9])*см$#');
//define('PRETEXT', '#^(в|на|и|для|к|с)$#');
//define('QTY1', '#^[0-9]{1,2}$#');
//define('QTY2', '#шт\.?$#');
//define('QTY3', '#^вид(а)?$#');
//define('ART', '#[a-z]+#');
//define('SHORTS', '#((([а-я]+)-+(?3))|([а-я]+\.))#');
//define('SHORTS', '(((\.?)([а-я]+)(\.?)((-|/)+)([а-я]+)(\.?))|([а-я]+\.))');
//define('ALL', '#((([а-я]+)-+(?3))|([а-я]+\.)|(^\(.*\)$)|(^[0-9]+(-|,|[0-9])*см$)|(^(в|на|и|для|к|с)$)|(шт\.?$)|(^вид(а)?)|([a-z]+)|(^[0-9]{1,2}$))#');
//$conc = 'TG|Самолет|1042777|R/|5103|(192шт)|инер-й|2|вида|12|шт.|в|дисплее|375-27-7см';
//$conc = 'Акварель|мед.н/сух.|14кол.|з|пензликом,|.к/у,|Улюблені|іграшки,(311037)|Гамма';
//$tmp = explode("|", str_replace(",", "", $conc));
//var_dump($tmp);
//$i = 0;
//foreach ($tmp as $key => $val) {
//    $val = xEscapeSQLstring($val);
//    $val2 = substr($val, -2, 2);
//    $val = stem($val);
//    if (!preg_match(ALL, $val)) {
//        $conc[] = $val;
//        $i++;
//        $query = "SELECT productID FROM SC_products WHERE product_code LIKE '%" . $val . "%' AND enabled=1 AND in_stock=100";
//        $res = mysql_query($query) or die(mysql_error() . "<br>$query");
//        if (preg_match('/[0-9]+/', $val)) {
//            if (preg_match(SHORTS . 'ui', $val)) {
//                echo $i . ')' . $val . '<br/>';
//            }
//        }
//    }
//    function xEscapeSQLstring($_Data, $_Params = array(), $_Key = array())
//    {
//        if (!is_array($_Data)) {
//            return mysql_real_escape_string($_Data);
//        }
//        if (!is_array($_Key)) $_Key = array($_Key);
//        foreach ($_Data as $__Key => $__Data) {
//            if (count($_Key) && !is_array($__Data)) {
//                if (in_array($__Key, $_Key)) {
//                    $_Data[$__Key] = xEscapeSQLstring($__Data, $_Params, $_Key);
//                }
//            } else $_Data[$__Key] = xEscapeSQLstring($__Data, $_Params, $_Key);
//        }
//        return $_Data;
//    }

//    preg_match_all("|<[^>]+>(.*)</[^>]+>|U", "<b>пример: </b><div align=left>это тест</div>", $out, PREG_PATTERN_ORDER);
//    echo $out[0][0] . "" . $out[0][1] . "\n";
//    echo $out[1][0] . ", " . $out[1][1] . "\n";
//    echo count($out[1]) . "\n";

$html_dir = $_SERVER['DOCUMENT_ROOT'].'/popup/search_by_conc/divoland';
$files = array_slice(scandir($html_dir), 2);

$f = 4;
$category = substr($files[$f], 0, -5);
$html = file_get_contents($html_dir.$files[$f]);

foreach ($files as $file) {
    $html = file_get_contents($html_dir.$file);

/*preg_match_all('|<a[\s]+class="name"[^<>]*?>(.*)</a>|U', $html, $names, PREG_PATTERN_ORDER);*/

/*preg_match_all('|<a[^<>]*?class="price[\s]+nyroModal">([^<>]*?)<small>|U', $html, $prices, PREG_PATTERN_ORDER);*/

preg_match_all('|<a[^<>]*?class="zoom[\s]+nyroModal"[\s]+href="/prodimages/normal/[\w]+/([\w]+?)\.jpg"[^<>]*?>[^<>]*?</a>[^<>]*?</div>[^<>]*?<a[\s]+class="name"[^<>]*?>(.*)</a>[^<>]*?<a[^<>]*?class="price[\s]+nyroModal">[\s]*?([^<>]*?)<small>|U', $html, $codes, PREG_PATTERN_ORDER);

    $num = count($codes[1]);
    echo $category.'<br>';
    for ($i = 0; $i < $num; $i++) {
        $n = $i + 1;
//        $f++;
        echo /*$n. '. ' .*/ $codes[1][$i]. '|' . $codes[2][$i] . '|' . $codes[3][$i] .  '<br>';
    }
}

//preg_match_all("|<div[\s]+class='name'>(.*)</div>|U", $html, $names, PREG_PATTERN_ORDER);
//preg_match_all("|<div[\s]+class='price'>([^<>]*?)</div>|U", $html, $prices, PREG_PATTERN_ORDER);

//preg_match_all("|<div[\s]+class='price'>([^<>]*?)</div>|U", $html, $prices, PREG_PATTERN_ORDER);

//$num = count($names[1]);
//
//for ($i = 0; $i < $num; $i++) {
//        $n = $i + 1;
////        echo $n . '. ' . $names[1][$i] . ' | ' . $prices[1][$i] . '<br>';
//        echo $n . '. ' . $names[1][$i]. '<br>';
//    }

