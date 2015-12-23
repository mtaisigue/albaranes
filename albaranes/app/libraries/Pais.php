<?
class Pais{
public static $id_pais = 0;	
public static $pais = '';

public static $id_estado = 0;
public static $estado = '';

//Seleccionar pais por id
	static public function verPais(){
		$db = new db;
		$db-> connect();
		$C = new ActiveRecord('lista_paises');
		$C-> find(self::$id_pais);
		$db-> close();
		return $C-> fields;
	}
	
//Seleccionar estado por id
	 static public function verEstado(){
		$db = new db;
		$db-> connect();
		$C = new ActiveRecord('lista_estados');
		$C-> find(self::$id_estado);
		$db-> close();
		return $C-> fields;
	}

//METOPDO PARA BUSCAR TODOS LOS PAISES
	static public function verPaises(){
		$db = new db;
		$db-> connect();
		$query = 'SELECT * FROM lista_paises';
		$db->query($query);
		$Arr = array();
		while($r = $db->next()){
			
			$Arr[] = $r;
		}
		$db-> close();
		return $Arr;
		
	} 
	
//METODO PARA BUSCAR LOS ESTADOS DE UN PAIS ESPECIFICO
	static public function verEstadosPais(){
		$db = new db;
		$db-> connect();
		$query = 'SELECT * FROM lista_estados WHERE id_pais = '.self::$id_pais; 
		$db->query($query);
		$Arr = array();
		while($r = $db->next()){
			$Arr[] = $r;
		}
		$db-> close();
		return $Arr;
	}
}

?>