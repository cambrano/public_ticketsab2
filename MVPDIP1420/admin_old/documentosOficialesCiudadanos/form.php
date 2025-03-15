<?php
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','documentos_oficiales',$_COOKIE["id_usuario"]);
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
			$( "#fecha_emision" ).datepicker({ 
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd', 
				onSelect: function (date) { 
					document.getElementById("fecha_emision").style.border= "";
				}
			}); 
			$( "#fecha_vigencia" ).datepicker({ 
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd', 
				onSelect: function (date) { 
					document.getElementById("fecha_vigencia").style.border= "";
				}
			}); 
		});
	</script>
	<script type="text/javascript">
		function fileValidation(){
			/*
			$("#mensajeImage").html(""); 
			document.getElementById("mensajeImage").classList.remove("mensajeSucces");
			document.getElementById("mensajeImage").classList.remove("mensajeError");
			var fileInput = document.getElementById('imagen');
			var filePath = fileInput.value;
			var size = fileInput.files[0].size/1024/1024;
			if(size > 1 ){
				document.getElementById("mensajeImage").classList.add("mensajeError");
				$("#mensajeImage").html("Error Solo puede subir archivos menor a 1mb");
				fileInput.value = '';
				document.getElementById("imageJs").src = "";
				document.getElementById('logo_1').innerHTML = '<img class="responsive" id="imageJs" src="<?= $imagen_icon ?>"/>';
				return false;
			}

			var allowedExtensions = /(.jpg|.jpeg|.png|.pdf)$/i;
			if(!allowedExtensions.exec(filePath)){
				//alert('Please upload file having extensions .jpeg/.jpg/.png/.gif only.');
				document.getElementById("mensajeImage").classList.add("mensajeError");
				$("#mensajeImage").html("Error Solo puede subir PNG, JPG ó PDF");
				fileInput.value = '';
				document.getElementById("imageJs").src = "";
				document.getElementById('logo_1').innerHTML = '<img class="responsive" id="imageJs" src="<?= $imagen_icon ?>"/>';
				return false;
			}
			*/
		}
	</script>
	<script language="javascript" type="text/javascript">
		function guardarImagen(metodo) {
			document.getElementById("sumbmitImage").disabled = true;
			document.getElementById("mensajeImage").classList.remove("mensajeSucces");
			document.getElementById("mensajeImage").classList.remove("mensajeError");

			var tipo_imagen = document.getElementById("tipo_imagen").value; 
			if(tipo_imagen == ""){
				document.getElementById("tipo_imagen").focus(); 
				document.getElementById("sumbmitImage").disabled = false;
				$("#mensajeImage").html("Tipo Imagen requerido");
				document.getElementById("mensajeImage").classList.add("mensajeError");
				return false;
			}

			var fileInput = document.getElementById('imagen');
			var fileInput = document.getElementById('imagen');
			var filePath = fileInput.value;
			if(filePath == ""){
				document.getElementById("sumbmitImage").disabled = false;
				$("#mensajeImage").html("Documento requerido");
				document.getElementById("mensajeImage").classList.add("mensajeError");
				return false;
			}

			var formData = new FormData($("#form")[0]); 
			formData.append('num', '');
			formData.append('tipo_imagen', tipo_imagen);

			var ruta = "documentosOficialesCiudadanos/imageSession.php";
			$.ajax({
				url: ruta,
				type: "POST",
				data: formData, 
				contentType: false,
				processData: false,
				success: function(data){ 
					document.getElementById("mensajeImage").classList.add("mensajeSolo");
					$("#mensajeImage").html("");
					document.getElementById("imagen").value="";
					document.getElementById("sumbmitImage").disabled = false;
					$("#imageList").html(data);
					//$("#imageJs").src("");
					document.getElementById("imageJs").src = "<?= $imagen_icon ?>";
				}
			});
		}
		function editarImagen(metodo) {
			document.getElementById("sumbmitImage").disabled = true;
			document.getElementById("mensajeImage").classList.remove("mensajeSucces");
			document.getElementById("mensajeImage").classList.remove("mensajeError");

			var tipo_imagen = document.getElementById("tipo_imagen").value; 
			if(tipo_imagen == ""){
				document.getElementById("tipo_imagen").focus(); 
				document.getElementById("sumbmitImage").disabled = false;
				$("#mensajeImage").html("Tipo Imagen requerido");
				document.getElementById("mensajeImage").classList.add("mensajeError");
				return false;
			}

			var fileInput = document.getElementById('imagen');
			var fileInput = document.getElementById('imagen');
			var filePath = fileInput.value;
			if(filePath == ""){
				document.getElementById("sumbmitImage").disabled = false;
				$("#mensajeImage").html("Documento requerido, si estás editando la imagen debe subir algun archivo o presione el boton cancelar para no perder el archivo.");
				document.getElementById("mensajeImage").classList.add("mensajeError");
				return false;
			}

			var num = document.getElementById("num").value; 
			if(num == ""){
				document.getElementById("num").focus(); 
				document.getElementById("sumbmitImage").disabled = false;
				$("#mensajeImage").html("Numero Imagen requerido");
				document.getElementById("mensajeImage").classList.add("mensajeError");
				return false;
			}

			var formData = new FormData($("#form")[0]); 
			formData.append('tipo_imagen', tipo_imagen); 
			formData.append('num', num);
			formData.append('update', 'update');
			var ruta = "documentosOficialesCiudadanos/imageSession.php";
			$.ajax({
				url: ruta,
				type: "POST",
				data: formData, 
				contentType: false,
				processData: false,
				success: function(data){ 
					document.getElementById("mensajeImage").classList.add("mensajeSolo");
					$("#mensajeImage").html("");
					document.getElementById("imagen").value="";
					document.getElementById("num").value="";
					document.getElementById("tipo_imagen").value="";
					document.getElementById("sumbmitImage").disabled = false;
					$("#imageList").html(data);
					//$("#imageJs").src("");
					console.log();
					document.getElementById("imageJs").src = "<?= $imagen_icon ?>";
					document.getElementById("pdfJS").data = "";

					document.getElementById("sumbmitImage").value="Subir Imagen";
					document.getElementById('sumbmitImage').setAttribute('onclick',  'guardarImagen("mas");');
				}
			});
		}
		function eliminarImage(value){
			var formData = new FormData($("#form")[0]);
			formData.append('num', value);
			var ruta = "documentosOficialesCiudadanos/imageSession.php";
			$.ajax({
				url: ruta,
				type: "POST",
				data: formData, 
				contentType: false,
				processData: false,
				success: function(data){ 
					document.getElementById("mensajeImage").classList.add("mensajeSolo");
					document.getElementById("sumbmitImage").disabled = false;
					$("#imageList").html(data);
					//$("#logo").html("");
				}
			});
		}
		function editarImage(value){
			var formData = new FormData($("#form")[0]);
			formData.append('num', value);
			var ruta = "documentosOficialesCiudadanos/formImagen.php";
			$.ajax({
				url: ruta,
				type: "POST",
				data: formData, 
				contentType: false,
				processData: false,
				success: function(data){ 
					document.getElementById("mensajeImage").classList.add("mensajeSolo");
					document.getElementById("sumbmitImage").disabled = false;
					$("#form_imagen").html(data);
					//$("#logo").html("");
				}
			});
		}
	</script>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Documento Oficial</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo<font color="#FF0004">*</font></label><br>
			<?php
				$select[$documento_oficialDatos['tipo']] = 'selected="selected"';
			?>
			<select name="tipo" id="tipo" class='myselect'>  
				<option value="">Seleccione</option>
				<option <?= $select['ine'] ?> value="ine">INE</option>
				<option <?= $select['comprobante_domicilio'] ?> value="comprobante_domicilio">Comprobante Domicilio</option>
				<option <?= $select['pasaporte'] ?> value="pasaporte">Pasaporte</option>
			</select>
		</div>
		 
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Fecha Emisión<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="fecha_emision" autocomplete="off"  id="fecha_emision" value="<?= $documento_oficialDatos['fecha_emision'] ?>" placeholder="" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Fecha Vigencia<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="fecha_vigencia" autocomplete="off"  id="fecha_vigencia" value="<?= $documento_oficialDatos['fecha_vigencia'] ?>" placeholder="" /><br>
		</div>

		<div class="sucFormTitulo">
				<label class="labelForm" id="labeltemaname">Imagenes</label>
		</div>

		<div id="form_imagen">
			<?php
			include "formImagen.php";
			?>
		</div>
		<br>
		<div id="imageList" class="mensajeSolo" >
			<?php
				include "imageSession.php";
			?>
		</div>
		<br>


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