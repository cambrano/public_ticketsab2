<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/programas_apoyos.php";
	include __DIR__."/../functions/claves.php";
	@session_start();
	include '../functions/tool_xhpzab.php';
	$id_programa_apoyo = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	if($id_programa_apoyo!=""){
		$id_programa_apoyo;
		$programa_apoyoDatos = programa_apoyoDatos($id_programa_apoyo);
		$nombre_completo = $programa_apoyoDatos['nombre_corto'];
	}else{
		echo $redirectSecurity=redirectSecurity($id_programa_apoyo,'programas_apoyos','programasApoyos','index');
		if($redirectSecurity!=""){
			die;
		}
	}


	$permiso='insert';
	$claveF= clave('secciones_ine_ciudadanos_programas_apoyos');
	$seccion_ine_ciudadano_programa_apoyoDatos['clave']=$claveF['clave'];

	$seccion_ine_ciudadano_programa_apoyoDatos['fecha'] = date("Y-m-d");
	$seccion_ine_ciudadano_programa_apoyoDatos['hora'] = date("H:i:s");
?>
	<title>Create</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="seccionesIneCiudadanosProgramasApoyosTotales/index.php";
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
			var espacios_invalidos= /\s+/g;
			
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
				url: "seccionesIneCiudadanosProgramasApoyosTotales/db_add.php",
				data: {seccion_ine_ciudadano_programa_apoyo: seccion_ine_ciudadano_programa_apoyo},
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
						urlink="seccionesIneCiudadanosProgramasApoyosTotales/index.php";
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
					<font style="font-size: 25px;">Crear Ciudadano</font>
				</label><br> 
				<h2><?= $programa_apoyoDatos['nombre']; ?></h2>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para registrar y dar de alta a ciudadano.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>