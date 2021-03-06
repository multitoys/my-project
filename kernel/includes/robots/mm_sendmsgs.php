<?php

    $logPath = '/var/www/wbs_dev_test/wbs.dev/webasyst/wbs/temp/log/my_test.log';
    $log = fopen($logPath, 'a');
    fwrite($log, "start\n");

    require_once("robotinit.php");
    ini_set("memory_limit", "256M");

    loadDatabaseLanguageList($DB_KEY);

    $language = LANG_ENG;

    $kernelStrings = $loc_str[$language];
    $mmStrings = $mm_loc_str[$language];

    require_once($WBSPath.'/published/CM/cm.php');
    require_once($WBSPath.'/published/MM/mm.php');

    $qr = db_query($qr_mm_pendingNotesToSend, array());
    if (PEAR::isError($qr))
        die("Error executing query");

    $toSend = array();

    $ids = '';
    while ($row = db_fetch_array($qr)) {
        $toSend[$row['MMM_ID']] = $row;
        $ids .= $row['MMM_ID'].' - ';
    }

    db_free_result($qr);

    fwrite($log, "toSend -> $ids\n");

    $maxRec = getApplicationVariable($DB_KEY, $MM_APP_ID, MM_OPT_RECIPIENTS_LIMIT, $kernelStrings);
    $dailyMaxRec = getApplicationVariable($DB_KEY, $MM_APP_ID, MM_OPT_DAILY_RECIPIENTS_LIMIT, $kernelStrings);
    $disableUnsibscribeFooter = getApplicationVariable($DB_KEY, $MM_APP_ID, MM_OPT_DISABLE_UNSUBSCRIBE_FOOTER, $kernelStrings);

    foreach ($toSend as $messageData) {
        $currentUser = $messageData['MMM_USERID'];

//fwrite($log, "currentUser = $currentUser\n");

        $sendTo = unserialize(base64_decode($messageData['MMM_SENDTO']));

        $contactsList = $sendTo['CGLF'];
        $TOMORE = $sendTo['TOMORE'];
        $URI = $sendTo['URI'];

        /*
        $cont = '';
        foreach($TOMORE as $c)
            foreach($c as $i)
                $cont .= $i;
        */

        $senderArray = $sendTo['SENDER'];

        // Prepare recipients

        $typeDescription = $fieldsPlainDesc = null;
        $ContactCollection = new contactCollection($typeDescription, $fieldsPlainDesc);

        $ContactCollection->loadAsArrays = true;

        $list = $ContactCollection->loadMixedEntityContactWithEmails($contactsList[CM_OT_FOLDERS], $contactsList[CM_OT_USERGROUPS], $contactsList[CM_OT_LISTS], $contactsList['CONTACTS'], 'C_ID', $kernelStrings, true);
        if (PEAR::isError($list)) {
            $errorStr = $list->getMessage();
            break;
        }

        $numEmails = count($ContactCollection->items) + count(mm_mailSender::_parseRecipientList($TOMORE));

        $sentNum = mm_getSentCount($kernelStrings);
        if (PEAR::isError($sentNum)) {
            $errorStr = $sentNum->getMessage();
            break;
        }

//fwrite($log, "MMM_SENDTO = ".$messageData['MMM_SENDTO']."\n");
//fwrite($log, "TOMORE = $cont\n");
//fwrite($log, "numEmails = $numEmails\n");
//fwrite($log, "sentNum = $sentNum\n");

        $message = new mm_message($mm_message_data_schema);

        if (PEAR::isError($ret = $message->loadFromArray($messageData, $kernelStrings, $mmStrings, array(s_datasource => s_database)))) {
            $errorStr = $res->getMessage();
            break;
        }

//fwrite($log, "mm_message init\n");

        if ($numEmails == 0 || (!is_null($maxRec) && $numEmails > $maxRec) || (!is_null($dailyMaxRec) && $numEmails + $sentNum > $dailyMaxRec)) {
            $sendTo['RECMAX'] = $maxRec;
            $sendTo['RECDAILYMAX'] = $dailyMaxRec;
            $sendTo['RECSENT'] = $sentNum;
            $sendTo['RECTOTAL'] = $numEmails;

            $message->MMM_SENDTO = base64_encode(serialize($sendTo));
            $message->MMM_STATUS = MM_STATUS_ERROR;
            $message->MMM_DATETIME = convertToSqlDateTime(time());

            if (PEAR::isError($res = $message->saveEntry($currentUser, ACTION_EDIT, $kernelStrings, $mmStrings))) {
                $errorStr = $res->getMessage();
                //break;
            }

            continue;
        }

        // Change message status from DRAFT to SENDING

        $message->MMM_STATUS = MM_STATUS_SENDING;

        fwrite($log, "MMM_STATUS = ".MM_STATUS_SENDING."\n");

        if (PEAR::isError($res = $message->saveEntry($currentUser, ACTION_EDIT, $kernelStrings, $mmStrings))) {
            $errorStr = $res->getMessage();
            break;
        }

        // Prepare Sender Object

        $sender = new mm_mailSender();

        $sender->maxRecipientNum = $maxRec;

        $textService = new ContactsTextService($kernelStrings, $language);
        $textService->ListAvailableVariables($kernelStrings, array(VS_CONTACT, VS_CURRENT_USER, VS_COMPANY));
        $sender->textService = $textService;

        $sender->recipients = $ContactCollection->items;

        if (is_array($senderArray))
            $sender->from = $senderArray;
        else {
            $recipientName = "";
            $recipientAddress = extractEmailAddress($message->MMM_FROM, $recipientName);
            $sender->from = array('MMS_FROM' => (strlen($recipientName) ? $recipientName : ""), 'MMS_EMAIL' => $recipientAddress, 'MMS_REPLYTO' => $message->MMM_REPLYTO, 'MMS_RETURNPATH' => $message->MMM_RETURNPATH, 'MMS_LANGUAGE' => LANG_ENG, 'MMS_ENCODING' => $message->MMM_ENCODING);
        }

        $sender->PRIORITY = $messageData['MMM_PRIORITY'];

        $sender->filesDir = mm_getNoteAttachmentsDir($message->MMM_ID, MM_ATTACHMENTS);
        $sender->imagesDir = mm_getNoteAttachmentsDir($message->MMM_ID, MM_IMAGES);
        $sender->imageUri = $URI."/".prepareURLStr(PAGE_MM_GETSENTIMG, array('DB_KEY' => base64_encode($DB_KEY), 'messageId' => $message->MMM_ID));

        $sender->URI = $URI;

        $sender->includeImages = false;

        $sender->TOMORE = $TOMORE;

        $sender->fillRecipients();

        // Prepare message object
        $MMM_CONTENT = $message->MMM_CONTENT;
        $MMM_CONTENT_TEXT = $message->MMM_CONTENT_TEXT;

        $footerArray = sliceLocalizaionArray($mm_loc_str, "app_mail_footer");
        $footerString = nl2br((isset($footerArray[$sender->from['MMS_LANGUAGE']])) ? $footerArray[$sender->from['MMS_LANGUAGE']] : $footerArray[$sender->from[LANG_ENG]]);

        if ( /*!is_null($disableUnsibscribeFooter) && */
            $disableUnsibscribeFooter != 1
        ) {
            $message->MMM_CONTENT .= '<small>';
            $message->MMM_CONTENT .= $footerString;
            $message->MMM_CONTENT .= '<a href="'.MM_VAR_UNSIBSCRIBE_URL.'">'.$mmStrings['app_mail_footer_link'].'</a>';
            $message->MMM_CONTENT .= '</small>';

            if (trim($message->MMM_CONTENT_TEXT) != "") {
                $message->MMM_CONTENT_TEXT .= $mmStrings['app_mail_footer'];
                $message->MMM_CONTENT_TEXT .= $mmStrings['app_mail_footer_link'].' [{UNSUBSCRIBE_URL}]';
            }
        }

        // Send a message
        $sendRes = $sender->send($currentUser, $message, $includeImages, $kernelStrings, $mmStrings);

        // Save message and send statistics
        $message->MMM_STATUS = MM_STATUS_SENT;
        $message->MMM_DATETIME = convertToSqlDateTime(time());

        $message->MMM_CONTENT = $MMM_CONTENT;
        $message->MMM_CONTENT_TEXT = $MMM_CONTENT_TEXT;

        fwrite($log, "MMM_STATUS = ".MM_STATUS_SENT." (sent)\n");

        if (PEAR::isError($MMM_ID = $message->saveEntry($currentUser, ACTION_EDIT, $kernelStrings, $mmStrings))) {
            $errorStr = $MMM_ID->getMessage();
            break;
        }

        if ($message->MMF_ID == 'Outbox') {
            $res = mm_deleteMessage($currentUser, $MMM_ID, 'Outbox', $kernelStrings, $mmStrings);
            if (PEAR::isError($res)) {
                $errorStr = $res->getMessage();
                break;
            }
        }

    }

    fwrite($log, "errorStr = $errorStr\n");

    if (isset($errorStr))
        die($errorStr);

?>