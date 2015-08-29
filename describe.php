<?php
$start = microtime(true);
ini_set('display_errors', true);
define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/published/SC/html/scripts");
$DebugMode = false;
$Warnings = array();
include_once(DIR_ROOT . '/includes/init.php');
include_once(DIR_CFG . '/connect.inc.wa.php');
include(DIR_FUNC . '/setting_functions.php');
$DB_tree = new DataBase();
$DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));
$DB_tree->selectDB(SystemSettings::get('DB_NAME'));
define('VAR_DBHANDLER', 'DBHandler');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/excel_reader2.php');

echo("
			<html>
				<head>
					<link rel='stylesheet' type='text/css' href='css/import.css'>
				</head>
				<body>
	   ");

removeDir($_SERVER['DOCUMENT_ROOT'] . '/temp/import/');

// Распаковка архива
$archive_dir = $_SERVER['DOCUMENT_ROOT'] . '/upload/';
$dest_dir = $_SERVER['DOCUMENT_ROOT'] . '/temp/import/';
$zip = new ZipArchive();
$fileName = $archive_dir . "describe.zip";
if ($zip->open($fileName) !== true) {
    echo("Error while openning archive file: " . $archive_dir . "describe.zip");
    exit(1);
}
$zip->extractTo($dest_dir);
echo("<div id='extract'>Файлы ($zip->numFiles) извлечены в папку " . $_SERVER['DOCUMENT_ROOT'] . "/temp/import.<br><br>");

//----------- Импорт товаров ----------- 
$start4 = microtime(true);
echo('Импорт товаров ...<hr>');

echo("
			<div id='products' >
				<div style='width:0px;'>&nbsp;</div>
			</div>
		 ");
echo str_repeat(' ', 1024 * 64);
flush();
// usleep(5000000);

$filename = $dest_dir . 'describe.xls';
$data = new Spreadsheet_Excel_Reader($filename);
$rowcount = $data->rowcount();
if ($rowcount < 2) {
    die(ShowError("XLS-файл ($filename) не содержит данных! (rowcount = $rowcount)"));
}
$colcount = $data->colcount();
if ($colcount < 2) {
    die(ShowError('Структура XLS-файла не соответствует требованиям! Количество столбцов меньше шести!'));
}
$no = 0;
$percent = 0;
$erorr = 0;

for ($row = 2; $row < $rowcount + 1; $row++) {
    set_time_limit(180);

    $no++;
    $id = $data->val($row, 'A');
    $desc = mysql_real_escape_string(DecodeCodepage($data->val($row,'B')));

    $est = GetValue('code_1c', 'SC_products', "code_1c = '$id'");

    if ($est) {
        $productID = GetValue('productID', 'SC_products', "code_1c = '$id'");

        $query = "
						UPDATE SC_products
						SET   brief_description_ru = '$desc',
                              description_ru = '$desc'
						WHERE productID = $productID";
        $res = mysql_query($query) or die(mysql_error() . "<br>$query");

    }

    $progress = round(($no / ($rowcount - 1) * 100), 0, PHP_ROUND_HALF_DOWN);
    if ($progress > $percent) {
        $percent = $progress . "%";
        echo '<script language="javascript">
				document.getElementById("products").innerHTML="<div style=\"width:' . $percent . ';\">' . $percent . '</div>";
				</script>';
        echo str_repeat(' ', 1024 * 64);
        flush();
        //	usleep(10000);
    }
}
echo('<span style="color:blue;"><br>Обработано ' . $no . ' товаров</span><br>');
$memoscript = memory_get_peak_usage(true) / (1024 * 1024);
$time = microtime(true) - $start4;
printf('<br>Скрипт выполнялся: %.2F сек.<br>', $time);
printf('Использовано оперативной памяти: %.2F МБ.<br>', $memoscript);

// Оптимизация таблиц
$query = "OPTIMIZE TABLE  `SC_products`";
$res = mysql_query($query) or die(mysql_error() . "<br>$query");


// Удаление временных файлов
removeDir($_SERVER['DOCUMENT_ROOT'] . '/upload/');

echo '<script language="javascript">
				document.getElementById("products").innerHTML="<div style=\"width:' . $percent . ';background-image:linear-gradient(to bottom, #5BFD76, #0EFF3E);\">' . $percent . '</div>";
				</script>';
echo("
<br></div>
  <div id='end'>Импорт завершен!</div>
");

$memoscript = memory_get_peak_usage(true) / (1024 * 1024);
$time = microtime(true) - $start5;
printf('<br>Скрипт выполнялся: %.2F сек.<br>', $time);
printf('Использовано оперативной памяти: %.2F МБ.<br>', $memoscript);
$time = microtime(true) - $start;
printf('<br>Скрипт выполнялся: %.2F сек.<br>', $time);
//--------- Функции --------- 
function ShowError($msg)
{
    return "<div style='color:red; font-size:16px;'>$msg</div>";
}

function removedir($directory)
{
    $dir = opendir($directory);
    while (($file = readdir($dir))) {
        if (is_file($directory . "/" . $file)) {
            unlink($directory . "/" . $file);
        } else if (is_dir($directory . "/" . $file) && ($file != ".") && ($file != "..")) {
            removedir($directory . "/" . $file);
        }
    }
    closedir($dir);
    return TRUE;
}

function GetValue($what, $table, $condition)
{
    $query = "SELECT $what FROM $table WHERE $condition LIMIT 1";
    $result = mysql_query($query) or die('Ошибка в запросе: ' . mysql_error() . '<br>' . $query);
    $row = mysql_fetch_row($result);
    return $row[0];
}

function DecodeCodepage($text)
{
    $s = mb_detect_encoding($text);
    $q = iconv($s, 'UTF-8', $text);
    return $q;
}

function rus2translit($string)
{
    $converter = array(
        'а' => 'a', 'б' => 'b', 'в' => 'v',
        'г' => 'g', 'д' => 'd', 'е' => 'e',
        'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
        'и' => 'i', 'й' => 'y', 'к' => 'k',
        'л' => 'l', 'м' => 'm', 'н' => 'n',
        'о' => 'o', 'п' => 'p', 'р' => 'r',
        'с' => 's', 'т' => 't', 'у' => 'u',
        'ф' => 'f', 'х' => 'h', 'ц' => 'c',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
        'ь' => '\'', 'ы' => 'y', 'ъ' => '\'',
        'э' => 'e', 'ю' => 'yu', 'я' => 'ya',

        'А' => 'A', 'Б' => 'B', 'В' => 'V',
        'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
        'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
        'И' => 'I', 'Й' => 'Y', 'К' => 'K',
        'Л' => 'L', 'М' => 'M', 'Н' => 'N',
        'О' => 'O', 'П' => 'P', 'Р' => 'R',
        'С' => 'S', 'Т' => 'T', 'У' => 'U',
        'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
        'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
        'Ь' => '\'', 'Ы' => 'Y', 'Ъ' => '\'',
        'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
    );
    return strtr($string, $converter);
}

function str2url($str)
{
    $str = rus2translit($str);
    $str = strtolower($str);
    $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
    $str = trim($str, "-");
    return $str;
}

?>
</body>
</html>