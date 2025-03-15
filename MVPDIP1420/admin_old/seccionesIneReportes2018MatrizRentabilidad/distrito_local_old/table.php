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
				"destroy": false,
				"responsive": true,
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
				"order": [[ 3, "asc" ]],
				"ordering": true,
				"searching": true,/* para el buscador*/
				"paging": true,/* para la paginacion y todo salga en una sola hora*/
				"aoColumnDefs": [
									{ "bSortable": false, "aTargets": [ 0,1,2,5,6,9,12,13,15 ] },
									{ "targets": [ 0,1,2,9 ],"visible": false}
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
			link="casillasVotosReportes2018/distrito_local/index.php?id="+valor; 
			var link2="casillasVotosReportes2018/distrito_local/index.php";
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
		function verMasDistritoLocal(valor){
			link="seccionesIneReportes2018MatrizRentabilidad/distrito_local/index.php?id="+valor; 
			var link2="seccionesIneReportes2018MatrizRentabilidad/distrito_local/index.php";
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
		function verMasDistritoLocalMatrizRentabilidad2018(valor){
			link="seccionesIneReportes2018MatrizRentabilidad/distrito_local/index.php?id=<?= $id_distrito_local ?>"; 
			var link2="seccionesIneReportes2018MatrizRentabilidad/distrito_local/index.php";
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
		function downloadExcel(){
			<?php
			if($pageService != $_COOKIE["pageService"]){
				if($pageService!=''){
					$_COOKIE["pageService"] = $pageService ;
				}
			}
			?>
			var link="seccionesIneReportes2018MatrizRentabilidad/distrito_local/excel/index.php?cot=<?=$_COOKIE['pageService']?>";
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
			var link="seccionesIneReportes2018MatrizRentabilidad/distrito_local/print/index.php?cot=<?=$_COOKIE['pageService']?>";
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

	<table id="secciones_reportes-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
		<thead>
			<tr>
				<th>Id Sección</th>
				<th>Id Partido Ganador</th>
				<th>Id Municipio</th>
				<th>Sección</th>
				<th>Municipio</th>
				<th>Primera Fuerza</th>
				<th>Segunda Fuerza</th>
				<th>Comp.</th>
				<th>Comp Sección.</th>
				<th>Semáforo Color</th>
				<th>Rent.</th>
				<th>Rent Sección.</th>
				<th>Información Votación</th>
				<th>Información Territorio</th>
				<th>Tipo</th>
				<th>Información Revocación Mandato 2022</th>
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
													<td style='border:1px solid;padding: 2px;background-color: #f8f9fb'>".$keyPartido."</td>
													<td style='border:1px solid;padding: 2px;background-color: #f8f9fb'>".$valueVoto."</td>
													<td style='border:1px solid;padding: 2px;background-color: #f8f9fb'>".$diff."</td>
												<tr>";
								}
						$informacion_segunda_fuerza .="</table>";
					}

					if(!empty($value['revocacion_mandato'])){
						$participacion_ciudadana = ($value['consulta_rvm_votos_totales'] / $value['consulta_rvm_lista_nominal']) * 100;
						$porcenjate_siga = $value['revocacion_mandato']['orden_preguntas']['SIGA'] / $value['consulta_rvm_votos_totales'] * 100;
						$porcenjate_no_siga = $value['revocacion_mandato']['orden_preguntas']['NO_SIGA'] / $value['consulta_rvm_votos_totales'] * 100;
						

						$revocacion_mandato = "
									<div style='text-transform: none;'>
									Consulta 2022 Revocación Mandato
									<br>
									<br>
									Lista Nominal: <b>".number_format($value['consulta_rvm_lista_nominal'], 0, '.', ',')."</b>
									<br>
									Casillas: <b>".number_format($value['consulta_rvm_casillas'], 0, '.', ',')."</b>
									<br>
									Participacion: <b> ".number_format($participacion_ciudadana, 2, '.', '')." %</b>
									<br><br>

									<table style='width: 100%;border:1px solid;text-align: left;font-size: 10px' >
										<tr>
											<td style='border:1px solid;padding: 2px;background-color: #dee3ed;text-align: center'><b>Siga</b></td>
											<td style='border:1px solid;padding: 2px;background-color: #dee3ed;text-align: center'><b>No Siga</b></td>
										</tr>
										<tr>
											<td style='border:1px solid;padding: 2px;background-color: #f8f9fb;text-align: center'>
												<img style='width:25px; height:25px' class='bntImageSize' src='images/logos_partidos/".$value['revocacion_mandato']['preguntas']['SIGA']['logo']."'>
											</td>
											<td style='border:1px solid;padding: 2px;background-color: #f8f9fb;text-align: center'>
												<img style='width:25px; height:25px' class='bntImageSize' src='images/logos_partidos/".$value['revocacion_mandato']['preguntas']['NO_SIGA']['logo']."'>
											</td>
										</tr>
										<tr>
											<td style='border:1px solid;padding: 2px;background-color: #f8f9fb;text-align: center'>
												Votos: <b>".number_format($value['revocacion_mandato']['preguntas']['SIGA']['votos'], 0, '.', ',')."</b><br>
											</td>
											<td style='border:1px solid;padding: 2px;background-color: #f8f9fb;text-align: center'>
												Votos: <b>".number_format($value['revocacion_mandato']['preguntas']['NO_SIGA']['votos'], 0, '.', ',')."</b><br>
											</td>
										</tr>
									</table>

									Votos Nulos: <b>".number_format($value['consulta_rvm_votos_nulos'], 0, '.', ',')."</b><br>
									Votos Totales: <b>".number_format($value['consulta_rvm_votos_totales'], 0, '.', ',')."</b>


									</div>";
						
						
						
						
					}else{
						$revocacion_mandato = "<center></center><img style='width:45px; height:45px' class='bntImageSize' src='images/logos_partidos/no_data.png'></center>";
					}


					$informacion_territorio = " 
										<br>
										Lista Nominal:<b>".number_format($value['lista_nominal'], 0, '.', ',')."</b><br>
										Casillas:<b>".number_format($value['casillas'], 0, '.', ',')."</b><br><br>

										Votos Válidos:<b>".number_format($value['votos_validos'], 0, '.', ',')."</b><br>".
										"Votos Nulos:<b>".number_format($value['votos_nulos'], 0, '.', ',')."</b><br>".
										"Votos CAN NREG:<b>".number_format($value['votos_can_nreg'], 0, '.', ',')."</b><br>".
										"Votos Totales:<b>".number_format($value['votos_totales'], 0, '.', ',')."</b><br>".
										"Participacion:<b>".number_format($value['participacion_ciudadana'], 2, '.', ',')."%</b>";


					$informacion_social = " 
						<br>
						Prog. Gob:<b>".number_format($value['apoyos_programas'], 0, '.', ',')."</b><br>
						Prog. Inv:<b>".number_format($value['acciones_obras'], 0, '.', ',')."</b><br>
						<br>
						Grupo Interes:<b>".number_format($value['grupos_interes'], 0, '.', ',')."</b><br>
						Ciudadanos:<b>".number_format($value['ciudadanos_registrados'], 0, '.', ',')."</b><br>
						Militantes:<b>".number_format($value['militantes'], 0, '.', ',')."</b><br>
						Funcionarios:<b>".number_format($value['funcionarios'], 0, '.', ',')."</b><br>
						<br>
						Juntas:<b>".number_format($value['juntas'], 0, '.', ',')."</b><br>
						Visitas:<b>".number_format($value['visitas'], 0, '.', ',')."</b><br>
						Caminatas:<b>".number_format($value['caminatas'], 0, '.', ',')."</b><br>
					";

					$vermas='<button class="btn btn-info"  onClick="verMasSeccion('.$value["id"].');" >
					<span class="btnImage"><img class="bntImageSize" src="img/view20.png"></span>
					<span class="btnText">Ver Más</span></button>';

					if($value['orden_votos_individual']['semaforo']['color']=='rojo'){
						$color = 'rgba(255, 105, 97, 0.9)';
					}elseif ($value['orden_votos_individual']['semaforo']['color']=='amarillo') {
						$color = 'rgba(253, 253, 150, 0.9)';;
					}elseif ($value['orden_votos_individual']['semaforo']['color']=='gris') {
						$color = 'rgba(141, 141, 141, 0.9)';;
					}elseif ($value['orden_votos_individual']['semaforo']['color']=='verde') {
						$color = 'rgba(119, 221, 119, 0.9)';;
					}else{
						$color = 'rgba(0, 0, 0, 0.9)';
					}

					?>

					<tr>
						<td><?= ($value['id']); ?></td>
						<td><?= celda_excel($datos_primera_fuerza['id']); ?></td>
						<td><?= ($value['id_municipio']); ?></td>
						<td><?= $value['numero'] ?></td>
						<td><?= $value['municipio'] ?></td>
						<td><?= $informacion_primera_fuerza ?></td>
						<td><?= $informacion_segunda_fuerza ?></td>
						<td style="text-align: center;">
							<div style="background-color : <?= $color ?>; height: 60px; width: 100%; text-align: center;font-size:10px;padding:5px">
								<br>
								<b style="color:black;font-size:15px">
									<?= $value['orden_votos_individual']['semaforo']['competitividad'] ?>
								</b>
							</div>
							
						</td>
						<td style="text-align: center;">
							<div style=" height: 60px; width: 100%; text-align: center;font-size:10px;padding:5px">
								<br>
								<b style="color:black;font-size:15px">
									<?= $value['orden_votos_individual']['semaforo']['competitividad_orden'] ?>
								</b>
							</div>
						</td>
						<td><?= $value['orden_votos_individual']['semaforo']['color'] ?></td>
						<td style="text-align: center;">
							<div style=" height: 60px; width: 100%; text-align: center;font-size:10px;padding:5px">
								<br>
								<b style="color:black;font-size:15px">
									<?= $value['orden_votos_individual']['semaforo']['rentabilidad'] ?>
								</b>
							</div>
						</td>
						<td style="text-align: center;">
							<div style=" height: 60px; width: 100%; text-align: center;font-size:10px;padding:5px">
								<br>
								<b style="color:black;font-size:15px">
									<?= $value['orden_votos_individual']['semaforo']['rentabilidad_orden'] ?>
								</b>
							</div>
						</td>
						<td><?= $informacion_territorio ?></td>
						<td><?= $informacion_social ?></td>
						<td><?= $value['tipo'] == 1 ? 'Urbana' :'Rural' ?></td>
						<td style="text-align: center;"><?= $revocacion_mandato ?></td>
					</tr>
					<?php
				}
			?>
		</tbody>
	</table>

