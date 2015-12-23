<?php
class Usuario{
	public $id_usuario = 0;
	public $id_usuario_2 = 0;
	public $usuario = '';
	public $nombre = '';
	public $apellidos = '';
	public $password = '';
	public $email = '';
	public $id_perfil = 0;
	public $fecha_reg = '';
	public $random = '';
	public $tipo = 0;
	public $id_tipo = 0;
	public $where = '';
	public $activo = 0;

	public $access = '';
	public $id_priv = 0;

	public function verUsuario(){
		$db = new db;
		$db-> connect();
		$C = new ActiveRecord('usuarios');
		$C-> find($this->id_usuario);
		$db-> close();
		return $C-> fields;
	}
	
	public function copiarPermisos(){
		$db = new db;
		$db-> connect();
		$db2 = new db;
		$db2-> connect();
		$query = 'DELETE FROM fk_perfiles_privs WHERE id_usuario = '.$this->id_usuario;
		$db->query($query);
		$query = 'SELECT * FROM fk_perfiles_privs WHERE id_usuario = '.$this->id_usuario_2;
		$db->query($query);
		while($r = $db->next()){
			$query = 'INSERT INTO fk_perfiles_privs (id_usuario,id_priv,access) 
			VALUES('.$this->id_usuario.','.$r['id_priv'].',"'.$r['access'].'")';
			$db2->query($query);
		}
	}
	
	public function verUsuariosWhere(){
		$db = new db;
		$db-> connect();
		$query = 'SELECT * FROM usuarios';
		if($this->where != ''){
			$query .= ' WHERE '.$this->where;
			$query .= ' AND ';
		}else{
			$query .= ' WHERE ';
		}
		$query .= ' email != "ahmedrm89@gmail.com" ';

		$db->query($query);
		$Arr = array();
		while($r = $db->next()){
			$Arr[] = $r;
		}
		$db-> close();
		return $Arr;
	}
	
	public function Modificar(){
		$data = $this->VerUsuario();

		$data = $this->VerUsuario();
		$db = new db();
		$db-> connect();
		$C = new ActiveRecord('usuarios');
		
		$C-> fields['nombre'] = $this->nombre;
		$C-> fields['apellidos'] = $this->apellidos;
		$C-> fields['tipo'] = 1;
		$C-> fields['id_tipo'] = $this->id_tipo;
		if($this->usuario != '')
			$C-> fields['usuario'] = $this->usuario;
		if($this->password != '')
			$C-> fields['password'] = md5($this->password);
		$C-> fields['email'] = $this->email;
		if($this->id_perfil != 0)
			$C-> fields['id_perfil'] = $this->id_perfil;

		
		if($data){
			$C-> fields['id_usuario'] = $this->id_usuario;
			$C-> update();
		}else{
			$C-> fields['fecha_reg'] = $this->fecha_reg;
			$C-> insert();
		}
		$db-> close();
	}
	
	public function UpdatebyRandom(){
		$db = new db;
		$db->connect();
		
		$update = 'UPDATE usuarios set random = "0" , activo = 1 WHERE email="'.$this->email.'"';	
		$db-> query($update);
		
	}
	public function ModificarAdmin(){
		$data = $this->VerUsuario();
		$db = new db();
		$db-> connect();
		$C = new ActiveRecord('usuarios');
		$C-> fields['usuario'] = $this->usuario;
		$C-> fields['nombre'] = $this->nombre;
		$C-> fields['apellidos'] = $this->apellidos;
		$C-> fields['id_tipo'] = $this->id_tipo;
		
		$un = strtoupper($this->nombre[0].$this->apellidos[0]);
		
		$x = '';
		do{
			$username = $un.$x;
			$sql = 'SELECT * FROM usuarios WHERE usuario = "'.$username.'"';
			if($data){
				$sql .= ' AND id_usuario != '.$this->id_usuario;
			}
			$db->query($sql);
			$f = $db->next();
			$x++;
		}while($f != 0);
		$C-> fields['usuario'] = $username;
		

		if($this->password != '')
			$C-> fields['password'] = md5($this->password);
		$C-> fields['email'] = $this->email;
		if($this->id_perfil != 0)
			$C-> fields['id_perfil'] = $this->id_perfil;
		if($data){
			$C-> fields['id_usuario'] = $this->id_usuario;
			$C-> update();
		}else{
			$C-> fields['fecha_reg'] = $this->fecha_reg;
			$C-> insert();
		}
		$db-> close();
	}
	
	public function Eliminar(){
		$data = $this->VerUsuario();
		$db = new db();
		$db->connect();
		$C = new ActiveRecord('usuarios');
		$C->fields['id_usuario'] = $this->id_usuario;
		$C->delete();
		$db->close();
	}
	public function EliminarAdmin(){
		$db = new db();
		$db-> connect();
		$C = new ActiveRecord('usuarios');
		$C-> fields['id_usuario'] = $this->id_usuario;
		$C-> delete();
		$db-> close();
	}
	
	public function updateram(){
		$db = new db();
		$db-> connect();
	
		$update = 'UPDATE usuarios set random="'.$this->random.'" WHERE email="'.$this->email.'"';	
		$db-> query($update);

	}
	public function updatepass(){
		$db = new db();
		$db-> connect();
		$update = 'UPDATE usuarios set password="'.$this->password.'", random="0" WHERE email="'.$this->email.'"';	
		$db-> query($update);

	}

	public function verPrivilegiosUsuario(){
		$db = new db;
		$db-> connect();
		$dbr = new db;
		$dbr-> connect();
		$query = 'SELECT * FROM fk_privileges';
		$db->query($query);
		$Arr = array();
		while($r = $db->next()){
			$query = 'SELECT * FROM fk_perfiles_privs WHERE id_usuario = '.$this->id_usuario.' AND id_priv = '.$r['id_priv'];
			
			$r['privilege_desc'] = utf8_encode($r['privilege_desc']);
			$dbr->query($query);
			$r2 = $dbr->next();
				
			$r['access'] = $r2['access'];
			
			$Arr[] = $r;
		}
		$db-> close();
		return $Arr;
	}
	public function verUsuarioPriv(){
		$db = new db();
		$db-> connect();
		$query = 'SELECT * FROM fk_perfiles_privs WHERE id_usuario = '.$this->id_usuario.' AND id_priv = '.$this->id_priv;
		$db->query($query);
		$r = $db->next();
		return $r;
	}
	public function EliminarUsuarioPriv(){
		$db = new db();
		$db-> connect();
		$db->query('DELETE FROM fk_perfiles_privs WHERE id_usuario = '.$this->id_usuario.' AND id_priv = '.$this->id_priv);
		$db-> close();
	}
	public function CrearUsuarioPriv(){
		$db = new db();
		$db-> connect();
		$C = new ActiveRecord('fk_perfiles_privs');
		$C-> fields['id_usuario'] = $this->id_usuario;
		$C-> fields['id_priv'] = $this->id_priv;
		$C-> fields['access'] = $this->access;
		$C-> insert();
		$db-> close();
	}
	public function TakePermisosUsuario(){
		$db = new db;
		$db->connect();
		$query = 'SELECT * FROM fk_perfiles_privs WHERE id_usuario = '.$this->id_usuario;
		$db->query($query);
		$Arr = array();
		while($r = $db->next()){
			$Arr[] = $r;
		}
		return $Arr;
	} 

}
?>