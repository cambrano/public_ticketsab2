<?php
	if(
		moduloAccion('configuracion','configuracion_inicial',$_COOKIE["id_usuario"],$permiso) ||
		moduloAccion('configuracion','configuracion_inicial',$_COOKIE["id_usuario"],'All') ){
		$permiso_usuario_action = true;
	}else{
		if(moduloAccion('configuracion','configuracion_inicial',$_COOKIE["id_usuario"],'View')){
			$permiso_usuario_action = false;
		}else{
			$permiso_usuario_action = false;
			?>
			<script type="text/javascript">
				document.getElementById("mensaje").classList.add("mensajeError");
				$("#mensaje").html("No tiene permiso");
				$("#homebody").load('home.php');
			</script>
			<?php
			die;
		}
	}
?>
	<script type="text/javascript">
		function fileValidation(){
		$("#mensaje").html(""); 
		document.getElementById("mensaje").classList.remove("mensajeSucces");
		document.getElementById("mensaje").classList.remove("mensajeError");
		var fileInput = document.getElementById('imagen');
		var filePath = fileInput.value;
		var allowedExtensions = /(.jpg|.png)$/i;
		if(!allowedExtensions.exec(filePath)){
			//alert('Please upload file having extensions .jpeg/.jpg/.png/.gif only.');
			document.getElementById("mensaje").classList.add("mensajeError");
			document.getElementById("sumbmit").disabled = false;
			$("#mensaje").html("Error Solo puede subir PNG o JPG");
			fileInput.value = '';
			document.getElementById("imageJs").src = "";
			document.getElementById('logo_1').innerHTML = '<img class="responsive" id="imageJs" src="<?= $imagen_configuracion ?>"/>';
			return false;
		}
	}
	</script>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucForm" style="width: 100%">
			<div id="logo" style="width: 100%; display: table; text-align: center; height;">
				<div id="logo_1" style=" background-color: #fcfbf9; display: block;padding: 5px;text-align: center;">
					<img class="responsive" id="imageJs" src="<?= $imagen_configuracion ?>" > 
				</div>
			</div>
		</div>
		<div class="sucForm">
			<form name="form" id="form"> 
				<div class="fileupload" style="text-align: center;float: left;" onchange="return fileValidation()">
					Seleccionar Imagen
					<input type="file" id="imagen" name="imagen" />
				</div>
			</form>
			<label class="descripcionForm">
				<font style="font-size: 8px;margin-left: 10px">Imagen PNG o JPG  en tamaño maximo recomendado 620 x 620.</font><br>
			</label>
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
					preview.src = "<?= $imagen_configuracion ?>";
				}
			}
		</script>

		<div class="sucFormTitulo">
			<center>
				<label class="labelForm" id="labeltemaname">Datos Sistema</label>
			</center>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Nombre<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="nombre" autocomplete="off"  id="nombre" value="<?= $configuracionDatos['nombre'] ?>" placeholder="" maxlength="120"/><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Slogan Del Sistema<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="slogan" autocomplete="off"  id="slogan" value="<?= $configuracionDatos['slogan'] ?>" placeholder="" maxlength="120" /><br>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">URL Base<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="url_base" autocomplete="off"  id="url_base" value="<?= $configuracionDatos['url_base'] ?>" placeholder="" maxlength="120" /><br>
		</div>


		<div class="sucFormTitulo">
			<center>
				<label class="labelForm" id="labeltemaname">Datos Representante</label>
			</center>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Nombre Completo<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="nombre_completo_representante" autocomplete="off"  id="nombre_completo_representante" value="<?= $configuracionDatos['nombre_completo_representante'] ?>" placeholder="" maxlength="120"/><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Teléfono<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="telefono" autocomplete="off"  id="telefono" value="<?= $configuracionDatos['telefono'] ?>" placeholder="" maxlength="45" onkeypress="return CheckNumeric()"/><br>
		</div>

		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Correo Electrónico<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="correo_electronico" autocomplete="off"  id="correo_electronico" value="<?= $configuracionDatos['correo_electronico'] ?>" placeholder="" maxlength="120" /><br>
		</div>

		<div class="sucForm" style="width: 100%">
			<br>
			<?php
			if($permiso_usuario_action){
				?>
				<input type="button" id="sumbmit" onclick="guardar()" value="Guardar">
				<!--<input type="button" onclick="ResetInput()" value="Borrar">-->
				<?php
			}
			?>
			<input type="button" value="Cancelar" onclick="cerrar()">
		</div>
	</div>
	<script type="text/javascript">
		$(".myselect").select2();
	</script>