<?php
    function transform_auxpage($name, $text, $ajax)
    {
        $log = isset($_SESSION['log']) ? $_SESSION['log'] : '';
        $vip = isset($_SESSION['cs_vip']) ? $_SESSION['cs_vip'] : '';

        $CustomerID = '';
        $may_order = '';
        $newitems = '';
        $shop_count_cart = '';
        if ($log !== '') {
            $may_order = isset($_SESSION['cs_may_order']) ? $_SESSION['cs_may_order'] : '0';
        }

        if (array_key_exists('auxpage', $_SESSION)) {
            unset($_SESSION['auxpage']);
        }
        $_SESSION['auxpage'] = substr($name, 8);
        $auxpage = $_SESSION['auxpage'];


        if ($may_order) {

            if (isset($_SESSION['cs_id'])) {
                $buy_enabled = true;
                $CustomerID = $_SESSION['cs_id'];
            } else {
                $buy_enabled = false;
            }

            //Собираем правила сортировки
            $default_sort = '';
            $sort = 'name';
            $ajax_sort = $sort;
            $direction = '';
            $sort_class_name = 'z_sort_inactive';
            $sort_class_pc = 'z_sort_inactive';
            $sort_class_bonus = 'z_sort_inactive';
            $sort_class_price = 'z_sort_inactive';
            $arrow_name = 'z_sort_asc';
            $arrow_product_code = 'z_sort_asc';
            $arrow_bonus = 'z_sort_asc';
            $arrow_price = 'z_sort_asc';
            $new_dir = 'ASC';

            if (isset($_GET['sort'])) {
                $sort = $_GET['sort'];
                $ajax_sort = $sort;
            } elseif (isset($_POST['sort'])) {
                $sort = $_POST['sort'];
            }

            if (isset($_REQUEST['direction'])) {
                $direction = $_REQUEST['direction'];
                // NEW DIRECTION
                if ($direction === 'ASC') {
                    $new_dir = 'DESC';
                    $arrow_name = 'z_sort_desc';
                    $arrow_product_code = 'z_sort_desc';
                    $arrow_bonus = 'z_sort_desc';
                    $arrow_price = 'z_sort_desc';
                }
            }

            // NAME
            if ($sort === 'name_ru') {
                $sort_class_name = 'z_sort_active';
                $arrow_name = ($direction === 'DESC')?'z_sort_desc':'z_sort_asc';
            }
            // PRODUCT_CODE
            if ($sort === 'product_code') {
                $sort_class_pc = 'z_sort_active';
                $arrow_product_code = ($direction === 'DESC')?'z_sort_desc':'z_sort_asc';
            }
            // BONUS
            if ($sort === 'Bonus') {
                $arrow_bonus = ($direction === 'DESC')?'z_sort_desc':'z_sort_asc';
                $sort_class_bonus = 'z_sort_active';
            }
            // PRICE
            if ($sort === 'Price') {
                $sort_class_price = 'z_sort_active';
                $arrow_price = ($direction === 'DESC')?'z_sort_desc':'z_sort_asc';
            }
            if ($sort === 'name') {
                $ajax_sort = $sort;
                $sort = 'name_ru';
                $default_sort = 't1.sort_order, t1.categoryID,';
            }

            // Количество выводимых товаров на один запрос
            //$add_count = 0;
            $tov_count = 50;

            // Количество выводимых товаров на странице
            $p_count = 150;

            //if (isset($_POST['count_add'])) {
            //    $add_count = (int)$_POST['count_add'];
            //}

            $query1 = '
                        SELECT settings_value
                        FROM SC_settings
                        WHERE settings_constant_name=\'CONF_PRODUCTS_PER_PAGE\'';
            $res1 = mysql_query($query1) or die(mysql_error().$query1);
            $settings1 = mysql_fetch_object($res1);

            if ($settings1) {
                $tov_count = (int)($settings1->settings_value);
            }

            $query2 = 'SELECT settings_value
                  FROM SC_settings
                  WHERE settings_constant_name=\'CONF_NEWTOV_COUNT\'';
            $res2 = mysql_query($query2) or die(mysql_error().$query2);
            $settings2 = mysql_fetch_object($res2);

            if ($settings2) {
                $p_count = (int)($settings2->settings_value);
            }

            // Общее количество товаров
            $query = '
                    SELECT count(*) AS tov_all_count
                    FROM SC_product_list_item
                    WHERE list_id = \'newitemspostup\'
                  ';
            $res = mysql_query($query) or die(mysql_error().$query);
            $product_list_item = mysql_fetch_object($res);
            $tov_all_count = (int)($product_list_item->tov_all_count);

            $start_row = 0;

            if (isset($_REQUEST['p'])) {
                $start_row = (int)$_REQUEST['p'];
                $start_row = ($start_row === -1) ? 0 : $start_row;
            }

            $url = '/auxpage_new_items/';
            $out = SimpleNavigator($tov_all_count, $start_row, $p_count, $url, &$out);
            $start = $start_row;
            $direction_nav = 'ASC';
            if ($direction) {
                $direction_nav = $direction;
            }
            $newitems_start = "
                                <div
                                    id='light-pagination'
                                    class='simple-pagination compact-theme'
                                    data-items       = $tov_all_count
                                    data-itemsOnPage = $p_count
                                    data-add         = $tov_count
                                    data-show        = 0
                                    data-page        = $start
                                    data-sort        = $ajax_sort
                                    data-direction   = $direction_nav
                                >$out</div>
                                ";

            //$newitems_start .= '
            //            <div class=shapka>
            //                <table class=cs_product_info>
            //                    <tbody>
            //                        <tr>
            //                            <td width=160px>
            //                            </td>
            //                            <td>
            //                                <div class=sort_name>
            //                                    <div class='.$sort_class_name.'>
            //                                        <a href="/auxpage_new_items/name_ru/'.$new_dir.'/">
            //                                            <table>
            //                                                <tr>
            //                                                    <td style="padding-left:5px;">
            //                                                        <div class=arbopr>Наименование&nbsp;</div>
            //                                                        <div class='.$arrow_name.'></div>
            //                                                    </td>
            //                                                </tr>
            //                                            </table>
            //                                        </a>
            //                                    </div>
            //                                </div>
            //                            </td>
            //                            <td width=100px>
            //                                <div>
            //                                    <div class='.$sort_class_pc.'>
            //                                        <a href="/auxpage_new_items/product_code/'.$new_dir.'/">
            //                                            <table width=100px>
            //                                                <tr>
            //                                                    <td>
            //                                                        <div class=arbopr>Артикул</div>
            //                                                        <div class='.$arrow_product_code.'></div>
            //                                                    </td>
            //                                                </tr>
            //                                            </table>
            //                                        </a>
            //                                    </div>
            //                                </div>
            //                            </td>
            //                            <td width=60px>
            //                                <div>
            //                                    <div class='.$sort_class_bonus.'>
            //                                        <a href="/auxpage_new_items/Bonus/'.$new_dir.'/">
            //                                            <table width=60px>
            //                                                <tr>
            //                                                    <td>
            //                                                        <div class=arbopr>Баллы</div>
            //                                                        <div class='.$arrow_bonus.'></div>
            //                                                    </td>
            //                                                </tr>
            //                                            </table>
            //                                        </a>
            //                                    </div>
            //                                </div>
            //                            </td>
            //                            <td width=80px>
            //                                <div>
            //                                    <div class='.$sort_class_price.'>
            //                                        <a href="/auxpage_new_items/Price/'.$new_dir.'/">
            //                                            <table width=80px>
            //                                                <tr>
            //                                                    <td>
            //                                                        <div class=arbopr>Цена</div>
            //                                                        <div class='.$arrow_price.'></div>
            //                                                    </td>
            //                                                </tr>
            //                                            </table>
            //                                        </a>
            //                                    </div>
            //                                </div>
            //                            </td>
            //                            <td width=50px>
            //                            </td>
            //                            <td width=70px>
            //                                <div class=ost>Остаток</div>
            //                            </td>
            //                            <td width=55px>
            //                                <div class=zakaz>Заказано</div>
            //                            </td>
            //                        </tr>
            //                    </tbody>
            //                </table>
            //            </div>
            //        </div>
            //        <div class=scroll-pane1>
            //            <div id=content>
            //';
            $newitems_start .= '
                        <div class=shapka>
                            <table class=cs_product_info  style="padding-left: 5px">
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class='.$sort_class_name.'>
                                                <div class="arbopr sort_name">
                                                    <a href="/auxpage_new_items/name_ru/'.$new_dir.'/">Наименование</a>
                                                </div>
                                                <div class='.$arrow_name.'></div>
                                            </div>
                                        </td>
                                        <td width=100px>
                                            <div class="'.$sort_class_pc.' ">
                                                <div class=arbopr>
                                                    <a href="/auxpage_new_items/product_code/'.$new_dir.'/">Артикул</a>
                                                </div>
                                                <div class='.$arrow_product_code.'></div>
                                            </div>
                                        </td>
                                        <td width=60px>
                                            <div class='.$sort_class_bonus.'>
                                                <div class=arbopr>
                                                    <a href="/auxpage_new_items/Bonus/'.$new_dir.'/">Баллы</a>
                                                </div>
                                                <div class='.$arrow_bonus.'></div>
                                            </div>
                                        </td>
                                        <td width=80px>
                                            <div class='.$sort_class_price.'>
                                                <div class=arbopr>
                                                    <a href="/auxpage_new_items/Price/'.$new_dir.'/">Цена</a>
                                                </div>
                                                <div class='.$arrow_price.'></div>
                                            </div>
                                        </td>
                                        <td width=110px>
                                            <div class=ost>Остаток</div>
                                        </td>
                                        <td width=65px>
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
            if ($vip) {
                $newitems_start .= '<hr style=\'border-color: coral\'>';
            } else {
                $newitems_start .= '<div class=delimiter></div>';
            }

            /**************************************************************************************************************/
            if ($vip) {
                $auxpages = array('divoland', 'mixtoys', 'dreamtoys');

                foreach ($auxpages as $aux) {
                    $query = "SELECT code, code_1c FROM Conc_search__$aux";
                    $res = mysql_query($query) or die(mysql_error().$query);
                    $codes_multi[$aux] = array();

                    while ($Codes = mysql_fetch_object($res)) {
                        $codes_multi[$aux][$Codes->code_1c] = $Codes->code;
                    }
                }
            }
            /**************************************************************************************************************/
            $query = 'SELECT categoryID, name_ru FROM SC_categories';
            $res = mysql_query($query) or die(mysql_error().$query);
            $category_name = array();
            while ($Categories = mysql_fetch_object($res)) {
                $category_name[$Categories->categoryID] = $Categories->name_ru;
            }

            $query = 'SELECT productID FROM SC_products WHERE enabled = 1 ORDER BY code_1c DESC LIMIT 500';
            $res = mysql_query($query) or die(mysql_error().$query);
            $new = array();
            while ($New_items = mysql_fetch_object($res)) {
                $new[$New_items->productID] = $New_items->productID;
            }

            if (isset($_POST['count_show'])) {
                $start_row = (int)$_POST['count_show'];
            }

            $query = "
                    SELECT
                          t1.productID, t1.categoryID, t1.product_code,  t1.code_1c, t1.sort_order, t1.Price,
                          t1.SpecialPrice, t1.list_price, t1.skidka, t1.name_ru, t1.default_picture, t1.ostatok,
                          t1.Bonus, t1.zakaz, t1.slug, t3.filename, t3.thumbnail
                    FROM SC_products t1
                    LEFT JOIN SC_product_list_item t2  USING(productID)
                    LEFT JOIN SC_product_pictures t3 ON t1.default_picture = t3.photoID
                    WHERE t2.list_id = 'newitemspostup'
                    ORDER BY $default_sort  t1.$sort $direction
                    LIMIT $start_row, $tov_count
                ";
            $res = mysql_query($query) or die(mysql_error().$query);

            if ($CustomerID) $shop_count_cart = get_shop_couts($CustomerID);

            while ($Product = mysql_fetch_object($res)) {
                /**********************************************************************************************************/
                $add2cart_conc = '';

                if ($vip) {
                    //$codes_multi = array();
                    $auxpages = array('divoland', 'mixtoys', 'dreamtoys');
                    foreach ($auxpages as $aux) {
                        if ($matched_product = $codes_multi[$aux][$Product->code_1c]) {
                            $query3
                                = "SELECT
                        category, code, product_code, name, price_uah
                    FROM
                        Conc__$aux
                    WHERE
                        code LIKE '$matched_product'";
                            $res3 = mysql_query($query3) or die(mysql_error().$query3);

                            if ($M_Product = mysql_fetch_object($res3)) {
                                $price_diff = round(($Product->Price / $M_Product->price_uah - 1) * 100, 1);
                                $marked = ($price_diff > 0) ? 'red' : 'green';
                                $mark_conc = ($price_diff > 0) ? 'font-weight:bold;background:yellow; box-shadow:
                             2px 2px 4px #9999aa;' : '';
                                $add2cart_conc .= "
												<div style='font-size: 11px; padding-left: 5px;clear: both' id=$aux$M_Product->code>
													<div style='min-width: 75px !important;
													color:#03A9F4;$mark_conc;text-transform: capitalize; float: left'>$aux:</div>
													<div style='min-width: 95px; float: left'>цена <span style='color:$marked;'>$M_Product->price_uah</span> &#8372; | </div>
													<div style='float: left'>разница <span style='color:$marked;font-weight:700'>$price_diff%</span></div>
												</div>
											";
                            }
                        }
                    }
                }
                /**********************************************************************************************************/
                $price = show_price(ZCalcPrice($Product->Price, $Product->skidka));
                $category = $category_name[$Product->categoryID];
                $label_new = ($new[$Product->productID])?'<div class="corner color_newitem"><span></span>Новинка!</div>':'';
                //$label_new = '<div class="corner color_newitemspostup"><span></span>Новинка!</div>';
                //$zakaz = $Product->zakaz;
                $bonus = $Product->Bonus;

                $shop_count = 0;
                if (($shop_count_cart[$Product->productID])) {
                    $shop_count = $shop_count_cart[$Product->productID];
                }
                $add2cart = ($buy_enabled) ? "
                                        <table width=175px>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input class=cart_product_quantity id=qty$Product->productID name=product_qty title='Количество' value='' size=2 data-id=$Product->productID onkeypress='if (event.keyCode == 13){add_2cart(\"#qty$Product->productID\")}'>
                                                    </td>
                                                    <td style='vertical-align:middle;white-space:nowrap;'>
                                                        <div class=ostatok_div>&nbsp;$Product->ostatok&nbsp;шт.</div>
                                                    </td>
                                                    <td>
                                                        <button class=z_add_cart title='добавить в корзину' onclick='add_2cart(\"#qty$Product->productID\")' type=button>
                                                            <div id=zpid_$Product->productID  class=in_cart>
                                                                <div class='animated zoomInDown'>$shop_count</div>
                                                            </div>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        " : '';
                $q = '
                    SELECT count(*) AS pics_all_count
                    FROM SC_product_pictures
                    WHERE productID = '.$Product->productID;
                $r = mysql_query($q) or die(mysql_error().$q);
                $pics_count = mysql_fetch_object($r);
                $pics_all_count = (int)($pics_count->pics_all_count);
                $pics_for_slider = $pics_all_count - 1;
                if ($pics_for_slider > 0) {
                    $pictures = '
                        <div class="slider visual">
                            <div class=controls  data-pid='.URL_PRODUCTS_PICTURES.'/'.$Product->filename.'>
                                <div class="label prev_pic" onclick="changePic('.$Product->code_1c.',-1)"></div>
                                <div class="label next_pic" onclick="changePic('.$Product->code_1c.', 1)"></div>
                            </div>
                            <img id=pic'.$Product->code_1c.' data-pics='.$pics_for_slider.' data-current=0 src='.URL_PRODUCTS_PICTURES.'/'.$Product->thumbnail.' />
                            '.$label_new.'
                        </div>';
                } else {
                    $pictures = ((strlen($Product->thumbnail) > 4) && (strlen($Product->filename) > 4)) ? "
                            <div class=visual><a href='/product/$Product->slug'><img width=160 height=120 class=preview  alt='$Product->name_ru' src='".URL_PRODUCTS_PICTURES."/$Product->thumbnail' data-pid='".URL_PRODUCTS_PICTURES."/$Product->filename'></a>$label_new</div>" : "<div class=visual><a href='/product/$Product->slug'><img width=153 height=117 alt='no foto' src='/img/nophoto.jpg'></a>$label_new</div>";
                }
                //$pictures = '<div class=div_izobrag><img width=153 height=117 alt=\'no foto\' src=\'/img/nophoto.jpg\' /></div>';
                //if ($zakaz === 1) {
                //    $akc = "<span style='color: red;font-size: 14px;'><i>под заказ!</i></span><br /><span style='color: grey;'><b>$price</b></span>";
                //} else {
                //    $akc = "<span class=totalPrice>$price</span>";
                //}

                $newitems .= "
                                <table class=cs_product_info>
                                    <tbody>
                                        <tr>
                                            <td width=165px>
                                                $pictures
                                            </td>
                                            <td>
                                                <div class='productname newpostup'>
                                                    <a href='/product/$Product->slug'>$Product->name_ru</a><br>
                                                    <small>категория: &laquo;$category&raquo;</small>
                                                </div>
                                                $add2cart_conc
                                            </td>
                                            <td width=100px>
                                                <a href='/product/$Product->slug'>$Product->product_code</a>
                                            </td>
                                            <td width=60px>
                                                <div class=totalPrice>$bonus</div>
                                            </td>
                                            <td width=80px>
                                                <div class=totalPrice>$price</div>
                                            </td>
                                            <td width=175px>
                                                $add2cart
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                              ";
                if ($vip) {
                    $newitems .= '<hr style=\'border-color: coral\'>';
                } else {
                    $newitems .= '<div class=delimiter></div>';
                }
            }
            $out_end = SimpleNavigator($tov_all_count, $start, $tov_count, $url, &$out_end);
			$newitems .= '
                                </div>';
            $newitems_end = "</div><div
                                    id='light-pagination'
                                    class='simple-pagination compact-theme'
                                    data-items       = $tov_all_count
                                    data-itemsOnPage = $p_count
                                    data-add         = $tov_count
                                    data-show        = 0
                                    data-page        = $start
                                    data-sort        = $ajax_sort
                                    data-direction   = $direction_nav
                                >$out_end</div>";
            $newitems_end .= '<div style="text-align: right;">
                                        <button
                                            class="addall_pp blue-button" onclick="add_all2cart();">Заказать все</button>
                                    </div>';


            if (!$ajax) {
                foreach ($text as $key => $oneline) {
                    $text[$key] = str_replace('%new_list%', $newitems_start.$newitems.$newitems_end, $oneline);
                }
                if ($buy_enabled) {
                    return $text;
                } else {
                    return '</div>';
                }
            } else {
                return $newitems;
            }
        }
    }