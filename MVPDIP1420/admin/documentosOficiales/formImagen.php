<script src="https://cdnjs.cloudflare.com/ajax/libs/compressorjs/1.0.6/compressor.min.js"></script>
	<script>
		function img_frente_delete(){
			document.getElementById("chk_frente").checked = true;
			var imagen = document.getElementById("preview_frente");
			imagen.value="";
			imagen.removeAttribute("src");
			imagen.removeAttribute("pdf_preview_frente");
			//imagen.hidden = true;
			var pdf = document.getElementById("pdf_preview_frente");
			pdf.value="";
			pdf.removeAttribute("src");
			pdf.removeAttribute("pdf_preview_frente");
			//pdf.hidden = true;
			var elemento = document.getElementById('chk_frente_origin');
			if (elemento !== null) {
				elemento.checked = false;
			}
		}
		function img_frente_reset(){
			document.getElementById("chk_frente").checked = false;
			<?php
			if($img_array['frente']['name_img']){
				?>
				document.getElementById('chk_frente_origin').checked = true;
				var imagen = document.getElementById("preview_frente");
				imagen.src = '<?= $imagen_file.$img_array['frente']['name_img'] ?>';
				//imagen.hidden = false;
				imagen.style.display = "initial";
				imagen.value = "";
				var pdf = document.getElementById("pdf_preview_frente");
				pdf.src = '#';
				//pdf.hidden = true;
				pdf.style.display = "none";
				pdf.value = "";
				<?php
			}else{
				?>
				document.getElementById('chk_frente_origin').checked = true;
				var imagen = document.getElementById("preview_frente");
				imagen.src = '#';
				//imagen.hidden = true;
				imagen.style.display = "none";
				imagen.value = "";
				var pdf = document.getElementById("pdf_preview_frente");
				pdf.src = '<?= $imagen_file.$img_array['frente']['name_pdf'] ?>';
				//pdf.hidden = false;
				pdf.style.display = "initial";
				pdf.value = "";
				<?php
			}
			?>
		}
		function img_atras_delete(){
			document.getElementById("chk_atras").checked = true;
			var imagen = document.getElementById("preview_atras");
			imagen.removeAttribute("src");
			imagen.removeAttribute("pdf_preview_atras");
			imagen.value="";
			//imagen.hidden = true;
			var pdf = document.getElementById("pdf_preview_atras");
			pdf.removeAttribute("src");
			pdf.removeAttribute("pdf_preview_atras");
			//pdf.hidden = true;
			pdf.value="";
			var elemento = document.getElementById('chk_atras_origin');
			if (elemento !== null) {
				elemento.checked = false;
			}
		}
		function img_atras_reset(){
			document.getElementById("chk_atras").checked = false;
			<?php
			if($img_array['atras']['name_img']){
				?>
				document.getElementById('chk_atras_origin').checked = true;
				var imagen = document.getElementById("preview_atras");
				imagen.src = '<?= $imagen_file.$img_array['atras']['name_img'] ?>';
				//imagen.hidden = false;
				imagen.style.display = "initial";
				imagen.value = "";
				var pdf = document.getElementById("pdf_preview_atras");
				pdf.src = '#';
				//pdf.hidden = true;
				pdf.style.display = "none";
				pdf.value = "";
				<?php
			}else{
				?>
				document.getElementById('chk_atras_origin').checked = true;
				var imagen = document.getElementById("preview_atras");
				imagen.src = '#';
				//imagen.hidden = true;
				imagen.style.display = "none";
				var pdf = document.getElementById("pdf_preview_atras");
				pdf.src = '<?= $imagen_file.$img_array['atras']['name_pdf'] ?>';
				//pdf.hidden = false;
				pdf.style.display = "initial";
				<?php
			}
			?>
		}
		function img_otros_delete(){
			document.getElementById("chk_otros").checked = true;
			var imagen = document.getElementById("preview_otros");
			imagen.removeAttribute("src");
			imagen.removeAttribute("pdf_preview_otros");
			//imagen.hidden = true;
			imagen.value="";
			var pdf = document.getElementById("pdf_preview_otros");
			pdf.removeAttribute("src");
			pdf.removeAttribute("pdf_preview_otros");
			//pdf.hidden = true;
			pdf.value="";
			var elemento = document.getElementById('chk_otros_origin');
			if (elemento !== null) {
				elemento.checked = false;
			}
		}
		function img_otros_reset(){
			document.getElementById("chk_otros").checked = false;
			<?php
			if($img_array['otros']['name_img']){
				?>
				document.getElementById('chk_otros_origin').checked = true;
				var imagen = document.getElementById("preview_otros");
				imagen.src = '<?= $imagen_file.$img_array['otros']['name_img'] ?>';
				//imagen.hidden = false;
				imagen.style.display = "initial";
				var pdf = document.getElementById("pdf_preview_otros");
				pdf.src = '#';
				//pdf.hidden = true;
				pdf.style.display = "none";
				<?php
			}else{
				?>
				document.getElementById('chk_otros_origin').checked = true;
				var imagen = document.getElementById("preview_otros");
				imagen.src = '#';
				//imagen.hidden = true;
				imagen.style.display = "none";
				var pdf = document.getElementById("pdf_preview_otros");
				pdf.src = '<?= $imagen_file.$img_array['otros']['name_pdf'] ?>';
				//pdf.hidden = false;
				pdf.style.display = "initial";
				<?php
			}
			?>
		}
	</script>
	<div class="sucFormTitulo">
        <label class="labelForm" id="labeltemaname">Archivo adjunto parte delantera</label>
    </div>
	<div class="sucForm" style="width: 100%">
		<div id="mensajeImageDelantera" class="mensajeSolo" ><br></div>
	</div>
	<div class="sucForm" style="width: 100%;text-align:center">
		<?php
		if(!empty($img_array['frente'])){
			?>
			<input type="checkbox" id="chk_frente_origin" value="1" checked hidden/>
			<?php
			if(!empty($img_array['frente']['name_img'])){
				?>
				<img id="preview_frente" src="<?= $imagen_file.$img_array['frente']['name_img'] ?>" width="80%" alt="Previsualización" />
				<embed id="pdf_preview_frente" src="#" type="application/pdf" height="300px" style="display:none;" />
				<?php
			}else{
				?>
				<img id="preview_frente" src="#" height="450px" alt="Previsualización" style="display:none;" />
				<embed id="pdf_preview_frente" src="<?= $imagen_file.$img_array['frente']['name_pdf'] ?>" type="application/pdf"  style="width:80%;height:300px" />
				<?php
			}
		}else{
			?>
			<img id="preview_frente" src="#" alt="Previsualización" style="display:none;" />
			<embed id="pdf_preview_frente" src="#" type="application/pdf" height="300px" style="display:none;" />
			<?php	
		}
		?>
	</div>
	
	<div class="sucForm">
		<input class="input_danger" type="button" value="Borrar archivo" onclick="img_frente_delete()">
		<?php
			if(!empty($img_array['frente'])){
				?>
				<input type="button" value="Restablecer archivo" onclick="img_frente_reset()">
				<?php
			}
		?>
		<input type="checkbox" id="chk_frente" value="1" hidden/>
		
	</div>
	<div class="sucForm">
		<form name="form" id="form">
			<!--<div class="fileupload" style="text-align: center;float: left;" onchange="return fileValidation()">--->
			<div class="fileupload" style="text-align: center;float: left;">
				Seleccionar Archivo
				<input type="file" id="imagen_frente" name="imagen_frente" accept="image/*,application/pdf" />
			</div>
			<label class="descripcionForm">
				<font style="font-size: 8px;margin-left: 10px">Archivo PNG, JPG, PDF</font><br>
			</label>
		</form>
	</div>
	<div class="sucFormTitulo">
        <label class="labelForm" id="labeltemaname">Archivo adjunto parte trasera</label>
    </div>
	<div class="sucForm" style="width: 100%">
		<div id="mensajeImageTrasera" class="mensajeSolo" ><br></div>
	</div>
	<div class="sucForm" style="width: 100%;text-align:center">
		<?php
		if(!empty($img_array['atras'])){
			?>
			<input type="checkbox" id="chk_atras_origin" value="1" checked hidden/>
			<?php
			if(!empty($img_array['atras']['name_img'])){
				?>
				<img id="preview_atras" src="<?= $imagen_file.$img_array['atras']['name_img'] ?>" width="80%" alt="Previsualización" />
				<embed id="pdf_preview_atras" src="#" type="application/pdf" height="300px" style="display:none;" />
				<?php
			}else{
				?>
				<img id="preview_atras" src="#" height="450px" alt="Previsualización" style="display:none;" />
				<embed id="pdf_preview_atras" src="<?= $imagen_file.$img_array['atras']['name_pdf'] ?>" type="application/pdf"  style="width:80%;height:300px" />
				<?php
			}
		}else{
			?>
			<img id="preview_atras" src="#" alt="Previsualización" style="display:none;" />
			<embed id="pdf_preview_atras" src="#" type="application/pdf" height="300px" style="display:none;" />
			<?php	
		}
		?>
	</div>
	<div class="sucForm">
		<input class="input_danger" type="button" value="Borrar archivo" onclick="img_atras_delete()">
		<?php
			if(!empty($img_array['atras'])){
				?>
				<input type="button" value="Restablecer archivo" onclick="img_atras_reset()">
				<?php
			}
		?>
		<input type="checkbox" id="chk_atras" value="1" hidden/>
	</div>
	<div class="sucForm">
		<form name="form" id="form">
			<!--<div class="fileupload" style="text-align: center;float: left;" onchange="return fileValidation()">--->
			<div class="fileupload" style="text-align: center;float: left;">
				Seleccionar Archivo
				<input type="file" id="imagen_atras" name="imagen_atras" accept="image/*,application/pdf" />
			</div>
			<label class="descripcionForm">
				<font style="font-size: 8px;margin-left: 10px">Archivo PNG, JPG, PDF</font><br>
			</label>
		</form>
	</div>
	<div class="sucFormTitulo">
        <label class="labelForm" id="labeltemaname">Archivo adjunto parte otro</label>
    </div>
	<div class="sucForm" style="width: 100%">
		<div id="mensajeImageOtro" class="mensajeSolo" ><br></div>
	</div>
	<div class="sucForm" style="width: 100%;text-align:center">
		<?php
		if(!empty($img_array['otros'])){
			?>
			<input type="checkbox" id="chk_otros_origin" value="1" checked hidden/>
			<?php
			if(!empty($img_array['otros']['name_img'])){
				?>
				<img id="preview_otros" src="<?= $imagen_file.$img_array['otros']['name_img'] ?>" width="80%" alt="Previsualización" />
				<embed id="pdf_preview_otros" src="#" type="application/pdf" height="300px" style="display:none;" />
				<?php
			}else{
				?>
				<img id="preview_otros" src="#" height="450px" alt="Previsualización" style="display:none;" />
				<embed id="pdf_preview_otros" src="<?= $imagen_file.$img_array['otros']['name_pdf'] ?>" type="application/pdf"  style="width:80%;height:300px" />
				<?php
			}
		}else{
			?>
			<img id="preview_otros" src="#" alt="Previsualización" style="display:none;" />
			<embed id="pdf_preview_otros" src="#" type="application/pdf" height="300px" style="display:none;" />
			<?php	
		}
		?>
	</div>
	<div class="sucForm">
		<input class="input_danger" type="button" value="Borrar archivo" onclick="img_otros_delete()">
		<?php
			if(!empty($img_array['otros'])){
				?>
				<input type="button" value="Restablecer archivo" onclick="img_otros_reset()">
				<?php
			}
		?>
		<input type="checkbox" id="chk_otros" value="1" hidden/>
	</div>
	<div class="sucForm">
		<form name="form" id="form">
			<!--<div class="fileupload" style="text-align: center;float: left;" onchange="return fileValidation()">--->
			<div class="fileupload" style="text-align: center;float: left;">
				Seleccionar Archivo
				<input type="file" id="imagen_otros" name="imagen_otros" accept="image/*,application/pdf" />
			</div>
			<label class="descripcionForm">
				<font style="font-size: 8px;margin-left: 10px">Archivo PNG, JPG, PDF</font><br>
			</label>
		</form>
	</div>


	<script type="text/javascript">
		function compressImage(image, quality, callback) {
			var canvas = document.createElement('canvas');
			var ctx = canvas.getContext('2d');
			var img = new Image();
			
			img.onload = function () {
				var maxWidth = 800; // Ancho máximo permitido
				var maxHeight = 600; // Altura máxima permitida
				var imageWidth = img.width;
				var imageHeight = img.height;
				
				// Redimensionar la imagen si es necesario
				if (imageWidth > maxWidth || imageHeight > maxHeight) {
					var ratio = Math.min(maxWidth / imageWidth, maxHeight / imageHeight);
					canvas.width = imageWidth * ratio;
					canvas.height = imageHeight * ratio;
					ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
				} else {
					canvas.width = imageWidth;
					canvas.height = imageHeight;
					ctx.drawImage(img, 0, 0);
				}

				// Convertir la imagen redimensionada en un archivo comprimido
				canvas.toBlob(function (blob) {
					callback(blob);
				}, 'image/jpeg', quality);
			};
			
			img.src = URL.createObjectURL(image);
		}
        // Función para previsualizar la imagen seleccionada en el input
        function previewImage(input, imagePreview, pdfPreview,tipo) {
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");
			document.getElementById("mensajeImageDelantera").classList.remove("mensajeSucces");
			document.getElementById("mensajeImageDelantera").classList.remove("mensajeError");
			$("#mensajeImageDelantera").html("&nbsp");
			document.getElementById("mensajeImageTrasera").classList.remove("mensajeSucces");
			document.getElementById("mensajeImageTrasera").classList.remove("mensajeError");
			$("#mensajeImageTrasera").html("&nbsp");
			document.getElementById("mensajeImageOtro").classList.remove("mensajeSucces");
			document.getElementById("mensajeImageOtro").classList.remove("mensajeError");
			$("#mensajeImageOtro").html("&nbsp");
			if (input.files && input.files[0]) {
				var fileSize = input.files[0].size; // Tamaño en bytes
				// Verificar si el tamaño es mayor a 1 megabyte (1 megabyte = 1024 * 1024 bytes)
				if (fileSize > 1024 * 1024) {
					// Mostrar un mensaje de error porque el archivo es demasiado grande
					if (tipo == 'frente') {
						document.getElementById("mensajeImageDelantera").classList.add("mensajeError");
						$("#mensajeImageDelantera").html("El archivo es demasiado grande, debe ser menor a 1 MB.");
					}
					if (tipo == 'atras') {
						document.getElementById("mensajeImageTrasera").classList.add("mensajeError");
						$("#mensajeImageTrasera").html("El archivo es demasiado grande, debe ser menor a 1 MB.");
					}
					if (tipo == 'otro') {
						document.getElementById("mensajeImageOtro").classList.add("mensajeError");
						$("#mensajeImageOtro").html("El archivo es demasiado grande, debe ser menor a 1 MB.");
					}
					// Cancelar la carga del archivo
					input.value = ''; // Esto elimina el archivo seleccionado del input
					return false; // Salir de la función sin continuar con la carga
				}

				// Obtener la extensión del archivo
				var fileExtension = input.files[0].name.split('.').pop().toLowerCase();

				// Validar si el archivo es un PDF o una imagen (puedes agregar más extensiones si es necesario)
				if (fileExtension === 'pdf') {
					var reader = new FileReader();

					reader.onload = function (e) {
						pdfPreview.attr('src', e.target.result);
						//pdfPreview.show();
						pdfPreview.css('display', 'initial'); // Mostrar la imagen
						pdfPreview.css('width', '80%'); // Establecer el ancho al 50%
						imagePreview.css('height', 'auto'); // Ajustar la altura automáticamente
						//imagePreview.hide();
						imagePreview.css('display', 'none'); // Ocultar la imagen
					}
					reader.readAsDataURL(input.files[0]);
				} else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
					//var reader = new FileReader();
					//reader.onload = function (e) {
					//	imagePreview.attr('src', e.target.result);
					//	imagePreview.show();
					//	imagePreview.css('height', '450px'); // Ajustar la altura automáticamente
					//	pdfPreview.hide();
					//}
					//reader.readAsDataURL(input.files[0]);

					// Comprimir la imagen antes de mostrarla
					compressImage(input.files[0], 0.7, function (compressedBlob) {
						var reader = new FileReader();
						reader.onload = function (e) {
							imagePreview.attr('src', e.target.result);
							//imagePreview.show();
							imagePreview.css('display', 'initial'); // Mostrar la imagen
							imagePreview.css('width', '80%'); // Ajustar la altura automáticamente
							//pdfPreview.hide();
							pdfPreview.css('display', 'none'); // Ocultar la imagen
						}
						reader.readAsDataURL(compressedBlob);
					});


				} else {
					if(tipo=='frente'){
						// El archivo no es ni PDF ni una imagen, puedes mostrar un mensaje de error aquí.
						document.getElementById("mensajeImageDelantera").classList.add("mensajeError");
						$("#mensajeImageDelantera").html("El archivo valido, debe ser de tipo pdf ó imagen");
						return false;
					}
					if(tipo=='atras'){
						// El archivo no es ni PDF ni una imagen, puedes mostrar un mensaje de error aquí.
						document.getElementById("mensajeImageTrasera").classList.add("mensajeError");
						$("#mensajeImageTrasera").html("El archivo valido, debe ser de tipo pdf ó imagen");
						return false;
					}
					if(tipo=='otro'){
						// El archivo no es ni PDF ni una imagen, puedes mostrar un mensaje de error aquí.
						document.getElementById("mensajeImageOtro").classList.add("mensajeError");
						$("#mensajeImageOtro").html("El archivo valido, debe ser de tipo pdf ó imagen");
						return false;
					}
					// Cancelar la carga del archivo
					input.value = ''; // Esto elimina el archivo seleccionado del input
					return; // Salir de la función sin continuar con la carga
				}
				if (tipo == 'frente') {
					document.getElementById("chk_frente").checked = false;
					var elemento = document.getElementById('chk_frente_origin');
					if (elemento !== null) {
						elemento.checked = false;
					}
				}
				if (tipo == 'atras') {
					document.getElementById("chk_atras").checked = false;
					var elemento = document.getElementById('chk_atras_origin');
					if (elemento !== null) {
						elemento.checked = false;
					}
				}
				if (tipo == 'otro') {
					document.getElementById("chk_otros").checked = false;
					var elemento = document.getElementById('chk_otros_origin');
					if (elemento !== null) {
						elemento.checked = false;
					}
				}
			}
		}


        // Evento para el input de la imagen de frente
        document.getElementById("imagen_frente").onchange = function () {
            previewImage(this, $('#preview_frente'), $('#pdf_preview_frente'),'frente');
        }

        // Evento para el input de la imagen de atrás
        document.getElementById("imagen_atras").onchange = function () {
            previewImage(this, $('#preview_atras'), $('#pdf_preview_atras'),'atras');
        }

        // Evento para el input de otro archivo
        document.getElementById("imagen_otros").onchange = function () {
            previewImage(this, $('#preview_otros'), $('#pdf_preview_otros'),'otro');
        }
    </script>