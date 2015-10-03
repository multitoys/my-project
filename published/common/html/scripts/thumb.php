<?php
    ini_set('session.cookie_lifetime', 2592000);
    session_set_cookie_params(2592000);
    ini_set('session.use_only_cookies', 0);

    include_once '../../../../system/init.php';

    $filename = Env::Get('basefile', Env::TYPE_BASE64, "");
    $ext = mb_strtolower(Env::Get('ext', Env::TYPE_BASE64, ""));
    $size = Env::Get('size', Env::TYPE_INT, -1);

    if ($ext == 'gif') {
        header('Content-type: image/gif');
    } else {
        header('Content-type: image/jpeg');
    }

    if ($size) {
        if (file_exists($filename.".".$size.".".$ext)) {
            readfile($filename.".".$size.".".$ext);
        } elseif (file_exists($filename.".".$ext)) {
            readfile($filename.".".$ext);
        }
    } else {
        if (file_exists($filename)) {
            readfile($filename);
        } elseif (file_exists($filename.".".$ext)) {
            readfile($filename.".".$ext);
        }
    }
?>