<?php
    /**
     * Smarty plugin
     * -------------------------------------------------------------
     * Type:     function
     * Name:     zakcia
     * Purpose:  akcia
     * -------------------------------------------------------------
     */
    function smarty_function_zakcia($params, &$smarty)
    {
        if ((isset($_GET['categoryID']) 
                && (int)($_GET['categoryID']) === 33424 
                && isset($_SESSION['log'])) || (!isset($_GET['categoryID']) 
                && isset($_SESSION['log']))) {
            
            $texts = get_akcia_body();
            
            if ($texts) {
                
                //$i = 0;
    
                for ($i = count($texts) - 1; $i >= 0; $i--) {
                    $texts[$i]['num'] = $i;
                    $texts[$i]['za_body'] =$texts[$i]['text_ru'];
                    $texts[$i]['seconds'] = $texts[$i]['act_time'];
                    
                }
    
                $smarty->assign("Akcias", $texts);
                //$smarty->assign("Akcias_num", $i);
                $output = $smarty->fetch(DIR_TPLS."/frontend/zakcia.html");
    
                return $output;
                
            } else return '';
            
        } else return '';
        
    }
    
    function get_akcia_body()
    {
        // $q = db_query("SELECT text_ru, TIME_TO_SEC(TIMEDIFF(`end_date`, now())) as act_time from SC_akcia where end_date>now() and start_date<now() and enabled=1");
        $q = db_phquery_fetch(DBRFETCH_ROW_ALL, 
            'SELECT text_ru, TIME_TO_SEC(TIMEDIFF(`end_date`, Now())) AS act_time 
             FROM SC_akcia WHERE end_date > now() AND enabled = 1');
        //$r = db_num_rows($q);
        //$r = db_fetch_row($q);
        
        return $q;
    }
