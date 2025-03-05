<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../functions/plataformas.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	$permiso="insert"; 
	$id_seccion_ine_ciudadano = decrypt_ab_checkFinal($_COOKIE['paguinaId_2']);
	validar_plataforma_vista($id_seccion_ine_ciudadano,'secciones_ine_ciudadanos','seccionesIneCiudadanos','index',$codigo_plataforma);
	if($id_seccion_ine_ciudadano!=""){
		$id_seccion_ine_ciudadano;
		$seccion_ine_ciudadanoDatos = seccion_ine_ciudadanoDatos($id_seccion_ine_ciudadano);
		$nombre_completo = $seccion_ine_ciudadanoDatos['nombre_completo'];
		$id_seccion_ine = $seccion_ine_ciudadanoDatos['id_seccion_ine'];
	}else{
		echo $redirectSecurity=redirectSecurity($id_seccion_ine_ciudadano,'secciones_ine_ciudadanos','seccionesIneCiudadanos','index');
		if($redirectSecurity!=""){
			die;
		}
	}
?>
	<title>Create</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="documentosOficialesCiudadanos/index.php";
			dataString = 'urlink='+urlink; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			$("#homebody").load(urlink);
		}
		function compressImageSend(inputFile, quality, callback) {
			const reader = new FileReader();

			reader.onload = function (event) {
				const img = new Image();

				img.onload = function () {
					const canvas = document.createElement('canvas');
					const ctx = canvas.getContext('2d');

					canvas.width = img.width;
					canvas.height = img.height;

					ctx.drawImage(img, 0, 0, img.width, img.height);

					// Comprimir la imagen en formato JPEG con la calidad especificada
					canvas.toBlob(function (blob) {
						callback(blob);
					}, 'image/jpeg', quality);
				};

				img.src = event.target.result;
			};

			reader.readAsDataURL(inputFile);
		}

		function guardar() {
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");
			
			
			var id_seccion_ine_ciudadano = '<?= $id_seccion_ine_ciudadano ?>';
			if (id_seccion_ine_ciudadano === "") {
				document.getElementById("id_seccion_ine_ciudadano").focus();
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Identidad requerida");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var tipo = document.getElementById("tipo").value;
			if (tipo === "") {
				document.getElementById("tipo").focus();
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Tipo requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var fecha_emision = document.getElementById("fecha_emision").value;
			if (fecha_emision === "") {
				document.getElementById("fecha_emision").focus();
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Emisión requerida");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var fecha_vigencia = document.getElementById("fecha_vigencia").value;
			if (fecha_vigencia === "") {
				document.getElementById("fecha_vigencia").focus();
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Vigencia requerida");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var documento_oficial = new FormData();
			// Agregar los valores a FormData
			documento_oficial.append('id_seccion_ine_ciudadano', id_seccion_ine_ciudadano);
			documento_oficial.append('tipo', tipo);
			documento_oficial.append('fecha_emision', fecha_emision);
			documento_oficial.append('fecha_vigencia', fecha_vigencia);
			
			var inputFrente = document.getElementById('imagen_frente');
			imagen_frente_id = '';
			var inputAtras = document.getElementById('imagen_atras');
			imagen_atras_id = '';
			var inputOtros = document.getElementById('imagen_otros');
			imagen_otros_id = '';
			
			
			
			function compressAndAddImage(inputElement, fieldName, ck) {
				return new Promise(function (resolve, reject) {
					if (inputElement.files.length > 0 && ck == 0 ) {
						const fileType = inputElement.files[0].type;
						if (fileType.startsWith('image/')) {
							const compressor = new Compressor(inputElement.files[0], {
								quality: 0.6,
								success(result) {
									documento_oficial.append(fieldName, result, inputElement.files[0].name);
									resolve();
								},
								error(err) {
									console.error(err.message);
									reject(err);
								},
							});
						} else if (fileType === 'application/pdf') {
							documento_oficial.append(fieldName, inputElement.files[0]);
							resolve();
						} else {
							alert(`Archivo en ${fieldName} inválido, debe ser PDF o imagen`);
							reject();
						}
					} else {
						// No se seleccionó ningún archivo
						resolve();
					}
				});
			}

			// Comprimir y agregar imágenes de manera asíncrona
			var frente_ck = document.getElementById("chk_frente");
			if (frente_ck.checked) {
				frente_ck = 1;
			} else {
				frente_ck = 0;
			}

			var atras_ck = document.getElementById("chk_atras");
			if (atras_ck.checked) {
				atras_ck = 1;
			} else {
				atras_ck = 0;
			}

			var otros_ck = document.getElementById("chk_otros");
			if (otros_ck.checked) {
				otros_ck = 1;
			} else {
				otros_ck = 0;
			}

			no_image = 0;
			if(imagen_frente_id != ""){
				if(frente_ck==1){
					if(inputFrente.files.length > 0){
						//rente_ck = 0
					}else{
						no_image = no_image + 1;
					}
				}
			}else{
				if(inputFrente.files.length == 0){
					no_image = no_image + 1;
				}else{
					if(frente_ck==1){
						no_image = no_image + 1;
					}else{
						frente_ck = 0;
					}
				}
			}
			
			if(imagen_atras_id != ""){
				if(atras_ck==1){
					if(inputAtras.files.length > 0){
						//atras_ck = 0
					}else{
						no_image = no_image + 1;
					}
				}
			}else{
				if(inputAtras.files.length == 0){
					no_image = no_image + 1;
				}else{
					if(atras_ck==1){
						no_image = no_image + 1;
					}else{
						atras_ck = 0;
					}
				}
			}
			
			if(imagen_otros_id != ""){
				if(atras_ck==1){
					if(inputOtros.files.length > 0){
						//atras_ck = 0
					}else{
						no_image = no_image + 1;
					}
				}
			}else{
				if(inputOtros.files.length == 0){
					no_image = no_image + 1;
				}else{
					if(otros_ck==1){
						no_image = no_image + 1;
					}else{
						otros_ck = 0;
					}
				}
			}

			if (no_image==3) {
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Archivo adjunto requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			documento_oficial.append('frente_ck', frente_ck);
			documento_oficial.append('atras_ck', atras_ck);
			documento_oficial.append('otros_ck', otros_ck);

			Promise.all([
				compressAndAddImage(inputFrente, 'imagen_frente',frente_ck),
				compressAndAddImage(inputAtras, 'imagen_atras',atras_ck),
				compressAndAddImage(inputOtros, 'imagen_otros',otros_ck)
			])
			.then(function () {
				// Todas las imágenes se han comprimido y agregado al FormData
				$.ajax({
					type: "POST",
					url: "documentosOficialesCiudadanos/db_add.php",
					data: documento_oficial,
					processData: false,
					contentType: false,
					success: function(data) {
						if (data === "SI") {
							document.getElementById("sumbmit").disabled = true;
							$("#mensaje").html("&nbsp;");
							document.getElementById("mensaje").classList.remove("mensajeError");
							$("#mensaje").html("Guardado con éxito");
							document.getElementById("mensaje").classList.add("mensajeSucces");
							urlink="documentosOficialesCiudadanos/index.php";
							dataString = 'urlink='+urlink; 
							$.ajax({
								type: "POST",
								url: "functions/backarray.php",
								data: dataString,
								success: function(data) { 	}
							});
							$("#homebody").load(urlink);
						} else {
							$("#mensaje").html(data);
							document.getElementById("sumbmit").disabled = true;
						}
					},
					error: function(error) {
						console.log(error);
					},
				});
			})
			.catch(function (error) {
				// Manejo de errores si algo sale mal con la compresión
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
			$("#mensajeImageDelantera").click(function(event) { 
				document.getElementById("mensajeImageDelantera").classList.remove("mensajeSucces");
				document.getElementById("mensajeImageDelantera").classList.remove("mensajeError");
				$("#mensajeImageDelantera").html("&nbsp");
			});
			$("#mensajeImageTrasera").click(function(event) { 
				document.getElementById("mensajeImageTrasera").classList.remove("mensajeSucces");
				document.getElementById("mensajeImageTrasera").classList.remove("mensajeError");
				$("#mensajeImageTrasera").html("&nbsp");
			});
			$("#mensajeImageOtro").click(function(event) { 
				document.getElementById("mensajeImageOtro").classList.remove("mensajeSucces");
				document.getElementById("mensajeImageOtro").classList.remove("mensajeError");
				$("#mensajeImageOtro").html("&nbsp");
			});
		});
	</script>
	<div class="bodymanager" id="bodymanager"> 
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Crear Documento Oficial</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para registrar y dar de alta a documento oficial.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>