<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','campañas_sms',$_COOKIE["id_usuario"]);
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
				url: "campanasSMS/cartografias.php",
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
			<input class="inputlogin" type="text" style="width: 100%" name="nombre" autocomplete="off"  id="nombre" value="<?= $campana_smsDatos['nombre'] ?>" placeholder="Nombre" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">API SMS<font color="#FF0004">*</font></label><br>
			<select id="id_api_sms" class="myselect" name="id_api_sms" >';
				<?php	echo api_sms($campana_smsDatos['id_api_sms']); ?>
			</select><br> 
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Estatus<font color="#FF0004">*</font></label><br>
			<select id="status" class="myselect" name="status" >';
				<?php	echo statusGeneralForm($campana_smsDatos['status']); ?>
			</select><br> 
		</div>

		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Tipo de Campaña</label>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo<font color="#FF0004">*</font></label><br>
			<select id="tipo" class="myselect" name="tipo" onchange="tipo_programado(this.value)" >
				<?php
				$selectTipo[$campana_smsDatos['tipo']]='selected="selected"';
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
				<input class="inputlogin" <?= $inputFecha; ?> type="text" name="fecha" autocomplete="off"  id="fecha" value="<?= $campana_sms_programadaDatos['fecha'] ?>" placeholder="" /><br>
			</div>

			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Hora<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" <?= $inputHora; ?> type="text" name="hora" autocomplete="off"  id="hora" value="<?= $campana_sms_programadaDatos['hora'] ?>" placeholder="" /><br>
			</div>
		</div>

		<div id="div_encuesta" style="display: <?= $div_encuesta ?>">
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Encuesta<font color="#FF0004">*</font></label><br>
				<select id="id_encuesta" class="myselect" name="id_encuesta" >
					<?= encuestas($campana_sms_encuestaDatos['id_encuesta']); ?>
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
				$selectTipoCartografia[$campana_sms_cartografiaDatos['tipo_cartografia']]='selected="selected"';
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
			if($campanas_sms_tipos_ciudadanosIdDatos[$value['id']]['id']!=''){
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
				if($campanas_sms_tipos_categorias_ciudadanosIdDatos[$value['id']]['id']!=''){
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

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Cuerpo<font color="#FF0004">*</font></label><br>
			<input type="hidden" id="remove_cuerpo" value="" >
			<input type="button" id="sumbmit" onclick="addCuerpo()" value="Editor HTML">
			<input type="button" id="sumbmit" onclick="removeCuerpo()" value="Remover HTML"><br><br>
			<textarea id="cuerpo" style="width: 100%;height: 200px"><?= $campana_sms_cuerpoDatos['cuerpo'] ?></textarea><br>
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