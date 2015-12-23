	<table class="justtable" width="100%">
    	<thead>
        	<tr>
            	<th><?=$usuario['nombre'].' '.$usuario['apellidos']?></th>
                <th>Funcionalidades</th>
            </tr>
        </thead>
        <tbody>
	        
        	<? 
			$x= 0;
			foreach($privs as $p){ 
				if($p['privilege_desc'] == ''){
					echo '<tr><td>&ensp;</td><td>&ensp;</td></tr>';
				}else{
			?>
        	<tr>
                <td><?=$p['privilege_desc']?></td>
                <td>
                <?
                if($p['is_group_parent'] == 1){
					$habilitado = strpos($p['access'],'r') !== false;
				?>
                	<a href="" class="habilitar" rel="group<?=$p['group']?>" style=" <?=$habilitado?'display:none':''?>">Habilitar</a>
                    <a href="" class="deshabilitar" rel="group<?=$p['group']?>" style=" <?=(!$habilitado)?'display:none':''?>">Deshabilitar</a>
                <? } ?>
                <div class="group<?=$p['group']?>" <?=$p['is_group_parent'] == 1?'style="display:none"':''?>>
                <?
				$c = strpos($p['access'],'c') !== false?'checked':'';
                $r = strpos($p['access'],'r') !== false?'checked':'';
				$u = strpos($p['access'],'u') !== false?'checked':'';
				$d = strpos($p['access'],'d') !== false?'checked':'';
				$e = strpos($p['access'],'e') !== false?'checked':'';
				?>
                <? if(strpos($p['exceptions'],'r') === false){?>
                <? if($p['privilege_desc'] == 'Sistema - Arrow'){ ?>Acceso<? }else{?>Ver<? } ?> <input type="checkbox" class="checkbox" rel="<?=$p['id_priv']?>" <?=$r?> value="r" /> &ensp;&ensp; &ensp;
                <? } ?>
                <? if(strpos($p['exceptions'],'c') === false){?>
                Agregar <input type="checkbox" class="checkbox" rel="<?=$p['id_priv']?>" <?=$c?>  value="c" /> &ensp;&ensp; &ensp;
				<? } ?>
                <? if(strpos($p['exceptions'],'u') === false){?>
                Editar <input type="checkbox" class="checkbox" rel="<?=$p['id_priv']?>" <?=$u?>  value="u" /> &ensp;&ensp; &ensp;
                <? } ?>
                <? if(strpos($p['exceptions'],'d') === false){?>
                Borrar <input type="checkbox" class="checkbox" rel="<?=$p['id_priv']?>" <?=$d?>  value="d" />&ensp;&ensp;&ensp;
                <? } ?>
                <? if(strpos($p['exceptions'],'e') === false){?>
                Enviar <input type="checkbox" class="checkbox" rel="<?=$p['id_priv']?>" <?=$e?>  value="e" />
                <? } ?>
                </div></td>
			</tr>
            <?
				}
			} ?>
        </tbody>
    </table>
    <div class="right" style="margin-top:10px; display:none;" id="loadingfuncs"></div>
