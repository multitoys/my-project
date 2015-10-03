<?php

    class Url
    {
        /**
         * Returns link
         *
         * @example Url::get('/common/', false) == '/wbs/published/common/';
         *
         * @param string $url  - relative link
         * @param bool   $full - http://domain/... or /...
         *
         * @return string
         */
        public static function get($url, $full = false)
        {
            if (!Wbs::isHosted()) {

                $request_url = Env::Server('REQUEST_URI');
                if (preg_match("/(.*)published(.*)/i", $request_url)) {
                    $url = preg_replace("/(.*)published(.*)/i", "$1published".$url, $request_url);
                } else {
                    $url = Wbs::getSystemObj()->getWebUrl()."published".$url;
                }
            }

            if ($full) {
                $host = Env::Server('HTTP_HOST');
                $port = Env::Server('SERVER_PORT');

                return 'http://'.$host.($port && $port != '80' ? ":".$port : "").$url;
            } else {
                return $url;
            }
        }

        public static function getServerUrl()
        {
            $host = Env::Server('HTTP_HOST');
            $port = Env::Server('SERVER_PORT');

            return 'http://'.$host.($port && $port != '80' ? ":".$port : "");
        }

        public static function go($url)
        {
            header("Location: ".self::get($url));
            exit();
        }
    }

?>