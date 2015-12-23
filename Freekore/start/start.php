<?php
/**
 * FreeKore Php Framework
 * Version: 0.1 Beta
 *
 * @Author     M. Angel Mendoza  email:mmendoza@freekore.com
 * @copyright  Copyright (c) 2010 Freekore PHP Team
 * @license    New BSD License
 */

//session_save_path('/tmp/');
session_start();

class start{
	static function get_filepath(){
		$fpath =self::create_file_path($_SERVER["SCRIPT_NAME"]);
		return $fpath;
	}
	static function create_file_path($file_name){

		$pth = explode('public/',$file_name);
		$pth[1] = trim(@$pth[1],'/');
			
		$tot = 0;
		if($pth[1]!=null){
			$pth_syspath = explode('/',$pth[1]);
			$tot = count($pth_syspath);
		}

		$SYS_PATH = './';

		for($i=0;$i<$tot;$i++){ $SYS_PATH .= '../'; }
		return $SYS_PATH;
	}
}

/*fkore app start*/
if(!defined('SYSTEM_PATH')){

	$APP_PATH = start::get_filepath();
	define('SYSTEM_PATH',$APP_PATH);

	include_once(SYSTEM_PATH.'/Freekore/ini.php');

}