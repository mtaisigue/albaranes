<?
class Pais{
	public $country_id = 0; 	 	 	 	 	 	
 	public $name = '';	 	 	 	 	 	 	 
	public $iso_code_2 = '';	 	 	 	 	 	 	 
	public $iso_code_3 = ''; 	 	 	 	 	 	 
	public $address_format = ''; 	 	 	 	 	 
	public $postcode_required= 0; 	 	 	 	 	 	
	public $status	= 0;		 	 	 	 	 	 	
	
	public function VerPais(){
		$db = new db;
		$db-> connect();
		$query = 'SELECT * FROM country ORDER BY country_id asc';
		$db->query($query);
		$Arr = array();
		while($r = $db->next()){
			$Arr[] = $r;
		}
		$db-> close();
		return $Arr;
	}
	public function VerDetallePais(){
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
}
?>