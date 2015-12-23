<div class="section">
	<div class="margin">
        <h2 class="title">Albaranes</h2>
        
<form method= "POST" action="<?= fk_link("paquetes/guardar_albaranes/")?>" class="validate">
<input type="hidden" name="location" value="<?= fk_link("paquetes/index/")?>" class="input">
<div class="msg_error fail_error ui-corner-all left"></div>
<div class="clear"></div>
<a href="<?= fk_link("paquetes/index/") ?>" style="float:right; margin-bottom:10px">Regresar</a>  
<table style="float:left">
    <tr>
    	<td>Fecha</td>
        <td><input type="text" value="" name="fecha" class="input datepicker" /></td>
    </tr>        
    <tr>
         <td>Operación</td>
         <td><input type="text" value="" name="operacion" class="input" /></td>
    </tr>
    <tr>    
    	<td>Guía</td>
		<td><input type="text" value="" name="guia" class="input" /></td>
	</tr>
    <tr>    
    	<td>Remitente</td>
		<td><input type="text" value="" name="remitente" class="input" /></td>
	</tr>
    <tr>    
    	<td>Beneficiario</td>
		<td><input type="text" value="" name="beneficiario" class="input" /></td>
	</tr>
     <tr>    
    	<td>Documento</td>
		<td><input type="text" value="" name="documento" class="input" /></td>
	</tr>
    <tr>
       	<td>País</td>
        <td><select name="pais" class="input" id="cmb_pais">
        	<option value="0">...</option>
            <? foreach($pais as $valor){ ?>
                <option value= <?= $valor[id_pais]?>><?= utf8_encode($valor[pais])?></option>
            <? } ?>
            </select>
         </td>
      </tr>
      <tr>
      	<td>Dirección</td>
        <td> <textarea name="direccion" class="input"></textarea></td>
      </tr>
       <tr>
       	<td>Ciudad</td>
        <td><select name="ciudad" class="input" id="cmb_ciudad">
              <option value="0">Seleccione Pais</option>
	    </select> 
         </td>  
      </tr>
</table>
<table style="float:left; margin-left:20px">
      <tr>
      	<td>Teléfono</td>
        <td><input type="text" value="" name="telefono" class="input"/></td>
      </tr>
      <tr>
      	<td>Descripción</td>
        <td><textarea name="descripcion" class="input"></textarea></td>
      </tr>
      <tr>
      	<td>Peso</td>
        <td><input type="text" value="" name="peso" class="input"/></td>
      </tr>
      <tr>
      	<td>Comisión</td>
        <td> <input type="text" value="" name="comision" class="input" /></td>
      </tr>
     <tr>
      	<td>Seguro</td>
        <td> <input type="text" value="" name="seguro" class="input" /></td>
      </tr>
      <tr>
      	<td>IV</td>
        <td> <input type="text" value="" name="iv" class="input" /></td>
      </tr>
      <tr>
      	<td>Total</td>
        <td> <input type="text" value="" name="total" class="input" /></td>
      </tr>
      <tr>
      	<td>Dirección de Agencia</td>
        <td> <textarea name="direccion_agencia" class="input"></textarea></td>
      </tr>
      <tr><td></td>
      	<td>   <input type="submit" value="Guardar" class="brad save"/></td>
      	
       </tr>
</table>
<div style="clear:both;"></div>

</form>

</div>
</div>
