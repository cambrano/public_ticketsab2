<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/empleados.php";
	include __DIR__."/../functions/dependencias.php";
	include __DIR__."/../functions/empleados_dependencias.php";
	include __DIR__."/../functions/plataformas.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	
	$id_empleado = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId_1",encrypt_ab_check($id), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	}

	validar_plataforma_vista($id_empleado,'empleados','adminGenerales','index',$codigo_plataforma);
	echo $redirectSecurity=redirectSecurity($id,'empleados_dependencias','usuarioDependencias','index');
	if($redirectSecurity!=""){
		die;
	}
	$id;
	$empleado_dependenciaDatos=empleado_dependenciaDatos($id);
	$empleadoDatos=empleadoDatos($id_empleado);
	$permiso="update";

	$disabled = 'disabled="disabled"';
?>
<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="usuarioDependencias/index.php";
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


			var id = '<?= $id ?>'; 
			if(id == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_empleado = '<?=$id_empleado ?>'; 
			if(id_empleado == ""){
				document.getElementById("id_empleado").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Empleado requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var id_dependencia = document.getElementById("id_dependencia").value; 
			if(id_dependencia == ""){
				document.getElementById("id_dependencia").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Dependencia requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var empleado_dependencia = []; 
			var data = {
					'id' : id,
					'id_empleado' : id_empleado,
					'id_dependencia' : id_dependencia,
				}
			empleado_dependencia.push(data);
			$.ajax({
			type: "POST",
			url: "usuarioDependencias/db_edit.php",
			data: {empleado_dependencia: empleado_dependencia},
			success: function(data) {
				if(data=="SI"){
					document.getElementById("sumbmit").disabled = true;
					document.getElementById("mensaje").classList.remove("mensajeError");
					$("#mensaje").html("&nbsp;");
					$("#mensaje").html("Guardado con éxito"); 
					document.getElementById("mensaje").classList.add("mensajeSucces");
					urlink="usuarioDependencias/index.php";
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
						document.getElementById("sumbmit").disabled = false;
						urlink="usuarioDependencias/index.php";
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
			<label class="tituloForm">
					<font style="font-size: 25px;">Modificar Dependencia de Empleado</font>
				</label>
				<h3>
					Empleado: <?= $empleadoDatos['nombre_empleado'] ?>
				</h3>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar dependencia a empleado.</font><br><br>
				</label>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>