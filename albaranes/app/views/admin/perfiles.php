<div class="section">
	<div class="margin">
        <h2 class="title">Perfiles</h2>
        <div id="perfiles" style="width:200px;" class="left">
        <div class="msg_error fail_error ui-corner-all"></div>
		<div class="clear"></div>
        <table class="justdata" width="100%">
    	<thead>
        	<tr>
            	<th>Nombre</th>
            </tr>
        </thead>
        <tbody>
        	<? foreach($DataUsers as $user){ ?>
        	<tr id="<?=$user['id_usuario']?>">
                <td><?=$user['usuario']?></td>
			</tr>
            <? } ?>
        </tbody>
	    </table>
	    </div>
        
        <div id="privilegios" class="left" style=" width:600px; margin-left:10px;">
        	
        </div>
        
        <div class="clear"></div>
    </div>
</div>