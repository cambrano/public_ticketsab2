<?php
	include '../functions/usuario_permisos.php';
	$modulosPermiso = modulosPermiso('sistema_unico_beneficiarios','',$_COOKIE["id_usuario"]);
	if($modulosPermiso['qr_scanner_ciudadano'] || $modulosPermiso['all'] ){
		$qr_scanner_ciudadano = true;
	}
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['delete'] || $moduloAccionPermisos['all']){
		$option_delete = true;
	}
	
	if(empty($qr_scanner_ciudadano)){
		?>
		<script type="text/javascript">
			document.getElementById("mensaje").classList.add("mensajeError");
			$("#mensaje").html("No tiene permiso");
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
<style type="text/css">
	.scaner {
		height: auto;
		width: 80%;
	}

	@media only screen and (max-width: 820px) {
		/* For mobile phones: */
		.scaner {
			height: auto;
			width: 100%;
		}
	}
</style>

<div style=" width: 100%; display:inline-block; text-align: left;">
	<div class="sucFormTitulo">
		<label class="labelForm" id="labeltemaname">Código QR Ciudadano</label>
	</div>
	<div class="sucForm" style="width: 100%;text-align: center;">
		<div id="divDevices" style="display: none"></div>
		<div id="divDevicesCamara" style="display: none"></div>
		<select id="id_camaras" onchange="cambioCamara(this.value)"></select>
		<div id="loadingMessage">🎥 No se puede acceder a la transmisión de video (asegúrese de tener una cámara web habilitada)</div>
	</div>
	<div class="sucForm" style="width: 100%;text-align: center;">
		<button id="stop-video" class="btn btn-primary bt_responsive"  onclick="stopvideo()" >Detener video</button>
		<button id="escanearOtro" class="btn btn-primary bt_responsive" onClick="reiniciarEscaneo();" style="<?php echo is_null($seccion_ine_ciudadanoDatos['id']) ? 'display:none' : '' ?>">Escanear otro</button>
	</div>
	
	
	<div class="sucForm"  id="output" hidden="hidden" style="width: 100%;text-align: center;">
		<div id="outputMessage" style="display:none">QR No Detectado.</div>
		<div hidden="hidden"><b></b> <span id="outputData"></span></div>
	</div>
	<center>
		<div class="sucForm" id="output_camara" style="text-align: center;width:100%;" <?php echo is_null($seccion_ine_ciudadanoDatos['id']) ? '' : 'hidden' ?> >
			<canvas id="canvas" class="scaner" hidden="hidden"></canvas>
		</div>
	</center>
	<script src="qrScannerCiudadano/jsQR.js"></script>
	<div class="sucForm" style="width:100%"></div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
		<input class="inputlogin" type="text"  name="clave" autocomplete="off"  id="clave" value="<?= $seccion_ine_ciudadanoDatos['clave'] ?>" placeholder="Clave" onblur="aMays(event, this)" size="40" /><br>
	</div>
	<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
		<label class="labelForm" id="labeltemaname"><br></label><br>
		<input type="button" id='buscar_clave' value="Buscar" onclick="buscarClave()">
	</div>
	<div class="sucForm" style="width:100%" ></div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Folio<font color="#FF0004">*</font></label><br>
		<input class="inputlogin" type="text"  name="folio" autocomplete="off"  id="folio" value="<?= $seccion_ine_ciudadanoDatos['folio'] ?>" placeholder="Folio" onblur="aMays(event, this)" size="40" readonly/><br>
	</div>
	<div class="sucForm" style="display: none">
		<label class="labelForm" id="labeltemaname">Id<font color="#FF0004">*</font></label><br>
		<input class="inputlogin" type="text"  name="id" autocomplete="off"  id="id" value="<?= $seccion_ine_ciudadanoDatos["id"] ?>" placeholder="id" size="40"  readonly/><br>
	</div>
	<div class="sucForm" style="display: none">
		<label class="labelForm" id="labeltemaname">expdiente<font color="#FF0004">*</font></label><br>
		<input class="inputlogin" type="text"  name="expediente" autocomplete="off"  id="expediente" value="<?= $seccion_ine_ciudadanoDatos['expediente'] ?>" placeholder="expdiente" size="40"  readonly/><br>
	</div>
	<div class="sucForm" style="width:100%"></div>
	<div class="sucForm" style="display: none">
		<label class="labelForm" id="labeltemaname">Tipo<font color="#FF0004">*</font></label><br>
		<input class="inputlogin" type="text"  name="tipo" autocomplete="off"  id="tipo" value="" placeholder="Tipo" onblur="aMays(event, this)" size="40"  readonly/><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Clave Electoral<font color="#FF0004">*</font></label><br>
		<input class="inputlogin" type="text"  name="clave_elector" autocomplete="off"  id="clave_elector" value="<?= $seccion_ine_ciudadanoDatos['clave_elector'] ?>" placeholder="Clave Elector" onblur="aMays(event, this)" size="40"  readonly/><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">C.U.R.P<font color="#FF0004">*</font></label><br>
		<input class="inputlogin" type="text"  name="curp" autocomplete="off"  id="curp" value="<?= $seccion_ine_ciudadanoDatos['curp'] ?>" placeholder="C.U.R.P" onblur="aMays(event, this)" size="40" readonly/><br>
	</div>
	<div class="sucForm" style="width:100%"></div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Nombre<font color="#FF0004">*</font></label><br>
		<input class="inputlogin" type="text"  name="nombre" autocomplete="off"  id="nombre" value="<?= $seccion_ine_ciudadanoDatos['nombre'] ?>" placeholder="Nombre" onblur="aMays(event, this)" size="40" readonly/><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Apellido Paterno<font color="#FF0004">*</font></label><br>
		<input class="inputlogin" type="text"  name="apellido_paterno" autocomplete="off"  id="apellido_paterno" value="<?= $seccion_ine_ciudadanoDatos['apellido_paterno'] ?>" placeholder="Apellido Paterno" onblur="aMays(event, this)" size="40" readonly/><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Apellido Materno<font color="#FF0004">*</font></label><br>
		<input class="inputlogin" type="text"  name="apellido_materno" autocomplete="off"  id="apellido_materno" value="<?= $seccion_ine_ciudadanoDatos['apellido_materno'] ?>" placeholder="Apellido Materno" onblur="aMays(event, this)" size="40" readonly/><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Sección<font color="#FF0004">*</font></label><br>
		<input class="inputlogin" type="text"  name="seccion" autocomplete="off"  id="seccion" value="<?= $seccion_ine_ciudadanoDatos['seccion'] ?>" placeholder="Sección" size="40" readonly/><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Municipio<font color="#FF0004">*</font></label><br>
		<input class="inputlogin" type="text"  name="municipio" autocomplete="off"  id="municipio" value="<?= $seccion_ine_ciudadanoDatos['municipio'] ?>" placeholder="Municipio" onblur="aMays(event, this)" size="40" readonly/><br>
	</div>

	<script type="text/javascript">
		var scanning = true;

		function cambioCamara(value) {
			var id_camara = value;
			var camara = [];
			var info = {
				'id_camara': id_camara,
			}
			camara.push(info);
			$.ajax({
				type: "POST",
				url: "qrScannerCiudadano/camara.php",
				data: { camara: camara },
				success: function(data) {
					$("#divDevicesCamara").html(data);
					location.reload();
				}
			});
		}

		if (!navigator.mediaDevices || !navigator.mediaDevices.enumerateDevices) {
			//console.log("enumerateDevices() not supported.");
		}

		select = document.getElementById('id_camaras');
		var device_div = "";
		var id_camara = "";
		navigator.mediaDevices.enumerateDevices()
		.then(function(devices) {
			devices.forEach(function(device) {
				if (device.kind == 'videoinput') {
					device_div = device_div + "<br>" + device.kind + ": " + device.label + " id = " + device.deviceId;
					var opt = document.createElement('option');
					opt.value = device.deviceId;
					str = device.label;
					var res = str.toUpperCase();
					opt.innerHTML = res;

					select.appendChild(opt);
					id_camara = device.deviceId;
					<?php
					if ($_COOKIE['camdv'] != "") {
						?>
						id_camara = '<?= $_COOKIE['camdv'] ?>';
					<?php
					} else {
						?>
						id_camara = device.deviceId;
					<?php
					}
					?>
				}
			});
			document.getElementById('divDevices').innerHTML = device_div;
			navigator.mediaDevices.getUserMedia({ video: { deviceId: id_camara } }).then(function(stream) {
				video.srcObject = stream;
				video.setAttribute("playsinline", false);
				video.play();
				requestAnimationFrame(tick);
			});

			select.value = id_camara;
		})
		.catch(function(err) {
			//console.log(err.name + ": " + err.message);
		});

		var video = document.createElement("video");
		var canvasElement = document.getElementById("canvas");
		var canvas = canvasElement.getContext("2d");
		var loadingMessage = document.getElementById("loadingMessage");
		var outputContainer = document.getElementById("output");
		var outputMessage = document.getElementById("outputMessage");
		var outputData = document.getElementById("outputData");
		var escanearOtroButton = document.getElementById("escanearOtro");
		var stopvideoButton = document.getElementById("stop-video");

		function drawLine(begin, end, color) {
			canvas.beginPath();
			canvas.moveTo(begin.x, begin.y);
			canvas.lineTo(end.x, end.y);
			canvas.lineWidth = 4;
			canvas.strokeStyle = color;
			canvas.stroke();
		}

		function reiniciarEscaneo() {
			var id_camaras = document.getElementById("id_camaras").value;
			navigator.mediaDevices.getUserMedia({ video: { deviceId: id_camara } }).then(function(stream) {
				video.srcObject = stream;
				video.setAttribute("playsinline", false);
				video.play();
				requestAnimationFrame(tick);
			});
			scanning = true;
			canvasElement.hidden = false;
			outputContainer.hidden = true;
			escanearOtroButton.style.display = "none";
			stopvideoButton.style.display = "inline-block";
			$("#data").html("");
			$("#output_camara").show();
			document.getElementById("clave_elector").value = '';
			document.getElementById("curp").value = '';
			document.getElementById("clave").value = '';
			document.getElementById("folio").value = '';
			document.getElementById("nombre").value = '';
			document.getElementById("apellido_paterno").value = '';
			document.getElementById("apellido_materno").value = '';
			document.getElementById("seccion").value = '';
			document.getElementById("municipio").value = '';
			document.getElementById("id").value = '';
			document.getElementById("expediente").value = '';
			//requestAnimationFrame(tick);
			var scaneo = [];
			var info = { 
				'scanner': 1,
			}
			scaneo.push(info);
			$.ajax({
				type: "POST",
				url: "qrScannerCiudadano/delete.php",
				dataType: 'json',
				data: { scaneo: scaneo },
				success: function(data) {
				}
			});
		}

		function tick() {
			loadingMessage.innerText = "⌛ Iniciando Cámara..."
			if (video.readyState === video.HAVE_ENOUGH_DATA && scanning) {
				loadingMessage.hidden = true;
				canvasElement.hidden = false;
				outputContainer.hidden = false;
				canvasElement.height = video.videoHeight;
				canvasElement.width = video.videoWidth;
				canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
				var imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
				var code = jsQR(imageData.data, imageData.width, imageData.height, {
					inversionAttempts: "dontInvert",
				});
				if (code) {
					if(code.data!=''){
						drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#FF3B58");
						drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#FF3B58");
						drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#FF3B58");
						drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#FF3B58");
						setTimeout(function () {
							//! paramos el video
							outputMessage.hidden = true;
							outputData.parentElement.hidden = false;
							code.data;
							scanning = false;
							canvasElement.hidden = true;
							escanearOtroButton.style.display = "inline-block";
							stopvideo();
							$("#output_camara").hide();
							var scaneo = [];
							var info = { 
								'scanner': code.data,
							}
							scaneo.push(info);
							$.ajax({
								type: "POST",
								url: "qrScannerCiudadano/data.php",
								dataType: 'json',
								data: { scaneo: scaneo },
								success: function(data) {
									document.getElementById("id").value = data.id;
									document.getElementById("expediente").value = data.expediente;
									document.getElementById("tipo").value = data.tipo;
									document.getElementById("clave_elector").value = data.clave_elector;
									document.getElementById("curp").value = data.curp;
									document.getElementById("clave").value = data.clave_ciudadano;
									document.getElementById("folio").value = data.folio;
									document.getElementById("nombre").value = data.nombre;
									document.getElementById("apellido_paterno").value = data.apellido_paterno;
									document.getElementById("apellido_materno").value = data.apellido_materno;
									document.getElementById("seccion").value = data.seccion;
									document.getElementById("municipio").value = data.municipio;
									return false;
								}
							});
						}, 1000);
					}
				}
			}
			requestAnimationFrame(tick);
		}
		// Declara videoStream en un alcance accesible desde detenerCamara
		function stopvideo1(){
			var mediaStream = null;
			var id_camaras = document.getElementById("id_camaras").value;
			navigator.mediaDevices.getUserMedia({ video: { deviceId: id_camara } }).then(function(stream) {
				stream.getTracks().forEach((track) => {
					if (track.readyState == 'live' && track.kind === 'video') {
						track.stop();
						track = null;
					}
				});
				video.srcObject = null;
				//requestAnimationFrame(tick);
				// Limpiar el srcObject del elemento de video
				video.srcObject = null;
				// Establecer el fondo negro en el elemento de video
				video.style.backgroundColor = 'black';
				// También puedes establecer la opacidad a 0 si deseas que sea completamente invisible
				// video.style.opacity = 0;
			});
		}
		function stopvideo() {
			// Obtén las dimensiones del canvas
			var canvasAncho = canvasElement.width;
			var canvasAlto = canvasElement.height;
			// Utiliza clearRect para limpiar todo el contenido del canvas
			canvas.clearRect(0, 0, canvasAncho, canvasAlto);
			stopvideoButton.style.display = "none";
			scanning = false;
			canvasElement.hidden = true;
			escanearOtroButton.style.display = "inline-block";
			var id_camara = document.getElementById("id_camaras").value;
			navigator.mediaDevices.getUserMedia({ video: { deviceId: id_camara } }).then(function(stream) {
				// Detener todas las pistas de video en el stream
				stream.getTracks().forEach((track) => {
					if (track.readyState == 'live' && track.kind === 'video') {
						track.stop();
						track = null;
					}
				});
				// Limpiar el srcObject del elemento de video
				video.srcObject = null;
				// Establecer el fondo negro en el elemento de video
				video.style.backgroundColor = 'black';
				// También puedes establecer la opacidad a 0 si deseas que sea completamente invisible
				// video.style.opacity = 0;
			}).catch(function(error) {
				console.error('Error al detener la transmisión de la cámara: ', error);
			});
		}
		function buscarClave(){
			document.getElementById("buscar_clave").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");
			var espacios_invalidos= /\s+/g;

			var clave = document.getElementById("clave").value; 
			clave = clave.trim();
			clavex = clave.replace(espacios_invalidos, '');
			if(clavex == ""){
				document.getElementById("clave").focus(); 
				document.getElementById("buscar_clave").disabled = false;
				$("#mensaje").html("Clave requerido");
				document.getElementById("mensaje").classList.add("mensajeError"); 
				return false;
			}

			var buscador = [];
			var info = { 
				'clave': clave,
			}
			buscador.push(info);
			$.ajax({
				type: "POST",
				url: "qrScannerCiudadano/data_clave.php",
				dataType: 'json',
				data: { buscador: buscador },
				success: function(data) {
					document.getElementById("id").value = data.id;
					document.getElementById("expediente").value = data.expediente;
					document.getElementById("tipo").value = data.tipo;
					document.getElementById("clave_elector").value = data.clave_elector;
					document.getElementById("curp").value = data.curp;
					document.getElementById("folio").value = data.folio;
					document.getElementById("nombre").value = data.nombre;
					document.getElementById("apellido_paterno").value = data.apellido_paterno;
					document.getElementById("apellido_materno").value = data.apellido_materno;
					document.getElementById("seccion").value = data.seccion;
					document.getElementById("municipio").value = data.municipio;
					document.getElementById("buscar_clave").disabled = false;
					return false;
				}
			});
		}
	</script>
	<div class="sucFormTitulo" style="width:100%">
		<label class="labelForm" id="labeltemaname">Opciones</label>
	</div>
	<?php
	if( $moduloAccionPermisos['view'] || $moduloAccionPermisos['update'] || $moduloAccionPermisos['all'] ){
		?>
		<div class="sucForm">
			<button class="btn btn-info bt_responsive"  onClick="edit();" >
					<span class="btnImage"><img class="bntImageSize" src="img/editar20.png"></span>
					<span class="btnText">Editar</span></button>
		</div>
		<?php
	}
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['delete'] || $moduloAccionPermisos['all']){
		?>
		<div class="sucForm">
		<button class="btn btn-danger bt_responsive"  onClick="borrar();" >
						<span class="btnImage"><img class="bntImageSize" src="img/eliminar20.png"></span>
						<span class="btnText">Eliminar</span></button>
		</div>
		<?php
	}
	if($modulosPermiso['secciones_ine_ciudadanos'] || $modulosPermiso['all'] ){
		?>
		<div class="sucForm">
			<button class="btn btn-primary bt_responsive" onClick="secciones_ine_ciudadanos_expediente();">Expediente</button>
		</div>
		<?php
	}
	if($modulosPermiso['documentos_oficiales'] || $modulosPermiso['all'] ){
		?>
		<div class="sucForm">
			<button class="btn btn-primary bt_responsive"  onClick="documentos_oficiales();" >Documentos</button>
		</div>
		<?php
	}
	if($modulosPermiso['secciones_ine_ciudadanos_giras'] || $modulosPermiso['all'] ){
		?>
		<div class="sucForm">
			<button class="btn btn-primary bt_responsive"  onClick="secciones_ine_gira();" >Participación Giras</button>
		</div>
		<?php
	}
	if($modulosPermiso['militantes_partidos'] || $modulosPermiso['all'] ){
		?>
		<div class="sucForm">
			<button class="btn btn-primary bt_responsive"  onClick="militantes_partidos();" >Militante</button>
		</div>
		<?php
	}
	if($modulosPermiso['secciones_ine_ciudadanos_programas_apoyos'] || $modulosPermiso['all'] ){
		?>
		<div class="sucForm">
			<button class="btn btn-primary bt_responsive"  onClick="programas_apoyos();" >Programa Apoyo</button>
		</div>
		<?php
	}
	if($modulosPermiso['secciones_ine_ciudadanos_categorias'] || $modulosPermiso['all'] ){
		?>
		<div class="sucForm">
			<button class="btn btn-primary bt_responsive"  onClick="ciudadano_categoria();" >Categoria</button>
		</div>
		<?php
	}
	if($modulosPermiso['secciones_ine_ciudadanos_encuestas'] || $modulosPermiso['all'] ){
		?>
		<div class="sucForm">
			<button class="btn btn-primary bt_responsive"  onClick="encuestas();" >Encuestas</button>
		</div>
		<?php
	}
	if($modulosPermiso['secciones_ine_ciudadanos_seguimientos'] || $modulosPermiso['all'] ){
		?>
		<div class="sucForm">
			<button class="btn btn-primary bt_responsive"  onClick="seguimientos();" >Seguimientos</button>
		</div>
		<?php
	} 
	if($modulosPermiso['secciones_ine_ciudadanos_grupos'] || $modulosPermiso['all'] ){
		?>
		<div class="sucForm">
			<button class="btn btn-primary bt_responsive"  onClick="secciones_ine_ciudadanos_grupos();" >Grupos Afiliado</button>
		</div>
		<?php
	}
	?>
</div>
<script type="text/javascript">
	function borrar(){
		$("#mensaje").html("&nbsp");
		document.getElementById("mensaje").classList.remove("mensajeSucces");
		document.getElementById("mensaje").classList.remove("mensajeError");
		$("#mensaje").html("&nbsp");
		var espacios_invalidos= /\s+/g;
		
		var expediente = document.getElementById("expediente").value; 
		var id = document.getElementById("id").value; 
		expedientex = expediente.replace(espacios_invalidos, '');
		idx = id.replace(espacios_invalidos, '');
		if(expedientex == "" || idx =="" ){
			$("#mensaje").html("Es necesario escanear un QR");
			document.getElementById("mensaje").classList.add("mensajeError");
			return false;
		}
		link="seccionesIneCiudadanos/deleterapid.php?id="+id; 
		var link2="seccionesIneCiudadanos/delete.php";
		dataString = 'urlink='+link2; 
		$.ajax({
			type: "POST",
			url: "functions/backarray.php",
			data: dataString,
			success: function(data) {}
			});
		//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
		$("#homebody").load(link+"&refresh=1");
	}
	function edit(){
		$("#mensaje").html("&nbsp");
		document.getElementById("mensaje").classList.remove("mensajeSucces");
		document.getElementById("mensaje").classList.remove("mensajeError");
		$("#mensaje").html("&nbsp");
		var espacios_invalidos= /\s+/g;
		
		var expediente = document.getElementById("expediente").value; 
		var id = document.getElementById("id").value; 
		expedientex = expediente.replace(espacios_invalidos, '');
		idx = id.replace(espacios_invalidos, '');
		if(expedientex == "" || idx =="" ){
			$("#mensaje").html("Es necesario escanear un QR");
			document.getElementById("mensaje").classList.add("mensajeError");
			return false;
		}
		link="seccionesIneCiudadanos/updaterapid.php?id="+id; 
		var link2="seccionesIneCiudadanos/update.php";
		dataString = 'urlink='+link2;  
		$.ajax({
			type: "POST",
			url: "functions/backarray.php",
			data: dataString,
			success: function(data) {}
		});
		//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
		//$("#homebody").load(link);
		$("#homebody").load(link+"&refresh=1");
	}
	function secciones_ine_ciudadanos_expediente(){
		$("#mensaje").html("&nbsp");
		document.getElementById("mensaje").classList.remove("mensajeSucces");
		document.getElementById("mensaje").classList.remove("mensajeError");
		$("#mensaje").html("&nbsp");
		var espacios_invalidos= /\s+/g;
		
		var expediente = document.getElementById("expediente").value; 
		var id = document.getElementById("id").value; 
		expedientex = expediente.replace(espacios_invalidos, '');
		idx = id.replace(espacios_invalidos, '');
		if(expedientex == "" || idx =="" ){
			$("#mensaje").html("Es necesario escanear un QR");
			document.getElementById("mensaje").classList.add("mensajeError");
			return false;
		}
		var link="seccionesIneCiudadanos/print/index.php?cot="+expediente;
		window.open(link); 
		return false;
	}
	function documentos_oficiales(){
		$("#mensaje").html("&nbsp");
		document.getElementById("mensaje").classList.remove("mensajeSucces");
		document.getElementById("mensaje").classList.remove("mensajeError");
		$("#mensaje").html("&nbsp");
		var espacios_invalidos= /\s+/g;
		
		var expediente = document.getElementById("expediente").value; 
		var id = document.getElementById("id").value; 
		expedientex = expediente.replace(espacios_invalidos, '');
		idx = id.replace(espacios_invalidos, '');
		if(expedientex == "" || idx =="" ){
			$("#mensaje").html("Es necesario escanear un QR");
			document.getElementById("mensaje").classList.add("mensajeError");
			return false;
		}
		link="documentosOficialesCiudadanos/index.php?cot="+id; 
		var link2="documentosOficialesCiudadanos/index.php";
		dataString = 'urlink='+link2;  
		$.ajax({
			type: "POST",
			url: "functions/backarray.php",
			data: dataString,
			success: function(data) {}
			});
		//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
		$("#homebody").load(link);
		$("#homebody").hide();
		setTimeout(function () {
            location.reload();
        }, 500);
	}
	function secciones_ine_gira(){
		$("#mensaje").html("&nbsp");
		document.getElementById("mensaje").classList.remove("mensajeSucces");
		document.getElementById("mensaje").classList.remove("mensajeError");
		$("#mensaje").html("&nbsp");
		var espacios_invalidos= /\s+/g;
		
		var expediente = document.getElementById("expediente").value; 
		var id = document.getElementById("id").value; 
		expedientex = expediente.replace(espacios_invalidos, '');
		idx = id.replace(espacios_invalidos, '');
		if(expedientex == "" || idx =="" ){
			$("#mensaje").html("Es necesario escanear un QR");
			document.getElementById("mensaje").classList.add("mensajeError");
			return false;
		}
		link="seccionesIneCiudadanosGiras/index.php?cot="+id; 
		var link2="seccionesIneCiudadanosGiras/index.php";
		dataString = 'urlink='+link2;  
		$.ajax({
			type: "POST",
			url: "functions/backarray.php",
			data: dataString,
			success: function(data) {}
			});
		//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
		$("#homebody").load(link);
		$("#homebody").hide();
		setTimeout(function () {
            location.reload();
        }, 500);
	}
	function militantes_partidos(){
		$("#mensaje").html("&nbsp");
		document.getElementById("mensaje").classList.remove("mensajeSucces");
		document.getElementById("mensaje").classList.remove("mensajeError");
		$("#mensaje").html("&nbsp");
		var espacios_invalidos= /\s+/g;
		
		var expediente = document.getElementById("expediente").value; 
		var id = document.getElementById("id").value; 
		expedientex = expediente.replace(espacios_invalidos, '');
		idx = id.replace(espacios_invalidos, '');
		if(expedientex == "" || idx =="" ){
			$("#mensaje").html("Es necesario escanear un QR");
			document.getElementById("mensaje").classList.add("mensajeError");
			return false;
		}
		link="militantesPartidos/index.php?cot="+id; 
		var link2="militantesPartidos/index.php";
		dataString = 'urlink='+link2;  
		$.ajax({
			type: "POST",
			url: "functions/backarray.php",
			data: dataString,
			success: function(data) {}
			});
		//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
		$("#homebody").load(link);
		$("#homebody").hide();
		setTimeout(function () {
            location.reload();
        }, 500);
	}
	function programas_apoyos(){
		$("#mensaje").html("&nbsp");
		document.getElementById("mensaje").classList.remove("mensajeSucces");
		document.getElementById("mensaje").classList.remove("mensajeError");
		$("#mensaje").html("&nbsp");
		var espacios_invalidos= /\s+/g;
		
		var expediente = document.getElementById("expediente").value; 
		var id = document.getElementById("id").value; 
		expedientex = expediente.replace(espacios_invalidos, '');
		idx = id.replace(espacios_invalidos, '');
		if(expedientex == "" || idx =="" ){
			$("#mensaje").html("Es necesario escanear un QR");
			document.getElementById("mensaje").classList.add("mensajeError");
			return false;
		}
		link="seccionesIneCiudadanosProgramasApoyos/index.php?cot="+id; 
		var link2="seccionesIneCiudadanosProgramasApoyos/index.php";
		dataString = 'urlink='+link2;  
		$.ajax({
			type: "POST",
			url: "functions/backarray.php",
			data: dataString,
			success: function(data) {}
			});
		//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
		$("#homebody").load(link);
		$("#homebody").hide();
		setTimeout(function () {
            location.reload();
        }, 500);
	}
	function ciudadano_categoria(){
		$("#mensaje").html("&nbsp");
		document.getElementById("mensaje").classList.remove("mensajeSucces");
		document.getElementById("mensaje").classList.remove("mensajeError");
		$("#mensaje").html("&nbsp");
		var espacios_invalidos= /\s+/g;
		
		var expediente = document.getElementById("expediente").value; 
		var id = document.getElementById("id").value; 
		expedientex = expediente.replace(espacios_invalidos, '');
		idx = id.replace(espacios_invalidos, '');
		if(expedientex == "" || idx =="" ){
			$("#mensaje").html("Es necesario escanear un QR");
			document.getElementById("mensaje").classList.add("mensajeError");
			return false;
		}
		link="seccionesIneCiudadanosCategorias/index.php?cot="+id; 
		var link2="seccionesIneCiudadanosCategorias/index.php";
		dataString = 'urlink='+link2;  
		$.ajax({
			type: "POST",
			url: "functions/backarray.php",
			data: dataString,
			success: function(data) {}
		});
		//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
		$("#homebody").load(link);
		$("#homebody").hide();
		setTimeout(function () {
            location.reload();
        }, 500);
	}
	function encuestas(){
		$("#mensaje").html("&nbsp");
		document.getElementById("mensaje").classList.remove("mensajeSucces");
		document.getElementById("mensaje").classList.remove("mensajeError");
		$("#mensaje").html("&nbsp");
		var espacios_invalidos= /\s+/g;
		
		var expediente = document.getElementById("expediente").value; 
		var id = document.getElementById("id").value; 
		expedientex = expediente.replace(espacios_invalidos, '');
		idx = id.replace(espacios_invalidos, '');
		if(expedientex == "" || idx =="" ){
			$("#mensaje").html("Es necesario escanear un QR");
			document.getElementById("mensaje").classList.add("mensajeError");
			return false;
		}
		link="seccionesIneCiudadanosEncuestas/index.php?cot="+id; 
		var link2="seccionesIneCiudadanosEncuestas/index.php";
		dataString = 'urlink='+link2;  
		$.ajax({
			type: "POST",
			url: "functions/backarray.php",
			data: dataString,
			success: function(data) {}
			});
		//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
		$("#homebody").load(link);
		$("#homebody").hide();
		setTimeout(function () {
            location.reload();
        }, 500);
	}
	function seguimientos(){
		$("#mensaje").html("&nbsp");
		document.getElementById("mensaje").classList.remove("mensajeSucces");
		document.getElementById("mensaje").classList.remove("mensajeError");
		$("#mensaje").html("&nbsp");
		var espacios_invalidos= /\s+/g;
		
		var expediente = document.getElementById("expediente").value; 
		var id = document.getElementById("id").value; 
		expedientex = expediente.replace(espacios_invalidos, '');
		idx = id.replace(espacios_invalidos, '');
		if(expedientex == "" || idx =="" ){
			$("#mensaje").html("Es necesario escanear un QR");
			document.getElementById("mensaje").classList.add("mensajeError");
			return false;
		}
		link="seccionesIneCiudadanosSeguimientos/index.php?cot="+id; 
		var link2="seccionesIneCiudadanosSeguimientos/index.php";
		dataString = 'urlink='+link2;  
		$.ajax({
			type: "POST",
			url: "functions/backarray.php",
			data: dataString,
			success: function(data) {}
			});
		//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
		$("#homebody").load(link);
		$("#homebody").hide();
		setTimeout(function () {
            location.reload();
        }, 500);
	}
	function secciones_ine_ciudadanos_grupos(){
		$("#mensaje").html("&nbsp");
		document.getElementById("mensaje").classList.remove("mensajeSucces");
		document.getElementById("mensaje").classList.remove("mensajeError");
		$("#mensaje").html("&nbsp");
		var espacios_invalidos= /\s+/g;
		
		var expediente = document.getElementById("expediente").value; 
		var id = document.getElementById("id").value; 
		expedientex = expediente.replace(espacios_invalidos, '');
		idx = id.replace(espacios_invalidos, '');
		if(expedientex == "" || idx =="" ){
			$("#mensaje").html("Es necesario escanear un QR");
			document.getElementById("mensaje").classList.add("mensajeError");
			return false;
		}
		link="seccionesIneCiudadanosGrupos/index.php?cot="+id; 
		var link2="seccionesIneCiudadanosGrupos/index.php";
		dataString = 'urlink='+link2;  
		$.ajax({
			type: "POST",
			url: "functions/backarray.php",
			data: dataString,
			success: function(data) {}
			});
		//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
		$("#homebody").load(link);
		$("#homebody").hide();
		setTimeout(function () {
            location.reload();
        }, 500);
	}
	
	$(".myselect").select2();
</script>