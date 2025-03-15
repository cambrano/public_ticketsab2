<?php
	$verde_rango_inicial = $secciones_ine_ciudadanos_avance_semaforoDatos['verde_rango_inicial'];
	$verde_rango_final = $secciones_ine_ciudadanos_avance_semaforoDatos['verde_rango_final'];
	$verde_rango_inicial_array = explode(".", $verde_rango_inicial);
	$select_verde_inicial_unidad[$verde_rango_inicial_array[0]] = 'selected="selected"';
	$select_verde_inicial_decimal[$verde_rango_inicial_array[1]] = 'selected="selected"';
	$verde_rango_final_array = explode(".", $verde_rango_final);
	$select_verde_final_unidad[$verde_rango_final_array[0]] = 'selected="selected"';
	$select_verde_final_decimal[$verde_rango_final_array[1]] = 'selected="selected"';

	$amarillo_rango_inicial = $secciones_ine_ciudadanos_avance_semaforoDatos['amarillo_rango_inicial'];
	$amarillo_rango_final = $secciones_ine_ciudadanos_avance_semaforoDatos['amarillo_rango_final'];
	$amarillo_rango_inicial_array = explode(".", $amarillo_rango_inicial);
	$select_amarillo_inicial_unidad[$amarillo_rango_inicial_array[0]] = 'selected="selected"';
	$select_amarillo_inicial_decimal[$amarillo_rango_inicial_array[1]] = 'selected="selected"';
	$amarillo_rango_final_array = explode(".", $amarillo_rango_final);
	$select_amarillo_final_unidad[$amarillo_rango_final_array[0]] = 'selected="selected"';
	$select_amarillo_final_decimal[$amarillo_rango_final_array[1]] = 'selected="selected"';

	$rojo_rango_inicial = $secciones_ine_ciudadanos_avance_semaforoDatos['rojo_rango_inicial'];
	$rojo_rango_final = $secciones_ine_ciudadanos_avance_semaforoDatos['rojo_rango_final'];
	$rojo_rango_inicial_array = explode(".", $rojo_rango_inicial);
	$select_rojo_inicial_unidad[$rojo_rango_inicial_array[0]] = 'selected="selected"';
	$select_rojo_inicial_decimal[$rojo_rango_inicial_array[1]] = 'selected="selected"';
	$rojo_rango_final_array = explode(".", $rojo_rango_final);
	$select_rojo_final_unidad[$rojo_rango_final_array[0]] = 'selected="selected"';
	$select_rojo_final_decimal[$rojo_rango_final_array[1]] = 'selected="selected"';



?>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo" style="background-color:red">
			<label class="labelForm" id="labeltemaname" style="color:white" >Rojo</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">
				Rango Inicial Unidad
			</label><br>
			<select id='rojo_rango_inicial_unidad' class='myselect'  >
				<?php
					for ($i=0; $i <= 100; $i++) { 
						?>
						<option <?= $select_rojo_inicial_unidad[$i] ?> value='<?= $i ?>' ><?= $i ?></option>
						<?php
					}
				?>
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">
				Rango Inicial Decimal
			</label><br>
			<select id='rojo_rango_inicial_decimal' class='myselect'  >
				<?php
					for ($i=0; $i <= 99; $i++) { 
						$numero = $i;
						$numero = str_pad($numero, 2, '0', STR_PAD_LEFT)
						?>
						<option <?= $select_rojo_inicial_decimal[$numero] ?> value='<?= str_pad($numero, 2, '0', STR_PAD_LEFT) ?>' ><?= str_pad($numero, 2, '0', STR_PAD_LEFT); ?></option>
						<?php
					}
				?>
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">
				Rango Final Unidad
			</label><br>
			<select id='rojo_rango_final_unidad' class='myselect' >
				<?php
					for ($i=0; $i <= 100; $i++) { 
						?>
						<option <?= $select_rojo_final_unidad[$i] ?> value='<?= $i ?>' ><?= $i ?></option>
						<?php
					}
				?>
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">
				Rango Final Decimal
			</label><br>
			<select id='rojo_rango_final_decimal' class='myselect' >
				<?php
					for ($i=0; $i <= 99; $i++) { 
						$numero = $i;
						$numero = str_pad($numero, 2, '0', STR_PAD_LEFT)
						?>
						<option <?= $select_rojo_final_decimal[$numero] ?> value='<?= str_pad($numero, 2, '0', STR_PAD_LEFT) ?>' ><?= str_pad($numero, 2, '0', STR_PAD_LEFT); ?></option>
						<?php
					}
				?>
			</select><br>
		</div>
		<div class="sucFormTitulo" style="background-color:yellow">
			<label class="labelForm" id="labeltemaname" style="color:black" >Amarillo</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">
				Rango Inicial Unidad
			</label><br>
			<select id='amarillo_rango_inicial_unidad' class='myselect' >
				<?php
					for ($i=0; $i <= 100; $i++) { 
						?>
						<option <?= $select_amarillo_inicial_unidad[$i] ?> value='<?= $i ?>' ><?= $i ?></option>
						<?php
					}
				?>
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">
				Rango Inicial Decimal
			</label><br>
			<select id='amarillo_rango_inicial_decimal' class='myselect' >
				<?php
					for ($i=0; $i <= 99; $i++) { 
						$numero = $i;
						$numero = str_pad($numero, 2, '0', STR_PAD_LEFT)
						?>
						<option <?= $select_amarillo_inicial_decimal[$numero] ?> value='<?= str_pad($numero, 2, '0', STR_PAD_LEFT) ?>' ><?= str_pad($numero, 2, '0', STR_PAD_LEFT); ?></option>
						<?php
					}
				?>
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">
				Rango Final Unidad
			</label><br>
			<select id='amarillo_rango_final_unidad' class='myselect' >
				<?php
					for ($i=0; $i <= 100; $i++) { 
						?>
						<option <?= $select_amarillo_final_unidad[$i] ?> value='<?= $i ?>' ><?= $i ?></option>
						<?php
					}
				?>
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">
				Rango Final Decimal
			</label><br>
			<select id='amarillo_rango_final_decimal' class='myselect' >
				<?php
					for ($i=0; $i <= 99; $i++) { 
						$numero = $i;
						$numero = str_pad($numero, 2, '0', STR_PAD_LEFT)
						?>
						<option <?= $select_amarillo_final_decimal[$numero] ?> value='<?= str_pad($numero, 2, '0', STR_PAD_LEFT) ?>' ><?= str_pad($numero, 2, '0', STR_PAD_LEFT); ?></option>
						<?php
					}
				?>
			</select><br>
		</div>
		<div class="sucFormTitulo" style="background-color:green">
			<label class="labelForm" id="labeltemaname" style="color:white" >Verde</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">
				Rango Inicial Unidad
			</label><br>
			<select id='verde_rango_inicial_unidad' class='myselect' >
				<?php
					for ($i=0; $i <= 100; $i++) { 
						?>
						<option <?= $select_verde_inicial_unidad[$i] ?> value='<?= $i ?>' ><?= $i ?></option>
						<?php
					}
				?>
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">
				Rango Inicial Decimal
			</label><br>
			<select id='verde_rango_inicial_decimal' class='myselect' >
				<?php
					for ($i=0; $i <= 99; $i++) { 
						$numero = $i;
						$numero = str_pad($numero, 2, '0', STR_PAD_LEFT)
						?>
						<option <?= $select_verde_inicial_decimal[$numero] ?> value='<?= str_pad($numero, 2, '0', STR_PAD_LEFT) ?>' ><?= str_pad($numero, 2, '0', STR_PAD_LEFT); ?></option>
						<?php
					}
				?>
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">
				Rango Final Unidad
			</label><br>
			<select id='verde_rango_final_unidad' class='myselect'  >
				<?php
					for ($i=0; $i <= 100; $i++) { 
						?>
						<option <?= $select_verde_final_unidad[$i] ?> value='<?= $i ?>' ><?= $i ?></option>
						<?php
					}
				?>
			</select><br>
		</div>
		
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">
				Rango Final Decimal
			</label><br>
			<select id='verde_rango_final_decimal' class='myselect'  >
				<?php
					for ($i=0; $i <= 99; $i++) { 
						$numero = $i;
						$numero = str_pad($numero, 2, '0', STR_PAD_LEFT)
						?>
						<option <?= $select_verde_final_decimal[$numero] ?> value='<?= str_pad($numero, 2, '0', STR_PAD_LEFT) ?>' ><?= str_pad($numero, 2, '0', STR_PAD_LEFT); ?></option>
						<?php
					}
				?>
			</select><br>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Estatus<font color="#FF0004">*</font></label><br>
			<select id="status" class="myselect" name="status" >
				<?php echo statusGeneralForm($secciones_ine_ciudadanos_avance_semaforoDatos['status']); ?>
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