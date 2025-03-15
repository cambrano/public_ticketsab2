<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos_programas_apoyos.php";
	include __DIR__."/../functions/programas_apoyos.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../functions/claves.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/plataformas.php";

	@session_start(); 
	$_SESSION['Paguinasub']="seccionesIneCiudadanosProgramasApoyosTotales/update.php";  
	
	if(!empty($_GET)){
		$id=$_SESSION['paguinaId']=$_GET['id'];
	}else{
		$id=$_SESSION['paguinaId'];
	}
	$redirectSecurity=redirectSecurity($id,'secciones_ine_ciudadanos_programas_apoyos','programasApoyos','index');
	if($redirectSecurity!=""){
		die;
	}

	$id_programa_apoyo = $_SESSION['id_programa_apoyo']; 
	if($id_programa_apoyo!=""){
		$id_programa_apoyo;
		$programa_apoyoDatos = programa_apoyoDatos($id_programa_apoyo);
		$nombre = $programa_apoyoDatos['nombre'];
	}else{
		echo $redirectSecurity=redirectSecurity($id_programa_apoyo,'programas_apoyos','programasApoyos','index');
		if($redirectSecurity!=""){
			die;
		}
	}

	$claveF= clave('secciones_ine_ciudadanos_programas_apoyos');
	$seccion_ine_ciudadano_programa_apoyoDatos=seccion_ine_ciudadano_programa_apoyoDatos($id);
	if($seccion_ine_ciudadano_programa_apoyoDatos['clave']==""){
		$seccion_ine_ciudadano_programa_apoyoDatos['clave']=$claveF['clave'];
	}

	$id_seccion_ine_ciudadano = $seccion_ine_ciudadano_programa_apoyoDatos['id_seccion_ine_ciudadano'];
	$row = seccion_ine_ciudadanoDatos($id_seccion_ine_ciudadano);
	validar_plataforma_vista($id_seccion_ine_ciudadano,'secciones_ine_ciudadanos','programasApoyos','index',$codigo_plataforma);
	$seccion_ine_ciudadano_programa_apoyoDatos['clave_elector'] = $row['clave_elector'];

	$permiso="update";
	?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('seccionesIneCiudadanosProgramasApoyosTotales/index.php');
		}


		function guardar() {
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");
			var espacios_invalidos= /\s+/g;
			
			var id = '<?= $id?>';
			if(id == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_programa_apoyo = '<?= $id_programa_apoyo ?>'; 
			if(id_programa_apoyo == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Debe Seleccionar un ciudadano en el sistema requerido");
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

			var folio = document.getElementById("folio").value; 
			if(folio == ""){
				document.getElementById("folio").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Folio requerido");
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

			var clave_elector = document.getElementById("clave_elector").value; 
			if(clave_elector == ""){
				document.getElementById("clave_elector").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Clave Elector requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var observaciones = document.getElementById("observaciones").value; 


			var seccion_ine_ciudadano_programa_apoyo = []; 
			var data = {    
					'id' : id,
					'id_programa_apoyo' : id_programa_apoyo, 
					'clave' : clave,
					'folio' : folio,
					'fecha' : fecha,
					'hora' : hora,
					'clave_elector' : clave_elector,
					'observaciones' : observaciones,
				}
			seccion_ine_ciudadano_programa_apoyo.push(data);
			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanosProgramasApoyosTotales/db_edit.php",
				data: {seccion_ine_ciudadano_programa_apoyo: seccion_ine_ciudadano_programa_apoyo},
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
						$("#homebody").load('seccionesIneCiudadanosProgramasApoyosTotales/index.php');
					}else{
						if(data==""){
							$("#homebody").load('seccionesIneCiudadanosProgramasApoyosTotales/index.php');
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
					<font style="font-size: 25px;">Modificar Ciudadano</font>
				</label><br>
				<h2><?= $row['nombre_completo']; ?></h2>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar ciudadano.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>