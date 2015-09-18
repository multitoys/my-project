<?php
    function smarty_function_conc_tree ($params, &$smarty)
    {
        $auxpage = $params['auxpage'];
        $disp  = '<ul id="navmenu-v"> ';
        $disp .= '<li><a href="" aria-haspopup=true>Конкуренты</a><ul><li><a href="/auxpage_divoland" aria-haspopup=true>Диволенд</a></li><li><a href="/auxpage_mixtoys" aria-haspopup=true>Микстойс</a></li><li><a href="/auxpage_dreamtoys" aria-haspopup=true>Веселка</a></li><li><a href="/auxpage_kindermarket" aria-haspopup=true>Киндер-Маркет</a></li></ul></li>';
        $sql = 'SELECT DISTINCT parent FROM Conc__'.$auxpage.' ORDER BY parent';
        if ($r = mysql_query($sql))
            while ($res = mysql_fetch_assoc($r)) {
                if ($res['parent'] !== '') {
                    $disp .= '<li><a href="/auxpage_'.$auxpage.'?div_par='.$res['parent'].'" aria-haspopup=true>'.$res['parent'].'</a>';
                    $disp .= subcategory($res['parent'], $auxpage).'';
                }
            }
        $disp .= '</ul>';
        return $disp;
    }

    function subcategory ($parid, $auxpage)
    {
        $sql = "SELECT DISTINCT category FROM Conc__$auxpage WHERE parent='$parid'";
        $disp = '';
        if ($r = mysql_query($sql)) {
            if (mysql_num_rows($r) > 0) {
                $disp .= '<ul class="animated slideInRight">';
                while ($res = mysql_fetch_assoc($r)) {
                    if ($res['category'] !== '') {
                        $disp .= '<li>';
                        $disp .= '<a href="/auxpage_'.$auxpage.'?div_cat='.$res['category'].'" aria-haspopup=true>'.$res['category'].'</a>';
                    }
                }
                $disp .= '</li></ul>';
            }
        }
        return $disp;
    }