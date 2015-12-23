<?php
class AdminController extends AppController{
	public function seguridad(){
		$this->page_title('Intactics | Administraci&oacute;n');
		$this->page_description('');
		$this->page_keywords('');
		if(!isset($_SESSION['id_usuario']) || $_SESSION['id_usuario'] == 0){
			$this->Load->Model('Login');
			$Login = new Login;
			$Login->TestCookies();
		}
		if(!leer_privilegios('sec','r'))
			header('location:'.fk_link('admin/login/'));
	}
	public function index(){
		self::seguridad();
		fk_header();
		$this->Load->View('admin/inicio.php');
		fk_footer();
	}
	
	public function login(){
		$this->page_title('Intactics | Administraci&oacute;n');
		fk_header();
		//$this->Load->View('admin/login.php');
		fk_footer();
	}
	
	public function usuarios(){
		self::seguridad();
		$this->Load->Model('Usuario');
		$U = new Usuario;
		$Administradores = $U->verUsuariosWhere();
		$Data = array(
			'DataUsuarios' => $Administradores,
			'title' => 'Usuarios'
		);
		
		fk_header();
		$this->Load->View('admin/menu.php');
		$this->Load->View('admin/usuarios.php', $Data);
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
		public function nuevoadmin(){
			self::seguridad();
			$this->Load->Model('Perfil');
			$P = new Perfil;
			$Data['action'] = 'Nuevo';
			$Data['url_action'] = 'ex_admin';
			$Data['perfiles'] = $P->verPerfiles();
		
			fk_header();
			$this->Load->View('admin/menu.php');
			$this->Load->View('admin/admin_formedit.php', $Data);
			fk_footer();
		}
			public function editadmin(){
				self::seguridad();
				$this->Load->Model(array('Usuario', 'Perfil'));
				$U = new Usuario;
				$U->id_usuario = $this->PermaLinkVars[0];
				$P = new Perfil;
				$Data['perfiles'] = $P->verPerfiles();
				$Data['action'] = 'Editar';
				$Data['url_action'] = 'ex_admin';
				$Data['user'] = $U->verUsuario();
			
				fk_header();
				$this->Load->View('admin/menu.php');
				$this->Load->View('admin/admin_formedit.php', $Data);
				fk_footer();
			}
		public function nuevousuario(){
			self::seguridad();
			$this->Load->Model('Perfil');
			$P = new Perfil;
			$Data['action'] = 'Nuevo';
			$Data['url_action'] = 'ex_usuario';
			$Data['perfiles'] = $P->verPerfiles();
		
			fk_header();
			$this->Load->View('admin/menu.php');
			$this->Load->View('admin/usuarios_formedit.php', $Data);
			fk_footer();
		}
			/*** Crear o Modificar Usuarios **/
			public function ex_usuario(){
				self::seguridad();
				$this->Load->Model('Usuario');
				$U = new Usuario;
				$msg = '';
				
				$U->usuario = trim($_POST['usuario']);
				$U->nombre = trim($_POST['nombre']);
				$U->apellidos = trim($_POST['apellidos']);
				$U->password = $_POST['password'];
				$U->email = trim($_POST['email']);
				$U->id_perfil = 0;
				
				$U->where = 'usuario = "'.$_POST['usuario'].'"';
				$UserName = $U->verUsuariosWhere();
				if(!empty($UserName))$UserName = $UserName[0];
				if(!empty($UserName) || (@$UserName['id_usuario'] != @$_POST['id_usuario']))
					$msg .= 'Este Usuario ya existe, por favor elija otro<br />';
					
				if($U->usuario == '')
					$msg .= 'El campo Usuario no puede estar vac&iacute;o<br />';
				if($U->email == '')
					$msg .= 'El Correo Electr&oacute;nico no puede estar vac&iacute;o<br />';
				if(($_POST['password']!= '' || !isset($_POST['id_usuario'])) && strlen($_POST['password']) < 5)
					$msg .= 'La Contrase&ntilde;a no puede tener menos de 5 caracteres<br />';
				else if($_POST['password'] != $_POST['confirm-password'])
					$msg .= 'Las Contrase&ntilde;as no coinciden<br />';

				
				if($msg != ''){
					echo $msg;
				}else{
					if(isset($_POST['id_usuario']))
						$U->id_usuario = $_POST['id_usuario'];
					else{
						$U->fecha_reg = date('Y-m-d');
					}
					$U->Modificar();
					if(!isset($_POST['ajax']))
						header('location:'.fk_link($_POST['location']));
				}
			}
			public function ex_admin() {
    			self::seguridad();
				$this->Load->Model('Usuario');
				$U = new Usuario;
				$msg = '';
				
				$U->usuario = trim($_POST['usuario']);
				$U->nombre = trim($_POST['nombre']);
				$U->apellidos = trim($_POST['apellidos']);
				$U->password = $_POST['password'];
				$U->email = trim($_POST['email']);
				$U->id_perfil = $_POST['id_perfil'];
				
				$U->where = 'usuario = "'.$_POST['usuario'].'"';
				$UserName = $U->verUsuariosWhere();
				if(!empty($UserName))$UserName = $UserName[0];
				if(!empty($UserName) || (@$UserName['id_usuario'] != @$_POST['id_usuario']))
					$msg .= 'Este Usuario ya existe, por favor elija otro<br />';
					
				if($U->usuario == '')
					$msg .= 'El campo Usuario no puede estar vac&iacute;o<br />';
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
					if(isset($_POST['id_usuario']))
						$U->id_usuario = $_POST['id_usuario'];
					else{
						$U->fecha_reg = date('Y-m-d');
					}
					$U->ModificarAdmin();
					if(!isset($_POST['ajax']))
						header('location:'.fk_link($_POST['location']));
				}
		    } // End ex_admin
		public function editusuario(){
			self::seguridad();
			$this->Load->Model(array('Usuario', 'Perfil'));
			$U = new Usuario;
			$U->id_usuario = $this->PermaLinkVars[0];
			$P = new Perfil;
			$Data['action'] = 'Editar';
			$Data['url_action'] = 'ex_usuario';
			$Data['user'] = $U->verUsuario();
		
			fk_header();
			$this->Load->View('admin/menu.php');
			$this->Load->View('admin/usuarios_formedit.php', $Data);
			fk_footer();
		}
		public function ex_deleteusuario(){
			self::seguridad();
			$this->Load->Model('Usuario');
			$U = new Usuario;
			$U->id_usuario = $this->PermaLinkVars[0];
			$U->Eliminar();
			header('location:'.fk_link('admin/usuarios'));
		}
		public function ex_deleteadmin(){
			self::seguridad();
			$this->Load->Model('Usuario');
			$U = new Usuario;
			$U->id_usuario = $this->PermaLinkVars[0];
			$DataUser = $U->verUsuario;
			$U->where = 'id_perfil = 4';
			if(count($U->verUsuariosWhere()) == 1 && $DataUser['id_perfil'] == 1){
				$msg = 'Para eliminar a este Usuario debe haber al menos otro administrador';
			}
			if($msg != ''){
				echo($msg);
			}else{
				$U->EliminarAdmin();
				header('location:'.fk_link('admin/administradores'));
			}
		}
		
	public function perfiles(){
		self::seguridad();
		fk_js_addLink(fk_theme_url().'/js/perfiles.js');
		$this->Load->Model('Perfil');
		$P = new Perfil;
		$perfiles = $P->verPerfiles();
		$Data = array('DataPerfiles' => $perfiles);
		//$Data['DataPerfiles'] = $perfiles;
		fk_header();
		$perfiles = $P->verPerfiles();
		$this->Load->View('admin/perfiles.php', $Data);
		fk_footer();
	}
		public function getprivilegios() {
			self::seguridad();
			$this->Load->Model('Perfil');
			$P = new Perfil;
			$P->id_perfil = @$_POST['id'];
			$Data['perfil'] = $P->verPerfil();
			$Data['privs'] = $P->verPrivilegiosPerfil();
			$this->Load->View('admin/privilegios.php', $Data);
    	}
		public function setprivilegios(){
			self::seguridad();
			$this->Load->Model('Perfil');
			$P = new Perfil;
			$P->id_perfil = @$_POST['perfil'];
			$P->id_priv = @$_POST['priv'];
			if($_POST['val'])
				$P->CrearPerfilPriv();
			else
				$P->EliminarPerfilPriv();
		}
		public function nuevoperfil(){
			self::seguridad();
			$Data['action'] = 'Nuevo';
			$Data['url_action'] = 'ex_perfil';
		
			fk_header();
			$this->Load->View('admin/menu.php');
			$this->Load->View('admin/perfil_formedit.php', $Data);
			fk_footer();
		}
			/*** Crear o Modificar Perfiles **/
			public function ex_perfil(){
				self::seguridad();
				$this->Load->Model('Perfil');
				$P = new Perfil;
				$msg = '';
				
				$P->nombre_perfil = trim($_POST['nombre_perfil']);

				if($P->nombre_perfil == '')
					$msg = 'El campo Nombre no puede estar vac&iacute;o';
				if($msg != ''){
					echo $msg;
				}else{
					if(isset($_POST['id_perfil']))
						$P->id_perfil = $_POST['id_perfil'];
					$P->Modificar();
					if(!isset($_POST['ajax']))
						header('location:'.fk_link($_POST['location']));
				}
			}
			
		public function editperfil(){
			self::seguridad();
			$this->Load->Model('Perfil');
			$P = new Perfil;
			$P->id_perfil = $this->PermaLinkVars[0];
			$Data['action'] = 'Editar';
			$Data['url_action'] = 'ex_perfil';
			$Data['perfil'] = $P->verPerfil();
		
			fk_header();
			$this->Load->View('admin/menu.php');
			$this->Load->View('admin/perfil_formedit.php', $Data);
			fk_footer();

		}
		public function ex_deleteperfil(){
			self::seguridad();
			if(!isset($_POST['id']))
				header('location:'.fk_link('admin/perfiles'));
			else{
				$this->Load->Model(array('Perfil', 'Usuario'));
				$P = new Perfil;
				$P->id_perfil = $_POST['id'];
				$U = new Usuario;
				$U->where = 'id_perfil = '.$P->id_perfil;
				$DataUser = $U->verUsuariosWhere();
				
				if(!empty($DataUser))
					die('No se puede eliminar este perfil porque tiene Administradores asociados');
				else		
					$P->Eliminar();
			}
		}
	
	public function ex_iniciar_sesion(){
		$this->Load->Model('Login');
		$Login = new Login;
		if(!$Login->authenticate($_POST['Usuario'], $_POST['Contrasena'], @$_POST['Recordar'])){
			echo 'Usuario o Contrase&ntilde;a incorrectos';
		}else if(! Security::hasPriv_Screen(@$_SESSION['id_usuario'],'admin','index')){
			echo 'El Usuario que ingres&oacute; no tiene los permisos para entrar a esta secci&oacute;n';
		}else if(!isset($_POST['ajax'])){
			header('location:'.fk_link('admin'));
		}
	}
	public function ex_cerrar_sesion(){
		$this->Load->Model('Login');
		$Login = new Login;
		$Login->cerrarSession();
		header('location:'.fk_link('admin'));
	}
	
	/**** Gershell ***/
	public function funcionalidades(){
			$this->Load->Model('Funcionalidades');
			$funct = new Funcionalidades;
			$r = $funct->verFuncionalidades();
			$array = array(
				"campos_func" => $r
			);
			fk_header();
			$this->Load->View('admin/funcionalidades.php',$array);
			fk_footer();		
	}
	
	//TERMINAN LOS CONTROLADORES DE COMENTARIOS
}
?>