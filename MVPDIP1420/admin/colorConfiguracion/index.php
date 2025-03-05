<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/partidos_legados.php";
	include __DIR__."/../functions/estados.php";
	include __DIR__."/../functions/municipios.php";
	include __DIR__."/../functions/distritos_locales.php";
	include __DIR__."/../functions/distritos_federales.php";
	@session_start();
	if($_COOKIE["id_usuario"]!=1){
		?>
		<script type="text/javascript">
			urlink="home.php";
			dataString = 'urlink='+urlink; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			$("#homebody").load(urlink);
		</script>
		<?php
		die;
	}

	?>
	<title>Create</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="setupmanagerpanel/index.php";
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
			
			var id_partido_legado = document.getElementById("id_partido_legado").value;
			if(id_partido_legado == ""){
				document.getElementById("id_partido_legado").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Partido Legado requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var id_estado = document.getElementById("id_estado").value;
			if(id_estado == ""){
				document.getElementById("id_estado").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Estado requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var todos = document.getElementById("todos").value;
			var gobernador = document.getElementById("gobernador").value;
			var senador = document.getElementById("senador").value;
			var id_municipio = document.getElementById("id_municipio").value;
			var id_distrito_local = document.getElementById("id_distrito_local").value;
			var id_distrito_federal = document.getElementById("id_distrito_federal").value;

			var forzar_distritos_locales = document.getElementById("forzar_distritos_locales").checked;
			if(forzar_distritos_locales==true){
				forzar_distritos_locales = 1;
			}else{
				forzar_distritos_locales = 0;
			}
			var forzar_distritos_federales = document.getElementById("forzar_distritos_federales").checked;
			if(forzar_distritos_federales==true){
				forzar_distritos_federales = 1;
			}else{
				forzar_distritos_federales = 0;
			}
			var forzar_gobernador = document.getElementById("forzar_gobernador").checked;
			if(forzar_gobernador==true){
				forzar_gobernador = 1;
			}else{
				forzar_gobernador = 0;
			}
			var forzar_senador = document.getElementById("forzar_senador").checked;
			if(forzar_senador==true){
				forzar_senador = 1;
			}else{
				forzar_senador = 0;
			}

			if(todos=='SI'){
				var gobernador = null;
				var senador = null;
				var id_municipio = null;
				var id_distrito_local = null;
				var id_distrito_federal = null;

				var forzar_distritos_locales = null;
				var forzar_distritos_federales = null;
				var forzar_gobernador = null;
				var forzar_senador = null;
			}
			if(id_municipio==""){
				var forzar_distritos_locales = null;
				var forzar_distritos_federales = null;
				var forzar_gobernador = null;
				var forzar_senador = null;
			}

			

			
			var color_configuracion = []; 
			var data = { 
					'id_partido_legado' : id_partido_legado,
				}
			color_configuracion.push(data);
			var territorio = []; 
			var data = { 
					'id_estado' : id_estado,
					'id_municipio' : id_municipio,
					'id_distrito_local' : id_distrito_local,
					'id_distrito_federal' : id_distrito_federal,
					'gobernador' : gobernador,
					'senador' : senador,
					'forzar_distritos_locales' : forzar_distritos_locales,
					'forzar_distritos_federales' : forzar_distritos_federales,
					'forzar_gobernador' : forzar_gobernador,
					'forzar_senador' : forzar_senador,
				}
			territorio.push(data);
			//console.log(color_configuracion);
			$.ajax({
				type: "POST",
				url: "colorConfiguracion/db_edit.php",
				data: {color_configuracion: color_configuracion,territorio:territorio},
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
					<font style="font-size: 25px;">Tropicalización</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;"></font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>