<?php

  ini_set('display_errors', true);
  define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT']."/published/SC/html/scripts");
  $DebugMode = false;
  $Warnings = array();
  include_once(DIR_ROOT.'/includes/init.php');
  include_once(DIR_CFG.'/connect.inc.wa.php');
  include(DIR_FUNC.'/setting_functions.php' );
  $DB_tree = new DataBase();
  $DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));

  $DB_tree->selectDB(SystemSettings::get('DB_NAME'));
  define('VAR_DBHANDLER','DBHandler');

  require_once($_SERVER['DOCUMENT_ROOT'].'/includes/excel_reader2.php');
  
  echo("
<html>
<head>
   <link href='css/jquery-ui.css' rel='stylesheet' type='text/css'>
   <SCRIPT src='zjs/jquery-1.4.2.min.js' type='text/javascript'></script>
   <script src='zjs/jquery-ui.min.js' type='text/javascript'></script>
</head>
<body>
");


  // Блокировка корзины
 // $query = "UPDATE SC_import_lock set `lock` = 1"; 
 // $res   = mysql_query($query) or die(mysql_error()."<br>$query");
  
removeDir($_SERVER['DOCUMENT_ROOT'].'/temp/import/');

  // Распаковка архива
  $archive_dir = $_SERVER['DOCUMENT_ROOT'].'/upload/';
  $dest_dir = $_SERVER['DOCUMENT_ROOT'].'/temp/import/';
  $zip = new ZipArchive();
  $fileName = $archive_dir."cast.zip";
  if ($zip->open($fileName) !== true)
  {
    echo("Error while openning archive file: ".$archive_dir."cast.zip");
    exit(1);
  }
  $zip->extractTo($dest_dir);
  echo("<div id='extract'>Файлы ($zip->numFiles) извлечены в папку ".$_SERVER['DOCUMENT_ROOT']."/temp/import.<br><br>");

  // Очистка базы от старой информации 
  
  $query = "DELETE FROM SC_categories WHERE allow_products_comparison = 0";
  $res   = mysql_query($query) or die(mysql_error()."<br>$query");
  
  $query = "DELETE FROM SC_products WHERE in_stock = 0";
  $res   = mysql_query($query) or die(mysql_error()."<br>$query");   
  
  // Импорт категорий
  echo('Импорт категорий ...<br>');
  $filename = $dest_dir.'castor_cat.xls';
  $data = new Spreadsheet_Excel_Reader($filename);
  $rowcount = $data->rowcount();
  if ($rowcount < 1) {die(ShowError('XLS-файл не содержит данных!'));}
  $colcount = $data->colcount();
  if ($colcount < 3) {die(ShowError('Структура XLS-файла не соответствует требованиям! Количество столбцов меньше трех!'));}
  $no = 0;
  for ($row=2; $row<$rowcount+1; $row++)
  {
    set_time_limit(0);
    $no++;
    $id     = $data->val($row,'A');
    // $parent = ($parent == 'NULL')?1:$parent;
    $parent = ($parent)?1:$parent;
    $parent = $data->val($row,'B');
    $name   = DecodeCodepage($data->val($row,'C'));
    $name   = mysql_real_escape_string($name);
	 $sort   = $data->val($row,'D');
	 $slug = str2url("$name");	 

    $query = "INSERT INTO SC_categories (slug, categoryID, parent, name_ru, allow_products_comparison, show_subcategories_products, sort_order) VALUES ('$slug', $id, $parent, '$name', 0, 1, $sort)";
    $res   = mysql_query($query) or die(mysql_error()."<br>$query");

  }  
	echo('<span style="color:blue;">Обработано '.$no.' категорий<br></span>');
  
  // Импорт товаров
  echo('Импорт товаров ...<br>');
  $filename = $dest_dir.'castor_pr.xls';
  $data = new Spreadsheet_Excel_Reader($filename);
  $rowcount = $data->rowcount();
  if ($rowcount < 2) {die(ShowError("XLS-файл ($filename) не содержит данных! (rowcount = $rowcount)"));}
  $colcount = $data->colcount();
  if ($colcount < 3) {die(ShowError('Структура XLS-файла не соответствует требованиям! Количество столбцов меньше шестнадцати!'));}
  $no = 0;
  $erorr = 0;

  for ($row=2; $row<$rowcount+1; $row++)
  {
    set_time_limit(0);

    $no++;
    $code         = $data->val($row,'A');
    $catid        = $data->val($row,'B');
    $price        = $data->val($row,'C');
    $special_price= $data->val($row,'D');
    $name         = DecodeCodepage($data->val($row,'E'));
	 $id           = $data->val($row,'F');
	 
	 
    $catid     	= is_numeric($catid)?$catid:1;
    $name       	= mysql_real_escape_string($name);
    $code       	= mysql_real_escape_string($code);
    $special_price= is_numeric($special_price)?$special_price:0;
	 $slug = 'p-'.$code.'-'.str2url("$name");
	 
    // Исправление цены
    if (!is_numeric($price))
    {
      $price = preg_replace('/[^0-9.]/', '', $price);
    }
    // Исправление спец цены
    if (!is_numeric($special_price))
    {
      $special_price = preg_replace('/[^0-9.]/', '', $special_price);
    }
         $query = "
INSERT INTO SC_products
       (categoryID, Price,  SpecialPrice  , in_stock, enabled, product_code, ordering_available, slug   , name_ru, skidka ,  ostatok  ,   minorder, code_1c)
VALUES ($catid    , $price, $special_price, 0       ,       1, '$code'     , 1                 , '$slug', '$name',  0     , '100'     ,          1, '$id'  )"; 
			$res   = mysql_query($query) or die(mysql_error()."<br>$query");

		echo(" #$no => ");
 
  }
  echo('<span style="color:blue;"><br>Обработано '.$no.' товаров</span>');

$query = "UPDATE SC_products SET `min_order_amount` = `box` WHERE `zakaz` = 1 OR `minorder` = 1";
$res   = mysql_query($query) or die(mysql_error()."<br>$query");

$query = "DELETE FROM `SC_product_pictures` WHERE `filename`=''";
$res   = mysql_query($query) or die(mysql_error()."<br>$query");

  // Оптимизация таблиц
  
$query = "OPTIMIZE TABLE `SC_auth_log`, `SC_categories`, `SC_category_product`, `SC_currency_types`, `SC_customers`, `SC_customer_addresses`, `SC_customer_reg_fields_values`, `SC_ordered_carts`, `SC_orders`, `SC_order_status_changelog`, `SC_products`, `SC_product_list_item`, `SC_product_pictures`, `SC_shopping_carts`, `SC_shopping_cart_items`, `SC_subscribers`";
$res   = mysql_query($query) or die(mysql_error()."<br>$query");

  // Разблокировка корзины
  
//$query = "UPDATE SC_import_lock set `lock` = 0";
//$res   = mysql_query($query) or die(mysql_error()."<br>$query");

  //Конвертация изображений (анимация)
  
   echo("
<br></div>
  <div id='end' style='display:none; text-align:center;  font-size:36px; color:green'>Импорт завершен!</div>
  <div id='msg' style='color:green; font-size:9px; width:100%;'></div>
");

$usd = 1/$usd;
echo('<span style="color:blue;"><br>курс доллара - '.$usd.' грн</span>'); 

// Функции

function ShowError($msg)
  {
    return "<div style='color:red; font-size:16px;'>$msg</div>";
  }


function removedir ($directory)
  {
    $dir = opendir($directory);
    while(($file = readdir($dir)))
    {
      if ( is_file ($directory."/".$file))
      {
        unlink ($directory."/".$file);
      }
      else if ( is_dir ($directory."/".$file)&&($file != ".")&&($file != ".."))
      {
        removedir ($directory."/".$file);
      }
    }
    closedir ($dir);
    return TRUE;
  }

function GetValue($what, $table, $condition)
  {
    $query = "SELECT $what FROM $table WHERE $condition LIMIT 1";
    $result = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);
    $row = mysql_fetch_row($result);
    return $row[0];
  }

function DecodeCodepage($text){
  $s = mb_detect_encoding($text);//Определяем кодировку
  $q = iconv($s, 'UTF-8', $text);//Декодируем
  return $q;
}

function DecodeCodepage1251($text){
  $s = 'windows-1251';
  $q = iconv($s, 'UTF-8', $text);//Декодируем
  return $q;
}

function rus2translit($string) {
    $converter = array(
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
    );
    return strtr($string, $converter);
}

function str2url($str) {
    // переводим в транслит
    $str = rus2translit($str);
    // в нижний регистр
    $str = strtolower($str);
    // заменям все ненужное нам на "-"
    $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
    // удаляем начальные и конечные '-'
    $str = trim($str, "-");
    return $str;
}

// Удаление временных файлов
	removeDir($_SERVER['DOCUMENT_ROOT'].'/upload/');
	//removeDir($_SERVER['DOCUMENT_ROOT'].'/temp/import/');

?>
</body>
</html>