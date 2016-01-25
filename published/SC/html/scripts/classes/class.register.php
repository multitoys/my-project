<?php

    class Register extends Singleton
    {

        public $Variables;

        protected function __construct()
        {

            $this->Variables = array();
        }

        public function set($Variable, &$Value)
        {

            $this->Variables[$Variable] = &$Value;
        }

        public function assign($Variable, $Value)
        {

            $this->Variables[$Variable] = $Value;
        }

        public function &get($Variable)
        {

            if (!isset($this->Variables[$Variable])) {

                $this->Variables[$Variable] = null;

                return $this->Variables[$Variable];
//                print_r(debug_backtrace());
//                die('Variable (' . $Variable . ') doesnt set!');
            }

            return $this->Variables[$Variable];
        }

        public function is_set($Variable)
        {

            return isset($this->Variables[$Variable]);
        }
    }