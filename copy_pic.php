<?php
  $start = microtime(true);
  ini_set('display_errors', true);
  define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'].'/published/SC/html/scripts');
  $DebugMode = false;
  $Warnings = array();
  include_once(DIR_ROOT.'/includes/init.php');
  include_once(DIR_CFG.'/connect.inc.wa.php');
  include(DIR_FUNC.'/setting_functions.php' );
  $DB_tree = new DataBase();
  $DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));
  $DB_tree->selectDB(SystemSettings::get('DB_NAME'));
  define('VAR_DBHANDLER','DBHandler');
  define('NEW_PRODUCTS_PICTURES',$_SERVER['DOCUMENT_ROOT'].'/products_pictures');
  
  echo'
        <html>
            <head>
                <link rel=stylesheet type=text/css href=css/import.css>
            </head>
        <body>
      ';
	
////	$fp = fopen('no_foto.txt', 'w');
//	
//	$query = 'SELECT * 
//						FROM SC_products
//						LEFT JOIN SC_product_pictures  
//						ON SC_product_pictures.photoID = SC_products.default_picture
//						WHERE enabled=1 AND in_stock != 0
//						ORDER BY code_1c ASC';
//	$res   = mysql_query($query) or die(mysql_error()."<br>$query");
//	
//	$no=0;
//	$erorr=0;
//
////	fwrite($fp, "Товары из 1С\n\nКод 1С;Артикул;Наименование;ID товара;ID фото;Фото;Описание ошибки;\n");
//	echo '<br/>Товары из 1С<br/><br/>';
//	
//	while($row = mysql_fetch_object($res)) {
//		
//		$no++;
//		$erorr_type = '';
//		
//		if (!$row->default_picture) {
//			$erorr_type = 'Фото не назначено';
//		}
//		elseif ($row->filename !== $row->code_1c.'.jpg') {
//			$erorr_type = 'Фото не соответствует';	
//		}
//		elseif (!is_file(DIR_PRODUCTS_PICTURES.'/'.$row->filename)) {
//			$erorr_type = 'Отсутствует файл фотографии';		
//		}
//		if ($erorr_type) {
//			$erorr++;
////			fwrite($fp, $row->code_1c.';'.$row->product_code.';'.$row->name_ru.';'.$row->productID.';'.$row->photoID.';'.$row->filename.';'.$erorr_type.";\n");
//			echo $erorr.')&nbsp;&nbsp;'.$row->code_1c.'&nbsp;&nbsp;['.$row->product_code.']&nbsp;&nbsp;'.$row->name_ru.'&nbsp;&nbsp;'.$row->filename.'&nbsp;-&nbsp;'.$erorr_type.'<br/><br/>';
//		}
//	}
//	mysql_free_result($query);
	
	$query = 'SELECT *
						FROM SC_products
						LEFT JOIN SC_product_pictures
						ON SC_product_pictures.photoID = SC_products.default_picture
						WHERE enabled=1 AND in_stock = 0
						ORDER BY name_ru ASC';
	$res   = mysql_query($query) or die(mysql_error()."<br>$query");
	
//	fwrite($fp, "\n\nCastorland\n\nID товара;Артикул;Наименование;Описание ошибки;\n");
	echo '<br/>Castorland<br/>';
	
	while($row = mysql_fetch_object($res)) {
		
		$no++;
		$erorr_type = '';
		
		if (!$row->default_picture) {
			$erorr_type = 'Фото не назначено';
		} elseif (!is_file(DIR_PRODUCTS_PICTURES.'/'.$row->filename)) {
			$erorr_type = 'Отсутствует файл фотографии';		
		} else {
			$filename = DIR_PRODUCTS_PICTURES.'/'.$row->filename;
			$thumbnail = DIR_PRODUCTS_PICTURES.'/'.$row->thumbnail;
			$enlarged = DIR_PRODUCTS_PICTURES.'/'.$row->enlarged;
            
			$filename_new = NEW_PRODUCTS_PICTURES.'/'.$row->filename;
			$thumbnail_new = NEW_PRODUCTS_PICTURES.'/'.$row->thumbnail;
			$enlarged_new = NEW_PRODUCTS_PICTURES.'/'.$row->enlarged;

            if (!copy($filename, $filename_new)) {
                echo "не удалось скопировать $filename...\n";
            }
            if (!copy($thumbnail, $thumbnail_new)) {
                echo "не удалось скопировать $thumbnail...\n";
            }
            if (!copy($enlarged, $enlarged_new)) {
                echo "не удалось скопировать $enlarged...\n";
            }
			
		}
		if ($erorr_type) {
			$erorr++;
//			fwrite($fp, $row->productID.';'.$row->product_code.';'.$row->name_ru.';'.$erorr_type.";\n");
			echo $no.') ['.$row->product_code.'] '.$row->name_ru.' - '.$erorr_type.'<br/>';
		}
	}
//	mysql_free_result($query);
//	fclose($fp);

	echo ("<br/>Из $no товаров у  $erorr проблемы с фото<br/>");
	$time = microtime(true) - $start;
	$memoscript_peak = memory_get_peak_usage(true)/1048576;
	printf('<br>Скрипт выполнялся: %.2F сек.<br>', $time);
	printf('Использовано оперативной памяти: %.2F МБ.<br>', $memoscript_peak);
	echo ("<div id='end_check' style='text-align:center;  font-size:36px; color:green'>Проверка окончена!</div>");