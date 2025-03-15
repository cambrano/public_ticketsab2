<?php
	 
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','casillas_votos_2024',$_COOKIE["id_usuario"]);
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
			<label class="labelForm" id="labeltemaname">Datos Estatus Casilla</label>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">status<font color="#FF0004">*</font></label><br>
			<?php
				$selecTipoSeccion[$casilla_voto_2024_statusDatos['status']] = 'selected="selected"';
			?>
			<select class="myselect" id="status" >
				<option  <?= $selecTipoSeccion ?> value="">Seleccione</option>
				<option <?= $selecTipoSeccion['1'] ?> value="1">Abierto</option>
				<option <?= $selecTipoSeccion['2'] ?> value="2">Cerrado Con Votantes</option>
				<option <?= $selecTipoSeccion['3'] ?> value="3">Cerrado</option>
				<option <?= $selecTipoSeccion['4'] ?> value="4">Inicio Conteo</option>
			</select><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Fecha<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="fecha" autocomplete="off"  id="fecha" value="<?= $casilla_voto_2024_statusDatos['fecha'] ?>" placeholder="" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Hora<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="hora" autocomplete="off"  id="hora" value="<?= $casilla_voto_2024_statusDatos['hora'] ?>" placeholder="" /><br>
		</div>


		<div class="sucForm" style="width: 100%" >
			<br>
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
		<?php
			if ($id==""){
				?>
				localize();
				<?php
			}
		?>
	</script>