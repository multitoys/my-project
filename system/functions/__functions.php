<?php

// REQUEST_URI for the windows servers IIS
    if (!isset($_SERVER['REQUEST_URI'])) {
        $_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];
    }

// Correct magic_quotes_gpc
    if ((function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()) ||
        (ini_get('magic_quotes_sybase') && strtolower(ini_get('magic_quotes_sybase')) != "off")
    ) {
        function stripcslashes_array($a)
        {
            foreach ($a as $k => $v) {
                if (is_array($v)) {
                    $a[$k] = stripcslashes_array($v);
                } else {
                    $a[$k] = stripcslashes($v);
                }
            }

            return $a;
        }

        $_GET = stripcslashes_array($_GET);
        $_POST = stripcslashes_array($_POST);
        $_COOKIE = stripcslashes_array($_COOKIE);
    }

// Module gettext
    if (!function_exists("gettext")) {
        include(dirname(__FILE__)."/gettext.php");
    }

// JSON, since PHP 5.2 exists
    if (!function_exists("json_encode")) {
        include(dirname(__FILE__)."/json.php");
    }

?>