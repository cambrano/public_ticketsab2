<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','supervisores',$_COOKIE["id_usuario"]);
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
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Supervisor</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" <?= $claveF['input'] ?> type="text" style="width: 100%" name="clave" autocomplete="off"  id="clave" value="<?= $supervisorDatos['clave'] ?>" placeholder="Clave" onkeyup="clave(this.value)" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Nombre<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="nombre" autocomplete="off"  id="nombre" value="<?= $supervisorDatos['nombre'] ?>" placeholder="" maxlength="120"/><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Apellido Paterno<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="apellido_paterno" autocomplete="off"  id="apellido_paterno" value="<?= $supervisorDatos['apellido_paterno']  ?>" placeholder="" maxlength="120"/><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Apellido Materno<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="apellido_materno" autocomplete="off"  id="apellido_materno" value="<?= $supervisorDatos['apellido_materno']  ?>" placeholder="" maxlength="120"/><br>
		</div>

		<div class="sucForm" style="width: 100%"></div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Correo Electrónico<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="correo_electronico" autocomplete="off"  id="correo_electronico" value="<?= $supervisorDatos['correo_electronico']  ?>" placeholder="" maxlength="120"/><br><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Teléfono</label><br>
			<input class="inputlogin" type="text" name="telefono" autocomplete="off"  id="telefono" value="<?= $supervisorDatos['telefono']  ?>" placeholder="" maxlength="120"/><br><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Celular</label><br>
			<input class="inputlogin" type="text" name="celular" autocomplete="off"  id="celular" value="<?= $supervisorDatos['celular']  ?>" placeholder="" maxlength="120"/><br><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Whatsapp</label><br>
			<input class="inputlogin" type="text" name="whatsapp" autocomplete="off"  id="whatsapp" value="<?= $supervisorDatos['whatsapp']  ?>" placeholder="" maxlength="120"/><br><br>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Observaciones</label><br>
			<textarea id="observaciones" style="width: 99%;height: 150px"><?= $supervisorDatos['observaciones'] ?></textarea> <br>
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