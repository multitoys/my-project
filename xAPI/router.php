<?

$MOD=@$_GET['mod'];
$TYPE=@$_GET['type'];
if($TYPE=="")$TYPE="json";

//$TOKEN=@$_GET['cid'];
$TOKEN= Toys::GetToken();

/*Защита токеном*/
if(!Api::TestToken($TOKEN))
{  echo json_encode(array("error"=>"token no sucsses")); die();}


switch($MOD)
{
  /*
  получение категорий
  */
  case 'GCAT': 
    $idcat=(int)@$_GET['idcat'];
	if($idcat<1)$idcat=1;
    $Cat=Api::GetCategory($idcat);
	Api::OutFormat($Cat,$TYPE,'cat');
  break; 
 
  case 'GTOV': 
    $idcat=(int)@$_GET['idcat'];
	if($idcat<1)$idcat=1;
    $Cat=Api::GetProductByCat($idcat);
	Api::OutFormat($Cat,$TYPE,'tov');
  break; 
  
  case 'GTOVA':
      $fname="cahe/".date("Y-m-d")."_".((int)(date("H")/4)).".".$TYPE;
	  
	  if((file_exists($fname)))
	  {
	   header("Content-Type: text/".$TYPE);
	   header("Expires: Thu, 19 Feb 1998 13:24:18 GMT");
	   header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
	   header("Cache-Control: no-cache, must-revalidate");
	   header("Cache-Control: post-check=0,pre-check=0");
	   header("Cache-Control: max-age=0");
	   header("Pragma: no-cache");
	    echo file_get_contents($fname);
	  }
	  else
	  {
       $Cat->tov=Api::GetProductTovarAll();
	   $Cat->cat=Api::GetCategoryAll();
	   Api::OutFormat($Cat,$TYPE,'all');
	  }
  break;
  
}

?>