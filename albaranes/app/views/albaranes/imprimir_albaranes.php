<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>
<body>

<div class="section">
	<div class="margin">
        <h2 class="title">Albaranes</h2>
        
        <div class="msg_error fail_error ui-corner-all"></div>
		<div class="clear"></div>

<? $valor = $detalle_albaranes; ?>

<div style="width:500px;">
<table class="justdata" width="100%">
	<thead>
    	<tr>
        	
        </tr>
    </thead>
    <tbody>
<tr>
		<td>
          	ID Albaranes     
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
      		<?= $valor[fecha]?>    
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
      		<?= $valor[pais]?>     
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
			Ciudad    
        </td>
        <td>
      		<?= $valor[ciudad]?>     
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
      		<?= $valor[peso]?>     
        </td>
        
</tr>
<tr>
		<td>
			Comisión    
        </td>
        <td>
      		<?= $valor[comision]?>     
        </td>
        
</tr>
<tr>
		<td>
			seguro    
        </td>
        <td>
      		<?= $valor[seguro]?>     
        </td>
        
</tr>
<tr>
		<td>
			IV    
        </td>
        <td>
      		<?= $valor[iv]?>     
        </td>
        
</tr>
<tr>
		<td>
			Total    
        </td>
        <td>
      		<?= $valor[total]?>     
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

</body>
</html>