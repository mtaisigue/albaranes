<div class="section">
	<div class="margin">
        <h2 class="title">Detalle Albaranes</h2>
        
        <div class="msg_error fail_error ui-corner-all"></div>
		<div class="clear"></div>

<? $valor = $detalle_albaranes; ?>

<a href="<?= fk_link("paquetes/index/") ?>" style="float:right; margin-bottom:10px">Regresar</a>     

<div style="width:500px;">
<table class="justdata" width="100%">
	<thead>
    	<tr>
        	<th>Campo</th>
            <th>Valor</th>
        </tr>
    </thead>
    <tbody>
<tr>
		<td>
          	ID
        </td>
        <td>
      		<?= $valor[id_albaranes]?>    
        </td>
        
</tr>
<tr>
		<td>
			Fecha
        </td>
        <td>
      		<?= date('d/m/Y', $valor[fecha]); ?>
        </td>
        
</tr>
<tr>
		<td>
			Operacion     
        </td>
        <td>
      		<?= $valor[operacion]?>    
        </td>
        
</tr>
<tr>
		<td>
			Guía       
        </td>
        <td>
      		<?= $valor[guia]?>      
        </td>
        
</tr>
<tr>
		<td>
			Remitente
        </td>
        <td>
      		<?= $valor[remitente]?>     
        </td>
        
</tr>
<tr>
		<td>
			Beneficiario    
        </td>
        <td>
      		<?= $valor[beneficiario]?>     
        </td>
        
</tr>
<tr>
		<td>
			Documento    
        </td>
        <td>
      		<?= $valor[documento]?>     
        </td>
        
</tr>
<tr>
		<td>
			País        
        </td>
        <td>
      		<?= $valor[p_nombre]?>     
        </td>
        
</tr>
<tr>
		<td>
			Estado    
        </td>
        <td>
      		<?= $valor[e_nombre]?>     
        </td>
        
</tr>
<tr>
		<td>
			Dirección        
        </td>
        <td>
      		<?= $valor[direccion]?>     
        </td>
        
</tr>
<tr>
		<td>
			Teléfono    
        </td>
        <td>
      		<?= $valor[telefono]?>     
        </td>
        
</tr>
<tr>
		<td>
			Descripción
        </td>
        <td>
      		<?= $valor[descripcion]?>     
        </td>
        
</tr>
<tr>
		<td>
			Peso
        </td>
        <td>
      		<?= $valor[peso]==0?'':$valor[peso]?>     
        </td>
        
</tr>
<tr>
		<td>
			Comisión    
        </td>
        <td>
      		<?= $valor[comision]==0?'':$valor[comision]?>     
        </td>
        
</tr>
<tr>
		<td>
			Seguro    
        </td>
        <td>
      		<?= $valor[seguro]==0?'':$valor[seguro]?>     
        </td>
        
</tr>
<tr>
		<td>
			IV    
        </td>
        <td>
      		<?= $valor[iv]==0?'':$valor[iv]?>     
        </td>
        
</tr>
<tr>
		<td>
			Total    
        </td>
        <td>
      		<?= $valor[total]==0?'':$valor[total]?>     
        </td>
        
</tr>
<tr>
		<td>
			Dirección de Agencia    
        </td>
        <td>
      		<?= $valor[direccion_agencia]?>     
        </td>
        
</tr>

	</tbody>
</table>
<div class="clear"></div>
</div>
</div>
</div>