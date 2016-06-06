<?php
    /**
     * Smarty plugin
     * -------------------------------------------------------------
     * Type:     function
     * Name:     miniTimer
     * Purpose:  akcia
     * -------------------------------------------------------------
     */
    function smarty_function_miniTimer($params, &$smarty)
    {
        if (isset($_GET['categoryID'], $_SESSION['log'])) {
                
                return get_end_time();
                
            } else return '';
    }
    
    function get_end_time()
    {
        $q = db_query('SELECT TIME_TO_SEC(TIMEDIFF(`end_date`, Now())) AS act_time 
             FROM SC_akcia WHERE start_date < now() 
             AND end_date > now() AND enabled = 1 
             AND (ISNULL(sending) OR sending<>1) ORDER BY `end_date` DESC LIMIT 1');
        $r = db_fetch_row($q);
        
        return date("Y/m/d H:i:s", time() + $r[0]);
    }
