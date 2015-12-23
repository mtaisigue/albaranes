<?
class Albaranes{
	public $id_albaranes = 0; 	 	 	 	 	 	
	public $fecha = 0;			 	 	 	 	 	 	
	public $operacion = '';	 	 	 	 	 	 	 
	public $guia = '';	 	 	 	 	 	 	 
	public $remitente = ''; 	 	 	 	 	 	 
	public $beneficiario = ''; 	 	 	 	 	 
	public $documento = '';	 	 	 	 	 	 	 
	public $pais= 0; 	 	 	 	 	 	
	public $direccion = ''; 	 	 	 	 	 	 
	public $ciudad	= 0;		 	 	 	 	 	 	
	public $telefono = ''; 	 
	public $descripcion	= '';			 	 	 	 	 	 	 
	public $peso = 0;	 	 	 	 	 	 	
	public $comision = 0;		 	 	 	 	 	 	
	public $seguro = 0;		 	 	 	 	 	 	
	public $iv = 0;		 	 	 	 	 	 	
	public $total = 0;		 	 	 	 	 	 	
	public $direccion_agencia = '';
	public $pag = 0;
	
	public function ContarPaginas(){
		$db = new db;
		$db-> connect();
		$query = 'SELECT COUNT(*) AS total FROM albaranes';
		$db->query($query);
		$registros = $db->next(); 
		return $registros['total'];
	}
	public function VerAlbaranes(){
		if($this->pag < 1){
			$this->pag = 1;
		}
		$p = ($this->pag-1)*10;
		$db = new db;
		$db2 = new db;
		$db-> connect();
		$db2-> connect();
		$query = 'SELECT * FROM albaranes ';
		
		if($_SESSION['id_tipo'] == 1){
			$query .= ' WHERE id_usuario = '.$_SESSION['id_usuario'];
		}else{
			$query .= ' WHERE 1 ';
		}
		
		if(isset($_POST['id_albaranes']) && trim(@$_POST['id_albaranes']) != '')
			$query .= ' AND id_albaranes = '.$_POST['id_albaranes'];
		else
			$query .= ' ORDER BY id_albaranes desc LIMIT '.$p.',10';
			
		
		$db->query($query);
		$Arr = array();
		while($r = $db->next()){
			$q = 'SELECT * FROM usuarios WHERE id_usuario = '.$r['id_usuario'];
			if($db2->query($q)){
				$u = $db2->next();
				$r['usuario'] = $u['nombre'].' '.$u['apellidos'];
			}else{
				$r['usuario'] = '';
			}
			$Arr[] = $r;
		}
		$db-> close();
		return $Arr;
	}
	public function VerDetalleAlbaranes(){
		$db = new db;
		$db-> connect();
		$query = 'SELECT * FROM albaranes WHERE id_albaranes = '.$this->id_albaranes;
		$db->query($query);
		$r = $db->next();
		$db-> close();
		return $r;
	}
	public function GuardarAlbaranes(){
		$db = new db;
		$db-> connect();
		$C = new ActiveRecord('albaranes');
		$C-> fields["id_usuario"] = $_SESSION['id_usuario'];
		$C-> fields["fecha"] = $this->fecha;
		$C-> fields["operacion"] = $this->operacion;
		$C-> fields["guia"] = $this->guia;
		$C-> fields["remitente"] = $this->remitente;
		$C-> fields["beneficiario"] = $this->beneficiario;
		$C-> fields["documento"] = $this->documento;
		$C-> fields["pais"] = $this->pais;
		$C-> fields["direccion"] = $this->direccion;
		$C-> fields["ciudad"] = $this->ciudad;
		$C-> fields["telefono"] = $this->telefono;
		$C-> fields["descripcion"] = $this->descripcion;
		$C-> fields["peso"] = $this->peso;
		$C-> fields["comision"] = $this->comision;
		$C-> fields["seguro"] = $this->seguro;
		$C-> fields["iv"] = $this->iv;
		$C-> fields["total"] = $this->total;
		$C-> fields["direccion_agencia"] = $this->direccion_agencia;
		$C-> insert();
		$db-> close();
	}
	
	public function EliminarAlbaranes(){
		$db = new db();
		$db-> connect();
		$C = new ActiveRecord('albaranes');
		$C-> fields['id_albaranes'] = $this->id_albaranes;
		$C-> delete();
		$db-> close();	
	}
}
?>