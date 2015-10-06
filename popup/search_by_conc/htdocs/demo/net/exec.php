<?php
    $server = "www.specialist.ru";
    exec("ping $server > ping.txt");
    echo "<pre>";
    readfile("ping.txt");
    echo "</pre>";
?>