<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/paises.php";
	include __DIR__."/../functions/estados.php";
	include __DIR__."/../functions/municipios.php";
	include __DIR__."/../functions/federalidades.php";
	include __DIR__."/../functions/distritos_federales_parametros.php";
	@session_start();
	$_SESSION['Paguinasub']="distritosFederales/create.php";  
	$permiso="insert";
	unset($_SESSION['limites']);
	unset($_SESSION['limites_num']);
	$distritos_federales_parametrosDatosMapa = distritos_federales_parametrosDatosMapa('','',' id_distrito_federal,orden ASC');

	include __DIR__."/../functions/claves.php";
	$claveF= clave('distritos_federales');
	$distrito_federalDatos['clave']=$claveF['clave'];

?>
	<title>Create</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('distritosFederales/index.php');
		}

		function guardar() {
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


			var numero = document.getElementById("numero").value; 
			if(numero == ""){
				document.getElementById("numero").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Fecha Nacimiento Válida requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var longitud = document.getElementById("longitud").value; 
			if(longitud == ""){
				document.getElementById("longitud").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Longitud requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var latitud = document.getElementById("latitud").value; 
			if(latitud == ""){
				document.getElementById("latitud").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Latitud requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}


			var distrito_federal = []; 
			var data = {    
					'clave' : clave,
					'numero' : numero,
					'latitud' : latitud,
					'longitud' : longitud,
				}
			distrito_federal.push(data); 
			 
			$.ajax({
				type: "POST",
				url: "distritosFederales/db_add.php",
				data: {distrito_federal: distrito_federal},
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
						$("#homebody").load('distritosFederales/index.php');
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
					<font style="font-size: 25px;">Crear Distrito Federal</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para registrar y dar de alta a distrito federal.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>