<?php
/****************************************************************************************
 **  fkControl : FkComments
 **  Version   : 0.0.1
 **  Author    : Mmendoza000 mmendoza000@hotmail.com (Sep-2010)
 **  Licence   : GPL
 **
 **  Descripcion: Control Comentarios  para FreeKore
 **  proyecto    fecha      por         descripcion
 **  ----------  ---------  ----------- ----------------
 **  00000001    24/09/10   mmendoza    Creado.
 *****************************************************************************************/

class FkComments
{
	private $page = '';
	private $id_obj = 'obj1';
	private $code = 'videos';
	private $id_tab_val = '0';


	private $allow_anonimo = false;
	private $auth_mode = false; //$auth_mode (1 [auto] | 2 [moderar] | 3 [ moderar anonimos])

	// Estructura tabla comentarios
	private $table = 'comentarios'; // tabla
	private $table_id_field	= 'id_comentario'; // id , llave primaria
	private $code_field = 'codigo_tabla'; // Codigo de referencia, que se comenta
	private $id_table2coment_field = 'id_val_tabla'; // Id referencia , id de lo que se comenta
	private $comment_field = 'comentario';	// texto del comentario
	private $name_field = 'nombre';	// nombre de quien comenta
	private $email_field = 'email';	// Email de quien comenta
	private $webpage_field = 'web_site';	// Pagina web de quien comenta







	public function __construct($CurrentPage = '',$IdObj = '',$Code='',$IdVal=''){
		try{
			// Page
			if($IdVal==''){
				$IdVal =( isset($_POST['id-t-val']) ? $_POST['id-t-val'] : '' );
			}

			if($CurrentPage=='' || $IdObj=='' || $Code=='' || $IdVal==''){
				throw new FkException(' Es requerido  {CurrentPage,IdObj,Code,idVal}');
			}else{
				$this->page = $CurrentPage;
				$this->id_obj = $IdObj;
				$this->code = $Code;
				$this->code = $Code;
				$this->id_tab_val = $IdVal;
			}
		}catch(FkException $e){
			$e->description=' fkComments() requiere variables para inicializacion';
			$e->solution='
			           Ej: new fkComments($this->getCurrentPage(),"miObj","comm-producto","1")<br><br>
					   $this->getCurrentPage() = la pagina actual;<br>
   					   "miObj" = El nombre que se le quiera dar al objeto;<br>
					   "comm-producto" = codigo referencia, pudiera ser el nombre de la tabla comentada;<br>
   					   "1" = id de referencia, que registro de la tabla fue comentado;					   
				 ';
			$e->show();
		}

	}
	public function render(){

		$oper = (isset($_POST['op'])) ? $_POST['op']: '';
		switch($oper){
			case 'save':
				#--------------------------
				# Guardar comments
				#--------------------------
				$comment = new db_record($this->table); // Crea dbRecord para la tabla Comentario
					

				$comment -> fields[$this->comment_field] = $_POST['comment'];
				$comment -> fields[$this->code_field] = $_POST['code'];
				$comment -> fields[$this->id_table2coment_field] = $_POST['id-t-val'];
				$comment -> fields[$this->name_field] = $_POST['name'];
				$comment -> fields[$this->email_field] = $_POST['email'];
				$comment -> fields[$this->webpage_field] = $_POST['web'];

				$comment -> save();

				// Encentra el ultimo y muestralo
				//$comment->findLast() :: crear esta function
				$db = new db();
				$db->connect();
				$sql = 'SELECT *,now() as ahora FROM '.$this->table.' WHERE '.$this->code_field.' =  "'.$this->code.'"
		             AND  '.$this->id_table2coment_field.' = "'.$this->id_tab_val.'"
					 ORDER BY '.$this->table_id_field.' DESC LIMIT 1 ;
					 ';

				$db->query( $sql);
				if($rec=$db->next()){ $this->printOneComent($rec); }

				break;
					
			default:
				#--------------------------
				# Load Comments
				#--------------------------
				?>
<ul id="fk-commenter-<?=$this->id_obj?>" class="fk-comments">
	<div id="comments-<?=$this->id_obj?>"><? $this->printComments(); ?></div>
	<div id="new-comments-<?=$this->id_obj?>"></div>
	<div id="leave-<?=$this->id_obj?>"><? $this->leaveAComment(); ?></div>
</ul>
<script language="javascript" type="text/javascript">
 fkComments('<?=$this->id_obj?>','<?=$this->page?>','<?=$this->code?>','<?=$this->id_tab_val?>');
 
</script>
				<?
				break;
		}

	} // render()

	private function printComments(){
		$db = new db();
		$db->connect();
		$sql = 'SELECT *,now() as ahora FROM '.$this->table.' WHERE '.$this->code_field.' =  "'.$this->code.'"
		             AND  '.$this->id_table2coment_field.' = "'.$this->id_tab_val.'"';
		$db->query($sql);
		while($rec=$db->next()){
			$this->printOneComent($rec);
		}


	} // End printComments()
	private function printOneComent($rec){
		?>
<li>
<div class="user-img"></div>
<div class="time">Hace <? fk_lapse_of_time($rec['fec_reg'],$rec['ahora']); ?></div>
<b><?=$rec[$this->name_field]?></b>: <?=$rec[$this->comment_field]?></li>
		<?
	}

	private function leaveAComment(){

		?>
<li class="leave-comment">
<div class="user-img"></div>
<table class="user-data">
	<tr>
		<td colspan="2">
		<div id="message-err-<?=$this->id_obj?>" class="fk-error-message"
			style="display: none"></div>
		</td>
	</tr>

	<tr>
		<td>Nombre(Requerido):</td>
		<td><input type="text" id="name-user-<?=$this->id_obj?>"
			name="name-user-<?=$this->id_obj?>" value="" /></td>
	</tr>
	<tr>
		<td>Email(Requerido):</td>
		<td><input type="text" id="email-user-<?=$this->id_obj?>"
			name="email-user<?=$this->id_obj?>" value="" /></td>
	</tr>
	<tr>
		<td>Sitio web:</td>
		<td><input type="text" id="web-user-<?=$this->id_obj?>"
			name="web-user<?=$this->id_obj?>" value="" /></td>
	</tr>
</table>
<table class="txt-data">
	<tr>
		<td><textarea id="leave-comment-<?=$this->id_obj?>"></textarea></td>
	</tr>
</table>


<a id="leave-comment-btn-<?=$this->id_obj?>" href="javascript:void(0)"
	class="btn-link1 btn">Comentar</a></li>
		<?


	} // End printComments()


}

?>