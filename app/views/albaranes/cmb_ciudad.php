<select name="ciudad" class="input" id="cmb_ciudad">
	<? if($id_pais == '0'){ ?>
    	<option value="0">Seleccionar Pais</option>
    <? }else{ ?>
        <option value="0">...</option>
        <? foreach($combo as $campo){ ?>
            <option value="<?=$campo['id_estado']?>"><?=$campo['estado']?></option>
        <? } ?>
    <? }?>
</select>