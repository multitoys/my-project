<?php
function transform_auxpage($name, $text)
{
    $log = isset($_SESSION['log'])?$_SESSION['log']:'';
    $vip = isset($_SESSION['cs_vip'])?$_SESSION['cs_vip']:'';

    if ($log !== '') {
        $may_order = isset($_SESSION['cs_may_order'])?$_SESSION['cs_may_order']:'0';
    }
    if ($may_order) {

        if (isset($_SESSION['cs_id'])) {
            $buy_enabled = true;
            $CustomerID = $_SESSION['cs_id'];
        } else {
            $buy_enabled = false;
        }

        // Собираем правила сортировки
        if (isset($_GET['sort'])) {
            $default_sort = '';
            $sort = $_GET['sort'];
        } else {
            $default_sort = 't1.sort_order, t1.categoryID,';
            $sort = 'name';
        }
        if (isset($_GET['direction'])) {
            $direction = $_GET['direction'];
        } else $direction = '';

        // NAME
        if ($sort=='name_ru') {
            $sort_class_name = 'z_sort_active';
        } else {
            $sort_class_name = 'z_sort_inactive';
        }
        if ($direction=='ASC' && $sort=='name_ru')
            $arrow_name='z_sort_desc';
        else
            $arrow_name='z_sort_asc';

        // PRODUCT_CODE
        if ($sort=='product_code') {
            $sort_class_pc = 'z_sort_active';
        } else {
            $sort_class_pc = 'z_sort_inactive';
        }
        if ($direction=='ASC' && $sort=='product_code')
            $arrow_product_code='z_sort_desc';
        else
            $arrow_product_code='z_sort_asc';

        // BONUS
        if ($sort=='Bonus') {
            $sort_class_bonus = 'z_sort_active';
        } else {
            $sort_class_bonus = 'z_sort_inactive';
        }
        if ($direction=='ASC' && $sort=='Bonus')
            $arrow_bonus='z_sort_desc';
        else
            $arrow_bonus='z_sort_asc';

        // PRICE
        if ($sort=='Price') {
            $sort_class_price = 'z_sort_active';
        } else {
            $sort_class_price = 'z_sort_inactive';
        }
        if ($direction=='ASC' && $sort=='Price')
            $arrow_price='z_sort_desc';
        else
            $arrow_price='z_sort_asc';

        // NEW DIRECTION
        if ($direction=='ASC') {
            $new_dir = 'DESC';
        } else {
            $new_dir = 'ASC';
        }
        if($sort == 'name') $sort='name_ru';

        // Количество выводимых товаров на странице
        $query = "SELECT settings_value FROM SC_settings where settings_constant_name='CONF_NEWTOV_COUNT'";
        $res = mysql_query($query) or die(mysql_error().$query);
        $settings = mysql_fetch_object($res);

        if($settings) {
            $tov_count = intval($settings->settings_value);
        } else {
            $tov_count = 100;
        }

        // Общее количество товаров
        $query = "
                    SELECT count(*) as tov_all_count
                    FROM SC_product_list_item
                    WHERE list_id = 'newitemspostup'
                  ";
        $res = mysql_query($query) or die(mysql_error().$query);

        $product_list_item = mysql_fetch_object($res);
        $tov_all_count = intval($product_list_item->tov_all_count);

        if(isset($_GET['p'])) $start_row = intval($_GET['p']);
        else $start_row = 0;

        $url = '/auxpage_new_items/';
        $pag_content = pagination($tov_all_count,$tov_count,10,$start_row,$url, $sort, $direction);
        $iLastPage = ceil($tov_all_count/$tov_count);
        // echo('Кол.тов.:'.$tov_all_count.'  Кол.стрн.:'.$tov_count.'  Номер.стр.:'.$page_num.'<br/>');
        $newitems = '<p class=navigator>'.$pag_content.'</p>';

        $newitems .= '
                    <div class=shapka>
                    <table class=cs_product_info>
                    <tbody>
                    <tr>
                    <td width=160px>
                    </td>
                    <td>
                    <div class=sort_name>
                    <div class='.$sort_class_name.'>
                    <a href="/auxpage_new_items/&amp;sort=name_ru&amp;direction='.$new_dir.'">
                    <table><tr><td style="padding-left:5px;"><div class=arbopr>Наименование&nbsp;</div>
                    <div class='.$arrow_name.'></div>
                    </td></tr></table>
                    </a>
                    </div>
                    </div>
                    </td>
                    <td width=100px>
                    <div>
                    <div class='.$sort_class_pc.'>
                    <a href="/auxpage_new_items/&amp;sort=product_code&amp;direction='.$new_dir.'">
                    <table width=100px><tr><td><div class=arbopr>Артикул</div>
                    <div class='.$arrow_product_code.'></div>
                    </td></tr></table>
                    </a>
                    </div>
                    </div>
                    </td>
                    <td width=60px>
                    <div>
                    <div class='.$sort_class_bonus.'>
                    <a href="/auxpage_new_items/&amp;sort=Bonus&amp;direction='.$new_dir.'">
                    <table width=60px><tr><td><div class=arbopr>Баллы</div>
                    <div class='.$arrow_bonus.'></div>
                    </td></tr></table>
                    </a>
                    </div>
                    </div>
                    </td>
                    <td width=80px>
                    <div>
                    <div class='.$sort_class_price.'>
                    <a href="/auxpage_new_items/&amp;sort=Price&amp;direction='.$new_dir.'">
                    <table width=80px><tr><td><div class=arbopr>Цена</div>
                    <div class='.$arrow_price.'></div>
                    </td></tr></table>
                    </a>
                    </div>
                    </div>
                    </td>
                    <td width=50px>
                    </td>
                    <td width=70px>
                    <div class=ost>
                    Остаток
                    </div>
                    </td>
                    <td width=55px>
                    <div class=zakaz>Заказано</div>
                    </td>
                    </tr>
                    </tbody>
                    </table>
                    </div>
                    </div>
                    <div class=scroll-pane1>
                         <div id=content>
                    ';
/**********************************************************************************************************************/
        if ($vip) {
            $auxpages = array('divoland', 'mixtoys', 'dreamtoys', 'alliance');

            foreach ($auxpages as $auxpage) {
                $query = "SELECT code, code_1c FROM Conc_search__$auxpage";
                $res = mysql_query($query) or die(mysql_error().$query);
                $codes_multi[$auxpage] = array();

                while ($Codes = mysql_fetch_object($res)) {
                    $codes_multi[$auxpage][$Codes->code_1c] = $Codes->code;
                }
            }
        }
/**********************************************************************************************************************/

        $query = "SELECT categoryID, name_ru FROM SC_categories";
        $res = mysql_query($query) or die(mysql_error().$query);

        $category_name = array();
        while($Categories = mysql_fetch_object($res)) {
            $category_name[$Categories->categoryID] = $Categories->name_ru;
        }
        $query = "
                    SELECT
                    t1.productID, t1.categoryID, t1.product_code,  t1.code_1c, t1.sort_order, t1.Price, t1.SpecialPrice, t1.list_price, t1.skidka, t1.name_ru, t1.default_picture, t1.ostatok, t1.Bonus, t1.zakaz, t1.slug, t3.filename, t3.thumbnail
                    FROM SC_products t1
                    LEFT JOIN SC_product_list_item t2  USING(productID)
                    LEFT JOIN SC_product_pictures t3 ON t1.default_picture = t3.photoID
                    WHERE t2.list_id = 'newitemspostup'
                    ORDER BY $default_sort  t1.$sort $direction
                    LIMIT $start_row, $tov_count
                ";
        $res = mysql_query($query) or die(mysql_error().$query);

        if ($CustomerID)$shop_count_cart = get_shop_cout($CustomerID);

        while ($Product = mysql_fetch_object($res)) {
/**********************************************************************************************************************/
            $add2cart_conc = '';

            if ($vip) {
                $auxpages = array('divoland', 'mixtoys', 'dreamtoys', 'alliance');
                foreach ($auxpages as $auxpage) {
                    if ($matched_product = $codes_multi[$auxpage][$Product->code_1c]) {
                        $query3
                            = "SELECT
                        category, code, product_code, name, price_uah
                    FROM
                        Conc__$auxpage
                    WHERE
                        code LIKE '$matched_product'";
                        $res3 = mysql_query($query3) or die(mysql_error().$query3);

                        if ($M_Product = mysql_fetch_object($res3)) {
                            //$category_conc = $M_Product->category;
                            $price_diff = round(($Product->Price / $M_Product->price_uah - 1) * 100, 1);
                            $marked     = ($price_diff > 0)?'red':'green';
                            $mark_conc     = ($price_diff > 0)?'font-weight:bold;background:yellow; box-shadow:
                             2px 2px 4px #9999aa;':'';
                            /*$add2cart_conc .= "
												<hr style='margin: 0 20px 0 5px;'>
												<div style='font-size: 10px; padding-left: 5px;' id=$auxpage$M_Product->code>
													<small style='font-size: 10px;'>$auxpage:</small><br>
													$M_Product->code  [$M_Product->product_code]  $M_Product->name
													<span style='color:$marked;'>$M_Product->price_uah</span> &#8372;  <br>
													разница: <span style='color:$marked;font-weight:700'>$price_diff%</span>
												</div>
											";*/
							$add2cart_conc .= " 
												<div style='font-size: 11px; padding-left: 5px;clear: both' id=$auxpage$M_Product->code>
													<div style='min-width: 75px !important;
													color:#03A9F4;$mark_conc;text-transform: capitalize; float: left'>$auxpage:</div>
													<div style='min-width: 95px; float: left'>цена <span style='color:$marked;'>$M_Product->price_uah</span> &#8372; | </div>
													<div style='float: left'>разница <span style='color:$marked;font-weight:700'>$price_diff%</span></div>
												</div>
											";
                        }
                    }
                }
            }
/**********************************************************************************************************************/
            $price = show_price(ZCalcPrice($Product->Price, $Product->SpecialPrice, $Product->skidka));
            // $oldprice = $Product->list_price;
            // $akcia = $Product->akcia;
            // $akcia_skidka = $Product->akcia_skidka;
            $category = $category_name[$Product->categoryID];
            $zakaz = $Product->zakaz;
            $bonus = $Product->Bonus;

            $shop_count = ($shop_count_cart[$Product->productID])?$shop_count_cart[$Product->productID]:0;
            $add2cart = ($buy_enabled) ? "
                                        <table width=175px>
                                        <tbody>
                                        <tr>
                                        <td>
                                        <input class=cart_product_quantity id=qty$Product->productID name=product_qty title='Количество' value='' size=2 data-id=$Product->productID onkeypress='if (event.keyCode == 13){add_2cart(\"#qty$Product->productID\");}'>
                                        </td>
                                        <td style='vertical-align:middle;white-space:nowrap;'>
                                        <div class=ostatok_div>&nbsp;$Product->ostatok&nbsp;шт.</div>
                                        </td>
                                        <td>
                                        <button class=z_add_cart title='добавить в корзину' onclick='add_2cart(\"#qty$Product->productID\")' type=button>
                                        <div id=zpid_$Product->productID  class=in_cart><div class='animated zoomInDown'>$shop_count</div></div>
                                        </button>
                                        </td>
                                        </tr>
                                        </tbody>
                                        </table>
                                        " : '';
            //$pictures = ((strlen($Product->thumbnail)>4) && (strlen($Product->filename)>4))?"
            //                                                                                <img width=160 height=120 class=preview  alt='$Product->name_ru' src='".URL_PRODUCTS_PICTURES."/$Product->thumbnail' data-pid='".URL_PRODUCTS_PICTURES."/$Product->filename' />
            //                                                                                ":"
            //                                                                                <img width=160 height=120 alt='no foto' src='/img/nophoto.jpg' />
            //                                                                                ";
            $q = '
                    SELECT count(*) AS pics_all_count
                    FROM SC_product_pictures
                    WHERE productID = '.$Product->productID;
            $r = mysql_query($q) or die(mysql_error().$q);
            $pics_count = mysql_fetch_object($r);
            $pics_all_count = (int)($pics_count->pics_all_count);
            $pics_for_slider = $pics_all_count - 1;
            if ($pics_for_slider) {
                $pictures = '
                        <div class=slider>
                            <div class=slides>
                                <div class=overflow>
                                    <div class=inner>
                                        <article>
                                            <img id=pic'.$Product->code_1c.' data-pics='.$pics_for_slider.' data-current=0 src='.URL_PRODUCTS_PICTURES.'/'.$Product->thumbnail.' data-pid='.URL_PRODUCTS_PICTURES.'/'.$Product->filename.' />
                                        </article>
                                    </div>
                                </div>
                            </div>
                            <div class=controls>
                                <div class="label prev" onclick="changePic('.$Product->code_1c.',-1)"></div>
                                <div class="label next" onclick="changePic('.$Product->code_1c.', 1)"></div>
                            </div>
                        </div>';
            } else {
                $pictures = ((strlen($Product->thumbnail) > 4) && (strlen($Product->filename) > 4)) ? "
                            <div class=div_izobrag><a href='/product/$Product->slug'><img width=160 height=120 class=preview  alt='$Product->name_ru' src='".URL_PRODUCTS_PICTURES."/$Product->thumbnail' data-pid='".URL_PRODUCTS_PICTURES."/$Product->filename' /></a></div>" : '<div class=div_izobrag><img width=153 height=117 alt=\'no foto\' src=\'/img/nophoto.jpg\' /></div>';
            }
            if ($zakaz ==1) {
                $akc ="<span style='color: red;font-size: 14px;'><i>под заказ!</i></span><br /><span style='color: grey;'><b>$price</b></span>";
            } else {
                $akc ="<span class=totalPrice>$price</span>";
            }
            if ($vip){
                $newitems .= "<hr style='border-color: coral'>";
            } else {
                $newitems .= "<div class=delimiter></div>";
            }
            $newitems .= "
                            <table class=cs_product_info>
                            <tbody>
                            <tr>
                            <td width=160px>
                            $pictures
                            </td>
                            <td>
                            <div class='productname newpostup'><a href='/product/$Product->slug'>$Product->name_ru</a><br><small>категория: &laquo;$category&raquo;</small></div>
                            $add2cart_conc
                            </td>
                            <td width=100px>
                            <div><a href='/product/$Product->slug'>$Product->product_code</a></div>
                            </td>
                            <td width=60px>
                            <div>
                            <span class=totalPrice>$bonus</span>
                            </div>
                            </td>
                            <td width=80px>
                            <div>
                            $akc
                            </div>
                            </td>
                            <td width=175px>
                            <div class=pravay_chast>
                            $add2cart
                            </div>
                            </td>
                            </tr>
                            </tbody>
                            </table>
                        ";
        }

        $newitems .= ($add2cart)?'<div style="text-align: right;"><button class="addall_pp blue-button" onclick="add_all2cart();">Заказать все</button></div></div></div>':'';
        $newitems .= '<p class="navigator bottom-nav">'.$pag_content.'</p>';

        foreach ($text as $key => $oneline) {
            $text[$key] = str_replace('%new_list%', $newitems, $oneline);
        }
        if ($buy_enabled) {
            return $text;
        } else return '</div>';

        // $newitems .= '</div>';
    }
}

function get_shop_cout($CustomerID)
{
    $query ="
            SELECT SC_products.productID, SC_shopping_carts.Quantity
            FROM SC_shopping_carts
            LEFT JOIN SC_shopping_cart_items ON SC_shopping_carts.itemID = SC_shopping_cart_items.itemID
            LEFT JOIN SC_products ON SC_shopping_cart_items.productID = SC_products.productID
            WHERE SC_shopping_carts.customerID  = $CustomerID";
    $res = mysql_query($query) or die(mysql_error()."<br>$query");
    $in_cart = array();
    while ($row = mysql_fetch_object($res)) {
        $in_cart[$row->productID] = intval($row->Quantity);
    }
    return $in_cart;
}

function pagination($total,$per_page,$num_links,$start_row,$url='', $sort, $direction)
{
    //Получаем общее число страниц
    $num_pages = ceil($total/$per_page);
    // Если страница одна, то ничего не выводим
    if ($num_pages == 1) return '';
    $cur_page = $start_row;
    //Если количество элементов на страницы больше чем общее число элементов
    // то текущая страница будет равна последней
    if ($cur_page > $total) {
        $cur_page = ($num_pages - 1) * $per_page;
    }
    //Получаем номер текущей страницы
    $cur_page = floor(($cur_page/$per_page) + 1);
    $start = (($cur_page - $num_links) > 0) ? $cur_page - $num_links : 0;
    if  ($cur_page != 1) {
        $i = $start_row - $per_page;
        if ($i <= 0) $i = 0;
        $z_sort = isset($_GET['sort']) ? '&amp;sort='.$sort.'&amp;direction='.$direction : "";
        $output .= '<i>←</i><a href="'.$url.'&amp;p='.$i.$z_sort.'">Предыдущая</a>';
    } else {
        $output .='<span><i>←</i>Предыдущая</span>';
    }
    $output .= '<span class=divider>&nbsp;|&nbsp;</span>';
    if ($cur_page < $num_pages) {
        $z_sort = isset($_GET['sort']) ? '&amp;sort='.$sort.'&amp;direction='.$direction : "";
        $output .= '<a href="'.$url.'&amp;p='.($cur_page * $per_page).$z_sort.'">Следующая</a><i>→</i>';
    } else {
        $output .= '<span>Следующая<i>→</i></span>';
    }
    //  $output .= '<span></span>';
    if($cur_page > ($num_links + 1)) {
        $output .= '<a href="'.$url.'" title="Первая"></a>';
    }
    for ($loop = 0; $loop <= $num_pages-1; $loop++) {
        $i = ($loop * $per_page);
        if ($i >= 0) {
            if (($cur_page-1) == $loop) {
                $output .= '<span style="color: purple;">&nbsp;'.($loop+1).'&nbsp;</span>'; // Текущая страница
            } else {
                $n = ($i == 0) ? '' : $i;
                $z_sort = isset($_GET['sort']) ? '&amp;sort='.$sort.'&amp;direction='.$direction : "";
                $output .= '<a href="'.$url.'&amp;p='.$n.$z_sort.'">&nbsp;'.($loop+1).'&nbsp;</a>';
            }
        }
    }
    return '<strong>Страницы:&nbsp;</strong>'.$output;
}