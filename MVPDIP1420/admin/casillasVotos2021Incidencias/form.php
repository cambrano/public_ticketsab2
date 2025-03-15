<?php
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','casillas_votos_2021',$_COOKIE["id_usuario"]);
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
	<style type="text/css">
		.ui-autocomplete {
			max-height: 180px;
			margin-bottom: 10px;
			overflow-x: hidden;
			overflow-y: auto;
		}
		.data_interior{
			width: 50%;
			float: left;
			padding-left: 10px;
			padding-right: 10px;
			color: #191919;
		}
		.data_interior_left{
			width: 50%;
			float: left;
			padding-left: 10px;
			padding-right: 10px;
			color: #191919;
			border-right: 1px solid #191919;
		}
		@media only screen and (max-width:1600px) {
			.data_interior{
				width: 100%;
			}
			.data_interior_left{
				border-right: none;
			}
		}
	</style>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Incidencia Casilla</label>
		</div>
		<style type="text/css">
			.semaforo_red{
				display: inline-block;
			    min-width: 10px;
			    padding: 3px 7px;
			    font-size: 12px;
			    font-weight: 700;
			    line-height: 1;
			    color: #fff;
			    text-align: center;
			    white-space: nowrap;
			    vertical-align: middle;
			    background-color: red;
			    border-radius: 10px;
			}
			.semaforo_yellow{
				display: inline-block;
			    min-width: 10px;
			    padding: 3px 7px;
			    font-size: 12px;
			    font-weight: 700;
			    line-height: 1;
			    color: #191919;
			    text-align: center;
			    white-space: nowrap;
			    vertical-align: middle;
			    background-color: yellow;
			    border-radius: 10px;
			}
			.semaforo_green{
				display: inline-block;
			    min-width: 10px;
			    padding: 3px 7px;
			    font-size: 12px;
			    font-weight: 700;
			    line-height: 1;
			    color: #fff;
			    text-align: center;
			    white-space: nowrap;
			    vertical-align: middle;
			    background-color: green;
			    border-radius: 10px;
			}
		</style>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Semáforo<font color="#FF0004">*</font></label><br>
			<?php
				$selecTipoSeccion[$casilla_voto_2021_incidenciaDatos['semaforo']] = 'selected="selected"';
			?>
			<!--<select class="myselect" id="status" >-->
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="semaforo"  <?= $disbale_id_pricipal ?> >
				<option <?= $selecTipoSeccion ?> value="">Seleccione</option> 
				<option data-content="<span class='semaforo_green'>Verde</span>" <?= $selecTipoSeccion['1'] ?> value="1" >Verde</option>
				<option data-content="<span class='semaforo_yellow'>Amarillo</span>" <?= $selecTipoSeccion['2'] ?> value="2" >Amarillo</option>
				<option data-content="<span class='semaforo_red'>Rojo</span>" <?= $selecTipoSeccion['3'] ?> value="3" >Rojo</option>
			</select><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Fecha<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="fecha" autocomplete="off"  id="fecha" value="<?= $casilla_voto_2021_incidenciaDatos['fecha'] ?>" placeholder="" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Hora<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="hora" autocomplete="off"  id="hora" value="<?= $casilla_voto_2021_incidenciaDatos['hora'] ?>" placeholder="" /><br>
		</div>

		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Observaciones</label>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Observaciones(140 caracteres)</label><br>
			<textarea id="observaciones" style="width: 99%;height: 150px" maxlength="140"><?= $casilla_voto_2021_incidenciaDatos['observaciones'] ?></textarea> <br>
		</div>
		<?php
			$casilla_voto_2021_incidenciaDatos['status'];
		?>
		<label class="labelForm" id="labeltemaname">Estatus<font color="#FF0004">*</font></label><br>
			<select id="status" class="myselect" name="status" >
				<?= statusAtendido($casilla_voto_2021_incidenciaDatos['status']); ?>
			</select><br><br>


		<div class="sucForm" style="width: 100%" >
			<?php

			if($moduloAccionPermisos[$permiso] || $moduloAccionPermisos['all']){
				?>
				<input type="button" id="sumbmit" onclick="guardar()" value="Guardar">
				<?php
			}
			?>
				<!--<input type="button" onclick="ResetInput()" value="Borrar">-->
				<input type="button" value="Cancelar" onclick="cerrar()">
		</div>
	</div> 
	<script type="text/javascript">
		$(".myselect").select2();
		$('select').selectpicker({
			deselectAllText: '<span class="glyphicon glyphicon-remove-sign"></span>', 
			selectAllText: '<span class="glyphicon glyphicon-ok-sign"></span>',
			liveSearchNormalize : true,
			multipleSeparator: ' | ',
			noneResultsText: 'No Encontrado {0}',
		});
		<?php
			if ($id==""){
				?>
				localize();
				<?php
			}
		?>
	</script>