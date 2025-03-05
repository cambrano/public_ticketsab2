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
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET)){
		$id_encuesta = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id_encuesta),time()+(60*60*24*650),"/",false);
	}else{
		$id_encuesta = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}
	$id_encuesta;
	$id_seccion_ine_ciudadanox = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);


	$encuestaDatos = encuestaDatos($id_encuesta);
	$seccion_ine_ciudadanoDatos = seccion_ine_ciudadanoDatos($id_seccion_ine_ciudadanox);

	$cuestionariosDatos = cuestionariosDatos('',$id_encuesta);
	$cuestionario_respuestasIdDatos = cuestionario_respuestasIdDatos('','',$id_encuesta,' hi.orden asc ');


	$seccion_ine_ciudadano_encuestaDatos = seccion_ine_ciudadano_encuestaDatos('',$id_encuesta,$id_seccion_ine_ciudadanox);
	if($seccion_ine_ciudadano_encuestaDatos['id']==''){
		if($seccion_ine_ciudadano_encuestaDatos['clave']==""){
			$seccion_ine_ciudadano_encuestaDatos['clave'] = 'ENCC-'.$tran_cod;
		}
		$claveF['input'] = 'disabled="disabled"';
		$seccion_ine_ciudadano_encuestaDatos['fecha'] = date("Y-m-d");
		$seccion_ine_ciudadano_encuestaDatos['hora'] = date("H:i:s");
	}

	$id_seccion_ine_ciudadano_encuesta = $seccion_ine_ciudadano_encuestaDatos['id'];
	$seccion_ine_ciudadano_encuesta_respuestasIdDatos = seccion_ine_ciudadano_encuesta_respuestasIdDatos('',$id_seccion_ine_ciudadano_encuesta,$id_seccion_ine_ciudadanox,$id_encuesta,'');

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
			urlink="seccionesIneCiudadanosEncuestas/index.php";
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

			var id = "<?= $id_seccion_ine_ciudadano_encuesta ?>"; 

			var id_seccion_ine_ciudadanox = "<?= $id_seccion_ine_ciudadanox ?>"; 
			if(id_seccion_ine_ciudadanox == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id Ciudadano Requerido requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_encuesta = "<?= $id_encuesta ?>"; 
			if(id_encuesta == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id Encuesta Requerido requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}


			var clave = document.getElementById("clave").value; 
			if(clave == ""){
				document.getElementById("clave").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Clave requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var fecha = document.getElementById("fecha").value; 
			if(fecha == ""){
				document.getElementById("fecha").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Fecha requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			if(!fechaValida(fecha)){ 
				document.getElementById("fecha").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Fecha Válida requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var hora = document.getElementById("hora").value; 
			if(hora == ""){
				document.getElementById("hora").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Hora requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var observaciones = document.getElementById("observaciones").value;

			var seccion_ine_ciudadano_encuesta = []; 
			var data = {    
					'id' : id,
					'id_encuesta' : id_encuesta,
					'id_seccion_ine_ciudadanox' : id_seccion_ine_ciudadanox,
					'clave' : clave,
					'fecha' : fecha,
					'hora' : hora,
					'observaciones' : observaciones,
				}
			seccion_ine_ciudadano_encuesta.push(data);

			var cuestionario = [];
			<?php
			foreach ($cuestionariosDatos as $key => $value) {
				echo 'var id_cuestionario = "'.$value['id'].'";';
				echo 'list=document.getElementsByName("input_'.$value['id'].'[]");';
				echo 'var numero = list.length;';
				echo 'var cantidad = "'.$value['cantidad'].'";';
				echo 'var campo = "'.$value['campo'].'";';
				echo 'var requerido = "'.$value['requerido'].'";';
				//echo 'console.log(numero);';
				?>
					if(campo=="text"){
						input_text_<?= $value['id'] ?> = document.getElementById("input_text_<?= $value['id'] ?>").value;
						if(input_text_<?= $value['id'] ?>=="" && requerido==1){
							document.getElementById("pre_<?= $value['id'] ?>").focus(); 
							document.getElementById("sumbmit").disabled = false;
							$("#mensaje").html("<?= $value['pregunta'] ?> requerido");
							document.getElementById("mensaje").classList.add("mensajeError");
							return false;
						}else{
							var data = {
								'id' : '<?= $value['id_seccion_ine_ciudadano_encuesta_respuesta'] ?>',
								'id_encuesta' : id_encuesta,
								'id_cuestionario' : id_cuestionario,
								'respuesta' : input_text_<?= $value['id'] ?>,
								'tipo' : 'text',
							}
							cuestionario.push(data);
						}
						
					}else{
						var seleccionado = 0;
						for (var i=0; i < numero; i++) {
							if (list[i].checked){
								var seleccionado = seleccionado + 1;
							}
						}
						if(seleccionado == 0 && requerido==1) {
							$('html, body').scrollTop( $("#main").offset().top);
							document.getElementById("pre_<?= $value['id'] ?>").focus(); 
							document.getElementById("sumbmit").disabled = false;
							$("#mensaje").html("Pregunta: <?= $value['pregunta'] ?><br> Usted debe seleccionar "+cantidad+" respuesta(s).");
							document.getElementById("mensaje").classList.add("mensajeError");
							return false;
						}else if (seleccionado < cantidad  && requerido==1 ) {
							$('html, body').scrollTop( $("#main").offset().top);
							document.getElementById("pre_<?= $value['id'] ?>").focus(); 
							document.getElementById("sumbmit").disabled = false;
							$("#mensaje").html("Pregunta: <?= $value['pregunta'] ?><br> Usted debe seleccionar "+cantidad+" respuesta(s).");
							document.getElementById("mensaje").classList.add("mensajeError");
							return false;
						}else if (seleccionado > cantidad  && requerido==1) {
							$('html, body').scrollTop( $("#main").offset().top);
							document.getElementById("pre_<?= $value['id'] ?>").focus(); 
							document.getElementById("sumbmit").disabled = false;
							$("#mensaje").html("Pregunta: <?= $value['pregunta'] ?><br> solo debe seleccionar "+cantidad+" respuesta(s).");
							document.getElementById("mensaje").classList.add("mensajeError");
							return false;
						}else{
							
						}
					}
				<?php
				foreach ($cuestionario_respuestasIdDatos[$value['id']] as $keyT => $valueT) {
					?>
					var input_<?= $valueT['id'] ?> = document.getElementById("input_<?= $valueT['id'] ?>").checked;
					var id_cuestionario_respuesta = "<?= $valueT['id'] ?>";
					if(input_<?= $valueT['id'] ?> == true){
						var input_<?= $valueT['id'] ?> = 1;
						var data = {
							'id' : '<?= $valueT['id_seccion_ine_ciudadano_encuesta_respuesta'] ?>',
							'id_encuesta' : id_encuesta,
							'id_cuestionario' : "<?= $valueT['id_cuestionario'] ?>",
							'id_cuestionario_respuesta' : id_cuestionario_respuesta,
							'respuesta' : input_<?= $valueT['id'] ?>,
						}
						cuestionario.push(data);
					}else{
						var id = "<?= $valueT['id_seccion_ine_ciudadano_encuesta_respuesta'] ?>";
						if(id !=""){
							var input_<?= $valueT['id'] ?> = 0;
							var data = {
								'id' : '<?= $valueT['id_seccion_ine_ciudadano_encuesta_respuesta'] ?>',
								'respuesta' : '0',
							}
							cuestionario.push(data);
						}
					}



					<?php
				}
			}
			?>

			$.ajax({
					type: "POST",
					url: "seccionesIneCiudadanosEncuestas/db_add_update.php",
					data: {seccion_ine_ciudadano_encuesta: seccion_ine_ciudadano_encuesta,cuestionario:cuestionario},
					success: function(data) {
						if(data=="SI"){ 
							document.getElementById("sumbmit").disabled = true;
							$("#mensaje").html("Guardado con éxito"); 
							document.getElementById("mensaje").classList.add("mensajeSucces");
							urlink="seccionesIneCiudadanosEncuestas/index.php";
							dataString = 'urlink='+urlink; 
							$.ajax({
								type: "POST",
								url: "functions/backarray.php",
								data: dataString,
								success: function(data) { 	}
							});
							$("#homebody").load(urlink);
						}else{
							document.getElementById("mensaje").classList.add("mensajeError");
							document.getElementById("sumbmit").disabled = false;
							$("#mensaje").html(data);
						}
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
	<div class="bodymanager" id="bodymanager" style="display: table;"> 
		<div class="submenux" onclick="subSeccionesIneCiudadanos()">Ciudadanos</div> <br>
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
