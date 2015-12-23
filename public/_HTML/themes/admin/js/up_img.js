// JavaScript Document
$(document).ready(function() {
	var id_usuario = $('#id_usuario').val();
	var id_anuncio = $('#id_anuncio').val();
	
	$('#file_upload').uploadify({
		'uploader'  : theme_url+'/js/uploadify2.swf',
		'script'    : HTTP+'admin/subir_imagen/'+id_usuario+'/',
		'cancelImg' : theme_url+'/img/cancel2.png',
		'folder'    : HTTP+'/public/_HTML/data/anuncios/',
		'auto'      : true,
		'onComplete' : function(event,ID,fileObj,response,data) {
/*			$('.msg_error').html(response);
			$('.msg_error').fadeIn('fast');*/
      	}
	});
});