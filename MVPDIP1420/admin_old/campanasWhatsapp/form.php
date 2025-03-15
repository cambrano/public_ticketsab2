<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','campañas_whatsapp',$_COOKIE["id_usuario"]);
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
			$('#hora').timepicker({ 
				timeFormat: 'H:i:s',
				showDuration: true,
				interval: 15,
				step:60,
				scrollDefault: "now",
				onSelect: function (date) { 
					document.getElementById("hora").style.border= "";
				}
			});
			$('#hora_encuesta').timepicker({ 
				timeFormat: 'H:i:s',
				showDuration: true,
				interval: 15,
				step:60,
				scrollDefault: "now",
				onSelect: function (date) { 
					document.getElementById("hora_encuesta").style.border= "";
				}
			}); 
			$( "#fecha_encuesta" ).datepicker({
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd',
				yearRange: "1890:2005",
				defaultDate: "2003-01-01",
				onSelect: function (date) { 
					document.getElementById("fecha_encuesta").style.border= "";
				}
			});
		});
	</script>
	<script type="text/javascript"> 
		function tipoSender(value){
			if (value==1){
				document.getElementById("div_tipo_sender_python").style.display = "block";
				document.getElementById("div_tipo_sender_api").style.display = "none";
			} else if (value == 2){
				document.getElementById("div_tipo_sender_python").style.display = "none";
				document.getElementById("div_tipo_sender_api").style.display = "block";
			}else{
				document.getElementById("div_tipo_sender_python").style.display = "none";
				document.getElementById("div_tipo_sender_api").style.display = "none";
			}

		}
		function tipo_programado(value){
			if(value==2){
				document.getElementById("fecha").disabled = false;
				document.getElementById("hora").disabled = false;
				document.getElementById("div_programa").style.display = "block";
				document.getElementById("div_encuesta").style.display = "none";
				document.getElementById("div_categorias_ciudadanos").style.display = "block";
			}else if (value==3){
				document.getElementById("fecha").disabled = false;
				document.getElementById("hora").disabled = false;
				document.getElementById("div_programa").style.display = "none";
				document.getElementById("div_encuesta").style.display = "block";
				document.getElementById("div_categorias_ciudadanos").style.display = "block";
			}else{
				document.getElementById("fecha").disabled = true;
				document.getElementById("hora").disabled = true;
				document.getElementById("div_programa").style.display = "none";
				document.getElementById("div_categorias_ciudadanos").style.display = "none";
				document.getElementById("div_encuesta").style.display = "none";
			}
		}

		$(document).ready(function() {
			$("#mensaje_correo_prueba").click(function(event) { 
				document.getElementById("mensaje_correo_prueba").classList.remove("mensajeSucces");
				document.getElementById("mensaje_correo_prueba").classList.remove("mensajeError");
				$("#mensaje_correo_prueba").html("");
			});
		});

		function tipo_cartografia(value){
			var cartografia = [];
			var data = {
					'tipo_cartografia' : value, 
				}
			cartografia.push(data);

			if (value == 'secciones_ine'){
				document.getElementById("id_tipo_cartografia").disabled = false;
				$("#labelCartografia").html('Sección INE');
			}else if (value == 'municipios'){
				document.getElementById("id_tipo_cartografia").disabled = false;
				$("#labelCartografia").html('Municipio');
			}else if (value == 'distritos_locales'){
				document.getElementById("id_tipo_cartografia").disabled = false;
				$("#labelCartografia").html('Distrito Local');
			}else if (value == 'distritos_federales'){
				document.getElementById("id_tipo_cartografia").disabled = false;
				$("#labelCartografia").html('Distrito Federal');
			}else{
				document.getElementById("id_tipo_cartografia").disabled = true;
				$("#labelCartografia").html('Cartografía');
			}

			$.ajax({
				type: "POST",
				url: "campanasWhatsapp/cartografias.php",
				data: {cartografia: cartografia},
				success: function(data) {
					$("#id_tipo_cartografia").html(data);
				}
			});
		}
	</script>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Generales</label>
		</div>
		
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Nombre<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="nombre" autocomplete="off"  id="nombre" value="<?= $campana_whatsappDatos['nombre'] ?>" placeholder="Nombre" /><br>
		</div>

		
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo Sender<font color="#FF0004">*</font></label><br>
			<select id="tipo_sender" class="myselect" name="tipo_sender" onchange="tipoSender(this.value)" >
				<?php
				$selectTipo[$campana_whatsappDatos['tipo_sender']]='selected="selected"';
				?>
				<option value="">Seleccione</option>
				<option <?= $selectTipo['1'] ?> value="1">Python</option>
				<option <?= $selectTipo['2'] ?> value="2">API</option> 
			</select>
		</div>


		<div class="sucForm" id="div_tipo_sender_api" style="display: <?= $div_tipo_sender_api ?>">
			<label class="labelForm" id="labeltemaname">API Whatsapp<font color="#FF0004">*</font></label><br>
			<select id="id_api_whatsapp" class="myselect" name="id_api_whatsapp" >';
				<?php	echo api_whatsapp($campana_whatsappDatos['id_api_whatsapp']); ?>
			</select><br> 
		</div>

		<div class="sucForm" id="div_tipo_sender_python" style="display: <?= $div_tipo_sender_python ?>">
			<label class="labelForm" id="labeltemaname">Whatsapp Python<font color="#FF0004">*</font></label><br>
			<select id="id_whatsapp_python" class="myselect" name="id_whatsapp_python" >';
				<?php	echo whatsapp_python($campana_whatsappDatos['id_whatsapp_python']); ?>
			</select><br> 
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Estatus<font color="#FF0004">*</font></label><br>
			<select id="status" class="myselect" name="status" >';
				<?php	echo statusGeneralForm($campana_whatsappDatos['status']); ?>
			</select><br> 
		</div>

		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Tipo de Campaña</label>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo<font color="#FF0004">*</font></label><br>
			<select id="tipo" class="myselect" name="tipo" onchange="tipo_programado(this.value)" >
				<?php
				$selectTipo[$campana_whatsappDatos['tipo']]='selected="selected"';
				?>
				<option <?= $selectTipo['0'] ?> value="">Seleccione</option>
				<option <?= $selectTipo['1'] ?> value="1">Bienvenida</option>
				<option <?= $selectTipo['2'] ?> value="2">Programada</option>
				<option <?= $selectTipo['3'] ?> value="3">Encuestas</option>
			</select>
		</div>

		<div id="div_programa" style="display: <?= $div_programa ?>">
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Fecha<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" <?= $inputFecha; ?> type="text" name="fecha" autocomplete="off"  id="fecha" value="<?= $campana_whatsapp_programadaDatos['fecha'] ?>" placeholder="" /><br>
			</div>

			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Hora<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" <?= $inputHora; ?> type="text" name="hora" autocomplete="off"  id="hora" value="<?= $campana_whatsapp_programadaDatos['hora'] ?>" placeholder="" /><br>
			</div>
		</div>

		<div id="div_encuesta" style="display: <?= $div_encuesta ?>">
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Encuesta<font color="#FF0004">*</font></label><br>
				<select id="id_encuesta" class="myselect" name="id_encuesta" >
					<?= encuestas($campana_whatsapp_encuestaDatos['id_encuesta']); ?>
				</select>
			</div>

			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Fecha<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" <?= $inputFecha; ?> type="text" name="fecha_encuesta" autocomplete="off"  id="fecha_encuesta" value="<?= $campana_whatsapp_encuestaDatos['fecha'] ?>" placeholder="" /><br>
			</div>

			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Hora<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" <?= $inputHora; ?> type="text" name="hora_encuesta" autocomplete="off"  id="hora_encuesta" value="<?= $campana_whatsapp_encuestaDatos['hora'] ?>" placeholder="" /><br>
			</div>

		</div>


		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Tipo de Cartografía</label>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo<font color="#FF0004">*</font></label><br>
			<select id="tipo_cartografia" class="myselect" name="tipo_cartografia" onchange="tipo_cartografia(this.value)" >
				<?php
				$selectTipoCartografia[$campana_whatsapp_cartografiaDatos['tipo_cartografia']]='selected="selected"';
				?>
				<option value="">Todas</option>
				<option <?= $selectTipoCartografia['municipios'] ?> value="municipios">Municipio</option>
				<option <?= $selectTipoCartografia['distritos_locales'] ?> value="distritos_locales">Distrito Local</option>
				<option <?= $selectTipoCartografia['distritos_federales'] ?> value="distritos_federales">Distrito Federal</option>
				<option <?= $selectTipoCartografia['secciones_ine'] ?> value="secciones_ine">Seccion INE</option>
			</select>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labelCartografia"><?= $cartografia_texto ?></label><font color="#FF0004">*</font><br>
			<select id="id_tipo_cartografia" class="myselect" name="id_tipo_cartografia" <?= $disable_id_tipo_cartografia ?>>
				<?= $selectCartografia ?>
			</select>
		</div>

		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Tipo de Ciudadanos</label>
		</div>
		<?php
		foreach ($tipos_ciudadanosDatos as $key => $value) {
			if($campanas_whatsapp_tipos_ciudadanosIdDatos[$value['id']]['id']!=''){
				$checked = 'checked="checked"';
			}else{
				$checked = '';
			}
			?>
			<div class="sucForm" style="padding:5px 20px 5px 20px ">
				<input <?= $checked ?> class="inputlogin" type="checkbox" name="chk_tc<?= $value['id'] ?>" autocomplete="off"  id="chk_tc<?= $value['id'] ?>" value=""/>
				<label class="labelForm" for="chk_tc<?= $value['id'] ?>" style="letter-spacing:2px;text-transform:none;" ><?= $value['nombre'] ?></label>
			</div>
			<?php
		}
		?>

		<div id="div_categorias_ciudadanos" style="display: <?= $div_categorias_ciudadanos ?>">
			<div class="sucFormTitulo">
				<label class="labelForm" id="labeltemaname">Tipo de Categorías Ciudadanos</label>
			</div>
			<?php
			foreach ($tipos_categorias_ciudadanosDatos as $key => $value) {
				if($campanas_whatsapp_tipos_categorias_ciudadanosIdDatos[$value['id']]['id']!=''){
					$checked = 'checked="checked"';
				}else{
					$checked = '';
				}
				?>
				<div class="sucForm" style="padding:5px 20px 5px 20px ">
					<input <?= $checked ?> class="inputlogin" type="checkbox" name="" autocomplete="off"  id="chk_tcc<?= $value['id'] ?>" value=""/>
					<label class="labelForm" for="chk_tcc<?= $value['id'] ?>" style="letter-spacing:2px;text-transform:none;" ><?= $value['nombre'] ?></label>
				</div>
				<?php
			}
			?>
		</div>

		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Campaña</label>
		</div>
		<div class="sucForm" style="width: 100%;padding: 20px;background-color: #f5f5f5;">
			<label class="labelForm" id="labeltemaname">Formato de estilos</label><br>
			<table class="table">
				<thead>
					<tr>
						<th scope="col">Estilo</th>
						<th scope="col">Formato</th>
						<th scope="col">Resultado</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Bold(Negrita)</td>
						<td>*texto aqui*</td>
						<td><b>texto aqui</b></td>
					</tr>
					<tr>
						<td>Italic(Cursiva)</td>
						<td>_texto aqui_</td>
						<td><font style="font-style: italic;">texto aqui</font></td>
					</tr>
					<tr>
						<td>Strike-through(Tachado)</td>
						<td>-texto aqui-</td>
						<td><font style="text-decoration:line-through">texto aqui</font></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Cuerpo<font color="#FF0004">*</font></label><br>
			<input type="hidden" id="remove_cuerpo" value="" >
			<input type="button" id="sumbmit" onclick="addCuerpo()" value="Editor HTML">
			<input type="button" id="sumbmit" onclick="removeCuerpo()" value="Remover HTML"><br><br>
			<textarea id="cuerpo" style="width: 100%;height: 200px"><?= $campana_whatsapp_cuerpoDatos['cuerpo'] ?></textarea><br>
			<script type="text/javascript" src="js/nicEdit.js"></script>
			<script type="text/javascript" src="js/variablesnicEdit.js"></script>
			<script type="text/javascript">
				function reLoadEditorCuerpo() {
					var remove_cuerpo = document.getElementById("remove_cuerpo").value;
					if(remove_cuerpo==0){
						return false;
					}
					removeCuerpo();
					addCuerpo();
				};
				$(window).resize( function() {
					reLoadEditorCuerpo();
				});

				addCuerpo();
				var cuerpo;
				function addCuerpo(){
					var remove_cuerpo = document.getElementById("remove_cuerpo").value;
					if(remove_cuerpo==1){
						return false;
					}
					document.getElementById("remove_cuerpo").value = 1;
					cuerpo = new nicEditor({
						buttonList : 
						[
							'Datos_Plataforma',
							'Datos_Correo_Fecha_Hora',
							'Ciudadanos',
							'Ciudadanos_Usuarios',
							'Ciudadanos_Cartografia',
						]
					}).panelInstance('cuerpo',{hasPanel : true});
				}
				function removeCuerpo() {
					var remove_cuerpo = document.getElementById("remove_cuerpo").value;
					if(remove_cuerpo==0){
						return false;
					}
					cuerpo.removeInstance('cuerpo');
					document.getElementById("remove_cuerpo").value = 0;
				} 
			</script>
		</div>
		<div class="sucForm" style="width: 100%;padding: 20px;background-color: #f5f5f5;">
			<label class="labelForm" id="labeltemaname">Formatos de archivos</label><br>
			<table class="table">
				<thead>
					<tr>
						<th scope="col">Tipo</th>
						<th scope="col">Formato</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Imagenes</td>
						<td>JPG, JPEG, PNG</td>
					</tr>
					<tr>
						<td>Audio</td>
						<td>MP3, OGG, AMR</td>
					</tr>
					<tr>
						<td>Documents</td>
						<td>PDF</td>
					</tr>
					<tr>
						<td>Video</td>
						<td>MP4 (with H.264 video codec and AAC audio)</td>
					</tr> 
				</tbody>
			</table>
			<font style="font-size: 11px">
				<b>* Ahora puede enviar archivos de imagen, video, texto y PDF de hasta 5 MB de tamaño a través de WhatsApp<br></b>
				<b>** Cuando envías Audio, Documentos y Vídeo no envía Texto en el mensaje.<br></b>
				<b>*** El link debe ser directo para que Whatsapp pueda enviar el archivo correctamente.</b>
			</font>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Media URL</label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="MediaUrl" autocomplete="off"  id="MediaUrl" value="<?= $campana_whatsapp_cuerpoDatos['MediaUrl'] ?>" placeholder="https://demo.twilio.com/owl.png" /><br>
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