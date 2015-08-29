<?php
ini_set('display_errors', true);
define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT']."/published/SC/html/scripts");
$DebugMode = false;
$Warnings = array();
include_once(DIR_ROOT.'/includes/init.php');
include_once(DIR_FUNC.'/functions.php');
include_once(DIR_CFG.'/connect.inc.wa.php');
include(DIR_FUNC.'/setting_functions.php' );

$DB_tree = new DataBase();
$DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));

$DB_tree->selectDB(SystemSettings::get('DB_NAME'));
define('VAR_DBHANDLER','DBHandler');

define('BRACKETS','(^\(.*\)$)');
define('SIZES','(^[0-9]+(-|,|[0-9])*см$)');
define('PRETEXT','(^(з|в|на|и|для|к|с|по)$)');
define('QTY1','(^[0-9]{1,2}$)');
define('QTY2','(шт\.?$)');
define('QTY3','(^вид(а)?$)');
define('ART','([a-z0-9\-]{2,}[^а-я\(\)])');
define('SHORTS','(((\.?)([а-я]+)(\.?)((-|/)+)([а-я]+)(\.?))|([а-я]+\.))');

define('ALL','#((((\.?)([а-я]+)(\.?)((-|/)+)([а-я]+)(\.?))|([а-я]+\.))|(^\(.*\)$)|(^[0-9]+(-|,|[0-9])*см$)|(^(з|в|на|и|для|к|с|по|у|(.?))$)|(шт\.?$)|(^вид(а)?)|([a-z]+)|(^[0-9]{1,2}$))#');

$mode = (isset($_GET["mode"]))?$_GET["mode"]:'';

switch ($mode) {
    case '1':
        findAnalogs();
        break;
    case '2':
        setAnalogs('Conc_search__divolend');
        break;
    case '3':
        unsetAnalogs('Conc_search__divolend');
        break;
    default:
        break;
}

function findAnalogs()
{
    $category_name = getCategories();
    if (isset($_GET["conc"]) && isset($_GET["code"]) && isset($_GET["price_uah"])) {
            $conc      = $_GET["conc"];
            $code      = $_GET["code"];
            $price_uah = $_GET["price_uah"];
            $k = 0.15;
            $replace = array(",","'");
            $tmp = explode("|", str_replace($replace,"",$conc));
            $product_code = array();
            $name_ru = array();
            $searchstring = array();
            $search = '';
            $i = 0;
            foreach( $tmp as $key=> $val ){
                // $val = xEscapeSQLstring($val);
                $i++;
                if (preg_match(ART.'ui',$val)) {
                    $res = getProductLike($val,'product_code',$price_uah,$k);
                    if (!empty($res)) {
                        while($temp = mysql_fetch_row($res)){
                            $product_code[$temp[0]] = $temp[0];
                            $searchstring[] = $val;
                        }
                    }
                }
                // var_dump($product_code);
                if (!preg_match(ALL.'ui',$val)) {
                    $res = getProductLike($val,'name_ru',$price_uah,$k);
                    if (!empty($res)) {
                        while($temp = mysql_fetch_row($res)){
                            $name_ru[$temp[0]] = $temp[0];
                            if (!$temp[1]) $product_code[$temp[0]] = $temp[0];
                            $searchstring[] = $val;
                        }
                    }
                }
            }
            $match_arr = array_intersect($product_code, $name_ru);
            $no = 0;
            foreach( $match_arr as $key=> $val ){
                if ( strlen($val) > 0 ){
                    $query ="SELECT categoryID, name_ru, product_code, Price, code_1c FROM SC_products WHERE productID = $val ORDER BY product_code";
                    $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    while($Product = mysql_fetch_object($res)){
                        $no++;
                        $name_ru = $Product->name_ru;
                        $product_code = $Product->product_code;
                        $category = $category_name[$Product->categoryID];
                        foreach ( $searchstring as $keys=> $match ) {
                            $pattern = '/'.$match.'/i';
                            $name_ru = preg_replace($pattern,"<span style='color:white; background:grey;'>$match</span>",$name_ru);
                            $product_code = preg_replace($pattern,"<span style='color:white; background:blue;'>$match</span>",$product_code);
                        }
                        $price_diff = round(($Product->Price/$price_uah - 1)*100,1);
                        $marked = ($price_diff > 0)?'red':'green';
                        $search.= "<p><button class='my-button' title='задать соответствие' onclick='setAnalogs(\"$code\",\"$Product->code_1c\")' type=button>=?</button>
                                $no)$Product->code_1c&nbsp;&nbsp;[$product_code]&nbsp;&nbsp;$name_ru&nbsp;&nbsp;
                                <span style='color:$marked;'>$Product->Price</span>&nbsp;&#8372;&nbsp;&nbsp;разница: $price_diff%
                                <br><small>категория: &laquo;$category&raquo;</small></p>";
                    }
                }
            }
        echo $search;
    }
}

function setAnalogs($table)
{
    $category_name = getCategories();
    if (isset($_GET["code"]) && isset($_GET["code1c"])) {
        $code   = $_GET["code"];
        $code1c = $_GET["code1c"];
        $analog = getValue('code_1c', "code = '$code'");
        if (!$analog) {
            $query = "INSERT INTO $table
								 (  code,    code_1c )
						VALUES   ('$code', '$code1c')"; 
            $res   = mysql_query($query) or die(mysql_error()."<br>$query");
        } else {
            $query = "UPDATE $table
						SET    code_1c = '$code1c'
						WHERE  code   = '$code'";
            $res   = mysql_query($query) or die(mysql_error()."<br>$query");
        }
        $query3 = "SELECT categoryID, code_1c, product_code, name_ru, Price
                   FROM SC_products WHERE code_1c LIKE '$code1c'";
		$res3 = mysql_query($query3) or die(mysql_error() . $query3);
        $M_Product = mysql_fetch_object($res3);
        $category = $category_name[$M_Product->categoryID];
        $search = "<p><button class='my-button' title='задать соответствие' onclick='unsetAnalogs(\"$code\")' type=button>!=
                   </button>$M_Product->code_1c&nbsp;&nbsp;[$M_Product->product_code]&nbsp;&nbsp;$M_Product->name_ru&nbsp;&nbsp;$M_Product->Price&nbsp;&#8372;
                   <br><small>категория: &laquo;$category&raquo;</small></p>";
        echo $search;
    }
}

function unsetAnalogs($table)
{
    if (isset($_GET["code"])) {
        $code   = $_GET["code"];
        $analog = getValue('code_1c', "code = '$code'");
        if ($analog) {
            $query = "DELETE FROM $table
                      WHERE code = '$code'";
            $res   = mysql_query($query) or die(mysql_error()."<br>$query");
        }
        $search= "";
        echo $search;
    }
}

/**
 * @param $value - сравниваемая строка
 * @param string $field - с чем сравниваем
 * @param float|int $price - цена для сравнения
 * @param float|int $k - коэффициент отклонения цены
 * @return resource - массив productID
 */
function getProductLike($value, $field = 'name_ru', $price = 0, $k = 0)
{
    $condition = '';
    if ($price) {
        $price1 = $price/(1+$k);
        $price2 = $price/(1-$k);
        $condition = ' AND Price BETWEEN '.$price1.' AND '.$price2;
    }
    $query ="SELECT productID FROM SC_products WHERE $field LIKE '%".$value."%' AND enabled=1 AND in_stock=100 $condition";
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    return $res;
}

// function insertAnalog($code, $code1c)
// {
    // $analog = getValue('code1c', 'Conk_search_divolend', "code = '$code'");
    // if (!$analog) {
         // $query = "INSERT INTO Conk_search_divolend
								 // (  code,    code1c )
						// VALUES   ('$code', '$code1c')"; 
         // $res   = mysql_query($query) or die(mysql_error()."<br>$query");
    // } else {
        // $query = "UPDATE Conk_search_divolend
						// SET    code1c = '$code1c'
						// WHERE  code   = '$code'";
        // $res   = mysql_query($query) or die(mysql_error()."<br>$query");
    // }
    // return $res;
// }

function getValue($what, $condition)
{
    $query = "SELECT $what FROM Conc_search__divolend WHERE $condition LIMIT 1";
    $result = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);
    $row = mysql_fetch_row($result);
    return $row[0];
}

function getCategories()
{
    $query = "SELECT categoryID, name_ru FROM SC_categories";
    $res = mysql_query($query) or die(mysql_error().$query);

    $category_name = array();

    while ($Categories = mysql_fetch_object($res)) {
        $category_name[$Categories->categoryID] = $Categories->name_ru;
    }

    return $category_name;
}