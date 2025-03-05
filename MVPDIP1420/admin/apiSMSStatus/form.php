<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','api_sms',$_COOKIE["id_usuario"]);
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
			<label class="labelForm" id="labeltemaname">Datos Tipo Actividad</label>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Apis SMS<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_api_sms" >
				<?php
				echo api_sms($api_sms_statusDatos['id_api_sms']);
				?>
			</select><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Nombre<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="nombre" autocomplete="off"  id="nombre" value="<?= $api_sms_statusDatos['nombre'] ?>" placeholder="Nombre" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Código<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="codigo" autocomplete="off"  id="codigo" value="<?= $api_sms_statusDatos['codigo'] ?>" placeholder="Nombre" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo<font color="#FF0004">*</font></label><br>
			<?php 
				$selectModo[$api_sms_statusDatos['tipo']]='selected="selected"';
			?>
			<select class="myselect" id="tipo">
				<option <?= $selectModo['bad'] ?> value="bad">Bad</option>
				<option <?= $selectModo['success'] ?> value="success">Success</option> 
				<option <?= $selectModo['offline'] ?> value="offline">Offline</option> 
				<option <?= $selectModo['online'] ?> value="online">Online</option> 
			</select>
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