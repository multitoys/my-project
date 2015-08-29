<?php
function transform_auxpage($name, $text)
{
    // Количество выводимых товаров на странице
    $query = "SELECT settings_value FROM SC_settings where settings_constant_name='CONF_NEWTOV_COUNT'";
    $res = mysql_query($query) or die(mysql_error() . $query);
    $settings = mysql_fetch_object($res);
    if ($settings) {
                    $tov_count = intval($settings->settings_value);
    } else {
                    $tov_count = 100;
    }
    $div_cat = '';
    $selected_category = '';
    if (isset($_GET['div_cat'])) {
        $selected_category = $_GET['div_cat'];
        $div_cat = "WHERE category LIKE '$selected_category'";
    }
    // Общее количество товаров
    $query = "
            SELECT count(*) as tov_all_count
            FROM Conk_divolend $div_cat";
    $res = mysql_query($query) or die(mysql_error() . $query);
    $product_list_item = mysql_fetch_object($res);
    $tov_all_count     = intval($product_list_item->tov_all_count);
    if (isset($_GET['p']))
                    $start_row = (int)$_GET['p'];
    else
                    $start_row = 0;
    $url         = '/auxpage_divoland/';
    $pag_content = pagination($tov_all_count, $tov_count, 10, $start_row, $url, $selected_category);
    $iLastPage   = ceil($tov_all_count / $tov_count);
    $newitems    = $pag_content;
    $newitems .= '<div class=shapka>
                    <table class=cs_product_info>
                        <tbody>
                            <tr>
                                <td width=160px>
                                </td>
                                <td>
                                    <div class=arbopr>Наименование</div>
                                </td>
                                <td width=70px>
                                    <div class=arbopr>Цена, $</div>
                                </td>
                                <td width=70px>
                                    <div class=arbopr>Цена-&#8372;</div>
                                </td>
                                <td width=600px>
                                    <div class=>Совпадения</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class=scroll-pane1>';

    $query1 = "SELECT code, code_1c FROM Conc_search_divolend";
    $res1 = mysql_query($query1) or die(mysql_error().$query1);
    $codes_multi = array();
    while($Codes = mysql_fetch_object($res1)) {
        $codes_multi[$Codes->code] = $Codes->code_1c;
    }

    $query = "SELECT categoryID, name_ru FROM SC_categories";
    $res = mysql_query($query) or die(mysql_error().$query);
    $category_name = array();
    while($Categories = mysql_fetch_object($res)) {
        $category_name[$Categories->categoryID] = $Categories->name_ru;
    }

    $query2 = "
            SELECT
            category, code, name, price_usd, price_uah, foto
            FROM Conk_divolend $div_cat
            ORDER BY name
            LIMIT $start_row, $tov_count";
    $res2 = mysql_query($query2) or die(mysql_error() . $query2);

    // $search_conc = array("'",".",",","(",")");

    while ($Product = mysql_fetch_object($res2)) {

        $name = escape($Product->name);
        // $matched_product = array();

        if ($matched_product = $codes_multi[$Product->code]) {
            $query3 = "SELECT categoryID, code_1c, product_code, name_ru, Price
                      FROM SC_products WHERE code_1c LIKE '$matched_product'";
            $res3 = mysql_query($query3) or die(mysql_error() . $query3);

            if ($M_Product = mysql_fetch_object($res3)) {
                $category = $category_name[$M_Product->categoryID];
                $price_diff = round(($M_Product->Price/$Product->price_uah - 1)*100,1);
                $marked = ($price_diff > 0)?'red':'green';
                $add2cart = "
                    <table width=600px>
                    <tbody>
                    <tr>
                    <td>
                    <div id=multi_$M_Product->code_1c><p>$M_Product->code_1c&nbsp;&nbsp;[$M_Product->product_code]&nbsp;&nbsp;$M_Product->name_ru&nbsp;&nbsp;<span style='color:$marked;'>$M_Product->Price</span>&nbsp;&#8372;&nbsp;&nbsp;разница: $price_diff%<br><small>категория: &laquo;$category&raquo;</small></p></div>
                    </td>
                    </tr>
                    </tbody>
                    </table>";
            }
        } else {
            // $name_conc = str_replace($search_conc," ",$Product->name);
            $name_conc = str_replace("+","",trim($Product->name));
            $name_conc = str_replace("  "," ",$name_conc);
            $name_conc = str_replace(" ","|",$name_conc);
            // $name_conc = trim($name_conc);

            $add2cart = "
                <table width=600px>
                <tbody>
                <tr>
                <td>
                <button  class='my-button' title='поиск соответствий' onclick='loadAnalogs(\"$name_conc\",\"$Product->code\",\"$Product->price_uah\")' type=button>Найти совпадения
                </button>
                <div id=conc_$Product->code></div>
                </td>
                </tr>
                </tbody>
                </table>";
        }
        $pictures      = (strlen($Product->foto)) ? "<img width=160 height=120  class=preview alt='$name' src='/divoland/$Product->foto' pid='/divoland/$Product->foto'>" : "<img width=153 height=117 alt='no foto' src='/img/nophoto.jpg'>";
        $newitems .= "
            <div class=delimiter></div>
            <table class=cs_product_info>
                <tbody>
                    <tr>
                        <td width=160px>
                            <div class=div_izobrag>
                                    $pictures
                            </div>
                        </td>
                        <td>
                            <div class='productname newpostup'>
                                    $name<br><small>категория: &laquo;$Product->category&raquo;</small>
                            </div>
                        </td>
                        <!-- <td width=70px>
                            <div>
                                <span class=totalPrice>$$Product->price_usd</span>
                            </div>
                        </td> -->
                        <td width=70px>
                            <div>
                                <span class=totalPrice>$Product->price_uah&nbsp;&#8372;</span>
                            </div>
                        </td>
                        <td width=600px style='border-left: 1px ridge red;'>
                            <div>
                                $add2cart
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>";
    }

    $newitems .= '</div>' . $pag_content . '';
    foreach ($text as $key => $oneline) {
                    $text[$key] = str_replace('%new_list%', $newitems, $oneline);
    }

    return $text;

    $newitems .= '</div>';
}
function escape($string, $esc_type = 'html')
{
    switch ($esc_type) {
        case 'html':
            return htmlspecialchars($string, ENT_QUOTES);

        case 'htmlall':
            return htmlentities($string, ENT_QUOTES);

        case 'url':
            return rawurlencode($string);

        case 'quotes':
            // escape unescaped single quotes
            return preg_replace("%(?<!\\\\)'%", "\\'", $string);

        case 'hex':
            // escape every character into hex
            $return = '';
            for ($x=0; $x < strlen($string); $x++) {
                $return .= '%' . bin2hex($string[$x]);
            }
            return $return;

        case 'hexentity':
            $return = '';
            for ($x=0; $x < strlen($string); $x++) {
                $return .= '&#x' . bin2hex($string[$x]) . ';';
            }
            return $return;

        case 'decentity':
            $return = '';
            for ($x=0; $x < strlen($string); $x++) {
                $return .= '&#' . ord($string[$x]) . ';';
            }
            return $return;

        case 'javascript':
            // escape quotes and backslashes, newlines, etc.
            return strtr($string, array('\\'=>'\\\\',"'"=>"\\'",'"'=>'\\"',"\r"=>'\\r',"\n"=>'\\n','</'=>'<\/'));

        case 'mail':
            // safe way to display e-mail address on a web page
            return str_replace(array('@', '.'),array(' [AT] ', ' [DOT] '), $string);

        case 'nonstd':
            // escape non-standard chars, such as ms document quotes
            $_res = '';
            for($_i = 0, $_len = strlen($string); $_i < $_len; $_i++) {
                $_ord = ord($string{$_i});
                // non-standard char, escape it
                if($_ord >= 126){
                    $_res .= '&#' . $_ord . ';';
                }
                else {
                    $_res .= $string{$_i};
                }
            }
            return $_res;

        default:
            return $string;
    }
}
function pagination($total, $per_page, $num_links, $start_row, $url = '', $selected_category= '')
{
				//Получаем общее число страниц
				$num_pages = ceil($total / $per_page);
				// Если страница одна, то ничего не выводим
				if ($num_pages == 1)
								return '';
				$cur_page = $start_row;
				//Если количество элементов на страницы больше чем общее число элементов
				// то текущая страница будет равна последней
				if ($cur_page > $total) {
								$cur_page = ($num_pages - 1) * $per_page;
				}
				//Получаем номер текущей страницы
				$cur_page = floor(($cur_page / $per_page) + 1);
				$start    = (($cur_page - $num_links) > 0) ? $cur_page - $num_links : 0;
				if ($cur_page != 1) {
								$i = $start_row - $per_page;
								if ($i <= 0) $i = 0;
                    $output ='';
                    $output .= '<i>←</i><a href="' . $url . '&amp;div_cat='.$selected_category.'&amp;p=' . $i . '">Предыдущая</a>';
				} else {
                    $output ='';
                    $output .= '<span><i>←</i>Предыдущая</span>';
				}
				$output .= '<span class=divider>&nbsp;|&nbsp;</span>';
				if ($cur_page < $num_pages) {
								$output .= '<a href="' . $url . '&amp;div_cat='.$selected_category.'&amp;p=' . ($cur_page * $per_page) . '">Следующая</a><i>→</i>';
				} else {
								$output .= '<span>Следующая<i>→</i></span>';
				}
				if ($cur_page > ($num_links + 1)) {
								$output .= '<a href="' . $url . '" title="Первая"></a>';
				}
				for ($loop = 0; $loop <= $num_pages - 1; $loop++) {
								$i = ($loop * $per_page);
								if ($i >= 0) {
												if (($cur_page - 1) == $loop) {
																$output .= '<span style="color: purple;">&nbsp;' . ($loop + 1) . '&nbsp;</span>'; // Текущая страница
												} else {
																$n      = ($i == 0) ? '' : $i;
																$output .= '<a href="' . $url . '&amp;div_cat='.$selected_category.'&amp;p=' . $n . '">&nbsp;' . ($loop + 1) . '&nbsp;</a>';
												}
								}
				}
				return '<p class=navigator><strong>Страницы:&nbsp;</strong>' . $output . '</p>';
}