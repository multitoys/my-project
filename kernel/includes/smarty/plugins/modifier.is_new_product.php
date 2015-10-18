<?php

    function smarty_modifier_is_new_product($productID)
    {
        $query = "
                SELECT count(*)  FROM SC_category_product
                WHERE categoryID = 66666 AND productID = '".$productID."' ";

        $res = mysql_query($query) or die(mysql_error()."<br>$query");
        $row = mysql_fetch_row($res);

        return (int)$row[0];
    }