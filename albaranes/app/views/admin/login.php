<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link type="text/css" rel="stylesheet" href="<?= fk_theme_url().'/css/main.css'?>"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Albaranes | Login</title>
</head>
<body>
<div class="container" id="login" style="width:300px; margin:200px auto 0; border-top:none">

<div class="content">
<form method="post" action="<?=fk_link('login/ex_login/')?>" class="validate" style="margin-top:0px">
	<? if (@$msg != ''){?>
    <div class="msg_error fail_error ui-corner-all" style="display:block"><? echo @$msg; ?></div>
    <div class="clear"></div><? } ?>
	<input type="hidden" name="location" value="admin" class="input" />
<table width="100%">
	<tr>
    	<td><label for="Usuario" style="color:#000">Usuario</label></td>
        <td align="right"><input type="text" name="Usuario" id="Usuario" class="input ui-widget-content ui-corner-all" /></td>
	</tr>
	<tr>
    	<td><label for="Contrasena" style="color:#000">Contrase&ntilde;a</label></td>
        <td align="right"><input type="password" name="Contrasena" id="Contrasena" class="input ui-widget-content ui-corner-all" /></td>
	</tr>
</table>

<div class="clear"></div>
<input type="submit" value="Iniciar Sesi&oacute;n" id="btn_iniciarsesion" style="margin-top:25px;" class="right">
</form>
</div>
</div>
</body>
</html>
