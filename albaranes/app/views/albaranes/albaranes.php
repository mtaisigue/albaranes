<div class="section">
	<div class="margin">
        <h2 class="title">Albaranes</h2>


  <form action="<?= fk_link("paquetes/index/")?>" method="post">
  <h3 style="font-size:14px; padding:0;">Busqueda</h3>
	<div class="msg_error fail_error ui-corner-all left"></div>
		<div class="clear"></div>
        
  <table style="margin:10px 0">
    <tr>
      <td style="">ID</td>
      <td><input type="text" value="" name="id_albaranes" class="input"/></td>
	</tr>
      <? if($_SESSION['id_tipo'] == 2){ ?>
	<tr>
      <td style="">Usuarios</td>
      <td><select name="id_usuario" class="input">
	      <option value=""></option>
      	<? foreach($usuarios as $u){ ?>
      	<option value="<?=$u['id_usuario']?>"><?=$u['nombre']?> <?=$u['apellidos']?></option>
        <? } ?>
      </select></td>
	</tr>
      <? } ?>
	<tr>
      <td><input type="submit" value="Buscar" class="save brad"/></td>
      <td></td>
    </tr>
  </table>
  </form>
  
  
  <table class="justdata" width="100%">
	<thead>
    	<tr>
        	<th>ID</th>
            <? if($_SESSION['id_tipo'] == 2){ ?>
            <th>Usuario</th>
            <? } ?>
            <th>Remitente</th>
            <th>Beneficiario</th>
            <th>Total</th>
            <th></th>
            </tr>
    </thead>
    <tbody>
<?
foreach($albaranes as $valor){
?>
<tr>
		<td>
			<?= $valor[id_albaranes]?>        
        </td>
        <? if($_SESSION['id_tipo'] == 2){ ?>
        	<td>
      		<?= $valor[usuario]?>     
	        </td>
        <? } ?>
        <td>
      		<?= $valor[remitente]?>     
        </td>
        <td>
        	<?= $valor[beneficiario]?>     
        </td>
        <td>
        	<?= $valor[total]?>     
        </td>
        <td>
        	<a href="<?= fk_link("paquetes/detalle_albaranes/".$valor["id_albaranes"]) ?>"><img src="<?=fk_theme_url()?>/img/ico_page.png" /></a>     
        
        	<a href="<?= fk_link("paquetes/eliminar_albaranes/".$valor["id_albaranes"]) ?>" style="margin-left:25px"><img src="<?=fk_theme_url()?>/img/cancel.png" /></a> 
       
        	<a href="<?= fk_link("paquetes/imprimir_albaranes/".$valor["id_albaranes"]) ?>" class="imprimirBTN" style="margin-left:25px"><img src="<?=fk_theme_url()?>/img/printButton.png" /></a>  
        </td>
	</tr>
<?
}
?>
	</tbody>
</table>
<a href="<?= fk_link("paquetes/nuevo_registro/") ?>" class="brad save" style="float:left; margin-top:15px">Nuevo Registro</a>
<div style="float:right">
<?
if (!isset($_POST["id_albaranes"]) or trim($_POST["id_albaranes"]) == ''){
	echo setPaginacion(fk_link('paquetes/index'),$pagactual, 5, $pag, $showone = 1);
	}
?>
<script type="text/javascript">
 $(function(){
	 $('.imprimirBTN').printPage();
	})
</script>
</div>
<div class="clear"></div>
</div>
</div>
