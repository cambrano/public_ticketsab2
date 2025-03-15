<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/supervisores.php";
	include __DIR__."/../functions/claves_2.php";
	@session_start(); 
	$_SESSION['Paguinasub']="supervisores/update.php";  
	$id_hotel=$_SESSION['id_hotel']; 
	if(!empty($_GET)){
		$id=$_SESSION['paguinaId']=$_GET['id'];
	}else{
		$id=$_SESSION['paguinaId'];
	}
	$redirectSecurity=redirectSecurity($id,'supervisores','supervisores','index');
	if($redirectSecurity!=""){
		die;
	}

	$claveF= clave2('supervisores');
	$supervisorDatos=supervisorDatos($id);
	if($supervisorDatos['clave']==""){
		$supervisorDatos['clave']=$claveF['clave'];
	}

	$permiso="update";
	?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('supervisores/index.php');
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

			var clave = document.getElementById("clave").value; 
			if(clave == ""){
				document.getElementById("clave").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Clave requerido");
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
			var apellido_paterno = document.getElementById("apellido_paterno").value; 
			if(apellido_paterno == ""){
				document.getElementById("apellido_paterno").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Apellido Paterno requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			

			var apellido_materno = document.getElementById("apellido_materno").value; 
			if(apellido_materno == ""){
				document.getElementById("apellido_materno").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Apellido Materno requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}


			var correo_electronico = document.getElementById("correo_electronico").value; 
			if(correo_electronico == ""){
				document.getElementById("correo_electronico").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Correo Electrónico Válido requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}else{
				if(!validarEmail(correo_electronico)){
					document.getElementById("correo_electronico").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Correo Electrónico Válido requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
			}

			var telefono = document.getElementById("telefono").value;
			var celular = document.getElementById("celular").value;
			var whatsapp = document.getElementById("whatsapp").value;
			var observaciones = document.getElementById("observaciones").value;

			var supervisor = [];
			var data = {
					'id' : id,
					'clave' : clave,
					'nombre' : nombre,
					'apellido_paterno' : apellido_paterno,
					'apellido_materno' : apellido_materno,
					'correo_electronico' : correo_electronico,
					'telefono' : telefono,
					'celular' : celular,
					'whatsapp' : whatsapp,
					'observaciones' : observaciones,
				}
			supervisor.push(data);
			$.ajax({
				type: "POST",
				url: "supervisores/db_edit.php",
				data: {supervisor: supervisor},
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
						$("#homebody").load('supervisores/index.php');
					}else{
						if(data==""){
							$("#homebody").load('supervisores/index.php');
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
					<font style="font-size: 25px;">Modificar Supervisor</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar a supervisor.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>