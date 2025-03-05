	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Whatsapp Basic</label>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Nombre<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="nombre" autocomplete="off"  id="nombre" value="<?= $api_whatsappDatos['nombre'] ?>" placeholder="" /><br>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">API Whatsapp<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="api_whatsapp" autocomplete="off"  id="api_whatsapp" value="<?= $api_whatsappDatos['api_whatsapp'] ?>" placeholder="https://www.example.com/" /><br>
		</div>


		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Code<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="code" autocomplete="off"  id="code" value="<?= $api_whatsappDatos['code'] ?>" placeholder="" /><br>
			<br>
		</div>

		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">API Whatsapp</label>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Url<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="url" autocomplete="off"  id="url" value="<?= $api_whatsappDatos['url'] ?>" placeholder="" /><br>
			<br>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Account SID<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="account_sid" autocomplete="off"  id="account_sid" value="<?= $api_whatsappDatos['account_sid'] ?>" placeholder="" /><br>
			<br>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Auth Token<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="auth_token" autocomplete="off"  id="auth_token" value="<?= $api_whatsappDatos['auth_token'] ?>" placeholder="" /><br>
			<br>
		</div>


		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">de<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="de" autocomplete="off"  id="de" value="<?= $api_whatsappDatos['de'] ?>" placeholder="" /><br>
			<br>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Status Call Back Plantillas<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="statusCallback_plantillas" autocomplete="off"  id="statusCallback_plantillas" value="<?= $api_whatsappDatos['statusCallback_plantillas'] ?>" placeholder="" /><br>
			<br>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Status Call Back Replyes<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="statusCallback_replys" autocomplete="off"  id="statusCallback_replys" value="<?= $api_whatsappDatos['statusCallback_replys'] ?>" placeholder="" /><br>
			<br>
		</div>

 

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tiempo en espera segndos<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="tiempo_espera_segundos" autocomplete="off"  id="tiempo_espera_segundos" value="<?= $api_whatsappDatos['tiempo_espera_segundos'] ?>" placeholder="" /><br>
			<br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Mensajes a enviár<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="mensajes_a_enviar" autocomplete="off"  id="mensajes_a_enviar" value="<?= $api_whatsappDatos['mensajes_a_enviar'] ?>" placeholder="" /><br>
			<br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tiempo Script<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="tiempo_script" autocomplete="off"  id="tiempo_script" value="<?= $api_whatsappDatos['tiempo_script'] ?>" placeholder="" /><br>
			<br>
		</div>

		<div class="sucForm" style="width: 100%"></div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Estatus<font color="#FF0004">*</font></label><br>
			<select id="status" class="myselect" name="status" >
				<?php echo statusGeneralForm($api_whatsappDatos['status']); ?>
			</select><br>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Whatsapp Proveedor</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Mensaje Proveedor<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="mensaje_proveedor" autocomplete="off"  id="mensaje_proveedor" value="<?= $api_whatsappDatos['mensaje_proveedor'] ?>" placeholder="" /><br>
			<br>
		</div>

		<div class="sucForm" ></div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Estatus Proveedor<font color="#FF0004">*</font></label><br>
			<select id="status_proveedor" class="myselect" name="status_proveedor" >
				<?php echo statusGeneralForm($api_whatsappDatos['status_proveedor']); ?>
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