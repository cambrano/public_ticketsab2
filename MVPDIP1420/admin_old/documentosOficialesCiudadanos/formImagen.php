	<?php
		@session_start(); 

		if(!empty($_POST)){
			$num = $_POST['num'];
			//var_dump($_SESSION['image'][$num]);
			$base64 = base64_encode($_SESSION['image'][$num]['imagePrint']);
			if($_SESSION['image'][$num]['type']=='application/pdf'){
				$pdf_icon = "data:application/pdf;base64,".$base64;
				$display_image ='style="display: none"';
			}else{
				$imagen_icon = "data:image/jpeg;base64,".$base64;
				$display_pdf ='style="display: none"';
			}
			$texto_boton = "Editar Archivo";
			$actionImagen = "editarImagen";
		}else{
			$texto_boton = "Subir Archivo";
			$actionImagen = "guardarImagen";
		}


	?>
	<div class="sucForm" style="width: 100%">
		<div id="logo" style="width: 100%; display: table; text-align: center;">
			<div id="logo_1" style=" background-color: #fcfbf9; display: block;padding: 5px;text-align: center;">
				<img class="responsive" id="imageJs" src="<?= $imagen_icon ?>" <?= $display_image ?>> 
				<object id="pdfJS" data="<?= $pdf_icon ?>" type="application/pdf" width="100%" height="600px" <?= $display_pdf ?>></object>
			</div>
		</div>
	</div>

	<div class="sucForm" style="width: 100%">
		<div id="mensajeImage" class="mensajeSolo" ><br></div>
	</div>
	<div class="sucForm">
		<input type="hidden" id="num" name="num" value="<?= $num ?>">
	</div>
	<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo<font color="#FF0004">*</font></label><br>
			<?php
				$select[$_SESSION['image'][$num]['tipo_imagen']] = 'selected="selected"';
			?>
			<select name="tipo_imagen" id="tipo_imagen" class='myselect'>  
				<option value="">Seleccione</option>
				<option <?= $select['frente'] ?> value="frente">Frente</option>
				<option <?= $select['atras'] ?> value="atras">Atras</option>
				<option <?= $select['otros'] ?> value="otros">Otros</option>
			</select>
		</div>
	<div class="sucForm" style="width: 100%">
		<label class="descripcionForm">
			<font style="font-size: 8px;margin-left: 10px">Archivo PNG, JPG, PDF</font><br>
		</label>
	</div>
	<div class="sucForm">
		<form name="form" id="form">
			<!--<div class="fileupload" style="text-align: center;float: left;" onchange="return fileValidation()">--->
			<div class="fileupload" style="text-align: center;float: left;">
				Seleccionar Archivo
				<input type="file" id="imagen" name="imagen" value="<?= $_SESSION['image'][$num]['id'] ?>" />
			</div>
		</form>
	</div>
	<div class="sucForm">
		<input type="button" id="sumbmitImage" style="float: left;" onclick="<?= $actionImagen ?>('mas')" value="<?= $texto_boton ?>">
	</div>
	<script type="text/javascript">
		document.getElementById("imagen").onchange = function(e) {
			$("#mensajeImage").html(""); 
			document.getElementById("mensajeImage").classList.remove("mensajeSucces");
			document.getElementById("mensajeImage").classList.remove("mensajeError");
			var fileInput = document.getElementById('imagen');
			var filePath = fileInput.value;
			var size = fileInput.files[0].size/1024/1024;

			var allowedExtensions = /(.jpg|.jpeg|.png|.pdf)$/i;
			if(allowedExtensions.exec(filePath)){
				
			}else{
				document.getElementById('imagen').value= null;
				document.getElementById("mensajeImage").classList.add("mensajeError");
				$("#mensajeImage").html("Error Solo puede subir archivos PNG, JPG, PDF");
				return false;
			}

			if(size > 1 ){
				document.getElementById("mensajeImage").classList.add("mensajeError");
				$("#mensajeImage").html("El archivo es superior a 1 mb");
				/*
				fileInput.value = '';
				document.getElementById("imageJs").src = "";
				document.getElementById('pdfJS').style.display = "none";
				document.getElementById('logo_1').innerHTML = '<img class="responsive" id="imageJs" src="<?= $imagen_icon ?>"/>';
				return false;
				*/
			}

			var allowedExtensions = /(.jpg|.jpeg|.png)$/i;
			if(allowedExtensions.exec(filePath)){
				document.getElementById('imageJs').style.display = "block";
				var preview = document.getElementById('imageJs');
				var file    = document.querySelector('input[type=file]').files[0];
				var reader  = new FileReader();
				reader.onloadend = function () {
					preview.src = reader.result;
				}
				if(file){
					reader.readAsDataURL(file);
				}else{
					preview.src = "<?= $imagen_icon ?>";
				}
			}else{
				document.getElementById("imageJs").src = "";
				document.getElementById('pdfJS').style.display = "none";
			}
			var allowedExtensions = /(.pdf)$/i;
			if(allowedExtensions.exec(filePath)){
				document.getElementById('pdfJS').style.display = "block";
				document.getElementById("imageJs").src = "";
				var preview = document.getElementById('pdfJS');
				var file    = document.querySelector('input[type=file]').files[0];
				var reader  = new FileReader();
				reader.onloadend = function () {
					preview.data = reader.result;
				}
				if(file){
					reader.readAsDataURL(file);
				}
			}else{ 
				document.getElementById("imageJs").src = "";
				document.getElementById('pdfJS').style.display = "none";
			}
	}
	</script>