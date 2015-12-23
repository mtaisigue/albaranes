<?php
/**
 * FreeKore Php Framework
 * Version: 0.1 Beta
 *
 * @Author     M. Angel Mendoza  email:mmendoza@freekore.com
 * @copyright  Copyright (c) 2010 Freekore PHP Team
 * @license    New BSD License
 */
/*************************************************************
 **  Programa:  db_record.class.php
 **  Descripcion: Funciones Active record db_record
 **  proyecto    fecha      por         descripcion
 **  ----------  ---------  ----------- ----------------
 **  00000001    30/04/10   mmendoza    Creado.
 **************************************************************/
/**
 *@package db_record
 *@desc Main class of ActiveRecord
 *@since v0.1 beta
 * */
class db_record {

	public  $fields = array();
	public  $form_fields = array();
	public  $num_rows = 0;
	public  $table = '';
	public  $id_field_name = '';
	public  $db_type;  // db_type, definido por defecto en config/config.ini
	private $db_obj; // Database de acuerdo a db_type
	private $FindMode = ''; // Find mode: [first|last|next|prev]
	private $SqlWhere = ''; // SQL WHERE
	public $SqlAnd = ''; // SQL AND

	public $field_mode = 'view-edit'; // update | view-edit

	public $error_code = ''; // database error code



	/**
	 * @package db_record :: Active Record
	 * @method  __construct()
	 * @since v0.1 beta
	 * */
	public function __construct($table = '',$p_db_type = NULL){
		//------------------------------------------
	 //create db object
		//------------------------------------------
	 if( $p_db_type != NULL ){
	 	$this->db_type = $p_db_type;
	 }else{
	 	$this->db_type = $GLOBALS['FKORE']['RUNNING']['db']['db_type'];
	 }
	 $this->db_obj = new db($this->db_type);
	 $this->db_obj->connect();

	 //------------------------------------------
	 //inicializar valores de objeto
	 //------------------------------------------

	 // TABLE
	 if($table==''){
	 	$this->table = $this->get_table();
	 }else{$this->table = $table;}


	 // obtiene la estructura de la tabla
	 self::describe_table($this->table);
	  
	 // obtener id_field_name
	 $this->id_field_name = $this->get_id_table();


	 //fields
	 $this->fields[$this->id_field_name] = 0;

	}
	/**
	 *@package db_record
	 *@method __destruct()
	 *@since v0.1 beta
	 * */
	public function __destruct(){
		//------------------------------------------
	 //destruc db object
		//------------------------------------------
	 //$this->db_obj->close();

	}
	/**
	 *@package db_record
	 *@method insert()
	 *@desc inserts a record
	 *@since v0.1 beta
	 * */
	public function insert() {

		$fields_list = '';
		$fields_vals = '';

		foreach($this->fields as $f_name=>$f_val){
			if($f_name!=$this->id_field_name){

				$FieldType = strtolower($this-> form_fields[$f_name]['Type']);

				if($FieldType=='password'){
					// Exepcion password
					if(trim($f_val)!=''){
						$fields_vals .= "'".md5($f_val)."',";
					}else{$fields_vals .= "'',";}
				}elseif($FieldType=='date'){
					// Exepcion date
					if($f_val===NULL || trim($f_val)==''){
						$fields_vals .= " NULL ,";
					}else{
						$fields_vals .= "'".$this->db_obj->escape_string($f_val)."',";
					}

				}else{
					if($f_val===NULL){
						$fields_vals .= " NULL ,";
					}else{
						$fields_vals .= "'".$this->db_obj->escape_string($f_val)."',";
					}

				}
				$fields_list .= ' `'.$f_name.'` ,';

			}

		}

		$fields_list = trim($fields_list,',');
		$fields_vals = trim($fields_vals,',');

		$sql = 'INSERT INTO '.$this->table.'
	           (`'.$this->id_field_name.'`,'.$fields_list.')
  			   VALUES (NULL,'.$fields_vals.')';

		$rs = $this->db_obj->query($sql);

		if($rs==FALSE){
			$this->error_code = $this->db_obj->error_code;
		}

		return $rs;


	}
	/**
	 *@package db_record
	 *@method update()
	 *@desc updates a record
	 *@since v0.1 beta
	 * */
	public function update() {

		$set_fields = '';
		foreach($this->fields as $f_name=>$f_val){
			if($f_name!=$this->id_field_name){

				$FieldType = strtolower($this-> form_fields[$f_name]['Type']);
				if($FieldType=='password'){
					// Exepcion password
					if(trim($f_val)!=''){
						$set_fields .= " `".$f_name."` = '".md5($f_val)."',";
					}
				}elseif($FieldType=='date'){
					// Exepcion date
					if($f_val===NULL || trim($f_val)==''){
						$set_fields .= " `".$f_name."` = NULL ,";
					}else{
						$set_fields .= " `".$f_name."` = '".$this->db_obj->escape_string($f_val)."',";
					}

				}else{
					if($f_val===NULL){
						$set_fields .= " `".$f_name."` = NULL ,";
					}else{
						$set_fields .= " `".$f_name."` = '".$this->db_obj->escape_string($f_val)."',";
					}

				}
					
			}
		}
		$set_fields = trim($set_fields,',');



		$SET = ' SET '.$set_fields;
		$WHERE = ' WHERE '.$this->id_field_name.' = "'.$this->fields[$this->id_field_name].'" ';

		$sql = 'UPDATE `'.$this->table.'` '.$SET.' '.$WHERE;

		$rs = $this->db_obj->query($sql);

		if($rs==FALSE){
			$this->error_code = $this->db_obj->error_code;
		}

		return $rs;
	}
	/**
	 *@package db_record
	 *@method delete()
	 *@desc deletes a record
	 *@since v0.1 beta
	 * */
	public function delete() {


		$WHERE = ' WHERE '.$this->id_field_name." = '".$this->fields[$this->id_field_name]."' ";

		$sql = 'DELETE FROM '.$this->table.' '.$WHERE;


		$rs = $this->db_obj->query($sql);

		if($rs==FALSE){
			$this->error_code = $this->db_obj->error_code;
		}

		return $rs;
	}
	/**
	 *@package db_record
	 *@method save()
	 *@desc inserts or updates a record from a given Id_record;
	 *      if $this->fields[id_record] is set to 0 then Inserts else Updates
	 *@since v0.1 beta
	 * */
	public function save() {
			
	 if($this->fields[$this->id_field_name]==0 ){
	 	$this->insert();
	 }else{$this->update();}

	  
	}
	/**
	 *@package db_record
	 *@method describe_table()
	 *@desc describes a table
	 *@since v0.1 beta
	 * */
	private function describe_table(){
		$this->form_fields = $this->db_obj->describe_table($this->table);
	} // function describe_table()

	/**
	 *@package db_record
	 *@method get_fields()
	 *@desc populates $this->fields array with the record fields
	 *@since v0.1 beta
	 * */
	public function get_fields(){
		$AND = '';
		switch ($this->FindMode) {
			case 'first':
				if(!empty($this->SqlAnd)){
					$AND = 'WHERE 1=1 '.$this->SqlAnd;
				}
				$WHERE = ' '.$AND.'  LIMIT 2 ';
				break;
			case 'prev':
				if(!empty($this->SqlAnd)){
					$AND = $this->SqlAnd;
				}
				$WHERE = ' WHERE '.$this->id_field_name.' < "'.$this->fields[$this->id_field_name].'" '.$AND.' ORDER BY '.$this->id_field_name.' DESC LIMIT 2 ';
				break;
			case 'next':
				if(!empty($this->SqlAnd)){
					$AND = $this->SqlAnd;
				}
				$WHERE = ' WHERE '.$this->id_field_name.' > "'.$this->fields[$this->id_field_name].'" '.$AND.' LIMIT 2 ';
				break;
			case 'last':
				if(!empty($this->SqlAnd)){
					$AND = 'WHERE 1=1 '.$this->SqlAnd;
				}
				$WHERE = ' '.$AND.' ORDER BY '.$this->id_field_name.' DESC LIMIT 2 ';
				break;
			case 'where':
				$WHERE = $this->SqlWhere.' '.$this->SqlAnd;
				break;

			default:
				if(!empty($this->SqlAnd)){
					$AND = $this->SqlAnd;
				}
				$WHERE = ' WHERE '.$this->id_field_name.' = "'.$this->fields[$this->id_field_name].'" '.$AND;
				break;
		}
			
		$sql = 'SELECT * FROM '.$this->table.' '.$WHERE;
			

		$this->db_obj->query_assoc($sql);
		$this->fields = array(); // Clear Array

		// Return fist result
		if($record = $this->db_obj->next()){
			$this->fields = $record;
		}
		// Set total rows
		$this-> num_rows = $this->db_obj-> num_rows() ;
			
			
			
	} // function get()

	/**
	 *@package db_record
	 *@method print_form_field()
	 *@desc returns the list of html fields  automatically from a record
	 *@since v0.1 beta
	 * */
	public function print_form_field($field,$CssName = '',$ExtraAttributes ='' ,$encode_fields =FALSE,$access=TRUE,$read_only=FALSE){
		$html_fld = '';

		if($encode_fields==TRUE){
			$field_id_html = 'fkf_'.encode($field);
			$field_name_html = encode($field);
		}else{
			$field_id_html = $field;
			$field_name_html = $field;
		}



		$original_type = $this->form_fields[$field]['Type'];
		$type_x = explode("(",$original_type);
		if($type_x>1){
			$type  = $type_x[0];
		}else{$type = $original_type;}

		// Display Mode

		$mode_view_edit = ($this->field_mode=='view-edit'?true:false);

		if($read_only==true){
			// read only
			$display_as='read-only';
		}else{
			if($this->field_mode=='view-edit'){
				$display_as = 'view-edit';
			}else{
				$display_as='edit';
			}
		}

		$this->fields[$field] = isset($this->fields[$field]) ? htmlentities( $this->fields[$field] ) : '' ;


		switch($type){
			case "varchar":
				// Class
				$Class = 'class="txt '.@$CssName.'"';
				if($access==TRUE){
					if($display_as=='view-edit'){
						$html_fld .='<div class="fld" onclick="appForm_updfldTxt({id:\''.$field_id_html.'\'})"><input style="display:none" id="'.$field_id_html.'" name="'.$field_name_html.'" type="text" value="'.@$this->fields[$field].'" '.$Class.' '.@$ExtraAttributes.' />';
						$html_fld .='<span id="val-'.$field_id_html.'">'.@$this->fields[$field].'</span>&nbsp;<span class="ui-icon ui-icon-gear"></span></div>';
						$html_fld .='<input id="cur-v-'.$field_id_html.'" type="hidden" value="'.$this->fields[$field].'"  />';
					}elseif($display_as=='edit'){
						$html_fld .='<input id="'.$field_id_html.'" name="'.$field_name_html.'" type="text" value="'.@$this->fields[$field].'" '.$Class.' '.@$ExtraAttributes.' />';
					}elseif($display_as=='read-only'){
						$html_fld .=@$this->fields[$field].'<input id="'.$field_id_html.'" name="'.$field_name_html.'" type="hidden" value="'.@$this->fields[$field].'" '.$Class.' '.@$ExtraAttributes.' />';
					}
				}


				break;
		 case "timestamp":
		 	// Class
		 	$Class = 'class="date-time '.@$CssName.'"';
		 	$html_fld .='<input id="'.$field_id_html.'" name="'.$field_name_html.'" type="text" value="'.@$this->fields[$field].'" '.$Class.' '.@$ExtraAttributes.'/>
    <script language="javascript" type="text/javascript">
	$(function() {
		
		$( "#'.$field_id_html.'" ).datepicker({ 
			  dateFormat: "yy-mm-dd",
			  showOn: "button",
			  buttonImage: HTTP+"_HTML/img/calendar.gif",
			  buttonImageOnly: true
		});
		
		
	});
	</script>';

		 	break;
		 case "date":
		 	//$Class = 'class="date '.@$CssName.'"';
		 	$Class = 'class="'.@$CssName.'"';
		 	if($access==TRUE){
		 		if($display_as=='view-edit'){
		 			$html_fld .='<input id="'.$field_id_html.'" name="'.$field_name_html.'" type="text" value="'.@$this->fields[$field].'" '.@$Class.' '.@$ExtraAttributes.' />';
		 			$html_fld .='<script language="javascript" type="text/javascript">$(function() {$( "#'.$field_id_html.'" ).datepicker({ dateFormat: "yy-mm-dd",showOn: "button",buttonImage: HTTP+"_HTML/img/calendar.gif", buttonImageOnly: true});	});	</script>';
		 		}elseif($display_as=='edit'){
		 			$html_fld .='<input id="'.$field_id_html.'" name="'.$field_name_html.'" type="text" value="'.@$this->fields[$field].'" '.@$Class.' '.@$ExtraAttributes.' />';
		 			$html_fld .='<script language="javascript" type="text/javascript">$(function(){$( "#'.$field_id_html.'" ).datepicker({ dateFormat: "yy-mm-dd",showOn: "button",buttonImage: HTTP+"_HTML/img/calendar.gif", buttonImageOnly: true});	});	</script>';
		 		}elseif($display_as=='read-only'){
                    $html_fld .= getFormatedDate($this->fields[$field]) .'<input id="'.$field_id_html.'" name="'.$field_name_html.'" type="hidden" value="'.@$this->fields[$field].'" '.$Class.' '.@$ExtraAttributes.' />';
		 		}
		 	}


		 	break;
		 case "text":
		 	$Class = 'class="'.@$CssName.'"';
		 	if($access==TRUE){
					if($display_as=='view-edit'){
						$html_fld .='<div class="fld" onclick="appForm_updfldTxt({id:\''.$field_id_html.'\'})"><textarea style="display:none" id="'.$field_id_html.'" name="'.$field_name_html.'"  '.$Class.' '.@$ExtraAttributes.' >'.@$this->fields[$field].'</textarea>';
						$html_fld .='<span id="val-'.$field_id_html.'">'.@$this->fields[$field].'</span>&nbsp;<span class="ui-icon ui-icon-gear"></span></div>';
						$html_fld .='<input id="cur-v-'.$field_id_html.'" type="hidden" value="'.@$this->fields[$field].'"  />';
					}elseif($display_as=='edit'){
						$html_fld .='<textarea id="'.$field_id_html.'" name="'.$field_name_html.'"  '.$Class.' '.@$ExtraAttributes.' >'.@$this->fields[$field].'</textarea>';
					}elseif($display_as=='read-only'){
						$html_fld .=@$this->fields[$field].'<input id="'.$field_id_html.'" name="'.$field_name_html.'" type="hidden" value="'.@$this->fields[$field].'" '.$Class.' '.@$ExtraAttributes.' />';
					}
		 	}

		 	break;
		 case "textarea":
		 	$Class = 'class="'.@$CssName.'"';
		 	if($access==TRUE){
					if($display_as=='view-edit'){
						$html_fld .='<div class="fld" onclick="appForm_updfldTxt({id:\''.$field_id_html.'\'})"><textarea style="display:none" id="'.$field_id_html.'" name="'.$field_name_html.'"  '.$Class.' '.@$ExtraAttributes.' >'.@$this->fields[$field].'</textarea>';
						$html_fld .='<span id="val-'.$field_id_html.'">'.@$this->fields[$field].'</span>&nbsp;<span class="ui-icon ui-icon-gear"></span></div>';
						$html_fld .='<input id="cur-v-'.$field_id_html.'" type="hidden" value="'.@$this->fields[$field].'"  />';
					}elseif($display_as=='edit'){
						$html_fld .='<textarea id="'.$field_id_html.'" name="'.$field_name_html.'"  '.$Class.' '.@$ExtraAttributes.' >'.@$this->fields[$field].'</textarea>';
					}elseif($display_as=='read-only'){
						$html_fld .=@$this->fields[$field].'<input id="'.$field_id_html.'" name="'.$field_name_html.'" type="hidden" value="'.@$this->fields[$field].'" '.$Class.' '.@$ExtraAttributes.' />';
					}
		 	}

		 	break;
		 case "tinyint":
		 	$chk[0] = '';
		 	$chk[1] = '';
		 	$chk_read_val = (@$this->fields[$field]==1?'Si':'No');
		 	if(@$this->fields[$field] == 1 || @$this->fields[$field]==0){
		 		$chk[@$this->fields[$field]] = 'CHECKED';
		 	}
		 	$Class = 'class="'.@$CssName.'"';
		 	if($access==TRUE){
					if($display_as=='view-edit' || $display_as = 'edit'){
						$html_fld .='Si<input id="'.$field_id_html.'_1" name="'.$field_name_html.'" '.$chk['1'].' type="radio" value="1" '.$Class.' '.@$ExtraAttributes.'>
             No<input id="'.$field_id_html.'_0" name="'.$field_name_html.'" '.$chk['0'].' type="radio" value="0" '.$Class.' '.@$ExtraAttributes.'>';
					}elseif($display_as=='read-only'){
						$html_fld .=$chk_read_val;
					}
		 	}
		 	 

		 	break;
		 	// Tipo Password
		 case "password":
		 	$Class = 'class="pass '.@$CssName.'"';

		 	if($access==TRUE){
					if($display_as=='view-edit'){
						$html_fld .='<input id="'.$field_id_html.'" name="'.$field_name_html.'" type="password" value="" '.$Class.' '.@$ExtraAttributes.' />';
					}elseif($display_as=='edit'){
						$html_fld .='<input id="'.$field_id_html.'" name="'.$field_name_html.'" type="password" value="" '.$Class.' '.@$ExtraAttributes.' />';
					}elseif($display_as=='read-only'){
						$html_fld .= '********';
					}
		 	}

		 	break;
		 case "select":

		 	// Get Select Options from sql

		 	$Class = 'class="sel '.@$CssName.'"';

		 	if($access==TRUE){

		 		$option_selected = isset($this->form_fields[$field]['selected_option']) ? $this->form_fields[$field]['selected_option'] : @$this->fields[$field];

					if($display_as=='view-edit'){

						$options = fk_select_options($this-> form_fields[$field]['sql_options'],$option_selected);
						$html_fld .='<select id="'.$field_id_html.'" name="'.$field_name_html.'" '.$Class.' '.@$ExtraAttributes.' ><option></option>'.$options.'</select>';

					}elseif($display_as=='edit'){
						$options = fk_select_options($this-> form_fields[$field]['sql_options'],$option_selected);
						$html_fld .='<select id="'.$field_id_html.'" name="'.$field_name_html.'" '.$Class.' '.@$ExtraAttributes.' ><option></option>'.$options.'</select>';
					}elseif($display_as=='read-only'){

						$slq_elements = fk_get_query_elements($this-> form_fields[$field]['sql_options']);

						$table = $slq_elements['table'];
						$val_fld_name = $slq_elements['fields'];

						$options = fk_select_text($table, $val_fld_name, $option_selected);
						$html_fld .=$options[1].'<input id="'.$field_id_html.'" name="'.$field_name_html.'" type="hidden" value="'.@$this->fields[$field].'" />';

					}
		 	}

		 	break;
		 case "hidden":
		 	$Class = 'class="hdn '.@$CssName.'"';
		 	$html_fld .='<input id="'.$field_id_html.'" name="'.$field_name_html.'" type="hidden" value="'.@$this->fields[$field].'" '.$Class.' '.@$ExtraAttributes.' />';
		 	break;
		 case "search_field":
				// Class
				$Class = 'class="txt '.@$CssName.'"';
				if($access==TRUE){
					if($display_as=='view-edit'){
						$html_fld .='<div class="fld" onclick="appForm_updfldTxt({id:\''.$field_id_html.'\'})"><input style="display:none" id="'.$field_id_html.'" name="'.$field_name_html.'" type="text" value="'.@$this->fields[$field].'" '.$Class.' '.@$ExtraAttributes.' />';
						$html_fld .='<span id="val-'.$field_id_html.'">'.@$this->fields[$field].'</span>&nbsp;<span class="ui-icon ui-icon-gear"></span></div>';
						$html_fld .='<input id="cur-v-'.$field_id_html.'" type="hidden" value="'.$this->fields[$field].'"  />';
					}elseif($display_as=='edit'){
						$html_fld .='<input id="'.$field_id_html.'" name="'.$field_name_html.'" type="text" value="'.@$this->fields[$field].'" '.$Class.' '.@$ExtraAttributes.' /><input type="button" value="..." onclick="appForm_PopupSrc({id:\''.$field_id_html.'\',tbl:\''.$this->table.'\'})">';
						$html_fld .='<div id="srcfld-rs-'.$field_id_html.'"></div>';
					}elseif($display_as=='read-only'){
						$html_fld .=@$this->fields[$field].'<input id="'.$field_id_html.'" name="'.$field_name_html.'" type="hidden" value="'.@$this->fields[$field].'" '.$Class.' '.@$ExtraAttributes.' />';
					}
				}


				break;
		 default:
		 	$Class = 'class="txt '.@$CssName.'"';
		 	if($access==TRUE){
					if($display_as=='view-edit'){
						$html_fld .='<div class="fld" onclick="appForm_updfldTxt({id:\''.$field_id_html.'\'})"><input style="display:none" id="'.$field_id_html.'" name="'.$field_name_html.'" type="text" value="'.@$this->fields[$field].'" '.$Class.' '.@$ExtraAttributes.' />';
						$html_fld .='<span id="val-'.$field_id_html.'">'.@$this->fields[$field].'</span>&nbsp;<span class="ui-icon ui-icon-gear"></span></div>';
						$html_fld .='<input id="cur-v-'.$field_id_html.'" type="hidden" value="'.@$this->fields[$field].'"  />';

					}elseif($display_as=='edit'){
						$html_fld .='<input id="'.$field_id_html.'" name="'.$field_name_html.'" type="text" value="'.@$this->fields[$field].'" '.$Class.' '.@$ExtraAttributes.' />';
					}elseif($display_as=='read-only'){
						$html_fld .=@$this->fields[$field].'<input id="'.$field_id_html.'" name="'.$field_name_html.'" type="hidden" value="'.@$this->fields[$field].'" '.$Class.' '.@$ExtraAttributes.' />';
					}
		 	}
		 	break;
		}
			
		return $html_fld;
			
	} // print_form_field()


	/**
	 *@package db_record
	 *@method get_table()
	 *@desc returns the table name
	 *@since v0.1 beta
	 * */
	private function get_table(){
		$t =  get_class($this);
		return $t;
	} // get_table
	# returns the table name
	/**
	 *@package db_record
	 *@method get_id_table()
	 *@desc returns the primary key id field name
	 *@since v0.1 beta
	 * */
	private function get_id_table(){

		$pri_key_id=$this->db_obj->get_primary_key_id();
		$id_field_name = $pri_key_id['Field'];
		return $id_field_name;
			
	} // get_table
	/**
	 *@package db_record
	 *@method find($Id,$FindMode );
	 *@desc finds a record from a given id
	 *@param $Id
	 *@param $FindMode
	 *@var  $FindMode  [first | prev | next | last | where]
	 *@return  Integer  num_rows found
	 *@since v0.1 beta
	 * */
	public function find($id,$FindMode = NULL){
		//Define Find Mode
		if($FindMode!=NULL){
			$this->FindMode = $FindMode;
		}
		$this->fields[$this->id_field_name] = $id;
		self::get_fields();
		return $this-> num_rows;
			
	}
	/**
	 * @package db_record :: Active Record
	 * @method  find_where($Where)
	 * @example $dbRecord->find_where('id_record = "3" AND other_field = "2" ');
	 * @return  Integer  num_rows found
	 * @desc    Llena el arraglo record->fields[] con los valores del primer registro
	 *          encontrado
	 *          y devuelve el total de registros encontrados
	 * */
	public function find_where($Where){

		//Define Find Mode
		$this->FindMode = 'where';
		$this->SqlWhere= ' WHERE ( '.$Where.' )';
		self::get_fields();
		return $this-> num_rows;

	}

	/**
	 * @package db_record
	 * @method  inserted_id()
	 * @desc returns the inserted id of last query
	 * @since v0.1 beta
	 * */
	public function inserted_id(){
		return $this->db_obj->inserted_id();
	}

	/**
	 * @package db_record
	 * @method  and_condition()
	 * @desc sets sql AND contition
	 * @since v0.1 beta
	 * */
	public function and_condition($and){
		return $this->SqlAnd=$and;
	}


}