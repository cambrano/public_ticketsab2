<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../functions/encuestas.php";
	include __DIR__."/../functions/cuestionarios.php";
	include __DIR__."/../functions/cuestionarios_respuestas.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos_encuestas.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos_encuestas_respuestas.php";
	include __DIR__."/../functions/timemex.php";
	
	@session_start();
	$_SESSION['Paguinasub']='encuestasDistritosLocales/update.php';
	if(!empty($_GET['id'])){
		$id_seccion_ine_ciudadano_encuesta=$_GET['id'];
		$_SESSION['id_seccion_ine_ciudadano_encuesta']=$id_seccion_ine_ciudadano_encuesta;
	}else{
		$id_seccion_ine_ciudadano_encuesta=$_SESSION['id_seccion_ine_ciudadano_encuesta']; 
	}
	echo $id_encuesta;
	echo $id_seccion_ine_ciudadano_encuesta=$_SESSION['id_seccion_ine_ciudadano_encuesta']; 

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
			$("#homebody").load('encuestasDistritosLocales/index.php');
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
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / 
		<div class="submenux" onclick="subEncuestas()">Encuestas</div> <br>
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
