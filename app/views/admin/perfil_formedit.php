<div class="section">
	<div class="margin">
	<h2 class="title"><?=$action?> Perfil</h2>
    <form action="<?=fk_link('perfiles/'.$url_action)?>" method="post" class="validate" >
    	<div class="msg_error fail_error ui-corner-all"></div>
		<div class="clear"></div>
    <? if(isset($perfil['id_perfil'])){ ?>
    	<input type="hidden" name="id_perfil" value="<?=$perfil['id_perfil']?>" class="input" />
    <? } ?>
    	<input type="hidden" name="location" value="perfiles/" class="input" />
    <table>
	<tr>
    	<td>Nombre</td>
        <td><input type="text" class="input ui-widget-content ui-corner-all" name="nombre_perfil" value="<?=@$perfil['nombre_perfil']?>" /></td>
	</tr>
	<tr>	
    	<td></td><td><input type="submit" class="standar-btn ui-corner-all" value="Guardar" /></td>
	</tr>
    </table>
    </form>
    <div class="clear"></div>
    </div>
</div>