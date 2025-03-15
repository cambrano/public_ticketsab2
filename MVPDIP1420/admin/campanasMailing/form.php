<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','campañas_mailing',$_COOKIE["id_usuario"]);
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
		});
	</script>
	<script type="text/javascript"> 
		function tipo_programado(value){
			if(value==2){
				document.getElementById("fecha").disabled = false;
				document.getElementById("hora").disabled = false;
				document.getElementById("div_programa").style.display = "block";
				document.getElementById("div_encuesta").style.display = "none";
				document.getElementById("div_categorias_ciudadanos").style.display = "block";
			}else if (value==3){
				document.getElementById("fecha").disabled = true;
				document.getElementById("hora").disabled = true;
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
		function test_correo_prueba(){
			var coma= /,/g;
			document.getElementById("sumbmit_prueba_correo").disabled = true;
			document.getElementById("mensaje_correo_prueba").classList.remove("mensajeSucces");
			document.getElementById("mensaje_correo_prueba").classList.remove("mensajeError");
			$("#mensaje_correo_prueba").html("&nbsp");
			var id_correo_mailing = document.getElementById("id_correo_mailing").value; 
			if(id_correo_mailing == ""){
				document.getElementById("id_correo_mailing").focus(); 
				document.getElementById("sumbmit_prueba_correo").disabled = false;
				$("#mensaje_correo_prueba").html("Correo Mailing requerido");
				document.getElementById("mensaje_correo_prueba").classList.add("mensajeError");
				return false;
			}

			var correo_prueba = document.getElementById("correo_prueba").value; 
			if(correo_prueba == ""){
				document.getElementById("correo_prueba").focus(); 
				document.getElementById("sumbmit_prueba_correo").disabled = false;
				$("#mensaje_correo_prueba").html("Correo Electronico Válido requerido");
				document.getElementById("mensaje_correo_prueba").classList.add("mensajeError");
				return false;
			}else{
				if(!validarEmail(correo_prueba)){
					document.getElementById("correo_prueba").focus(); 
					document.getElementById("sumbmit_prueba_correo").disabled = false;
					$("#mensaje_correo_prueba").html("Correo Electronico Válido requerido");
					document.getElementById("mensaje_correo_prueba").classList.add("mensajeError");
					return false;
				}
			}


			var remove_asunto = document.getElementById("remove_asunto").value;
			if(remove_asunto==0){
				addCuerpo();
			}
			var asunto = new nicEditors.findEditor('asunto');
			asunto = asunto.getContent();
			var asunto = asunto.replace(/^\s+|\s+$/g, "");
			var asunto = asunto.replace(/&nbsp;/g, "");
			var asunto = asunto.replace(/<br>/g, "");
			var asunto = asunto.replace(/<br\s*[\/]?>/gi, "");
			var asunto = asunto.replace(/<div>/g, "");
			var asunto = asunto.replace(/<\/div>/g, "");
			var asunto = asunto.replace(/\s/g, '');
			if(asunto == ""){ 
				document.getElementById("sumbmit_prueba_correo").disabled = false;
				$("#mensaje_correo_prueba").html("Cuerpo requerido");
				document.getElementById("mensaje_correo_prueba").classList.add("mensajeError");
				return false;
			}
			var asunto = new nicEditors.findEditor('asunto');
			asunto = asunto.getContent();


			var remove_cuerpo = document.getElementById("remove_cuerpo").value;
			if(remove_cuerpo==0){
				addCuerpo();
			}
			var cuerpo = new nicEditors.findEditor('cuerpo');
			cuerpo = cuerpo.getContent();
			var cuerpo = cuerpo.replace(/^\s+|\s+$/g, "");
			var cuerpo = cuerpo.replace(/&nbsp;/g, "");
			var cuerpo = cuerpo.replace(/<br>/g, "");
			var cuerpo = cuerpo.replace(/<br\s*[\/]?>/gi, "");
			var cuerpo = cuerpo.replace(/<div>/g, "");
			var cuerpo = cuerpo.replace(/<\/div>/g, "");
			var cuerpo = cuerpo.replace(/\s/g, '');
			if(cuerpo == ""){ 
				document.getElementById("sumbmit_prueba_correo").disabled = false;
				$("#mensaje_correo_prueba").html("Cuerpo requerido");
				document.getElementById("mensaje_correo_prueba").classList.add("mensajeError");
				return false;
			}
			var cuerpo = new nicEditors.findEditor('cuerpo');
			cuerpo = cuerpo.getContent();



			var correo_electronico_prueba = [];
			var data = {
					'id_correo_mailing' : id_correo_mailing,
					'correo_prueba' : correo_prueba,
					'asunto' : asunto, 
					'cuerpo' : cuerpo, 
				}
			correo_electronico_prueba.push(data);
			$.ajax({
				type: "POST",
				url: "campanasMailing/correo_preview.php",
				data: {correo_electronico_prueba: correo_electronico_prueba},
				success: function(data) {
					//document.getElementById("form").reset();  
					//document.getElementById("form").style.border="";
					//
					if(data=="1"){
						document.getElementById("sumbmit_prueba_correo").disabled = false;
						$("#mensaje_correo_prueba").html("&nbsp;");
						document.getElementById("mensaje_correo_prueba").classList.remove("mensajeError");
						$("#mensaje_correo_prueba").html("Preview Enviado con éxito"); 
						document.getElementById("mensaje_correo_prueba").classList.add("mensajeSucces"); 
					}else{
						document.getElementById("mensaje_correo_prueba").classList.add("mensajeError");
						document.getElementById("sumbmit_prueba_correo").disabled = false;
						$("#mensaje_correo_prueba").html(data);
					}
				}
			});
		}
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
				url: "campanasMailing/cartografias.php",
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
			<input class="inputlogin" type="text" style="width: 100%" name="nombre" autocomplete="off"  id="nombre" value="<?= $campana_mailingDatos['nombre'] ?>" placeholder="Nombre" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Correo Mailing<font color="#FF0004">*</font></label><br>
			<select id="id_correo_mailing" class="myselect" name="id_correo_mailing" >';
				<?php	echo correos_mailing($campana_mailingDatos['id_correo_mailing']); ?>
			</select><br> 
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Estatus<font color="#FF0004">*</font></label><br>
			<select id="status" class="myselect" name="status" >';
				<?php	echo statusGeneralForm($campana_mailingDatos['status']); ?>
			</select><br> 
		</div>

		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Tipo de Campaña</label>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo<font color="#FF0004">*</font></label><br>
			<select id="tipo" class="myselect" name="tipo" onchange="tipo_programado(this.value)" >
				<?php
				$selectTipo[$campana_mailingDatos['tipo']]='selected="selected"';
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
				<input class="inputlogin" <?= $inputFecha; ?> type="text" name="fecha" autocomplete="off"  id="fecha" value="<?= $campana_mailing_programadaDatos['fecha'] ?>" placeholder="" /><br>
			</div>

			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Hora<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" <?= $inputHora; ?> type="text" name="hora" autocomplete="off"  id="hora" value="<?= $campana_mailing_programadaDatos['hora'] ?>" placeholder="" /><br>
			</div>
		</div>

		<div id="div_encuesta" style="display: <?= $div_encuesta ?>">
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Encuesta<font color="#FF0004">*</font></label><br>
				<select id="id_encuesta" class="myselect" name="id_encuesta" >
					<?= encuestas($campana_mailing_encuestaDatos['id_encuesta']); ?>
				</select>
			</div>
		</div>


		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Tipo de Cartografía</label>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo<font color="#FF0004">*</font></label><br>
			<select id="tipo_cartografia" class="myselect" name="tipo_cartografia" onchange="tipo_cartografia(this.value)" >
				<?php
				$selectTipoCartografia[$campana_mailing_cartografiaDatos['tipo_cartografia']]='selected="selected"';
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
			if($campanas_mailing_tipos_ciudadanosIdDatos[$value['id']]['id']!=''){
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
				if($campanas_mailing_tipos_categorias_ciudadanosIdDatos[$value['id']]['id']!=''){
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
			<label class="labelForm" id="labeltemaname">Campaña Prueba</label>
		</div>
		<div class="sucForm" style="width: 100%">
			<div id="mensaje_correo_prueba" class="mensajeSolo" ></div>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Correo prueba<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="correo_prueba" autocomplete="off"  id="correo_prueba" value="<?= $campana_mailing_programadaDatos['correo_prueba'] ?>" placeholder="" /><br> 
			<button  class="btn btn-primary" id="sumbmit_prueba_correo" onclick="test_correo_prueba()">Probar Campaña</button>
		</div>

		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Campaña</label>
		</div>

		<div class="sucForm" style="width: 100%">
			<input type="hidden" id="remove_asunto" value="" >
			<input type="button" id="sumbmit" onclick="addAsunto()" value="Editor HTML">
			<input type="button" id="sumbmit" onclick="removeAsunto()" value="Remover HTML"><br><br>
			<label class="labelForm" id="labeltemaname">Asunto<font color="#FF0004">*</font></label><br>
			<textarea id="asunto" style="width: 100%;height: 50px" rows="1"><?= $campana_mailing_cuerpoDatos['asunto'] ?></textarea><br>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Cuerpo<font color="#FF0004">*</font></label><br>
			<input type="hidden" id="remove_cuerpo" value="" >
			<input type="button" id="sumbmit" onclick="addCuerpo()" value="Editor HTML">
			<input type="button" id="sumbmit" onclick="removeCuerpo()" value="Remover HTML"><br><br>
			<textarea id="cuerpo" style="width: 100%;height: 200px"><?= $campana_mailing_cuerpoDatos['cuerpo'] ?></textarea><br>
			<script type="text/javascript" src="js/nicEdit.js"></script>
			<script type="text/javascript" src="js/variablesnicEdit.js"></script>
			<script type="text/javascript">
				function reLoadEditorAsunto() {
					var remove_asunto = document.getElementById("remove_asunto").value;
					if(remove_asunto==0){
						return false;
					}
					removeAsunto();
					addAsunto();
				};
				function reLoadEditorCuerpo() {
					var remove_cuerpo = document.getElementById("remove_cuerpo").value;
					if(remove_cuerpo==0){
						return false;
					}
					removeCuerpo();
					addCuerpo();
				};
				$(window).resize( function() {
					reLoadEditorAsunto();
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
							'bold',
							'italic',
							'underline',
							'left',
							'center',
							'right',
							'justify',
							'ol',
							'ul',
							//'subscript',
							//'superscript',
							//'strikethrough',
							'removeformat',
							'indent',
							'outdent',
							'hr',
							'image',
							'upload',
							'forecolor',
							'bgcolor',
							'link',
							'unlink',
							'xhtml',
							'fontSize',
							'fontFamily',
							'fontFormat',
							'Datos_Correo',
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
			<script type="text/javascript">
				addAsunto();
				var asunto;
				function addAsunto(){
					var remove_asunto = document.getElementById("remove_asunto").value;
					if(remove_asunto==1){
						return false;
					}
					document.getElementById("remove_asunto").value = 1;
					asunto = new nicEditor({
						buttonList : 
						[
							'Datos_Correo',
							'Datos_Correo_Fecha_Hora',
							'Ciudadanos',
							'Ciudadanos_Usuarios',
							'Ciudadanos_Cartografia',
						]
					}).panelInstance('asunto',{hasPanel : true});
				}
				function removeAsunto() {
					var remove_asunto = document.getElementById("remove_asunto").value;
					if(remove_asunto==0){
						return false;
					}
					asunto.removeInstance('asunto');
					document.getElementById("remove_asunto").value = 0;
				} 
			</script>
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