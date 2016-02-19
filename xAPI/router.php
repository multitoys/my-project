<?

$MOD=@$_GET['mod'];
$TYPE=@$_GET['type'];
$TYPE = ($TYPE!="")? $TYPE:"xml";

//$TOKEN=@$_GET['cid'];
$TOKEN= Toys::GetToken();

/*Защита токеном*/
if(!API::TestToken($TOKEN))
{  echo json_encode(array("error"=>"token no sucsess")); die();}

$Cat = '';
$Tov = '';
    
switch($MOD)
{
  /*
  получение категорий
  */
  case 'GCAT': 
    $idcat=(int)@$_GET['idcat'];
	if($idcat<1)$idcat=1;
    $Cat=API::GetCategory($idcat);
	API::OutFormat($Cat, $Tov, $TYPE, 'cat');
  break; 
 
  case 'GTOV': 
    $idcat=(int)@$_GET['idcat'];
	if($idcat<1)$idcat=1;
    $Tov=API::GetProductByCat($idcat);
	API::OutFormat($Cat, $Tov, $TYPE, 'tov');
  break; 
  
  case 'GTOVA':
      $fname="cache/".date("Y-m-d")."_".((int)(date("H")/4)).".".$TYPE;
	  
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
       $Tov=API::GetProductTovarAll();
	   $Cat=API::GetCategoryAll();
	   API::OutFormat($Cat, $Tov, $TYPE, 'all');
	  }
  break;
  
}

?>