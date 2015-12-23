<?php
class Perfil{
	public $id_perfil = 0;
	public $id_priv = 0;
	public $access = 0;
	public $nombre_perfil = '';
	public $descripcion = '';
	
	public function verPerfil(){
		$db = new db;
		$db-> connect();
		$C = new ActiveRecord('fk_perfiles');
		$C-> find($this->id_perfil);
		$db-> close();
		return $C-> fields;
	}
	public function verPerfiles(){
		$db = new db;
		$db-> connect();
		$query = 'SELECT * FROM fk_perfiles';
		$db->query($query);
		$Arr = array();
		while($r = $db->next()){
			$Arr[] = $r;
		}
		$db-> close();
		return $Arr;
	}
	
	public function verPrivilegios(){
		$db = new db;
		$db-> connect();
		$query = 'SELECT * FROM fk_privileges ORDER BY orden ASC';
		$db->query($query);
		$Arr = array();
		while($r = $db->next()){
			$Arr[] = $r;
		}
		$db-> close();
		return $Arr;
	}
	
	public function verPrivilegiosPerfil(){
		$db = new db;
		$db-> connect();
		$dbr = new db;
		$dbr-> connect();
		$query = 'SELECT * FROM fk_privileges ORDER BY orden ASC';
		$db->query($query);
		$Arr = array();
		while($r = $db->next()){
			$query = 'SELECT * FROM fk_perfiles_privs WHERE id_usuario = '.$this->id_perfil.' AND id_priv = '.$r['id_priv'];
			
			$r['privilege_desc'] = utf8_encode($r['privilege_desc']);
			$dbr->query($query);
			$r2 = $dbr->next();
				
			$r['access'] = $r2['access'];
			
			$Arr[] = $r;
		}
		$db-> close();
		return $Arr;
	}
	
	public function Modificar(){
		$data = $this->VerPerfil();
		$db = new db();
		$db-> connect();
		$C = new ActiveRecord('fk_perfiles');
		$C-> fields['nombre_perfil'] = $this->nombre_perfil;
		$C-> fields['descripcion'] = $this->descripcion;
		
		if($data){
			$C-> fields['id_perfil'] = $this->id_perfil;
			$C-> update();
		}else{
			$C-> insert();
		}
		$db-> close();
	}
	
	public function Eliminar(){
		$db = new db();
		$db-> connect();
		$C = new ActiveRecord('fk_perfiles');
		$C-> fields['id_perfil'] = $this->id_perfil;
		$C-> delete();
		$db-> close();
	}
	public function verPerfilPriv(){
		$db = new db();
		$db-> connect();
		$query = 'SELECT * FROM fk_perfiles_privs WHERE id_usuario = '.$this->id_perfil.' AND id_priv = '.$this->id_priv;
		$db->query($query);
		$r = $db->next();
		return $r;
	}
	public function CrearPerfilPriv(){
		$db = new db();
		$db-> connect();
		$C = new ActiveRecord('fk_perfiles_privs');
		$C-> fields['id_usuario'] = $this->id_perfil;
		$C-> fields['id_priv'] = $this->id_priv;
		$C-> fields['access'] = $this->access;
		$C-> insert();
		$db-> close();
	}
	public function EliminarPerfilPriv(){
		$db = new db();
		$db-> connect();
		$db->query('DELETE FROM fk_perfiles_privs WHERE id_usuario = '.$this->id_perfil.' AND id_priv = '.$this->id_priv);
		$db-> close();
	}
}
?>