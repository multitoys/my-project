<?php
    /**
     * Created by PhpStorm.
     * User: Gololobov
     * Date: 20.11.2015
     * Time: 12:25
     */
    
    if (isset($_SESSION['progress'])) {
        
        ini_set('display_errors', true);
        define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'].'/published/SC/html/scripts');
        
        include_once(DIR_ROOT.'/includes/init.php');
        include_once(DIR_CFG.'/connect.inc.wa.php');
        include(DIR_FUNC.'/setting_functions.php');
        include(DIR_FUNC.'/import_functions.php');
        
        $progress = (float)$_SESSION['progress'];
        
        echo $progress;
    }
