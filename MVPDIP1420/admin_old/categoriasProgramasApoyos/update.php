<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/categorias_programas_apoyos.php";
	include __DIR__."/../functions/claves.php";
	@session_start(); 
	$_SESSION['Paguinasub']="categoriasProgramasApoyos/update.php";  
	$id_hotel=$_SESSION['id_hotel']; 
	if(!empty($_GET)){
		$id=$_SESSION['paguinaId']=$_GET['id'];
	}else{
		$id=$_SESSION['paguinaId'];
	}
	$redirectSecurity=redirectSecurity($id,'categorias_programas_apoyos','categoriasProgramasApoyos','index');
	if($redirectSecurity!=""){
		die;
	}

	$claveF= clave('categorias_programas_apoyos');
	$categoria_programa_apoyoDatos=categoria_programa_apoyoDatos($id);
	if($categoria_programa_apoyoDatos['clave']==""){
		$categoria_programa_apoyoDatos['clave']=$claveF['clave'];
	}

	$permiso="update";
	?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('categoriasProgramasApoyos/index.php');
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

			var descripcion = document.getElementById("descripcion").value; 
			/*
			if(descripcion == ""){
				document.getElementById("descripcion").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Descripción requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			*/

			var categoria_programa_apoyo = [];
			var data = {
					'id' : id,
					'clave' : clave,
					'nombre' : nombre,
					'descripcion' : descripcion,
				}
			categoria_programa_apoyo.push(data);
			$.ajax({
				type: "POST",
				url: "categoriasProgramasApoyos/db_edit.php",
				data: {categoria_programa_apoyo: categoria_programa_apoyo},
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
						$("#homebody").load('categoriasProgramasApoyos/index.php');
					}else{
						if(data==""){
							$("#homebody").load('categoriasProgramasApoyos/index.php');
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
					<font style="font-size: 25px;">Modificar Categoría Programa Apoyos</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar a categoría programa apoyos.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>