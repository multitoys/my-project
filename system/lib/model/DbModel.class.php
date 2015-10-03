<?php

    /**
     * WebAsyst.net
     *
     * @copyright  Copyright (c) 2008, WebAsyst.net
     * @link       http://webasyst.net
     * @package    Lib
     * @subpackage Model
     * @since      12.12.2008
     */
    class DbModel
    {
        protected $handler;

        /**
         * Table name
         *
         * @var string
         */
        protected $table = false;

        public static $queries = array();

        public function __construct($code = 0)
        {
            $this->handler = DbConnector::getConnection($code);
        }

        /**
         * Run query
         *
         * @param string $sql - SQL query
         *
         * @return mysql_result/boolean - result
         */
        private function run($sql)
        {
            $sql = trim($sql);

            $t = microtime(true);
            $res = mysql_query($sql, $this->handler);
            if (mysql_errno($this->handler)) {
                throw new MySQLException("Query Error\nQuery: ".$sql."\nError: ".mysql_errno($this->handler)."\nMessage: ".mysql_error($this->handler), mysql_errno($this->handler));
            }
            self::$queries[] = array(
                (microtime(true) - $t),
                $sql
            );

            return $res;
        }

        /**
         * Execute query without creating object of result
         *
         * @param string $sql - SQL query
         *
         * @return mysql_result/boolean - result of query
         */
        public function exec($sql)
        {
            return $this->run($sql);
        }

        /**
         * Execute the query and returns an object of result (itterator), depending on the type of query/
         *
         * @param string $sql - SQL query
         *
         * @return DbResultSelect|DbResultInsert - result
         */
        public function query($sql)
        {
            // Get type of query
            $DbQueryAnalyzer = new DbQueryAnalyzer($sql);

            return $DbQueryAnalyzer->invokeResult($this->run($sql), $this->handler);
        }

        /**
         * Escapes data
         *
         * @param mixed $data
         *
         * @return string
         */
        public function escape($data)
        {
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    $data[$key] = mysql_real_escape_string($value, $this->handler);
                }

                return $data;
            }

            return mysql_real_escape_string($data, $this->handler);
        }

        /**
         * Returns constructor of sql-queries
         *
         * @return DbQueryConstructor
         */
        public function getQueryConstructor()
        {
            return new DbQueryConstructor($this);
        }

        /**
         * Get name of the current table
         *
         * @return string
         */
        public function getTableName()
        {
            return $this->table;
        }

        /**
         * Returns prepare for the query
         *
         * @return DbStatement
         */
        public function prepare($sql)
        {
            return new DbStatement($this, $sql);
        }

    }

?>