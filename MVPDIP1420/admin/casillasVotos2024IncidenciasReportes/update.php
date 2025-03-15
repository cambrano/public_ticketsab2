<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/casillas_votos_2024_incidencias.php";
	include __DIR__."/../functions/casillas_votos_2024.php";
	include __DIR__."/../functions/status.php";
	include __DIR__."/../functions/secciones_ine.php";
	include '../functions/usuario_permisos.php';
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}

	echo $redirectSecurity=redirectSecurity($id,'casillas_votos_2024_incidencias','casillasVotos2024IncidenciasReportes','index');
	if($redirectSecurity!=""){
		die;
	}

	$casilla_voto_2024_incidenciaDatos=casilla_voto_2024_incidenciaDatos($id,$id_casilla_voto_2024);
	$casilla_voto_2024_incidenciaDatos['id_casilla_voto_2024'];


	//var_dump($casilla_voto_2024_incidenciaDatos);
	$id_casilla_voto_2024 = $casilla_voto_2024_incidenciaDatos['id_casilla_voto_2024']; 
	if($id_casilla_voto_2024!=""){
		$id_casilla_voto_2024;
		$casilla_voto_2024Datos = casilla_voto_2024Datos($id_casilla_voto_2024);
		$codigo = $casilla_voto_2024Datos['codigo'];
	}else{
		echo $redirectSecurity=redirectSecurity($id_casilla_voto_2024,'casillas_votos_2024','casillasVotos2024','index');
		if($redirectSecurity!=""){
			die;
		}
	}



	if($casilla_voto_2024Datos['tipo']==0){
		$tipo_nombre = 'Municipal';
	}elseif ($casilla_voto_2024Datos['tipo']==1) {
		$tipo_nombre = 'Local';
	}else{
		$tipo_nombre = 'Federal';
	}

	$id_seccion_ine = $casilla_voto_2024Datos['id_seccion_ine'];
	$seccion_ineDatos =  seccion_ineDatos($id_seccion_ine);
	?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="casillasVotos2024IncidenciasReportes/index.php";
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
			var coma= /,/g;
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

			var id_casilla_voto_2024 = '<?= $id_casilla_voto_2024 ?>'; 
			if(id_casilla_voto_2024 == ""){
				document.getElementById("id_casilla_voto_2024").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Debe Seleccionar un ciudadano en el sistema requerido");
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

			var semaforo = document.getElementById("semaforo").value;
			if(semaforo == ""){
				document.getElementById("semaforo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Semáforo requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			if(semaforo>1){
				var observaciones = document.getElementById("observaciones").value;
				if(observaciones == ""){
					document.getElementById("observaciones").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Observaciones requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
			}else{
				var observaciones = '';
			}

			var status = document.getElementById("status").value;
			if(status == ""){
				document.getElementById("status").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Estatus requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}


			var casilla_voto_2024_incidencia = []; 
			var data = {    
					'id' : id,
					'id_casilla_voto_2024' : id_casilla_voto_2024,
					'fecha' : fecha,
					'hora' : hora,
					'semaforo' : semaforo,
					'observaciones' : observaciones,
					'status' : status,
				}
			casilla_voto_2024_incidencia.push(data);
			$.ajax({
				type: "POST",
				url: "casillasVotos2024IncidenciasReportes/db_edit.php",
				data: {casilla_voto_2024_incidencia: casilla_voto_2024_incidencia},
				success: function(data) {
					//document.getElementById("form").reset();  
					//document.getElementById("form").style.border="";
					//
					if(data=="SI"){
						document.getElementById("sumbmit").disabled = true;
						document.getElementById("mensaje").classList.remove("mensajeError");
						$("#mensaje").html("&nbsp;");
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						$("#homebody").load('casillasVotos2024IncidenciasReportes/index.php');
					}else{
						if(data==""){
							urlink="casillasVotos2024IncidenciasReportes/index.php";
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
	<div class="bodymanager" id="bodymanager"> 
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Modificar Incidencia Casilla</font>
				</label><br>
				<h2>Tipo:<?= $tipo_nombre ?><br>Sección:<?= $seccion_ineDatos['numero'].' <br> Casilla:'.$casilla_voto_2024Datos['codigo']; ?></h2>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar modificar el incidencia de la casilla.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>