// JavaScript Document
var filteringon = 0;


$(function(){

//	$('.datatablemod').before('<input type="text" class="searchinput">')
    
	var oTable =  $('.datatablemod').dataTable( {
    	"sDom": 'R<"H"lfr>t<"F"ip<',
		"bJQueryUI": true,
//		"sPaginationType": "full_numbers",
		"oLanguage": {
			"sLengthMenu": "Mostrar _MENU_ registros por pagina",
			"sZeroRecords": "No se han encontrado registros",
			"sInfo": "Mostrando _START_ al _END_ de _TOTAL_ registros",
			"sInfoEmpty": "0 registros",
			"sInfoFiltered": "(filtrados de _MAX_ registros en total)",
			"sSearch" : "Buscar:"
		},
    	<? if (isset($_GET['ajaxsource'])){ ?>
		"bProcessing": true,
		"sAjaxSource": "<?=$_GET['ajaxsource']?>"
        <? } ?>
	} );
        
		<? if($_GET['multicolumn_search'] ==1 ){ ?>
        select= '<select id="filteronfield">';
        for(x=0; x<$('.DataTables_sort_wrapper').length ;x++){
        	select += '<option value="'+x+'">'+$('.DataTables_sort_wrapper:eq('+x+')').text()+'</option>';
		}
        select += '</select>';
        $(".dataTables_filter input").after(select);
        
        $('#filteronfield').change(function(){
	        oTable.fnFilter( '', filteringon);
        	filteringon = $('#filteronfield').val();
            oTable.fnFilter( $(".dataTables_filter input").val() , filteringon);
        });
        
        $(".dataTables_filter input").unbind('keyup');
        
       $(".dataTables_filter input").keyup( function () {
        //$(".searchinput").keyup( function () {
			// Filter on the column (the index) of this element 
			oTable.fnFilter( this.value, filteringon);
		} );
        <? } ?>
        
} );