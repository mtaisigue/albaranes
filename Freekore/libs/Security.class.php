<?php
class Security{
	private $allow_access = false;
	static $db_tbl_usuarios = 'usuarios'; // table usuarios
	static $db_fld_id_usuario = 'id_usuario'; // field id_usuario
	private static $sess_var_id_usuario = 'id_usuario';
	public static $sess_var_logged = 'logged_in';


	public function addSecurity($controller=NULL,$action=NULL){

		$cnt_error = 0;

		if(self::is_logged()==true){
			if($controller!=NULL && $action!=NULL){
				$idUser = isset($_SESSION[self::$sess_var_id_usuario])?$_SESSION[self::$sess_var_id_usuario] : 0 ;
				if(self::hasPriv_Screen($idUser,$controller, $action)==FALSE){
					$cnt_error++;
				}
			}
		}else{
			$cnt_error++;
		}

		if($cnt_error==0){
			$this->allow_access = true;
		}else{
			$this->allow_access = false;
		}
		// Ejecutar
		$this->run_seguridad();
			
	}

	// Ejecutar seguridad
	private function run_seguridad(){

		$ajax = isset($_POST['ajax']) ? $_POST['ajax'] : 0;
		if($this->allow_access===false){
			if($ajax == 1){
				echo '<div class="fk-error-message">Acceso denegado</div>';
			}else{
				fk_header();
				?>
<div class="fk-error-message">
<h3>Acceso denegado</h3>
El acceso a esta p&aacute;gina fue denegado</div>
				<?php
				fk_footer();
			}
			die();

		}
			
	} // run seguridad

	public static function is_logged(){
		$res = FALSE;
		if(isset($_SESSION[self::$sess_var_logged])){
			if($_SESSION[self::$sess_var_logged]==true){    $res = true;   }
		}
		return $res;
	} // is_logged

	public static function hasPriv_Screen($id_user,$controller,$action){

		$db = new db();
		$db->connect();
		$id_perfil = 0;
		$id_priv = 0;
		$id_mode_priv = 1; // Privilegios sobre: 1 Pantalla, 2 campo, 3 Pantalla y campo

		// si no hay controller o action en la db si tiene acceso
		$has_priv = TRUE;

		// Encontrar el privilegio
		$sql = 'SELECT p.id_priv
				FROM fk_controllers c, fk_controllers_action a, fk_privileges p
				WHERE c.controller =  "'.$controller.'"
				AND a.action =  "'.$action.'"
				AND c.id_controller = a.id_controller
				AND p.id_controller = c.id_controller
				AND p.id_action = a.id_action
				AND p.id_mode_priv ="'.$id_mode_priv.'"';

		$db->query($sql);
		if($rec=$db->next()){
			$id_priv=$rec['id_priv'];
		}

		// Si hay priv definido
		if($id_priv!=0){
			// Si existe el privilegio, por default el acceso es false
			$has_priv = FALSE;

			// Encontrar perfil del usuario
			$sql = 'SELECT id_perfil from '.self::$db_tbl_usuarios.'
			        where '.self::$db_fld_id_usuario.' = "'.$id_user.'" ';
			$db->query($sql);

			if($rec=$db->next()){
				$id_perfil=$rec[0];
			}

			// 1) encontrar priv de excepcion
			$sql = 'SELECT p_usr.permitir_acceso as access
		            FROM fk_privileges_usuarios p_usr 
		            WHERE p_usr.id_usuario = "'.$id_user.'"
		            AND p_usr.id_priv = "'.$id_priv.'"
		            LIMIT 1';
			$db->query($sql);

			if($rec=$db->next()){
				$acceso = $rec['access'];
			}else{
				//2) Si no hay registros de excepcion, buscar los del perfil
				// encontrar priv de perfil...

				$sql = 'SELECT p_pf.access as access
		            FROM fk_perfiles_privs p_pf
		            WHERE p_pf.id_perfil = "'.$id_perfil.'"
		            AND p_pf.id_priv = "'.$id_priv.'"
		            LIMIT 1
		            ';
				$db->query($sql);

				if($rec=$db->next()){
					$acceso = $rec['access'];
				}
					
			}

		}

		if(isset($acceso)){
			if($acceso!=0){
				$has_priv = true;
			}
		}

		return $has_priv;

	} // hasPriv_Screen

	public static function hasPriv_Field($id_user,$table,$field){


		$db = new db();
		$db->connect();
		$id_controller = 0;
		$id_accion = 0;
		$id_priv = 0;
		$id_perfil = 0;
		$id_mode_priv = 2; // Privilegios sobre: 1 Pantalla, 2 campo, 3 Pantalla y campo

		// si no hay nada que evite ver este campo default: tiene priv
		$has_priv['access'] = 1;
		$has_priv['read_only'] = 0;

		// Encontrar el privilegio
		$sql = 'SELECT p.id_priv
				FROM fk_privileges p
				WHERE p.id_mode_priv ="'.$id_mode_priv.'"
				AND p.table_name = "'.$table.'" 
		        AND p.field_name = "'.$field.'" LIMIT 1
				';
		$db->query($sql);


		if($rec=$db->next()){
			$id_priv = $rec['id_priv'];
		}

		if($id_priv!=0){
			// Si existe el privilegio, por default el acceso es false
			$has_priv['access'] = 0;
			$has_priv['read_only'] = 0;


			// Encontrar perfil del usuario
			$sql = 'SELECT id_perfil from '.self::$db_tbl_usuarios.'
			        where '.self::$db_fld_id_usuario.' = "'.$id_user.'" ';
			$db->query($sql);

			if($rec=$db->next()){
				$id_perfil=$rec[0];
			}

			// 1) encontrar priv de excepcion
			$sql = 'SELECT p_usr.permitir_acceso as access,solo_lectura as read_only
		            FROM fk_privileges_usuarios p_usr 
		            WHERE p_usr.id_usuario = "'.$id_user.'"
		            AND p_usr.id_priv = "'.$id_priv.'"
		            LIMIT 1';
			$db->query($sql);

			if($rec=$db->next()){
				$acceso = $rec['access'];
				$read_only = $rec['read_only'];
			}else{
				//2) Si no hay registros de excepcion, buscar los del perfil
				// encontrar priv de perfil...

				$sql = 'SELECT p_pf.access,p_pf.read_only
		            FROM fk_perfiles_privs p_pf 
		            WHERE p_pf.id_perfil = "'.$id_perfil.'"
		            AND p_pf.id_priv = "'.$id_priv.'"
		            LIMIT 1
		            ';
				$db->query($sql);

				if($rec=$db->next()){
					$acceso = $rec['access'];
					$read_only = $rec['read_only'];
				}
					
			}

			if(isset($acceso) && isset($read_only)){
				$has_priv['access'] = $acceso;
				$has_priv['read_only'] =$read_only;
			}


		}
			


		return $has_priv;

	} // hasPriv_Field

} // Seguridad


