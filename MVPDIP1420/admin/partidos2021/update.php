<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/partidos_2021.php";
	include __DIR__."/../functions/claves.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}

	echo $redirectSecurity=redirectSecurity($id,'partidos_2021','partidos2021','index');
	//var_dump($redirectSecurity);
	if($redirectSecurity!=""){
		die;
	}

	$claveF= clave('partidos_2021');
	$partido_2021Datos=partido_2021Datos($id);
	if($partido_2021Datos['clave']==""){
		$partido_2021Datos['clave']=$claveF['clave'];
	}


	$permiso="update";
	?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="partidos2021/index.php";
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

			var nombre_corto = document.getElementById("nombre_corto").value; 
			if(nombre_corto == ""){
				document.getElementById("nombre_corto").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Nombre Corto requerido");
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
			var tipo = document.getElementById("tipo").value; 
			if(tipo == ""){
				document.getElementById("tipo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Tipo requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var icono = document.getElementById("icono").value; 
			if(icono == ""){
				document.getElementById("icono").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Icono requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var logo = document.getElementById("logo").value; 
			if(logo == ""){
				document.getElementById("logo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Logo requerido");
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

			var principal = document.getElementById("principal").value;

			var partido_2021 = [];
			var data = {
					'id' : id,
					'clave' : clave,
					'nombre_corto' : nombre_corto,
					'nombre' : nombre,
					'tipo' : tipo,
					'icono' : icono,
					'logo' : logo,
					'color_border' : color_border,
					'color_background' : color_background,
					'principal' : principal,
				}
			partido_2021.push(data);
			$.ajax({
				type: "POST",
				url: "partidos2021/db_edit.php",
				data: {partido_2021: partido_2021},
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
						urlink="partidos2021/index.php";
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
							urlink="partidos2021/index.php";
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
					<font style="font-size: 25px;">Modificar Partido</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar a partido.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>