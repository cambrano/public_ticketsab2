<?php
	include __DIR__.'/../../functions/security.php'; 
	@session_start();
	//var_dump($_POST);
	if(!empty($_POST)){
		include '../../functions/secciones_reportes.php';
		foreach ($_POST['searchTable'][0] as $key => $value) {
			//echo "XX".$key." = XX_SESSION['".$key."'];";
			$_SESSION[$key]=mysqli_real_escape_string($conexion,$value);
			//echo "<br>";
		}
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
									{ "bSortable": false, "aTargets": [ 0,1,4,5,6/*1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16*/ ] },
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
		function verMasSeccion(valor){
			link="casillasVotosReportes2021/distrito_federal/index.php?id="+valor; 
			var link2="casillasVotosReportes2021/distrito_federal/index.php";
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
			link="seccionesIneReportes2021/distrito_federal/index.php?id="+valor; 
			var link2="seccionesIneReportes2021/distrito_federal/index.php";
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
		function verMasDistritoLocalMatrizRentabilidad2021(valor){
			link="seccionesIneReportes2021MatrizRentabilidad/distrito_federal/index.php?id=<?= $id_distrito_federal ?>"; 
			var link2="seccionesIneReportes2021MatrizRentabilidad/distrito_federal/index.php";
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
				<th>Sección</th>
				<th>Mayoria</th>
				<th>Partido</th>
				<th>Información Distrito</th>
				<th>Tipo</th>
				<th>Opciones</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach ($datos_secciones_ine as $key => $value) {

					$porcentaje_partido_ganador = 0;
					$porcentaje_partido_ganador = ($value['partido_ganador_votos'] / ($value['votos_validos'] + $value['votos_nulos'] +$value['votos_can_nreg']) )*100;
					$porcentaje_partido_ganador = truncar($porcentaje_partido_ganador, 2);

					$informacion_partido_ganador = "<div style='text-transform: none;'>
										<img style='width:45px; height:45px' class='bntImageSize' src='images/logos_partidos/".$value['partido_ganador_logo']."'>&nbsp;
										<b>".$value['partido_ganador_nombre_corto']."</b><br>Votos Totales:<b>".number_format($value['partido_ganador_votos'],0,'.',',')."</b><br>
										Votos % Ganado:<b>".$porcentaje_partido_ganador."%</b>
									</div>";

					$porcentaje_partido_sistema = 0;
					$porcentaje_partido_sistema = ($value['partido_sistema_votos'] / ($value['votos_validos'] + $value['votos_nulos'] +$value['votos_can_nreg']) )*100;
					$porcentaje_partido_sistema = truncar($porcentaje_partido_sistema, 2);

					$partido_ganador_diferencia_votos = $value['partido_ganador_votos'] - $value['partido_sistema_votos'];
					$partido_ganador_diferencia_porcentaje = $porcentaje_partido_ganador - $porcentaje_partido_sistema;

					if($value['partido_ganador_votos'] != $value['partido_sistema_votos']){
						$diferencia_votos ="<font color='red'>&nbsp;&nbsp;&#8595;<b>".$partido_ganador_diferencia_votos."</b></font>";
						$diferencia_porcentaje ="<font color='red'>&nbsp;&nbsp;&#8595;<b>".$partido_ganador_diferencia_porcentaje."%</b></font>";
					}else{
						$diferencia_votos = "";
						$diferencia_porcentaje = "";
					}
					$informacion_partido_sistema= "<div style='text-transform: none;'>
									<img style='width:45px; height:45px' class='bntImageSize' src='images/logos_partidos/".$value['partido_sistema_logo']."'>&nbsp;
									<b>".$value['partido_sistema_corto']."</b>
									<br>
									Votos Totales:<b>".number_format($value['partido_sistema_votos'], 0, '.', ',')."</b>".$diferencia_votos."<br>
									Votos % Partido:<b>".$porcentaje_partido_sistema."%</b>".$diferencia_porcentaje."
								</div>";


					$votos_totales=0;
					$votos_totales=$value['votos_validos'] + $value['votos_nulos'] + $value['votos_can_nreg'];
					$participacion_ciudadana = 0;
					if($votos_totales != 0){
						$participacion_ciudadana = ($votos_totales / $value['lista_nominal'] ) * 100;
					}else{
						$participacion_ciudadana =0 ;
					}

					$informacion_municipio = "<div class='div_info_seccion'>
										Lista Nominal:<b>".number_format($value['lista_nominal'], 0, '.', ',')."</b><br>
										Casillas:<b>".number_format($value['casillas'], 0, '.', ',')."</b><br><br>
									 
										Votos Válidos:<b>".number_format($value['votos_validos'], 0, '.', ',')."</b><br>".
										"Votos Nulos:<b>".number_format($value['votos_nulos'], 0, '.', ',')."</b><br>".
										"Votos CAN NREG:<b>".number_format($value['votos_can_nreg'], 0, '.', ',')."</b><br>".
										"Votos Totales:<b>".number_format($votos_totales, 0, '.', ',')."</b><br>".
										"P. Ciudadana:<b>".number_format($participacion_ciudadana, 2, '.', ',')."%</b>".
									"</div>
									";
					$vermas='<button class="btn btn-info"  onClick="verMasSeccion('.$value["id"].');" >
					<span class="btnImage"><img class="bntImageSize" src="img/view20.png"></span>
					<span class="btnText">Ver Más</span></button>';
					?>
					<tr>
						<td><?= $value['id'] ?></td>
						<td><?= $value['partido_ganador_id'] ?></td>
						<td><?= $value['numero'] ?></td>
						<td><?= $informacion_partido_ganador ?></td>
						<td><?= $informacion_partido_sistema ?></td>
						<td><?= $informacion_municipio ?></td>
						<td><?= $value['tipo'] == 1 ? 'Urbana' :'Rural' ?></td>
						<?php
						if($value['partido_ganador_votos']!=0){
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

