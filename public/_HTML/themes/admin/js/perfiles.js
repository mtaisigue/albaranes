// JavaScript Document
var cPerfil = 0;


$(function(){
	setSelectableRows();
	setShowPrivillegios();
});

function setSelectableRows(){
	$('#perfiles tr').hover(
		function () {
			$(this).addClass("hover");
		},
		function () {
			$(this).removeClass("hover");
		}
	)
}

function setShowPrivillegios(){
	$('#perfiles tbody tr').click(function(){
		id = $(this).attr('id');
		cPerfil = id;
		$.ajax({
			url: HTTP+"perfiles/getprivilegios/",
			type: "post",
			data: "id="+id,
			success: function(html){
				$("#privilegios").html(html);
				setSavePrivilegios();
				
				$('#privilegios .justtable').dataTable( {
					"sDom": 'R<"H"lfr>t<"F"ip<',
					"bJQueryUI": true,
					"bPaginate": false,
					"bInfo" : false,
					"bFilter" : false,
					"bSort" : false,
					"oLanguage": {
						"sLengthMenu": "Mostrar _MENU_ registros por pagina",
						"sZeroRecords": "No se han encontrado registros",
						"sInfo": "Mostrando _START_ al _END_ de _TOTAL_ registros",
						"sInfoEmpty": "0 registros",
						"sInfoFiltered": "(filtrados de _MAX_ registros en total)"
					}
				} );
				
				
			}
		});
		

	});
}

function setSavePrivilegios(){
	$('#privilegios .checkbox').click(function(event){
		$('#loadingfuncs').html('<img src="'+theme_url+'/img/loading1.gif" class="left" />&ensp;Guardando...');
		$('#loadingfuncs').fadeIn('slow');
		id = $(this).attr('rel');
		
		var newval = 0;
		if($(this).is(':checked')){
			newval = $(this).val();
			if($(this).val() == 2)
				$('.checkbox[rel="'+id+'"][value="1"]').attr('checked','checked');
		}else{
			if($(this).val() == 2){
				if($('.checkbox[rel="'+id+'"][value="1"]').is(':checked'))
					newval = 1;
			}else{
				$('.checkbox[rel="'+id+'"][value="2"]').removeAttr("checked")
			}
		}
		
		$.ajax({
			url: HTTP+"perfiles/setprivilegios/",
			type: "post",
			data: "perfil="+cPerfil+"&priv="+id+"&val="+newval,
			success: function(html){
				$('#loadingfuncs').fadeOut('slow');
			}
		});
	});
}