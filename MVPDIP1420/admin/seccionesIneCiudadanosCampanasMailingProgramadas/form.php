<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_campanas_mailing_programadas',$_COOKIE["id_usuario"]);
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
			<label class="labelForm" id="labeltemaname">Datos Ciudadano Campaña</label>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">ciudadano<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" disabled="disabled" type="text" style="width: 100%" value="<?= $seccion_ine_ciudadano_campana_mailing_programadaDatos['nombre_completo'] ?>" ><br>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Correo Electrónico<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" disabled="disabled" type="text" style="width: 100%" value="<?= $seccion_ine_ciudadano_campana_mailing_programadaDatos['correo_electronico'] ?>" ><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Nombre<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" disabled="disabled" type="text" style="width: 100%" value="<?= $seccion_ine_ciudadano_campana_mailing_programadaDatos['nombre'] ?>" ><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Estatus<font color="#FF0004">*</font></label><br>
			<?php
			$selectStatus[$seccion_ine_ciudadano_campana_mailing_programadaDatos['status']]='selected="selected" ';
			?>
			<select class="myselect" id="status" >
				<option <?= $selectStatus[0] ?> value="0">Reenviar</option>
				<option <?= $selectStatus[1] ?> value="1">Enviado</option>
				<option <?= $selectStatus[2] ?> value="2">No Enviado</option>
				<option <?= $selectStatus[3] ?> value="3">Leído</option>
				<option <?= $selectStatus[4] ?> value="4">Cancelado</option>
			</select><br>
		</div>

		<div class="sucForm"  style="width: 100%">
			<label class="labelForm" id="labeltemaname">Asunto<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" disabled="disabled" type="text" style="width: 100%"  value="<?= $seccion_ine_ciudadano_campana_mailing_programadaDatos['asunto'] ?>" ><br>
		</div>
		<?= $seccion_ine_ciudadano_campana_mailing_programadaDatos['cuerpo'] ?>
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