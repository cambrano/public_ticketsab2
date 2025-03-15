	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Casilla Status</label>
		</div>
		<script type="text/javascript">
			$( function() {
				$( "#fecha" ).datepicker({ 
					changeMonth: true,
					changeYear: true,
					showButtonPanel: true, 
					dateFormat: 'yy-mm-dd', 
					onSelect: function (date) { 
						document.getElementById("fecha").style.border= "";
					}
				});
				$('#hora').timepicker({ 
					timeFormat: 'H:i:s',
					showDuration: true,
					interval: 15,
					scrollDefault: "now",
					onSelect: function (date) { 
						document.getElementById("hora").style.border= "";
					}
				}); 
				 
			});
		</script>

		<div class="sucForm" ></div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Estatus<font color="#FF0004">*</font></label><br>
			<select id="status" class="myselect" name="status" >
				<option>Seleccione</option>
				<option value="1">Abierto</option>
				<option value="2">Cerrado Con Gente</option>
				<option value="3">Cerrado</option>
				<option value="4">Inicio Conteo</option>
			</select><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Fecha<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="fecha" autocomplete="off"  id="fecha" value="<?= $seccion_ine_ciudadano_seguimientoDatos['fecha'] ?>" placeholder="" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Hora<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="hora" autocomplete="off"  id="hora" value="<?= $seccion_ine_ciudadano_seguimientoDatos['hora'] ?>" placeholder="" /><br>
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