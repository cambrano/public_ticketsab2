<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','correos_mailing',$_COOKIE["id_usuario"]);
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
			<label class="labelForm" id="labeltemaname">Datos Servidor de Correo</label>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Nombre<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="nombre" autocomplete="off" id="nombre" value="<?= $correo_mailingDatos['nombre'] ?>" placeholder=""  maxlength="120" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Servidor<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="servidor" autocomplete="off" id="servidor" value="<?= $correo_mailingDatos['servidor'] ?>" placeholder=""  maxlength="120" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Cifrado<font color="#FF0004">*</font></label><br>
			<select id="cifrado" class="myselect" >
				<?php $sf_servidor[$cifrado]='selected="selected"' ?>
				<option <?= $sf_servidor["ssl"] ?> value="ssl" >SSL</option>
				<option <?= $sf_servidor["tls"] ?> value="tls" >TLS</option>
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Puerto<font color="#FF0004">*</font></label><br>
			<input type="text" name="monto_inicial" autocomplete="off"  id="puerto" value="<?= $correo_mailingDatos['puerto'] ?>" onkeypress="return CheckNumeric()"/><br>
		</div>
		<div class="sucForm" style="width: 100%"></div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Correo</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">De<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="de" autocomplete="off" id="de" value="<?= $correo_mailingDatos['de'] ?>" placeholder=""  maxlength="120" /><br>
		</div>
		<div class="sucForm" style="width: 100%"></div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Usuario<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="usuario" autocomplete="off" id="usuario" value="<?= $correo_mailingDatos['usuario'] ?>" placeholder=""  maxlength="120" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Password<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="password" name="password" autocomplete="off"  id="password" value="<?= $correo_mailingDatos['password'] ?>" placeholder="" maxlength="120" /><br>
		</div>
		<div class="sucForm" style="width: 100%"></div>
		<div class="sucForm">
			<label class="labelForm" id="labelMostrar">Mostrar</label><input type="checkbox"  id="monstar_contraseña" value="1"><br>
		</div>
		<div class="sucForm" style="width: 100%"></div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Correo Reply <font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="correo_electronico" autocomplete="off" id="correo_electronico" value="<?= $correo_mailingDatos['correo_electronico'] ?>" placeholder=""  maxlength="120" /><br>
		</div>
		<div class="sucForm" style="width: 100%">
			<br>
			<?php
			if($moduloAccionPermisos[$permiso] || $moduloAccionPermisos['all']){
				?>
				<input type="button" id="sumbmit" onclick="guardar()" value="Guardar">
				<!--<input type="button" onclick="ResetInput()" value="Borrar">-->
				<input type="button" value="Cancelar" onclick="cerrar()">
				<?php
			}
			?>
		</div>
	</div>
	<script type="text/javascript">
		$(".myselect").select2();
	</script>