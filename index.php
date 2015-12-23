<?
/**
 * FreeKore Php Framework
 * Version: 0.1 Beta
 *
 * @Author     M. Angel Mendoza  email:mmendoza@freekore.com
 * @copyright  Copyright (c) 2010 Freekore PHP Team
 * @license    New BSD License
 */

/**
 *  Load public/index.php
 **/
 

if(@$GLOBALS['ENV']['mod_rewrite_engine']==true){
	//mod rewrite is not supported
	
	?>
<h2>Fkore: No tiene Mod-ReWrite de Apache instalado</h2>
Debe habilitar/instalar mod_rewrite en su servidor Apache. Consulte para
m&aacute;s informaci&oacute;n:
<ul>
	<li><a href='http://httpd.apache.org/docs/2.0/misc/rewriteguide.html'>http://httpd.apache.org/docs/2.0/misc/rewriteguide.html</a>

</ul>

	<?
}else{
	// Start app
	include_once('./Freekore/start/start.php');
	// Ejecutar FreeKore
	fkore::InitializeFK($_GET,$mod_rewrite=false);


}
?>