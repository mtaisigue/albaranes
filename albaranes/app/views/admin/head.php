<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo fk_document_title();?></title>
<link href="<?php echo fk_theme_url()?>/css/main.css" rel="stylesheet"	type="text/css" />
<?php
fk_css();
fk_jS();
?>
<script type="text/javascript" > var theme_url = '<?php echo fk_theme_url()?>'; 
var root = '<?=fk_link('')?>';
</script>
<script type="text/javascript" src="<?php echo fk_theme_url()?>/js/menu.js"></script>
<script type="text/javascript" src="<?php echo fk_theme_url()?>/js/common.js"></script>


</head>
<body id="body">
<div id="footerless">
<div id="container">
<div id="header" style="height:107px;">
  <div id="profile_info" style="background:none; margin-top:20px;">
    <div style="float:right; margin-left:20px;">
      <p> Bienvenido <strong><?=$_SESSION['nombre']?></strong>. <a href="<?=fk_link('login/ex_logout')?>">Cerrar Sesi&oacute;n</a> </p>
<? /*      <p class="last_login">Ultimo Login 06-09-2011</p> */?>
    </div>
  </div>
  <div id="logo">
    <h1><a href="/intactics/">Panel de Control</a></h1>
  </div>
  
</div>
<!-- end header -->
<div class="clear"></div>


<div id="top_menu">
<div id="droplinetabs1" class="droplinetabs">
<ul>
	<li>
		<a href="<?=fk_link('paquetes')?>" <?=$sec=='index'?'class="selected"':'';?> >Alrabanes</a>
	</li>
    <li>
		<a href="<?=fk_link('usuarios')?>" <?=$sec=='index'?'class="selected"':'';?> >Usuarios</a>
	</li>
</ul>
</div>
</div>
<div class="clear"></div>


<div class="main">