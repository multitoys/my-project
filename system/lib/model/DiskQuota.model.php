<?php

    /**
     * UDQ in KBytes
     *
     * @author WebAsyst Team
     *
     */
    class DiskQuotaModel extends DbModel
    {
        protected $table = 'USER_DISK_QUOTA';

        /**
         * Set disk quota to user in the application
         *
         * @param string $user_id
         * @param string $app_id
         * @param int    $size - size in KB
         *
         * @return unknown_type
         */
        public function set($user_id, $app_id, $size)
        {
            // Insert or update row
            if ($size) {
                $sql = "REPLACE INTO ".$this->table."
					SET UDQ_USER_ID = s:user_id, UDQ_APP_ID = s:app_id, UDQ_SIZE = i:size";

                return $this->prepare($sql)->exec(array('user_id' => $user_id, 'app_id' => $app_id, 'size' => $size));
            } // Delete row from the table
            else {
                $sql = "DELETE FROM ".$this->table." WHERE UDQ_USER_ID = s:user_id AND UDQ_APP_ID = s:app_id";

                return $this->prepare($sql)->exec(array('user_id' => $user_id, 'app_id' => $app_id));
            }
        }

        /**
         * Returns current disk quota to user in the application
         *
         * @param string $user_id
         * @param string $app_id
         *
         * @return int - size in KB
         */
        public function get($user_id, $app_id)
        {
            $sql = "SELECT UDQ_SIZE FROM ".$this->table."
				WHERE UDQ_USER_ID = s:user_id AND UDQ_APP_ID = s:app_id";

            return $this->prepare($sql)->query(array('user_id' => $user_id, 'app_id' => $app_id))->fetchField('UDQ_SIZE');
        }
    }

?>