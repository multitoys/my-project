<?php
    function smarty_function_conc_tree ($params, &$smarty)
    {
        $auxpage = $params['auxpage'];
        $disp = '<div id="slidemenu1"><ul>';
        $disp .= '<li class=collapsible><a href="/">Конкуренты</a><ul><li><a href="/auxpage_divoland">Диволенд</a></li><li><a href="/auxpage_mixtoys">Микстойс</a></li><li><a href="/auxpage_dreamtoys">Веселка</a></li><li><a href="/auxpage_grandtoys">Гранд-Тойс</a></li></ul></li>';
        $sql = 'SELECT DISTINCT parent FROM Conc__'.$auxpage.' ORDER BY parent';
        if ($r = mysql_query($sql))
            while ($res = mysql_fetch_assoc($r)) {
                if ($res['parent'] !== '') {
                    $disp .= '<li class=collapsible><a href="/auxpage_'.$auxpage.'?div_par='.$res['parent'].'">'.$res['parent'].'</a>';
                    $disp .= subcategory($res['parent'], $auxpage).'';
                }
            }
        $disp .= '</ul></div>';
        return $disp;
    }

    function subcategory ($parid, $auxpage)
    {
        $sql = "SELECT DISTINCT category FROM Conc__$auxpage WHERE parent='$parid'";
        $disp = '';
        if ($r = mysql_query($sql)) {
            if (mysql_num_rows($r) > 0) {
                $disp .= '<ul>';
                while ($res = mysql_fetch_assoc($r)) {
                    if ($res['category'] !== '') {
                        $disp .= '<li>';
                        $disp .= '<a href="/auxpage_'.$auxpage.'?div_cat='.$res['category'].'">'.$res['category'].'</a>';
                    }
                }
                $disp .= '</li></ul>';
            }
        }
        return $disp;
    }