<?
class IndexController extends AppController{
public function index(){
	header('location:'.fk_link('paquetes'));
	}
}
?>