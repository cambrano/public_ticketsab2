<?php

	include __DIR__."/../../functions/security.php";
	include __DIR__."/../../functions/redirect_security.php";
	include __DIR__."/../../functions/genid.php";
	include __DIR__."/../../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../../functions/encuestas.php";
	include __DIR__."/../../functions/cuestionarios.php";
	include __DIR__."/../../functions/cuestionarios_respuestas.php";
	include __DIR__."/../../functions/secciones_ine_ciudadanos_encuestas.php";
	include __DIR__."/../../functions/secciones_ine_ciudadanos_encuestas_respuestas.php";
	include __DIR__."/../../functions/timemex.php";
	include '../../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}

	$id_encuesta = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	$id_seccion_ine_ciudadano_encuesta = $id;

	$encuestaDatos = encuestaDatos($id_encuesta);


	$cuestionariosDatos = cuestionariosDatos('',$id_encuesta);
	$cuestionario_respuestasIdDatos = cuestionario_respuestasIdDatos('','',$id_encuesta,' hi.orden asc ');

	$seccion_ine_ciudadano_encuestaDatos = seccion_ine_ciudadano_encuestaDatos($id_seccion_ine_ciudadano_encuesta,$id_encuesta,'');
	if($seccion_ine_ciudadano_encuestaDatos['id']==''){
		if($seccion_ine_ciudadano_encuestaDatos['clave']==""){
			$seccion_ine_ciudadano_encuestaDatos['clave'] = 'ENCC-'.$tran_cod;
		}
		$claveF['input'] = 'disabled="disabled"';
		$seccion_ine_ciudadano_encuestaDatos['fecha'] = $fechaSF;
		$seccion_ine_ciudadano_encuestaDatos['hora'] = $fechaSH;
	}


	$id_seccion_ine_ciudadano=$seccion_ine_ciudadano_encuestaDatos['id_seccion_ine_ciudadano'];


	$seccion_ine_ciudadanoDatos = seccion_ine_ciudadanoDatos($id_seccion_ine_ciudadano);

	
	$seccion_ine_ciudadano_encuesta_respuestasIdDatos = seccion_ine_ciudadano_encuesta_respuestasIdDatos('',$id_seccion_ine_ciudadano_encuesta,$id_seccion_ine_ciudadano,$id_encuesta,'');

	foreach ($cuestionariosDatos as $key => $value) {
		$cuestionariosDatos[$key]['respuesta'] = $seccion_ine_ciudadano_encuesta_respuestasIdDatos[$value['id']]['x']['respuesta'];
		$cuestionariosDatos[$key]['id_seccion_ine_ciudadano_encuesta_respuesta'] = $seccion_ine_ciudadano_encuesta_respuestasIdDatos[$value['id']]['x']['id'];
	}
	foreach ($cuestionario_respuestasIdDatos as $key => $value) {
		foreach ($value as $keyT => $valueT) {
			$cuestionario_respuestasIdDatos[$key][$keyT]['respuesta_selected']=$seccion_ine_ciudadano_encuesta_respuestasIdDatos[$key][$valueT['id']]['respuesta'];
			$cuestionario_respuestasIdDatos[$key][$keyT]['id_seccion_ine_ciudadano_encuesta_respuesta']=$seccion_ine_ciudadano_encuesta_respuestasIdDatos[$key][$valueT['id']]['id'];
			if($seccion_ine_ciudadano_encuesta_respuestasIdDatos[$key][$valueT['id']]['respuesta']!=""){
				$cuestionario_respuestasIdDatos[$key][$keyT]['checked']='checked="checked"';
			}
		}
	}
?>
	<title>Ciudadano Encuesta</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="encuestasSecciones/municipio/seccion.php";
			dataString = 'urlink='+urlink; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			$("#homebody").load(urlink);
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
	<div class="bodymanager" id="bodymanager" style="display: table;">
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Ciudadano Encuesta</font>
				</label><br>
				<h2><?= $seccion_ine_ciudadanoDatos['nombre_completo']; ?></h2>
				<h3><?= $encuestaDatos['nombre']; ?></h3>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Por favor, complete el siguiente formulario para encuesta .</font><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
			</div>
		</div> 
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>
