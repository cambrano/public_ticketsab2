<?php
	include '../functions/usuario_permisos.php';
?>
	<style type="text/css">
		.checkboxNotific{
			text-align: center; 
			width: 100%;
		}
		@media only screen and (max-width:992px) {
			.checkboxNotific{
				text-align: left;
			}
		}
	</style>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Fecha Nacimiento</label><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Activo<font color="#FF0004">*</font></label><br>
			<div class="checkboxNotific" >
				<input type="checkbox" id="fecha_nacimiento_status" <?= $fecha_nacimiento_statusCK ?>  value="1"><br>
			</div>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Intervalo<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="fecha_nacimiento_intervalo" name="fecha_nacimiento_intervalo" >
				<option value="">Seleccione</option>
				<option <?= $sf_fecha_nacimiento_intervalo['antes'] ?> value="antes">Antes</option>
				<option <?= $sf_fecha_nacimiento_intervalo['despues'] ?> value="despues">Despues</option>
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Días<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="fecha_nacimiento_dias" autocomplete="off"  id="fecha_nacimiento_dias" value="<?= $fecha_nacimiento_dias ?>" placeholder="" maxlength="5" onkeypress="return CheckNumeric()"/><br>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Pago Cliente Fecha de Reserva</label><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Activo<font color="#FF0004">*</font></label><br>
			<div class="checkboxNotific" >
				<input type="checkbox"  id="cliente_retorno_status" <?= $cliente_retorno_statusCK ?> value="1"><br>
			</div>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Intervalo<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="pago_reserva_intervalo" name="pago_reserva_intervalo" >
				<option value="">Seleccione</option>
				<option <?= $sf_pago_reserva_intervalo['antes'] ?> value="antes">Antes</option>
				<!--<option <?= $sf_spago_reserva_intervalo['despues'] ?> value="despues">Despues</option>-->
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Días<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="pago_reserva_dias" autocomplete="off"  id="pago_reserva_dias" value="<?= $pago_reserva_dias ?>" placeholder="" placeholder="" maxlength="5" onkeypress="return CheckNumeric()"/><br>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Cliente Retorno Reserva</label><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Activo<font color="#FF0004">*</font></label><br>
			<div class="checkboxNotific" >
				<input type="checkbox"  id="pago_reserva_status" <?= $pago_reserva_statusCK ?> value="1"><br>
			</div>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Intervalo<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="cliente_retorno_intervalo" name="cliente_retorno_intervalo" >
				<option value="">Seleccione</option>
				<option <?= $sf_cliente_retorno_intervalo['antes'] ?> value="antes">Antes</option>
				<option <?= $sf_cliente_retorno_intervalo['despues'] ?> value="despues">Despues</option>
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Días<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="cliente_retorno_dias" autocomplete="off"  id="cliente_retorno_dias" value="<?= $cliente_retorno_dias ?>" placeholder="" maxlength="5" onkeypress="return CheckNumeric()"/><br>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Cliente Check IN  Reserva</label><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Activo<font color="#FF0004">*</font></label><br>
			<div style="text-align: center; width: 100%"><input type="checkbox"  id="cliente_llegada_status" <?= $cliente_llegada_statusCK ?> value="1"></div>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Intervalo<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="cliente_llegada_intervalo" name="cliente_llegada_intervalo" >
				<option value="">Seleccione</option>
				<option <?= $sf_cliente_llegada_intervalo['antes'] ?> value="antes">Antes</option>
				<option <?= $sf_cliente_llegada_intervalo['despues'] ?> value="despues">Despues</option>
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Días<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="cliente_llegada_dias" autocomplete="off"  id="cliente_llegada_dias" value="<?= $cliente_llegada_dias ?>" placeholder="" maxlength="5" onkeypress="return CheckNumeric()"/><br>
		</div>
		<div class="sucForm" style="width: 100%">
			<br>
			<?php
			if(
				moduloAccion('configuracion','notificaciones_sistema',$_COOKIE["id_usuario"],$permiso) ||
				moduloAccion('configuracion','notificaciones_sistema',$_COOKIE["id_usuario"],'All') ){
				?>
				<br>
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