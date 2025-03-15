<?php
	include __DIR__.'/../../functions/security.php'; 
	@session_start();
	//var_dump($_POST);
	if(!empty($_POST)){
		include '../../functions/secciones_reportes.php';
		foreach ($_POST['searchTable'][0] as $key => $value) {
			$escapedValue = mysqli_real_escape_string($conexion, $value);
			$_POST['searchTable'][0][$key] = $escapedValue;
		}
		$postData = json_encode($_POST);
	}else{
		$postData = "''";
	}
?> 
	<script type="text/javascript">
		$(document).ready(function() {
			var dataTable = $('#secciones_reportes-tabla').DataTable( {
				"destroy": true,
				"autoWidth": true,
				"responsive": true,
				"pageLength": 11,
				"retrieve": true,
				"info": true,
				"processing": true,
				
				"sPaginationType": "full_numbers", 
				/*"fixedHeader": true,*/
				"fixedHeader": {
					header: true,
					footer: true
				},
				"order": [[ 3, "asc" ]],
				"ordering": true,
				"searching": true,/* para el buscador*/
				"paging": true,/* para la paginacion y todo salga en una sola hora*/
				"aoColumnDefs": [
									{ "bSortable": false, "aTargets": [ 0,1,2,6,7,8,9,11 ] },
									{ "targets": [ 0,1,2 ],"visible": false},
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
			link="casillasVotosReportes2016/senador/index.php?id="+valor; 
			var link2="casillasVotosReportes2016/senador/index.php";
			dataString = 'urlink='+link2;  
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {}
			});
			//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
			//$("#homebody").load(link+"&refresh=1");
			$("#homebody").load(link+"&refresh=1");
		}
		function verMasMunicipio(valor){
			link="seccionesIneReportes2016/senador/index.php?id="+valor; 
			var link2="seccionesIneReportes2016/senador/index.php";
			dataString = 'urlink='+link2;  
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {}
			});
			//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
			//$("#homebody").load(link+"&refresh=1");
			$("#homebody").load(link+"&refresh=1");
		}
		function verMasMunicipioMatrizRentabilidad2016(valor){
			link="seccionesIneReportes2016MatrizRentabilidad/senador/index.php?id=<?= $id_municipio ?>"; 
			var link2="seccionesIneReportes2016MatrizRentabilidad/senador/index.php";
			dataString = 'urlink='+link2;  
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {}
			});
			//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
			//$("#homebody").load(link);
			$("#homebody").load(link+"&refresh=1");
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

	<table id="secciones_reportes-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
		<thead>
			<tr>
				<th>Id Sección</th>
				<th>Id Partido Ganador</th>
				<th>Id Senador</th>
				<th>Sección</th>
				<th>D.L</th>
				<th>D.F</th>
				<th>Senador</th>
				<th>Localidad(es)</th>
				<th>Colonia(s)</th>
				<th>Primera Fuerza</th>
				<th>Segunda Fuerza</th>
				<th>Información Territorio</th>
				<th>Tipo</th>
				<th>Opciones</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach ($datos_secciones_ine as $key => $value) {

					$primera_fuerza = $value['orden_votos_individual']['primera_fuerza'];
					$segunda_fuerza = $value['orden_votos_individual']['segunda_fuerza'];
					if($primera_fuerza=='NoData'){
						$datos_segunda_fuerza = $datos_primera_fuerza =$no_data['NoData'];
					}else{
						$datos_primera_fuerza = $value['partidos'][$primera_fuerza];
						$datos_segunda_fuerza = $value['partidos'][$segunda_fuerza];
					}
					
					

					$informacion_primera_fuerza = "
								<div style='text-transform: none;'>
									<img style='width:45px; height:45px' class='bntImageSize' src='images/logos_partidos/".$datos_primera_fuerza['logo']."'>&nbsp;
										<b>".$partidos[$primera_fuerza]."</b><br>
										Votos Individual: <b>".number_format($datos_primera_fuerza['votos_individual'], 0, '.', ',')."</b><br>
										Votos Coalición Ind: <b>".number_format($datos_primera_fuerza['votos_coaliciones_individual'], 0, '.', ',')."</b><br>
										Votos Coalición Boletas: <b>".number_format($datos_primera_fuerza['votos_coaliciones'], 0, '.', ',')."</b><br>
										Votos Total: <b>".number_format($datos_primera_fuerza['votos_totales'], 0, '.', ',')."</b><br>


									</div>";

					if(!empty($datos_primera_fuerza['coaliciones_orden_votos_individual'])){
						$informacion_primera_fuerza .="
								<table style='width: 100%;border:1px solid;text-align: left;font-size: 10px' >
									<tr>
										<td style='border:1px solid;padding: 2px;background-color: #dee3ed'><b>Partido</b></td>
										<td style='border:1px solid;padding: 2px;background-color: #dee3ed'><b>Votos</b></td>
										<td style='border:1px solid;padding: 2px;background-color: #dee3ed'><b>Diff</b></td>
									</tr>";
								unset($datos_primera_fuerza['coaliciones_orden_votos_individual'][$primera_fuerza]);
								foreach ($datos_primera_fuerza['coaliciones_orden_votos_individual'] as $keyPartido => $valueVoto) {
									$diff = $datos_primera_fuerza['votos_individual'] - $valueVoto;
									$informacion_primera_fuerza .="
												<tr>
													<td style='border:1px solid;padding: 2px;background-color: #f8f9fb'>".$partidos[$keyPartido]."</td>
													<td style='border:1px solid;padding: 2px;background-color: #f8f9fb'>".$valueVoto."</td>
													<td style='border:1px solid;padding: 2px;background-color: #f8f9fb'>".$diff."</td>
												<tr>";
								}
						$informacion_primera_fuerza .="</table>";
					}

					$informacion_segunda_fuerza = "
								<div style='text-transform: none;'>
									<img style='width:45px; height:45px' class='bntImageSize' src='images/logos_partidos/".$datos_segunda_fuerza['logo']."'>&nbsp;
										<b>".$datos_segunda_fuerza['nombre_corto']."</b><br>
										Votos Individual: <b>".number_format($datos_segunda_fuerza['votos_individual'], 0, '.', ',')."</b><br>
										Votos Coalición Ind: <b>".number_format($datos_segunda_fuerza['votos_coaliciones_individual'], 0, '.', ',')."</b><br>
										Votos Coalición Boletas: <b>".number_format($datos_segunda_fuerza['votos_coaliciones'], 0, '.', ',')."</b><br>
										Votos Total: <b>".number_format($datos_segunda_fuerza['votos_totales'], 0, '.', ',')."</b><br>


									</div>";

					if(!empty($datos_segunda_fuerza['coaliciones_orden_votos_individual'])){
						$informacion_segunda_fuerza .="
								<table style='width: 100%;border:1px solid;text-align: left;font-size: 10px' >
									<tr>
										<td style='border:1px solid;padding: 2px;background-color: #dee3ed'><b>Partido</b></td>
										<td style='border:1px solid;padding: 2px;background-color: #dee3ed'><b>Votos</b></td>
										<td style='border:1px solid;padding: 2px;background-color: #dee3ed'><b>Diff</b></td>
									</tr>";
								unset($datos_segunda_fuerza['coaliciones_orden_votos_individual'][$segunda_fuerza]);
								foreach ($datos_segunda_fuerza['coaliciones_orden_votos_individual'] as $keyPartido => $valueVoto) {
									$diff = $datos_segunda_fuerza['votos_individual'] - $valueVoto;
									$informacion_segunda_fuerza .="
												<tr>
													<td style='border:1px solid;padding: 2px;background-color: #f8f9fb'>".$partidos[$keyPartido]."</td>
													<td style='border:1px solid;padding: 2px;background-color: #f8f9fb'>".$valueVoto."</td>
													<td style='border:1px solid;padding: 2px;background-color: #f8f9fb'>".$diff."</td>
												<tr>";
								}
						$informacion_segunda_fuerza .="</table>";
					}


					$informacion_territorio = " 
										<br>
										Lista Nominal:<b>".number_format($value['lista_nominal'], 0, '.', ',')."</b><br>
										Casillas:<b>".number_format($value['casillas'], 0, '.', ',')."</b><br><br>
									 
										Votos Válidos:<b>".number_format($value['votos_validos'], 0, '.', ',')."</b><br>".
										"Votos Nulos:<b>".number_format($value['votos_nulos'], 0, '.', ',')."</b><br>".
										"Votos CAN NREG:<b>".number_format($value['votos_can_nreg'], 0, '.', ',')."</b><br>".
										"Votos Totales:<b>".number_format($value['votos_totales'], 0, '.', ',')."</b><br>".
										"P. Ciudadana:<b>".number_format($value['participacion_ciudadana'], 2, '.', ',')."%</b>";
					$vermas='<button class="btn btn-info"  onClick="verMasSeccion('.$value["id"].');" >
					<span class="btnImage"><img class="bntImageSize" src="img/view20.png"></span>
					<span class="btnText">Ver Más</span></button>';
					?>

					<tr>
						<td><?= ($value['id']); ?></td>
						<td><?= celda_excel($datos_primera_fuerza['id']); ?></td>
						<td><?= celda_excel($value['id_municipio']); ?></td>
						<td><?= $value['numero'] ?></td>
						<td><?= $value['id_distrito_local'] ?></td>
						<td><?= $value['id_distrito_federal'] ?></td>
						<td><?= $value['municipio'] ?></td>
						<td>
							<?php
								$porciones = explode("*_*", $value['seccion_localidades']);
								foreach ($porciones as $keyHid => $localidad) {
									?>
									<table><tr><td style="border-bottom: 1px solid black;">*<?= htmlspecialchars($localidad, ENT_QUOTES, 'UTF-8') ?></td></tr></table>
									<?php
								}

							?> 
						</td>
						<td>
							<?php
								$porciones = explode("*_*", $value['seccion_colonias']);
								foreach ($porciones as $keyHid => $colonia) {
									?>
									<table><tr><td style="border-bottom: 1px solid black;">*<?= htmlspecialchars($colonia, ENT_QUOTES, 'UTF-8') ?></td></tr></table>
									<?php
								}

							?> 
						</td>
						<td><?= $informacion_primera_fuerza ?></td>
						<td><?= $informacion_segunda_fuerza ?></td>
						<td><?= $informacion_territorio ?></td>
						<td><?= $value['tipo'] == 1 ? 'Urbana' :'Rural' ?></td>
						<?php
						if($datos_primera_fuerza['votos_totales']!=0){
							echo "<td>".$vermas."</td>";
						}else{
							echo "<td></td>";
						}
						?>
					</tr>
					<?php
				}
			?>
		</tbody>
	</table>

