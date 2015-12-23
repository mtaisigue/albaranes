<?php
/**
 * FreeKore Php Framework
 * Version: 0.1 Beta
 *
 * @Author     M. Angel Mendoza  email:mmendoza@freekore.com
 * @copyright  Copyright (c) 2010 Freekore PHP Team
 * @license    New BSD License
 */

function fk_theme(){
	$theme  = (defined('theme'))? @constant('theme') : @constant('default_theme');
	return $theme;
}

function fk_theme_url(){
	$theme_url = HTTP.'_HTML/themes/'.fk_theme();
	return $theme_url;

}

function fk_no_display_header(){
	$GLOBALS['FKORE']['display_header_footer']=FALSE;
}
function fk_header_blank(){
	$GLOBALS['FKORE']['blank_header_footer']=TRUE;
}
function display_header_footer(){

	if(@$GLOBALS['FKORE']['display_header_footer']===FALSE){
		return FALSE;
	}else{ return TRUE;}

}
function display_blank_header(){
	if(@$GLOBALS['FKORE']['blank_header_footer']===TRUE){
		return TRUE;
	}else{ return FALSE;}
}

function fk_header($theme = NULL){

	if($theme==NULL){
		$theme=fk_theme();

	}else{
		define('theme',$theme);
	}

	if(display_header_footer()){
		if(display_blank_header()){
			// Display Blank Header
			fk::blank_header();
		}else{
			// Header normal
			fkore::_use('public/_HTML/themes/'.$theme.'/header.php');
		}

	}

}
function fk_footer($theme = NULL){

	if($theme==NULL){
		$theme=fk_theme();
	}
	if(display_header_footer()){

		if(display_blank_header()){
			// Display Blank Header
			fk::blank_footer();
		}else{
			// Header normal
			fkore::_use('public/_HTML/themes/'.$theme.'/footer.php');
		}
	}
}



function fk_link($p_lnk = ''){

	$mod_rew_enabled=$GLOBALS['FKORE']['config']['APP']['mod_rewrite_engine'];
	

	if($mod_rew_enabled){
		//mod rewrite
		if($p_lnk==''){
			$link = $GLOBALS['FKORE']['RUNNING']['app']['www_server'];
		}else{
		  $link = HTTP.$p_lnk;	
		}
		
	}else{
		// no mod rewrite
		//Account/productos?x=1&x2=2
		$url_vars = explode('?',$p_lnk);
		$url = @$url_vars[0];
		$vars = @$url_vars[1];

		if($vars!=''){
			$vars = str_replace('&',';',$vars);
			$vars = '?{'.$vars.'}';
		}

		$url = str_replace('/','::',$url);
		// 'Account::Producto?{x=1;y=2}';

		$link = HTTP."p?!=".$url.$vars;


	}

	return $link;

}

function fk_money_format($amount){ // FORMAT MONEY FOR WINDOWS
	setlocale(LC_ALL, ''); // Locale will be different on each system.
	$locale = localeconv();
	$mon_val =  $locale['currency_symbol'].number_format($amount, 2, $locale['decimal_point'], $locale['thousands_sep']);
	return $mon_val;
}

function encodedArray($StrEncoded){
	/*
	 * Generar array de caracteres especiales
	 * ej: base64_encode("á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ")
	 * */
	$StrDecoded = base64_decode($StrEncoded);
	eval("\$strByComas = \"$StrDecoded\";");
	$Arr=explode(',', $strByComas);

	return $Arr;
}

function fk_str_format($txt,$f = 'html:no-tags',$f_2 = ''){
	// Para:  'a', 'e', 'i', 'o', 'u', 'n','A', 'E', 'I', 'O', 'U', 'N'
	$find = encodedArray('4SzpLO0s8yz6LPEswSzJLM0s0yzaLNE=');
	// Para:  'a', 'e', 'i', 'o', 'u', 'n','A', 'E', 'I', 'O', 'U', 'N','<','>','"'
	$arr1 = encodedArray('4SzpLO0s8yz6LPEswSzJLM0s0yzaLNEsPCw+');
	$findHtml = array_merge($arr1,array('"')); // Add quot
	
	switch($f){
		//---------HTML---------------
		case "html":

			$repl = array('&aacute;', '&eacute;', '&iacute;', '&oacute;', '&uacute;', '&ntilde;'
			,'&Aacute;', '&Eacute;', '&Iacute;', '&Oacute;', '&Uacute;', '&Ntilde;','&lt;','&gt;','&quot;');
			$txt = str_replace ($findHtml, $repl, $txt);
			$txtrs = $txt;
			break;
			//---------HTML respetando los <>---------------
		case "html:no-tags":

			$repl = array('&aacute;', '&eacute;', '&iacute;', '&oacute;', '&uacute;', '&ntilde;'
			,'&Aacute;', '&Eacute;', '&Iacute;', '&Oacute;', '&Uacute;', '&Ntilde;');
			$txt = str_replace ($find, $repl, $txt);
			$txtrs = $txt;
			break;
			//---------Texto plano---------------
		case "txt":

			$repl = array('a', 'e', 'i', 'o', 'u', 'n','A', 'E', 'I', 'O', 'U', 'N');
			$txt = str_replace ($find, $repl, $txt);
			$txtrs = $txt;
			break;
			//---------For Url link---------------
		case "url":
			//Rememplazamos caracteres especiales latinos

			$repl = array('a', 'e', 'i', 'o', 'u', 'n','A', 'E', 'I', 'O', 'U', 'N');
			$txt = str_replace ($find, $repl, $txt);
			// Añaadimos los guiones
			$find2 = array(' ', '&', '\r\n', '\n', '+');
			$txt = str_replace ($find2, '-', $txt);
			// Eliminamos y Reemplazamos demás caracteres especiales
			$find3 = array('/[^A-Za-z0-9\-<>.$]/', '/[\-]+/', '/<[^>]*>/');
			$repl = array('', '-', '');
			$txt = preg_replace ($find3, $repl, $txt);
			$txtrs = $txt;
			break;
		case "php_var":
			//Rememplazamos caracteres especiales latinos
			$repl = array('a', 'e', 'i', 'o', 'u', 'n','A', 'E', 'I', 'O', 'U', 'N');
			$txt = str_replace ($find, $repl, trim($txt));
			// Anadimos los guiones
			$find2 = array(' ', '&', '\r\n', '\n', '+');
			$txt = str_replace ($find2, '_', $txt);
			// Eliminamos y Reemplazamos demas caracteres especiales
			$find3 = array('/[^A-Za-z0-9\-<>.$]/', '/[\-]+/', '/<[^>]*>/');
			$repl = array('_', '_', '');
			$txt = preg_replace ($find3, $repl, $txt);
			$txtrs = $txt;
			break;
			//---------For CamelCase---------------
		case "camelcase":
			$txtrs =  camelcase($txt);
			break;

	 default:
	 	$txtrs = $txt;
	 	break;

	} // End Case

	// Second Format "CamelCase"

	if($f_2 == 'camelcase'){
		$txtrs = camelcase($txtrs);
	}

	return $txtrs;
} // End fk_str_format

function fk_file_exists($f){

	$rs = false;
	if(trim($f)!=NULL && trim($f)!=''){

		$rs = file_exists(SYSTEM_PATH.$f);

	}

	return $rs;
}

function fk_js(){
	$js_path = HTTP.'_HTML/javascript/';
	?>
<script language="javascript" type="text/javascript"> var HTTP = "<?php echo HTTP?>";</script>
<script	type="text/javascript" src="<?php echo $js_path;?>CODE/jquery-ui/js/jquery-1.4.2.min.js"></script>
<script	type="text/javascript" src="<?php echo $js_path;?>CODE/jquery-ui/js/jquery-ui-1.8.2.custom.min.js"></script>
<?php
	/* <script	type="text/javascript" src="<?php echo $js_path;?>CODE/validate/jquery.validate.min.js"></script> */
?><script type="text/javascript" src="<?php echo $js_path;?>CODE/validate/jquery.validate.js"></script> 
<script	type="text/javascript" src="<?php echo $js_path;?>CODE/validate/jquery.maskedinput-1.2.2.min.js"></script>
<script type="text/javascript" src="<?=$js_path;?>CODE/dataTables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?=$js_path;?>CODE/dataTables/media/js/jquery.dataTables.FkUtils.js"></script> 
<script	type="text/javascript" src="<?php echo $js_path;?>fk.js"></script>
<script	type="text/javascript" src="<?php echo $js_path;?>custom.js"></script>
<?php
	/*<script
	 type="text/javascript"
	 src="<?php echo $js_path;?>CODE/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>*/
	#print all js links
	//echo '<!--otros js-->';
	echo $GLOBALS['FKORE']['js_links'];
}

function fk_js_addResource($url){
	$GLOBALS['FKORE']['js_links'] .= '<script src="'.HTTP.'_HTML/fk_utils/FkResource.php?r='.encode($url).'&t=js" type="text/javascript"></script>
';
}

function fk_css_addResource($url){
	$GLOBALS['FKORE']['css_links'] .= '<link type="text/css" href="'.HTTP.'_HTML/fk_utils/FkResource.php?r='.encode($url).'&t=css" rel="stylesheet" />
';
}

function fk_js_addLink($url){
	$GLOBALS['FKORE']['js_links'] .= '<script type="text/javascript" language="javascript" src="'.$url.'"></script>
';
}

function fk_css_addLink($url){
	$GLOBALS['FKORE']['css_links'] .= '<link type="text/css" href="'.$url.'" rel="stylesheet" />
';
}


function fk_css(){
		
		$js_path = HTTP.'_HTML/javascript/';
		$css_path = HTTP.'_HTML/css/';

	?>
<style type="text/css" title="currentStyle">@import "<?php echo $css_path;?>FreeKore.css";</style>
	<?php
	echo $GLOBALS['FKORE']['css_links'];
	?>
	<link type="text/css" href="<?php echo $js_path;?>CODE/jquery-ui/css/smoothness/jquery-ui-1.8.6.custom.css"	rel="stylesheet" />
<style type="text/css" title="currentStyle">
@import "<?php echo $js_path;?>CODE/dataTables/media/css/demo_page.css";
@import	"<?php echo $js_path;?>CODE/dataTables/media/css/demo_table_jui.css";
</style><?php
	#print all css links

}

function fk_menu($IdMenu,$SelectedItem){
	define('MENU_'.$IdMenu.'',$SelectedItem);
}

function fk_page($var = '',$val){
	define('FK_PAGE_'.$var.'',$val);
}

function fk_document_title(){
	$title = 'Untitled Document';
	if(defined('FK_PAGE_TITLE')){ $title = constant('FK_PAGE_TITLE'); }
	return $title;
}

function fk_document_description(){
	$d = '';
	if(defined('FK_PAGE_DESCRIPTION')){ $d = constant('FK_PAGE_DESCRIPTION'); }
	return $d;
}

function fk_document_keywords(){
	$d = '';
	if(defined('FK_PAGE_KEYWORDS')){ $d = constant('FK_PAGE_KEYWORDS'); }
	return $d;
}

function fk_select_options($SQL,$SELECTED = NULL){

	$db = new db();

	$db -> query($SQL);
	$OPTION = '';

	while($OPT = $db->next()){
		if($OPT[0]==$SELECTED){ $IS_SELECTED='selected="selected"';}else{$IS_SELECTED='';}
		$OPTION .= '<option value="'.$OPT[0].'" '.$IS_SELECTED.'>'.fk_str_format($OPT[1], 'html').'</option>';
	}
	return $OPTION;

}
function fk_select_text($table,$fields,$id_selected){

	$db = new db();
	
	$OPTION[0] = '';
	$OPTION[1] = '';
	
	$rec = new ActiveRecord($table);
	
	$WHERE = ' WHERE '.$rec-> id_field_name.' = "'.$id_selected.'" ';
	
	
	$SQL = 'SELECT '.$fields.' FROM '.$table.' '.$WHERE ;
	$db -> query($SQL);
	
	if($opt = $db->next()){
	    $OPTION[0] = $opt[0];  
	    $OPTION[1] = $opt[1];
	}
	
	
	return $OPTION;

}
function fk_get_query_elements($sql_query){
	
	$sql_query = trim($sql_query);
	$sql_query = str_replace(' ', '-', $sql_query);
	$sql_query = str_replace(';', '', $sql_query);
	
	$xp = explode('-', $sql_query);
	
	$nw_sql='';
	foreach ($xp as $k => $v){
		$v =trim($v); 
	   if($v!=''){
	      if($v=='select' || $v=='from'|| $v=='WHERE'){ $v=strtoupper($v);}
	      $nw_sql .= ' '.$v;
	   }     
	}
	$nw_sql = trim($nw_sql);
	
	//Table
	$ex_table = explode('FROM', $nw_sql);
	$table = trim($ex_table[1]);
	
	//fields
	$ex_fld = explode('SELECT', $ex_table[0]);
	$fields = trim($ex_fld[1]);
	
	
	$ARR['table']=$table;
	$ARR['fields']=$fields;
	
	return $ARR;
}

function fk_select_options_r($array,$SELECTED = NULL){

	$OPTION = '';
	foreach($array as $k=>$v){
		if($k==$SELECTED){ $IS_SELECTED='selected="selected"';}else{$IS_SELECTED='';}
		$OPTION .= '<option value="'.$k.'" '.$IS_SELECTED.'>'.$v.'</option>';
	}
	return $OPTION;

}

function camelcase($s){

	$s = ucwords(strtolower(strtr($s, '_', ' ')));
	$s = str_replace(' ', '', $s);
	return $s;
}
//@todo: create file no funciona
function fk_create_file($f){

	$f_xp = explode('/',$f);
	$tot = count($f_xp);
	$file_p = '';
	$i = 1;
	foreach($f_xp as $k=>$v){
		$file_p .= '/'.$v;
		if($i==$tot){
			// file
			//if(file_exists(SYSTEM_PATH.'/'.$v))
		}else{
			//dir
			$file_p = trim($file_p);
			echo SYSTEM_PATH.substr($file_p,1,strlen($file_p)).'<hr>';
		}
		$i++;
	}

}

/**
 * Converts string to a timestamp, Format Required: Y-m-d H:i:s
 * @param string $fecha
 * @return int
 */
function fk_convert_to_timestamp($fecha){

	//defino fecha 1
	$fecha_xpl = explode(' ',$fecha);
	$fecha_tmp = $fecha_xpl[0];
	$hora_tmp = $fecha_xpl[1];
	$fecha_1 = explode('-',$fecha_tmp);
	$hora_1 = explode(':',$hora_tmp);
	$ano1 = $fecha_1[0];
	$mes1 = $fecha_1[1];
	$dia1 = $fecha_1[2];
	$hor1 = $hora_1[0];
	$min1 = $hora_1[1];
	$sec1 = $hora_1[2];

	$timestamp = mktime($hor1,$min1,$sec1,$mes1,$dia1,$ano1);

	return $timestamp;

}

function fk_lapse_of_time($fecha1,$fecha2,$RETURN_JUST_TIME=false){


	//calculo timestamp de las dos fechas
	$timestamp1 = fk_convert_to_timestamp($fecha1);
	$timestamp2 = fk_convert_to_timestamp($fecha2);

	//resto a una fecha la otra
	$segundos_diferencia = $timestamp2 - $timestamp1;
	$segundos_diferencia = abs($segundos_diferencia);// Val Absoluto

	//convierto segundos en minutos
	$minutos_diferencia = floor($segundos_diferencia / (60));

	//convierto segundos en horas
	$horas_diferencia = floor($segundos_diferencia / (60 * 60));

	//convierto segundos en dias
	$dias_diferencia = floor($segundos_diferencia / (60 * 60 * 24));

	//convierto segundos en meses
	$meses_diferencia = floor($segundos_diferencia / (60 * 60 * 24 * 30));

	//DEVOLVER RESULTADO
	if($segundos_diferencia < 60){
		//Segs
		$rs = $segundos_diferencia.' Segundo'.fk_plural($minutos_diferencia,'s','');
	}elseif($minutos_diferencia < 60){
		//Mins
		$rs = $minutos_diferencia.' Minuto'.fk_plural($minutos_diferencia,'s','');

	}elseif($horas_diferencia < 24){
		//Horas
		$rs = $horas_diferencia.' Hora'.fk_plural($horas_diferencia,'s','');
	}elseif($dias_diferencia < 30){
		//Dias
		$rs = $dias_diferencia.' Dia'.fk_plural($dias_diferencia,'s','');
	}else{
		//Meses
		$rs = $meses_diferencia.' Mes'.fk_plural($meses_diferencia,'es','');

	}
	//DEVOLVER VALOR O ENVIAR SEGUNDOS DE DIFERENCIA
	if($RETURN_JUST_TIME==TRUE){
		return $segundos_diferencia;
	}else{
		echo $rs;
	}


} // end fk_lapse_of_time

function fk_plural($tot,$plural,$singular = ''){
	$rs = '';
	if($tot==1){ $rs = $singular;}else{$rs = $plural;}
	return $rs;

} // end fk_thime

// FUNCION LENGUAGE
function __($str){

	if(!defined($str)){
		return fk_str_format($str, 'html:no-tags');
	}else{
		return fk_str_format(constant($str), 'html:no-tags');
	}

}

function fk_download_file($file){

	// Dar formato al nombre del archivo a descargar
	$file_to_download = $file;
	$pos = strrpos($file, "/");
	if($pos!=''){
		$file_to_download = substr($file, $pos+1,strlen($file));
	}


	$f = SYSTEM_PATH.$file;
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"$file_to_download\"\n");
	$fp = fopen("$f","r");
	fpassthru($fp);
}

function fk_export_excel($content,$filename){

	// Exportartabla copmo archivo excel
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=$filename.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	echo $content;


}
function fk_memory_usage(){
	$size=memory_get_usage(true);
	return fk_size_format($size);

}

function fk_size_format($size){
	$unit=array('b','kb','mb','gb','tb','pb');
	return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}
function fk_ok_message_dialog($Message,$AutoClose = TRUE){
	
    $id = 'fk-msg-dlg'.encode(rand('10000000','10000000000'));
	
	$x = '<script>
	$(function() {
		var dlg = $( "#'.$id.'" ).dialog({
			autoOpen: true,
			buttons: {
				"Aceptar": function() {
				   $( this ).dialog( "close" );
				}
			}
	    });
	});';
    if($AutoClose==TRUE){
		$x .='setTimeout("$( \'#'.$id.'\' ).dialog(\'close\')",3000);';
	}
	$x.='</script><div id="'.$id.'" title="Mensaje" ><div class="fk-ok-message">'.OK_ICON.$Message.'</div></div>';

return $x;

}
function fk_ok_message($Message,$AutoHide = TRUE){
	$id = 'fk-message-'.encode(rand('10000000','10000000000'));

	$x = '<div id="'.$id.'" class="fk-ok-message">'.OK_ICON.__($Message).'</div>';
	if($AutoHide==TRUE){
		$x .= '<script>
	var options = {};
	setTimeout("hide(\''.$id.'\')",3000);
	//$("#'.$id.'").hide( "highlight", options, 3000);
	</script>
	';
	}
	return $x;
}
function fk_alert_message_dialog($Message,$AutoClose = TRUE){
	
    $id = 'fk-msg-dlg'.encode(rand('10000000','10000000000'));
	
	$x = '<script>
	$(function() {
		var dlg = $( "#'.$id.'" ).dialog({
			autoOpen: true,
			buttons: {
				"Aceptar": function() {
				   $( this ).dialog( "close" );
				}
			}
	    });
	});';
    if($AutoClose==TRUE){
		$x .='setTimeout("$( \'#'.$id.'\' ).dialog(\'close\')",3000);';
	}
	$x.='</script><div id="'.$id.'" title="Alerta" ><div class="fk-alert-message">'.ALERT_ICON.__($Message).'</div></div>';

return $x;

}
function fk_alert_message($Message,$AutoClose = TRUE){
	$id = 'fk-message-'.encode(rand('10000000','10000000000'));
	$x = '<div id="'.$id.'" class="fk-alert-message">'.ALERT_ICON.__($Message).'</div>';

	if($AutoClose==TRUE){
		$x .='<script>setTimeout("hide(\''.$id.'\')",3000);</script>';
	}

	return $x;
}

function fk_post($val){
	if(isset($_POST[$val])){
		return $_POST[$val];
	}else{ return '';}
}

function fk_get($val){
	if(isset($_GET[$val])){
		return $_GET[$val];
	}else{ return '';}
}

function fk_even_odd($cnt){
	if($cnt%2==0){
		$rs = 'even';
	}else{$rs = 'odd';}
	return $rs ;
}

function fk_unique_code($length = 8){
	$code = md5(uniqid(rand(), true));
	if ($length != "") return substr($code, 0, $length);
	else return $code;
}

function getFormatedDate($fecha){

	// recibe formato "YYYY-mm-dd" (Y-m-d)
	//echo $fecha;
	//echo '<hr>';

	$dia=substr($fecha,8,2);
	//echo '<hr>';
	$mes=substr($fecha,5,2);
	//echo '<hr>';
	$agno=substr($fecha,0,4);
	//echo '<hr>';
	setlocale(LC_ALL,"es_CL");
	$loc = setlocale(LC_TIME, NULL);
	

	if ($mes=="01") $xmes=__('Enero');
	if ($mes=="02") $xmes=__('Febrero');
	if ($mes=="03") $xmes=__('Marzo');
	if ($mes=="04") $xmes=__('Abril');
	if ($mes=="05") $xmes=__('Mayo');
	if ($mes=="06") $xmes=__('Junio');
	if ($mes=="07") $xmes=__('Julio');
	if ($mes=="08") $xmes=__('Agosto');
	if ($mes=="09") $xmes=__('Setiembre');
	if ($mes=="10") $xmes=__('Octubre');
	if ($mes=="11") $xmes=__('Noviembre');
	if ($mes=="12") $xmes=__('Diciembre');

	//return  $fecha= strftime("%d de %B del %Y",mktime(0,0,0,$mes,$dia,$agno));
	if(isset($xmes)){
	   return  $fecha= strftime("%d de ".$xmes.", %Y",mktime(0,0,0,$mes,$dia,$agno));
	}else{
	   return  '';
	}
	
}
