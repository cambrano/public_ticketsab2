<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','empresas_adjudicadas',$_COOKIE["id_usuario"]);
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
			<label class="labelForm" id="labeltemaname">Datos Empresa Adjudicada</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" <?= $claveF['input'] ?> type="text" style="width: 100%" name="clave" autocomplete="off"  id="clave" value="<?= $empresa_adjudicadaDatos['clave'] ?>" placeholder="Clave" onkeyup="clave(this.value)" /><br>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Nombre<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="nombre" autocomplete="off"  id="nombre" value="<?= $empresa_adjudicadaDatos['nombre'] ?>" placeholder="Nombre" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Rep. Nombre<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="representante_nombre" autocomplete="off"  id="representante_nombre" value="<?= $empresa_adjudicadaDatos['representante_nombre'] ?>" placeholder="" maxlength="120"/><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Rep. Apellido Paterno<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="representante_apellido_paterno" autocomplete="off"  id="representante_apellido_paterno" value="<?= $empresa_adjudicadaDatos['representante_apellido_paterno']  ?>" placeholder="" maxlength="120"/><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Rep. Apellido Materno<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="representante_apellido_materno" autocomplete="off"  id="representante_apellido_materno" value="<?= $empresa_adjudicadaDatos['representante_apellido_materno']  ?>" placeholder="" maxlength="120"/><br>
		</div>

		<div class="sucForm" style="width: 100%"></div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Correo Electrónico</label><br>
			<input class="inputlogin" type="text" name="correo_electronico" autocomplete="off"  id="correo_electronico" value="<?= $empresa_adjudicadaDatos['correo_electronico']  ?>" placeholder="" maxlength="120"/><br><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Teléfono</label><br>
			<input class="inputlogin" type="text" name="telefono" autocomplete="off"  id="telefono" value="<?= $empresa_adjudicadaDatos['telefono']  ?>" placeholder="" maxlength="120"/><br><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Celular</label><br>
			<input class="inputlogin" type="text" name="celular" autocomplete="off"  id="celular" value="<?= $empresa_adjudicadaDatos['celular']  ?>" placeholder="" maxlength="120"/><br><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Whatsapp</label><br>
			<input class="inputlogin" type="text" name="whatsapp" autocomplete="off"  id="whatsapp" value="<?= $empresa_adjudicadaDatos['whatsapp']  ?>" placeholder="" maxlength="120"/><br><br>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Observaciones<font color="#FF0004">*</font></label><br>
			<textarea id="observaciones" style="width: 99%;height: 150px"><?= $empresa_adjudicadaDatos['observaciones'] ?></textarea> <br>
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