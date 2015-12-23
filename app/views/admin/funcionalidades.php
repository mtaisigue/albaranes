<div class="section">
	<div class="margin">
	<h2 class="title">Funcionalidades</h2>

	<div style="width:700px;">
    <table class="datatable" width="100%">
    	<thead>
        	<tr>
            	<th>Nombre</th>
            </tr>
        </thead>
        <tbody>
        	<? foreach($campos_func as $result){ ?>
        	<tr>
                <td><?=$result['privilege_desc']?></td>
			</tr>
            <? } ?>
        </tbody>
    </table>
    </div>
   
    <div class="clear"></div>
    </div>
</div>