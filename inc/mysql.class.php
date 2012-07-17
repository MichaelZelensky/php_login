<?php
/***************************************************************************

  # Author and copyright: Michael Zelensky
  # www.miha.in
  # (c)2011-2012


  Based on copyright            : (C) 2001 PHPtools4U.com - Mathieu LESNIAK

***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
 
DEFINE ("SQL_HOST","localhost");
DEFINE ("SQL_BDD","your_database");
DEFINE ("SQL_USER","your_user");
DEFINE ("SQL_PASSWORD","your_user_password");
DEFINE ("MYSQL_LOG", TRUE); // set this line to FALSE if you don't want to log
DEFINE ("MYSQL_LOG_PATH", "/your_dir/mysql.log");

class DB {


        var $Host 		= SQL_HOST;    # Hostname of our MySQL server
        var $Database 	= SQL_BDD;     # Logical database name on that server
        var $User 		= SQL_USER;    # Database user
        var $Password 	= SQL_PASSWORD;# Database user's password

        var $Link_ID    = 0;           # Result of mysql_connect()
        var $Query_ID	= 0;           # Result of most recent mysql_query()
        var $Record		= array();     # Current mysql_fetch_array()-result
        var $Row;                      # Current row number
        var $Errno 		= 0;           # Error state of query
        var $Error 		= "";

	#
	# Create a link id to the MySQL database
	# Allow to call $var = new DB($otherhost,$otherDB,$otheruser,$otherpass);
	# where $other* are connections vars different from 
	# $this->Host, etc
	#
        
	function DB($altHost = "",$altDB = "",$altUser = "",$altPassword = "") {
		if ($altHost == "")
			$altHost = $this->Host;
		if ($altDB == "")
			$altDB = $this->Database;
		if ($altUser == "")
			$altUser = $this->User;
		if ($altPassword == "")
			$altPassword = $this->Password;

		$this->Host = $altHost;
		$this->Database = $altDB;
		$this->User = $altUser;
		$this->Password = $altPassword;
	}

    #
    # Stop the execution of the script
    # in case of error
    # $msg : the message that'll be printed
    #
    
    function halt($msg) {
      $this->log("Database error: $msg\nMysql error: $this->Errno ($this->Error)\n");
      echo("<B>Database error:</B> $msg<BR/>\n");
      echo("<B>MySQL error</B>: 
        $this->Errno ($this->Error)<BR>\n");
      $this->write_log();
      die("Session halted.");
    }


	#
	# Connect to the MySQL server
	#

	function connect() {
		global $DBType;

		if($this->Link_ID == 0) {
			$this->Link_ID = mysql_connect($this->Host, 
											$this->User, 
											$this->Password);
			if (!$this->Link_ID) {
				$this->halt("Link_ID == false, connect failed");
            }
            $SelectResult = mysql_select_db($this->Database, $this->Link_ID);
			if(!$SelectResult) {
				$this->Errno = mysql_errno($this->Link_ID);
				$this->Error = mysql_error($this->Link_ID);
				$this->halt("cannot select database <I>".$this->Database."</I>");
			}
		}
	}

	#
    # Send a query to the MySQL server
    # $Query_String = the query
    #
    
  function query($Query_String) {

		$this->connect();
    $this->log($Query_String);
		$this->Query_ID = mysql_query($Query_String,$this->Link_ID);
    $this->Row = 0;
    $this->Errno = mysql_errno();
    $this->Error = mysql_error();
    if (!$this->Query_ID) {
      $this->halt("Invalid SQL: ".$Query_String);
    }
		return $this->Query_ID;
	}

	#
	# return the next record of a MySQL query
	# in an array
	#

  function next_record() {
		$this->Record = mysql_fetch_array($this->Query_ID);
		$this->Row += 1;
		$this->Errno = mysql_errno();
		$this->Error = mysql_error();
		$stat = is_array($this->Record);
		if (!$stat) {
			mysql_free_result($this->Query_ID);
			$this->Query_ID = 0;
		}
		return $this->Record;
    }

	#
	# Return the number of rows affected by a query
	# (except insert and delete query)
	#

	function num_rows() {
		return mysql_num_rows($this->Query_ID);
	}

	#
	# Return the number of affected rows
	# by a UPDATE, INSERT or DELETE query
	#

  function affected_rows() {
		return mysql_affected_rows($this->Link_ID);
	}
    
    #
    # Return the id of the last inserted element
    #
    
	function insert_id() {
		return mysql_insert_id($this->Link_ID);
	}
  
  #
  # Delete a raw from given table by id
  #
  
  function delete($table, $id) {
    $q = "DELETE FROM `$table` WHERE id='$id'";
    $this->query($q);
    return $this->affected_rows();
  }

	#
	# Optimize a table
	# $tbl_name : the name of the table
	#

	function optimize($tbl_name) {
		$this->connect();
		$this->Query_ID = @mysql_query("OPTIMIZE TABLE $tbl_name",$this->Link_ID);
	}

	#
	# Free the memory used by a result
	#

	function clean_results() {
		if($this->Query_ID != 0) mysql_freeresult($this->Query_ID);
	}

	#
	# Close the link to the MySQL database
	#

	function close() {
		if($this->Link_ID != 0) mysql_close($this->Link_ID);
	}
  
  #
  # return array of arays (table rows)
  # @param table string
  # @param order string, e.g. "id DESC"
  # @param limit string, e.g. "0,1"
  # @param where string, e.g. "id>10 AND id<20"
  # if no parameters given, returns the whole table, be very careful with large data amounts
  #
  
  function all($table, $order = '', $limit = '', $where = '') {
    $sql = "SELECT * FROM $table";
    (int) $limit;
    if ($where) {
      $sql .= " WHERE $where";
    }
    if ($order) {
      $sql .= " ORDER BY $order";
    }
    if ($limit) {
      $sql .= " LIMIT 0,$limit";
    }
    $this->query($sql);
    return $this->to_arr();
  }
  
  #
  # returns one row by id from table
  # @param table string
  # @param id int
  # @return array (full row from table)
  #
  
  function get_by_id($table, $id) {
    $this->query("SELECT * FROM $table WHERE id='$id' LIMIT 0,1");
    return $this->next_record();
  }

  #sinonym of get_by_id
  
  function get($table, $id) {
    return $this->get_by_id($table,$id);
  }
  
  #
  # returns the set of rows where field = value
  # @res - query_id ($this->query result)
  #
  # @par table - required, string, table name
  # @par field - required, string, field name
  # @par id - required, string or int
  # @par order, string
  # @par limit, int
  # 
  function get_by_field($table, $field, $value, $order='', $limit = 0) {
    $sql = "SELECT * FROM $table WHERE `$field`='$value'";
    if ($order) {
      $sql .= " ORDER BY $order";
    }
    if ($limit) {
      $sql .= " LIMIT 0,$limit";
    }
    $this->query($sql);
    return $this->next_record();
  }
  
  #insert row
  #@param table
  #@param data array { column => value}
  function insert($table, $data) {
    $sql = "INSERT INTO $table ";
    $cols = "";
    $vals = "";
    foreach ($data as $key=>$val) {
      $cols .= "$key,";
      $vals .= "'$val',";
    }
    $cols = substr($cols, 0, -1);
    $vals = substr($vals, 0, -1);
    $cols = "($cols)";
    $vals = " VALUES ($vals)";
    $sql .= $cols . $vals;
    $this->query($sql);
    return $this->insert_id();
  }
  
  #updates row
  # @param table required - table name
  # @param id required - row id
  # @param data required - array {column => value}
  function update($table, $id, $data) {
    $str = '';
    foreach($data as $key=>$value) {
      $str .= "`$key` = '$value',";
    }
    $str = substr($str, 0, -1);
    $sql = "UPDATE $table SET $str WHERE `id`='$id'";
    $this->query($sql);
    return $this->affected_rows();
  }
  
  #@return array consisting of last query SELECT data
  function to_arr() {
    $res = array();
    if ($this->num_rows()>0) {
      while ($row = $this->next_record()) {
        $res[] = $row;
      }
    } 
    return $res;
  }
  
  # combination of query() and to_arr()
  function query_to_arr($q) {
    $this->query($q);
    return $this->to_arr();
  }
  
  # BOOLEAN
  # table exists?
  function table_exists($table = '') {
    $this->connect();
    if (mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$table."'"))) {
      return TRUE;
    }
    return FALSE;
  } 
  
  # BOOLEAN
  # table exists in database?
  # this is slower than table_exists method, but you can check table existance in any other database
  function table_exists_in_db($table = '', $db = '') {
    if ($db == '') {
      $db = $this->Database;
    }
    $this->connect();
    $tables = mysql_list_tables ($db);
    //select our database back, because the checked db was selected in the above
    mysql_select_db($this->Database, $this->Link_ID);
    while (list ($temp) = mysql_fetch_array ($tables)) {
      if ($temp == $table) {
        return TRUE;
      }
    }
    return FALSE;
  }
  
  # BOOLEAN 
  # database exists?
  # make sure database user has enough rights to connect to the database that you are checking!
  # Returns FALSE if COULD NOT CONNECT. This is possible NOT only because DB is inexistant, but also because the db user has not enough rights to connect.
  # check $this->Errno for details
  function db_exists($db = '') {
    if ($db == '') {
      $db = $this->Database;
    }
    //trying to select db, which is being checked
    $this->connect();
    $db_selected = mysql_select_db($db, $this->Link_ID);
    if (!$db_selected) {
      return FALSE;
    }
    return TRUE;
  }
  
  # BOOLEAN
  # drops database 
  # BE CAREFUL
  function drop($db='') {
    if ($db) return FALSE;
    $this->query("DROP DATABASE `$DB`");
  }
  
  ####
  #### Collect log messages
  ####  
  function log($msg) {
    if (!MYSQL_LOG) return;
    $this->log[] = date('Y.m.d H:i:s.u') .' '. $msg;
  }

  
  
  ####
  #### Save log
  ####  
  function write_log() {
    if (!MYSQL_LOG) return;
    //write to file
    $log_path = MYSQL_LOG_PATH;
    $content = '';
    foreach($this->log as $msg) {
      $content .= "$msg\n";
    }
    file_put_contents($log_path, $content, FILE_APPEND);
  }

/* 

  END OF CLASS 
  
*/  
} ?>