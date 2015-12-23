// JavaScript Document
var filteringon = 0;

$(function(){
	$( ".datepicker" ).datepicker();
	setDataTables();
	setValidateF();
	setAjax_Command();
	init_menu();
})

function init_menu(){
	$('.prevent').click(function(event){ event.preventDefault(); });
	
	$('#menu_center>ul>li').hover(
		function(){
			$('ul', this).css('display','block');
		},
		function(){
			$('ul', this).css('display','none');
		}
	);
}

/* Cambio de Lenguaje para el Datepicker */
jQuery(function($){
	$.datepicker.regional['es'] = {clearText: 'Eliminar', clearStatus: '',
		closeText: 'Cerrar', closeStatus: 'Cerrar Estado',
		prevText: '<Ant', prevStatus: 'Anterior',
		nextText: 'Sig>', nextStatus: 'Siguiente',
		currentText: 'Actual', currentStatus: 'Estado Actual',
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
		'Jul','Ago','Sep','Oct','Nov','Dic'],
		monthStatus: 'Estado del mes', yearStatus: 'Estado del A&ntilde;o',
		weekHeader: 'Sm', weekStatus: '',
		dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
		dayNamesShort: ['Dom','Lun','Mar','Mir','Jue','Vie','Sab'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
		dayStatus: 'Utilizar DD como primer dia de la semana', dateStatus: 'Elegir DD, MM d',
		dateFormat: 'dd/mm/yy', firstDay: 1, 
		initStatus: 'Escoger la fecha', isRTL: false};
	$.datepicker.setDefaults($.datepicker.regional['es']);
});


/*  Tipos de Tablas por Default */
function setDataTables(){
	var dataTable = $('.datatable').dataTable( {
		"sDom": 'R<"H"lfr>t<"F"ip<',
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"oLanguage": {
			"sLengthMenu": "Mostrar _MENU_ registros por pagina",
			"sZeroRecords": "No se han encontrado registros",
			"sInfo": "Mostrando _START_ al _END_ de _TOTAL_ registros",
			"sInfoEmpty": "0 registros",
			"sInfoFiltered": "(filtrados de _MAX_ registros en total)",
			"sSearch" : "Buscar:"
		}
	} );
	
	var justTable = $('.justdata').dataTable( {
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

//	$('.datatablemod').before('<input type="text" class="searchinput">')
    
/*	var oTable =  $('.datatablemod').dataTable( {
    	"sDom": 'R<"H"lfr>t<"F"ip<',
		"bJQueryUI": true,
		"oLanguage": {
			"sLengthMenu": "Mostrar _MENU_ registros por pagina",
			"sZeroRecords": "No se han encontrado registros",
			"sInfo": "Mostrando _START_ al _END_ de _TOTAL_ registros",
			"sInfoEmpty": "0 registros",
			"sInfoFiltered": "(filtrados de _MAX_ registros en total)",
			"sSearch" : "Buscar:"
		}
	} );
       
	selectin = '<select id="filteronfield">';
        for(x=0; x<$('.DataTables_sort_wrapper').length ;x++){
        	selectin += '<option value="'+x+'">'+$('.DataTables_sort_wrapper:eq('+x+')').text()+'</option>';
		}
        selectin += '</select>';
        $(".dataTables_filter input").after(selectin);
        
        $('#filteronfield').change(function(){
	        oTable.fnFilter( '', filteringon);
        	filteringon = $('#filteronfield').val();
            oTable.fnFilter( $(".dataTables_filter input").val() , filteringon);
        });
        
        $(".dataTables_filter input").unbind('keyup');
        
       $(".dataTables_filter input").keyup( function () {
			oTable.fnFilter( this.value, filteringon);
		} );*/
        
	
}

/* Funcion para ejecutar la validacion del formulario por ajax */
var cform;
function setValidateF(){
	$('form.validate').submit(function(event){
		if($(cform).attr('ajax') != 'done'){
			event.preventDefault();
			cform = $(this);		

			$('.msg_error', cform).css('display','none');
			var post = 'ajax=1';
			var action = '';
			
			for(x=0; x < $('.input', this).length; x++){
				$('.input:eq('+x+')', this).css('border-color','');
				$('.input:eq('+x+')', this).css('background','');
				
				if($('.input:eq('+x+')', this).hasClass('checkbx')){
					if($('.input:eq('+x+')', this).is(':checked')){
						post += '&';
						post += $('.input:eq('+x+')', this).attr('name') + '=' + $('.input:eq('+x+')', this).val();
					}
				}else{
					if($('.input:eq('+x+')', this).hasClass('required') && $('.input:eq('+x+')', this).val() == ''){
						$('.input:eq('+x+')', this).css('border-color','#F00');
						$('.input:eq('+x+')', this).css('background','#ffe1e1');
					}else{
						if(post != '')post += '&';
						post += $('.input:eq('+x+')', this).attr('name') + '=' + $('.input:eq('+x+')', this).val();
					}
				}
			}
			action = $(cform).attr('action');
			$.ajax({
				type: "POST",
				url: action,
				async: false,
				data: post,
				success: function(msg){
					mess = msg;
					if(msg != ''){
						ShowAlert(msg, cform);
						prevent = 1;
					}else if($('input[name="location"]', cform).length < 1){
						$('#btn_siguiente').css('display','block');
						prevent = 1;
						if(typeof afterSubmit == 'function') { 
							afterSubmit(); 
						}
					}else{
						prevent = 0;
						$('.msg_error', cform).css('display','none');
						htt = ( $('input[name="location"]', cform).val() );

						$(cform).attr('action', htt);
						$(cform).attr('ajax','done');
						$(cform).submit();
					}
				}
			});
			
		}
	});
}

function ShowAlert(msg, cform){
	$('.msg_error', cform).css('display','none');
	$('.msg_error', cform).html(msg);
	$("html,body").animate({
        scrollTop: '240px'
		}, 'fast', function(){
        $('.msg_error', cform).fadeIn('slow');
	});
}

function setAjax_Command(){
	$('.ajax_command').click(function(event){
		var action = $(this).attr('href');
		var post = $(this).attr('rel');
		$.ajax({
			type: "POST",
			url: action,
			async: false,
			data: post,
			success: function(msg){
				if(msg != ''){
					ShowAlert(msg);
					prevent = 1;
				}else{
					$('.msg_error').css('display','none');
				}
			}
 		});
		if(prevent)
			event.preventDefault();
	});
}