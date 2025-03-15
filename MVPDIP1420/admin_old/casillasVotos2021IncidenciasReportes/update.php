<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/casillas_votos_2021_incidencias.php";
	include __DIR__."/../functions/casillas_votos_2021.php";
	include __DIR__."/../functions/status.php";
	include __DIR__."/../functions/secciones_ine.php";
	
	include '../functions/usuario_permisos.php';
	
	@session_start(); 
	$_SESSION['Paguinasub']="casillasVotos2021IncidenciasReportes/update.php";  
	
	if(!empty($_GET)){
		$id=$_SESSION['paguinaId']=$_GET['id'];
	}else{
		$id=$_SESSION['paguinaId'];
	}

 
	$redirectSecurity=redirectSecurity($id,'casillas_votos_2021_incidencias','casillasVotos2021IncidenciasReportes','index');
	if($redirectSecurity!=""){
		die;
	}

	/*
	
	*/

	$casilla_voto_2021_incidenciaDatos=casilla_voto_2021_incidenciaDatos($id,$id_casilla_voto_2021);
	$casilla_voto_2021_incidenciaDatos['id_casilla_voto_2021'];


	//var_dump($casilla_voto_2021_incidenciaDatos);

	$id_casilla_voto_2021 = $casilla_voto_2021_incidenciaDatos['id_casilla_voto_2021']; 
	if($id_casilla_voto_2021!=""){
		$id_casilla_voto_2021;
		$casilla_voto_2021Datos = casilla_voto_2021Datos($id_casilla_voto_2021);
		$codigo = $casilla_voto_2021Datos['codigo'];
	}else{
		echo $redirectSecurity=redirectSecurity($id_casilla_voto_2021,'casillas_votos_2021','casillasVotos2021','index');
		if($redirectSecurity!=""){
			die;
		}
	}



	if($casilla_voto_2021Datos['tipo']==0){
		$tipo_nombre = 'Municipal';
	}elseif ($casilla_voto_2021Datos['tipo']==1) {
		$tipo_nombre = 'Local';
	}else{
		$tipo_nombre = 'Federal';
	}

	$id_seccion_ine = $casilla_voto_2021Datos['id_seccion_ine'];
	$seccion_ineDatos =  seccion_ineDatos($id_seccion_ine);
	?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('casillasVotos2021IncidenciasReportes/index.php');
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


			var casilla_voto_2021_incidencia = []; 
			var data = {    
					'id' : id,
					'id_casilla_voto_2021' : id_casilla_voto_2021,
					'fecha' : fecha,
					'hora' : hora,
					'semaforo' : semaforo,
					'observaciones' : observaciones,
					'status' : status,
				}
			casilla_voto_2021_incidencia.push(data);
			$.ajax({
				type: "POST",
				url: "casillasVotos2021IncidenciasReportes/db_edit.php",
				data: {casilla_voto_2021_incidencia: casilla_voto_2021_incidencia},
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
						$("#homebody").load('casillasVotos2021IncidenciasReportes/index.php');
					}else{
						if(data==""){
							$("#homebody").load('casillasVotos2021IncidenciasReportes/index.php');
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
				<h2>Tipo:<?= $tipo_nombre ?><br>Sección:<?= $seccion_ineDatos['numero'].' <br> Casilla:'.$casilla_voto_2021Datos['codigo']; ?></h2>
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