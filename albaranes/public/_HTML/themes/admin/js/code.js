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
						htt = base_url+( $('input[name="location"]', cform).val() );
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
