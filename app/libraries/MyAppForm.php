<?php
/**
 * FreeKore Php Framework
 * Version: 0.1 Beta
 *
 * @Author     Ahmed Rugama MAya
 * @Modified     M. Angel Mendoza  email:mmendoza@freekore.com
 * @copyright  Copyright (c) 2010 Freekore PHP Team
 * @license    New BSD License
 */
/*************************************************************
 **  Descripcion: Clase para Formularios dinamicos en freekore
/**
 *@package AppForm
 *@since v0.1 beta
 *@desc  Application Form
 **/
 

class MyAppForm{
	public $settings = array(
		'create' => '1',
		'edit' => '1',
		'delete' => '1',
	);
	public $lenguaje = array(
		'btn_create' => 'Nuevo',
	);
	public $ajax = 0;
	private $fields;
	private $fieldnames;
	public $searchcond = '';
	public $table = '';
	public $tableclass ='datatable_appform';
	public $currentrecord; // id o permalink del record que se modificara
	public $hidefields = array();
	public $stopsearch = array();
	public $labels = array();
	public $stage; // show, new, edit, ...
	private $db;
	
	function __construct($t,$s = '',$cr = 0){
		$this->table = $t;
		$this->stage = $s;
		$this->currentrecord = $cr;
		
		$this->db = new db;
		$this->db->connect();
		$this->db->query("SHOW COLUMNS FROM ".$this->table);
		while($r = $this->db->next()){
			$this->fields[] = $r['Field'];
		}
	}
	
	function render(){
		foreach($this->fields as $r){
			if(!in_array($r, $this->hidefields)){
				if(isset($this->labels[$r])){
					$r = $this->labels[$r];
				}else{
					$r = str_replace('_',' ', $r);
					$r = ucwords($r);
				}
				$this->fieldnames[] = $r;
			}
		}
		
		switch($this->stage){
			case '':
				$r = $this->viewtable();
				break;
			case 'nuevo':
				if($this->settings['create'])
					$r = $this->editform();
				break;
			case 'ajax_records':
				$r = '{ "aaData": [';
//	["Trident","Internet Explorer 4.0","Win 95+","4","X"]
					$query = 'SELECT * FROM '.$this->table;
					if($this->searchcond != '')$query += $this->searchcond;
					$this->db->query_assoc($query);
					while($res = $this->db->next()){
						$r .= '[';
						$zero = 0;
						foreach($this->fields as $f){
							if(!in_array($f, $this->hidefields)){
								if($zero != 0)
									$r .= ',';
								$zero++;
								$r .= '"'.$res[$f].'"';
							}
						}
						$r .= ']';
					}
				$r .= '] }';
				die($r);
				break;
			case 'editar':
				if($this->settings['edit'])
					$r = $this->editform();
				break;
			case 'ex_nuevo':
				if($this->settings['create'])
					$this->editform();
				break;
			case 'ex_editar':
				if($this->settings['edit'])
					$this->editform();
				break;
			case 'ex_eliminar':
				if($this->settings['delete'])
					$this->editform();
				break;
				
		}
		return $r;
	}
	
	function viewtable(){
		$html = 
		'<table class="'.$this->tableclass.'" width="100%">
    	<thead>
        	<tr>';
		foreach($this->fieldnames as $f){
			$html .= '<th>'.$f.'</th>';
		}
		$html .= '';
        $html .= '</tr>
        </thead>
		
        <tbody>';
		
	        $html .= '<tr>'; 
			if($this->ajax == 0){
				$query = 'SELECT * FROM '.$this->table;
				if($this->searchcond != '')$query += $this->searchcond;
				$this->db->query_assoc($query);
				while($res = $this->db->next()){
					foreach($this->fields as $f){
						$html .= '<td>'.$res[$f].'</td>';
					}
				}
			}
			$html .= '</tr>';
		
  		$html .= '</tbody>
    		</table>
    		<a href="" class="standar-btn ui-corner-all">'.$this->lenguaje['btn_create'].'</a>';
			
		return $html;
	}
	
	function editform(){
	}

	
} // End class
