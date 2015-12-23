<?php
/**
 * FreeKore Php Framework
 * Version: 0.1 Beta
 *
 * @Author     M. Angel Mendoza  email:mmendoza@freekore.com
 * @copyright  Copyright (c) 2010 Freekore PHP Team
 * @license    New BSD License
 * @since      0.1 Beta
 *
 */

/**
 * @package    Load.class.php
 * @desc       Programa para cargar views,libs, helpers, plugins
 * @since      0.1 Beta
 */
class Load{

	// Contenido dinamico
	private $ViewContent= array(); // Contenido
	public $ReturnResult= false;  // False: Imprime resultado (Default), true devuelve resultado

	/**
	 * @package Load.class.php
	 * @method View()
	 * @desc Loads a View
	 * @example 1) $this->Load->View('Inicio/view.php');
	 *          2) $ArrData['mi_var']; $this->Load->View('Inicio/view.php',$ArrData);
	 *          3) $this->PutContent('{list}',$listValues);$this->Load->View('Inicio/view.php');
	 * @since 0.1 Beta
	 */
	public function View($view,$ArrData = array(),$ReturnResult=false){

		// False: Imprime resultado, true devuelve resultado
		$this -> ReturnResult = $ReturnResult;

		$view_file = $GLOBALS['FKORE']['config']['APP']['views_dir'].'/'.$view;
		$view_file_path = SYSTEM_PATH.$view_file;
		$view_exist = false;

		if( fk_file_exists($view_file) ){ $view_exist = true; }

		if($view_exist==FALSE){
			//view no existe
			if($GLOBALS['FKORE']['RUNNING']['app']['interactive']==true){

				try{
					throw new FkException("Archivo: ".$view_file." no existe ");
				}catch(FkException $e){
					$e->description='Es requerido el archivo view <b>'.$view_file.'</b>,
					                    sin embargo no fue encontrado';
					$e->solution='Cree el archivo <b>'.$view_file.'</b> y agregue el codigo requerido.
					                 Ejemplo:';
					$e->solution_code= fk_str_format('<?php fk_header();?>
Este es el archivo '.$view_file.'
<?php fk_footer();?> ','html');
					$e->show('code_help');

				}
					
			}else{ die("<fieldset><h1>Error 404 -1: La p&aacute;gina no existe </h1></fieldset>"); }

		}else{
			// Preprocesar la vista
			return $this->PreProcessFile($view_file_path,$ArrData);
		}

	} // End View
	/**
	 * @package Load
	 * @method Model()
	 * @desc Loads a model
	 * @example 1) $this->Load->Model('MyModel'); calls app/models/MyModel.php
	 *          2) $this->Load->Model(array('MyModel1','MyModel2')); calls the array of models
	 * @since 0.1 Beta
	 */
	public function Model($Model){

		if(is_array($Model)){
			// Cargar los modelos
			if(count($Model)>0){
				foreach ($Model as $k => $v) {
					// Cargar el modelo
					fkore::_use($GLOBALS['FKORE']['config']['APP']['models_dir'].'/'.$v.'.php');
				}
			} // End count $Model >0

		}else{
			// Cargar el modelo
			fkore::_use($GLOBALS['FKORE']['config']['APP']['models_dir'].'/'.$Model.'.php');

		}
	} // End Model
	/**
	 * @package Load
	 * @method Helper()
	 * @desc Loads a Helper
	 * @example 1) $this->Load->Helper('MyHelper'); calls app/helpers/MyHelper.php
	 *          2) $this->Load->Helper(array('MyHelper1','MyHelper2')); calls the array of Helpers
	 * @since 0.1 Beta
	 */
	public function Helper($Helper){

		if(is_array($Helper)){
			// Cargar los helpers
			if(count($Helper)>0){
				foreach ($Helper as $k => $v) {
					// Cargar helper
					fkore::_use($GLOBALS['FKORE']['config']['APP']['helpers_dir'].'/'.$v.'.php');
				}
			} // End count $Helper >0

		}else{
			// Cargar el Helper
			fkore::_use($GLOBALS['FKORE']['config']['APP']['helpers_dir'].'/'.$Helper.'.php');

		}
	}

	/**
	 * @package Load
	 * @method Library()
	 * @desc Loads a Library | loads array of libraries
	 * @example 1) $this->Load->Library('MyLibrary'); calls app/libraries/MyHelper.php
	 *          2) $this->Load->Library(array('MyLibrary1','MyLibrary2')); calls the array of Libraries
	 * @since 0.1 Beta
	 */
	public function Library($Lib){

		if(is_array($Lib)){
			// Cargar librerias
			if(count($Lib)>0){
				foreach ($Lib as $k => $v) {
					// Cargar el modelo
					fkore::_use($GLOBALS['FKORE']['config']['APP']['libraries_dir'].'/'.$v.'.php');
				}
			} // End count $Lib >0

		}else{
			// Cargar libreria
			fkore::_use($GLOBALS['FKORE']['config']['APP']['libraries_dir'].'/'.$Lib.'.php');
		}
	} // Library
	/**
	 * @package Load
	 * @method Plugin()
	 * @desc Loads a Plugin | loads array of Plugins
	 * @example 1) $this->Load->Plugin('MyPlugin'); calls app/PLUGINS/MyPlugin/MyPlugin.class.php
	 *             & app/PLUGINS/MyPlugin/MyPlugin.utils.php
	 *          2) $this->Load->Plugin(array('MyPlugin1','MyPlugin2')); calls the array of Plugins
	 * @since 0.1 Beta
	 */
	public function Plugin($Plugin){

		if(is_array($Plugin)){
			// Cargar Plugin
			if(count($Plugin)>0){
				foreach ($Plugin as $k => $v) {
					// Cargar el plugin

					fkore::_use($GLOBALS['FKORE']['config']['APP']['plugins_dir'].'/'.$v."/".$v.".class.php");
					// get other variables (css code, css links, js code, js links )
					fkore::_use($GLOBALS['FKORE']['config']['APP']['plugins_dir']."/".$v."/".$v.".utils.php");
				}
			} // End count $Plugin >0

		}else{
			// Cargar $Plugin
			fkore::_use($GLOBALS['FKORE']['config']['APP']['plugins_dir']."/".$Plugin."/".$Plugin.".class.php");
			// get other variables (css code, css links, js code, js links )
			fkore::_use($GLOBALS['FKORE']['config']['APP']['plugins_dir']."/".$Plugin."/".$Plugin.".utils.php");

		}
	} // $Plugin
	
	/**
	 * @package Load
	 * @method PreProcessFile()
	 * @desc Pre process file replacing the content sent by $this->PutContent() method
	 * @since 0.1 Beta
	 */
	private function PreProcessFile($File,$Arr = array()){

		// Declarar Arrays
		$ArrSearch = array();
		$ArrRepl   = array();

		// Array de variables enviado por funcion
		if(count($Arr)>0){
			foreach ($Arr as $k => $v){

				$$k = $v;
			}

		}

		// Array generado por PutContent
		if(count($this->ViewContent)>0){
			foreach ($this->ViewContent as $k => $v){
				$ArrSearch[] = $k;
				$ArrRepl[] = $v;
			}

		}

		$f_view = file_get_contents($File);

		$view_content = str_replace($ArrSearch, $ArrRepl, $f_view);

		$ViewResult = '';
		if($this->ReturnResult==TRUE){

			// Returns result
			ob_start();
			eval('?>' . $view_content . '<?php ');
			echo $ViewResult = ob_get_contents();
			ob_end_clean();

		}else{

			// Prints result
			echo  eval('?>' . $view_content . '<?php ');
            
		}

		return $ViewResult;


	} // End Pre Process

	/**
	 *@package Load
	 *@method PutContent()
	 *@desc replaces Content into a view
	 *@example  PutContent('{list}' , $MyListContent );
	 *          will replace the tag {list} with the $MyListContent in the
	 *          $this->Load->View('MyView.tpl') execution
	 *@since v0.1 beta
	 * */

	public function PutContent($Index , $Content ){

		$this->ViewContent[$Index] = $Content;
			
	}


}


