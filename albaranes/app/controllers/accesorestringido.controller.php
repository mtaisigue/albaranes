<?php
class AccesorestringidoController extends AppController{
	public function index(){
		fk_header();
		$this->Load->View('admin/accesorestringido.php',$array);
		fk_footer();
	}
}
?>