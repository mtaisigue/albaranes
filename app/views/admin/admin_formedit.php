<div class="section">
	<div class="margin">
	<h2 class="title"><?=$action?> Usuario</h2>
    <form action="<?=fk_link('usuarios/'.$url_action)?>" method="post" class="validate" >
    	<div class="msg_error fail_error ui-corner-all"></div>
        <div class="clear"></div>
    <? if(isset($user['id_usuario'])){ ?>
    	<input type="hidden" name="id_usuario" value="<?=$user['id_usuario']?>" class="input" />
    <? } ?>
    	<input type="hidden" name="location" value="<?=fk_link('usuarios/')?>" class="input" />
    <table>
	<tr>
    	<td>Nombre</td>
        <td><input type="text" class="input ui-widget-content ui-corner-all" name="nombre" value="<?=@$user['nombre']?>" /></td>
	</tr>
	<tr>
        <td>Apellidos</td>
        <td><input type="text" class="input ui-widget-content ui-corner-all" name="apellidos" value="<?=@$user['apellidos']?>"  /></td>
	</tr>
	<tr>
		<td>Correo Electrónico</td>
        <td><input type="text" class="input ui-widget-content ui-corner-all" name="email" value="<?=@$user['email']?>"  /></td>
	</tr>
	<tr>
		<td>Contrase&ntilde;a</td>
        <td><input type="password" class="input ui-widget-content ui-corner-all" name="password"  /></td>
	</tr>
    <tr>
		<td>Confirmar Contrase&ntilde;a</td>
        <td><input type="password" class="input ui-widget-content ui-corner-all" name="confirm-password"  /></td>
	</tr>
	<tr>
		<td>Tipo</td>
        <td><select type="text" class="input ui-widget-content ui-corner-all" name="id_tipo">
        	<option value="1" <?=@$user['id_tipo']==1?'selected="selected"':''?> >Usuario</option>
            <option value="2" <?=@$user['id_tipo']==2?'selected="selected"':''?> >Administrador</option>
        </select></td>
	</tr>
	<tr>	
    	<td></td><td><input type="submit" class="standar-btn ui-corner-all" value="Guardar" /></td>
	</tr>
    </table>
    
    <input name="id_perfil" class="input ui-widget-content ui-corner-all" type="hidden" value="1" />
        
    </form>
    <div class="clear"></div>
    </div>
</div>