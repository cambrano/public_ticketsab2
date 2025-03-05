	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Mailing</label>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Url Mailing<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="url_mailing" autocomplete="off"  id="url_mailing" value="<?= $api_mailingDatos['url_mailing'] ?>" placeholder="https://www.example.com/" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Estatus<font color="#FF0004">*</font></label><br>
			<select id="status" class="myselect" name="status" >
				<?php echo statusGeneralForm($api_mailingDatos['status']); ?>
			</select><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tiempo en espera segundos<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="tiempo_espera_segundos" autocomplete="off"  id="tiempo_espera_segundos" value="<?= $api_mailingDatos['tiempo_espera_segundos'] ?>" placeholder="" /><br>
			<br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Correos a enviár<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="correos_a_enviar" autocomplete="off"  id="correos_a_enviar" value="<?= $api_mailingDatos['correos_a_enviar'] ?>" placeholder="" /><br>
			<br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tiempo Script en segundos<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="tiempo_script" autocomplete="off"  id="tiempo_script" value="<?= $api_mailingDatos['tiempo_script'] ?>" placeholder="" /><br>
			<br>
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