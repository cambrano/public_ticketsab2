<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','partidos_2024',$_COOKIE["id_usuario"]);
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
			<label class="labelForm" id="labeltemaname">Datos Partido</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" <?= $claveF['input'] ?> type="text" name="clave" autocomplete="off"  id="clave" value="<?= $partido_2024Datos['clave'] ?>" placeholder="" maxlength="120" onkeyup="clave(this.value)"/><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Nombre Corto<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="nombre_corto" autocomplete="off"  id="nombre_corto" value="<?= $partido_2024Datos['nombre_corto'] ?>" placeholder="Nombre" onblur="aMays(event, this)"/><br>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Nombre<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="nombre" autocomplete="off"  id="nombre" value="<?= $partido_2024Datos['nombre'] ?>" placeholder="Nombre" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo<font color="#FF0004">*</font></label><br>
			<?php
			$select[$partido_2024Datos['tipo']] = 'selected="selected"';
			?>
			<select name="tipo" id="tipo" class='myselect'>  
				<option value="">Seleccione</option>
				<?php
				if($tipo_uso_plataforma=='municipio'){
					?>
					<option <?= $select['0'] ?> value="0">Municipio</option>
					<?php
				}elseif($tipo_uso_plataforma=='distrito_local'){
					?>
					<option <?= $select['1'] ?> value="1">Distrito Local</option>
					<?php
				}elseif($tipo_uso_plataforma=='distrito_federal'){
					?>
					<option <?= $select['2'] ?> value="2">Distrito Federal</option>
					<?php
				}elseif($tipo_uso_plataforma=='gobernador'){
					?>
					<option <?= $select['3'] ?> value="3">Gobernador</option>
					<?php
				}elseif($tipo_uso_plataforma=='senador'){
					?>
					<option <?= $select['4'] ?> value="4">Senador</option>
					<?php
				}else{
					?>
					<option <?= $select['0'] ?> value="0">Municipio</option>
					<option <?= $select['1'] ?> value="1">Distrito Local</option>
					<option <?= $select['2'] ?> value="2">Distrito Federal</option>
					<option <?= $select['3'] ?> value="3">Gobernador</option>
					<option <?= $select['4'] ?> value="4">Senador</option>
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
			<input class="inputlogin" type="text" style="width: 100%" name="icono" autocomplete="off"  id="icono" value="<?= $partido_2024Datos['icono'] ?>" placeholder="Nombre" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Logo<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="logo" autocomplete="off"  id="logo" value="<?= $partido_2024Datos['logo'] ?>" placeholder="Nombre" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Border Color<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="color_border" autocomplete="off"  id="color_border" value="<?= $partido_2024Datos['color_border'] ?>" placeholder="Nombre" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Background Color<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="color_background" autocomplete="off"  id="color_background" value="<?= $partido_2024Datos['color_background'] ?>" placeholder="Nombre" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Principal<font color="#FF0004">*</font></label><br>
			<select name="principal" id="principal" class='myselect'>
				<?php
					$principal_slct[$partido_2024Datos['principal']] = 'selected="selected"';
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