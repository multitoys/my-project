<?php
    // See http://pear.php.net/package/Mail_Mime/docs/1.5.2/Mail_Mime/Mail_mime.html#class_details

    define('GET_DBKEY_FROM_URL', true);
    include_once '../../../system/init.php';
    include_once 'mailconsts.php';
    Wbs::publicAuthorize();

    $wbsPath = Wbs::getSystemObj()->files()->getWbsPath(); // . '/published'; // = $_SERVER['DOCUMENT_ROOT']

    define('TEMP_PATH', '../../../temp');

    $logPath = TEMP_PATH.'/log';
    $pointerPath = TEMP_PATH.'/sendmail';
    if (!is_dir($logPath))
        mkdir($logPath);
    if (!is_dir($pointerPath))
        mkdir($pointerPath);

    $logFile = fopen($logPath.'/send.log', 'a');

    /*
        define( 'PEAR_PATH', realpath($wbsPath.'kernel/includes/pear') );
        $paths = array(realpath( PEAR_PATH ),realpath( PEAR_PATH.'/PEAR' ));
        set_include_path( implode(((DIRECTORY_SEPARATOR=='/')?':':';'),$paths) );

        include_once 'Mail/mime.php';
    */
    require_once 'mailmime.php';
    require_once 'socketmail.php';
    require_once 'mailparse.php';

    global $log, $logFile, $DB_KEY;

    if (!$DB_KEY = base64_decode(WebQuery::getParam('DB_KEY')))
        exitOnError('No DB_KEY');

    if ($logFile) fwrite($logFile, date('Y-m-d H:i:s').' -> get '.$_SERVER['HTTP_HOST'].' '.$_SERVER['REQUEST_URI']." ($DB_KEY)\n");

    if ($MMM_ID = WebQuery::getParam('MMM_ID')) {
        $msgIds = unserialize(base64_decode($MMM_ID));
        try {
            $sql = new CSelectSqlQuery('MMMESSAGE');
            $sql->addConditions("MMM_ID='".join("' OR MMM_ID='", $msgIds)."'");
            $sql->addConditions("MMM_STATUS='".MM_STATUS_SENDING."'");
            $docList = Wdb::getData($sql);
        } catch (Exception $e) {
            exitOnError($e->getMessage());
        }
    } else {
        $msgIds = false;
        $sql = new CSelectSqlQuery('MMMESSAGE');

        $sql->addConditions("MMM_STATUS='".MM_STATUS_PENDING."' OR MMM_STATUS='".MM_STATUS_SENDING."'");
        $sql->addConditions("MMM_DATETIME<=NOW()");
        $docList = Wdb::getData($sql);

        if ($docList) {
            $sql = new CUpdateSqlQuery('MMMESSAGE');
            $hash = array('MMM_STATUS' => MM_STATUS_SENDING);
            $sql->addFields($hash, array_keys($hash));
            $sql->addConditions("MMM_STATUS='".MM_STATUS_PENDING."'");
            $sql->addConditions("MMM_DATETIME<=NOW()");
            Wdb::runQuery($sql);
        }

    }

    if (!$xml = @file_get_contents("$wbsPath/kernel/wbs.xml"))
        exitOnError('XML open error');

    $sxml = new SimpleXMLElement($xml);
    if (sizeof(@(array)$sxml->SMTP_SERVER->attributes()) == 0)
        $host = $port = $user = $pass = $connect = false;
    else {
        $host = (string)$sxml->SMTP_SERVER->attributes()->host;
        $port = (string)$sxml->SMTP_SERVER->attributes()->port;
        $user = (string)$sxml->SMTP_SERVER->attributes()->user;
        $pass = (string)$sxml->SMTP_SERVER->attributes()->password;

        global $connect;
        $connect = false;
    }

    if ($logFile) fwrite($logFile, "-> $DB_KEY docList count: ".count($docList)."\n");

    foreach ($docList as $message) {
        // try to get current pointer and test if another copy of this script is already working
        $pointerPath = TEMP_PATH."/sendmail/$DB_KEY~{$message['MMM_ID']}";
        $pointerTmpPath = TEMP_PATH."/sendmail/$DB_KEY~{$message['MMM_ID']}~tmp";

        @touch($pointerPath);
        @touch($pointerTmpPath);

        $pointer = @file_get_contents($pointerPath);
        sleep(1);

        if ($pointer != @file_get_contents($pointerPath))
            continue;

        if (!$pointer)
            $pointer = 0;

        if (preg_match('/^(.*)<(.*)>$/', $message['MMM_FROM'], $match)) {
            $from_name = trim($match[1]);
            $from_email = $match[2];
        } else {
            $from_name = '';
            $from_email = $message['MMM_FROM'];
        }

        $send_to = array();

        if ($lists = $message['MMM_LISTS']) {
            $lists = explode(',', $lists);

            $sql = new CSelectSqlQuery('CLIST_CONTACT', 'CLC');
            $sql->innerJoin('CONTACT', 'C', 'CLC.C_ID=C.C_ID');
            $sql->addConditions("CLC.CL_ID='".join("' OR CLC.CL_ID='", $lists)."'");
            $sql->addConditions("C.C_EMAILADDRESS<>''");
            $sql->setGroupBy('C.C_EMAILADDRESS, C.C_FIRSTNAME, C.C_LASTNAME');

            try {
                $res = Wdb::runQuery($sql);
            } catch (Exception $e) {
                exitOnError($e->getMessage())." 1";
            }

            while ($row = mysql_fetch_assoc($res))
                $send_to[joinContactAddress($row)] = $row['C_ID'];

            $sql = new CSelectSqlQuery('CONTACT', 'C');
            $sql->innerJoin('CLIST_FOLDER', 'CLF', 'C.CF_ID=CLF.CF_ID');
            $sql->addConditions("CLF.CL_ID='".join("' OR CLF.CL_ID='", $lists)."'");
            $sql->addConditions("C.C_EMAILADDRESS<>''");

            try {
                $res = Wdb::runQuery($sql);
            } catch (Exception $e) {
                exitOnError($e->getMessage())." 2";
            }

            while ($row = mysql_fetch_assoc($res))
                $send_to[joinContactAddress($row)] = $row['C_ID'];
        }
        if ($send_to)
            $listsIsSet = true;
        else
            $listsIsSet = false;

        // TODO: add $MMM_CONTACTS here

        $tomore = $bounced = array();
        $addr = parseAddressString($message['MMM_TO'], false);
        $tomore['TO'] = $addr['accepted'];
        $bounced = $addr['bounced'];
        $addr = parseAddressString($message['MMM_CC'], false);
        $tomore['CC'] = $addr['accepted'];
        if ($addr['bounced']) $bounced = array_merge($bounced, $addr['bounced']);
        $addr = parseAddressString($message['MMM_BCC'], false);
        $tomore['BCC'] = $addr['accepted'];
        if ($addr['bounced']) $bounced = array_merge($bounced, $addr['bounced']);
        if ($tomore) {
            $to_sql = $to_addr = array();
            foreach ($tomore as $key => $item) {
                for ($i = 0; $i < count($item); $i++) {
                    $to_sql[] = $item[$i]['email'];

                    $str = array();
                    if ($item[$i]['name']) $str[] = $item[$i]['name'];
                    if ($item[$i]['email']) $str[] = '<'.$item[$i]['email'].'>';
                    $to_addr[] = array(
                        'addr'  => join(' ', $str),
                        'name'  => $item[$i]['name'],
                        'email' => $item[$i]['email']
                    );
                }
            }
            if ($to_sql) {
                $sql = new CSelectSqlQuery('CONTACT');
                $sql->addConditions("C_EMAILADDRESS='".join("' OR C_EMAILADDRESS='", $to_sql)."'");
                $sql->setGroupBy('C_EMAILADDRESS, C_FIRSTNAME, C_LASTNAME');

                try {
                    $res = Wdb::runQuery($sql);
                } catch (Exception $e) {
                    exitOnError($e->getMessage());
                }

                while ($row = mysql_fetch_assoc($res))
                    $try_it[joinContactAddress($row)] = $row;

                foreach ($to_addr as $addr) {
                    if (isset($try_it[$addr['addr']]))
                        $send_to[$addr['addr']] = $try_it[$addr['addr']]['C_ID'];
                    else
                        $send_to[$addr['addr']] = 0;
                }
            }
        }

        $sendFull = array();
        foreach ($send_to as $address => $cid)
            $sendFull[] = array('addr' => $address, 'id' => $cid);

        // check daily send limit for current hosting plan
        $sql = new CSelectSqlQuery('MMSENT');
        $sql->setSelectFields('MMS_COUNT');
        $sql->addConditions("MMS_DATE='".date('Y-m-d')."'");
        if (!$dailySent = Wdb::getFirstField($sql))
            $dailySent = 0;

        if (MM_DAILY_SEND_LIMIT && (count($sendFull) > (MM_DAILY_SEND_LIMIT - $dailySent)))
            $forceError = 'Daily send limit is reached';
        else
            $forceError = '';

        $sendCurrent = array_slice($sendFull, $pointer);

        $message['MMM_CONTENT'] = str_replace("\n.", "\n .", $message['MMM_CONTENT']); // fix some mail servers bug;

//		$content = $message['MMM_CONTENT'];
//		$content = str_replace("\n.", "\n .", $content); // fix some mail servers bug
//		$subject = $message['MMM_SUBJECT'];

        // Try to find variables for extraction and extract sender and company variables)
        MailMessage::extractSenderVars($message);
        /*
                // Try to find variables for extraction
                $sql = new CSelectSqlQuery('CONTACT');
                $sql->addConditions("C_ID=(SELECT C_ID FROM WBS_USER WHERE U_ID='".$message['MMM_USERID']."')");
                try { $sender = Wdb::getRow($sql); }
                catch (Exception $e) { continue; }

                $vars = ContactDescriptor::getFields();
                unset($vars['C_X_PHOTO']);
                $contactVariables = array('NAME' => array());
                $contactVariables = array_merge($contactVariables, $vars);

                $doExtract = false;
                foreach($contactVariables as $key=>$val)
                {
                    if((strpos($content, '{'.$key.'}') !== false) || (strpos($subject, '{'.$key.'}') !== false))
                        $doExtract = true;

                    // replace sender variable here
                    $senderVariable = str_replace('C_', 'MY_', $key);
                    if($key != 'NAME' && $key != 'MY_NAME') {
                        $content = str_ireplace('{'.$senderVariable.'}', $sender[$key], $content);
                        $subject = str_ireplace('{'.$senderVariable.'}', $sender[$key], $subject);
                    }
                }

                try
                {
                    $sql = new CSelectSqlQuery('COMPANY');
                    $comVars = Wdb::getRow($sql);
                }
                catch (Exception $e) { exitOnError($e->getMessage()); }

                foreach($companyVariables as $key=>$val)
                {
                    if(strpos($content, '{'.$key.'}') !== false)
                        $doExtract = true;
                    // replace company variable here
                    $content = str_ireplace('{'.$key.'}', $comVars[$val[1]], $content);
                    $subject = str_ireplace('{'.$key.'}', $comVars[$val[1]], $subject);
                }

                $user_name = Users::getUsername($message['MMM_USERID']);
                $content = str_ireplace('{MY_NAME}', $user_name, $content);
                $subject = str_ireplace('{MY_NAME}', $user_name, $subject);

                if(strpos($content, '{UNSUBSCRIBE}') !== false || strpos($content, '{MANAGE_YOUR_SUBSCRIPTION_URL}') !== false)
                    $doExtract = true;
        */
        /*
        //		WBS_URL = sprintf( "%s%s", end(explode(".", $_SERVER['HTTP_HOST'], 2)), Url::get());
                if(Wbs::isHosted())
                    $WBS_URL = sprintf('%s%s',
                        ($_SERVER["SERVER_PORT"] == 43 ? "https://" : "http://").$_SERVER['HTTP_HOST'],
                        Url::get(''));
                else
                    $WBS_URL = Url::get('');
        */
        /*
                $wbs_host = Wbs::isHosted() ?
                    ($_SERVER['SERVER_PORT'] == 43 ? 'https://' : 'http://').$_SERVER['HTTP_HOST'] : '';
                $WBS_URL = $wbs_host.Url::get('');
        */
        $WBS_URL = Url::get('', true);

        $sentCount = 0;
        $errorStr = '';

        if (!count($sendCurrent)) {
            $errorStr = 'Contact(s) not found';
        }

        if ($logFile) fwrite($logFile, "-> $DB_KEY messages count: ".count($sendCurrent)."\n");

        // start sending current message
        for ($count = 0; $count < count($sendCurrent); $count++) // *****************
        {
            $full_address = $sendCurrent[$count]['addr'];
            $cid = $sendCurrent[$count]['id'];
            $currentMessage = $message;

            if (!preg_match('/^(.*)<(.*)>$/', $full_address, $match))
                continue;

            $to_name = trim($match[1]);
            $to_email = $match[2];

            $model = new DbModel();
            $res = $model->prepare("SELECT * FROM UNSUBSCRIBER WHERE ENS_EMAIL = s:email")->query(array('email' => $to_email))->fetchRow();

            if ($res)
                $MMMST_STATUS = 'Unsubscribed';
            else {
                if ($message['doExtract']) // || $listsIsSet)
                {
                    MailMessage::extractRecipientVars($currentMessage, $cid, $WBS_URL);
                    /*
                                        if($cid)
                                        {
                                            $sql = new CSelectSqlQuery('CONTACT');
                                            $sql->addConditions("C_ID='$cid'");
                                            try { $contact = Wdb::getRow($sql); }
                                            catch (Exception $e) { continue; }

                                            $href = "$WBS_URL/CM/html/scripts/confirm.php?DB_KEY="
                                                .base64_encode($DB_KEY)
                                                .'&C_ID='.base64_encode($cid)
                                                .'&E='.md5($contact['C_EMAILADDRESS']);
                                            $currentContent = str_replace('{UNSUBSCRIBE}', $href, $currentContent);
                                            $currentContent = str_replace('{MANAGE_YOUR_SUBSCRIPTION_URL}', $href, $currentContent); // TODO: delete it 01.01.2010

                                            // this is the copy of Users::getUserDisplayName()
                                            $cname = array();
                                            if(strlen($contact['C_FIRSTNAME'])) $cname[] = $contact['C_FIRSTNAME'];
                                            if(strlen($contact['C_MIDDLENAME'])) $cname[] = substr($contact['C_MIDDLENAME'], 0, 1 ).".";
                                            if(strlen($contact['C_LASTNAME']) ) $cname[] = $contact['C_LASTNAME'];
                                            if(isset($contact['C_NICKNAME']) && strlen($contact['C_NICKNAME']))
                                                $cname[] = (count($cname)) ? "(".$contact['C_NICKNAME'].")" : $contact['C_NICKNAME'];
                                            if(isset($contact['C_EMAILADDRESS']) && strlen($contact['C_EMAILADDRESS']) && !count($cname))
                                                $cname[] = '<'.$contact['C_EMAILADDRESS'].'>';
                                            $contact['NAME'] = implode(' ', $cname);

                                            // replace contact variables
                                            foreach($contactVariables as $key=>$val) {
                                                $currentContent = str_ireplace('{'.$key.'}', htmlspecialchars($contact[$key]), $currentContent);
                                                $currentSubject = str_ireplace('{'.$key.'}', $contact[$key], $currentSubject);
                                            }
                                        }
                                        else
                                        {
                                            foreach($contactVariables as $key=>$val) {
                                                $currentContent = str_ireplace('{'.$key.'}', '', $currentContent);
                                                $currentSubject = str_ireplace('{'.$key.'}', '', $currentSubject);
                                            }
                                            $currentContent = str_replace('{UNSUBSCRIBE}', '', $currentContent);
                                        }
                    */
                }
                $currentContent = $currentMessage['MMM_CONTENT'];
                $currentSubject = $currentMessage['MMM_SUBJECT'];

                $plainText = html2plain($currentContent);

                if (!preg_match('/<html.*>/i', $currentContent) && !preg_match('/<head.*>/i', $currentContent) &&
                    !preg_match('/<body.*>/i', $currentContent)
                )
                    $currentContent = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>'
                        ."\n<body>\n$currentContent\n</body></html>\n";

                if (!$forceError) {
                    $out = array();
                    foreach ($tomore as $key => $item) {
                        $enc = array();
                        for ($i = 0; $i < count($item); $i++)
                            $enc[] = EncodeHeader($item[$i]['name']).' <'.$item[$i]['email'].'>';
                        $to_out[$key] = join(', ', $enc);
                    }

                    if ($listsIsSet) {
                        $to_out['TO'] = EncodeHeader($to_name).' <'.$to_email.'>';
                        $to_out['CC'] = '';
                    }

                    $currentSubject = EncodeHeader($currentSubject);

                    $from_encoded = trim(EncodeHeader($from_name).' <'.$from_email.'>');

                    /*
                                        $mimeparam = Array(
                                            'head_encoding' => 'base64',
                                            'head_charset'  => 'utf-8',
                                            'text_encoding' => '8bit',
                                            'text_charset'  => 'utf-8',
                                            'html_encoding' => '8bit',
                                            'html_charset'  => 'utf-8'
                                        );
                    */

                    if ($host) {
                        $crlf = "\r\n";
                        $mime = new Mail_mime($crlf);

                        $mime->setTXTBody($plainText.$crlf);
                        $mime->setHTMLBody($currentContent.$crlf);

                        if ($message['MMM_ATTACHMENT']) {
                            $xml = base64_decode($message['MMM_ATTACHMENT']);
                            try {
                                $sxml = new SimpleXMLElement($xml);

                                $i = 0;
                                if (count((array)$sxml->FILE)) {
                                    while ($file = (array)$sxml->FILE[$i]) {
                                        $fileName = base64_decode($file['@attributes']['FILENAME']);
                                        $mime->addAttachment(
                                            "$wbsPath/data/$DB_KEY/attachments/mm/attachments/{$message['MMM_ID']}/$fileName",
                                            $file['@attributes']['MIME_TYPE'],
                                            EncodeHeader($fileName)
                                        );
                                        $i++;
                                    }
                                }
                            } catch (Exception $e) {
                                if ($logFile) fwrite($logFile, "-> error: ".$e->getMessage()."\n");
                            }
                        }

                        $header = array(
                            'Reply-To'         => $from_encoded,
                            'From'             => $from_encoded,
                            'To'               => $to_out['TO'],
                            'Cc'               => $to_out['CC'],
                            'Date'             => date('r'),
                            'Subject'          => $currentSubject,
                            'X-Mailer'         => 'WebAsyst [http://www.webasyst.net] MailMaster (s)',
                            'X-Originating-IP' => '['.$_SERVER['REMOTE_ADDR'].']',
                            'X-Priority'       => $message['MMM_PRIORITY']
                        );
                        //'X-MSMail-Priority' => 'Normal'
                        // 'Importance' => 'Normal'
                        if (empty($to_out['CC']))
                            unset($header['Cc']);
                        $mime->headers($header);

                        if (!$connect)
                            $errorStr = socketSendOpen($host, $port, $user, $pass);

                        if ($connect) {
                            $res = socketSendMail($connect, $from_email, array($to_email), $mime->getMessage($crlf.$crlf));
                            $method = 'socketMail()';
                        }
                    }
                    if (!$host || !$connect) {
                        $crlf = "\n";
                        $mime = new Mail_mime($crlf);

                        $mime->setTXTBody($plainText.$crlf);
                        $mime->setHTMLBody($currentContent.$crlf);

                        if ($message['MMM_ATTACHMENT']) {
                            $xml = base64_decode($message['MMM_ATTACHMENT']);
                            try {
                                $sxml = new SimpleXMLElement($xml);
                            } catch (Exception $e) {
                            }

                            $i = 0;
                            if (count((array)$sxml->FILE))
                                while ($file = (array)$sxml->FILE[$i]) {
                                    $fileName = base64_decode($file['@attributes']['FILENAME']);
                                    $mime->addAttachment(
                                        "$wbsPath/data/$DB_KEY/attachments/mm/attachments/{$message['MMM_ID']}/$fileName",
                                        $file['@attributes']['MIME_TYPE'],
                                        EncodeHeader($fileName)
                                    );
                                    $i++;
                                }
                        }

                        $header = array(
                            'Reply-To'                  => $from_encoded,
                            'From'                      => $from_encoded,
                            'Date'                      => date('r'),
                            'X-Mailer'                  => 'WebAsyst [http://www.webasyst.net] MailMaster',
                            'X-Originating-IP'          => '['.$_SERVER['REMOTE_ADDR'].']',
                            'X-Priority'                => $message['MMM_PRIORITY'],
                            'Content-Transfer-Encoding' => '8bit'
                        );
                        $header = $mime->headers($header);

                        $body = str_replace("\r", '', $mime->get());
                        $txt_hdr = $mime->txtHeaders();
                        $params = "-f $from_email";

                        $to_once = EncodeHeader($to_name)." <$to_email>";

                        if (mail($to_once, $currentSubject, $body, $txt_hdr, $params))
                            $res = array($to_email);
                        else
                            $res = $errorStr ? $errorStr : 'PHP mail() error';

                        $method = 'PHP mail()';
                    }
                } else
                    $res = $forceError;

                if (is_array($res)) {
                    $sentCount++;
                    $MMMST_STATUS = 0;
                    if ($logFile) fwrite($logFile, '=> '.date('Y-m-d H:i:s').
                        " $method DB_KEY=$DB_KEY MMM_ID=".$message['MMM_ID']." sent to <$to_email>\n");
                } else {
                    socketSendClose();
                    $MMMST_STATUS = $errorStr = $res;
                    if ($logFile) fwrite($logFile, '-> '.date('Y-m-d H:i:s')
                        ." $method DB_KEY=$DB_KEY MMM_ID=".$message['MMM_ID']." $res\n");
                }

                if ($connect && !(($count + 1) % 10))
                    socketSendClose(); // SMTP server limit
            }

            // write message sent result to DB
            $sql = new CReplaceSqlQuery('MMMSENTTO');
            $hash = array(
                'MMM_ID'       => $message['MMM_ID'],
                'MMMST_EMAIL'  => $to_email,
                'MMMST_STATUS' => $MMMST_STATUS
            );
            $sql->addFields($hash, array_keys($hash));
            try {
                Wdb::runQuery($sql);
            } catch (Exception $e) {
            }

            if ($forceError)
                break;

            $pointer++;

            file_put_contents($pointerTmpPath, $pointer);
            copy($pointerTmpPath, $pointerPath);

            if ($message['MMM_APP_ID'] == '--') {
                $db_model = new DbModel();
                $qr = "DELETE FROM MMMESSAGE WHERE MMM_ID='".$message['MMM_ID']."' LIMIT 1";
                $db_model->query($qr);
            }

        } // one message from docList is sent **********************************

        if ($sentCount)
            $MMM_STATUS = MM_STATUS_SENT;
        else
            $MMM_STATUS = MM_STATUS_ERROR;

        if ((count($send_to) == $pointer) || !$sentCount) {
            try {
                $sql = new CUpdateSqlQuery('MMMESSAGE');
                $hash = array(
                    'MMM_STATUS' => $MMM_STATUS
                );
                $sql->addFields($hash, array_keys($hash));
                $sql->addConditions("MMM_ID='".$message['MMM_ID']."'");
                Wdb::runQuery($sql);

                // write count of sent messages to DB
                $sql = new CReplaceSqlQuery('MMSENT');
                $hash = array(
                    'MMS_DATE'  => date('Y-m-d'),
                    'MMS_COUNT' => $dailySent + count($send_to)
                );
                $sql->addFields($hash, array_keys($hash));
                Wdb::runQuery($sql);

                if (Wbs::getSystemObj()->getCommonLogBase()) {
                    $sql = new CDeleteSqlQuery('SCHEDULE_TASK');

                    $sql->addConditions("SCH_DBKEY='$DB_KEY'");
                    $sql->addConditions("SCH_DATETIME<='{$message['MMM_DATETIME']}'");

                    Wbs::getSystemObj()->CommonLogBase->runQuery($sql);
                }
                if (!$sentCount && !count($sendCurrent)) {
                    // write fatal error message to sent result DB

                    $MMM_ID = $message['MMM_ID'];

                    if ($bounced) {
                        $err = 'Incorrect email address';
                        for ($i = 0; $i < count($bounced); $i++) {
                            $bounced[$i] = mysql_real_escape_string($bounced[$i]);
                        }
                        $sql = "REPLACE INTO MMMSENTTO (MMM_ID, MMMST_EMAIL, MMMST_STATUS) VALUES "
                            ."('$MMM_ID', '".join("', '$err'), ('$MMM_ID', '", $bounced)."', '$err')";
                    } else {
                        $err = $errorStr ? $errorStr : 'Fatal error';
                        $sql = "REPLACE INTO MMMSENTTO (MMM_ID, MMMST_EMAIL, MMMST_STATUS) VALUES "
                            ."('$MMM_ID', 'error', '$err')";
                    }
                    $db_model = new DbModel();
                    $db_model->query($sql);
                    /*
                                        $sql = new CReplaceSqlQuery('MMMSENTTO');
                                        $hash = array(
                                            'MMM_ID' => $message['MMM_ID'],
                                            'MMMST_EMAIL' => $addr,
                                            'MMMST_STATUS' => $stat
                                        );
                                        $sql->addFields($hash, array_keys($hash));
                                        Wdb::runQuery($sql);
                    */
                }
            } catch (Exception $e) {
                exitOnError($e->getMessage());
            }

            unlink($pointerPath);
        }
        unlink($pointerTmpPath);
    }

    socketSendClose();

    echo 'Total messages: '.count($docList);

    // Encode a header string to B (base64) or none.
    function EncodeHeader($str)
    {
        if (preg_match_all('/[\000-\010\013\014\016-\037\177-\377]/', $str, $matches))
            return '=?utf-8?B?'.base64_encode($str).'?=';
        else
            return $str;
    }

    function exitOnError($errorStr)
    {
        global $logFile, $DB_KEY;
        if ($logFile) fwrite($logFile, date('Y-m-d H:i:s')." DB_KEY=$DB_KEY $errorStr\n");
        exit($errorStr);
    }

?>
