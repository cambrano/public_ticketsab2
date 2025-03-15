<?php
	include __DIR__.'/../../functions/security.php'; 
	@session_start();
	//var_dump($_POST);
	if (!empty($_POST)) {
		include '../functions/secciones_reportes.php';
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
				"order": [[ 0, "desc" ]],
				"ordering": true,
				"searching": true,/* para el buscador*/
				"paging": true,/* para la paginacion y todo salga en una sola hora*/
				"aoColumnDefs": [
									{ "bSortable": false, "aTargets": [ 0,1,2,3,4,5/*6,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16*/ ] },
									{ "targets": [ 0,1 ],"visible": false}
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
	</script>



	<style type="text/css">
		div.dataTables_wrapper div.dataTables_filter {
			display: none;
			text-align: right;
		}
		.div_info_seccion{
			text-transform: none;
			float:left;
		}
		@media screen and (max-width: 1010px) {
			.div_info_seccion{
				width:100%;
			}
		}
		table.dataTable tbody td {
			background-color: none !important;
		}

		.red {
			background-color: blue !important;
		}
	</style>

	<table id="secciones_reportes-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
		<thead>
			<tr>
				<th>Id Sección</th>
				<th>Id Pregunta</th>
				<th>Sección</th>
				<th>Mayoria</th>
				<th>Información</th>
				<th>Información Sección</th>
				<th>Tipo Sección</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach ($datos_secciones_ine as $key => $value) {
					$informacion_seccion = "<div class='div_info_seccion'>
						Lista Nominal:<b>".number_format($value['lista_nominal'], 0, '.', ',')."</b><br>
						Casillas:<b>".number_format($value['casillas'], 0, '.', ',')."</b><br><br>
						Votos Válidos:<b>".number_format($value['votos_validos'], 0, '.', ',')."</b><br>".
						"Votos Nulos:<b>".number_format($value['votos_nulos'], 0, '.', ',')."</b><br>".
						"<!---Votos CAN NREG:<b>".number_format($value['votos_can_nreg'], 0, '.', ',')."</b><br>--->".
						"Votos Totales:<b>".number_format($value['votos_totales'], 0, '.', ',')."</b><br>".
						"P. Ciudadana:<b>".$value['participacion_ciudadana']."%</b>".
					"</div>
					";
					$informacion_ganador = "<div class='div_info_seccion'>
						<img style='width:45px; height:45px;text-align:center' class='bntImageSize' src='images/logos_partidos/".$value['ganador']['logo']."'><br>
						Pregunta:<b>".$value['ganador']['nombre']."</b><br>
						Votos Totales:<b>".number_format($value['ganador']['votos'], 0, '.', ',')."</b><br>
						Porcentaje:<b>".$value['ganador']['porcentaje']."%</b><br>
						Gano x Diferencia:<b>".$value['diferencia_votos']."</b><br>
					</div>";
					$informacion_secundario = "<div class='div_info_seccion'>
						<img style='width:45px; height:45px;text-align:center' class='bntImageSize' src='images/logos_partidos/".$value['secundario']['logo']."'><br>
						Pregunta:<b>".$value['secundario']['nombre']."</b><br>
						Votos Totales:<b>".number_format($value['secundario']['votos'], 0, '.', ',')."</b><br>
						Porcentaje:<b>".$value['secundario']['porcentaje']."%</b><br>
						Perdio x Diferencia:<b>".$value['diferencia_votos']."</b><br>
					</div>";



					/*style="<?= $color ?>"*/
					?>
					<tr >
						<td>a<?= $value['id'] ?>z</td>
						<td><?= $value['ganador']['id'] ?></td>
						<td><?= $value['numero'] ?></td>
						<td><?= $informacion_ganador ?></td>
						<td><?= $informacion_secundario ?></td>
						<td><?= $informacion_seccion ?></td>
						<td><?= $value['tipo'] == 1 ? 'Urbana' :'Rural' ?></td>
					</tr>
					<?php
				}
			?>

		</tbody>
	</table>

