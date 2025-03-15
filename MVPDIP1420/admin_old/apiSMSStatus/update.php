<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/api_sms_status.php";
	include __DIR__."/../functions/api_sms.php";
	@session_start(); 
	$_SESSION['Paguinasub']="apiSMSStatus/update.php";  
	$id_hotel=$_SESSION['id_hotel']; 
	if(!empty($_GET)){
		$id=$_SESSION['paguinaId']=$_GET['id'];
	}else{
		$id=$_SESSION['paguinaId'];
	}
	$redirectSecurity=redirectSecurity($id,'api_sms_status','apiSMSStatus','index');
	if($redirectSecurity!=""){
		die;
	}

	$api_sms_statusDatos=api_sms_statusDatos($id);

	$permiso="update";
	?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('apiSMSStatus/index.php');
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

			var id_api_sms = document.getElementById("id_api_sms").value; 
			if(id_api_sms == ""){
				document.getElementById("id_api_sms").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("API SMS requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var nombre = document.getElementById("nombre").value; 
			if(nombre == ""){
				document.getElementById("nombre").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Nombre requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var codigo = document.getElementById("codigo").value; 
			if(codigo == ""){
				document.getElementById("codigo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Código requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var tipo = document.getElementById("tipo").value; 
			if(tipo == ""){
				document.getElementById("tipo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Tipo requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var api_sms_status = [];
			var data = {
					'id' : id,
					'id_api_sms' : id_api_sms,
					'nombre' : nombre,
					'codigo' : codigo,
					'tipo' : tipo,
				}
			api_sms_status.push(data);
			$.ajax({
				type: "POST",
				url: "apiSMSStatus/db_edit.php",
				data: {api_sms_status: api_sms_status},
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
						$("#homebody").load('apiSMSStatus/index.php');
					}else{
						if(data==""){
							$("#homebody").load('apiSMSStatus/index.php');
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
					<font style="font-size: 25px;">Modificar Tipo de Actividad</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar a tipo de actividad.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>