<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/tipos_ciudadanos_secciones_avance_semaforo.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/tipos_ciudadanos.php";
	include __DIR__."/../functions/plataformas.php";
	include '../functions/usuario_permisos.php';  
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}
	$id_seccion_ine = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	validar_plataforma_vista($id_seccion_ine,'secciones_ine','seccionesIneCiudadanosSeccionesAvanceSemaforo','index',$codigo_plataforma);
	echo $redirectSecurity=redirectSecurity($id,'tipos_ciudadanos_secciones_avance_semaforo','tiposCiudadanosSeccionesAvanceSemaforo','index');
	if($redirectSecurity!=""){
		die;
	}
	$tipo_ciudadano_seccion_avance_semaforoDatos=tipo_ciudadano_seccion_avance_semaforoDatos($id,$id_seccion_ine);
	$id = $tipo_ciudadano_seccion_avance_semaforoDatos['id'];

	$seccion_ineDatos = seccion_ineDatos($id_seccion_ine);
	$tipo_ciudadanoDatos = tipo_ciudadanoDatos($tipo_ciudadano_seccion_avance_semaforoDatos['id_tipo_ciudadano']);

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_secciones_avance_semaforo',$_COOKIE["id_usuario"]);
?>
	<title>Delete</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="tiposCiudadanosSeccionesAvanceSemaforo/index.php";
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
			var id = '<?= $id ?>'; 
			if(id == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var dataString = 'id=<?=$id;?>';  
			$.ajax({
				type: "POST",
				url: "tiposCiudadanosSeccionesAvanceSemaforo/db_delete.php",
				data: dataString,
				success: function(data) { 
					if(data=="SI"){
						document.getElementById("sumbmit").disabled = true;
						document.getElementById("mensaje").classList.remove("mensajeError"); 
						$("#mensaje").html("&nbsp;");
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						urlink="tiposCiudadanosSeccionesAvanceSemaforo/index.php";
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
		<?php
		if( $moduloAccionPermisos['delete'] == false && $moduloAccionPermisos['all'] == false ){
			?>
			<script type="text/javascript">
				document.getElementById("mensaje").classList.add("mensajeError");
				$("#mensaje").html("No tiene permiso");
				urlink="home.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				$("#homebody").load(urlink);
			</script>
			<?php
			die;
		}
		?>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Eliminar Semáforo Tipo Ciudadano En Sección </font>
				</label><br>
				
			</div>
		</div>
		<div class="bodyinput">
			<br>
			<label class="labelForm" id="labeltemaname">Sección</label><br>
			<label class="descripcionForm">
				<strong><?=$seccion_ineDatos['clave']; ?></strong>
			</label><br><br>
			<label class="labelForm" id="labeltemaname">Tipo Ciudadano</label><br>
			<label class="descripcionForm">
				<strong><?=$tipo_ciudadanoDatos['nombre']; ?></strong>
			</label><br><br>
			<font style="font-size: 15px;"><strong></strong></font>
			<?php
			if( $moduloAccionPermisos['delete'] || $moduloAccionPermisos['all']){
				?>
				<input type="button" id="sumbmit" onclick="guardar()" value="SI">
				<?php
			}
			?>
			<input type="button" onclick="cerrar()" value="NO">
		</div>
	</div>