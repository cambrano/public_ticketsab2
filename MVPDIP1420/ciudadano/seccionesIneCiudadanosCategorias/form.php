<?php
	if($switch_operacionesPermisos['evaluacion']==false){
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
			<label class="labelForm" id="labeltemaname">Categorías</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="clave" autocomplete="off" disabled="disabled" id="clave" value="<?= $secciones_ine_ciudadanos_categoriasDatos[0]['clave'] ?>" onkeyup="clave(this.value)" /><br>
		</div>

		<?php
		foreach ($tipos_categorias_ciudadanosDatos as $key => $value) {
			?>
			<div class="sucForm" style="width: 100%">
				<label class="labelForm" id="labeltemaname"><?= $value['nombre'] ?></label>
				<input type="checkbox" id="id_tipo_categoria_ciudadano_<?= $value['id'] ?>" value="SI" <?= $value['checked'] ?> >
			</div>

			<?php
		}
		?>

		<div class="sucForm" style="width: 100%">
			<br>
			<?php
			if($switch_operacionesPermisos['evaluacion']){
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