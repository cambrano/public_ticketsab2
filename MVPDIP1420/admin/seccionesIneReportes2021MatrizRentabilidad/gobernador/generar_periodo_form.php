<?php
	include __DIR__."/../../functions/timemex.php";
	if($_COOKIE['fecha_inicial']!='' && $_COOKIE['fecha_final']!=''){
		?>
		<label class="tituloForm" style="font-size: 14px">
			<font style="font-weight: initial;font-size: 12px">Periodo:</font> <?= fechaNormalSimpleDDMMAA_ES($_COOKIE['fecha_inicial']) ?> A  <?= fechaNormalSimpleDDMMAA_ES($_COOKIE['fecha_final']) ?>
		</label><br>
		<?php
	}elseif($_COOKIE['fecha_inicial']!='' && $_COOKIE['fecha_final']==''){
		?>
		<label class="tituloForm" style="font-size: 14px">
			<font style="font-weight: initial;font-size: 12px">Periodo:</font> Todos antes del <?= fechaNormalSimpleDDMMAA_ES($_COOKIE['fecha_inicial']) ?>
		</label><br>
		<?php
	}elseif($_COOKIE['fecha_inicial'] == '' && $_COOKIE['fecha_final'] != ''){
		?>
		<label class="tituloForm" style="font-size: 14px">
			<font style="font-weight: initial;font-size: 12px">Periodo:</font> Todos desde <?= fechaNormalSimpleDDMMAA_ES($_COOKIE['fecha_final']) ?>
		</label><br>
		<?php
	}else{
		?>
		<label class="tituloForm" style="font-size: 14px">
			<font style="font-weight: initial;font-size: 12px">Periodo:</font> Todos
		</label><br>
		<?php
	}
	
?>
	<div id="mensajeGrafica" class="mensajeSolo" ><br></div>
	<div class="sucForm" style="width: 100%">
		<label class="labelForm" id="labeltemaname">Seleccione periodo</label><br>
	</div>
	<div class="sucForm">
		<input id="fecha_inicial" autocomplete="off" type="text" value="<?= $_COOKIE['fecha_inicial'] ?>" placeholder="Fecha Inicial" ><br>
	</div>
	<div class="sucForm">
		<input id="fecha_final" autocomplete="off" type="text" value="<?= $_COOKIE['fecha_final'] ?>" placeholder="Fecha Final" ><br>
	</div>
	<div class="sucForm">
		<button class="btn btn-primary bt_responsive" onclick="generarPeriodo()" style="margin: 0px 2px 2px 2px;"> Generar </button>
	</div>
	<script type="text/javascript">
		$( function() {
			$( "#fecha_inicial" ).datepicker({ 
				changeMonth: true,
				changeYear: true, 
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd',
				onSelect: function (date) {
					//searchTable();
				}
			});
			$( "#fecha_final" ).datepicker({ 
				changeMonth: true,
				changeYear: true, 
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd',
				onSelect: function (date) {
					//searchTable();
				}
			}); 
		});
		function generarPeriodo(argument) {
			var espacios_invalidos= /\s+/g;
			var fecha_inicial = document.getElementById("fecha_inicial").value;
			fecha_inicial = fecha_inicial.replace(espacios_invalidos, '');
			if(fecha_inicial != ""){
				if(!fechaValida(fecha_inicial)){ 
					$("#mensajeGrafica").html("Fecha(1) Válida requerido");
					document.getElementById("mensajeGrafica").classList.add("mensajeError");
					document.getElementById("fecha_inicial").focus(); 
					return false;
				}
			}
			var fecha_final = document.getElementById("fecha_final").value;
			fecha_final = fecha_final.replace(espacios_invalidos, '');
			if(fecha_final != ""){
				if(!fechaValida(fecha_final)){ 
					$("#mensajeGrafica").html("Fecha(2) Válida requerido");
					document.getElementById("mensajeGrafica").classList.add("mensajeError");
					document.getElementById("fecha_final").focus(); 
					return false;
				}
			}

			if( (fecha_inicial > fecha_final ) && (fecha_inicial !='' && fecha_final !=''  )  ){
				$("#mensajeGrafica").html("Fecha(1) debe ser menor a Fecha(2)");
				document.getElementById("mensajeGrafica").classList.add("mensajeError");
				return false;
			}
			document.cookie = "fecha_inicial=" + fecha_inicial + "; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";
			document.cookie = "fecha_final=" + fecha_final + "; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";

			var genPeriodo = [];
			var data = {
					'fecha_inicial' : fecha_inicial,
					'fecha_final' : fecha_final,
					'tipo' : '<?= $tipo ?>',
					'ano' : '<?= $ano ?>',
				}
			genPeriodo.push(data);
			$.ajax({
				type: "POST",
				url: "seccionesIneReportes2021MatrizRentabilidad/gobernador/generar_periodo_session.php",
				data: {genPeriodo: genPeriodo/*,dataSecciones:dataSecciones*/},
				async: true,
				success: function(data) {
					$("#mensajeGrafica").html('');
					location.reload();
				}
			});

		}
	</script>