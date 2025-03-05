<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','control_gastos_ingresos',$_COOKIE["id_usuario"]);
	if(empty($moduloAccionPermisos)){
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
	<script>
		$( function() {
			$( "#fecha_compra" ).datepicker({
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd',
				yearRange: "2024:2030",
				defaultDate: "2024-10-01",
				onSelect: function (date) { 
					document.getElementById("fecha_compra").style.border= "";
				}
			});
			$( "#fecha_factura" ).datepicker({
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd',
				yearRange: "2024:2030",
				defaultDate: "2024-10-01",
				onSelect: function (date) { 
					document.getElementById("fecha_factura").style.border= "";
				}
			});
			$( "#fecha_envio" ).datepicker({
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd',
				yearRange: "2024:2030",
				defaultDate: "2024-10-01",
				onSelect: function (date) { 
					document.getElementById("fecha_envio").style.border= "";
				}
			});
			$('#hora_compra').timepicker({ 
				timeFormat: 'H:i:s',
				showDuration: true,
				interval: 15,
				scrollDefault: "now",
				onSelect: function (date) { 
					document.getElementById("hora_compra").style.border= "";
				}
			});
		});


		const imageDropzone = document.getElementById('imageDropzone');
		const docDropzone = document.getElementById('docDropzone');
		const imagePreviewContainer = document.getElementById('imagePreview');
		const docPreviewContainer = document.getElementById('docPreview');
		let images = [];
		let documents = [];

		// Eventos de drag-and-drop para imágenes
		imageDropzone.addEventListener('dragover', (e) => handleDragOver(e, imageDropzone));
		imageDropzone.addEventListener('dragleave', () => resetBorder(imageDropzone));
		imageDropzone.addEventListener('drop', (e) => handleDrop(e, 'image'));

		imageDropzone.addEventListener('click', () => {
			const fileInput = createFileInput('image/*', true);
			fileInput.onchange = () => handleFiles(fileInput.files, 'image');
		});

		// Eventos de drag-and-drop para documentos
		docDropzone.addEventListener('dragover', (e) => handleDragOver(e, docDropzone));
		docDropzone.addEventListener('dragleave', () => resetBorder(docDropzone));
		docDropzone.addEventListener('drop', (e) => handleDrop(e, 'doc'));

		docDropzone.addEventListener('click', () => {
			const fileInput = createFileInput('.pdf,.doc,.docx,.xls,.xlsx', true);
			fileInput.onchange = () => handleFiles(fileInput.files, 'doc');
		});

		function handleDragOver(event, element) {
			event.preventDefault();
			element.style.borderColor = '#000';
		}

		function resetBorder(element) {
			element.style.borderColor = '#ccc';
		}

		function handleDrop(event, type) {
			event.preventDefault();
			resetBorder(event.currentTarget);
			handleFiles(event.dataTransfer.files, type);
		}

		function createFileInput(accept, multiple) {
			const fileInput = document.createElement('input');
			fileInput.type = 'file';
			fileInput.accept = accept;
			fileInput.multiple = multiple;
			fileInput.click();
			return fileInput;
		}

		function handleFiles(files, type) {
			Array.from(files).forEach((file) => {
				if (type === 'image' && images.length < 5 && file.type.startsWith('image/')) {
					images.push(file);
					displayPreview(file, 'image');
				} else if (type === 'doc' && documents.length < 5 && isValidDoc(file)) {
					documents.push(file);
					displayPreview(file, 'doc');
				} else if (type === 'image' && images.length >= 5) {
					alert('No puedes agregar más de 5 imágenes.');
				} else if (type === 'doc' && documents.length >= 5) {
					alert('No puedes agregar más de 5 documentos.');
				} else {
					alert('Tipo de archivo no permitido.');
				}
			});
		}

		function isValidDoc(file) {
			return ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
					'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'].includes(file.type);
		}

		function displayPreview(file, type) {
			const previewItem = document.createElement('div');
			previewItem.classList.add('preview-item');
			
			if (type === 'image') {
				const img = document.createElement('img');
				const reader = new FileReader();
				reader.onload = (e) => img.src = e.target.result;
				reader.readAsDataURL(file);
				previewItem.appendChild(img);
			} else {
				const docPreview = document.createElement('div');
				docPreview.classList.add('doc-preview');
				docPreview.innerText = file.name;
				previewItem.appendChild(docPreview);
			}
			
			const removeBtn = document.createElement('button');
			removeBtn.innerText = 'x';
			removeBtn.classList.add('remove-btn');
			removeBtn.onclick = () => removeFile(file, previewItem, type);

			previewItem.appendChild(removeBtn);
			type === 'image' ? imagePreviewContainer.appendChild(previewItem) : docPreviewContainer.appendChild(previewItem);
		}

		function removeFile(file, previewItem, type) {
			if (type === 'image') {
				images = images.filter(img => img !== file);
			} else {
				documents = documents.filter(doc => doc !== file);
			}
			previewItem.remove();
		}

	</script>
	<style>
		.dropzone {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            margin-bottom: 20px;
            transition: border-color 0.3s;
        }
        .dropzone:hover {
            border-color: #000;
        }
        .preview-item {
            position: relative;
            display: inline-block;
            margin: 10px;
        }
        .preview-item img, .preview-item .doc-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: red;
            color: white;
            border: none;
            cursor: pointer;
        }
	</style>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Control de Gastos e Ingresos</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" <?= $claveF['input'] ?> type="text" style="width: 100%" name="clave" autocomplete="off"  id="clave" value="<?= $tipo_equipoDatos['clave'] ?>" placeholder="Clave" onkeyup="clave(this.value)" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Folio<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="folio" autocomplete="off"  id="folio" value="<?= $tipo_equipoDatos['folio'] ?>" onkeyup="aMays(event, this)" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo Gasto<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_tipo_gasto">
				<?php
				echo tipos_gastos($tipo_equipoDatos['id_tipo_gasto']);
				?>
			</select><br>
		</div>
		<div class="sucForm" style="width:100%">
			<label class="labelForm" id="labeltemaname">Concepto<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="concepto" autocomplete="off"  id="concepto" value="<?= $tipo_equipoDatos['concepto'] ?>" placeholder="Concepto" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Fecha Compra<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="fecha_compra" autocomplete="off"  id="fecha_compra" value="<?= $tipo_equipoDatos['fecha_compra'] ?>" placeholder="Fecha Compra" /><br>
		</div> 

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Hora Compra<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="hora_compra" autocomplete="off"  id="hora_compra" value="<?= $tipo_equipoDatos['hora_compra'] ?>" placeholder="Hora Compra" /><br>
		</div>
		<div class="sucForm" style="width:100%"></div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo Pago<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_tipo_pago">
				<?php
				echo tipos_pagos($tipo_equipoDatos['id_tipo_pago']);
				?>
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Monto Otorgado<font color="#FF0004">*</font></label><br>
			<input type="text" name="monto_otorgado" autocomplete="off"  id="monto_otorgado" value="<?= $tipo_equipoDatos['monto_otorgado'] ?>" placeholder="0.00" onkeypress="return CheckNumeric()" onkeyup="FormatCurrency(this)"/><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Monto Compra<font color="#FF0004">*</font></label><br>
			<input type="text" name="monto_compra" autocomplete="off"  id="monto_compra" value="<?= $tipo_equipoDatos['monto_compra'] ?>" placeholder="0.00" onkeypress="return CheckNumeric()" onkeyup="FormatCurrency(this)"/><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Monto Saldo<font color="#FF0004">*</font></label><br>
			<input type="text" name="monto_saldo" autocomplete="off"  id="monto_saldo" value="<?= $tipo_equipoDatos['monto_saldo'] ?>" placeholder="0.00" onkeypress="return CheckNumeric()" onkeyup="FormatCurrency(this)"/><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Monto Saldo<font color="#FF0004">*</font></label><br>
			<input type="text" name="monto_saldo" autocomplete="off"  id="monto_saldo" value="<?= $tipo_equipoDatos['monto_saldo'] ?>" placeholder="0.00" onkeypress="return CheckNumeric()" onkeyup="FormatCurrency(this)"/><br>
		</div>
		<div class="sucForm" style="width:100%"></div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Fecha Factura<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="fecha_factura" autocomplete="off"  id="fecha_factura" value="<?= $tipo_equipoDatos['fecha_factura'] ?>" placeholder="Fecha Factura" /><br>
		</div> 

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Fecha Envio<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="fecha_envio" autocomplete="off"  id="fecha_envio" value="<?= $equipoDatos['fecha_envio'] ?>" placeholder="Fecha Envio" /><br>
		</div> 
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Estatus<font color="#FF0004">*</font></label><br>
			<select id="status" class="myselect" name="status" >
				<?php
					echo statusPago($tipo_equipoDatos['status']);
				?>
			</select><br><br>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Observaciones</label><br>
			<textarea id="observaciones" style="width: 99%;height: 150px"><?= $equipoDatos['observaciones'] ?></textarea> <br>
		</div>

		<div class="sucForm" style="width: 100%">
			<h2>Subir Imágenes</h2>
			<div id="imageDropzone" class="dropzone">Arrastra y suelta imágenes aquí o haz clic para seleccionar</div>
			<div id="imagePreview"></div>
		</div>

 

		<div class="sucForm" style="width: 100%">
			<br>
			<?php
			if($moduloAccionPermisos[$permiso] || $moduloAccionPermisos['all']){
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