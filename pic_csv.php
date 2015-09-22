<?php
    $start = microtime(true);

    if ($zip->extractTo($dest_dir) !== true) {
        echo("No archive file: pics.zip");
        exit(1);
    }

    echo("<div id='extract'>Файлы ($zip->numFiles) успешно извлечены!<br><br>");

    $watermark_dir = $_SERVER['DOCUMENT_ROOT'].'/img/';
    $searh_dir = $_SERVER['DOCUMENT_ROOT'].'/published/publicdata/MULTITOYS/attachments/SC/search_pictures/';

    $filename = $dest_dir.'pics.csv';

    $file = file($filename);
    $rowcount = count($file);
    echo $rowcount;
    if (!$rowcount) die(ShowError("CSV-файл ($filename) не содержит данных! (rowcount = $rowcount)"));
    if (($handle = fopen($filename, "r")) !== false) {
        echo("
				Импорт фотографий ...<hr>
				<div id='products' >
					<div style='width:0px;'>&nbsp;</div>
				</div>
			 ");
        $no = 0;
        $row = 0;
        $percent = 0;
        $erorr = 0;

        while (($data = fgetcsv($handle, 25, ";")) !== false) {
            set_time_limit(0);
            $no++;
            $row++;
            $pics = $data[0];
            $num = $data[1];
            $num = ($num > 0)?$num:0;
            // $pics       = mysql_real_escape_string($pics);
            $dopic = $pics;
            if ($num > 9) {
                $dopic = substr($pics, 0, -3);
            } elseif ($num > 0) {
                $dopic = substr($pics, 0, -2);
            }
            //$dopic      = ($num > 0)?substr($pics, 0, -2):$pics;
            $pics_search = $pics.'_s.jpg';
            $pics_thm = $pics.'_thm.jpg';
            $pics_enl = $pics.'_enl.jpg';
            $picture = $pics.'.jpg';
            $file_name = $dest_dir.$picture;
            $file_name2 = $archive_dir.$picture;
            $file_name = is_file($file_name)?$file_name:$file_name2;
            $stamp200 = false;
            $stamp400 = $watermark_dir.'watermark400.png';
            $stamp600 = $watermark_dir.'watermark600.png';

            if (!is_file($file_name)) {
                echo("<span style='color: #E9967A;'>$picture - файл не найден! (строка $row)<br></span>");
                $erorr++;
            } else {
                $file_name2 = $searh_dir.$pics_search;
                unlink($file_name2);
                make_thumbnail($file_name, $file_name2, $stamp200, 80);

                $file_name2 = DIR_PRODUCTS_PICTURES.'/'.$pics_thm;
                unlink($file_name2);
                make_thumbnail($file_name, $file_name2, $stamp200, 160);

                $file_name2 = DIR_PRODUCTS_PICTURES.'/'.$picture;
                unlink($file_name2);
                make_thumbnail($file_name, $file_name2, $stamp400, 400);

                $file_name2 = DIR_PRODUCTS_PICTURES.'/'.$pics_enl;
                unlink($file_name2);
                make_thumbnail($file_name, $file_name2, $stamp600, 600, 90);

                unlink($file_name);

                $productID = GetValue('productID', 'SC_products', "code_1c = $dopic");

                if (!$productID) {
                    echo("<span style='color: #FF8000;'>$picture - товар не найден!<br></span>");
                    $erorr++;
                } else {

                    $pictureID = GetValue('default_picture', 'SC_products', "code_1c = $dopic");
                    $query = "DELETE FROM `SC_product_pictures` WHERE `filename`='$picture' AND `productID`!=$productID";
                    $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    $pid = GetValue('PhotoID', 'SC_product_pictures', "filename = '$picture'");

                    if ($pid) {
                        $query = "UPDATE SC_product_pictures
													SET 
													priority = $num
													WHERE filename = '$picture'";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    } else {
                        $query = "INSERT INTO SC_product_pictures
												 (productID , filename, thumbnail, enlarged, priority)
												 VALUES ($productID, '$picture', '$pics_thm', '$pics_enl', $num)";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                        $pid = mysql_insert_id();
                    }

                    if ($num == 0 || $pictureID == '') {
                        $query = "UPDATE SC_products SET default_picture = $pid 
													WHERE productID = $productID";
                        $res = mysql_query($query) or die(mysql_error()."<br>$query");
                    }

                    $progress = round(($no / ($rowcount) * 100), 0, PHP_ROUND_HALF_DOWN);
                    if ($progress > $percent) {
                        $percent = $progress."%";
                        ProgressBar('products', $percent, $start);
                        BuferOut(100000);
                    }
                }
            }
        }
        fclose($handle);
    }
    $pics = $no - $erorr;
    echo('<span style="color:blue;"><br>Обработано '.$pics.' изображений</span>');

    $query = "OPTIMIZE TABLE `SC_products`, `SC_product_pictures`";
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    mysql_close();
    removeDir($dest_dir);

    ProgressBar('products', $percent, false, true);
    echo("
<br></div>
  <div id='end'>Импорт завершен!</div>
");
    debugging($start);

    // Функции
    function make_thumbnail($file_name, $fileout, $stamp, $max_size, $quality = 85)
    {
        $image_info = getimagesize($file_name);
        $image = imagecreatefromJPEG($file_name);

        $image_width = imagesx($image);
        $image_height = imagesy($image);

        //задано ограничение на высоту и ширину:
        if ($max_size) {
            if ($image_width < $image_height) {
                $thumb_height = min(array($max_size, $image_height));
                $thumb_width = min(array(round($max_size * $image_width / $image_height), $image_width));
            } else {
                $thumb_width = min(array($max_size, $image_width));
                $thumb_height = min(array(round($max_size * $image_height / $image_width), $image_height));
            }
        } //задана только ширина
        elseif ($thumb_width && !$thumb_height) {
            $thumb_height = round($thumb_width * $image_height / $image_width);
        } //задана только высота
        elseif (!$thumb_width && $thumb_height) {
            $thumb_width = round($thumb_height * $image_width / $image_height);
        } //не задан ни один из размеров
        else {
            $thumb_width = $image_width;
            $thumb_height = $image_height;
        }

        $thumb = imagecreatetruecolor($thumb_width, $thumb_height);
        // imagealphablending($thumb, false);
        // imagesavealpha($thumb, true);
        imagecopyresampled($thumb, $image, 0, 0, 0, 0, $thumb_width, $thumb_height, $image_width, $image_height);
        //	 imagejpeg($thumb, $fileout,80);
        imagedestroy($image);
        //    imagedestroy($thumb);
        if ($stamp) {
            $stamp = imagecreatefrompng($stamp);
            $marge_right = 0;
            $marge_bottom = 0;
            $sx = imagesx($stamp);
            $sy = imagesy($stamp);
            // $im = imagecreatefromjpeg($main_img_obj);

            //		$image = imagecreatefromJPEG($fileout);
            imagecopy($thumb, $stamp, imagesx($thumb) - $sx - $marge_right, imagesy($thumb) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
        }
        imagejpeg($thumb, $fileout, $quality);
        // 		imagejpeg($thumb, $fileout);
        imagedestroy($thumb);
    }

    function GetValue($what, $table, $condition)
    {
        $query = "SELECT $what FROM $table WHERE $condition LIMIT 1";
        $result = mysql_query($query) or die('Ошибка в запросе: '.mysql_error().'<br>'.$query);
        $row = mysql_fetch_row($result);

        return $row[0];
    }

    function ProgressBar($import_items, $percent, $start = false, $full = false)
    {
        if ($start) {
            $time = round(((100 - $percent) * (microtime(true) - $start) / $percent), 0);
            // $end = printf('&nbsp;&nbsp;&nbsp;Осталось: %.2F сек.', $time);
            //$end = ' - <small>Осталось: '.$time.' сек.</small>';
        }
        $full = ($full)?'background-image:linear-gradient(to bottom, #6AFF7D, #00DC08);':'';
        echo '<script language="javascript">
				document.getElementById("'.$import_items.'").innerHTML="<div style=\"width:'.$percent.';'.$full.'\">'.$percent.''.$end.'</div>";
				</script>';

        return;
    }

    function BuferOut($delay = 0)
    {
        echo str_repeat(' ', 1024 * 64);
        flush();
        usleep($delay);

        return;
    }

    function Debugging($start)
    {
        // $memoscript = memory_get_usage(true)/1048576;
        $memoscript_peak = memory_get_peak_usage(true) / 1048576;
        $time = microtime(true) - $start;
        printf('<br>Скрипт выполнялся: %.2F сек.', $time);
        // printf('<br>Использовано оперативной памяти: %.2F МБ.', $memoscript);
        printf('<br>Пик оперативной памяти: %.2F МБ.', $memoscript_peak);

        return;
    }

    function removedir($directory)
    {
        $dir = opendir($directory);
        while (($file = readdir($dir))) {
            if (is_file($directory."/".$file)) {
                unlink($directory."/".$file);
            } else if (is_dir($directory."/".$file) && ($file != ".") && ($file != "..")) {
                removedir($directory."/".$file);
            }
        }
        closedir($dir);

        return true;
    }

?>
</body>
</html>