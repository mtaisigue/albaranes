<?php
/**
 * FreeKore Php Framework
 * Version: 0.1 Beta
 *
 * @Author     M. Angel Mendoza  email:mmendoza@freekore.com
 * @copyright  Copyright (c) 2010 Freekore PHP Team
 * @license    New BSD License
 */
/*************************************************************
 **  Programa:  oracle.php
 **  Descripcion: Funciones de base de datos adaptador Oracle
 **  proyecto    fecha      por         descripcion
 **  ----------  ---------  ----------- ----------------
 **  00000001    19/06/10   mmendoza    Creado.
 **************************************************************/

/*
 Oracle adapter
 */

/**
 *@package db_oracle
 *@desc  Oracle Object to ejecutes Sql Operations
 *@since v0.1 beta
 * */
class db_oracle implements db_interface{
	// define db_interface...
	public $resource;
	public static $conn;
	public static $host = HOST;
	public static $user = USER;
	public static $pass = PASSWORD;
	public static $database = SYSTEM_DB;
	public  $sql_query = "";
	public  $primary_key_id = array();
	private $query_is_assoc = false;
	public static $is_connected = false;
	/**
	 *@package db_oracle
	 *@method connect()
	 *@desc Open a connection to a Oracle Server
	 *@since v0.1 beta
	 * */
	public static function connect($p_host = NULL,$p_user = NULL,$p_pass = NULL,$p_db = NULL) {

		$H = isset($p_host)? $p_host : self::$host;
		$U = isset($p_user)? $p_user : self::$user;
		$P = isset($p_pass)? $p_pass : self::$pass;
		$D = isset($p_db)  ? $p_db   : self::$database;

		self::$conn = oci_connect($U, $P, $H);
		if (!self::$conn) {
			$e = oci_error();
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}else{
			self::$is_connected = true;
		}
			
	}
	/**
	 *@package db_oracle
	 *@method close()
	 *@desc Close Oracle connection
	 *@since v0.1 beta
	 * */
	public function close() {
			
		if(isset($this->resource)){
			oci_free_statement($this->resource);
		}
		if(self::$is_connected){
			oci_close(self::$conn);
			self::$is_connected = false;
		}

			
	}

	/**
	 *@package db_oracle
	 *@method query()
	 *@desc Send a Oracle query
	 *@since v0.1 beta
	 *@return bool & Populates $this->resource
	 * */
	public function query($query){

		$this->sql_query = $query;

		// Prepare the statement
		$this->resource = oci_parse(self::$conn, $query);
		if (!$this->resource) {
			$e = oci_error($this->conn);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		// Perform the logic of the query
		$ok = oci_execute($this->resource);
		if (!$ok) {
			$e = oci_error($stid);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}
			
		return $this->resource;
	}
	/**
	 *@package db_oracle
	 *@method query_assoc()
	 *@desc Send a Oracle query in assoc mode
	 *@since v0.1 beta
	 **/
	public function query_assoc($query){
		$this->query_is_assoc = true;
		$this->query($query);

	}

	/**
	 *@package db_oracle
	 *@method num_rows()
	 *@desc Get number of rows in result
	 *@since v0.1 beta
	 * */
	public function num_rows($rs = null){
		// Definir

	}
	/**
	 *@package db_oracle
	 *@method next()
	 *@desc Fetch a result row as an associative array, a numeric array, or both depending on query() or query_assoc() method
	 *@since v0.1 beta
	 * */
	public function next($rs = ''){

	 $Resource = ( $rs!='' ? $rs : $this->resource );
	 // Falta Validar si es assoc o no
	 return oci_fetch_array($Resource,OCI_ASSOC+OCI_RETURN_NULLS);
	}
	/**
	 *@package db_oracle
	 *@method find_last()
	 *@desc Fetch a result of last record as an associative array & numeric array
	 *@since v0.1 beta
	 * */
	public function find_last($TABLE,$ID,$WHERE = NULL){
	 $VAL = array();
	 if($WHERE!=NULL){$WHERE=$WHERE;}else{$WHERE='';}
	 $RS=$this->query("SELECT * FROM `".$TABLE."` ".$WHERE." ORDER BY ".$ID." DESC LIMIT 1 ;");
	 if($REC=mysql_fetch_array($RS)){$VAL=$REC;}
	 return $VAL;
	}
	/**
	 *@package db_oracle
	 *@method inserted_id()
	 *@desc Get the ID generated in the last query
	 *@since v0.1 beta
	 * */
	public function inserted_id(){
		return mysql_insert_id();
	}
	/**
	 *@package db_oracle
	 *@method describe_table()
	 *@desc describes a table
	 *@since v0.1 beta
	 * */
	public function describe_table($table){

		$t_fields = array();
		$sql = ' DESC '.$table.';';
		$this->query($sql);

		while($rec = $this->next()){
			$fld=$rec['Field'];
			$t_fields[$fld] = $rec;
			//------------------
			//primary key id
			//------------------
			if($rec['Key']=='PRI'){
				$this->primary_key_id = $rec;
			}
		}
		return $t_fields;
	} // describe_table
	/**
	*@package db_oracle
	*@method fetch_array()
	*@desc Fetch a result row as an associative array, a numeric array, or both depending on query() or query_assoc() method
	*@since v0.1 beta
	* */
	public function fetch_array(){
			
	} // fetch_array(){


}
