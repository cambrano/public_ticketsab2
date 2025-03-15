<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../functions/campanas_whatsapp.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET['cot'])){
		$id_seccion_ine_ciudadano=$_GET['cot'];
		setcookie("paguinaId_2",encrypt_ab_check($id_seccion_ine_ciudadano), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id_seccion_ine_ciudadano = decrypt_ab_checkFinal($_COOKIE['paguinaId_2']);
	}


	if($id_seccion_ine_ciudadano!=""){
		$id_seccion_ine_ciudadano;
		$seccion_ine_ciudadanoDatos = seccion_ine_ciudadanoDatos($id_seccion_ine_ciudadano);
		$nombre_completo = $seccion_ine_ciudadanoDatos['nombre_completo'];
		if($nombre_completo==""){
			echo $redirectSecurity=redirectSecurity('','secciones_ine_ciudadanos','seccionesIneCiudadanos','index');
			if($redirectSecurity!=""){
				die;
			}
		}
	}else{
		echo $redirectSecurity=redirectSecurity($id_seccion_ine_ciudadano,'secciones_ine_ciudadanos','seccionesIneCiudadanos','index');
		if($redirectSecurity!=""){
			die;
		}
	}

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_encuestas',$_COOKIE["id_usuario"]);

	?>
	<title>Encuestas</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / 
		<div class="submenux" onclick="subSeccionesIneCiudadanos()">Ciudadanos</div> /
		<?php
		if(!empty($_COOKIE['subPage'])){
			echo '<div class="submenux" onclick="subSeccionesIneCiudadanosSeccion()">Ciudadanos Sección</div> /';
		}
		?>
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
			Campañas Whatsapp
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<input type="button" value="Historial de mensajes" onClick="vermas(<?= $id_seccion_ine_ciudadano ?>);"> 
		</div><br><br>
		<div> <?php include "filtros.php"; ?></div>
		<div style="clear: both;"></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>