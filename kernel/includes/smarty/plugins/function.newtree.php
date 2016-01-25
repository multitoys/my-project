<?php
    function smarty_function_newtree($params, &$smarty)
    {
        if (isset($_SESSION['newtree'])) {
            $disp = $_SESSION['newtree'];
        
            return $disp;
        }
        $haspopup = $params['haspopup'];
        $disp = '<ul id="navmenu-v">';
        if ((int)$_SESSION['cs_vip'] === 1) {
            $disp .= '<li style="background: tomato">
                        <a href="#" '.$haspopup.' style="
                            color: floralwhite;
                            text-shadow: 1px 1px 1px rgba(0, 0, 0, .4) !important;
                            font-size: 16px;
                            ">Конкуренты</a>
                        <ul class="animated slideInRight">
                            <li><a href="/auxpage_divoland" '.$haspopup.'>Диволенд</a></li>
                            <li><a href="/auxpage_mixtoys" '.$haspopup.'>Микстойс</a></li>
                            <li><a href="/auxpage_dreamtoys" '.$haspopup.'>Веселка</a></li>
                            <li><a href="/auxpage_kindermarket" '.$haspopup.'>Киндер-Маркет</a></li>
                            <li><a href="/auxpage_grandtoys" '.$haspopup.'>Гранд-Тойс</a></li>
                        </ul>
                    </li>
                    <li><a href="/category/novinki" '.$haspopup.'>Новинки</a></li>';
        }
        $query = '
                    SELECT count(*) AS tov_all_count
                    FROM SC_category_product
                    WHERE categoryID = 100003
                  ';
        $res = mysql_query($query) or die(mysql_error().$query);
        $product_count = mysql_fetch_object($res);
        $count = (int)($product_count->tov_all_count);
        //echo $count;
    
        $sql = 'SELECT categoryID, slug, name_ru AS name FROM SC_categories WHERE parent=1 ORDER BY sort_order, name';
        if ($r = mysql_query($sql)) {
    
            while ($res = mysql_fetch_assoc($r)) {
                $a = '';
                if ($res['slug'] === 'akcija' || $res['slug'] === 'akcija-bally') {
                    $a = 'style="color: red;text-shadow: 1px 1px 3px rgb(200, 104, 104),-1px -1px 3px rgb(255,255,255);"';
                }
                if ($res['slug'] !== 'novinki' && $res['slug'] !== 'akcija') {
                    if ($res['slug'] !== 'akcija-bally' || $count > 0) {
                        $disp .= '<li>';
                        if ($res['slug'] !== '')
                            $disp .= '<a '.$a.' href="/category/'.$res['slug'].'" '.$haspopup.'>'.$res['name'].'</a>';
                        else
                            $disp .= '<a href="?categoryID='.$res['categoryID'].'">'.$res['name'].'</a>';
                        $disp .= subcat($res['categoryID'], $haspopup).'';
                    }
                }
            }
            $disp .= '</li>
                      <li style="background: tomato">
                        <a href="/auxpage_new_items/0/all/" 
                            '.$haspopup.' 
                            style="color: floralwhite;
                            text-shadow: 1px 1px 1px rgba(0, 0, 0, .4) !important;
                            font-size: 16px;
                            ">Новые поступления</a>
                            '.newItemsCategory($haspopup).'
                      </li>
                    </ul>';
        }
    
        if (isset($_SESSION['log'])) {
            $_SESSION['newtree'] = $disp;
        }
    
        return $disp;
    }
    
    function subcat($parid, $haspopup = '')
    {
        $sql = 'SELECT categoryID, slug, name_ru AS name FROM SC_categories WHERE parent='.$parid.' ORDER BY sort_order, name';
        $disp = '';
        $r = mysql_query($sql);
        if (mysql_num_rows($r) > 0) {
            $disp .= '<ul class="animated slideInRight">';
            while ($res = mysql_fetch_assoc($r)) {
                $disp .= '<li '.$haspopup.'>';
                if ($res['slug'] != '')
                    $disp .= '<a href="/category/'.$res['slug'].'">'.$res['name'].'</a>';
                else
                    $disp .= '<a href="?categoryID='.$res['categoryID'].'">'.$res['name'].'</a>';
                $disp .= subcat($res['categoryID'], $haspopup).'';
            }
            $disp .= '</li></ul>';
        }
    
        return $disp;
    }
    
    function newItemsCategory($haspopup = '')
    {
        $disp = '';
    
        $sql = "SELECT DISTINCT date
                FROM SC_product_list_item
                WHERE list_id = 'newitemspostup'
                ORDER BY date ASC";
    
        if ($r = mysql_query($sql)) {
            if (mysql_num_rows($r) > 0) {
                $disp .= '<ul id=newmenu class="animated slideInRight">';
                $disp .= '<li '.$haspopup.'>';
                $disp .= '<a href="/auxpage_new_items/0/china/" '.$haspopup.'>Китай</a>';
    
                $sqlC = "SELECT DISTINCT date
                FROM SC_product_list_item
                WHERE list_id = 'newitemspostup' AND ukraine=0
                ORDER BY date ASC";
                
                if ($rC = mysql_query($sqlC)) {
                    if (mysql_num_rows($rC) > 0) {
                        $disp .= '<ul>';
                        while ($resC = mysql_fetch_assoc($rC)) {
                            $date_postup = calcDate($resC['date']);
                            $disp .= '<li '.$haspopup.'>';
                            $disp .= '<a href="/auxpage_new_items/'.$resC['date'].'/china/" '.$haspopup.'>'.$date_postup.'</a>';
                            $disp .= '</li>';
                        }
                        $disp .= '</ul>';
                    }
                }
                $disp .= '</li>';
                
                $disp .= '<li '.$haspopup.'>';
                $disp .= '<a href="/auxpage_new_items/0/ukraine/" '.$haspopup.'>Украина</a>';
    
                $sqlC = "SELECT DISTINCT date
                FROM SC_product_list_item
                WHERE list_id = 'newitemspostup' AND ukraine=1
                ORDER BY date ASC";
                
                if ($rC = mysql_query($sqlC)) {
                    if (mysql_num_rows($rC) > 0) {
                        $disp .= '<ul>';
                        while ($resC = mysql_fetch_assoc($rC)) {
                            $date_postup = calcDate($resC['date']);
                            $disp .= '<li '.$haspopup.'>';
                            $disp .= '<a href="/auxpage_new_items/'.$resC['date'].'/ukraine/" '.$haspopup.'>'.$date_postup.'</a>';
                            $disp .= '</li>';
                        }
                        $disp .= '</ul>';
                    }
                }
                $disp .= '</li>';
                $disp .= '</ul>';
            }
        }
    
        return $disp;
    }
    
    function calcDate($date_num)
    {
        $date = time() - (($date_num - 1) * 24 * 60 * 60);
        $date_num = date('d-m-Y', $date);
        
        return $date_num;
    }