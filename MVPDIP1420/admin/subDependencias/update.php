<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/dependencias.php";
	include __DIR__."/../functions/sub_dependencias.php";
	include __DIR__."/../functions/plataformas.php";
	include '../functions/usuario_permisos.php';
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}
	echo $redirectSecurity=redirectSecurity($id,'sub_dependencias','subDependencias','index');
	if($redirectSecurity!=""){
		die;
	}
	$id_dependencia = decrypt_ab_checkFinal($_COOKIE['paguinaId_2']);
	if($id_dependencia!=""){
		$id_dependencia;
		$dependenciaDatos = dependenciaDatos($id_dependencia);
		$nombre = $dependenciaDatos['nombre'];
		if($nombre==""){
			echo $redirectSecurity=redirectSecurity('','dependencias','dependencias','index');
			if($redirectSecurity!=""){
				die;
			}
		}
	}else{
		echo $redirectSecurity=redirectSecurity($id_dependencia,'dependencias','dependencias','index');
		if($redirectSecurity!=""){
			die;
		}
	}
	$sub_dependenciaDatos = sub_dependenciaDatos($id_sub_dependencia);
	$permiso="update";
	?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="subDependencias/index.php";
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

			var nombre_corto = document.getElementById("nombre_corto").value; 
			if(nombre_corto == ""){
				document.getElementById("nombre_corto").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Nombre Corto requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}


			var sub_dependencia = []; 
			var data = {    
					'id' : id,
					'nombre_corto' : nombre_corto,
					'nombre' : nombre
				}
			sub_dependencia.push(data);
			$.ajax({
				type: "POST",
				url: "subDependencias/db_edit.php",
				data: {sub_dependencia: sub_dependencia},
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
						urlink="subDependencias/index.php";
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
							urlink="subDependencias/index.php";
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
			$("#mensaje_ine_disponible").click(function(event) { 
				document.getElementById("mensaje_ine_disponible").classList.remove("mensajeSucces");
				document.getElementById("mensaje_ine_disponible").classList.remove("mensajeError");
				$("#mensaje_ine_disponible").html("&nbsp");
			});
		});
	</script>
	<div class="bodymanager" id="bodymanager"> 
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Modificar sub dependencia</font>
				</label><br>
				<h2><?= $dependenciaDatos['nombre']; ?></h2>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar participación.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>