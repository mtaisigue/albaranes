<?php
class Funcionalidades{
	
	public $id_priv=0;
	public $privilege_desc='';
	public $privilege_help='';	
	public $id_mode_priv=0;
	public $id_controller=0;
	public $id_action=0;
	public $table_name='';
	public $field_name='';
		
	public function verFuncionalidades(){
		$db = new db;
		$db-> connect();
		$query = 'SELECT * FROM fk_privileges';
		$db->query($query);
		$Arr = array();
		while($r = $db->next()){
			$r['privilege_desc'] = utf8_encode($r['privilege_desc']);
			$Arr[] = $r;
		}
		$db-> close();
		return $Arr;
	}
}
?>