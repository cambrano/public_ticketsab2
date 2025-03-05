<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('configuracion','empleados',$_COOKIE["id_usuario"]);
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
		function passwordGen(length) {
			var length = 6;
			var result           = [];
			var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			var charactersLength = characters.length;
			for ( var i = 0; i < length; i++ ) {
				result.push(characters.charAt(Math.floor(Math.random() * charactersLength)));
			}
			/*return result.join('');*/
			document.getElementById("password").value = result.join('');
			document.getElementById("password1").value = result.join('');
		}
	</script>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
				<label class="labelForm" id="labeltemaname">Datos Empleado</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="clave" autocomplete="off" <?= $claveF['input'] ?> id="clave" value="<?= $empleadoDatos['clave']  ?>" maxlength="20" onkeyup="clave(this.value)"/><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Empleado Grupo<font color="#FF0004">*</font></label><br>
			<select name="id_empleado_grupo" id="id_empleado_grupo" class='myselect'>  
				<?php echo empleados_grupos($empleadoDatos['id_empleado_grupo']) ?>
			</select>
		</div>
		<div class="sucForm" style="width: 100%"></div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Nombre<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="nombre" autocomplete="off"  id="nombre" value="<?= $empleadoDatos['nombre'] ?>" placeholder="" maxlength="120"/><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Apellido Paterno<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="apellido_paterno" autocomplete="off"  id="apellido_paterno" value="<?= $empleadoDatos['apellido_paterno']  ?>" placeholder="" maxlength="120"/><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Apellido Materno<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="apellido_materno" autocomplete="off"  id="apellido_materno" value="<?= $empleadoDatos['apellido_materno']  ?>" placeholder="" maxlength="120"/><br>
		</div>

		<div class="sucForm" style="width: 100%"></div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Correo Electrónico<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="correo_electronico" autocomplete="off"  id="correo_electronico" value="<?= $empleadoDatos['correo_electronico']  ?>" placeholder="" maxlength="120"/><br><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Teléfono<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="telefono" autocomplete="off"  id="telefono" value="<?= $empleadoDatos['telefono']  ?>" placeholder="" maxlength="120"/><br><br>
		</div>

		<div class="sucFormTitulo">
				<label class="labelForm" id="labeltemaname">Datos Usuario</label>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Usuario<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="usuario" autocomplete="off"  id="usuario" value="<?= $usuarioDatos['usuario']  ?>" placeholder="" maxlength="45" /><br>
		</div>

		<div class="sucForm" style="width: 100%"></div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Password<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="password" name="password" autocomplete="off"  id="password" value="<?= $usuarioDatos['password'] ?>" placeholder="" maxlength="10" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Repetir Password<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="password" name="password1" autocomplete="off"  id="password1" value="<?= $usuarioDatos['password'] ?>" placeholder="" maxlength="10" />
		</div>
		<div class="sucForm" style="width: 100%"></div>
		<div class="sucForm">
			<label class="labelForm" id="labelMostrar">Mostrar</label><input type="checkbox"  id="monstar_contraseña" value="1"><br>
			<input type="button" onclick="passwordGen(6)" value="Generar password">
		</div>
		<div class="sucForm" style="width: 100%">
			<?php
				if(!empty($id)){

					echo '<label class="labelForm" id="labeltemaname">Estatus<font color="#FF0004">*</font></label><br>';
					echo '<select id="status" class="myselect" name="status" >';
					echo statusGeneralForm($usuarioDatos['status']);
					echo '</select><br><br>';
				} 
			?> 
		</div>

		<div class="sucForm" style="width: 100%">
			<br>
			<?php
			if($moduloAccionPermisos[$permiso] || $moduloAccionPermisos['all']){
				?>
				<input type="button" id="sumbmit" onclick="guardar()" value="Guardar">
				<?php
			}
			?>
			<!--<input type="button" onclick="ResetInput()" value="Borrar">-->
			<input type="button" value="Cancelar" onclick="cerrar()">
		</div> 
	</div>
	<script type="text/javascript">
		$(".myselect").select2();
	</script>