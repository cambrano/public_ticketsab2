<?php
	$selectRegistro[$switch_operacionesDatos['registro']] = 'selected="selected"';
	$selectEvaluacion[$switch_operacionesDatos['evaluacion']] = 'selected="selected"';
	$selectEntrega[$switch_operacionesDatos['entrega']] = 'selected="selected"';
	$selectentRecibe[$switch_operacionesDatos['recibe']] = 'selected="selected"';
	$selectentCasilla[$switch_operacionesDatos['casilla']] = 'selected="selected"';
	$selectentUsuarios[$switch_operacionesDatos['usuarios']] = 'selected="selected"';
	$selectMailing[$switch_operacionesDatos['mailing']] = 'selected="selected"';
	$selectSMS[$switch_operacionesDatos['sms']] = 'selected="selected"';
	$selectWhatsapp[$switch_operacionesDatos['whatsapp']] = 'selected="selected"';
?>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Ciudadanos</label>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">
				Registro Ciudadano
			</label><br>
			<select id='registro' class='myselect' >
				<option <?= $selectRegistro[1] ?> value='1' >Abierto</option>
				<option <?= $selectRegistro[0] ?> value='0' >Cerrado</option>
			</select><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">
				Evaluación Ciudadano
			</label><br>
			<select id='evaluacion' class='myselect' >
				<option <?= $selectEvaluacion[1] ?> value='1' >Abierto</option>
				<option <?= $selectEvaluacion[0] ?> value='0' >Cerrado</option>
			</select><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">
				Campañas Mailing
			</label><br>
			<select id='mailing' class='myselect' >
				<option <?= $selectMailing[1] ?> value='1' >Activado</option>
				<option <?= $selectMailing[0] ?> value='0' >Desactivado</option>
			</select><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">
				Campañas SMS
			</label><br>
			<select id='sms' class='myselect' >
				<option <?= $selectSMS[1] ?> value='1' >Activado</option>
				<option <?= $selectSMS[0] ?> value='0' >Desactivado</option>
			</select><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">
				Campañas Whatsapp
			</label><br>
			<select id='whatsapp' class='myselect' >
				<option <?= $selectWhatsapp[1] ?> value='1' >Activado</option>
				<option <?= $selectWhatsapp[0] ?> value='0' >Desactivado</option>
			</select><br>
		</div>


		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Día D</label>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">
				Lleva Ciudadano
			</label><br>
			<select id='entrega' class='myselect' >
				<option <?= $selectEntrega[1] ?> value='1' >Abierto</option>
				<option <?= $selectEntrega[0] ?> value='0' >Cerrado</option>
			</select><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">
				Recibe Ciudadano
			</label><br>
			<select id='recibe' class='myselect' >
				<option <?= $selectentRecibe[1] ?> value='1' >Abierto</option>
				<option <?= $selectentRecibe[0] ?> value='0' >Cerrado</option>
			</select><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">
				Info. Casillas
			</label><br>
			<select id='casilla' class='myselect' >
				<option <?= $selectentCasillas[1] ?> value='1' >Abierto</option>
				<option <?= $selectentCasillas[0] ?> value='0' >Cerrado</option>
			</select><br>
		</div>

		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Seguridad</label>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">
				Usuarios ONLINE
			</label><br>
			<select id='usuarios' class='myselect' >
				<option <?= $selectentUsuarios[1] ?> value='1' >Abierto</option>
				<option <?= $selectentUsuarios[0] ?> value='0' >Cerrado</option>
			</select><br>
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