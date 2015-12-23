// JavaScript Document
function fk_ajax_exec(xParams){
	/*
	 * xParams={pDiv:'', pUrl:'', pArgs:'', pUrlAfter:'', insertMode:''};
	 */
	var Loading  = '';
	   if(xParams.showLoading !=undefined){
		   if(xParams.showLoading == true){
			    Loading = '<div class="loading-img"><img src="'+HTTP+'_HTML/img/ajax-loader.gif" /></div>';	   
		   }
	   }

		
		
		if(xParams.insertMode==''){ if(xParams.showLoading == true){$('#'+xParams.pDiv).prepend(Loading);}	}
		if(xParams.insertMode=='top'){	$('#'+xParams.pDiv).prepend(Loading);}
		if(xParams.insertMode=='bottom'){	$('#'+xParams.pDiv).append(Loading);}
				
        var response = $.ajax({ type: "POST",   
                          url : HTTP+xParams.pUrl,   
                        async : false,
						 data : xParams.pArgs+'&ajax=1',
                      success : function(resp) {								

				            if(xParams.insertMode==''){
				            	var htmlStr  = $('#'+xParams.pDiv).html();
				            	if(htmlStr!=resp){
				            		$('#'+xParams.pDiv).html(resp);	
				            	}
							}else{
							   $('.loading-img').html(''); 
							}
				            if(xParams.insertMode=='top'){
								$('#'+xParams.pDiv).prepend(resp);								
							}							
							if(xParams.insertMode=='bottom'){
								$('#'+xParams.pDiv).append(resp); 
							}							
							
							if( xParams.pUrlAfter != '' ){ 
							   window.open(HTTP+xParams.pUrlAfter,'_self'); 
							}
		   
                     } // Success
        });	  // $.ajax
} // fk_ajax_exec

/* function fk_ajax_submit(p_div,p_url,p_form,p_url_after){ */
function fk_ajax_submit(xVars){
     
	 var formArgs = $("#"+xVars.pForm).serialize();
	
	 pVarsData = {pDiv:xVars.pDiv,
				  pUrl:xVars.pUrl,
				  pArgs:formArgs+'&'+xVars.pArgs,
				  pUrlAfter:xVars.pUrlAfter,
				  insertMode:xVars.insertMode,
				  showLoading:true}; 
	
     fk_ajax_exec(pVarsData);
	 
}

function fk_toggle(Objt){
	 $(Objt).slideToggle();
}

function oculta(IdObj){
	 $('#data_'+IdObj).slideToggle();
}

function hide(IdObj){
	var options = {};
	$("#"+IdObj).hide( "highlight", options, 500);
}
function fk_show(IdObj){
	var options = {};
	$("#"+IdObj).show( "highlight", options, 500);
}
// AppForm
function appForm_updfldTxt(data){
	//alert(data.id);
	$('#'+data.id).show();
	$('#'+data.id).focus();
	$('#val-'+data.id).hide();
	$('#'+data.id).blur(function(){
		cur_val = $('#cur-v-'+data.id).val();
		new_val = $('#'+data.id).val();
		$('#val-'+data.id).html(new_val);
		$('#'+data.id).hide();
		$('#val-'+data.id).show();
		// Resaltar como cambio 
		if(cur_val!=new_val){
			$('#val-'+data.id).addClass('nw');
		}else{
			$('#val-'+data.id).removeClass('nw');
		}
		
	});
}
function appForm_PopupSrc(data){
	
	var val = $('#'+data.id).val();
	fk_ajax_exec({pDiv:'srcfld-rs-'+data.id, pUrl:'FkMaster/PopupSrc', pArgs:'idf='+data.id+'&v='+val+'&t='+data.tbl, pUrlAfter:'', insertMode:''}); 
	
}
function appForm_PopupSrcSel(i,id){
	val = $("#td_"+i+"-0").html();
	$("#"+id).val(val);
	$("#psrc-"+id).dialog("close");
	$("#psrc-"+id).remove();	
}
// DATAGRID control FkGrid()
var DEBUG = true;
function dg_funct(tName,row,fnct){
   if (eval("typeof " +tName+  "_"+fnct+"_click == 'function'")) {
	   eval(tName+  "_"+fnct+"_click('"+tName+"','"+row+"')");
   }else{
	   dg_debug_msg('function '+tName+'_'+fnct+'_click() does not exist');
   }
}
function dg_get_val(tName,row,col){ 
  if($('#dg_'+tName+'_'+row+'_'+col)){
    return $('#dg_'+tName+'_'+row+'_'+col).val();
  }else{ return '';
  }
  
}

// appform