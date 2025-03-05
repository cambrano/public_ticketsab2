<?php
	include '../functions/switch_operaciones.php';
	$switch_operacionesPermisos = switch_operacionesPermisos();
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
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Encuesta</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input maxlength="45" max="45" class="inputlogin" type="text" name="clave" autocomplete="off" <?= $claveF['input'] ?> id="clave" value="<?= $seccion_ine_ciudadano_encuestaDatos['clave'] ?>" onkeyup="clave(this.value)"  /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Fecha<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="fecha" autocomplete="off"  id="fecha" value="<?= $seccion_ine_ciudadano_encuestaDatos['fecha'] ?>" placeholder="" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Hora<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="hora" autocomplete="off"  id="hora" value="<?= $seccion_ine_ciudadano_encuestaDatos['hora'] ?>" placeholder="" /><br>
		</div>
		<div class="sucFormTitulo" >
			<label class="labelForm" id="labeltemaname">Preguntas</label>
		</div>

		<?php
		foreach ($cuestionariosDatos as $key => $value) {
			echo '<div class="sucForm" style="width: 100%;background-color:#f1f1ee;padding: 2px 10px 2px 10px;"><label class="labelForm" id="labeltemaname" style="font-size:12px;text-transform:none;letter-spacing:2px">'.$value['orden'].'.- '.$value['pregunta'].'-'.$value['cantidad'].'-'.$value['tipo'];
			echo $value['requerido'] ? '<font color="#FF0004">*</font>' : '';
			echo '</label></div>';
			if($value['campo']=="text"){
				echo '<div id="pre_'.$value['id'].'" class="sucForm" style="padding:5px 20px 5px 20px;width:100%">
							<input class="inputlogin" type="text" name="input_text_'.$value['id'].'" autocomplete="off"  id="input_text_'.$value['id'].'" value="'.$value['respuesta'].'" placeholder="" /><br> 
						</div>';
			}else{
				foreach ($cuestionario_respuestasIdDatos[$value['id']] as $keyT => $valueT) {
					echo '<div id="pre_'.$value['id'].'" class="sucForm" style="padding:5px 20px 5px 20px ">
							<input class="inputlogin" type="'.$value['campo'].'" name="input_'.$value['id'].'[]" autocomplete="off"  id="input_'.$valueT['id'].'" value="" placeholder="" '.$valueT['checked'].' />
							<label class="labelForm" for="input_'.$valueT['id'].'" style="letter-spacing:2px;text-transform:none;" >'.$valueT['respuesta'].'</label>
							
						</div>';
				}
			}

		}
		?>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Observaciones</label><br>
			<textarea id="observaciones" style="width: 99%;height: 150px"><?= $seccion_ine_ciudadano_encuestaDatos['observaciones'] ?></textarea> <br>
		</div>

		<div class="sucForm" style="width: 100%" >
			<br>
			<?php
			if($switch_operacionesPermisos['evaluacion'] && $encuestaDatos['status']==1 ){
				?>
				<input type="button" id="sumbmit" onclick="guardar()" value="Guardar">
				<?php
			}
			?>
				<!--<input type="button" onclick="ResetInput()" value="Borrar">-->
				<input type="button" value="Cancelar" onclick="cerrar()">
		</div>
	</div> 