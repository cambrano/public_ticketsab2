	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">SMS</label>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Nombre<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="nombre" autocomplete="off"  id="nombre" value="<?= $api_smsDatos['nombre'] ?>" placeholder="" /><br>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Url SMS<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="url" autocomplete="off"  id="url" value="<?= $api_smsDatos['url'] ?>" placeholder="https://www.example.com/" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Modo<font color="#FF0004">*</font></label><br>
			<?php 
				$selectModo[$api_smsDatos['modo']]='selected="selected"';
			?>
			<select class="myselect" id="modo">
				<option <?= $selectModo[1] ?> value="1">demo</option>
				<option <?= $selectModo[0] ?> value="0">produccion</option> 
			</select>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Key Sandbox<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="key_sandbox" autocomplete="off"  id="key_sandbox" value="<?= $api_smsDatos['key_sandbox'] ?>" placeholder="" /><br>
			<br>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Key Producción<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="key_produccion" autocomplete="off"  id="key_produccion" value="<?= $api_smsDatos['key_produccion'] ?>" placeholder="" /><br>
			<br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Max Caracteres<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="max_caracteres" autocomplete="off"  id="max_caracteres" value="<?= $api_smsDatos['max_caracteres'] ?>" placeholder="" /><br>
			<br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Max Caracteres Especiales<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="max_caracteres_especiales" autocomplete="off"  id="max_caracteres_especiales" value="<?= $api_smsDatos['max_caracteres_especiales'] ?>" placeholder="" /><br>
			<br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tiempo en espera segndos<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="tiempo_espera_segundos" autocomplete="off"  id="tiempo_espera_segundos" value="<?= $api_smsDatos['tiempo_espera_segundos'] ?>" placeholder="" /><br>
			<br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Mensajes a enviár<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="mensajes_a_enviar" autocomplete="off"  id="mensajes_a_enviar" value="<?= $api_smsDatos['mensajes_a_enviar'] ?>" placeholder="" /><br>
			<br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tiempo Script<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="tiempo_script" autocomplete="off"  id="tiempo_script" value="<?= $api_smsDatos['tiempo_script'] ?>" placeholder="" /><br>
			<br>
		</div>

		<div class="sucForm" style="width: 100%"></div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Estatus<font color="#FF0004">*</font></label><br>
			<select id="status" class="myselect" name="status" >
				<?php echo statusGeneralForm($api_smsDatos['status']); ?>
			</select><br>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">SMS Proveedor</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Mensaje Proveedor<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="mensaje_proveedor" autocomplete="off"  id="mensaje_proveedor" value="<?= $api_smsDatos['mensaje_proveedor'] ?>" placeholder="" /><br>
			<br>
		</div>

		<div class="sucForm" ></div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Estatus Proveedor<font color="#FF0004">*</font></label><br>
			<select id="status_proveedor" class="myselect" name="status_proveedor" >
				<?php echo statusGeneralForm($api_smsDatos['status_proveedor']); ?>
			</select><br>
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