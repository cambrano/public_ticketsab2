<?php
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','militantes_partidos',$_COOKIE["id_usuario"]);
	if(empty($moduloAccionPermisos)){
		?>
		<script type="text/javascript">
			document.getElementById("mensaje").classList.add("mensajeError");
			$("#mensaje").html("No tiene permiso");
			$("#homebody").load('home.php');
		</script>
		<?php
		die;
	}
?>
	<script type="text/javascript">
		$( function() {
			$( "#fecha" ).datepicker({ 
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd', 
				onSelect: function (date) { 
					document.getElementById("fecha").style.border= "";
				}
			});
			$('#hora').timepicker({ 
				timeFormat: 'H:i:s',
				showDuration: true,
				interval: 15,
				scrollDefault: "now",
				onSelect: function (date) { 
					document.getElementById("hora").style.border= "";
				}
			}); 
		}); 
	</script>
	<script type="text/javascript">
		function fileValidation(){
			$("#mensaje").html(""); 
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			var fileInput = document.getElementById('imagen');
			var filePath = fileInput.value;
			var allowedExtensions = /(.jpg|.jpeg|.png)$/i;
			if(!allowedExtensions.exec(filePath)){
				//alert('Please upload file having extensions .jpeg/.jpg/.png/.gif only.');
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Error Solo puede subir PNG o JPG");
				fileInput.value = '';
				document.getElementById("imageJs").src = "";
				document.getElementById('logo_1').innerHTML = '<img class="responsive" id="imageJs" src="<?= $image ?>"/>';
				return false;
			}
		}
	</script>
	<style type="text/css">
		.ui-autocomplete {
			max-height: 180px;
			margin-bottom: 10px;
			overflow-x: hidden;
			overflow-y: auto;
		}
		.data_interior{
			width: 50%;
			float: left;
			padding-left: 10px;
			padding-right: 10px;
			color: #191919;
		}
		.data_interior_left{
			width: 50%;
			float: left;
			padding-left: 10px;
			padding-right: 10px;
			color: #191919;
			border-right: 1px solid #191919;
		}
		@media only screen and (max-width:1600px) {
			.data_interior{
				width: 100%;
			}
			.data_interior_left{
				border-right: none;
			}
		}
	</style>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Partido Legado</label>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="clave" autocomplete="off" <?= $claveF['input'] ?> id="clave" value="<?= $militante_partidoDatos['clave'] ?>" onkeyup="clave(this.value)" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Folio<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="folio" autocomplete="off"  id="folio" value="<?= $militante_partidoDatos['folio'] ?>" onkeyup="aMays(event, this)" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Partido Legado<font color="#FF0004">*</font></label><br>
			<select id="id_partido_legado" class='myselect'>
				<?php echo partidos_legados($militante_partidoDatos['id_partido_legado']); ?>
			</select>
			<br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Fecha<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="fecha" autocomplete="off"  id="fecha" value="<?= $militante_partidoDatos['fecha'] ?>" placeholder="" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Hora<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="hora" autocomplete="off"  id="hora" value="<?= $militante_partidoDatos['hora'] ?>" placeholder="" /><br>
		</div>

		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Formas de contacto</label><br>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Correo Eletrónico<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="correo_electronico" autocomplete="off"  id="correo_electronico" value="<?= $militante_partidoDatos['correo_electronico'] ?>" placeholder="" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Whatsapp<font color="#FF0004">*</font></label>(Solo Numero 10 Digitos)<br>
			<input class="inputlogin" type="text" name="whatsapp" autocomplete="off"  id="whatsapp" value="<?= $militante_partidoDatos['whatsapp'] ?>" placeholder="9991742151" onkeypress="return CheckNumeric()" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Teléfono</label><br>
			<input class="inputlogin" type="text" name="telefono" autocomplete="off"  id="telefono" value="<?= $militante_partidoDatos['telefono'] ?>" placeholder="9992154554 ext 10" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Celular</label>(Solo Numero 10 Digitos, Si no tiene dejar en blanco)<br>
			<input class="inputlogin" type="text" name="celular" autocomplete="off"  id="celular" value="<?= $militante_partidoDatos['celular'] ?>" placeholder="9991742151" onkeypress="return CheckNumeric()" /><br>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Observaciones</label><br>
			<textarea id="observaciones" style="width: 99%;height: 150px"><?= $militante_partidoDatos['observaciones'] ?></textarea> <br>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Estatus<font color="#FF0004">*</font></label><br>
			<select id="status" class="myselect" name="status" >
				<?php
				echo statusGeneralForm($militante_partidoDatos['status']);
				?>
			</select><br><br>
		</div>


		<div class="sucForm" style="width: 100%">
			<div id="logo" style="width: 100%; display: table; text-align: center; height;">
				<div id="logo_1" style=" background-color: #fcfbf9; display: block;padding: 5px;text-align: center;">
					<img class="responsive" id="imageJs" src="<?= $image ?>" > 
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
				<font style="font-size: 8px;margin-left: 10px">Imagen PNG o JPG  en tamaño máximo recomendado 1144px x 640px.</font><br>
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
					preview.src = "<?= $image ?>";
				}
			}
		</script>




		<div class="sucForm" style="width: 100%" >
			<br>
			<?php

			if($moduloAccionPermisos[$permiso] || $moduloAccionPermisos['all']){
				?>
				<input type="button" id="sumbmit" onclick="guardar()" value="Guardar">
				<?php
			}
			?>
				<!--<input type="button" onclick="ResetInput()" value="Borrar">-->
				<input type="button" value="Cancelar" onclick="cerrar()">
		</div>
	</div> 
	<script type="text/javascript">
		$(".myselect").select2();
	</script>