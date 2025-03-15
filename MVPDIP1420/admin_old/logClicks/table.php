<?php
	include __DIR__."/../functions/security.php";
	@session_start();
	if(!empty($_POST)){
		foreach ($_POST['searchTable'][0] as $key => $value) {
			$_SESSION['searchTable'][$key] = mysqli_real_escape_string($conexion,$value);
		}
	}
?>
	<script type="text/javascript" language="javascript" >
		$(document).ready(function() {
			var responsive=true;
			$(window).resize(function() {
				var widthBrowser =$(window).width();
				var heightBrowser =$(window).height();
				//console.log("Tamaño de la pantalla del navegador: width="+widthBrowser +" height="+heightBrowser );
				if(widthBrowser<820){
					var responsive=true;
				}else{
					var responsive=false;
				}
			});
			var dataTable = $('#log_clicks-tabla').DataTable( {
				"destroy": true,
				"responsive": responsive,
				"pageLength": 11,
				"retrieve": true,
				"info": true,
				"processing": true,
				"sPaginationType": "full_numbers", 
				"fixedHeader": true,
				"fixedHeader": {
					header: true,
				},
				"order": [[ 0, "desc" ]],
				"ordering": true,
				"searching": false,/* para el buscador*/
				"paging": true,/* para la paginacion y todo salga en una sola hora*/
				"aoColumnDefs": [
								{ "bSortable": false, "aTargets": [ 1,2,3,4,5,6,7,8 ] }
								],
				"serverSide": true,
				"scrollY": "100%", 
				"scrollX": "100%",

				"language": {
					"sProcessing":     "Procesando...",
					//"sLengthMenu":     "Mostrar _MENU_ registros",
					"sLengthMenu": ' ',
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
				"ajax":{
					url :"logClicks/log_clicks.php", // json datasource
					type: "post",  // method  , by default get
					error: function(){  // error handling
						$(".log_clicks-tabla-error").html("");
						$("#log_clicks-tabla").append('<tbody class="log_clicks-tabla-error"><tr><th colspan="8">Registros no encontrados</th></tr></tbody>');
						$("#log_clicks-tabla_processing").css("display","none");
						
					}
				}
			});

			$('#log_clicks-tabla').on( 'order.dt', function () {
				var table = $('#log_clicks-tabla').dataTable();
				var api = table.api();
				var order = table.api().order(); // this has column and order details 
				//console.log(order[0][0]);
				//console.log(order[0][1]);
				//mostrar en mapa 
				var city = document.getElementById("city").value;
				var region = document.getElementById("region").value;
				var country = document.getElementById("country").value;
				var fecha_1 = document.getElementById("fecha_1").value;
				var fecha_2 = document.getElementById("fecha_2").value;
				var os = document.getElementById("os").value;
				var browser = document.getElementById("browser").value;
				var searchTable = [];
				var data = {   
					'city' : city,
					'region' : region,
					'country' : country,
					'fecha_1' : fecha_1,
					'fecha_2' : fecha_2,
					'os' : os,
					'browser' : browser,
				}
				searchTable.push(data);
				var mapa = [];
				var data = {
						'order' : order[0][0],
						'order_tipo' : order[0][1],
					}
				mapa.push(data);
				$.ajax({
					type: "POST",
					url: "logClicks/mapa.php",
					data: {searchTable:searchTable,mapa:mapa},
					async: true,
					success: function(data) {
						$("#mapaLoad").html(data);
					}
				});

			});

			$('#log_clicks-tabla').css( 'display', 'table' );
			$('#log_clicks-tabla').resize();
			$('#log_clicks-tabla').DataTable().columns.adjust().responsive.recalc();
			$("#log_clicks-tabla_filter").css("display","none");  // hiding global search box
			$('#selectCheckbox').on("click", function(event){ // triggering delete one by one
				if( $('.checkselected:checked').length > 0 ){  // at-least one checkbox checked
					var ids = [];
					$('.checkselected').each(function(){
						if($(this).is(':checked')){ 
							ids.push($(this).val());
						}
					});
					var ids_string = ids.toString();  // array to string conversion   
					var link="logClicks/index.php?cot="+ids_string;   
					var link2="logClicks/index.php";
					dataString = 'urlink='+link2;  
					$.ajax({
						type: "POST",
						url: "functions/backarray.php",
						data: dataString,
						success: function(data) { 	}
					});
					////
					$("#homebody").load(link); 
				}
			});
			$('#log_clicks-tabla').on( 'page.dt', function () {
				var info = dataTable.page.info();
				//console.log('Showing page: '+info.page+' of '+info.pages );
				//mostrar en mapa 
				var city = document.getElementById("city").value;
				var region = document.getElementById("region").value;
				var country = document.getElementById("country").value;
				var fecha_1 = document.getElementById("fecha_1").value;
				var fecha_2 = document.getElementById("fecha_2").value;
				var os = document.getElementById("os").value;
				var browser = document.getElementById("browser").value;
				var searchTable = [];
				var data = {   
						'city' : city,
						'region' : region,
						'country' : country,
						'fecha_1' : fecha_1,
						'fecha_2' : fecha_2,
						'os' : os,
						'browser' : browser,
					}
				searchTable.push(data);
				var mapa = [];
				var data = {
						'pagina' : info.page,
				}
				mapa.push(data);
				$.ajax({
					type: "POST",
					url: "logClicks/mapa.php",
					data: {searchTable:searchTable,mapa:mapa},
					async: true,
					success: function(data) {
						$("#mapaLoad").html(data);
					}
				});
				//return false;
			} );
		});
	</script> 
	<table id="log_clicks-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
		<thead>
			<!--
			<tr>
				<th>Fecha Registro</th>
				<th>Tipo</th>
				<th>Distancia(KM)</th>
				<th>Not. Distancia</th>
				<th>Not. Contagio</th>
				<th>Nombre Ciudadano</th>
				<th>Nombre Agente</th>
				<th>Location GPS</th>
				<th>Dirección Completa</th>
				<th>Ciudad</th>
				<th>Region</th>
				<th>País</th>
				<th>OS</th>
				<th>IP</th> 
			</tr>-->
			<tr>
			<?php
			$columna= array(
				'fechaR',
				'server_name',
				'os',
				'browser',
			    'ip',
			    'fbclid',
			    'loc',
			    'loc_script',
			    'ip_type',
			    'type',
			    'direccion_completa',
			    'hostname',
			    'isp',
			    'org',
			    'domain',
			    'user_agent'
			);

			$columnaTipo= array(
				'fecha',
				'texto',
			);

			$_SESSION['reporte_Sistema']['nombres']=$columna;
			$_SESSION['reporte_Sistema']['nombres_tipo']=$columnaTipo;
			$_SESSION['reporte_Sistema']['nombres_excel']="Reporte Pages Tracking";
			foreach ($columna as $key => $value) {
				echo '<th >'.$value.'</th>';
			}
			//echo '<th >Opciones</th>';
			?>
			</tr>
		</thead>
	</table>
