<?php
	include __DIR__.'/../../functions/security.php'; 
	@session_start();
	//var_dump($_POST);
?> 
	<script type="text/javascript">
		$(document).ready(function() {
			var dataTable = $('#secciones_reportes-tabla').DataTable( {
				"destroy": false,
				"responsive": false,
				"pageLength": 11,
				"retrieve": false,
				"info": true,
				"processing": true,
				
				"sPaginationType": "full_numbers", 
				/*"fixedHeader": true,*/
				"fixedHeader": {
					header: false,
					footer: false
				},
				"order": [[ 8, "asc" ]],
				"ordering": true,
				"searching": true,/* para el buscador*/
				"paging": true,/* para la paginacion y todo salga en una sola hora*/
				"aoColumnDefs": [
									//{ "bSortable": false, "aTargets": [ 0,1,2,3,8,9,10,11,12,17,18,20 ] },
									{ "targets": [ 0,1,2,3,4,5 ],"visible": false}
								],
				
				"scrollY": "100%", 
				"scrollX": "100%",

				"language": {
					"sProcessing":     "Procesando...",
					//"sLengthMenu":     "Mostrar _MENU_ registros",
					"sLengthMenu": ' ',
					/*"lengthMenu": 'Mostrar <select id="total_registros">'+
					'<option value="11">11</option>'+
					'<option value="2000000">Todos</option>'+
					'</select> registros',*/
					"sSearch":         "Buscar:",
					"sZeroRecords":    "Registro no encontrados",
					"sEmptyTable":     "No Existe Registros",
					"sInfo":           "Mostrar  (_START_ a _END_) de _TOTAL_ Registros",//
					"sInfoEmpty":      "Mostrando Registros del 0 al 0 de Total de 0 Registros",//
					"sInfoFiltered":   "(Filtrado de _MAX_ Total Registros)",//
					//"sInfoPostFix":    "",
					//"sUrl":            "",
					//"sInfoThousands":  ",",
					"sLoadingRecords": "Cargando...",
					"oPaginate": {
						"sFirst":    "<<",
						"sLast":     ">>",
						"sNext":     ">",
						"sPrevious": "<"
					},
					"oAria": {
						"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
						"sSortDescending": ": Activar para ordenar la columna de manera descendente"
					},
				},
			});
		});
		function verMasSeccion(valor){
			link="casillasVotosReportes2024/distrito_federal/index.php?id="+valor; 
			var link2="casillasVotosReportes2024/distrito_federal/index.php";
			dataString = 'urlink='+link2;  
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {}
				});
			//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
			$("#homebody").load(link+"&refresh=1");
		}
		function verMasDistritoFederal(valor){
			link="seccionesIneReportes2024MatrizRentabilidad/distrito_federal/index.php?id="+valor; 
			var link2="seccionesIneReportes2024MatrizRentabilidad/distrito_federal/index.php";
			dataString = 'urlink='+link2;  
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {}
				});
			//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
			$("#homebody").load(link+"&refresh=1");
		}
		function verMasDistritoFederalMatrizRentabilidad2024(valor){
			link="seccionesIneReportes2024MatrizRentabilidad/distrito_federal/index.php?id=<?= $id_distrito_federal ?>"; 
			var link2="seccionesIneReportes2024MatrizRentabilidad/distrito_federal/index.php";
			dataString = 'urlink='+link2;  
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {}
				});
			//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
			$("#homebody").load(link);
		}
		function downloadExcel(){
			<?php
			if($pageService != $_COOKIE["pageService"]){
				if($pageService!=''){
					$_COOKIE["pageService"] = $pageService ;
				}
			}
			?>
			var link="seccionesIneReportes2024MatrizRentabilidad/distrito_federal/excel/index.php?cot=<?=$_COOKIE['pageService']?>";
			//window.open(link);
			//window.open(link,'newWindow','width=1280, height=460'); return false;
			//document.location = link;
			window.open(link); 
			return false;
		}
		function printPdf() {
			<?php
			if($pageService != $_COOKIE["pageService"]){
				if($pageService!=''){
					$_COOKIE["pageService"] = $pageService ;
				}
			}
			?>
			var link="seccionesIneReportes2024MatrizRentabilidad/distrito_federal/print/index.php?cot=<?=$_COOKIE['pageService']?>";
			//window.open(link);
			//window.open(link,'pdf','width=1280, height=460'); return false;
			//document.location = link;
			window.open(link); 
			return false;
		}
	</script>



	<style type="text/css">
		div.dataTables_wrapper div.dataTables_filter {
			display: none;
			text-align: right;
		}
		.div_info_seccion{
			text-transform: none;
			width:50%;
			float:left;
		}
		@media screen and (max-width: 1010px) {
			.div_info_seccion{
				width:100%;
			}
		}
	</style>
	<?php
	unset($columnas_titulos);
	//Filtros
	$columnas_titulos[]= array(
		"row" => "id",
        "nombre" => "id",
	); 
	$columnas_titulos[]= array(
		"row" => "partido_primera_fuerza",
        "nombre" => "partido_primera_fuerza",
	); 
	$columnas_titulos[]= array(
		"row" => "seccion_ine_semaforo_individual_color",
        "nombre" => "seccion_ine_semaforo_individual_color",
	); 
	$columnas_titulos[]= array(
		"row" => "coalicion_primera_fuerza",
        "nombre" => "coalicion_primera_fuerza",
	); 
	$columnas_titulos[]= array(
		"row" => "seccion_ine_semaforo_coalicion_color",
        "nombre" => "seccion_ine_semaforo_coalicion_color",
	); 
	$columnas_titulos[]= array(
		"row" => "id_municipio",
        "nombre" => "id_municipio",
	);
	$columnas_titulos[]= array(
		"row" => "municipio",
        "nombre" => "Municipio",
	);
	$columnas_titulos[]= array(
		"row" => "id_distrito_local",
        "nombre" => "D.<br>Local",
	);
	$columnas_titulos[]= array(
		"row" => "id_distrito_federal",
        "nombre" => "D.<br>Federal",
	);
	$columnas_titulos[]= array(
		"row" => "numero",
		"nombre" => "Sección",
	);
	$columnas_titulos[]= array(
		"row" => "seccion_tipo",
		"nombre" => "Tipo",
	);
	$columnas_titulos[]= array(
		"row" => "votos_totales_porcentaje",
		"nombre" => "Peso<br>Electoral",
	);
	$columnas_titulos[]= array(
		"row" => "lista_nominal_porcentaje",
		"nombre" => "Porcentaje<br>LN",
	);
	$columnas_titulos[]= array(
		"row" => "participacion_ciudadana",
		"nombre" => "Participación Ciudadana",
	);
	
	$columnas_titulos[]= array(
		"row" => "prioridad",
		"nombre" => "Prioridad", 
	);
	$columnas_titulos[]= array(
		"row" => "seccion_ine_semaforo_individual",
        "nombre" => "Semáforos<br>Individual",
	);
	$columnas_titulos[]= array(
		"row" => "seccion_ine_semaforo_individual_dif",
        "nombre" => "Semáforos<br>Individual Diff",
	);
	$columnas_titulos[]= array(
		"row" => "seccion_ine_semaforo_coalicion",
        "nombre" => "Semáforos<br>Coalición",
	);
	$columnas_titulos[]= array(
		"row" => "seccion_ine_semaforo_coalicion_dif",
        "nombre" => "Semáforos<br>Coalición Diff",
	);
	$columnas_titulos[]= array(
		"row" => "seccion_localidades",
        "nombre" => "Localidad(es)",
	);
	$columnas_titulos[]= array(
		"row" => "seccion_colonias",
        "nombre" => "Colonia(es)",
	);
	$columnas_titulos[]= array(
		"row" => "primera_fuerza_ind_coa",
        "nombre" => "Primera Fuerza",
	); 
	$columnas_titulos[]= array(
		"row" => "segunda_fuerza_ind_coa",
        "nombre" => "Segunda Fuerza",
	); 
	$columnas_titulos[]= array(
		"row" => "informacion_votaciones",
        "nombre" => "Información Votación",
	); 
	$columnas_titulos[]= array(
		"row" => "informacion_territorio",
        "nombre" => "Información Territorio",
	); 
	/*
	foreach ($columnas_titulos as $key => $value) {
		echo $key;
		echo "---";
		echo $value['row'];
		echo "<br>";
	}
	*/
	?>
	<table id="secciones_reportes-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
		<thead>
			<?php
				foreach ($columnas_titulos as $key => $value) {
					echo "<th>".$value['nombre']."</th>";
				}
			?>
		</thead>
		<tbody>
			<?php
				foreach ($seccion_ine_datos as $key => $value) {
					echo "<tr>";
					foreach ($columnas_titulos as $keyP => $valueP) {
						if($valueP['row'] == "participacion_ciudadana"){
							echo "<td>".$value['participacion_ciudadana']."%</td>";
						}elseif($valueP['row'] == "seccion_ine_semaforo_individual_color"){
							echo "<td>".$value['seccion_ine_semaforo_individual']."</td>";
						}elseif($valueP['row'] == "seccion_ine_semaforo_coalicion_color"){
							echo "<td>".$value['seccion_ine_semaforo_coalicion']."</td>";
						}elseif($valueP['row'] == "seccion_ine_semaforo_individual"){
							if($value[$valueP['row']]=="VERDE"){
								$color = 'rgba(119, 221, 119, 0.9)';
							}elseif ($value[$valueP['row']]=="AMARILLO") {
								$color = 'rgba(253, 253, 150, 0.9)';
							}elseif ($value[$valueP['row']]=="ROJO") {
								$color = 'rgba(255, 105, 97, 0.9)';
							}
							elseif ($value[$valueP['row']]=="GRIS") {
								$color = 'rgba(141, 141, 141, 0.9)';
							}else{
								$color = 'rgba(0, 0, 0, 0.9)';
							}
							echo "<td><div style='background-color:".$color.";padding:12px;width:90px;text-align:center'>";
							echo $value['principal_individual_votos_totales_porcentaje']."%";
							//echo "<br>";
							//echo "".$value['partido_primera_dif_principal']." Votos Dif";
							//echo "<br>";
							echo "</div></td>";
						}elseif($valueP['row'] == "votos_totales_porcentaje"){
							echo "<td>";
							echo $value['votos_totales_porcentaje']." %";
							echo "</td>";
						}elseif($valueP['row'] == "seccion_ine_semaforo_individual_dif"){
							echo "<td>";
							echo $value['partido_primera_dif_principal']." Votos";
							echo "</td>";
						}elseif ($valueP['row'] == "seccion_ine_semaforo_coalicion") {
							if($value[$valueP['row']]=="VERDE"){
								$color = 'rgba(119, 221, 119, 0.9)';
							}elseif ($value[$valueP['row']]=="AMARILLO") {
								$color = 'rgba(253, 253, 150, 0.9)';
							}elseif ($value[$valueP['row']]=="ROJO") {
								$color = 'rgba(255, 105, 97, 0.9)';
							}
							elseif ($value[$valueP['row']]=="GRIS") {
								$color = 'rgba(141, 141, 141, 0.9)';
							}else{
								$color = 'rgba(0, 0, 0, 0.9)';
							}
							echo "<td><div style='background-color:".$color.";padding:12px;width:90px;text-align:center'>";
							echo $value['principal_coalicion_votos_totales_porcentaje']."%";
							//echo "<br>";
							//echo "".$value['coalicion_primera_dif_principal']." Votos Dif";
							//echo "<br>";
							echo "</div></td>";
						}elseif($valueP['row'] == "seccion_ine_semaforo_coalicion_dif"){
							echo "<td>";
							echo $value['coalicion_primera_dif_principal']." Votos";
							echo "</td>";
						}elseif ($valueP['row'] == "seccion_localidades") {
							$porciones = explode("*_*", $value['seccion_localidades']);
							echo "<td>";
							foreach ($porciones as $keyHid => $localidad) {
								?>
								<div style="border-bottom: 1px solid black;  ">
									*<?= htmlspecialchars($localidad, ENT_QUOTES, 'UTF-8') ?>
								</div>
								<?php
							}
							echo "</td>";
						}elseif ($valueP['row'] == "seccion_colonias") {
							$porciones = explode("*_*", $value['seccion_colonias']);
							echo "<td>";
							foreach ($porciones as $keyHid => $colonia) {
								?>
								<div style="border-bottom: 1px solid black;  ">
									*<?= htmlspecialchars($colonia, ENT_QUOTES, 'UTF-8') ?>
								</div>
								<?php
							}
							echo "</td>";;
						}elseif ($valueP['row'] == "primera_fuerza_ind_coa") {
							echo "<td>";
							?>
							<div style='background-color:rgba(232, 236, 241,0.6);padding:12px;text-align:center; width:300px'>
								Partido <br>
								<img src="images/logos_partidos/<?= $value['partido_primera_fuerza_logo'] ?>" style="width: 40px ">
								<br>
								<b><?= $value['partido_primera_fuerza'] ?></b>
								<br>
								Votos : <b><?= number_format($value['partido_primera_fuerza_votos'], 0, '.', ',') ?></b>
								
							</div>
							<div style='background-color:rgba(108, 122, 137,0.3);padding:12px;text-align:center'>
								Coalición <br>
								<b><?= $value['coalicion_primera_fuerza'] ?></b>
								<br>
								Votos Totales: <b><?= number_format($value['coalicion_primera_fuerza_votos'], 0, '.', ',') ?></b>
								<br>
								<div style='padding:12px;text-align:left'>
								<?php
								foreach ($value['coalicion_primera_fuerza_datos'] as $partido => $datos) {
									if ($datos['tipo'] == 1) {
										echo "<b>".$partido."</b> : ".number_format($datos['votos'], 0, '.', ',')." votos";
										echo "<br>";
									}
									if ($datos['tipo'] == 2) {
										echo "<br>";
										echo "<b>".$partido."</b> : ".number_format($datos['votos'], 0, '.', ',')." votos";
										echo "<br>";
									}
									
								}
								?>

								</div>
							</div>
							<?php
							echo "</td>";
						}elseif ($valueP['row'] == "segunda_fuerza_ind_coa") {
							echo "<td>";
							?>
							<div style='background-color:rgba(232, 236, 241,0.6);padding:12px;text-align:center; width:300px'>
								Partido <br>
								<img src="images/logos_partidos/<?= $value['partido_segunda_fuerza_logo'] ?>" style="width: 40px ">
								<br>
								<b><?= $value['partido_segunda_fuerza'] ?></b>
								<br>
								Votos : <b><?= number_format($value['partido_segunda_fuerza_votos'], 0, '.', ',') ?></b>
								
							</div>
							<div style='background-color:rgba(108, 122, 137,0.3);padding:12px;text-align:center'>
								Coalición <br>
								<b><?= $value['coalicion_segunda_fuerza'] ?></b>
								<br>
								Votos Totales: <b><?= number_format($value['coalicion_segunda_fuerza_votos'], 0, '.', ',') ?></b>
								<br>
								<div style='padding:12px;text-align:left'>
								<?php
								foreach ($value['coalicion_segunda_fuerza_datos'] as $partido => $datos) {
									if ($datos['tipo'] == 1) {
										echo "<b>".$partido."</b> : ".number_format($datos['votos'], 0, '.', ',')." votos";
										echo "<br>";
									}
									if ($datos['tipo'] == 2) {
										echo "<br>";
										echo "<b>".$partido."</b> : ".number_format($datos['votos'], 0, '.', ',')." votos";
										echo "<br>";
									}
									
								}
								?>

								</div>
							</div>
							<?php
							echo "</td>";
						}elseif ($valueP['row'] == "informacion_votaciones") {
							echo "<td>";
							?>
							<div style='padding:12px;text-align:left; width:300px'>
								Lista Nominal : <b><?= number_format($value['lista_nominal'], 0, '.', ',') ?></b><br>
								Casillas : <b><?= number_format($value['casillas'], 0, '.', ',') ?></b><br><br>
								V. Nulos : <b><?= number_format($value['votos_nulos'], 0, '.', ',') ?></b><br>
								V. Can N Reg : <b><?= number_format($value['votos_can_nreg'], 0, '.', ',') ?></b><br>
								V. Validos : <b><?= number_format($value['votos_validos'], 0, '.', ',') ?></b><br>
								V. Totales : <b><?= number_format($value['votos_totales'], 0, '.', ',') ?></b><br>
								<br>
								Votos : <b><?= number_format($value['partido_segunda_fuerza_votos'], 0, '.', ',') ?></b>
							</div>
							<?php
							echo "</td>";
						}elseif ($valueP['row'] == "informacion_territorio") {
							echo "<td>";
							?>
							<div style='padding:12px;text-align:left; width:300px'>
								Manzanas : <b><?= number_format($value['manzanas'], 0, '.', ',') ?></b><br><br>
								Prog. Gobierno : <b><?= number_format($value['apoyos_programas'], 0, '.', ',') ?></b><br>
								Prog. Inversion : <b><?= number_format($value['acciones_obras'], 0, '.', ',') ?></b><br>
								Eventos Agen. Gobierno : <b><?= number_format($value['eventos_agenda_gobierno'], 0, '.', ',') ?></b><br><br>

								Grupos Interes : <b><?= number_format($value['grupos_interes'], 0, '.', ',') ?></b><br>
								Cant. Padron : <b><?= number_format($value['ciudadanos_registrados'], 0, '.', ',') ?></b><br>
								Militantes : <b><?= number_format($value['militantes'], 0, '.', ',') ?></b><br>
								Funcionarios : <b><?= number_format($value['funcionarios'], 0, '.', ',') ?></b><br>
								<br>
								
							</div>
							<?php
							echo "</td>";
						}else{
							echo "<td>".$value[$valueP['row']]."</td>";
						}
						
					}
					echo "</tr>";
				}
			?>
		</tbody>
	</table>

