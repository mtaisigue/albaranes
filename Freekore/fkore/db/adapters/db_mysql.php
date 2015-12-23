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
 **  Programa:  mysql.php
 **  Descripcion: Funciones de base de datos adaptador MySql
 **  proyecto    fecha      por         descripcion
 **  ----------  ---------  ----------- ----------------
 **  00000001    30/04/10   mmendoza    Creado.
 **************************************************************/

/**
 *@package db_mysql
 *@desc  MySql Object to ejecutes MySql Operations
 *@since v0.1 beta
 * */

class db_mysql implements db_interface{

	public $resource;
	public static $host = HOST;
	public static $user = USER;
	public static $pass = PASSWORD;
	public static $database = SYSTEM_DB;
	public  $sql_query='';
	public  $primary_key_id = array();
	private $query_is_assoc = false;
	public static $is_connected = false;

	private $arr_handled_errors = array(
			'1062'=>'ER_DUP_ENTRY',
			'1451'=>'ER_ROW_IS_REFERENCED_2',
			'1452'=>'ER_NO_REFERENCED_ROW_2');

	public $error_code = '';


	/**
	 *@package db_mysql
	 *@method connect()
	 *@desc Open a connection to a MySQL Server
	 *@since v0.1 beta
	 * */
	public static function connect($p_host = NULL,$p_user = NULL,$p_pass = NULL,$p_db = NULL) {

		$H = isset($p_host)? $p_host : self::$host;
		$U = isset($p_user)? $p_user : self::$user;
		$P = isset($p_pass)? $p_pass : self::$pass;
		$D = isset($p_db)  ? $p_db   : self::$database;


		if(@mysql_connect($H,$U,$P)==false){

			try{
				throw new FkException("Error al conectar a la db ");
			}catch(FkException $e){
				$e->description='Mysql Respondi&oacute;:'. mysql_error().'</b>';
				$e->solution='Verifique la conexion, posiblemente el archivo /app/config/environment.ini no contiene los datos de conexion correctos. Vea ejemplo:';
				$e->solution_code= fk_str_format('[development]
db_host = localhost
db_username = tester
db_password = test
db_name = freekore_dev
db_type = mysql','html');
				$e-> error_code = 'DB000002';
				$e->show('code_help');

			}
		}

		if(mysql_select_db($D)!=false){
			self::$is_connected = true;

		}else{

			try{
				throw new FkException("Error al Seleccionar la db: ");
			}catch(FkException $e){
				$e->description='Mysql Respondi&oacute;:'. mysql_error().'</b>';
				$e->solution='Verifique el nombre de la base de datos, posiblemente el archivo /app/config/environment.ini no contiene el nombre de la db correcto. Vea ejemplo:';
				$e->solution_code= fk_str_format('[development]
db_host = localhost
db_username = tester
db_password = test
db_name = freekore_dev
db_type = mysql','html');
				$e-> error_code = 'DB000003';
				$e->show('code_help');

			}
		} // End else


			
	}
	public static function verfy_connection($p_host = NULL,$p_user = NULL,$p_pass = NULL,$p_db = NULL) {

		$error = false;
		$error_code = '';
		$H = isset($p_host)? $p_host : self::$host;
		$U = isset($p_user)? $p_user : self::$user;
		$P = isset($p_pass)? $p_pass : self::$pass;
		$D = isset($p_db)  ? $p_db   : self::$database;


		if(@mysql_connect($H,$U,$P)==true){

			if(mysql_select_db($D)==false){
				$error = true;
				$error_code = 'SELECT_DB_ERROR';

			}

		}else{
			$error = true;
			$error_code = 'CONNECT_ERROR';

		}
		
		$arr_error['error'] = $error;
		$arr_error['code'] = $error_code; 

		return $arr_error;
			
	}

	/**
	 *@package db_mysql
	 *@method close()
	 *@desc Close MySQL connection
	 *@since v0.1 beta
	 * */
	public function close() {
		if(self::$is_connected){
			mysql_close();
			self::$is_connected = false;
		}
	}
	/**
	 *@package db_mysql
	 *@method query()
	 *@desc Send a MySQL query
	 *@since v0.1 beta
	 *@return bool & Populates $this->resource
	 * */
	public function query($query){

		$this->sql_query = $query ;

		if($this->resource = mysql_query($query)){
			return TRUE;
		}else{
			// is hanled error
			$error_no = mysql_errno();

			$is_handed = false;

			if(array_key_exists($error_no, $this->arr_handled_errors)){
				$is_handed = true;
			}

			if($is_handed==true){
				$this->error_code = $this->arr_handled_errors[$error_no];
				return FALSE;
			}else{
				// if uknown error
				try{
					throw new FkException("Mysql Error");
				}catch(FkException $e){
					$e->description='Mysql Respondi&oacute;:'. mysql_error().'</b>';
					$e->solution='Verifique la consulta';
					$e->solution_code= fk_str_format($query,'html');
					$e->error_code=$error_no;
					$e->show('code_help');
				}
				return FALSE;
			}



		} // End else
			

	}

	/**
	 *@package db_mysql
	 *@method query_assoc()
	 *@desc Send a MySQL query in assoc mode
	 *@since v0.1 beta
	 * */
	public function query_assoc($query){
		$this->query_is_assoc = true;
		$this->query($query);

	}

	/**
	 *@package db_mysql
	 *@method num_rows()
	 *@desc Get number of rows in result
	 *@since v0.1 beta
	 * */
	public function num_rows($rs = null){
		$Resource = ( $rs!=NULL? $rs : $this->resource);
		return mysql_num_rows($Resource);

	}
	/**
	 *@package db_mysql
	 *@method next()
	 *@desc Fetch a result row as an associative array, a numeric array, or both depending on query() or query_assoc() method
	 *@since v0.1 beta
	 * */
	public function next($rs = ''){

			
		$Resource = ( $rs!=''? $rs : $this->resource);

		if($this->query_is_assoc==true){
			return mysql_fetch_assoc($Resource);
		}else{return mysql_fetch_array($Resource);}
			
	}
	/**
	 *@package db_mysql
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
	 *@package db_mysql
	 *@method inserted_id()
	 *@desc Get the ID generated in the last query
	 *@since v0.1 beta
	 * */
	public function inserted_id(){
		return mysql_insert_id();
	}

	/**
	 *@package db_mysql
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
	 *@package db_mysql
	 *@method fetch_array()
	 *@desc Fetch a result row as an associative array, a numeric array, or both depending on query() or query_assoc() method
	 *@since v0.1 beta
	 * */
	public function fetch_array($rs = ''){
			
		$Resource = ( $rs!='' ? $rs : $this->resource );

		if($this->query_is_assoc==true){
			return mysql_fetch_assoc($Resource);
		}else{return mysql_fetch_array($Resource);}

	} // fetch_array(){

	/**
	 *@package db_mysql
	 *@method escape_string()
	 *@desc returns escape strings
	 *@since v0.1 beta
	 * */
	public function escape_string($str){

		return mysql_real_escape_string($str);

	} // escape_string()
}