<?php

    /**
     * @example
     *    $m = Mailer::composeMessage();
     *    $m->addTo('Mail To string here');
     *    $m->addSubject('Subject string here');
     *    $m->addContent('Content string here');
     *  try{ Mailer::send($m) } catch (Exception $e) {}
     */
    class Mailer
    {
        public static function composeMessage($APP_ID = 'MM')
        {
            return new MailMessage($APP_ID);
        }

        public static function composeNotification()
        {
            return new NotificationMessage();
        }

        /**
         * Creates a task for sending the message
         *
         * @param MailMessage $message
         * @param string      $doSend
         * @param bool        $update
         *
         * @return int
         */
        public static function send($message, $doSend = 'now', $update = false)
        {
            $hash = $message->getData($doSend);

            // Insert $messageDB into database and send it

            $db_model = new DbModel();
            $values = array();
            foreach ($hash as $key => $val)
                $values[] = $key." = '".$db_model->escape($val)."'";
            $sql = ($update ? 'UPDATE' : 'INSERT').' MMMESSAGE SET '
                .implode(', ', $values).
                ($update ? ' WHERE MMM_ID = '.intval($hash['MMM_ID']) : '');
            $db_model->exec($sql);

            $fileSize = 0;
            if ($message->attachments) {
                $path = Wbs::getSystemObj()->files()->getDataPath().'/'.$message->DB_KEY.'/attachments/mm';

                foreach ($message->attachments as $file) {
                    if (!$sz = self::saveAttachment($file, $path, $message->MMM_ID))
                        throw new RuntimeException("Can't save attachment(s)");
                    else
                        $fileSize += $sz;
                }
                $dqm = new DiskQuotaManager();
                $dqm->addDiskUsageRecord('$SYSTEM', 'MM', $fileSize);
            }

            if (!$doSend)
                return $hash['MMM_ID'];
            else {
                self::setSheduleTask($hash['MMM_DATETIME'], $message->DB_KEY);
                if ($doSend == 'later')
                    return $hash['MMM_ID'];
            }

            //
            //	Send Now ...
            //
            $host = $_SERVER['HTTP_HOST'];
            if (Wbs::isHosted()) {
                $host = preg_replace("/^([^\.]*)/i", "webasyst", $host);
            }

            $parsed_url = parse_url(WebQuery::getPublishedUrl('common/scripts/sendmail.php', null, true));
            $get = $parsed_url['path'].'?DB_KEY='.
                base64_encode($message->DB_KEY).'&MMM_ID='.
                base64_encode(serialize(array($message->MMM_ID)));

            $fp = fsockopen($host, 80, $errno, $error, 10);

            if (!$fp)
                throw new RuntimeException("Connect error");

            $query = "GET $get HTTP/1.1\r\nHost: $host\r\nConnection: close\r\n\r\n";
            fputs($fp, $query);
            fclose($fp);

            return $hash['MMM_ID'];
        }

        private function saveAttachment($file, $path, $MMM_ID)
        {
            $pathLevels = array($path, $file['type'], $MMM_ID);
            $path = '';
            foreach ($pathLevels as $level) {
                $path .= "$level/";
                if (!is_dir($path))
                    if (!@mkdir($path))
                        return false;
            }
            $dest = $path.$file['name'];

            if ($file['path']) {
                $source = urldecode($file['path']);
                if (!is_file($source) || (($dest != $source) && !@copy($source, $dest))) {
                    return false;
                }
            } else {
                if (!@file_put_contents($dest, getUploadedFileBody($file['name'], $file['type'])))
                    return false;
            }

            return filesize($dest);
        }

        private function setSheduleTask($sqlDateTime, $DB_KEY)
        {
            if (!Wbs::getSystemObj()->getUrl()) {
                $URL = sprintf('%s%s', Wbs::isHosted() ? end(explode('.', $_SERVER['HTTP_HOST'], 2)) : $_SERVER['HTTP_HOST'], Url::get(''));
                Wbs::getSystemObj()->setUrl($URL);
            }

            if (Wbs::getSystemObj()->getCommonLogBase()) {
                $sql = new CInsertSqlQuery('SCHEDULE_TASK');
                $hash = array(
                    'SCH_DBKEY'    => $DB_KEY,
                    'SCH_APP'      => 'MM',
                    'SCH_TASKNAME' => 'Subscribe',
                    'SCH_DATETIME' => $sqlDateTime
                );
                $sql->addFields($hash, array_keys($hash));

                Wbs::getSystemObj()->CommonLogBase->runQuery($sql);
            }
        }

    }

?>