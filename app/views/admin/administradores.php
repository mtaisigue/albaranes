<?
foreach($pefiles as $p){
	$perf[$p['id_perfil']] = $p['nombre_perfil'];
}
?>
<div class="section">
	<div class="margin">
	<h2 class="title">Usuarios</h2>
    <table class="datatable" width="100%">
    	<thead>
        	<tr>
            	<th>Nombre</th>
                <th>Apellidos</th>
                <th>Correo Electrónico</th>
                <th>Tipo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        	<? foreach($DataUsuarios as $user){ ?>
        	<tr>
                <td><?=$user['nombre']?></td>
                <td><?=$user['apellidos']?></td>
                <td><?=$user['email']?></td>
                <td><?=$user['id_tipo']==1?'Usuario':'Administrador'?></td>
                <td class="acciones">
                   	<a href="<?=fk_link('usuarios/editusuario/'.$user['id_usuario'])?>">
                    <img src="<?=fk_theme_url()?>/img/edit.png">
                    </a>
	
                    <a href="<?=fk_link('usuarios/ex_deleteusuario/'.$user['id_usuario'])?>">
                    <img src="<?=fk_theme_url()?>/img/cancel.png">
                    </a>

				</td>
			</tr>
            <? } ?>
        </tbody>
    </table>
    <a href="<?=fk_link('usuarios/nuevousuario/')?>" class="standar-btn ui-corner-all">Nuevo Administrador</a>
    <div class="clear"></div>
    </div>
</div>