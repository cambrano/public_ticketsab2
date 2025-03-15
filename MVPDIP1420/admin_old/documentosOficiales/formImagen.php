	<?php
		@session_start(); 

		if(!empty($_POST)){
			$num = $_POST['num'];
			//var_dump($_SESSION['image'][$num]);
			$base64 = base64_encode($_SESSION['image'][$num]['imagePrint']);
			$imagen_icon = "data:image/jpeg;base64,".$base64;
			$texto_boton = "Editar Imagen";
			$actionImagen = "editarImagen";

		}else{
			$texto_boton = "Subir Imagen";
			$actionImagen = "guardarImagen";
		}


	?>
	<div class="sucForm" style="width: 100%">
		<div id="logo" style="width: 100%; display: table; text-align: center;">
			<div id="logo_1" style=" background-color: #fcfbf9; display: block;padding: 5px;text-align: center;">
				<img class="responsive" id="imageJs" src="<?= $imagen_icon ?>" > 
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
			<font style="font-size: 8px;margin-left: 10px">Imagen PNG o JPG</font><br>
		</label>
	</div>
	<div class="sucForm">
		<form name="form" id="form">
			<div class="fileupload" style="text-align: center;float: left;" onchange="return fileValidation()">
				Seleccionar Imagen
				<input type="file" id="imagen" name="imagen" value="<?= $_SESSION['image'][$num]['id'] ?>" />
			</div>
		</form>
	</div>
	<div class="sucForm">
		<input type="button" id="sumbmitImage" style="float: left;" onclick="<?= $actionImagen ?>('mas')" value="<?= $texto_boton ?>">
	</div>
	<script type="text/javascript">
		document.getElementById("imagen").onchange = function(e) {
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
		}
	</script>