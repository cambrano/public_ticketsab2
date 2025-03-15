<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos_campanas_whatsapp_programadas.php";
	
	@session_start(); 
	$_SESSION['Paguinasub']="seccionesIneCiudadanosCampanasWhatsappProgramadas/update.php";  
	$id_hotel=$_SESSION['id_hotel']; 
	if(!empty($_GET)){
		$id=$_SESSION['paguinaId']=$_GET['id'];
	}else{
		$id=$_SESSION['paguinaId'];
	}
	$redirectSecurity=redirectSecurity($id,'secciones_ine_ciudadanos_campanas_whatsapp_programadas','seccionesIneCiudadanosCampanasWhatsappProgramadas','index');
	if($redirectSecurity!=""){
		die;
	}

	
	$seccion_ine_ciudadano_campana_whatsapp_programadaDatos=seccion_ine_ciudadano_campana_whatsapp_programadaDatos($id);
	 

	$permiso="update";
	?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('seccionesIneCiudadanosCampanasWhatsappProgramadas/index.php');
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

			var status = document.getElementById("status").value; 
			if(status == ""){
				document.getElementById("status").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Clave requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

		 

			var seccion_ine_ciudadano_campana_whatsapp_programada = [];
			var data = {
					'id' : id,
					'status' : status, 
				}
			seccion_ine_ciudadano_campana_whatsapp_programada.push(data);
			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanosCampanasWhatsappProgramadas/db_edit.php",
				data: {seccion_ine_ciudadano_campana_whatsapp_programada: seccion_ine_ciudadano_campana_whatsapp_programada},
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
						$("#homebody").load('seccionesIneCiudadanosCampanasWhatsappProgramadas/index.php');
					}else{
						if(data==""){
							$("#homebody").load('seccionesIneCiudadanosCampanasWhatsappProgramadas/index.php');
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
					<font style="font-size: 25px;">Modificar Ciudadano Campaña</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar a ciudadano campaña.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>