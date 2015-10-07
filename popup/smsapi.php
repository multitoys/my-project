<?php
    /**
     * Created by PhpStorm.
     * User: multi
     * Date: 13.09.2015
     * Time: 22:35
     */
    ini_set('display_errors', true);
    define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'].'/published/SC/html/scripts');
    include_once(DIR_ROOT.'/includes/smslogpass.php');

    $sUrl = 'http://letsads.com/api';
    $sXML = '<?xml version="1.0" encoding="UTF-8"?>
                <request>
                    <auth>
                        <login>'.SMS_LOGIN.'</login>
                        <password>'.SMS_PASSWORD.'</password>
                    </auth>
                    <balance />
                </request>';

    $rCurl = curl_init($sUrl);
    curl_setopt($rCurl, CURLOPT_HEADER, 0);
    curl_setopt($rCurl, CURLOPT_POSTFIELDS, $sXML);
    curl_setopt($rCurl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($rCurl, CURLOPT_POST, 1);
    $sAnswer = curl_exec($rCurl);
    echo($sAnswer);
    curl_close($rCurl);