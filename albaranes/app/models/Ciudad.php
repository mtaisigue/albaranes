<?
class Ciudad{
	public $id_estado = 0;
	public $estado = '';
	public $id_pais = 0;
	
	function verCiudadPais(){
		$db = new db;
		$db->connect();
		$query = 'SELECT * FROM lista_estados WHERE id_pais = '.$this->id_pais;
		$db->query($query);
		$Arr = array();
		while($r = $db->next()){
			$Arr[] = $r;
		}
		return $Arr;
		$db->close();
	}
}
?>