<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/casillas_votos_2024.php"; 
	include __DIR__."/../functions/status.php";
	include __DIR__."/../functions/timemex.php";
	include '../functions/tool_xhpzab.php';
	@session_start();
	$id_casilla_voto_2024 = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	if($id_casilla_voto_2024!=""){
		$id_casilla_voto_2024;
		$casillas_votos_2024Datos = casillas_votos_2024Datos($id_casilla_voto_2024);
		$codigo = $casillas_votos_2024Datos[0]['codigo']; 
	}else{
		echo $redirectSecurity=redirectSecurity($id_casilla_voto_2024,'casillas_votos_2024','casillasVotos2024','index');
		if($redirectSecurity!=""){
			die;
		}
	}
 
	$permiso='insert';
	$casilla_voto_2024_incidenciaDatos['fecha'] = $fechaSF;
	$casilla_voto_2024_incidenciaDatos['hora'] = $fechaSH;

	
?>
	<title>Create</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="casillasVotos2024Incidencias/index.php";
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
				$("#mensaje").html("Esemaforo requerido");
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
				var observaciones = null;
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
				url: "casillasVotos2024Incidencias/db_add.php",
				data: {casilla_voto_2024_incidencia: casilla_voto_2024_incidencia},
				success: function(data) {
					//document.getElementById("form").reset();  
					//document.getElementById("form").style.border="";
					//
					if(data=="SI"){
						document.getElementById("sumbmit").disabled = true;
						$("#mensaje").html("&nbsp;");
						document.getElementById("mensaje").classList.remove("mensajeError");
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						urlink="casillasVotos2024Incidencias/index.php";
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
	<div class="bodymanager" id="bodymanager"> 
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Crear Inicidencia</font>
				</label><br> 
				<h2><?= $casillas_votos_2024Datos['nombre_completo']; ?></h2>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para registrar y dar de alta a incidencia de casilla.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>