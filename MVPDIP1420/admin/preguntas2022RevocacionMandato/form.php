<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','preguntas_2022_revocacion_mandato',$_COOKIE["id_usuario"]);
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
			<label class="labelForm" id="labeltemaname">Datos pregunta consulta</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" <?= $claveF['input'] ?> type="text" name="clave" autocomplete="off"  id="clave" value="<?= $pregunta_2022_revocacion_mandatoDatos['clave'] ?>" placeholder="" maxlength="120" onkeyup="clave(this.value)"/><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Nombre Corto<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="nombre_corto" autocomplete="off"  id="nombre_corto" value="<?= $pregunta_2022_revocacion_mandatoDatos['nombre_corto'] ?>" placeholder="Nombre" onblur="aMays(event, this)"/><br>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Nombre<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="nombre" autocomplete="off"  id="nombre" value="<?= $pregunta_2022_revocacion_mandatoDatos['nombre'] ?>" placeholder="Nombre" /><br>
		</div>
		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Tipo<font color="#FF0004">*</font></label><br>
			<?php
			$select[$pregunta_2022_revocacion_mandatoDatos['tipo']] = 'selected="selected"';
			?>
			<select name="tipo" id="tipo" class='myselect'>  
				<!--<option value="">Seleccione</option>-->
				<?php
				if($tipo_uso_plataforma=='municipio'){
					?>
					<option <?= $select['0'] ?> value="0">Ayuntamiento</option>
					<?php
				}elseif($tipo_uso_plataforma=='distrito_local'){
					?>
					<option <?= $select['1'] ?> value="1">Distrito Local</option>
					<?php
				}elseif($tipo_uso_plataforma=='distrito_federal'){
					?>
					<option <?= $select['2'] ?> value="2">Distrito Federal</option>
					<?php
				}else{
					?>
					<option <?= $select['0'] ?> value="0">Ayuntamiento</option>
					<option <?= $select['1'] ?> value="1">Distrito Local</option>
					<option <?= $select['2'] ?> value="2">Distrito Federal</option>
					<?php
				}
				?>
			</select>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Diseño</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Icono<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="icono" autocomplete="off"  id="icono" value="<?= $pregunta_2022_revocacion_mandatoDatos['icono'] ?>" placeholder="Nombre" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Logo<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="logo" autocomplete="off"  id="logo" value="<?= $pregunta_2022_revocacion_mandatoDatos['logo'] ?>" placeholder="Nombre" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Border Color<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="color_border" autocomplete="off"  id="color_border" value="<?= $pregunta_2022_revocacion_mandatoDatos['color_border'] ?>" placeholder="Nombre" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Background Color<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="color_background" autocomplete="off"  id="color_background" value="<?= $pregunta_2022_revocacion_mandatoDatos['color_background'] ?>" placeholder="Nombre" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Principal<font color="#FF0004">*</font></label><br>
			<select name="principal" id="principal" class='myselect'>
				<?php
					$principal_slct[$pregunta_2022_revocacion_mandatoDatos['principal']] = 'selected="selected"';
				?>
				<option <?= $principal_slct[0] ?> value="">Seleccione</option>
				<option <?= $principal_slct[1] ?> value="1">Principal</option>
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