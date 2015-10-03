<?php
    extract($_GET);
    header("Content-type: application/x-compressed");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $fd = fopen(base64_decode($filename), "r");
    while ($contents = fread($fd, 1024))
        print $contents;
    fclose($fd);
?>  