<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/casillas_votos_2021.php"; 
	include __DIR__."/../functions/timemex.php"; 
 

	@session_start();

	$_SESSION['Paguinasub']="casillasVotos2021Status/create.php";
	$id_casilla_voto_2021 = $_SESSION['id_casilla_voto_2021']; 
	if($id_casilla_voto_2021!=""){
		$id_casilla_voto_2021;
		$casillas_votos_2021Datos = casillas_votos_2021Datos($id_casilla_voto_2021);
		$codigo = $casillas_votos_2021Datos[0]['codigo']; 
	}else{
		echo $redirectSecurity=redirectSecurity($id_casilla_voto_2021,'casillas_votos_2021','casillasVotos2021','index');
		if($redirectSecurity!=""){
			die;
		}
	}
 
	$permiso='insert';
	$casilla_voto_2021_statusDatos['fecha'] = $fechaSH;
	$casilla_voto_2021_statusDatos['hora'] = $fechaSF;

	
?>
	<title>Create</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('casillasVotos2021Status/index.php');
		}

		function guardar() {
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");
			 
			 

			var id_casilla_voto_2021 = '<?= $id_casilla_voto_2021 ?>'; 
			if(id_casilla_voto_2021 == ""){
				document.getElementById("id_casilla_voto_2021").focus(); 
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

			var status = document.getElementById("status").value;
			if(status == ""){
				document.getElementById("status").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Estatus requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var casilla_voto_2021_status = []; 
			var data = {    
					'id_casilla_voto_2021' : id_casilla_voto_2021,
					'fecha' : fecha,
					'hora' : hora,
					'status' : status,
				}
			casilla_voto_2021_status.push(data);

			$.ajax({
				type: "POST",
				url: "casillasVotos2021Status/db_add.php",
				data: {casilla_voto_2021_status: casilla_voto_2021_status},
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
						$("#homebody").load('casillasVotos2021Status/index.php');
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
					<font style="font-size: 25px;">Crear Status</font>
				</label><br> 
				<h2><?= $casillas_votos_2021Datos['nombre_completo']; ?></h2>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para registrar y dar de alta a estatus de casilla.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>