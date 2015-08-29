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
/* 
  $archive_dir = $_SERVER['DOCUMENT_ROOT'].'/upload/';
  $dest_dir = $_SERVER['DOCUMENT_ROOT'].'/popup/pics/';
  $zip = new ZipArchive();
  $fileName = $archive_dir."pics.zip";
  if ($zip->open($fileName) !== true)
    {
		echo("Error while openning archive file: ".$archive_dir."pics.zip");
		exit(1);
	}
  $zip->extractTo($dest_dir);
  
  echo("<div id='extract'>Файлы ($zip->numFiles) извлечены в папку ".$_SERVER['DOCUMENT_ROOT']."/temp/import.<br><br>"); */
  $src_dir   = $_SERVER['DOCUMENT_ROOT'].'/popup/rename/';
  $src_dir2  = $_SERVER['DOCUMENT_ROOT'].'/upload/';
  $filename = $src_dir.'rename.xls';
  $data = new Spreadsheet_Excel_Reader($filename);
  $rowcount = $data->rowcount();
  $no = 0;
  $erorr = 0;
  
  for ($row=1; $row<$rowcount+1; $row++)
    {
	  set_time_limit(0);
	  $no++;
	  $pics       = $data->val($row,'A');
	  $num        = $data->val($row,'B');
	  /* $num        = ($num > 0)?$num:0; */
	  $pics       = mysql_real_escape_string($pics);
	  /* $dopic      = ($num > 0)?substr($pics, 0, -2):$pics;
	  $pics_thm   = $pics.'_thm.jpg';
	  $pics_enl   = $pics.'_enl.jpg';*/
	  $pics    = $src_dir.$pics.'.jpg';
	 /* $file_name  = $src_dir.$picture;
      $file_name2 = $src_dir2.$picture;
	  $file_name  = is_file($file_name)?$file_name:$file_name2; */
	  $num       = mysql_real_escape_string($num);
	  $num    = DIR_PRODUCTS_PICTURES.'/'.$num;
//$data_file = "datal.txt";
// $file_name2 = DIR_PRODUCTS_PICTURES.'/'.$pics_thm;
echo("Строка #$no => "); 
if (!copy($num, $pics))  {
    echo("<span style='color: #E9967A;'>$num - файл не найден!<br></span>");
	 $erorr++;
}


/* 	  if (!is_file($file_name)) 
		    {
				echo("<span style='color: #E9967A;'>$picture - файл не найден!<br></span>");
				$erorr++;
		    }
	  else
		    {
				$file_name2 = DIR_PRODUCTS_PICTURES.'/'.$pics_thm;
				unlink($file_name2);
				make_thumbnail($file_name, $file_name2, false, false, 200);
				$file_name2 = DIR_PRODUCTS_PICTURES.'/'.$picture;
				unlink($file_name2);
				make_thumbnail($file_name, $file_name2, false, false, 400);
				$file_name2 = DIR_PRODUCTS_PICTURES.'/'.$pics_enl;
				unlink($file_name2);
				make_thumbnail($file_name, $file_name2, false, false, 600);
				unlink($file_name);
				
				echo("Строка $picture => ");

				$productID = GetValue('productID', 'SC_products', "code_1c = $dopic");	
				$pictureID = GetValue('default_picture', 'SC_products', "code_1c = $dopic");
				$query = "DELETE FROM `SC_product_pictures` WHERE `filename`='$picture' AND `productID`!=$productID";
				$res   = mysql_query($query) or die(mysql_error()."<br>$query");				
				$pid       = GetValue('PhotoID', 'SC_product_pictures', "filename = '$picture'");
				
				if($pid)
					{
						$query = "UPDATE SC_product_pictures
								  SET 
								  priority = $num
								  WHERE filename = '$picture'";
						$res   = mysql_query($query) or die(mysql_error()."<br>$query");
					}
				else
					{
						$query = "INSERT INTO SC_product_pictures
								 (productID , filename, thumbnail, enlarged, priority)
								 VALUES ($productID, '$picture', '$pics_thm', '$pics_enl', $num)";
						$res   = mysql_query($query) or die(mysql_error()."<br>$query");
						$pid   = mysql_insert_id();
					}
					
				if($num == 0 || $pictureID == '')
					{
						$query = "UPDATE SC_products SET default_picture = $pid 
								  WHERE productID = $productID";
						$res   = mysql_query($query) or die(mysql_error()."<br>$query");										
					} 
					
				echo("Строка #$no => ");
			}*/	 
	}
/*   $query = "OPTIMIZE TABLE `SC_products`, `SC_product_pictures`";
  $res   = mysql_query($query) or die(mysql_error()."<br>$query");
  $pics = $no - $erorr; */
  $done=$no-$erorr;
  echo('<span style="color:blue;"><br>Обработано '.$done.' изображений</span>');
  


   function err($msg)
  {
    return "<span style='color:red; font-size:16px;'>$msg</span>";
  }

  function make_thumbnail($file_name, $fileout, $thumb_width, $thumb_height, $max_size)
  {
    $image_info = getimagesize($file_name);
    switch ($image_info['mime'])
    {
      case 'image/gif':  if (imagetypes() & IMG_GIF) {$image = imagecreatefromGIF($file_name); }
                                                else {$err_str = 'GD не поддерживает GIF';}
                         break;
      case 'image/jpeg': if (imagetypes() & IMG_JPG) {$image = imagecreatefromJPEG($file_name);}
                                                else {$err_str = 'GD не поддерживает JPEG';}
                         break;
      case 'image/png':  if (imagetypes() & IMG_PNG) {$image = imagecreatefromPNG($file_name);}
                                                else {$err_str = 'GD не поддерживает PNG';}
                         break;
      default: $err_str = 'GD не поддерживает ' . $image_info['mime'];
    }
    if (isset($err_str)) {return $err_str;}

    putenv('GDFONTPATH=' . realpath('.'));
    $watermark = new watermark1();

    $image=$watermark->create_watermark($image, "MultiToys.com.ua", $_SERVER['DOCUMENT_ROOT']."/comicbd.ttf",0,100,255,100);
    //$image=$watermark->create_watermark($image, "MultiToys.com.ua", "arial.ttf",0,0,255,100);

    $image_width = imagesx($image);
    $image_height = imagesy($image);

    //задано ограничение на высоту и ширину:
    if ($max_size)
    {
      if ($image_width < $image_height)
      {
        $thumb_height = min(array($max_size, $image_height));
        $thumb_width  = min(array(round($max_size * $image_width / $image_height), $image_width));
      }
      else
      {
        $thumb_width = min(array($max_size, $image_width));
        $thumb_height = min(array(round($max_size * $image_height / $image_width), $image_height));
      }
    }

    //задана только ширина
    elseif ($thumb_width && !$thumb_height)
    {
      $thumb_height = round($thumb_width * $image_height / $image_width);
    }
    //задана только высота
    elseif (!$thumb_width && $thumb_height)
    {
      $thumb_width = round($thumb_height * $image_width / $image_height);
    }

    //не задан ни один из размеров
    else
    {
      $thumb_width = $image_width;
      $thumb_height = $image_height;
    }

    $thumb = imagecreatetruecolor($thumb_width, $thumb_height);
    imagealphablending($thumb, false);
    imagesavealpha($thumb, true);

    imagecopyresampled($thumb, $image, 0, 0, 0, 0, $thumb_width, $thumb_height, $image_width, $image_height);

    imagejpeg($thumb, $fileout);

    //освобождаем память
    imagedestroy($image);
    imagedestroy($thumb);
  }

  class watermark1
  {
    function create_watermark( $main_img_obj, $text, $font, $r = 128, $g = 128, $b = 128, $alpha_level = 100 )
    {
      $width = imagesx($main_img_obj);
      $height = imagesy($main_img_obj);
      $angle =  -rad2deg(atan2((-$height),($width)));

      $text = " ".$text." ";

      $c = imagecolorallocatealpha($main_img_obj, $r, $g, $b, $alpha_level);
      $size = (($width+$height)/2)*2/strlen($text);
      $box  = imagettfbbox ( $size, $angle, $font, $text );
      $x = $width/2 - abs($box[4] - $box[0])/2;
      $y = $height/2 + abs($box[5] + $box[1])/2;

      if (!imagettftext($main_img_obj,$size ,$angle, $x, $y, $c, $font, $text)) {echo('<span style="color:red;">error => </span>');}
      return $main_img_obj;
    }
  } 
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
?>
<div id='end' style="text-align:center;  font-size:36px; color:green">Импорт завершен!</div>
	
</body>
</html>