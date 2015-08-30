<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     modifier
 * Name:     basename
 * Purpose:  see basename() in php manual
 * -------------------------------------------------------------
 */
function smarty_modifier_cs_load_auxpage($slug)
{
  $query = "SELECT aux_page_text_ru as text_ru, aux_page_name_ru FROM SC_aux_pages WHERE aux_page_slug = '$slug'";
  $res   = mysql_query($query) or die(mysql_error()."<br>$query");
  $row=mysql_fetch_object($res);
  if ($row)
  {
    $sotr_text = "<B>$row->aux_page_name_ru</B><br> $row->text_ru";
  } else $sotr_text = 'Для вставки здесь текста добавьте информационную страницу с id = '.$slug;
  return $sotr_text;
}

/* vim: set expandtab: */

?>