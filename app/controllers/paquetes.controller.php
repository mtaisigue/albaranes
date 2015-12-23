<?
class PaquetesController extends AppController{
	function seguridad(){
		if(!isset($_SESSION['id_usuario'])){
			header('location:'.fk_link('login/'));
			die();
		}
	}

/*-------------------------------------------
--------------ALBARANES--------------------
-------------------------------------------	*/
	
	function index(){
		fk_js_addLink(fk_theme_url().'/js/jquery.printPage.js');
		$this->page_title('Albaranes');
		$this->seguridad();
		$this->Load->Model(array('Albaranes','Usuario'));
		$enter = new Albaranes;
		$U = new Usuario;
		$enter->pag = $this->PermaLinkVars[0];
		$albaranes = $enter->VerAlbaranes();
		$pagina = $enter->ContarPaginas();
		$numero = ceil($pagina/10);
		$Data = array(
			'albaranes'=>$albaranes, 
			'pag'=>$numero, 
			'pagactual' => $this->PermaLinkVars[0],
			'usuarios' => $U->verUsuariosWhere()
			);
		
		fk_header();
		$this->Load->View('albaranes/albaranes.php', $Data);
		fk_footer();
	}
	function detalle_albaranes(){
		//proceso de programacion
		$this->seguridad();
		$this->page_title('Albaranes | Detalle');
		$this->Load->Model('Albaranes');
		$enter = new Albaranes;
		$enter->id_albaranes = $this->PermaLinkVars[0];
		$detalle_albaranes = $enter->VerDetalleAlbaranes();
		
		Pais::$id_pais = $detalle_albaranes['pais'];
		$p = Pais::verPais();
		Pais::$id_estado = $detalle_albaranes['ciudad'];
		$c = Pais::verEstado();
		$detalle_albaranes['p_nombre'] = $p['pais'];
		$detalle_albaranes['e_nombre'] = $c['estado'];
		//vistas en html
		fk_header();		
		if($_SESSION['id_tipo'] == 2 || $detalle_albaranes['id_usuario'] == $_SESSION['id_usuario'])
			$this->Load->View('albaranes/detalle_albaranes.php', array('detalle_albaranes'=>$detalle_albaranes));		
		fk_footer();
	}
	function nuevo_registro(){
		//proceso de programacion
		$this->seguridad();
		$pais = Pais::verPaises();
		//vistas en html
		fk_js_addLink(fk_theme_url().'/js/cargar_ciudad.js');
		fk_header();		
		$this->Load->View('albaranes/nuevo_registro.php', array('pais'=>$pais));
		fk_footer();
	}
	function combo_ciudad(){
		$this->Load->Model('Ciudad');
		$C = new Ciudad;
		$C->id_pais = $this->PermaLinkVars[0];
		$Arr = $C->verCiudadPais();
		$this->Load->View('albaranes/cmb_ciudad.php', array('id_pais'=>$C->id_pais,'combo' => $Arr));
	}

	function guardar_albaranes(){
		//proceso de programacion
		$this->seguridad();
		$msg = '';
		
		if(!is_numeric($_POST['peso'])){
			$msg = 'El campo Peso debe contener un valor numm&eacute;rico';
		}else if(!is_numeric($_POST['comision'])){
			$msg = 'El campo Comisi&oacute;n debe contener un valor numm&eacute;rico';
		}else if(!is_numeric($_POST['seguro'])){
			$msg = 'El campo Seguro debe contener un valor numm&eacute;rico';
		}else if(!is_numeric($_POST['iv'])){
			$msg = 'El campo IV debe contener un valor numm&eacute;rico';
		}else if(!is_numeric($_POST['total'])){
			$msg = 'El campo Total debe contener un valor numm&eacute;rico';
		}
		
		if($msg != ''){
			die($msg);
		}
		else{
			$this->Load->Model('Albaranes');
			$enter = new Albaranes;
			list($d,$m,$y) = explode('/',$_POST["fecha"]);
			$enter->fecha= strtotime($y.'-'.$m.'-'.$d);
			$enter->operacion=$_POST["operacion"];
			$enter->guia=$_POST["guia"];
			$enter->remitente=$_POST["remitente"];
			$enter->beneficiario=$_POST["beneficiario"];
			$enter->documento=$_POST["documento"];
			$enter->pais=$_POST["pais"];
			$enter->direccion=$_POST["direccion"];
			$enter->ciudad=$_POST["ciudad"];
			$enter->telefono=$_POST["telefono"];
			$enter->descripcion=$_POST["descripcion"];
			$enter->peso=$_POST["peso"];
			$enter->comision=$_POST["comision"];
			$enter->seguro=$_POST["seguro"];
			$enter->iv=$_POST["iv"];
			$enter->total=$_POST["total"];
			$enter->direccion_agencia=$_POST["direccion_agencia"];
			$enter->GuardarAlbaranes();
		}		
	}
	
	function eliminar_albaranes(){
		$this->seguridad();
		$this->Load->Model('Albaranes');
		$enter = new Albaranes;
		$enter->id_albaranes = $this->PermaLinkVars[0];
		$enter->EliminarAlbaranes();	
		header('location:'. fk_link('paquetes/index'));
	}
	function imprimir_albaranes(){
		$this->seguridad();
		$this->Load->Model('Albaranes');
		$enter = new Albaranes;
		$enter->id_albaranes = $this->PermaLinkVars[0];
		$detalle_albaranes = $enter->VerDetalleAlbaranes();
		//vistas en html
			
		$this->Load->View('albaranes/imprimir_albaranes.php', array('detalle_albaranes'=>$detalle_albaranes));		
	}
	function busqueda(){
		$this->seguridad();
		$this->Load->Model('Albaranes');
		$A = new Albaranes;
		$A->id_albaranes = $_POST['id_albaranes'];
		$vista = $A->BuscarAlbaranes();
		fk_header();
		$this->Load->View('albaranes/albaranes.php', array('albaranes'=>$vista));
		fk_footer();
	}
}
?>