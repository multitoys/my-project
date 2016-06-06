<?php
    function smarty_function_m_slidetree($params, &$smarty)
    {
        $query = 'SELECT import from  SC_import_lock';
        $res = mysql_query($query) or die(mysql_error().$query);
        $r = mysql_fetch_row($res);
        $import = (int)$r[0];

        if (isset($_SESSION['import'], $_SESSION['newtree'])
            && (int)$_SESSION['import'] === $import) {
            $disp = $_SESSION['newtree'];

            return $disp;
        }
        $href = (isset($_SESSION['cs_may_order']) && $_SESSION['cs_may_order'] == 1);

        $disp = '';
        
//        if ((int)$_SESSION['cs_vip'] === 1) {
//
//            $conc_disp = '<div id="slidemenu0" class=hide>
//                            <ul>
//                                <li id= competitors class=collapsible>
//                                    <a href="#">Конкуренты</a>
//                                    <ul>
//                                        <li><a href="/auxpage_divoland">Диволенд</a></li>
//                                        <li><a href="/auxpage_mixtoys">Микстойс</a></li>
//                                        <li><a href="/auxpage_dreamtoys">Веселка</a></li>
//                                        <li><a href="/auxpage_grandtoys">Гранд-Тойс</a></li>
//                                    </ul>
//                                </li>
//                            </ul>
//                        </div>';
//            $disp .= $conc_disp;
//        }
        
        $disp .= '<div id="slidemenu1"><ul>';

        $new_items = '';
        $href_new = '/';

        if ($href) {
            $new_items = newItemsCategory();
            $href_new .= 'auxpage_new_items/all/0/0/';
        }

        $disp .= '<li  id=new_postup class=collapsible>
                    <a id=new href="'.$href_new.'">Новые поступления</a>
                    '.$new_items.'
                  </li>'
        ;
        
        if ((int)$_SESSION['cs_vip'] === 1) {
            $disp .= '<li><a href="/category/novinki">Новинки</a></li>';
        }
//        $query = '
//                  SELECT count(*) AS tov_all_count
//                  FROM SC_category_product
//                  WHERE categoryID = 100002
//                 ';
//        $res = mysql_query($query) or die(mysql_error().$query);
//        $product_count = mysql_fetch_object($res);
//        $count_akcia = (int)($product_count->tov_all_count);
        
        $query = '
                  SELECT count(*) AS tov_all_count
                  FROM SC_category_product
                  WHERE categoryID = 100003
                 ';
        $res = mysql_query($query) or die(mysql_error().$query);
        $product_count = mysql_fetch_object($res);
        $count_super_price = (int)($product_count->tov_all_count);

        $sql = '
                SELECT categoryID, slug, name_ru AS name 
                FROM SC_categories 
                WHERE parent=1 
                ORDER BY sort_order, name
               ';
        
        $disp_sub_cat ='';
        
        if ($r = mysql_query($sql)) {

            while ($res = mysql_fetch_assoc($r)) {

                $a = '';
                if ($res['slug'] === 'akcija' || $res['slug'] === 'akcija-bally') {
                    $a = 'style="color: red;text-shadow: 1px 1px 3px rgb(200, 104, 104),-1px -1px 3px rgb(255,255,255);"';
                }

                if ($res['slug'] !== 'novinki'/* && $res['slug'] !== 'akcija'*/) {

                    if ($res['slug'] !== 'akcija-bally' || $count_super_price > 0) {

                        if (isset($_SESSION['log'])) {
                            $disp_sub_cat = subcat($res['categoryID'], $res['slug'], $res['name'], $href);
                        }
                        
                        $disp .= '<li';
                        
                        if ($disp_sub_cat) {
                            $disp .= ' class=collapsible';
                        }
                        $disp .= '>';
                        
                        if ($res['slug'] !== '') {

                            if ($href) {
                                $disp .= '<a ' . $a . ' href="/category/' . $res['slug'] . '">' . $res['name'] . '</a>';
                            } else if (isset($_SESSION['log'])) {
                                $disp .= '<a ' . $a . ' href="/">' . $res['name'] . '</a>';
                            } else {
                                $disp .= '<a ' . $a . ' href="/register/">' . $res['name'] . '</a>';
                            }
                        }
                        $disp .= $disp_sub_cat;
                        
                    }
                }
            }
                        $disp .= '</li>';
        }

        $disp .= '</ul><div style="clear:both"></div></div>';
        
        if (isset($_SESSION['log'])) {

            $_SESSION['newtree'] = $disp;
            $_SESSION['import'] = $import;
        }
        
        return $disp;
    }

    function subcat($parid, $parid_slug, $parid_name, $href)
    {
        $sql = 'SELECT categoryID, slug, name_ru AS name FROM SC_categories WHERE parent='.$parid.' ORDER BY sort_order, name';
        $disp = '';
        $r = mysql_query($sql);
        $disp_sub_cat = '';
        
        if (mysql_num_rows($r) > 0) {

            $disp .= '<ul>';
            $disp .= '<li class="multiSlideMenu-back"><a href="/category/'.$parid_slug.'">'.$parid_name.'</a></li>';

            while ($res = mysql_fetch_assoc($r)) {

                $disp .= '<li';
                $disp_sub_cat = subcat($res['categoryID'], $res['slug'], $res['name'], $href);
                if ($disp_sub_cat) {
                    $disp .= ' class=collapsible';
                }
                $disp .= '>';
                if ($res['slug'] != '') {

                    if ($href) {
                        $disp .= '<a href="/category/' . $res['slug'] . '">' . $res['name'] . '</a>';
                    } else if (isset($_SESSION['log'])) {
                        $disp .= '<a href="/">' . $res['name'] . '</a>';
                    } else {
                        $disp .= '<a href="/register/">' . $res['name'] . '</a>';
                    }
                }
                $disp .= $disp_sub_cat.'</li>';
                unset($disp_sub_cat);
            }

            $disp .= '</ul>';
        }

        return $disp;
    }

    function newItemsCategory()
    {
        $disp = '';

        $sql = "SELECT DISTINCT date
                FROM SC_product_list_item
                WHERE list_id = 'newitemspostup'
                ORDER BY date ASC";

        if ($r = mysql_query($sql)) {

            if (mysql_num_rows($r) > 0) {

                $disp .= '<ul><li class="multiSlideMenu-back"><a href="/auxpage_new_items/all/0/0/">Новые поступления</a></li>';
                $disp .= '<li class=collapsible>';
                $disp .= '<a href="/auxpage_new_items/china/0/0/">Китай</a>';

                $sqlC = "SELECT DISTINCT date
                         FROM SC_product_list_item
                         WHERE list_id = 'newitemspostup' AND ukraine=0
                         ORDER BY date ASC";

                if ($rC = mysql_query($sqlC)) {

                    if (mysql_num_rows($rC) > 0) {

                        $disp .= '<ul><li class="multiSlideMenu-back"><a href="/auxpage_new_items/china/0/0/">Китай</a></li>';

                        while ($resC = mysql_fetch_assoc($rC)) {

                            $date_postup = calcDate($resC['date']);
                            $disp .= '<li>';
                            $disp .= '<a href="/auxpage_new_items/china/'.$resC['date'].'/0/">'.$date_postup.'</a>';
                            $disp .= '</li>';
                        }

                        $disp .= '</ul>';
                    }
                }

                $disp .= '</li>';
                $disp .= '<li class=collapsible>';
                $disp .= '<a href="/auxpage_new_items/ukraine/0/0/">Украина</a>';

                $sqlC = "SELECT DISTINCT date
                         FROM SC_product_list_item
                         WHERE list_id = 'newitemspostup' AND ukraine=1
                         ORDER BY date ASC";

                if ($rC = mysql_query($sqlC)) {

                    if (mysql_num_rows($rC) > 0) {

                        $disp .= '<ul><li class="multiSlideMenu-back"><a href="/auxpage_new_items/ukraine/0/0/">Украина</a></li>';

                        while ($resC = mysql_fetch_assoc($rC)) {

                            $date_postup = calcDate($resC['date']);
                            $disp .= '<li>';
                            $disp .= '<a href="/auxpage_new_items/ukraine/'.$resC['date'].'/0/">'.$date_postup.'</a>';
                            $disp .= '</li>';
                        }

                        $disp .= '</ul>';
                    }
                }

                $disp .= '</li></ul>';
            }
        }

        return $disp;
    }

    function calcDate($date_num)
    {
        $date = time() - (($date_num - 1) * 24 * 60 * 60);
        
        return date('d-m-Y', $date);
    }