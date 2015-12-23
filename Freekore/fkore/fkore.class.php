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
 **  Programa:  fkore.class.php
 **  Descripcion: Funciones principales del sistema FlexKore
 **  proyecto    fecha      por         descripcion
 **  ----------  ---------  ----------- ----------------
 **  00000001    26/04/10   mmendoza    Creado.
 **************************************************************/
/**
 *@package fkore
 *@since v0.1 beta
 *@desc  Freekore Main Package
 * */
class fkore {

	/**
	 *@package fkore
	 *@var string
	 *@desc  Freekore Version
	 * */
	static $Version = '0.1';

	/**
	 *@package fkore
	 *@method _use()
	 *@since v0.1 beta
	 *@desc  Includes file or files inside Freekore Application. Using it You Don't have
	 *		 to worry about relative path. Automatically detects the path.
	 *@example 1) fkore::_use('app/models/*'); Includes all files on app/models/
	 *         2) fkore::_use('app/models/*','.php'); Includes all .php files on app/models/
	 *         3) fkore::_use('app/models/file.php'); Includes app/models/file.php
	 *         4) fkore::_use('app/models/file.php', FALSE); includes  app/models/file.php but
	 *            if file does not exist the application can continue.
	 *            By default Required param is set to TRUE.
	 *
	 * */
	public static function _use($p_rute,$end = '',$required = true){

		$x_r=explode('/',$p_rute);
		$num_dirs=count($x_r);
		$dir = trim(SYSTEM_PATH.$p_rute,'*');

		if($x_r[$num_dirs-1]!="*"){
			// un solo archivos
			try{
			 if(file_exists($dir)){
			 	include_once($dir);
			 }else{
			 	// Mandar error si es requerido
			 	if($required == true){
			 		throw new FkException('El archivo '.$dir.' no existe');
			 	}
			 }
			}catch(FkException $ex){
			 $ex->show();
			}

		}else{

			// multiples archivos
			try{
				if(!@$reader = opendir($dir)){


					throw new FkException('Directorio '.$dir.' no existe');
				}
			}catch(FkException $ex){
				$ex->show();
			}
			while ($file = readdir($reader)){

				if(!is_dir(SYSTEM_PATH.$file)){

					if($end != ''){
						// con terminacion
				  $filex =	explode($end, $file);
				  if(count($filex)==2 && $filex[1] == '' ){
				  	if(file_exists($dir.$file)){ include_once($dir.$file);}
				  }
					}else{
						// archivo completo
						if(file_exists($dir.$file)){
							include_once($dir.$file);
					 }
					}
				} // end if !is_dir
			} // end while
			closedir ($reader);
			// multiples archivos
		}

	} // end _use()
	/**
	 *@package fkore
	 *@method _use()
	 *@since v0.1 beta
	 *@desc  Includes Freekore Plugin
	 *       1) loads app/PLUGINS/{plugin}/{plugin}.class.php file &
	 *        app/PLUGINS/{plugin}/{plugin}.utils.php file
	 * */
	public static function usePlugin($plugin){

		// Include Class
		self :: _use("app/PLUGINS/".$plugin."/".$plugin.".class.php");

		// get other variables (css code, css links, js code, js links )
		//Js
		self :: _use("app/PLUGINS/".$plugin."/".$plugin.".utils.php");
			
			
			
	} // _useControl('FkGrid')
	/**
	 *@package fkore
	 *@method url()
	 *@since v0.1 beta
	 *@desc  returns the http url
	 * */
	//function para imprimir una url,
	//TODO:considerara permalinks en la proxima version
	public static function url($p_path){
		return RUTA_HTTP.$p_path;
	} // url

	/**
	 *@package fkore
	 *@method get_theme_dir()
	 *@since v0.1 beta
	 *@desc  Returns the complete theme url
	 * */
	//function para obtener la ruta http de la ruta raiz del tema configurado
	public static function get_theme_dir($tema = NULL){

		if(!defined('SELECTED_THEME_DIR')){
			// definir ruta del tema seleccionado
			if($tema==NULL || trim($tema)==''){
				$the_theme=THEME;
			}else{
				$the_theme=$tema;
			}

			$url_dir = RUTA_HTTP.THEMES_DIR.'/'.$the_theme;
			define('SELECTED_THEME_DIR',$url_dir);

		}

		$ruta_http_tema = constant('SELECTED_THEME_DIR');

		return $ruta_http_tema;
	} // get_theme_dir
	/**
	 *@package fkore
	 *@method FkResource($Resource,$Type)
	 *@since v0.1 beta
	 *@desc  Gets the .js or .css resource
	 * */
	public static function FkResource($Resource,$Type){
		$Resource = decode($Resource);
		$fileName = SYSTEM_PATH.$Resource;
		// Reject if is not  .js o .css file
		$ext=self::file_ext($fileName);
		if($ext=='js' || $ext == 'css'){
			if(file_exists($fileName)){
				$file =file_get_contents($fileName);
				if($Type=='js'){
					header("Content-type: text/javascript");
					echo $file;
				}
				if($Type=='css'){
					header("Content-type: text/css");
					echo $file;
				}


			}
		}
	}
	/**
	 *@package fkore
	 *@method file_ext($file)
	 *@since v0.1 beta
	 *@desc  returns the file exencion
	 * */
	public static function file_ext($file){
		$fext = explode('.',$file);
		$ext_pos = count($fext) - 1;
		return $fext[$ext_pos];
	}
	/**
	 *@package fkore
	 *@method Initialize()
	 *@since v0.1 beta
	 *@desc  runs freekore
	 **/
	public static function InitializeFK($P_GET,$p_mod_rewrite = true){
		
		//load view
		if(isset($P_GET['!'])){
			$url_rs= self::url_processor($P_GET['!'],false);
		}else{
			if(isset($P_GET['url'])){
				$url_rs=self::url_processor($P_GET['url']);
			}
		}


		if(!isset($url_rs)){
			$url_rs['controller'] = 'IndexController';
			$url_rs['module'] = 'index';
			$url_rs['action'] = 'index';

			$url_rs['file_controller'] = 'index'.CONTROLLER.'.php';
			$url_rs['file_view'] = 'index/index'.VIEW.'.php';
		}



		$controller_exist = false;
		if( fk_file_exists(@$GLOBALS['FKORE']['config']['APP']['controllers_dir'].'/'.$url_rs['file_controller']) ){ $controller_exist = true; }


		if($controller_exist==true){
			// controler existe

			// view existe
			//EJECUTAR CONTROLLER
			
			require(SYSTEM_PATH.$GLOBALS['FKORE']['config']['APP']['controllers_dir'].'/'.$url_rs['file_controller']);
			
			//EJECUTAR CONTROLLER
			$page = new $url_rs['controller']($url_rs);

		}else{
			// controler no existe
			if($GLOBALS['FKORE']['RUNNING']['app']['interactive']==true){
				// MOSTRAR ERROR Y AYUDA

				$cont_control = str_replace('__ControlerName__',$url_rs['controller'],file_get_contents(SYSTEM_PATH.'Freekore/build/templates/controller-layout.tpl'));

				try{
					throw new FkException('El Controlador "'.$url_rs['file_controller'].'" no existe');
				}catch(FkException $e){
					$e->description='Es requerido el archivo Controllador <b>'.$url_rs['file_controller'].'</b>
						                    , sin embargo no fue encontrado.';
					$e->solution='1. Crea la clase <b>'.$url_rs['controller'].'</b> en el archivo
						                    <b>'.$url_rs['file_controller'].'</b> ';						                           $e->solution_code = fk_str_format($cont_control,'html');
					$e->show('code_help');
				}

			}else{
				require(SYSTEM_PATH.'FreeKore/sys_messages/page/error_404.php'); 
		    }
		}

	} // End InitializeFK
	private static function url_processor($url,$mod_rewrite = true){

		$file_lst = array();

		if($mod_rewrite){
			//----------------
			//MOD REWRITE TRUE
			//----------------
			$url_div = explode('/',$url);
			$tot= count($url_div);

			$cnt = 0;

			for($i=0;$i<$tot;$i++){

				if(trim($url_div[$i])!=''){
					$cnt++;

					$file_lst['url'][$cnt]['value'] = $url_div[$i];
					$file_lst['url'][$cnt]['is_file_or_dir'] = 'dir';
				}

			}
			// last is file

			$file_lst['url'][$cnt]['is_file_or_dir'] = 'file';

		}else{
			//----------------
			//MOD REWRITE FALSE
			//----------------
			$_slash = '::';
			$_q_mark = '?';


			$url_and_vars = explode($_q_mark,$url);

			$the_url = $url_and_vars[0];
			$the_vars = @$url_and_vars[1];

			$url_div = explode($_slash,$the_url);
			$tot= count($url_div);

			$cnt = 0;

			for($i=0;$i<$tot;$i++){

				if(trim($url_div[$i])!=''){
					$cnt++;
					$file_lst['url'][$cnt]['value'] = $url_div[$i];
					$file_lst['url'][$cnt]['is_file_or_dir'] = 'dir';
				}
			}

			// last is file

			$file_lst['url'][$cnt]['is_file_or_dir'] = 'file';

			//get prams
			$the_vars = trim($the_vars,'{');
			$the_vars = trim($the_vars,'}');
			$the_vars_arr = explode(';',$the_vars);

			$file_lst['get_vars']=array();

			if(count($the_vars_arr)>0){
				foreach($the_vars_arr as $k => $v){
					$new_v = explode('=',$v);

					if(isset($new_v[0]) && isset($new_v[1])){
						$file_lst['get_vars'][$new_v[0]]=$new_v[1];
					}

				}
			}



		}


		// return controller , view  &  model files


		$file_view = '';
		$file_controller = '';
		$controller = '';
		$module = '';
		$action = 'index';

		$i=0;
		foreach($file_lst['url'] as $k=>$v){

			// Controller
			if($i==0){
				$module = $v['value'];
				$file_view .= $v['value'].'/';
				$file_controller = $v['value'].CONTROLLER.'.php';
				$controller = fk_str_format($v['value'],'php_var','camelcase');
				$controller = $controller.'Controller';
			}

			if($i==1){
				$action = $v['value'];
				$file_view .= $v['value'].VIEW.'.php';

			}

			$i++;
		} // end foreach


		$ext = substr($file_view,strlen($file_view)-9,strlen($file_view));

		if($ext!= VIEW.'.php'){
			$file_view .= 'index'.VIEW.'.php';
		}

		$file_rs = array();
		$file_rs['module']=fk_str_format($module,'php_var');
		$file_rs['action']=fk_str_format($action,'php_var');
		$file_rs['file_view']=$file_view;
		$file_rs['file_controller']=$file_controller;
		$file_rs['controller']=$controller;
		$file_rs['directory_track']=$file_lst['url'];

		$file_rs['get_vars']=@$file_lst['get_vars'];

		return $file_rs;

	} // url_processor($url)


	public static function fk_autoload(){
		self::autoloader('models');    // Models
		self::autoloader('plugins');   // Plugins
		self::autoloader('libraries'); // Libs
		self::autoloader('helpers');   // Helpers
	}
	private static function autoloader($mode){

		$Dir = $GLOBALS['FKORE']['config']['APP'][$mode.'_dir'];
		$Arr = $GLOBALS['autoload'][$mode];

		if(count($Arr)>0){
			foreach ($Arr as $k=>$v){
				fkore::_use($Dir.'/'.$v.'.php');
			}
		}
	}

	public static function load_configuration(){
		//--------------------
		// LOAD CONFIG FILES
		//--------------------
		// config.ini
		self::read_config_file('config');
		// environment.ini
		self::read_config_file('environment');


		//--------------------
		// Set view,controler & model files variable
		//--------------------
		$ENV_VIEW = @$GLOBALS['FKORE']['config']['APP']['view_files'];
		$view  = ($ENV_VIEW!="")? $ENV_VIEW : '.view';

		$ENV_MODEL = @$GLOBALS['FKORE']['config']['APP']['model_files'];
		$model  = ($ENV_MODEL!="")? $ENV_MODEL : '.model';

		$ENV_CONTR = @$GLOBALS['FKORE']['config']['APP']['controller_files'];
		$controller  = ($ENV_CONTR!="")? $ENV_CONTR : '.controller';

		define('VIEW',$view);
		define('MODEL',$model);
		define('CONTROLLER',$controller);

		//--------------------
		// Set database conection
		//--------------------
		// get app activated
		$app_on=$GLOBALS['FKORE']['config']['APP']['app_activated'];
		$app_on = strtoupper($app_on);

		$arr_app_act = $GLOBALS['FKORE']['config'][$app_on];
		// get environment activated
		$env_on = strtoupper($arr_app_act['mode']);

		//--------------------
		// Set HTTP PATH
		//--------------------
		//Set HTTP variable = www_server
		//(moved to from AppController)
		//define('HTTP',$arr_app_act['www_server']);

		// get environment activated variables
		$arr_env = $GLOBALS['FKORE']['environment'][$env_on];

		$GLOBALS['FKORE']['RUNNING']['app'] = $arr_app_act;
		$GLOBALS['FKORE']['RUNNING']['db'] = $arr_env;

		// define  database vars
		define('HOST',$arr_env['db_host']);
		define('USER',$arr_env['db_username']);
		define('PASSWORD',$arr_env['db_password']);
		define('SYSTEM_DB',$arr_env['db_name']);
		define('DB_TYPE',$arr_env['db_type']);
		// Inicializar JS links, Css links
		$GLOBALS['FKORE']['js_links'] = '';
		$GLOBALS['FKORE']['css_links'] = '';

		//SET LANGUAGE
		$DEFAULT_LANGUAGE = $GLOBALS['FKORE']['config']['APP']['default_language'];
		$GLOBALS['APP_LANGUAGE']  = (@$_SESSION['language']!=null) ? $_SESSION['language'] : $DEFAULT_LANGUAGE ;



		//pa($GLOBALS['FKORE']);



	} // read_config

	private static function read_config_file($FILE){

		//
		$file_cnf = file(SYSTEM_PATH.'app/config/'.$FILE.'.ini');
		$subsection = false;

		foreach($file_cnf as $k=>$v){

			$v=trim($v);
			$char0=substr($v,0,1);

			if($char0!=';' && $char0!='#' && $v!=''){
				//LINEAS NO COMENTADAS
				$var_value = explode('=',$v);
				$var   = trim($var_value[0]);
				$value = trim(@$var_value[1]);
				$value = trim($value,'"');

				if(strtoupper($value)==="ON"){$value = TRUE;}
				if(strtoupper($value)==="OFF"){$value = FALSE;}




				if($char0=='['){
					// SUB SECTION
					$subsection = true;
					$section_name = trim($var,'[');
					$section_name = trim($section_name,']');
					$section_name = strtoupper($section_name);

				}else{
					// VARS
					if(!$subsection){
						// NO SECCIONES
						$GLOBALS['FKORE'][$FILE][$var]=$value;
					}else{
						// SI HAY SECCIONES
						$GLOBALS['FKORE'][$FILE][$section_name][$var]=$value;

					}


				}
				//LINEAS NO COMENTADAS
			}

		}

			

	} // read_config_file

	/**
	 *@package fkore
	 *@method createUrlRelative()
	 *@desc creates the Url Relative to the Current Url
	 *@example  Current url = "http://example/Controller/Model/var1/"
	 *          relative url = "../../../" , removing Controller/Model/var1/
	 *@since v0.1
	 * */
	public static function createUrlRelative(){
		if(!defined('HTTP')){
			$url_get_vars = isset($_GET['url']) ?  $_GET['url'] :'';
				
			$ur_xp = explode('/', $url_get_vars);
			$tot_expl = count($ur_xp);
			$tot = $tot_expl -1;
				
			$rel_path = '';
			for($i=1; $i <= $tot; $i++){
				$rel_path .= '../';
			}
				
			//--------------------
			// Set HTTP PATH
			//--------------------
			//Set HTTP variable = www_server
			define('HTTP',$rel_path);
		}			
	} // createUrlRelative
} // End class fkore



