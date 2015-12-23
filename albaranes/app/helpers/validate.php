<?php
/**
 * FreeKore Php Framework
 * Version: 0.1 Beta
 *
 * @Author     M. Angel Mendoza  email:mmendoza@freekore.com
 * @copyright  Copyright (c) 2010 Freekore PHP Team
 * @license    New BSD License
 */

/**
 * @package validate helper
 * @since   0.1 Beta
 */

if(!function_exists('is_email')){
	
	function is_email($pMail) {
		return filter_var($pMail, FILTER_VALIDATE_EMAIL);
	} 
		
}

if(!function_exists('is_empty')){
	
	function is_empty($str) {
		$str = trim($str);
	    if ($str!=null && $str!='') {
	       return FALSE;
	    } else {
	       return TRUE;
	    }
	} 
		
}

function generatePermalink($url){
	$url = utf8_decode($url);
	
	//Rememplazar caracteres especiales latinos
	$find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
	$repl = array('a', 'e', 'i', 'o', 'u', 'n');
	$url = str_replace ($find, $repl, $url);
	
	$find = array('Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ');
	$url = str_replace ($find, $repl, $url);
	
	$url = strtolower($url);//minusculas

	// Añadir guiones
	$find = array(' ', '&', '\r\n', '\n', '+'); 
	$url = str_replace ($find, '-', $url);

	// Eliminar y Reemplazar demás caracteres especiales
	$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
	$repl = array('', '-', '');
	$url = preg_replace ($find, $repl, $url);

	return $url;
}

function random($longitud){
	$cadena = 'abcdefghijklmnopqrstuvwxyz0123456789';
	$str="";
	
	for ($x=0;$x<$longitud;$x++){
		$n = rand(0,35);
		$str .= $cadena[$n];
	}
	
	return $str;
}

function sendHTML($to,$asunto,$mensaje){
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	mail($to,$asunto,$mensaje,$headers);
}

function getExtension($str) {
	$i = strrpos($str,".");
	if (!$i) { return ""; }
	$l = strlen($str) - $i;
	$ext = substr($str,$i+1,$l);
	return $ext;
}

function setPaginacion($url, $pactual, $show, $total, $showone = 0){
	if($total >1 || $showone == 1){
		$pactual = $pactual<1?1:$pactual;
		$min = $pactual - $show;
		$max = $pactual + $show;
		$min = $min<1?1:$min;
		$max = $max>$total?$total:$max;
		
		$html = '<ul class="pagination">';
		for($x = $min; $x<=$max; $x++){
			$class = $x==$pactual?'class="selected"':'';
			$html .= '<li><a href="'.$url.'/'.$x.'" '.$class.'>'.$x.'</a></li>';
		}
		$html .= '</ul><div class="clear"></div>';
		
		return $html;
	}
}