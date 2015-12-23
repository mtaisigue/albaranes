<?
class LoginController extends AppController{
	
	public function index(){
		$this->Load->View('admin/login.php');
	}
	public function invalido(){
		$Data['msg'] = 'Usuario o Contrase&ntilde;a inv&aacute;lidos';
		$this->Load->View('admin/login.php', $Data);
	}
	public function ex_login(){
		$this->Load->Model('Usuario');
		$U = new Usuario;
		
		$email = $_POST['Usuario'];
		$pass = $_POST['Contrasena'];
		
		$U->where = ' email = "'.$email.'"';
		$usuario = $U->verUsuariosWhere();
				
		if($usuario[0]['password'] == md5($pass)){
			$_SESSION['id_usuario'] = $usuario[0]['id_usuario'];
			$_SESSION['id_tipo'] = $usuario[0]['id_tipo'];;
			header('location:'.fk_link('paquetes'));
		}else{
			header('location:'.fk_link('login/invalido/'));
		}
	}
	
	public function ex_logout(){
		unset($_SESSION['id_usuario']);
		header('location:'.fk_link('login/'));
	}
	
}
?>