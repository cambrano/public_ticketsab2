<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/claves.php";
	@session_start(); 
	$_SESSION['Paguinasub']="partidos2018/create.php";
	unset($_SESSION['paguinaId']); 
	$permiso="insert";
	$claveF= clave('partidos_2018');
	$partido_2018Datos['clave']=$claveF['clave'];

	?>
	<title>Create</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('partidos2018/index.php');
		}

		function guardar() {
			var coma= /,/g;
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");

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

			var partido_2018 = [];
			var data = {
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
			partido_2018.push(data);

			$.ajax({
				type: "POST",
				url: "partidos2018/db_add.php",
				data: {partido_2018: partido_2018},
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
						$("#homebody").load('partidos2018/index.php');
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
					<font style="font-size: 25px;">Crear Partido</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para registrar y dar de nombrea a partido.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>