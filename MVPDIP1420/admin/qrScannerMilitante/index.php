<?php
	@session_start();  
	include __DIR__."/../functions/security.php";
	setcookie("qr",'CevZPrOV', array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));


	include '../functions/tool_xhpzab.php';
	$id_seccion_ine_ciudadano = decrypt_ab_checkFinal($_COOKIE['paguinaId_2']);
	if(!empty($id_seccion_ine_ciudadano)){
		include __DIR__."/../functions/secciones_ine_ciudadanos.php";
		$seccion_ine_ciudadanoDatos = seccion_ine_ciudadanoDatos($id_seccion_ine_ciudadano);
		if(!empty($seccion_ine_ciudadanoDatos)){
			// Palabra clave para encriptar y desencriptar
			$palabra_clave = "sistemaRadarAB";
			// Algoritmo de encriptación
			$algoritmo = "AES-256-CBC";
			// Vector de inicialización
			$iv = 'AB';
			$otra_variable = $seccion_ine_ciudadanoDatos["id"];
			$otra_variable = urlencode(openssl_encrypt($otra_variable, $algoritmo, $palabra_clave, 0, $iv));
			$seccion_ine_ciudadanoDatos['expediente'] = $otra_variable;
		}
	}
	
?>
	<title>Create</title>
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
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> /
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Escáner Militante</font>
				</label><br>
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>