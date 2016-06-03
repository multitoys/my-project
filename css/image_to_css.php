<?php
    /**
     * Created by PhpStorm.
     * User: multi
     * Date: 25.03.2016
     * Time: 18:36
     */
    
    echo preg_replace(
        '/images\/[-\w\/\.]*/ie', 
        '"data:image/".((substr("\\0",-4)==".png")?"png":"gif").";base64,".base64_encode(file_get_contents("\\0"))', 
        file_get_contents('images.css')
    );