<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_secciones_avance_semaforo',$_COOKIE["id_usuario"]);
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
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo Ciudadano<font color="#FF0004">*</font></label><br>
			<select name="id_tipo_ciudadano" id="id_tipo_ciudadano" class='myselect'>  
				<?php echo tipos_ciudadanos($tipo_ciudadano_seccion_avance_semaforoDatos['id_tipo_ciudadano']) ?>
			</select>
		</div>
		<div class="sucFormTitulo" style="background-color:red">
			<label class="labelForm" id="labeltemaname" style="color:white" >Rojo</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Rango Inicial Unidad<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="rojo_rango_inicial" autocomplete="off"  id="rojo_rango_inicial" value="<?= $tipo_ciudadano_seccion_avance_semaforoDatos['rojo_rango_inicial'] ?>" placeholder="Rango Inicial" onkeypress="return CheckNumeric()" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Rango Final Unidad<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="rojo_rango_final" autocomplete="off"  id="rojo_rango_final" value="<?= $tipo_ciudadano_seccion_avance_semaforoDatos['rojo_rango_final'] ?>" placeholder="Rango Final" onkeypress="return CheckNumeric()" /><br>
		</div>
		<div class="sucFormTitulo" style="background-color:yellow">
			<label class="labelForm" id="labeltemaname" style="color:black" >Amarillo</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Rango Inicial Unidad<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="amarillo_rango_inicial" autocomplete="off"  id="amarillo_rango_inicial" value="<?= $tipo_ciudadano_seccion_avance_semaforoDatos['amarillo_rango_inicial'] ?>" placeholder="Rango Inicial" onkeypress="return CheckNumeric()" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Rango Final Unidad<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="amarillo_rango_final" autocomplete="off"  id="amarillo_rango_final" value="<?= $tipo_ciudadano_seccion_avance_semaforoDatos['amarillo_rango_final'] ?>" placeholder="Rango Final" onkeypress="return CheckNumeric()" /><br>
		</div>
		<div class="sucFormTitulo" style="background-color:green">
			<label class="labelForm" id="labeltemaname" style="color:white" >Verde</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Rango Inicial Unidad<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="verde_rango_inicial" autocomplete="off"  id="verde_rango_inicial" value="<?= $tipo_ciudadano_seccion_avance_semaforoDatos['verde_rango_inicial'] ?>" placeholder="Rango Inicial" onkeypress="return CheckNumeric()" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Rango Final Unidad<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="verde_rango_final" autocomplete="off"  id="verde_rango_final" value="<?= $tipo_ciudadano_seccion_avance_semaforoDatos['verde_rango_final'] ?>" placeholder="Rango Final" onkeypress="return CheckNumeric()" /><br>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Estatus<font color="#FF0004">*</font></label><br>
			<select id="status" class="myselect" name="status" >
				<?php
				echo statusGeneralForm($tipo_ciudadano_seccion_avance_semaforoDatos['status']);
				?>
			</select><br><br>
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