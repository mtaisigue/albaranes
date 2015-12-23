// JavaScript Document
$(function(){
	cargar_ciudad();
	$('#cmb_pais').change(cargar_ciudad);
});

function cargar_ciudad(){
	id = $('#cmb_pais').val();
		ruta = root+'paquetes/combo_ciudad/'+id;
		
		$.ajax({
			url  : ruta,
			success : function(msg){
				$('#cmb_ciudad').replaceWith(msg);
			}
		});

	
}