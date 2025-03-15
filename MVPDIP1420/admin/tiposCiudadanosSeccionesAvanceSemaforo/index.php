<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/plataformas.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET['cot'])){
		$id_seccion_ine=$_GET['cot'];
		setcookie("paguinaId_1",encrypt_ab_check($id_seccion_ine), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id_seccion_ine = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	}

	validar_plataforma_vista($id_seccion_ine,'secciones_ine','seccionesIneCiudadanosSeccionesAvanceSemaforo','index',$codigo_plataforma);
	if($id_seccion_ine!=""){
		$id_seccion_ine;
		$seccion_ineDatos = seccion_ineDatos($id_seccion_ine);
		$nombre_completo = $seccion_ineDatos['numero'];
		if($nombre_completo==""){
			echo $redirectSecurity=redirectSecurity('','secciones_ine','seccionesIneCiudadanosSeccionesAvanceSemaforo','index');
			if($redirectSecurity!=""){
				die;
			}
		}
	}else{
		echo $redirectSecurity=redirectSecurity($id_seccion_ine,'secciones_ine','seccionesIneCiudadanosSeccionesAvanceSemaforo','index');
		if($redirectSecurity!=""){
			die;
		}
	}

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_secciones_avance_semaforo',$_COOKIE["id_usuario"]);
?>
	<title>Seguimientos</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / 
		<div class="submenux" onclick="subSemaforoSecciones()">Semáforo secciones</div> / 
		<br>
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<?php
			if(empty($moduloAccionPermisos)){
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
		<h2><?= $nombre_completo ?> </h2>
		<label class="tituloForm">
			Semáforo Tipos Ciudadanos
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
				if( $moduloAccionPermisos['insert'] || $moduloAccionPermisos['all']){
					?>
					<input type="button" value="Nuevo semáforo tipo ciudadano" onClick="add();"> 
					<?php
				}
			?>
		</div>
		<br><br>
		<div></div>
		<div style="clear: both;"></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>