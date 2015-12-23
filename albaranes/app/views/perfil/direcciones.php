<?
$themeurl = str_replace('../','',fk_theme_url());
$themeurl = $root.$themeurl;
?>
<h3>Direcciones</h3>
<? if((empty($dir) || !is_array($dir)) && $tipo_cuenta == 1){?>
	<p>No has ingresado ninguna direcci&oacute;n. Ingresa tus direcciones, para realizar busquedas de locales de acuerdo a la ubicacion en la que te encuentras</p>
<? } ?>
<? if (is_array($dir)) foreach($dir as $D){?>
    <div style="float:left; margin-right:20px; border-right:1px solid #ccc; padding-right:20px">
        <label><?=$D['nombre_direccion']?></label><br/>
        <label><?=$D['calle'].', '.$D['colonia']?></label><br/>
        <label><?=$D['estado'].', '.$D['pais']?></label><br/><br/>
        
        <div style="float:right">
        	 <a href="<?=$root?>miperfil/editDireccion/<?= $D['id_direccion']?>" class="edit_dir"><img src="<?=$themeurl?>/img/Text-Edit.png" alt="" title="Editar"/></a>
            <a href="<?=$root?>miperfil/eliminardireccion/<?=$D['id_direccion']?>" class="delete_dir"><img src="<?=$themeurl?>/img/eliminar.png" alt="" title="Eliminar"/></a>
        </div>
        
    </div>
<? }?>
<? if($_SESSION['proceso'] == 'perfil'){?>
<div class="clear"></div>
<a id="btn_nuevadireccion" href=""></a>
<? } ?>
<div class="clear"></div>