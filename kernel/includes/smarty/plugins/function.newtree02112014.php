<?php 
  function smarty_function_newtree($params, &$smarty){ 
$disp.='<ul id="navmenu-v"> '; 
  $sql='SELECT categoryID, slug, parent, '.LanguagesManager::sql_prepareField('name').' AS name from '.CATEGORIES_TABLE. ' where parent=1 order by sort_order,name'; 
  if($r=mysql_query($sql)) 
  while($res=mysql_fetch_assoc($r)){ 
  $disp.='<li >'; 
   
  if($res['slug']!='') 
   
  $disp.='<a href="index.php?categoryID='.$res['categoryID'].'/" aria-haspopup="true">'.$res['name'].'</a>'; 
  else 
  $disp.='<a href="?categoryID='.$res['categoryID'].'" aria-haspopup="true">'.$res['name'].'</a>'; 
  $disp.=subcat($res['categoryID']).''; 
  } 
  $disp.='</li><li>
						<a href="index.php?ukey=auxpage_new_items">Последние поступления</a>
              </li></ul>'; 
  return $disp; 
  } 

  function subcat($parid){ 
  $sql='SELECT categoryID, slug, parent, '.LanguagesManager::sql_prepareField('name').' AS name from '.CATEGORIES_TABLE. ' where parent='.$parid.' order by sort_order, name'; 
  if($r=mysql_query($sql)){ 
  if(mysql_num_rows($r)>0){ 
  $disp.='<ul >'; 
  while($res=mysql_fetch_assoc($r)){ 
  $disp.='<li >'; 
  if($_GET['categoryID'] == $res['categoryID']) ; 
  if($res['slug']!='') 
  $disp.='<a href="index.php?categoryID='.$res['categoryID'].'/">'.$res['name'].'</a>'; 
  else 
  $disp.='<a href="?categoryID='.$res['categoryID'].'">'.$res['name'].'</a>'; 
  $disp.=subcat($res['categoryID']).''; 
  } 
  $disp.='</li></ul>'; 
  } 
  } 
  return $disp; 
  } 

?>