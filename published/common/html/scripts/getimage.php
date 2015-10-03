<?php

    function get_mime($fileName)
    {
        preg_match('/\.(.*?)$/', $fileName, $m);
        switch (strtolower($m[1])) {
            case 'jpg':
            case 'jpeg':
            case 'jpe':
                return 'image/jpg';
            case 'png':
            case 'gif':
            case 'bmp':
            case 'tiff' :
                return 'image/'.strtolower($m[1]);
            default:
                return 'image/gif';
        }
    }

    $fileName = base64_decode(str_replace(' ', '+', $_GET['file']));
    $ctype = get_mime($fileName);

    $path = $_SERVER['DOCUMENT_ROOT'].str_replace(
            '/common/html/scripts/getimage.php',
            '/data/'.base64_decode($_GET['user']).'/attachments/mm/images/'.$_GET['msg'].'/'.$fileName,
            $_SERVER['SCRIPT_NAME']
        );
    $path = str_replace('/published', '', $path);

    header("Content-type: $ctype");
    print file_get_contents($path);

?>