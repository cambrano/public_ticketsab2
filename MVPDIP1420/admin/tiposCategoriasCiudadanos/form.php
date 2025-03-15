<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','tipos_categorias_ciudadanos',$_COOKIE["id_usuario"]);
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
			<label class="labelForm" id="labeltemaname">Datos Categoría</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" <?= $claveF['input'] ?> type="text" name="clave" autocomplete="off"  id="clave" value="<?= $tipo_categoria_ciudadanoDatos['clave'] ?>" placeholder="" maxlength="120" onkeyup="clave(this.value)"/><br>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Nombre<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="nombre" autocomplete="off"  id="nombre" value="<?= $tipo_categoria_ciudadanoDatos['nombre'] ?>" placeholder="Nombre" /><br>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Diseño</label>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Border Color<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="color_border" autocomplete="off"  id="color_border" value="<?= $tipo_categoria_ciudadanoDatos['color_border'] ?>" placeholder="Nombre" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Background Color<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="color_background" autocomplete="off"  id="color_background" value="<?= $tipo_categoria_ciudadanoDatos['color_background'] ?>" placeholder="Nombre" /><br>
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