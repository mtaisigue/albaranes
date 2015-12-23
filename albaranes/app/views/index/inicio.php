<div class="container">
	<a href="<?=fk_link('')?>" id="mainlogo"></a>
    <form id="mainform" action="<?=fk_link('buscar')?>" method="post" />
			<div class="msg_error fail_error ui-corner-all"></div>
            <div class="clear"></div>
            <input type="hidden" name="location" class="input" value="/" />
        <input type="text" name="busqueda" id="mainsearch" class="left input ui-widget-content ui-corner-all" /><input type="submit" value="" class="left" id="btn_search" /><br class="clear"/>
        
        <div id="ubicacioncont"><label for="ubicacion">Ubicaci&oacute;n</label><input type="text" name="ubicacion" class="input ui-widget-content ui-corner-all" id="ubicacion" /></div>
        <div class="clear"></div>
        <? /*<a href="#" id="start-page">Haz de Easyfinde tu P&aacute;gina de Inicio</a>*/?>
	</form>
</div>
<div id="redcontainer">
	<div class="container">
    	<div class="col first" style="width:530px;">
    		<h2>&iquest;Qué es EasyFinde?</h2>
           	<p>EasyFinde es un directorio de negocios de queretaro, el cual le permite encontrar los negocios mas cercanos a su ubicacion, ya sea desde la comodidad de su casa, su trabajo o desde su telefono celular.</p>
            <div id="main-video">
            	<a href="" id="video-start">
                </a>
                <iframe width="480" height="340" id="videoframe" src="http://www.youtube.com/embed/gTA-5HM8Zhs?rel=0" frameborder="0" allowfullscreen></iframe>
            </div>
            <a href="registro/negocio/paso1" id="btn_reg_negocio"></a>
        </div>
        <div class="col" style="width:370px;">
        <? if (!isset($_SESSION['id_usuario']) and $_SESSION['id_usuario'] == 0 ){ ?>
    		<h2>Registrate ya!</h2>
            <form id="home-register" method="post" action="<?= fk_link('registro/ex_usuario1/'); ?>" class="validate" >
            <div class="msg_error fail_error ui-corner-all"></div>
            <div class="clear"></div>
            <input type="hidden" name="location" value="registro/conf_registro_ex" class="input clear">
            	<table>
                	<tr>
                    	<td class="textright">Nombre</td><td><input name="nombre" class="input ui-widget-content ui-corner-all required" /></td>
					</tr>
                    <tr>
                    	<td class="textright">Apellidos</td><td><input name="apellidos" class="input ui-widget-content ui-corner-all required" /></td>
					</tr>
                    <tr>
                    	<td class="textright">Usuario</td><td><input type="text" name="usuario" class="input ui-widget-content ui-corner-all required" /></td>
					</tr>
                    <tr>
                    	<td class="textright">Correo Electr&oacute;nico</td><td><input name="email" class="input ui-widget-content ui-corner-all required" /></td>
					</tr>
                    <tr>
                    	<td class="textright">Contrase&ntilde;a</td><td><input type="password" name="contrasena" class="input ui-widget-content ui-corner-all required" /></td>
					</tr>
                    <tr>
                    	<td class="textright">Confirmar Contrase&ntilde;a</td><td><input type="password" name="conf-contrasena" class="input ui-widget-content ui-corner-all required" /></td>
					</tr>
                    <tr>
                    	<td colspan="2">
                        	Al hacer click en registrame aceptas los <a href="<?=fk_link('terminos');?>">T&eacute;rminos y Condiciones</a>
                        </td>
					</tr>
                    <tr>
                    	<td colspan="2">
                        	<input type="submit" value="" id="btn_registrate" />
                        </td>
					</tr>
				</table>
			</form>                
<? }?>
        </div>
        <div class="clear"></div>
    </div>
</div>