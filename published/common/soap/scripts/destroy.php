<?php

    require_once("../../../common/html/includes/httpinit.php");

    $SID = $_COOKIE['WBS_SID'];

    if (strlen($SID)) {
        @session_unset();
        @session_destroy();
    }

    setcookie(WBS_USERNAME, "", time() - 3600, "/");
    setcookie(WBS_DBKEY, "", time() - 3600, "/");
    setcookie(WBS_SID, "", time() - 3600, "/");
?>