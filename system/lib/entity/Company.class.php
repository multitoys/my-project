<?php

    /**
     * Class for the works with company variables
     *
     * @author WebAsyst Team
     *
     */
    class Company
    {
        protected static $store = array();

        protected function __construct()
        {
        }

        public static function get($name)
        {
            if (!self::$store) {
                $company_model = new DbModel();
                self::$store = $company_model->query("SELECT * FROM COMPANY")->fetchAssoc();
            }

            return isset(self::$store[$name]) ? self::$store[$name] : false;
        }

        public static function getName()
        {
            return self::get('COM_NAME');
        }

    }

?>