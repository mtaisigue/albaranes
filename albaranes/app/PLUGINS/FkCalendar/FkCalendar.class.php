<?php
class FkCalendar{

	public $Url = '';
	public $operation = '';
	private $fecha_header_label='';
	public $Date;

	public $EventList = array();

	public function __construct($getCurrentUrl){


		$this->Url = $getCurrentUrl;
		$this->operation = decode(fk_post('oper'));

		$this->DateTime = new DateTime();

		// No display header
		if(fk_post('ajax') == '1'){
			fk_no_display_header();
		}

	}

	/**
	 * @method addEvent
	 * @desc dateformat="Y-m-d",time format=H:i,all_day = true|false
	 * */
	public function addEvent($title,$date_ini,$time_ini,$time_fin,$date_fin,$all_day=FALSE){

		$this->EventList[] = array('t'=>$title,'d_i'=>$date_ini,'t_i'=>$time_ini,'t_f'=>$time_fin,'d_f'=>$date_fin,'allday'=>$all_day);

	} // end addEvent

	public function Render(){

		$html = '';
		$this->fecha_header_label = getFormatedDate(date('Y-m-d'));


		$this->get_date();

		switch ($this->operation) {

			case 'd_today': // dia previo

			 $this->today_day(); // regresa a dia hoy
			 $html .= $this->get_tab_day();
			 break;
			 	
			case 'd_prev': // dia previo

			 $this->prev_day(); // Atrasa 1 dia
			 $html .= $this->get_tab_day();
			 break;
			 	
			case 'd_next': // dia siguiente
			 $this->next_day(); // adelanta 1 dia
			 $html .= $this->get_tab_day();
			 break;
			 	
			default:
				$html .= $this->getCalendar();
				break;
		}

		return $html;

	} // Render

	private function getCalendar(){


		$html = '<div id="cal-tbs">
	<ul>
		<li><a href="#ctb-1">Dia</a></li>
		<li><a href="#ctb-2">Semana</a></li>
		<li><a href="#ctb-3">Mes</a></li>
	</ul>
	<div id="ctb-1">
    <form  id="fkc-frm-day" name="fkc-frm-day" action="?">
    <div id="cal-pnl-1" class="caltb">'.$this->get_tab_day().'</div>
	</form>
	</div>
	<div id="ctb-2">
	<form  id="fkc-frm-wk" name="fkc-frm-wk" action="?">
    <div id="cal-pnl-2" class="caltb">'.$this->get_tab_week().'</div>
	</form>
	</div>
	<div id="ctb-3">
	<form id="fkc-frm-yr" name="fkc-frm-yr" action="?">
    <div id="cal-pnl-3" class="caltb">'.$this->get_tab_day().'</div>
	</form></div>
</div>

<script type="text/javascript">
$(document).ready(function(){
   $( "#cal-tbs" ).tabs();
});
</script>';


		return $html;

	} // getCalendar

	private function get_tab_day(){

		$fx_d_today = new fk_ajax('submit', 'cal-pnl-1', 'form:fkc-frm-day;url:'.$this->Url.';args:oper='.encode('d_today'));
		$fx_d_prev = new fk_ajax('submit', 'cal-pnl-1', 'form:fkc-frm-day;url:'.$this->Url.';args:oper='.encode('d_prev'));
		$fx_d_next = new fk_ajax('submit', 'cal-pnl-1', 'form:fkc-frm-day;url:'.$this->Url.';args:oper='.encode('d_next'));


		$horas = $this->getHoras();
		$event_day_list = $this->getEventDayList();


		$html = '<div >
	
	<input type="button" onclick="'.$fx_d_today->render().'" value=" Hoy ">
	<input type="button" onclick="'.$fx_d_prev->render().'" value=" &laquo; ">
	<input type="button" onclick="'.$fx_d_next->render().'" value=" &raquo; ">
	'.$this->fecha_header_label.'
	<div class="day-view">
	'.$this->input_date.'
<table style="width:100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div class="dy-head"> <h3>'.$this->dia_label.'</h3> 
        <div class="new-agend">&nbsp;</div>
        </div>
        </td>
  </tr>
  <tr>
    <td><div class="dy-body"> 
    
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr><td width="50"><div class="col-1">'.$horas.'</div></td>
      <td width="%" valign="top"><div class="col-2">'.$event_day_list.'</div></td></tr>
      </table>
      
     
    </div>
     </td>
  </tr>
</table>
</div>
	</div>	
	';

		return $html;



	}


	private function get_tab_week(){

		$fx_d_prev = new fk_ajax('submit', 'cal-pnl-1', 'form:fkc-frm-day;url:'.$this->Url.';args:oper='.encode('d_prev'));
		$fx_d_next = new fk_ajax('submit', 'cal-pnl-1', 'form:fkc-frm-day;url:'.$this->Url.';args:oper='.encode('d_next'));
		$horas = $this->getHoras();

		$html = '<div >
	
	
	<div>
	<input type="button" onclick="'.$fx_d_prev->render().'" value=" &laquo; ">
	<input type="button" onclick="'.$fx_d_next->render().'" value=" &raquo; ">
	 
	
	
	'.$this->rango_fecha_header_label.' 
	</div>
	
	<div class="day-view">
	'.$this->input_date.'
<table width="200" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div class="dy-head"> <h3>'.$this->dia_label.'</h3> 
        <div><input type="text"></div>
        </div>
        </td>
  </tr>
  <tr>
    <td><div class="dy-body"> 
    
      <div class="col-1">'.$horas.'
      </div>
      <div class="col-2">
      
      </div>
     
    </div>
     </td>
  </tr>
</table>
</div>
	</div>	
	';

		return $html;



	}


	private function getHoras(){
			
		$html ='';

		$hr = -1;
		for($i=0;$i<24;$i++){

			$hr = $hr + 1;
				
			$hrstr =($hr<10)?'0'.$hr.':00':$hrstr = $hr.':00';
				
			$html .= '<div class="hr">
            <div class="hr1">'.$hrstr.' </div>
            <div class="hr2">&nbsp;</div>
            </div>';	
		}


		return $html;
	} //getHoras

	private function getEventDayList(){
			
		$html ='';
		$hr = 11;
		

		$date =  $this->Date->format('Y-m-d');
		$eventos = array();
		foreach ($this->EventList as $k=>$v){

			if($v['d_i']==$date){
				$hour_xp = explode(':',$v['t_i']);
				
				$hours = $hour_xp[0];
				$mins =  $hour_xp[1];
				
				$newv = array_merge($v,array('time'=>'2H'));
				
				$eventos[$hours][] = $newv;
			}
		}



			
		$hr = -1;
		for($i=0;$i<24;$i++){

			$hr = $hr + 1;
				
			$hrstr =($hr<10)?'0'.$hr:$hrstr = $hr;
			$ev_1 = '';
			$ev_2 = '';
				
			if(isset($eventos[$hrstr])){
				//pa($eventos[$hrstr]);
				foreach ($eventos[$hrstr] as $k=>$evnt) {
					$ishalf=FALSE;
					$mins = explode(':',$evnt['t_i']);
					$mins = $mins[1];
					
					if($mins=='30'){$ishalf=TRUE;}
					
					$height = 'height:20px';
					
					if($ishalf==FALSE){
						$ev_1 .= '<li class="evnt" style="'.$height.'"> '.$evnt['time'].'-'.$evnt['t_i'].'-'.$evnt['t_f'].' <b>'.$evnt['t'].'</b> </li>';
					}else{
						$ev_2 .= '<li class="evnt" style="'.$height.'"> '.$evnt['time'].'-'.$evnt['t_i'].'-'.$evnt['t_f'].' <b>'.$evnt['t'].'</b> </li>';
					}

				}
					
			}
				
			$html .= '<div class="hr">';
			$html .= '<div class="hr1"><ul class="dropper">'.$ev_1.'</ul></div>';
			$html .= '<div class="hr2"><ul class="dropper">'.$ev_2.'</ul></div>';
			$html .= '</div>';
		}
		
		$html .='<script> 
	$(function() {
		$( "ul.dropper" ).sortable({
			connectWith: "ul"
		});
		$( "#sort-list" ).disableSelection();
	});
</script>';

		return $html;
	} //getHoras

	private function get_date(){

		//		$interv_day = new DateInterval('P1D');
		//		$interv_week = new DateInterval('P1W');
		//		$interv_month = new DateInterval('P1M');
		//		$interv_year = new DateInterval('P1Y');

		$y = date('Y');
		$m = date('m');
		$d = date('d');

			

		if(fk_post('fkc-date')!==''){
			$p_date = explode('-', fk_post('fkc-date'));
			$y = $p_date[0];
			$m = $p_date[1];
			$d = $p_date[2];

		}




		$this->Date = new DateTime();
		$this->Date-> setDate($y,$m,$d);


		// update labels with right dates and input
		$this->update_date_labels();

	}

	private function update_date_labels(){

		$DateWeek_1 = new DateTime();
		$DateWeek_1-> setTimestamp ($this->Date->getTimestamp());

		$DateWeek_2 = new DateTime();
		$DateWeek_2-> setTimestamp ($this->Date->getTimestamp());

		$this->fecha_header_label =  strftime(" %B %d, %Y ",$this->Date->getTimestamp());
		$this->rango_fecha_header_label =  strftime(" %B %d ",$DateWeek_1->getTimestamp()).' - '.strftime(" %B %d %Y ",$DateWeek_2->getTimestamp());
		$this->dia_label =  strftime(" %A %d ",$this->Date->getTimestamp());
		$this->input_date = '<input type="hidden" value="'.$this->Date->format('Y-m-d').'" id="fkc-date" name="fkc-date">';

		$this->fecha_header_label = __($this->fecha_header_label);
		$this->rango_fecha_header_label = __($this->rango_fecha_header_label);
		$this->dia_label = __($this->dia_label);

	}

	private function today_day(){

		$interv_day = new DateInterval('P1D');
		$this->Date->setDate(date('Y'), date('m'), date('d'));

		// update labels with right dates and input
		$this->update_date_labels();
	}

	private function next_day(){

		$interv_day = new DateInterval('P1D');

		$this->Date->add($interv_day);

		// update labels with right dates and input
		$this->update_date_labels();


	}

	private function prev_day(){

		$interv_day = new DateInterval('P1D');
		$this->Date->sub($interv_day);

		// update labels with right dates and input
		$this->update_date_labels();
	}

}