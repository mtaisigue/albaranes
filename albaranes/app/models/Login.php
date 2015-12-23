<?php
// Model Login
class Login{
	function authenticate($U,$P, $recordar = 0, $by='usuario'){
		$RESULT  = false;

		if(trim($U)!='' && trim($P)!=''){
			$db = new db();
			$db->connect();
			$sql = ' SELECT * FROM usuarios
						 WHERE ( '.$by.' = "'.mysql_real_escape_string($U).'" )
						 AND   ( password = "'.md5($P).'" )
						 ';
			$db->query($sql);
			
			

			// no existe
			$RESULT = false;
				
			while($record = $db->next()){
				// LOGEAR

				$this->creaSession($record);
				$RESULT = true;
				
				if($recordar){
					$two_months = time() + (30*24*3600);
					setcookie ('id_usuario', $U, $two_months);
					setcookie ('contrasena', $P, $two_months);
				}
			}
			$db->close();
		}
		return $RESULT;
	} // end auth

	function creaSession($data){
		$_SESSION['fecha']      = date('d-m-Y');
		$_SESSION['hora']       = date('h:i:s a');
		$_SESSION['id_usuario'] = $data['id_usuario'];
		$_SESSION['usuario'] = $data['usuario'];
		$_SESSION['nombre'] = $data['nombre'];
		$_SESSION[Security::$sess_var_logged] = true;
	}

	public function cerrarSession(){
		$_SESSION[Security::$sess_var_logged]=false;
		$this->killCookies();
		session_destroy();		
	}
	
	function killCookies(){
		setcookie ('id_usuario', "", time() - 3600);
		setcookie ('contrasena', "", time() - 3600);
	}

	public function go_to_url(){
		if(isset($_POST['url']) && $_POST['url']!=''){
			$url=decode($_POST['url']);
		 }else{
	 		$url=HTTP;
		 }

		 header("Location: " . $url);
		 return NULL;
	}
	
	/***
	Compara las cookies con el usuario en la Tabla
	si coinciden crea las variables de sesion
	***/
	public function TestCookies(){
		if(isset($_COOKIE['id_usuario']) && isset($_COOKIE['contrasena'])){
			if(!authenticate($_COOKIE['id_usuario'],$_COOKIE['contrasena'])){
				$this->killCookies();
			}
			$this->creaSession($record);
		}
	}

} // fin class
?>