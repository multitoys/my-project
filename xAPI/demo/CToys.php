<?php
/*
PHP Class 
from work Toys API JSON
Created by Oleg Abramov
email: jktu@ua.fm
*/

class Toys
{
  static $login="sales";
  static $pass="2405";
  static $type="json";
  static $query="";
  static $token;
 
  
  static function GetToken()
  {
     if(self::$token=="")
	 {
	   self::$token=md5(self::$login."_".base64_encode(self::$pass));
	 }
	 return self::$token;
  }
  
  static function GetURL($url)
  {
    $token=self::GetToken();
	$url.="&type=".self::$type."&cid=".$token;
	self::$query=$url;
	$data=file_get_contents($url);
	return $data;
  }
  
  static function getCategory($id=1)
  {
    $url="http://toysi.com.ua/xAPI/index.php?mod=GCAT&idcat={$id}";
	$aCat=json_decode(self::GetURL($url));
	
	return $aCat;
  }
  
  static function getTovar($id=1)
  {
    $url="http://toysi.com.ua/xAPI/index.php?mod=GTOV&idcat={$id}";
	$aTov=json_decode(self::GetURL($url));
	
	return $aTov;
  }
  
  
  static function getAll($id=1)
  {
    $url="http://toysi.com.ua/xAPI/index.php?mod=GCAT&idcat={$id}";
	$aCat=json_decode(self::GetURL($url));
	if($aCat!="")
	for($i=0;$i<count($aCat);$i++)
	{
	  $aCat[$i]->c_cat=getAll($aCat[$i]->categoryID);
	}
	
	return $aCat;
  }
  
  
}
?>