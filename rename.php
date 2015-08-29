<?php
    //$start = microtime(true);
    ini_set('display_errors', true);
    define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT']."/published/SC/html/scripts");
    $DebugMode = false;
    $Warnings = array();
    include_once(DIR_ROOT.'/includes/init.php');
    include_once(DIR_CFG.'/connect.inc.wa.php');
    include(DIR_FUNC.'/setting_functions.php' );

    echo("
			<html>
				<head>
					<link rel='stylesheet' type='text/css' href='css/import.css'>
				</head>
				<body>
	   ");

    $archive_dir = $_SERVER['DOCUMENT_ROOT'].'/upload/';
    $dest_dir = $_SERVER['DOCUMENT_ROOT'].'/temp/import/';

    $zip = new ZipArchive();
    $fileName = $archive_dir."rename.zip";

    if ($zip->open($fileName) !== true)
    {
        echo("Error while openning archive file: rename.zip");
        exit(1);
    }

    if ($zip->extractTo($dest_dir) !== true) {
        echo("Error while extracting archive file: rename.zip");
        exit(1);
    }

    echo("<div id='extract'>Файлы ($zip->numFiles) успешно извлечены!<br><br>");

    $renamed_dir  = $_SERVER['DOCUMENT_ROOT'].'/popup/rename/';

    $filename = $dest_dir.'rename.csv';

    $file = file($filename);
    $rowcount = count($file);
    echo $rowcount;
    if (!$rowcount) die(ShowError("CSV-файл ($filename) не содержит данных! (rowcount = $rowcount)"));
    if (($handle = fopen($filename, "r")) !== FALSE) {
        echo ("
				Импорт фотографий ...<hr>
				<div id='products' >
					<div style='width:0px;'>&nbsp;</div>
				</div>
			 ");
        $row = 0;
        $percent = 0;
        $ext = '.jpg';

        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            set_time_limit(0);
            $no = 0;
            //$row++;
            $code1c     = $data[0];
            $urls       = explode('|', $data[1]);
            //$data = array('53209', 'http://www.top.dp.ua/img/p/2/7/5/3/6/27536.jpg|http://www.top.dp.ua/img/p/2/7/5/3/7/27537.jpg|http://www.top.dp.ua/img/p/2/7/5/3/8/27538.jpg|http://www.top.dp.ua/img/p/2/7/5/3/9/27539.jpg|http://www.top.dp.ua/img/p/2/7/5/4/0/27540.jpg');
            $code1c     = $data[0];
            $urls       = explode('|', $data[1]);


            foreach ($urls as $url) {

                $dopic      = ($no > 0)?'-'.$no : '';
                $slash = strrpos ( $url, '/');
                //echo $slash.'<br>';
                $slash++;
                $pic_name = substr($url, $slash);
                //echo $pic_name.'<br>';
                $pic = str_replace($ext, '', $pic_name);

                $file_name_0 = $dest_dir.$pic_name;
                //echo $file_name_0.'<br>';
                $file_name_1 = $renamed_dir.$code1c.$dopic.$ext;

                //if (is_file($file_name_0)) {
                //    rename($file_name_0, $file_name_1);
                    echo $code1c.'-'.$no.'<br>';
                $no++;
                //}
            }
            //$num        = ($num > 0)?$num:0;
            //// $pics       = mysql_real_escape_string($pics);
            //$dopic      = ($num > 0)?substr($pics, 0, -2):$pics;
            //$pics_thm   = $pics.'_thm.jpg';
            //$pics_enl   = $pics.'_enl.jpg';
            //$picture    = $pics.'.jpg';
            //$file_name  = $dest_dir.$picture;
            //$file_name2 = $archive_dir.$picture;
            //$file_name  = is_file($file_name)?$file_name:$file_name2;
            //$stamp200 = false;
            //$stamp400 = $watermark_dir.'watermark400.png';
            //$stamp600 = $watermark_dir.'watermark600.png';
            //
            //if (!is_file($file_name))
            //{
            //    echo("<span style='color: #E9967A;'>$picture - файл не найден! (строка $row)<br></span>");
            //    $erorr++;
            //}
            //else
            //{
            //    $file_name2 = DIR_PRODUCTS_PICTURES.'/'.$pics_thm;
            //    unlink($file_name2);
            //    make_thumbnail($file_name, $file_name2, $stamp200, 160);
            //
            //    $file_name2 = DIR_PRODUCTS_PICTURES.'/'.$picture;
            //    unlink($file_name2);
            //    make_thumbnail($file_name, $file_name2, $stamp400, 400);
            //
            //    $file_name2 = DIR_PRODUCTS_PICTURES.'/'.$pics_enl;
            //    unlink($file_name2);
            //    make_thumbnail($file_name, $file_name2, $stamp600, 600);
            //
            //    unlink($file_name);
            //
            //    $productID = GetValue('productID', 'SC_products', "code_1c = $dopic");
            //
            //    if(!$productID)
            //    {
            //        echo("<span style='color: #FF8000;'>$picture - товар не найден!<br></span>");
            //        $erorr++;
            //    }
            //    else
            //    {
            //
            //        $pictureID = GetValue('default_picture', 'SC_products', "code_1c = $dopic");
            //        $query = "DELETE FROM `SC_product_pictures` WHERE `filename`='$picture' AND `productID`!=$productID";
            //        $res   = mysql_query($query) or die(mysql_error()."<br>$query");
            //        $pid       = GetValue('PhotoID', 'SC_product_pictures', "filename = '$picture'");
            //
            //        if($pid)
            //        {
            //            $query = "UPDATE SC_product_pictures
				//									SET
				//									priority = $num
				//									WHERE filename = '$picture'";
            //            $res   = mysql_query($query) or die(mysql_error()."<br>$query");
            //        }
            //        else
            //        {
            //            $query = "INSERT INTO SC_product_pictures
				//								 (productID , filename, thumbnail, enlarged, priority)
				//								 VALUES ($productID, '$picture', '$pics_thm', '$pics_enl', $num)";
            //            $res   = mysql_query($query) or die(mysql_error()."<br>$query");
            //            $pid   = mysql_insert_id();
            //        }
            //
            //        if($num == 0 || $pictureID == '')
            //        {
            //            $query = "UPDATE SC_products SET default_picture = $pid
				//									WHERE productID = $productID";
            //            $res   = mysql_query($query) or die(mysql_error()."<br>$query");
            //        }
            //
            //        $progress = round(($no/($rowcount) * 100), 0, PHP_ROUND_HALF_DOWN);
            //        if ($progress > $percent) {
            //            $percent = $progress."%";
            //            ProgressBar ('products', $percent, $start);
            //            BuferOut(100000);
            //        }
            //    }
            //}
        }
    fclose($handle);
    }
//    $pics = $no - $erorr;
//    echo('<span style="color:blue;"><br>Обработано '.$pics.' изображений</span>');
//
//    $query = "OPTIMIZE TABLE `SC_products`, `SC_product_pictures`";
//    $res   = mysql_query($query) or die(mysql_error()."<br>$query");
//    mysql_close();
//    removeDir($dest_dir);
//
//    ProgressBar ('products', $percent, false, true);
//    echo("
//<br></div>
//  <div id='end'>Импорт завершен!</div>
//");
//    debugging ($start);

    // Функции
    function GetValue($what, $table, $condition)
    {
        $query = "SELECT $what FROM $table WHERE $condition LIMIT 1";
        $result = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);
        $row = mysql_fetch_row($result);
        return $row[0];
    }

    function ProgressBar($import_items, $percent, $start=false, $full=false) {
        if ($start) {
            $time = round(((100-$percent)*(microtime(true) - $start)/$percent), 0);
            // $end = printf('&nbsp;&nbsp;&nbsp;Осталось: %.2F сек.', $time);
            //$end = ' - <small>Осталось: '.$time.' сек.</small>';
        }
        $full = ($full)?'background-image:linear-gradient(to bottom, #6AFF7D, #00DC08);':'';
        echo '<script language="javascript">
				document.getElementById("'.$import_items.'").innerHTML="<div style=\"width:'.$percent.';'.$full.'\">'.$percent.''.$end.'</div>";
				</script>';
        return;
    }

    function BuferOut($delay=0) {
        echo str_repeat(' ',1024*64);
        flush();
        usleep($delay);
        return;
    }

    function Debugging($start) {
        // $memoscript = memory_get_usage(true)/1048576;
        $memoscript_peak = memory_get_peak_usage(true)/1048576;
        $time = microtime(true) - $start;
        printf('<br>Скрипт выполнялся: %.2F сек.', $time);
        // printf('<br>Использовано оперативной памяти: %.2F МБ.', $memoscript);
        printf('<br>Пик оперативной памяти: %.2F МБ.', $memoscript_peak);
        return;
    }