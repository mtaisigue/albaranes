<?

class UsuariosController extends AppController{
	public function seguridad(){
		$this->page_title('Albaranes | Usuarios');
		$this->page_description('');
		$this->page_keywords('');
		if(!isset($_SESSION['id_usuario']) || $_SESSION['id_usuario'] == 0){
			$this->Load->Model('Login');
			$Login = new Login;
			$Login->TestCookies();
		}
		if($_SESSION['id_tipo'] != 2)
			header('location:'.fk_link('paquetes'));
		
	}
	public function index(){
		self::seguridad();
		$this->Load->Model(array('Usuario','Perfil'));
		$U = new Usuario;
	
		$Administradores = $U->verUsuariosWhere();
		$Data = array(
			'DataUsuarios' => $Administradores,
			'title' => 'Administradores',
			'pefiles' => array(),
		);
		
		fk_header();
		$this->Load->View('admin/menu.php');
		$this->Load->View('admin/administradores.php', $Data);
		fk_footer();
	}
	
	public function administradores(){
		self::seguridad();
		$this->Load->Model(array('Usuario','Perfil'));
		$U = new Usuario;
		$P = new Perfil;
		$Administradores = $U->verUsuariosWhere();
		$Data = array(
			'DataUsuarios' => $Administradores,
			'title' => 'Administradores',
			'pefiles' => $P->verPerfiles()
		);
		
		fk_header();
		$this->Load->View('admin/menu.php');
		$this->Load->View('admin/administradores.php', $Data);
		fk_footer();
	}
		public function nuevousuario(){
			self::seguridad();
			$this->Load->Model(array('Perfil','Usuario'));
			$P = new Perfil;
			$Data['action'] = 'Nuevo';
			$Data['url_action'] = 'ex_admin';
			$Data['perfiles'] = $P->verPerfiles();
			$U = new Usuario;
			$U->where = 'id_usuario != 0';
			$Data['usuarios'] = $U->verUsuariosWhere();
			fk_header();
			$this->Load->View('admin/menu.php');
			$this->Load->View('admin/admin_formedit.php', $Data);
			fk_footer();
		}
			public function editusuario(){
				self::seguridad();
				$this->Load->Model(array('Usuario', 'Perfil'));
				$U = new Usuario;
				$U->id_usuario = $this->PermaLinkVars[0];
				$P = new Perfil;
				$Data['perfiles'] = $P->verPerfiles();
				$Data['action'] = 'Editar';
				$Data['url_action'] = 'ex_admin';
				$Data['user'] = $U->verUsuario();
				$U->where = 'id_usuario != 0';
				$Data['usuarios'] = $U->verUsuariosWhere();
				fk_header();
				$this->Load->View('admin/menu.php');
				$this->Load->View('admin/admin_formedit.php', $Data);
				fk_footer();
			}
			
			public function ex_admin() {
    			self::seguridad();
				$this->Load->Model('Usuario');
				$U = new Usuario;
				$msg = '';
				
				$nombre = trim($_POST['nombre']);
				$apellido = trim($_POST['apellidos']);
				
				$U->nombre = $nombre;
				$U->apellidos = $apellido;
				$U->password = $_POST['password'];
				$U->email = trim($_POST['email']);
				$U->id_perfil = $_POST['id_perfil'];
				$U->permisos_from = $_POST['permisos_from'];
				$U->id_tipo = $_POST['id_tipo'];
				$U->tipo = 1;
				
				if($U->apellidos == '')
					$msg .= 'El campo Apellido no puede estar vac&iacute;o<br />';
				if($U->email == '')
					$msg .= 'El Correo Electr&oacute;nico no puede estar vac&iacute;o<br />';
				if(($_POST['password']!= '' || !isset($_POST['id_usuario'])) && strlen($_POST['password']) < 5)
					$msg .= 'La Contrase&ntilde;a no puede tener menos de 5 caracteres<br />';
				else if($_POST['password'] != $_POST['confirm-password'])
					$msg .= 'Las Contrase&ntilde;as no coinciden<br />';
				if($U->id_perfil == 0)
					$msg .= 'Asegurese de elegir un perfil<br />';
					
				if($msg != ''){
					echo $msg;
				}else{
					if(isset($_POST['id_usuario'])){
						$id_usuario = $_POST['id_usuario'];
						$U->id_usuario = $id_usuario;
					}else{
						$stru = substr(trim($_POST['nombre']),0,1);
						$stru .= substr(trim($_POST['apellidos']),0,1);
						$U->usuario = $stru;
						$U->fecha_reg = date('Y-m-d');
						
					}
					
					$U->ModificarAdmin();
					if(!isset($_POST['id_usuario'])){
						$U->where = 'email = "'.$U->email.'"';
						$us = $U->verUsuariosWhere();
						$id_usuario = $us[0]['id_usuario'];
					}
					
					if($_POST['permisos_from'] != 0){
						$U->id_usuario = $_POST['permisos_from']; 
						$perm = $U->TakePermisosUsuario();
						foreach ($perm as $PE) {
							$U->access = $PE['access'];
							$U->id_priv = $PE['id_priv'];
							$U->id_usuario = $id_usuario;
							$U->EliminarUsuarioPriv();
							$U->CrearUsuarioPriv();	
						}
						
					}
					if(!isset($_POST['ajax']))
						header('location:'.fk_link('usuarios/'));
				}
		    } // End ex_admin
		public function ex_deleteusuario(){
			self::seguridad();
			$this->Load->Model('Usuario');
			$U = new Usuario;
			$U->id_usuario = $this->PermaLinkVars[0];
			$U->Eliminar();
			header('location:'.fk_link('usuarios/'));
		}
}

?>