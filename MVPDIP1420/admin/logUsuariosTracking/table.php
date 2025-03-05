<?php
	include __DIR__."/../functions/security.php";
	@session_start();
	if (!empty($_POST)) {
		foreach ($_POST['searchTable'][0] as $key => $value) {
			$escapedValue = mysqli_real_escape_string($conexion, $value);
			$_POST['searchTable'][0][$key] = $escapedValue;
		}
		$postData = json_encode($_POST);
	}else{
		$postData = "''";
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
			var dataTable = $('#log_usuarios_tracking-tabla').DataTable( {
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
								{ "bSortable": false, "aTargets": [ 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21 ] }
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
					url :"logUsuariosTracking/log_usuarios_tracking.php", // json datasource
					type: "post",  // method  , by default get
					data: {
						postData: <?php echo $postData; ?>
					},
					error: function(){  // error handling
						$(".log_usuarios_tracking-tabla-error").html("");
						$("#log_usuarios_tracking-tabla").append('<tbody class="log_usuarios_tracking-tabla-error"><tr><th colspan="8">Registros no encontrados</th></tr></tbody>');
						$("#log_usuarios_tracking-tabla_processing").css("display","none");
						
					}
				}
			});

			$('#log_usuarios_tracking-tabla').on( 'order.dt', function () {
				var table = $('#log_usuarios_tracking-tabla').dataTable();
				var api = table.api();
				var order = table.api().order(); // this has column and order details 
				//console.log(order[0][0]);
				//console.log(order[0][1]);
				//mostrar en mapa 
				var fecha_1 = document.getElementById("fecha_1").value;
				var fecha_2 = document.getElementById("fecha_2").value;
				var id_usuario = document.getElementById("id_usuario").value;
				var tipo_usuario = document.getElementById("tipo_usuario").value;
				var alerta = document.getElementById("alerta").value;
				var searchTable = [];
				var data = {   
					'fecha_1' : fecha_1,
					'fecha_2' : fecha_2,
					'id_usuario' : id_usuario,
					'tipo_usuario' : tipo_usuario,
					'alerta' : alerta,
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
					url: "logUsuariosTracking/mapa.php",
					data: {searchTable:searchTable,mapa:mapa},
					async: true,
					success: function(data) {
						$("#mapaLoad").html(data);
					}
				});

			});

			$('#log_usuarios_tracking-tabla').css( 'display', 'table' );
			$('#log_usuarios_tracking-tabla').resize();
			$('#log_usuarios_tracking-tabla').DataTable().columns.adjust().responsive.recalc();
			$("#log_usuarios_tracking-tabla_filter").css("display","none");  // hiding global search box
			$('#selectCheckbox').on("click", function(event){ // triggering delete one by one
				if( $('.checkselected:checked').length > 0 ){  // at-least one checkbox checked
					var ids = [];
					$('.checkselected').each(function(){
						if($(this).is(':checked')){ 
							ids.push($(this).val());
						}
					});
					var ids_string = ids.toString();  // array to string conversion   
					var link="logUsuariosTracking/index.php?cot="+ids_string;   
					var link2="logUsuariosTracking/index.php";
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
			$('#log_usuarios_tracking-tabla').on( 'page.dt', function () {
				var info = dataTable.page.info();
				//console.log('Showing page: '+info.page+' of '+info.pages );
				//mostrar en mapa 
				var fecha_1 = document.getElementById("fecha_1").value;
				var fecha_2 = document.getElementById("fecha_2").value;
				var id_usuario = document.getElementById("id_usuario").value;
				var tipo_usuario = document.getElementById("tipo_usuario").value;
				var alerta = document.getElementById("alerta").value;
				var searchTable = [];
				var data = {   
						'fecha_1' : fecha_1,
						'fecha_2' : fecha_2,
						'id_usuario' : id_usuario,
						'tipo_usuario' : tipo_usuario,
						'alerta' : alerta,
					}
				searchTable.push(data);
				var mapa = [];
				var data = {
						'pagina' : info.page,
				}
				mapa.push(data);
				$.ajax({
					type: "POST",
					url: "logUsuariosTracking/mapa.php",
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
	<table id="log_usuarios_tracking-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
		<thead>
			<tr>
				<th>fechaR</th>
				<th>usuario</th>
				<th>tipo_usuario</th>
				<th>server_name</th>
				<th>request_uri</th>
				<th>script_name</th>
				<th>nombre_completo</th>
				<th>Alerta</th>
				<th>Alerta M.</th>
				<th>Paguinasub</th>
				<th>paguinaId</th>
				<th>os</th>
				<th>browser</th>
				<th>ip</th>
				<th>loc</th>
				<th>loc_script</th>
				<th>ip_type</th>
				<th>type</th>
				<th>direccion_completa</th>
				<th>hostname</th>
				<th>isp</th>
				<th>org</th>
				<th>domain</th>
				<th>user_agent</th>
			</tr>
		</thead>
	</table>
