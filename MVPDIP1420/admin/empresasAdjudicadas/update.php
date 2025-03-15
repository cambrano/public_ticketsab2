<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/empresas_adjudicadas.php";
	include __DIR__."/../functions/claves_2.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}
	echo $redirectSecurity=redirectSecurity($id,'empresas_adjudicadas','empresasAdjudicadas','index');
	if($redirectSecurity!=""){
		die;
	}

	$claveF= clave2('empresas_adjudicadas');
	$empresa_adjudicadaDatos=empresa_adjudicadaDatos($id);
	if($empresa_adjudicadaDatos['clave']==""){
		$empresa_adjudicadaDatos['clave']=$claveF['clave'];
	}

	$permiso="update";
	?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="empresasAdjudicadas/index.php";
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

			var representante_nombre = document.getElementById("representante_nombre").value; 
			if(representante_nombre == ""){
				document.getElementById("representante_nombre").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Representante Nombre requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var representante_apellido_paterno = document.getElementById("representante_apellido_paterno").value; 
			if(representante_apellido_paterno == ""){
				document.getElementById("representante_apellido_paterno").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Representante Apellido Paterno requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			

			var representante_apellido_materno = document.getElementById("representante_apellido_materno").value; 
			if(representante_apellido_materno == ""){
				document.getElementById("representante_apellido_materno").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Representante Apellido Materno requerido");
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

			var empresa_adjudicada = [];
			var data = {
					'id' : id,
					'clave' : clave,
					'nombre' : nombre,
					'representante_nombre' : representante_nombre,
					'representante_apellido_paterno' : representante_apellido_paterno,
					'representante_apellido_materno' : representante_apellido_materno,
					'correo_electronico' : correo_electronico,
					'telefono' : telefono,
					'celular' : celular,
					'whatsapp' : whatsapp,
					'observaciones' : observaciones,
				}
			empresa_adjudicada.push(data);
			$.ajax({
				type: "POST",
				url: "empresasAdjudicadas/db_edit.php",
				data: {empresa_adjudicada: empresa_adjudicada},
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
						urlink="empresasAdjudicadas/index.php";
						dataString = 'urlink='+urlink; 
						$.ajax({
							type: "POST",
							url: "functions/backarray.php",
							data: dataString,
							success: function(data) { 	}
						});
						$("#homebody").load(urlink);
					}else{
						if(data==""){
							urlink="empresasAdjudicadas/index.php";
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
					<font style="font-size: 25px;">Modificar Empresa Adjudicada</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar a empresa adjudicada.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>