<?php
		include __DIR__.'/../functions/security.php'; 
		
		@session_start();
		//var_dump($_POST);
		if(!empty($_POST)){
			include '../functions/municipios_reportes.php';
			foreach ($_POST['searchTable'][0] as $key => $value) {
				//echo "XX".$key." = XX_SESSION['".$key."'];";
				$_SESSION[$key]=mysqli_real_escape_string($conexion,$value);
				//echo "<br>";
			}
		}
?>
	<script type="text/javascript">
		$(document).ready(function () {
			var dataTable = $('#municipios_reportes-tabla').DataTable({
				"destroy": true,
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
				"order": [
					[0, "desc"]
				],
				"ordering": true,
				"searching": true,
				/* para el buscador*/
				"paging": true,
				/* para la paginacion y todo salga en una sola hora*/
				"aoColumnDefs": [
					{
						"bSortable": false,
						"aTargets": [
							0,
							1,
							3,
							4,
							5,
							6,
							7,
							8/*1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16*/
						]
					}, {
						"targets": [0, 1],"visible": false
					}
				],

				"scrollY": "100%",
				"scrollX": "100%",

				"language": {
					"sProcessing": "Procesando...",
					//"sLengthMenu":     "Mostrar _MENU_ registros",
					"sLengthMenu": ' ',
					/*"lengthMenu": 'Mostrar <select id="total_registros">'+
						'<option value="11">11</option>'+
						'<option value="2000000">Todos</option>'+
						'</select> registros',*/
					"sSearch": "Buscar:",
					"sZeroRecords": "Registro no encontrados",
					"sEmptyTable": "No Existe Registros",
					"sInfo": "Mostrar  (_START_ a _END_) de _TOTAL_ Registros", //
					"sInfoEmpty": "Mostrando Registros del 0 al 0 de Total de 0 Registros", //
					"sInfoFiltered": "(Filtrado de _MAX_ Total Registros)", //
					//"sInfoPostFix":    "", "sUrl":            "", "sInfoThousands":  ",",
					"sLoadingRecords": "Cargando...",
					"oPaginate": {
						"sFirst": "<<",
						"sLast": ">>",
						"sNext": ">",
						"sPrevious": "<"
					},
					"oAria": {
						"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
						"sSortDescending": ": Activar para ordenar la columna de manera descendente"
					}
				}
			});
		});
		function verMasDistritoLocal(valor) {
			link = "seccionesIneReportes2021/municipio/index.php?id=" + valor;
			var link2 = "seccionesIneReportes2021/municipio/index.php";
			dataString = 'urlink=' + link2;
			$.ajax(
				{type: "POST", url: "functions/backarray.php", data: dataString, success: function (data) {}}
			);
			//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
			$("#homebody").load(link);
		}
		function verMasDistritoLocalMatrizRentabilidad(valor) {
			link = "seccionesIneReportes2021MatrizRentabilidad/municipio/index.php?id=" +
					valor;
			var link2 = "seccionesIneReportes2021MatrizRentabilidad/municipio/index.php";
			dataString = 'urlink=' + link2;
			$.ajax(
				{type: "POST", url: "functions/backarray.php", data: dataString, success: function (data) {}}
			);
			//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
			$("#homebody").load(link);
		}
		function verMasMunicipioMandato2022(valor) {
			link = "seccionesIneReportes2022RevocacionMandato/municipio/index.php?id=" +
					valor;
			var link2 = "seccionesIneReportes2022RevocacionMandato/municipio/index.php";
			dataString = 'urlink=' + link2;
			$.ajax(
				{type: "POST", url: "functions/backarray.php", data: dataString, success: function (data) {}}
			);
			//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
			$("#homebody").load(link);
		}
	</script>

	<style type="text/css">
		div.dataTables_wrapper div.dataTables_filter {
			display: none;
			text-align: right;
		}
	</style>
	<table
		id="municipios_reportes-tabla"
		class="table table-striped table-bordered  cell-border compact stripe"
		style="width:100%">
		<thead>
			<tr>
				<th>Id Distrito Local</th>
				<th>Id Partido Ganador</th>
				<th>Municipio</th>
				<th>Mayoria</th>
				<th>Partido</th>
				<th>Información Territorio</th>
				<th>Ver Territorio</th>
				<th>Ver Matriz
					<br>Rentabilidad</th>
				<th>Ver Cosulta Revoc
					<br>Mandato 2022</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($datos_municipios as $key => $value) {
				$primera_fuerza = $value['orden_votos_individual']['primera_fuerza'];
				$datos_primera_fuerza = $datos_municipios[$key]['partidos'][$primera_fuerza];
				$segunda_fuerza = $datos_municipios[$key]['orden_votos_individual']['segunda_fuerza'];
				$datos_segunda_fuerza = $datos_municipios[$key]['partidos'][$segunda_fuerza];
				
				$informacion_primera_fuerza = "
					<div style='text-transform: none;'>
						<img style='width:45px; height:45px' class='bntImageSize' src='images/logos_partidos/".$datos_primera_fuerza['logo']."'>&nbsp;
						<b>".$primera_fuerza."</b><br>
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
										<td style='border:1px solid;padding: 2px;background-color: #f8f9fb'>".$keyPartido."</td>
										<td style='border:1px solid;padding: 2px;background-color: #f8f9fb'>".number_format($valueVoto, 0, '.', ',')."</td>
										<td style='border:1px solid;padding: 2px;background-color: #f8f9fb'>".number_format($diff, 0, '.', ',')."</td>
									<tr>";
							}
					$informacion_primera_fuerza .="</table>";
				} 	
				$informacion_segunda_fuerza = "
					<div style='text-transform: none;'>
						<img style='width:45px; height:45px' class='bntImageSize' src='images/logos_partidos/".$datos_segunda_fuerza['logo']."'>&nbsp;
						<b>".$segunda_fuerza."</b><br>
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
										<td style='border:1px solid;padding: 2px;background-color: #f8f9fb'>".$keyPartido."</td>
										<td style='border:1px solid;padding: 2px;background-color: #f8f9fb'>".number_format($valueVoto, 0, '.', ',')."</td>
										<td style='border:1px solid;padding: 2px;background-color: #f8f9fb'>".number_format($diff, 0, '.', ',')."</td>
									<tr>";
							}
					$informacion_segunda_fuerza .="</table>";
				}
				$informacion_territorio = " 
									<br>
									Lista Nominal:<b>".number_format($value['lista_nominal'], 0, '.', ',')."</b><br>
									Secciones:<b>".number_format($value['secciones_ine'], 0, '.', ',')."</b><br>
									Casillas:<b>".number_format($value['casillas'], 0, '.', ',')."</b><br><br>
									Votos Válidos:<b>".number_format($value['votos_validos'], 0, '.', ',')."</b><br>".
									"Votos Nulos:<b>".number_format($value['votos_nulos'], 0, '.', ',')."</b><br>".
									"Votos CAN NREG:<b>".number_format($value['votos_can_nreg'], 0, '.', ',')."</b><br>".
									"Votos Totales:<b>".number_format($value['votos_totales'], 0, '.', ',')."</b><br>".
									"P. Ciudadana:<b>".number_format($value['participacion_ciudadana'], 2, '.', ',')."%</b>";
				$vermas='<button class="btn btn-info"  onClick="verMasDistritoLocal('.$value["id"].');" >
				<span class="btnImage"><img class="bntImageSize" src="img/view20.png"></span>
				<span class="btnText">Ver Más</span></button>';
				$vermatriz_rentabilidad='<button class="btn btn-info"  onClick="verMasDistritoLocalMatrizRentabilidad('.$value["id"].');" >
				<span class="btnImage"><img class="bntImageSize" src="img/bigdata20.png"></span>
				<span class="btnText">Matriz Rentabilidad</span></button>';
				$revocacion_mandato22='<button class="btn btn-info"  onClick="verMasMunicipioMandato2022('.$value["id"].');" >
				<span class="btnImage"><img class="bntImageSize" src="img/download20.png"></span>
				<span class="btnText">Revocación Mandato 2021</span></button>'; 
			?>
			<tr>
				<td><?= celda_excel($value['id']); ?></td>
				<td><?= celda_excel($datos_primera_fuerza['id']); ?></td>
				<td><?= $value['municipio'] ?></td>
				<td><?= $informacion_primera_fuerza ?></td>
				<td><?= $informacion_segunda_fuerza ?></td>
				<td><?= $informacion_territorio ?></td>
				<td><?= $vermas ?></td>
				<td><?= $vermatriz_rentabilidad ?></td>
				<td><?= $revocacion_mandato22 ?></td>
			</tr>
			<?php
				}
			?>
		</tbody>
	</table>