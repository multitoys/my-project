<?php
    function transform_auxpage_conc($name, $text)
    {
        if (!isset($_SESSION['cs_vip']) || $_SESSION['cs_vip'] == 0) {
            return false;
        }
        if (array_key_exists('auxpage', $_SESSION)) {
            unset($_SESSION['auxpage']);
        }
        $_SESSION['auxpage'] = substr($name, 8);
        $auxpage = $_SESSION['auxpage'];
        // Количество выводимых товаров на странице
//    $query = 'SELECT settings_value FROM SC_settings where settings_constant_name=\'CONF_NEWTOV_COUNT\'';
//    $res = mysql_query($query) or die(mysql_error() . $query);
//    $settings = mysql_fetch_object($res);
//    if ($settings) {
//                    $tov_count = intval($settings->settings_value);
//    } else {
        $tov_count = 50;
//    }
        $div_cat = 'WHERE enabled ';
        $cat_div = '';
        $selected_category = '';
        if (isset($_GET['div_cat'])) {
            $selected_category = $_GET['div_cat'];
            $div_cat .= "AND category LIKE '$selected_category'";
            $cat_div = 'div_cat';
        } elseif (isset($_GET['div_par'])) {
            $selected_category = $_GET['div_par'];
            $div_cat .= "AND parent LIKE '$selected_category'";
            $cat_div = 'div_par';
        }
        // Общее количество товаров
        $query = '
            SELECT count(*) as tov_all_count
            FROM Conc__'.$auxpage.' '.$div_cat;
        $res = mysql_query($query) or die(mysql_error().$query);
        $product_list_item = mysql_fetch_object($res);
        $tov_all_count = (int)$product_list_item->tov_all_count;
        if (isset($_GET['p']))
            $start_row = (int)$_GET['p'];
        else
            $start_row = 0;
        $url = '/'.$name.'/';
        //$pag_content = pagination($tov_all_count, $tov_count, 50, $start_row, $cat_div, $url, $selected_category);
        $out = SimpleNavigator($tov_all_count, $start_row, $tov_count, $url, &$out);
        //$newitems    = $pag_content;
        $newitems = "<div class='simple-pagination compact-theme'>$out</div>";
        $newitems .= '</div>
                    <div class=scroll-pane1>';

        $query1 = "SELECT code, code_1c FROM Conc_search__$auxpage";
        $res1 = mysql_query($query1) or die(mysql_error().$query1);
        $codes_multi = array();
        while ($Codes = mysql_fetch_object($res1)) {
            $codes_multi[$Codes->code] = $Codes->code_1c;
        }

        $query = 'SELECT categoryID, name_ru FROM SC_categories';
        $res = mysql_query($query) or die(mysql_error().$query);
        $category_name = array();
        while ($Categories = mysql_fetch_object($res)) {
            $category_name[$Categories->categoryID] = $Categories->name_ru;
        }

        $query2 = "SELECT
					category, code, product_code, name, price_uah
				FROM
					Conc__$auxpage $div_cat
				ORDER BY
					date_added DESC, code DESC
				LIMIT
					$start_row, $tov_count";
        $res2 = mysql_query($query2) or die(mysql_error().$query2);

        while ($Product = mysql_fetch_object($res2)) {

            $category_conc = escape($Product->category);
            $name = escape($Product->name);
            $product_code = escape($Product->product_code);
            $replace_arr = array('+', '.', '\'', '/', '"', ',', '  ', '  ');
            $name_conc = str_replace($replace_arr, ' ', trim($name).' '.trim($product_code));;
            $name_conc = escape(str_replace(' ', '|', $name_conc), 'javascript');

            if ($matched_product = $codes_multi[$Product->code]) {
                $query3 = "SELECT categoryID, code_1c, product_code, name_ru, Price, enabled
                      FROM SC_products WHERE code_1c LIKE '$matched_product'";
                $res3 = mysql_query($query3) or die(mysql_error().$query3);

                if ($M_Product = mysql_fetch_object($res3)) {
                    $category = $category_name[$M_Product->categoryID];
                    $price_diff = round(($M_Product->Price / $Product->price_uah - 1) * 100, 1);
                    $marked = ($price_diff > 0) ? 'red' : 'green';
//                $disabled = 'line-height:65px;';
                    $disabled = '';
                    $button = 'gainsboro';

                    if (!$M_Product->enabled) {
                        $disabled .= 'color:grey;text-decoration:line-through;';
//                    $button = '#848484';
                    }
//                $analog = "
//                    <div class='productname newpostup' id=multi_$M_Product->code_1c $style>
//                        $M_Product->code_1c  [$M_Product->product_code]  $M_Product->name_ru
//                        <span style='color:$marked;'>$M_Product->Price</span> &#8372;  <br>
//                        разница: <span style='color:$marked;font-weight:700'>$price_diff%</span><br>
//                        <small>категория: &laquo;$category&raquo;</small>
//                    </div>";
                    $analog = "
                    <p class='productname newpostup' style=\"$disabled\"><button class='blue-button' title=''
                     style='background-color: $button'
                     onclick='unsetAnalogs(\"$name_conc\",\"$Product->code\",\"$M_Product->code_1c\",\"$Product->price_uah\")' type=button>
                   X</button>($M_Product->code_1c)  $M_Product->name_ru<br>
                        <small>арт.: </small><span style='color: #008BFF'>$M_Product->product_code</span><br>
                        <small>цена: </small><span class=totalPrice
                        style='color:$marked;'>$M_Product->Price&nbsp;&#8372;&nbsp;|&nbsp;</span>
                        <small>разница: </small><span style='color:$marked;font-weight:700'>$price_diff%</span>
                    </p>";
                }
            } else {

                $analog = "<input type='text' class='input_message search-concs' rel='Поиск аналогов' value='Поиск аналогов' name='searchstring'   data-conc=$name_conc data-code=$Product->code data-price=$Product->price_uah >
                            <a class='blue-button fancybox fancybox.ajax find' title=''
                            href='/popup/search_by_conc/search_conc.php?mode=1&conc=$name_conc&code=$Product->code&price=$Product->price_uah' onclick=\"this.style.backgroundColor = 'transparent'\">Найти совпадения</a>";
            }
//        $pictures  = (strlen($Product->foto)) ? "<img width=160 height=120  class=preview alt='$name' src='/$auxpage/$Product->foto' pid='/$auxpage/$Product->foto'>" : "<img width=153 height=117 alt='no foto' src='/img/nophoto.jpg'>";
            $newitems .= "
            <hr style='border-color: coral;'>
            <div class=cs_product_info style='height: auto'>
                <div class='productname newpostup'>
                        $name <small>&laquo;$category_conc&raquo;</small><br>
                        <small>арт.: </small><span style='color: #008BFF'>$product_code</span><br>
                        <small>цена: </small><span class=totalPrice>$Product->price_uah&nbsp;&#8372;</span>
                </div>
                <div class=delimiter></div>
                <div id=conc_$Product->code style='display: flex;'>
                    $analog
                </div>
            </div>
		";
        }

        $newitems .= '</div>';
        foreach ($text as $key => $oneline) {
            $text[$key] = str_replace('%new_list%', $newitems, $oneline);
        }

        return $text;

//    $newitems .= '</div>';
    }