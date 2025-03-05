<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/redirect_security.php"; 
	include __DIR__."/../functions/documentos_oficiales.php";
	include __DIR__."/../functions/documentos_oficiales_images.php";
	include __DIR__."/../functions/plataformas.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}
	echo $redirectSecurity=redirectSecurity($id,'documentos_oficiales','documentosOficiales','index');
	if($redirectSecurity!=""){
		die;
	}

	$id_identidad = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	validar_plataforma_vista($id_identidad,'identidades','identidades','index',$codigo_plataforma);
	if($id_identidad!=""){
		echo $redirectSecurity=redirectSecurity($id_identidad,'identidades','identidades','index');
		if($redirectSecurity!=""){
			die;
		}
	}else{
		echo $redirectSecurity=redirectSecurity($id_identidad,'identidades','identidades','index');
		if($redirectSecurity!=""){
			die;
		}
	}
	$documento_oficialDatos=documento_oficialDatos($id);
	$documento_oficial_imagesDatos=documento_oficial_imagesDatos('',$id);
	

	foreach ($documento_oficial_imagesDatos as $key => $value) {
		//echo $value['tipo_imagen'];
		//echo "--";
		//echo $value['id'];
		//echo "<br>";
		$img_array[$value['tipo_imagen']] = $value;
		
		// Obtén la extensión del archivo desde el 'name' o 'type'
		$file_extension = pathinfo($value['name'], PATHINFO_EXTENSION) ?: pathinfo($value['type'], PATHINFO_EXTENSION);
		
		// Define un arreglo de extensiones de imagen permitidas
		$image_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp');
		
		// Verifica si la extensión está en el arreglo de extensiones de imagen
		if (in_array(strtolower($file_extension), $image_extensions)) {
			$img_array[$value['tipo_imagen']]['tipo_archivo'] = 'img';
			$img_array[$value['tipo_imagen']]['name_img'] = $value['name'];
		} elseif (strtolower($file_extension) == 'pdf') {
			$img_array[$value['tipo_imagen']]['tipo_archivo'] = 'pdf';
			$img_array[$value['tipo_imagen']]['name_pdf'] = $value['name'];
		} else {
			$img_array[$value['tipo_imagen']]['tipo_archivo'] = 'desconocido';
		}
	}

	$permiso="update"; 
	$imagen_file="../../ops/imagen.php?id_img=";
?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="documentosOficiales/index.php";
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
			
			var id = '<?= $id ?>'; 
			if(id == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_identidad = '<?= $id_identidad ?>';
			if (id_identidad === "") {
				document.getElementById("id_identidad").focus();
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
			documento_oficial.append('id', id);
			documento_oficial.append('id_identidad', id_identidad);
			documento_oficial.append('tipo', tipo);
			documento_oficial.append('fecha_emision', fecha_emision);
			documento_oficial.append('fecha_vigencia', fecha_vigencia);
			
			var frente_file = document.getElementById('imagen_frente');
			frente_id = '<?= $img_array['frente']['id'] ?>';
			documento_oficial.append('frente_id',frente_id );
			frente_reset = 0;
			// Ver si existe el boton restablecer
			var elemento = document.getElementById('chk_frente_origin');
			if (elemento !== null) {
				if (elemento.checked) {
					documento_oficial.append('frente_reset',1 );
					frente_reset = 1;
				} else {
					documento_oficial.append('frente_reset',0 );
				}
			}
			// Ver si esta check borrar archivo boton
			var frente_borrar = document.getElementById("chk_frente");
			if (frente_borrar.checked) {
				frente_borrar = 1;
				documento_oficial.append('frente_borrar',1 );
			} else {
				frente_borrar = 0;
			}

			if(frente_id!=""){
				if(frente_borrar==1){
					frente_no_image = 'NOTIENE';
				}else{
					if(frente_reset==1){
						frente_no_image = 'SITIENE';
					}else{
						if(frente_file.files.length > 0){
							frente_no_image = 'SITIENE';
						}else{
							frente_no_image = 'NOTIENE';
						}
					}
				}
			}else{
				if(frente_borrar==1){
					frente_no_image = 'NOTIENE';
				}else{
					if(frente_file.files.length > 0){
						frente_no_image = 'SITIENE';
					}else{
						frente_no_image = 'NOTIENE';
					}
				}
			}

			var atras_file = document.getElementById('imagen_atras');
			atras_id = '<?= $img_array['atras']['id'] ?>';
			documento_oficial.append('atras_id',atras_id );
			atras_reset = 0;
			// Ver si existe el boton restablecer
			var elemento = document.getElementById('chk_atras_origin');
			if (elemento !== null) {
				if (elemento.checked) {
					documento_oficial.append('atras_reset',1 );
					atras_reset = 1;
				} else {
					documento_oficial.append('atras_reset',0 );
				}
			}
			// Ver si esta check borrar archivo boton
			var atras_borrar = document.getElementById("chk_atras");
			if (atras_borrar.checked) {
				atras_borrar = 1;
				documento_oficial.append('atras_borrar',1 );
			} else {
				atras_borrar = 0;
			}
			if(atras_id!=""){
				if(atras_borrar==1){
					atras_no_image = 'NOTIENE';
				}else{
					if(atras_reset==1){
						atras_no_image = 'SITIENE';
					}else{
						if(atras_file.files.length > 0){
							atras_no_image = 'SITIENE';
						}else{
							atras_no_image = 'NOTIENE';
						}
					}
				}
			}else{
				if(atras_borrar==1){
					atras_no_image = 'NOTIENE';
				}else{
					if(atras_file.files.length > 0){
						atras_no_image = 'SITIENE';
					}else{
						atras_no_image = 'NOTIENE';
					}
				}
			}

			var otros_file = document.getElementById('imagen_otros');
			otros_id = '<?= $img_array['otros']['id'] ?>';
			documento_oficial.append('otros_id',otros_id );
			otros_reset = 0;
			// Ver si existe el boton restablecer
			var elemento = document.getElementById('chk_otros_origin');
			if (elemento !== null) {
				if (elemento.checked) {
					documento_oficial.append('otros_reset',1 );
					otros_reset = 1;
				} else {
					documento_oficial.append('otros_reset',0 );
				}
			}
			// Ver si esta check borrar archivo boton
			var otros_borrar = document.getElementById("chk_otros");
			if (otros_borrar.checked) {
				otros_borrar = 1;
				documento_oficial.append('otros_borrar',1 );
			} else {
				otros_borrar = 0;
			}

			if(otros_id!=""){
				if(otros_borrar==1){
					otros_no_image = 'NOTIENE';
				}else{
					if(otros_reset==1){
						otros_no_image = 'SITIENE';
					}else{
						if(otros_file.files.length > 0){
							otros_no_image = 'SITIENE';
						}else{
							otros_no_image = 'NOTIENE';
						}
					}
				}
			}else{
				if(otros_borrar==1){
					otros_no_image = 'NOTIENE';
				}else{
					if(otros_file.files.length > 0){
						otros_no_image = 'SITIENE';
					}else{
						otros_no_image = 'NOTIENE';
					}
				}
			}

			//console.log(frente_no_image);
			//console.log(atras_no_image);
			//console.log(otros_no_image);
			if(frente_no_image=='NOTIENE' && atras_no_image=='NOTIENE' && otros_no_image=='NOTIENE' ){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Archivo adjunto requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			function compressAndAddImage(inputElement, fieldName, ck) {
				return new Promise(function (resolve, reject) {
					if (inputElement.files.length > 0 && ck == 'SITIENE') {
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
			Promise.all([
				compressAndAddImage(frente_file, 'imagen_frente',frente_no_image),
				compressAndAddImage(atras_file, 'imagen_atras',atras_no_image),
				compressAndAddImage(otros_file, 'imagen_otros',otros_no_image)
			])
			.then(function () {
				// Todas las imágenes se han comprimido y agregado al FormData
				$.ajax({
					type: "POST",
					url: "documentosOficiales/db_edit.php",
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
							urlink="documentosOficiales/index.php";
							dataString = 'urlink='+urlink; 
							$.ajax({
								type: "POST",
								url: "functions/backarray.php",
								data: dataString,
								success: function(data) { 	}
							});
							$("#homebody").load(urlink);
						} else {
							if(data==""){
								urlink="documentosOficiales/index.php";
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
		});
	</script>
	<div class="bodymanager" id="bodymanager"> 
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Modificar Documento Oficial</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar a documento oficial.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>