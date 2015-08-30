<?php

/**
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     count
 * Purpose:  assign a number of array members to 'item' param
 * -------------------------------------------------------------
 */
function smarty_function_cs_show_new_item($params, &$smarty)
{
  $count = 4;

  $query = "
SELECT * FROM SC_product_list_item
LEFT JOIN SC_products ON SC_products.productID = SC_product_list_item.productID
LEFT JOIN SC_product_pictures ON SC_product_pictures.productID = SC_products.productID
WHERE list_id = 'newitems'
ORDER BY RAND() LIMIT $count;";

  $res   = mysql_query($query) or die(mysql_error()."<br>$query");
  $products = '';
  $i = 0;
  while($row=mysql_fetch_object($res))
  {
    $i++;
    $src = $row->thumbnail?URL_PRODUCTS_PICTURES.'/'.$row->thumbnail:'/img/nophoto.jpg';
    $text = "<b>".strip_tags($row->name_ru)." $row->product_code"."</b>. ".strip_tags($row->brief_description_ru);

    $line = ($i == $count)?"":"<hr width='90%' align='center' style='margin:10px 0 0 5%;'>";

    $products .= "
<div style='padding:0; margin:5px; width:137px; overflow-x:hidden;'>
  <a href='index.php?productID=$row->productID' style='text-decoration:none;'>
    <div style='text-align:center'><img src='$src' style='width:135px;'></div>
    $text
  </a>
  $line
</div>";
  }

  return $products;
}
?>