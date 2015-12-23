<?php
/****************************************************************************************
 **  fkControl : FkDataTable
 **  Version   : 0.0.1
 **  Author    : Mmendoza000 (Sep-2010)
 **  Licence   : GPL
 **
 **  Descripcion: Control Grid  para FreeKore
 **  proyecto    fecha      por         descripcion
 **  ----------  ---------  ----------- ----------------
 **  00000001    22/09/10   mmendoza    Creado.
 *****************************************************************************************/

class FkDataTable
{

	private $sLimit = ""; # Paging
	private $sOrder = ""; # Orderinf
	private $sWhere	= ""; # Where
	private $sQuery = ""; # Query
	private $sql_query    = ""; # sql

	private $sql_fields = '';
	private $sql_table = '';
	private $sql_where = '';
	private $sql_order = '';
	private $arr_col_db = array();	// Array de columnas de la tabla
	private $special_cols = array(); // Para columnas especiales
	private $arr_columns = array();	// Array final de colunmas

	private $Action='';
	private $CurrentPage='';

	public function __construct(){


		if(@$_GET['ajax']=='1'){
			$Act = 'show-json';
			fk_no_display_header();
		}else{
			$Act = 'show-table';
		}
		$this->Action = $Act;


	}

	public function Render($GetCurrentPage){

		$this->CurrentPage = $GetCurrentPage;

		$result = '';

		switch ($this->Action) {
			case 'show-json':
				// Show Json data
				$result = $this-> generateJSON();
					
				break;
			case 'show-table':
				$result = $this-> showTable();
				break;
		}

		echo $result;

	} // render()
	private function generateJSON(){
		// no mostrar errores como warnings, ya que afecta el resultado y marca error en {json}
		ini_set('display_errors', 0);
			
		// MySQL connection
		$db = new	db();
		$db->connect();
		// Get Columns
		$this->get_columns();
		//Limit
		$this->setLimit();

		//Ordering
		$this->setOrder();

		//Filtering
		$this->setFilter();

		$this->sQuery = "SELECT SQL_CALC_FOUND_ROWS ".$this->sql_fields."
			             FROM   ".$this->sql_table." ".$this->sWhere." ".$this->sOrder." ".$this->sLimit." ;";
			
		$db->query( $this->sQuery );

		$out_ini='';
		$out_regs='';
		$out_fin='';
		#--------------------------------------
		# REGISTROS
		#--------------------------------------

		while ( $aRow = $db->next() )
		{
			$out_regs .= "[";
			foreach($this->arr_columns as $k=>$col){

				if(isset($col['type']) && @$col['type']=='special'){
					$out_regs .= '"'.addslashes($this->procesa_columnas_esp($col,$aRow)).'",';
				}else{
					$out_regs .= '"'.addslashes($aRow[$col]).'",';
				}

			}
			$out_regs = substr_replace( $out_regs, "", -1 );

			$out_regs .= "],";
		}
		$out_regs = substr_replace( $out_regs, "", -1 );


		#--------------------------------------
		# TOTALES
		#--------------------------------------

		$this->sQuery = "SELECT FOUND_ROWS()";

		$db->query( $this->sQuery );
		$aResultFilterTotal = $db->next();
		$iFilteredTotal = $aResultFilterTotal[0];

		$this->sQuery = "
			SELECT COUNT(*)
			FROM   ".$this->sql_table."
		";

		$db->query( $this->sQuery );
		$aResultTotal = $db->next();
		$iTotal = $aResultTotal[0];



		$out_ini .= '{';
		$out_ini .= '"sEcho": '.intval(@$_GET['sEcho']).', ';
		$out_ini .= '"iTotalRecords": '.$iTotal.', ';
		$out_ini .= '"iTotalDisplayRecords": '.$iFilteredTotal.', ';
		$out_ini .= '"aaData": [ ';

		#--------------------------------------
		# Cerrar cadena output
		#--------------------------------------
		$out_fin .= '] }';


		#--------------------------------------
		# FORTAMEAR output
		#--------------------------------------

		$sOutput = $out_ini . $out_regs . $out_fin;

		return $sOutput;

	}

	private function showTable(){
		// Obtener columnas
		$this->get_columns();
		 
		//pa($this-> arr_columns);
		
				 
		?>
<table cellpadding="0" cellspacing="0" border="0" class="display"
	id="videosGrid">
	<thead>
		<tr>
		<?php 
		foreach($this->arr_columns as $k=>$col){
			if(isset($col['type']) && @$col['type']=='special'){
				?><th></th><?php 
			}else{
				?><th><?php echo $col;?></th><?php
			}
		}
		
		?>
		
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="4" class="dataTables_empty">Loading data from server</td>
		</tr>
	</tbody>
</table>
<script language="javascript" type="text/javascript">
$(document).ready(function() {	
	
	

	$('#videosGrid').dataTable({
		    "bJQueryUI": true,
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": HTTP+"<?php echo $this->CurrentPage;?>?",
			"sPaginationType": "full_numbers",
			"aaSorting": [[ 0, "desc" ]],
			"iDisplayLength": 10,
			"fnServerData": function ( sSource, aoData, fnCallback ) {
				/* Add some extra data to the sender */
				aoData.push( { "name": "ajax", "value": "1" } );
				$.getJSON( sSource, aoData, function (json) { 
					/* Do whatever additional processing you want on the callback, then tell DataTables */
					fnCallback(json);
				} );
			}
		} );
   
} );
</script>
		<?php
		 
		 
	}
	# sql source
	public function sql_source($sql){

		$this->sql_query = $sql;
		$this->set_query_estructure();



	} // sqlSource

	private function set_query_estructure(){

		$sql =  trim( strtolower($this->sql_query));

		$sql_fields_table = explode('from',$sql);
		$sql_fields_1 = trim($sql_fields_table[0]);
		$sql_fields = trim($sql_fields_1,'select');
		$sql_fields = trim($sql_fields);
		$sql_table = trim($sql_fields_table[1]);
		$sql_table_where = explode("where",$sql_table);

		$sql_table = $sql_table_where[0];

		//prev where
		$sql_prev_where = ( isset($sql_table_where[1]) ? $sql_table_where[1] : '' );

		$sql_order = '';
		$sql_where = '';

		if(trim($sql_prev_where)!=''){
			$sql_order_1 = explode("order by",$sql_prev_where);

			if(isset($sql_order_1[1])){
				$sql_order = 'order by '.$sql_order_1[1] ;
				$sql_where = 'where '.$sql_order_1[0] ;
			}

		}

		if(trim( $sql_where ) == ''){
			$sql_where = ( isset($sql_table_where[1]) ? 'where '.$sql_table_where[1] : '' );
		}


		// FIELDS
		$this->sql_fields = $sql_fields;

		// TABLE
		$this->sql_table = $sql_table;

		//WHERE
		$this->sql_where = $sql_where;

		//ORDER
		$this->sql_order = $sql_order;


	} // set_query_estructure(){
	#SET LIMIT
	private function setLimit(){
		if ( isset( $_GET['iDisplayStart'] ) )
		{
			$this->sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
			mysql_real_escape_string( $_GET['iDisplayLength'] );
		}
	}

	#SET ORDER
	private function setOrder(){

		if ( isset( $_GET['iSortCol_0'] ) )
		{
			// PETICION DE ORDER
			$this->sOrder = "order by ";
			for ( $i=0 ; $i<mysql_real_escape_string( $_GET['iSortingCols'] ) ; $i++ )
			{
				$this->sOrder .= $this->fnColumnToField(mysql_real_escape_string( $_GET['iSortCol_'.$i] ))."
					".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
			}
			$this->sOrder = substr_replace( $this->sOrder, "", -2 );
		}else{
			//  ORDER	por default
			if($this->sql_order != '' ){
				$this->sOrder = $this->sql_order;
			}
		}
	}

	#Filtering
	private function setFilter(){

		if(trim($this->sql_where) != ''){
			$this->sWhere = $this->sql_where.' ';
			$W_A = ' and ';
		}else{
			$this->sWhere = '';
			$W_A = ' where ';
		}


		if ( @$_GET['sSearch'] != "" )
		{
			$this->sWhere .= $W_A." ( ".$this->getSearchWhere()." )";
		}


	}
	// GENERAR WHERE DE CONSULTA CON SEARCH
	private function getSearchWhere(){
			
		$whereStr = '';
		if(count($this->arr_col_db)>0){
			foreach($this->arr_col_db as $k=>$v){
				$whereStr .= $v." LIKE '%".mysql_real_escape_string( trim($_GET['sSearch']) )."%' OR ";
			}
			$whereStr = substr_replace( $whereStr, "", -3 );
		}

		return $whereStr;

	}

	private function fnColumnToField( $i ){

		return $this->arr_col_db[$i];

	}
	private function procesa_columnas_esp($col,$rec){
		$i = 0;
			
		foreach($rec as $k => $v){
			$col['val'] = str_replace('{'.$i.'}',$v, $col['val']);
			$i ++;
		}

		$rs = $col['val'];
		return $rs;

	} // End procesa_columnas_esp

	private function get_columns(){

		$flds1 = str_replace(' ','',$this->sql_fields);
		$columns = explode(',',$flds1);
		$this->arr_col_db = $columns;

		$tot_nr = count($columns); // Columnas normales
		$tot_sp = count($this->special_cols); // Columnas especiales

		for($i=0;$i<$tot_sp;$i++){
			$indx = $tot_nr + ($i);
			$columns[$indx]['type']='special';
			$columns[$indx]['val']=$this->special_cols[$i];
		}

		$this->arr_columns = $columns;


	} // End get_columns

	public function addCol($htmlVal){

		$this->special_cols[] = $htmlVal;

	} // End addCol

}

?>