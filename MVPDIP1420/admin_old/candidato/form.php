	<script type="text/javascript">
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
			<label class="labelForm" id="labeltemaname">Información Candidato</label>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Nombre Completo<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="nombre_completo" autocomplete="off"  id="nombre_completo" value="<?= $candidatoDatos['nombre_completo'] ?>" placeholder="" /><br>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Descripción Corta</label><br>
			<textarea id="descripcion_corta" style="width: 99%;height: 80px"><?= $candidatoDatos['descripcion_corta'] ?></textarea> <br>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Descripción</label><br>
			<textarea id="descripcion" style="width: 99%;height: 150px"><?= $candidatoDatos['descripcion'] ?></textarea> <br>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Contacto Bunker Candidato</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Latitud<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="latitud" autocomplete="off"  id="latitud" value="<?= $candidatoDatos['latitud'] ?>" placeholder="" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Longitud<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="longitud" autocomplete="off"  id="longitud" value="<?= $candidatoDatos['longitud'] ?>" placeholder="" /><br>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Dirección Completa<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="direccion_completa" autocomplete="off"  id="direccion_completa" value="<?= $candidatoDatos['direccion_completa'] ?>" placeholder="" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Teléfono<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="telefono" autocomplete="off"  id="telefono" value="<?= $candidatoDatos['telefono'] ?>" placeholder="" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Whatsapp<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="whatsapp" autocomplete="off"  id="whatsapp" value="<?= $candidatoDatos['whatsapp'] ?>" placeholder="" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Correo Electrónico<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="correo_electronico" autocomplete="off"  id="correo_electronico" value="<?= $candidatoDatos['correo_electronico'] ?>" placeholder="" /><br>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Redes Sociales</label>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Twitter<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="twitter" autocomplete="off"  id="twitter" value="<?= $candidatoDatos['twitter'] ?>" placeholder="" /><br>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Facebook<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="facebook" autocomplete="off"  id="facebook" value="<?= $candidatoDatos['facebook'] ?>" placeholder="" /><br>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Instagram<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="instagram" autocomplete="off"  id="instagram" value="<?= $candidatoDatos['instagram'] ?>" placeholder="" /><br>
		</div> 
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Tipo de Cartografía</label>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo<font color="#FF0004">*</font></label><br>
			<select id="tipo_cartografia" class="myselect" name="tipo_cartografia" onchange="tipo_cartografia(this.value)" >
				<?php
				$selectTipoCartografia[$candidatoDatos['tipo_cartografia']]='selected="selected"';
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
			<label class="labelForm" id="labeltemaname">Diseño</label>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Link Video<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="link_video" autocomplete="off"  id="link_video" value="<?= $candidatoDatos['link_video'] ?>" placeholder="" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Color Principal<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="color_principal" autocomplete="off"  id="color_principal" value="<?= $candidatoDatos['color_principal'] ?>" placeholder="" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Color Secundario<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="color_secundario" autocomplete="off"  id="color_secundario" value="<?= $candidatoDatos['color_secundario'] ?>" placeholder="" /><br>
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