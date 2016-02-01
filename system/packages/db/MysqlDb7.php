<?php
	class MysqlDb {
		var $connection = 0;
		var $db = 0;
		var $charset;

		function connect ($host, $port, $user, $password, $dbName) {
			$hostStr = $host;
			if ($port)
				$hostStr .= ":" . $port;
			
//			if (!($this->connection = mysql_pconnect ($hostStr, $user, $password)))
//				throw new MysqlException (mysql_error(), mysql_errno());
//			
//			if ($dbName) {
//				$this->db = mysql_select_db ($dbName);
//				if (!$this->db)
//					throw new MysqlException (mysql_error($this->connection));
//			}
			return mysqli_connect($hostStr, $user, $password, $dbName);
		}
		
		public function runQuery ($query) {
			$queryStr = $this->getQueryStr ($query);
			$res = mysqli_query ($queryStr, $this->connection);
			if (!$res)
				throw new MysqlException (mysql_error($this->connection), mysqli_errno($this->connection), $queryStr);
			return $res;
		}
		
		public function getData ($query, $keyField = null) {
			$data = array ();
			$res = $this->runQuery($query);
			while ($row = mysqli_fetch_array ($res, MYSQL_ASSOC)) {
				if ($keyField)
					$data[$row[$keyField]] = $row;
				else
					$data[] = $row;
			}
			return $data;
		}
		
		public function getRow ($query) {
			$row = mysqli_fetch_array ($this->runQuery ($query), MYSQL_ASSOC);
			return $row;
		}
		
		public function getFirstField ($query) {
			$row = mysqli_fetch_array ($this->runQuery ($query), MYSQL_NUM);
			return $row[0];
		}
		
		private function getQueryStr ($query) {
			return (is_object($query)) ? $query->getQuery() : $query;
		}		

		public function insertId () {
			return mysql_insert_id ($this->connection);
		}
		
		public function setCharset($charset) {
			$this->charset = $charset;
			mysqli_query( $this->connection, 'set names '.$charset);
			mysqli_query ($this->connection, "set character_set_client='$charset'");
			mysqli_query ($this->connection, "set character_set_results='$charset'");
			mysqli_query ($this->connection, "set collation_connection='${charset}_bin'");
		}		
	}