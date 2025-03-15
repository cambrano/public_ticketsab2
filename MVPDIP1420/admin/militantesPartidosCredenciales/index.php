<?php
	@session_start();
	include '../functions/tool_xhpzab.php';
	include '../functions/efs.php';
	if($_GET['refresh']==1){
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		$id_militante_partido = $_GET['cot'];
		setcookie("paguinaId",encrypt_ab_check($id_militante_partido), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		die;
	}else{
		$id_militante_partido = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}

	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../functions/militantes_partidos.php";
	include __DIR__."/../functions/plataformas.php";
	
	validar_plataforma_vista($id_militante_partido,'militantes_partidos','seccionesIneCiudadanos','index',$codigo_plataforma);

	if($id_militante_partido!=""){
		$id_seccion_ine_ciudadano;
		$militante_partidoDatos = militante_partidoDatos($id_militante_partido);
		$id_seccion_ine_ciudadano = $militante_partidoDatos['id_seccion_ine_ciudadano'];
		$seccion_ine_ciudadanoDatos = seccion_ine_ciudadanoDatos($id_seccion_ine_ciudadano);
	}else{
		echo $redirectSecurity=redirectSecurity($id_militante_partido,'militantes_partidos','seccionesIneCiudadanos','index');
		if($redirectSecurity!=""){
			die;
		}
	}
	
	$permiso="insert";
	?>
	<title>Ciudadano Categoría</title>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#mensaje").click(function(event) { 
				document.getElementById("mensaje").classList.remove("mensajeSucces");
				document.getElementById("mensaje").classList.remove("mensajeError");
				$("#mensaje").html("&nbsp");
			});
		});
	</script>
	<div class="bodymanager" id="bodymanager" style="display: table;"> 
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / 
		<?php
		if(!empty($_COOKIE['qr'])){
			echo '<div class="submenux" onclick="subQRScannerCiudadano()">Scanner QR Ciudadano</div> /';
		}else{
			echo '<div class="submenux" onclick="subSeccionesIneCiudadanos()">Ciudadanos</div> /';
		}
		?>
		<div class="submenux" onclick="subMilitantePartido()">Militantes</div> /
		<?php
		if(!empty($_COOKIE['subPage'])){
			echo '<div class="submenux" onclick="subSeccionesIneCiudadanosSeccion()">Ciudadanos Sección</div> /';
		}
		?>
		<br>
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Ciudadano Credencialización</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;"></font><br>
				</label><br>
				<h2><?= $nombre_completo ?> </h2>
				<font style="font-size: 15px;"><strong></strong></font>
			</div>
		</div> 
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>
