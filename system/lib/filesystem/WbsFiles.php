<?php

    class WbsFiles
    {
        private $appName = null;

        /**
         * @param string $App
         */
        public function __construct($App)
        {
            $this->appName = $App;
        }

        /**
         * @param string $srcFile
         * @param string $destFile
         */
        public function copy($srcFile, $destFile)
        {
            $size = filesize($srcFile);
            $quota_manager = new DiskQuotaManager();
            $quota = $quota_manager->getUserApplicationFreeSpace(CurrentUser::getId(), $this->appName);
            if ($quota > 0 && $size > $quota) {
                throw new RuntimeException('Error Quota Limit'.__LINE__);
            }
            $info = pathinfo($destFile);
            $this->_checkDir($info['dirname']);

            if (!copy($srcFile, $destFile)) {
                throw new RuntimeException('Error copy file: '.$srcFile);
            }
            $quota_manager->addDiskUsageRecord(CurrentUser::getId(), $this->appName, $size);
        }

        /**
         * @param string $srcPath
         * @param array  $files
         * @param string $destPath
         */
        public function copyList($srcPath, $files, $destPath)
        {
            $this->_checkDir($destPath);
            $quota = new DiskQuotaManager();
            $freeSpace = $quota->getUserApplicationFreeSpace(CurrentUser::getId(), $this->appName);

            foreach ($files as $file) {
                $size = filesize($srcPath.'/'.$file);
                if ($freeSpace > $size) {
                    $freeSpace -= $size;

                    if (!copy($srcPath.'/'.$file, $destPath.'/'.$file)) {
                        throw new RuntimeException('Error copy file: '.$srcPath.'/'.$file);
                    }

                    $quota->addDiskUsageRecord(CurrentUser::getId(), $this->appName, $size);
                } else
                    throw new RuntimeException('Error Quota Limit. No copy files.');
            }
        }

        /**
         * @param string $srcFile
         */
        public function remove($srcFile)
        {
            $size = filesize($srcFile);

            if (!unlink($srcFile)) {
                throw new RuntimeException('Error remove file: '.$srcFile);
            }
            $quota_manager = new DiskQuotaManager();
            $quota_manager->deleteDiskUsageRecord(CurrentUser::getId(), $this->appName, $size);

        }

        /**
         * @param string $srcFile
         * @param string $destFile
         */
        public function move_upload($srcFile, $destFile)
        {
            $size = filesize($srcFile);
            $quota_manager = new DiskQuotaManager();
            $quota = $quota_manager->getUserApplicationFreeSpace(CurrentUser::getId(), $this->appName);
            if ($quota > 0 && $size > $quota) {
                throw new RuntimeException('Error Quota Limit'.__LINE__." quota = {$quota}");
            }
            $info = pathinfo($destFile);
            $this->_checkDir($info['dirname']);

            if (!move_uploaded_file($srcFile, $destFile)) {
                throw new RuntimeException('Error move upload file: '.$srcFile);
            }
            $quota_manager->addDiskUsageRecord(CurrentUser::getId(), $this->appName, $size);
        }

        /**
         * Move file
         *
         * @param string $src_file
         * @param string $dest_file
         */
        function move($srcFile, $destFile)
        {
            $info = pathinfo($destFile);
            $this->_checkDir($info['dirname']);

            copy($srcFile, $destFile);
            unlink($srcFile);
        }

        static function chmod($file, $permission = 0666)
        {
            if (!file_exists($file)) {
                return false;
            }

            return @chmod($file, $permission & 0666);
        }

        private function _checkDir($dirPath)
        {
            if (!file_exists($dirPath)) {
                $arrayPaths = explode("/", $dirPath);
                if (is_null($arrayPaths[0]))
                    array_shift($arrayPaths);
                $tempPath = '';
                foreach ($arrayPaths as $path) {
                    $tempPath .= "/".$path;
                    if (!file_exists($tempPath)) {
                        $complit = mkdir($tempPath, 0777);
                        if (!$complit)
                            throw new RuntimeException('Error create dir: '.$tempPath);
                    }
                }
            }

            return true;
        }
    }

?>