<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/campanas_sms.php";
	include __DIR__."/../functions/campanas_sms_cuerpos.php";
	include __DIR__."/../functions/tablas_relacionadas.php";
	include '../functions/usuario_permisos.php';
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}
	echo $redirectSecurity=redirectSecurity($id,'campanas_sms','campanasSMS','index');
	if($redirectSecurity!=""){
		die;
	}

	$campana_smsDatos=campana_smsDatos($id);
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','campañas_sms',$_COOKIE["id_usuario"]);

	$campana_sms_cuerpoDatos = campana_sms_cuerpoDatos('',$id);

	$tablasRelacionadas = tablasRelacionadas('campanas_sms',$id);
	$registros_titutlo = 'Este registro esta ligado a : <br>';
	foreach ($tablasRelacionadas['tablas'] as $key => $value) {
		if($value['registros']>0 && $value['tabla']!='campanas_sms_cuerpos' && $value['tabla']!='campanas_sms_programadas' && $value['tabla']!='campanas_sms_cartografias' && $value['tabla']!='campanas_sms_tipos_ciudadanos' && $value['tabla']!='campanas_sms_tipos_categorias_ciudadanos' && $value['tabla']!='campanas_sms_encuestas' ){
			$registros_tablas .= ' - '.$value['comentario'].' con '.number_format($value['registros'],0,'',',').' registro(s) <br>';
		}
	}

	if($campana_smsDatos['tipo']==1){
		$tipo_campana ='Bienvenida';
	}elseif ($campana_smsDatos['tipo']==2) {
		$tipo_campana ='Programada';
	}else{
		$tipo_campana ='Encuesta';
	}
?>
	<title>Delete </title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="campanasSMS/index.php";
			dataString = 'urlink='+urlink; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			$("#homebody").load(urlink);
		}

		function guardar() {
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");
			var id = '<?= $id?>';
			if(id == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var mensajeDelete = '<?= $registros_tablas ?>';
			if(mensajeDelete != ''){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html('Tiene registros relacionados no se puede borrar el registro.');
				document.getElementById("mensaje").classList.add("mensajeError"); 
				return false;
			}
			//var dataString = 'id=<?=$id;?>';
			var dataString = 'id=<?=$id;?>';
			$.ajax({
				type: "POST",
				url: "campanasSMS/db_delete.php",
				data: dataString,
				success: function(data) { 
					if(data=="SI"){
						document.getElementById("mensaje").classList.remove("mensajeError"); 
						$("#mensaje").html("&nbsp;");
						$("#mensaje").html("Eliminado con exito."); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						urlink="campanasSMS/index.php";
						dataString = 'urlink='+urlink; 
						$.ajax({
							type: "POST",
							url: "functions/backarray.php",
							data: dataString,
							success: function(data) { 	}
						});
						$("#homebody").load(urlink);

					}else{
						document.getElementById("sumbmit").disabled = false;
						$("#mensaje").html(data);
						document.getElementById("mensaje").classList.add("mensajeError"); 
					}
					//$("#homebody").load('temaslist.php'); 
				}
			});
		}
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#mensaje").click(function(event) { 
				document.getElementById("mensaje").classList.remove("mensajeSucces");
				document.getElementById("mensaje").classList.remove("mensajeError");
				$("#mensaje").html("&nbsp");
			});
		});
	</script>
	<div class="bodymanager" id="bodymanager"> 
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Eliminar Campaña SMS</font>
				</label>
			</div>
		</div>
		<div class="bodyinput">
			<label class="labelForm" id="labeltemaname">Campaña SMS</label><br>
			<label class="descripcionForm">
				<strong><?= $campana_smsDatos['nombre']?></strong>
			</label><br>
			<label class="labelForm" id="labeltemaname">Tipo</label><br>
			<label class="descripcionForm">
				<strong><?= $tipo_campana ?></strong>
			</label><br>

			<label class="labelForm" id="labeltemaname">Cuerpo</label><br>
			<?=	$campana_sms_cuerpoDatos['cuerpo'];	?>
			<br><br>
			<div style="width: 100%"></div>
			<?php
			if($registros_tablas!=''){
				echo '<div class="mensajeWarning"><font style="font-size:10px">*Usted debe borrar antes los registros relacionados.</font><br>'.$registros_titutlo.'';
				echo '<b>'.$registros_tablas.'</b>';
				echo '</div><br>';
			}
			if( $moduloAccionPermisos['delete'] || $moduloAccionPermisos['all']){
				?>
				<input type="button" id="sumbmit" onclick="guardar()" value="SI">
				<?php
			}
			?>
			<input type="button" onclick="cerrar()" value="NO">
		</div>
	</div>