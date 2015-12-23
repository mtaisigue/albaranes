<?php
function http_uploads(){

	$app = $GLOBALS['FKORE']['config']['APP']['app_activated'];

	if($app=='production_app'){
		return HTTP_UPLOADS_PROD;
	}elseif($app=='internet_test_app'){
		return HTTP_UPLOADS_TEST;
	}elseif($app=='interet_dev_app'){
		return HTTP_UPLOADS_DEV;
	}else{
		return HTTP_UPLOADS_LOCAL;
	}

}

function app_is_on_production(){
	$app = $GLOBALS['FKORE']['config']['APP']['app_activated'];
	if($app=='production_app'){
		return true;
	}else{
		return false;
	}
}

function app_is_on_development(){
	$app = $GLOBALS['FKORE']['config']['APP']['app_activated'];
	if($app=='development_app'){
		return true;
	}else{
		return false;
	}
}
function app_is_on_dbinternet_dev(){
	$app = $GLOBALS['FKORE']['config']['APP']['app_activated'];
	if($app=='dbinternet_dev_app'){
		return true;
	}else{
		return false;
	}
}
function app_is_on_interet_dev(){
	$app = $GLOBALS['FKORE']['config']['APP']['app_activated'];
	if($app=='interet_dev_app'){
		return true;
	}else{
		return false;
	}
}
function app_is_on_testing(){
	$app = $GLOBALS['FKORE']['config']['APP']['app_activated'];
	if($app=='internet_test_app'){
		return true;
	}else{
		return false;
	}
}

function app_url_is($url){
	$url_conf = $GLOBALS['FKORE']['RUNNING']['app']['www_server'];
	if($url==$url_conf){
		return true;
	}else{
		return false;
	}
}

function app_is_hosted_on_internet(){
	$app = $GLOBALS['FKORE']['config']['APP']['app_activated'];
	if($app=='production_app' || $app=='internet_test_app' || $app=='interet_dev_app' ){
		return true;
	}else{
		return false;
	}
}
function uploads_directory(){

	$app = $GLOBALS['FKORE']['config']['APP']['app_activated'];

	if($app=='production_app'){
		return UPLOADS_DIRECTORY_PROD;
	}elseif($app=='internet_test_app'){
		return UPLOADS_DIRECTORY_TEST;
	}elseif($app=='interet_dev_app'){
		return UPLOADS_DIRECTORY_DEV;
	}else{
		return UPLOADS_DIRECTORY_LOCAL;
	}

}

if(!function_exists('priv_paquete')){
	function priv_paquete($codigo_paquete){

		$db = new db();
		$sql = 'SELECT count(id_paquete_usuario)
		        FROM paquetes_usuario pu 
		          INNER JOIN paquetes p ON pu.id_paquete = p.id_paquete
		        WHERE pu.id_usuario = "'.$_SESSION['id_usuario'].'" 
		        AND pu.fecha_fin >= CURDATE()
		        AND p.codigo ="'.$codigo_paquete.'" ';

		$db->query($sql);
		$found = $db-> next();
		$tot = $found[0];

		if($tot>=1){
			return TRUE;
		}else{
			return FALSE;
		}

	} // End priv()

} // priv()

if(!function_exists('checkbox_view')){
	function checkbox_view($checked){
		$checked_text = '';
		if($checked==1){
			$checked_text = 'checked="chekhed"';
		}
		$chk = '<input type="checkbox" value="1" name="chk-vw" disabled="disabled" '.$checked_text.' >';
		return $chk;
	}
} // end checkbox_view


/**
 * @desc devuelve enlace back similar a history.go(-1) en javascript
 * */
function go_back($default_link = NULL){

	$default_link = ($default_link == NULL) ? fk_link() : fk_link().$default_link ;
	$serv_refer = isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER'] : '';
	$url_refer = get_domain_url($serv_refer);
	$url_this_site = get_domain_url(fk_link());
	$link_refer = ($url_this_site==$url_refer) ? $_SERVER['HTTP_REFERER'] : $default_link;
	return $link_refer;
}

/**
 * @desc genera un transaction key
 * */
function setTransactionKey(){

	$t_key = encode(rand(1000000,100000000));
	$_SESSION['TRANSACTION_KEY'] = $t_key;
	return $t_key;
}
/**
 * @desc comprueba transaction key generado en la ultima operacion
 * */
function confirmTransactionKey($t_key){

	$TransactionKey = isset($_SESSION['TRANSACTION_KEY'])?$_SESSION['TRANSACTION_KEY']:NULL;
	
	if($t_key==$TransactionKey && $TransactionKey!=NULL ){
		//unset($_SESSION['TRANSACTION_KEY']);
		return true;
	}else{
		return false;
	}
}

function get_domain_url($url){
	$arrSrc = array('http://www.','https://www.','http://','https://');
	$url = str_replace($arrSrc, '', $url);
	$url_1 = explode('/', $url);
	$url = $url_1[0];
	return $url;
}

/**
 * @desc verifica la http url y el tipo de navegador y envia a mobil o al descktop
 * */
function verify_http_path(){
	
	$domain_url = get_domain_url(fk_link());
	$req_host = $_SERVER['HTTP_HOST'];
	
	if($req_host!=$domain_url){
		header('Location:'.fk_link());
	}
		
}