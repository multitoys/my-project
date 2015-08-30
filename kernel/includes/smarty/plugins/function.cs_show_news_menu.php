<?php

/**
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     count
 * Purpose:  assign a number of array members to 'item' param
 * -------------------------------------------------------------
 */
function smarty_function_cs_show_news_menu($params, &$smarty)
{

  $query = "SELECT * FROM SC_news_table ORDER BY priority, add_stamp DESC LIMIT 15";
  $res   = mysql_query($query) or die(mysql_error()."<br>$query");
  $news_text = '';
  $no = 0;
  while ($row=mysql_fetch_object($res))
  {
    $no++;
    $news_text .= "
<B>$row->title</B>
<p>$row->add_date</p>
$row->textToPublication
<div align='right'><a href='/blog/$row->NID/'>Подробнее</a></div>
";
    $news_text .= ($no<15)?"<div class='bg_news_separator'></div>":'';
	
  }
	

  $text = "

<div class='header_menu_news'>
$news_text
</div>

";


  return $text;
}
?>