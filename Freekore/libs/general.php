<?php
/**
 * FreeKore Php Framework
 * Version: 0.1 Beta
 *
 * @Author     M. Angel Mendoza  email:mmendoza@freekore.com
 * @copyright  Copyright (c) 2010 Freekore PHP Team
 * @license    New BSD License
 */
# FUNCIONES GENERALES DEL SISTEMA

// pa() = PRINT ARRAY
/**
 * 
 * @desc       formated print_r() function, by default prints $_POST array   
 * @since      0.1 Beta
 */
function pa($ARR = NULL){
	if($ARR!=NULL){
		$printArray = $ARR;
	}else{$printArray = $_POST;}
	$uniqId=rand(100,1000000000000000);
	if(count($printArray)>0){
		echo '<div id="DEBUG_POST_INFO'.$uniqId.'" class="message" style="width:500px">
  <a href="javaScript:oculta(\'DEBUG_POST_INFO'.$uniqId.'\');">[Show|Hide]</a>
  <pre id="data_DEBUG_POST_INFO'.$uniqId.'">';
		print_r($printArray);
		echo '</pre>
  
  </div>';  
	}
}
/**
 * 
 * @desc       Encode value into base64 string
 * @since      0.1 Beta
 */
// encode codifica los caracteres en base64
function encode($v){
	$CODE = trim(base64_encode($v),'=');
	return $CODE;
}
/**
 * 
 * @desc       Decode base64 string 
 * @since      0.1 Beta
 */
// encode decodifica los caracteres que estan en base64
function decode($v){
	$CODE = base64_decode($v);
	return $CODE;
}

