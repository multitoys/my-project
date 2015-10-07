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
    curl_close($rCurl);
    echo($sAnswer);

    $replace = array('Balance', 'UAH');
    $balance = str_replace($replace, '', $sAnswer);
    echo($balance);

    if ($balance < 1000) {
        $sAnswer = preg_match_all('|<description>(\d+\.\d+)</description>|',
            $sAnswer,
            $balance,
            PREG_PATTERN_ORDER);
        define('BALANCE', $balance);
        $sUrl = 'http://letsads.com/api';
        $sXML = '<?xml version="1.0" encoding="UTF-8"?>
                <request>
                    <auth>
                        <login>'.SMS_LOGIN.'</login>
                        <password>'.SMS_PASSWORD.'</password>
                    </auth>
                    <message>
                        <from>Multitoys</from>
                        <text>'.BALANCE.'</text>
                        <recipient>380676383204</recipient>
                    </message>
                </request>';

        $rCurl = curl_init($sUrl);
        curl_setopt($rCurl, CURLOPT_HEADER, 0);
        curl_setopt($rCurl, CURLOPT_POSTFIELDS, $sXML);
        curl_setopt($rCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($rCurl, CURLOPT_POST, 1);
        $sAnswer = curl_exec($rCurl);
        //include($_SERVER['DOCUMENT_ROOT'].'/popup/smsauth.php');
    }
    curl_close($rCurl);
    echo($sAnswer);
