<div class="container" style="margin-top:30px;">
<h1>Mi Perfil</h1>

<div id="usprofile_data">

<h3 class="left">Datos del Usuario</h3>
<a href="<?=fk_link('miperfil/editarusuario/'.$_SESSION['id_usuario'])?>" id="edit_user">
<img src="<?=fk_theme_url()?>/img/Text-Edit.png" alt="" title="Editar" class="left" style="margin-left:10px" />
</a>

<div class="clear"></div>

<form action="<?=fk_link('miperfil/editUser/')?>" method="post" class="validate">
<div class="msg_error fail_error ui-corner-all"></div>
<div class="clear"></div>
	<input type="hidden" name="location" value="miperfil/" class="input clear">
<table style="margin-bottom:15px">
<? if (is_array($user)) foreach($user as $U){?>
	<tr>
    	<td style="text-align:right">Nombre</td><td style="padding-left:10px" class="cont_input"><?=$U['nombre']?></td>
    </tr>
    <tr>
    	<td style="text-align:right">Apellidos</td><td style="padding-left:10px" class="cont_input"><?=$U['apellidos']?></td>
    </tr>
    <tr>
    	<td style="text-align:right">Email</td><td style="padding-left:10px" class="cont_input"><?=$U['email']?></td>
    </tr>
     <tr>
    	<td style="text-align:right">Contrase&ntilde;a<div id="conf_pass" style="display:none; margin-top:10px">Confirmar Contrase&ntilde;a</div></td><td style="padding-left:10px" id="cont_pass">
			 <?
             for ($i=0;$i<=15; $i++){
                $c .= '&bull;';
             }
             echo $c;
             ?>
        </td>
    </tr>
<? }?>
<tr>
    	<td style="text-align:right"></td><td style="padding-left:10px;" id="sub"></td>
    </tr>
</table>
</form>
</div>

<? /*<h3>Direcciones</h3>
<? if (is_array($dir)) foreach($dir as $D){?>
    <div style="float:left; margin-right:20px; border-right:1px solid #ccc; padding-right:20px">
        <label><?=$D['nombre_direccion']?></label><br/>
        <label><?=$D['calle'].', '.$D['colonia']?></label><br/>
        <label><?=$D['estado'].', '.$D['pais']?></label><br/><br/>
        
        <div style="float:right">
        	 <a href="<?=fk_link('miperfil/editardireccion/'.$D['id_direccion'])?>"><img src="<?=fk_theme_url()?>/img/Text-Edit.png" alt="" title="Editar"/></a>
            <a href="<?=fk_link('miperfil/eliminardireccion/'.$D['id_direccion'])?>"><img src="<?=fk_theme_url()?>/img/eliminar.png" alt="" title="Eliminar"/></a>
        </div>
        
    </div>
<? }?>
</div>
*/?>