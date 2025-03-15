<?php
	@session_start(); 
	if($_GET['refresh']==1){
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		die;
	}

	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/status.php";
	include __DIR__."/../functions/api_whatsapp.php";
	include __DIR__."/../functions/whatsapp_python.php";
	include __DIR__."/../functions/encuestas.php";
	include __DIR__."/../functions/tipos_ciudadanos.php";
	include __DIR__."/../functions/tipos_categorias_ciudadanos.php";
	
	$permiso="insert";

	$inputFecha ='disabled="disabled"';
	$inputHora ='disabled="disabled"';
	$div_programa='none';
	$div_encuesta='none';
	$div_tipo_sender_api='none';
	$div_tipo_sender_python='none';

	$selectCartografia='<option value="">Seleccione</option>';
	$disable_id_tipo_cartografia ='disabled="disabled"';
	$cartografia_texto = 'Cartografía';

	$tipos_ciudadanosDatos = tipos_ciudadanosDatos();
	$tipos_categorias_ciudadanosDatos = tipos_categorias_ciudadanosDatos();

	?>
	<title>Create</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="campanasWhatsapp/index.php";
			dataString = 'urlink='+urlink; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			$("#homebody").load(urlink+'?refresh=1');
		}
		function guardar() {
			var coma= /,/g;
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");

			var nombre = document.getElementById("nombre").value; 
			if(nombre == ""){
				document.getElementById("nombre").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Nombre requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			id_api_whatsapp = null;
			id_api_whatsapp = null;

			var tipo_sender = document.getElementById("tipo_sender").value;
			if(tipo_sender == ""){
				document.getElementById("tipo_sender").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Tipo Sender requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}else if (tipo_sender == 2) {
				var id_api_whatsapp = document.getElementById("id_api_whatsapp").value; 
				if(id_api_whatsapp == ""){
					document.getElementById("id_api_whatsapp").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("API Whatsapp requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
			}else if (tipo_sender == 1) {
				var id_whatsapp_python = document.getElementById("id_whatsapp_python").value; 
				if(id_whatsapp_python == ""){
					document.getElementById("id_whatsapp_python").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Whatsapp Python requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
			}else{
				document.getElementById("tipo_sender").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Tipo Sender valido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}


			var status = document.getElementById("status").value; 
			if(status == ""){
				document.getElementById("status").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Estatus requerido");
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

			if(tipo==2){
				var id_encuesta = null;
				var fecha = document.getElementById("fecha").value; 
				if(fecha == ""){
					document.getElementById("fecha").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Fecha requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}

				var hora = document.getElementById("hora").value; 
				if(hora == ""){
					document.getElementById("hora").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Hora requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
			}else if (tipo==3){
				var fecha = null;
				var hora = null;
				var id_encuesta = document.getElementById("id_encuesta").value; 
				if(id_encuesta == ""){
					document.getElementById("id_encuesta").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Encuesta requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
			}else{
				var fecha = null;
				var hora = null;
				var id_encuesta = null;
			}

			var tipo_cartografia = document.getElementById("tipo_cartografia").value; 
			if(tipo_cartografia!=''){
				var id_tipo_cartografia = document.getElementById("id_tipo_cartografia").value; 
				if(id_tipo_cartografia == ""){
					document.getElementById("id_tipo_cartografia").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Cartografia requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
			}else{
				var tipo_cartografia = null;
				var id_tipo_cartografias = null;
			}


			var remove_cuerpo = document.getElementById("remove_cuerpo").value;
			if(remove_cuerpo==0){
				addCuerpo();
			}
			var cuerpo = new nicEditors.findEditor('cuerpo');
			cuerpo = cuerpo.getContent();
			var cuerpo = cuerpo.replace(/^\s+|\s+$/g, "");
			var cuerpo = cuerpo.replace(/&nbsp;/g, "");
			var cuerpo = cuerpo.replace(/<br>/g, "");
			var cuerpo = cuerpo.replace(/<br\s*[\/]?>/gi, "");
			var cuerpo = cuerpo.replace(/<div>/g, "");
			var cuerpo = cuerpo.replace(/<\/div>/g, "");
			var cuerpo = cuerpo.replace(/\s/g, '');
			if(cuerpo == ""){ 
				document.getElementById("sumbmit_prueba_correo").disabled = false;
				$("#mensaje_correo_prueba").html("Cuerpo requerido");
				document.getElementById("mensaje_correo_prueba").classList.add("mensajeError");
				return false;
			}
			var cuerpo = new nicEditors.findEditor('cuerpo');
			cuerpo = cuerpo.getContent();

			var MediaUrl = document.getElementById("MediaUrl").value; 
			/*
			if(MediaUrl == ""){
				document.getElementById("MediaUrl").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Media URL requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			*/

			var campana_whatsapp = [];
			var data = {
					'nombre' : nombre,
					'id_api_whatsapp' : id_api_whatsapp,
					'id_whatsapp_python' : id_whatsapp_python,
					'status' : status,
					'tipo' : tipo,
					'tipo_sender' : tipo_sender,
				}
			campana_whatsapp.push(data);

			var campana_whatsapp_cuerpo = [];
			var data = {
					'cuerpo' : cuerpo,
					'MediaUrl' : MediaUrl,
				}
			campana_whatsapp_cuerpo.push(data);

			var campana_whatsapp_programada = [];
			var data = {
					'fecha' : fecha,
					'hora' : hora,
				}
			campana_whatsapp_programada.push(data);

			var campana_whatsapp_encuesta = [];
			var data = {
					'id_encuesta' : id_encuesta,
				}
			campana_whatsapp_encuesta.push(data);

			var campana_whatsapp_cartografia = [];
			var data = {
					'tipo_cartografia' : tipo_cartografia,
					'id_tipo_cartografia' : id_tipo_cartografia,
				}
			campana_whatsapp_cartografia.push(data);


			var campana_whatsapp_tipo_ciudadano = [];
			<?php
				foreach ($tipos_ciudadanosDatos as $key => $value) {
					?>
					var check = document.getElementById("chk_tc<?=$value['id'] ?>").checked;
					if(check == true){
						check = 1
					}else{
						check = 0;
					}
					var data = {
							'id_tipo_ciudadano' : '<?= $value['id'] ?>',
							'check' : check,
						}
					campana_whatsapp_tipo_ciudadano.push(data);
					<?php
				}
			?>


			var campana_whatsapp_tipo_categoria_ciudadano = [];
			<?php
				foreach ($tipos_categorias_ciudadanosDatos as $key => $value) {
					?>
					var check = document.getElementById("chk_tcc<?=$value['id'] ?>").checked;
					if(check == true){
						check = 1
					}else{
						check = 0;
					}
					var data = {
							'id_tipo_categoria_ciudadano' : '<?= $value['id'] ?>',
							'check' : check,
						}
					campana_whatsapp_tipo_categoria_ciudadano.push(data);
					<?php
				}
			?>

			$.ajax({
				type: "POST",
				url: "campanasWhatsapp/db_add.php",
				data: {campana_whatsapp: campana_whatsapp,campana_whatsapp_cuerpo:campana_whatsapp_cuerpo,campana_whatsapp_programada:campana_whatsapp_programada,campana_whatsapp_cartografia:campana_whatsapp_cartografia,campana_whatsapp_encuesta:campana_whatsapp_encuesta,campana_whatsapp_tipo_ciudadano:campana_whatsapp_tipo_ciudadano,campana_whatsapp_tipo_categoria_ciudadano:campana_whatsapp_tipo_categoria_ciudadano},
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
						urlink="campanasWhatsapp/index.php";
						dataString = 'urlink='+urlink; 
						$.ajax({
							type: "POST",
							url: "functions/backarray.php",
							data: dataString,
							success: function(data) { 	}
						});
						$("#homebody").load(urlink+'?refresh=1');
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
					<font style="font-size: 25px;">Crear Campaña Whatsapp</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para registrar y dar de nombrea a campaña whatsapp.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>