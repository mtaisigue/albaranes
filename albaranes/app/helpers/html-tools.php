<?php
if(!function_exists('form_open')){

	function form_open($IdObj,$Action,$Method = 'POST',$Target='_self',$Return = FALSE){

		$frm = '<form id="'.$IdObj.'" name="'.$IdObj.'" target="'.$Target.'" action="'.$Action.'" method="'.$Method.'" >';

		if($Return==true){
			return $frm;
		}else{
			echo $frm;
		}
	}

} // print hidden
if(!function_exists('form_open_multipart')){

	function form_open_multipart($IdObj,$Action,$Method = 'POST',$Target='_self',$Return = FALSE){

		$frm = '<form id="'.$IdObj.'" name="'.$IdObj.'" target="'.$Target.'" action="'.$Action.'" method="'.$Method.'" enctype="multipart/form-data" >';

		if($Return==true){
			return $frm;
		}else{
			echo $frm;
		}
	}

}
// form_close
if(!function_exists('form_close')){

	function form_close($Return = FALSE){

		$frm = '</form>';
		if($Return==true){
			return $frm;
		}else{
			echo $frm;
		}
	}

}

// form text
if(!function_exists('form_text')){
	function form_text($IdObj,$Value,$Return = FALSE){
		$txt = '<input type="text" id="'.$IdObj.'" name="'.$IdObj.'" value="'.$Value.'">';
		if($Return==true){
			return $txt;
		}else{
			echo $txt;
		}
	}

}

// form hidden
if(!function_exists('form_hidden')){
	function form_hidden($IdObj,$Value,$Return = FALSE){
		$hidden = '<input type="hidden" id="'.$IdObj.'" name="'.$IdObj.'" value="'.$Value.'">';
		if($Return==true){
			return $hidden;
		}else{
			echo $hidden;
		}
	}

}
// print hidden alias of form_hidden
if(!function_exists('print_hidden')){

	function print_hidden($IdObj,$Value,$Return = FALSE){
		form_hidden($IdObj,$Value,$Return);
	}

}

// html_message
if(!function_exists('html_message')){
	function ui_message($Message,$Type = 'ok',$Buttons = '',$AutoClose = TRUE){

		$id = 'html_message-'.encode(rand('10000000','10000000000'));
		$html_buttons = '';
		if($Buttons!=''){
			$btns = explode('|', $Buttons);
			$html_buttons = '';
			foreach ($btns as $button){
				$html_buttons .= '<a href="javascript:void(0)" class="btn-1a" onclick="hide(\''.$id.'\')">'.$button.'</a>';
			}
		}

		$ms = '<div id="'.$id.'" class="fk-'.$Type.'-message">'.$Message.'<div class="clear"></div>'.$html_buttons.'</div>';
		if($AutoClose==TRUE){
			$ms .= '<script>setTimeout("hide(\''.$id.'\')",3000);</script>';
		}
		return $ms;

	}
}