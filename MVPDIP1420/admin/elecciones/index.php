<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/elecciones.php";
	include __DIR__."/../functions/status.php";
	$eleccionesDatos = eleccionesDatos();
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
			
			var elecciones = []; 
			<?php
			foreach ($eleccionesDatos as $key => $value) {
				?>
				var id = '<?= $value['id'] ?>';
				var municipios = document.getElementById("municipios_<?= $value['id'] ?>").value;
				var municipios_show = document.getElementById("municipios_show_<?= $value['id'] ?>").value;
				var distritos_locales = document.getElementById("distritos_locales_<?= $value['id'] ?>").value;
				var distritos_locales_show = document.getElementById("distritos_locales_show_<?= $value['id'] ?>").value;
				var distritos_federales = document.getElementById("distritos_federales_<?= $value['id'] ?>").value;
				var distritos_federales_show = document.getElementById("distritos_federales_show_<?= $value['id'] ?>").value;

				var senador = document.getElementById("senador_<?= $value['id'] ?>").value;
				var senador_show = document.getElementById("senador_show_<?= $value['id'] ?>").value;
				var gobernador = document.getElementById("gobernador_<?= $value['id'] ?>").value;
				var gobernador_show = document.getElementById("gobernador_show_<?= $value['id'] ?>").value; 

				var data = { 
					'id' : id,
					'municipios' : municipios,
					'municipios_show' : municipios_show,
					'distritos_locales' : distritos_locales,
					'distritos_locales_show' : distritos_locales_show,
					'distritos_federales' : distritos_federales,
					'distritos_federales_show' : distritos_federales_show,

					'senador' : senador,
					'senador_show' : senador_show,
					'gobernador' : gobernador,
					'gobernador_show' : gobernador_show,
				}
				elecciones.push(data);
				<?php
			}
			?>	
			$.ajax({
				type: "POST",
				url: "elecciones/db_edit.php",
				data: {elecciones: elecciones},
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
					<font style="font-size: 25px;">Elecciones</font>
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