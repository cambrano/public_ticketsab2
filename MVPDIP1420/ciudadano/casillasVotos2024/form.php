	<script type="text/javascript">
		function votos_validos(){
			var votos = 0;
			<?php
			foreach ($partidos_2024Datos as $key => $value) {
				?>
				var votos_partido_<?= $value['id'] ?> = document.getElementById("votos_partido_<?= $value['id'] ?>").value; 
				if(votos_partido_<?= $value['id'] ?>!=""){
					votos = parseInt(votos_partido_<?= $value['id'] ?>) + votos;
				}else{
					document.getElementById("votos_partido_<?= $value['id'] ?>").value = 0;
				}
				<?php
			}
			?>
			var votos_validos = document.getElementById("votos_validos").value=votos.toLocaleString("ja-JP"); 
			votos_totales();
		}

		function votos_totales(){
			var votos = 0;
			var votos_nulos = document.getElementById("votos_nulos").value;
			if(votos_nulos==""){
				votos_nulos = 0;
				document.getElementById("votos_nulos").value =0;
			}
			votos = parseInt(votos_nulos) + votos;
			var votos_can_nreg = document.getElementById("votos_can_nreg").value;
			if(votos_can_nreg==""){
				votos_can_nreg = 0;
				document.getElementById("votos_can_nreg").value =0;
			}
			votos = parseInt(votos_can_nreg) + votos;
			<?php
			foreach ($partidos_2024Datos as $key => $value) {
				?>
				var votos_partido_<?= $value['id'] ?> = document.getElementById("votos_partido_<?= $value['id'] ?>").value; 
				if(votos_partido_<?= $value['id'] ?>!=""){
					votos = parseInt(votos_partido_<?= $value['id'] ?>) + votos;
				}else{
					document.getElementById("votos_partido_<?= $value['id'] ?>").value = 0;
				}
				<?php
			}
			?>
			var votos_totales = document.getElementById("votos_totales").value=votos.toLocaleString("ja-JP"); 
		}

		function guardarStatus(){
			document.getElementById("sumbmitStatus").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");

			var id_casilla_voto_2024 = document.getElementById("id_casilla_voto_2024").value;
			if(id_casilla_voto_2024==""){
				document.getElementById("sumbmitStatus").disabled = false;
				$("#mensaje").html("Casilla requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var status = document.getElementById("status").value;
			if(status==""){
				document.getElementById("sumbmitStatus").disabled = false;
				$("#mensaje").html("Estatus requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}


			var casilla_voto_status = [];
			var data = {
					'id_casilla_voto_2024' : id_casilla_voto_2024, 
					'status' : status,
				}
			casilla_voto_status.push(data);
			$.ajax({
				type: "POST",
				url: "casillasVotos2024/asignar_casilla_status.php",
				data: {casilla_voto_status: casilla_voto_status},
				async: true,
				success: function(data) {
					if(data=='SI'){
						document.getElementById("mensaje").classList.add("mensajeSucces");
						$("#mensaje").html('Estatus Guardado con exito.');
						document.getElementById("sumbmitStatus").style.cursor = "not-allowed";
						document.getElementById("sumbmitStatus").style.backgroundColor = "black";
						document.getElementById("sumbmitStatus").style.color = "yellow";
					}else{
						document.getElementById("sumbmitStatus").disabled = false;
						$("#mensaje").html("Error, hablar con soporte");
					}
					
				}
			});
		}

		function semaforoOptions(){
			var semaforo = document.getElementById("semaforo").value;
			if(semaforo>1){
				document.getElementById("observacionesDiv").style.display = "block";
			}else{
				document.getElementById("observacionesDiv").style.display = "none";
			}
		}

		function guardarIncidencia(){
			document.getElementById("sumbmitIncidencia").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");

			var id_casilla_voto_2024 = document.getElementById("id_casilla_voto_2024").value;
			if(id_casilla_voto_2024==""){
				document.getElementById("sumbmitIncidencia").disabled = false;
				$("#mensaje").html("Casilla requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var semaforo = document.getElementById("semaforo").value;
			if(semaforo==""){
				document.getElementById("sumbmitIncidencia").disabled = false;
				$("#mensaje").html("Semaforo requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			if(semaforo>1){
				var observaciones = document.getElementById("observaciones").value;
				if(observaciones==""){
					document.getElementById("sumbmitIncidencia").disabled = false;
					$("#mensaje").html("Observaciones requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
			}else{
				observaciones = null
			}


			var casilla_voto_incidencia = [];
			var data = {
					'id_casilla_voto_2024' : id_casilla_voto_2024, 
					'semaforo' : semaforo,
					'observaciones' : observaciones,
				}
			casilla_voto_incidencia.push(data);
			$.ajax({
				type: "POST",
				url: "casillasVotos2024/asignar_casilla_incidencia.php",
				data: {casilla_voto_incidencia: casilla_voto_incidencia},
				async: true,
				success: function(data) {
					if(data=='SI'){
						document.getElementById("mensaje").classList.add("mensajeSucces");
						$("#mensaje").html('Incidencia Guardado con exito.');
						document.getElementById("sumbmitIncidencia").style.cursor = "not-allowed";
						document.getElementById("sumbmitIncidencia").style.backgroundColor = "black";
						document.getElementById("sumbmitIncidencia").style.color = "yellow";
					}else{
						document.getElementById("sumbmitIncidencia").disabled = false;
						$("#mensaje").html("Error, hablar con soporte");
					}
					
				}
			});
		}
	</script>
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
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Estatus Casilla Voto 2024</label>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Estatus<font color="#FF0004">*</font></label><br>
			<?php
				$selecTipoSeccion[$casilla_voto_2024_statusDatosAll[0]['status']] = 'selected="selected"';
			?>
			<select class="myselect" id="status" >
				<option  <?= $selecTipoSeccion ?> value="">Seleccione</option>
				<option <?= $selecTipoSeccion['1'] ?> value="1">Abierto</option>
				<option <?= $selecTipoSeccion['2'] ?> value="2">Cerrado Con Votantes</option>
				<option <?= $selecTipoSeccion['3'] ?> value="3">Cerrado</option>
				<option <?= $selecTipoSeccion['4'] ?> value="3">Inicio Conteo</option>
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">&nbsp;</label><br>
			<input type="button" id="sumbmitStatus" onclick="guardarStatus()" value="Guardar Estatus">
		</div>

		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Reportar Incidencias Casilla Voto 2024</label>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Semaforo<font color="#FF0004">*</font></label><br>
			<?php
				if($casilla_voto_2024_incidenciasDatos[0]['status']==1 && $casilla_voto_2024_incidenciasDatos[0]['semaforo'] !=''){
					$selecTipoSeccionInicidencia['1'] = 'selected="selected"';
				}else{
					if($casilla_voto_2024_incidenciasDatos[0]['semaforo'] !=''){
						$selecTipoSeccionInicidencia[$casilla_voto_2024_incidenciasDatos[0]['semaforo']] = 'selected="selected"';
					}else{
						$selecTipoSeccionInicidencia['0'] = 'selected="selected"';
					}
				}
			?>
			<!--<select class="myselect" id="status" >-->
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="semaforo" onchange="semaforoOptions()" >
				<option <?= $selecTipoSeccionInicidencia['0'] ?> value="">Seleccione</option> 
				<option data-content="<span class='semaforo_green'>Verde</span>" <?= $selecTipoSeccionInicidencia['1'] ?> value="1" >Verde</option>
				<option data-content="<span class='semaforo_yellow'>Amarillo</span>" <?= $selecTipoSeccionInicidencia['2'] ?> value="2" >Amarillo</option>
				<option data-content="<span class='semaforo_red'>Rojo</span>" <?= $selecTipoSeccionInicidencia['3'] ?> value="3" >Rojo</option>
			</select><br>
		</div>
		<div class="sucForm" style="display: none;width: 100%" id="observacionesDiv">
			<label class="labelForm" id="labeltemaname">Observaciones(140 caracteres)</label><br>
			<textarea id="observaciones" style="width: 99%;height: 150px" maxlength="140"><?= $casilla_voto_2024_incidenciasDatos[0]['observaciones'] ?></textarea> <br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">&nbsp;</label><br>
			<input type="button" id="sumbmitIncidencia" onclick="guardarIncidencia()" value="Guardar Incidencia">
		</div>

		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Casilla Voto 2024</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" disabled="disabled" type="text" name="clave" autocomplete="off"  id="clave" value="<?= $casilla_voto_2024Datos['clave'] ?>" placeholder="" maxlength="120" onblur="aMays(event, this)"/><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Código<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="codigo" autocomplete="off"  id="codigo" value="<?= $casilla_voto_2024Datos['codigo'] ?>" placeholder="Código" onblur="aMays(event, this)" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Lista Nominal<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="lista_nominal" autocomplete="off"  id="lista_nominal" value="<?= $casilla_voto_2024Datos['lista_nominal'] ?>" placeholder="Lista Nominal" onkeypress="return CheckNumeric()" /><br>
		</div>
		<div class="sucForm" style="display: none">
			<label class="labelForm" id="labeltemaname">Estatus<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="status" autocomplete="off"  value="<?= $casilla_voto_2024Datos['status'] ?>" placeholder="estatus" /><br>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Votos</label>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Votos Totales<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" disabled="disabled" type="text" style="width: 100%" name="votos_totales" autocomplete="off"  id="votos_totales" value="<?= $votos_totales ?>" placeholder="Votos Totales" onkeypress="return CheckNumeric()" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Votos Válidos<font color="#FF0004">*</font></label><br>
			<input  class="inputlogin" disabled="disabled" type="text" style="width: 100%" name="votos_validos" autocomplete="off"  id="votos_validos" value="<?= $votos_validos ?>" placeholder="Votos Válidos" onkeypress="return CheckNumeric()" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Votos Nulos<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="votos_nulos" autocomplete="off"  id="votos_nulos" value="<?= $casilla_voto_2024Datos['votos_nulos'] ?>" placeholder="Votos Nulos" onkeypress="return CheckNumeric()" onchange="votos_totales()"/><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Votos CAN NREG<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="votos_can_nreg" autocomplete="off"  id="votos_can_nreg" value="<?= $casilla_voto_2024Datos['votos_can_nreg'] ?>" placeholder="Votos CAN NREG" onkeypress="return CheckNumeric()" onchange="votos_totales()"/><br>
		</div>

		<style type="text/css">
			.mobile_mode{
				width: 30%;
				background-color: #f4f4f2;
				height:80px;
			}
			@media screen and (max-width: 930px) {
				.mobile_mode{
					width: 49%;
					background-color: #f4f4f2
				}
			}
			@media screen and (max-width: 820px) {
				.mobile_mode{
					width: 100%;
					background-color: #f4f4f2
				}
			}
		</style>

		<div class="sucForm" style="width: 100%;">
			<?php
			foreach ($partidos_2024Datos as $key => $value) {
				$nombre_corto = strtr($value['nombre_corto'], "_", " ");

				?>
				<div class="sucForm mobile_mode">
					<div class="sucForm" style="width:25%">
						
						<img style="width:80% " src="images/logos_partidos/<?= $value['logo'] ?>">
					</div>

					<div class="sucForm" style="width: 60%">
						<label class="labelForm" id="labeltemaname"><?= $nombre_corto ?><font color="#FF0004">*</font></label><br>
						<input class="inputlogin" type="text" style="width: 100%" name="votos_partido_<?= $value['id'] ?>" autocomplete="off"  id="votos_partido_<?= $value['id'] ?>" value="<?= $value['votos'] ?>" placeholder="Número Votos" onkeypress="return CheckNumeric()" onchange="votos_validos()"/><br>
					</div>
				</div>
				<?php
			}
			?>
		</div>


		<div class="sucForm" style="width: 100%" >
			<input style="width: 100%;margin-bottom: 10px" type="button" id="sumbmit" onclick="guardar()" value="Guardar">
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
	</script>
	</div>