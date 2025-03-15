<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/tipos_categorias_ciudadanos.php";
	include __DIR__."/../functions/claves.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}

	echo $redirectSecurity=redirectSecurity($id,'tipos_categorias_ciudadanos','tiposCategoriasCiudadanos','index');
	//var_dump($redirectSecurity);
	if($redirectSecurity!=""){
		die;
	}

	$claveF= clave('tipos_categorias_ciudadanos');
	$tipo_categoria_ciudadanoDatos=tipo_categoria_ciudadanoDatos($id);
	if($tipo_categoria_ciudadanoDatos['clave']==""){
		$tipo_categoria_ciudadanoDatos['clave']=$claveF['clave'];
	}


	$permiso="update";
	?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="tiposCategoriasCiudadanos/index.php";
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

			var color_border = document.getElementById("color_border").value; 
			if(color_border == ""){
				document.getElementById("color_border").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Border Color requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var color_background = document.getElementById("color_background").value; 
			if(color_background == ""){
				document.getElementById("color_background").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Background Color requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}


			var tipo_categoria_ciudadano = [];
			var data = {
					'id' : id,
					'clave' : clave,
					'nombre' : nombre,
					'color_border' : color_border,
					'color_background' : color_background,
				}
			tipo_categoria_ciudadano.push(data);
			$.ajax({
				type: "POST",
				url: "tiposCategoriasCiudadanos/db_edit.php",
				data: {tipo_categoria_ciudadano: tipo_categoria_ciudadano},
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
						urlink="tiposCategoriasCiudadanos/index.php";
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
							urlink="tiposCategoriasCiudadanos/index.php";
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
					<font style="font-size: 25px;">Modificar Categoría Ciudadano</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar a categoría.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>