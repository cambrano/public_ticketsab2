<?php
	include __DIR__.'/../functions/security.php';
	@session_start();
	if(!empty($_POST)){
		include '../functions/distritos_federales.php';
		foreach ($_POST['searchTable'][0] as $key => $value) {
			$_SESSION['searchTable'][$key] = mysqli_real_escape_string($conexion,$value);
		}
	}

	if($_POST['mapa'][0]['order']==""){
		$order =0;
	}
	if($_POST['mapa'][0]['order_tipo']==""){
		$order_tipo ="desc";
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
			var dataTable = $('#distritos_federales-tabla').DataTable( {
				"destroy": true,
				"responsive": responsive,
				"pageLength": 100,
				"retrieve": true,
				"info": true,
				"processing": true,
				"sPaginationType": "full_numbers", 
				"fixedHeader": true,
				"fixedHeader": {
					header: true,
				},
				"order": [[ <?= $order ?>, "<?= $order_tipo ?>" ]],
				"ordering": true,
				"searching": false,/* para el buscador*/
				"paging": true,/* para la paginacion y todo salga en una sola hora*/
				"aoColumnDefs": [
								{ "bSortable": false, "aTargets": [ 4 ] }
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
					url :"distritosFederales/distritos_federales.php", // json datasource
					type: "post",  // method  , by default get
					error: function(){  // error handling
						$(".distritos_federales-tabla-error").html("");
						$("#distritos_federales-tabla").append('<tbody class="distritos_federales-tabla-error"><tr><th colspan="8">Registros no encontrados</th></tr></tbody>');
						$("#distritos_federales-tabla_processing").css("display","none");
						
					}
				}
			});


			$('#distritos_federales-tabla').on( 'order.dt', function () {
				var table = $('#distritos_federales-tabla').dataTable();
				var api = table.api();
				var order = table.api().order(); // this has column and order details 
				//console.log(order[0][0]);
				//console.log(order[0][1]);
				var clave = document.getElementById("clave").value;
				var numero = document.getElementById("numero").value;
				var searchTable = [];
				var data = {   
					'clave' : clave, 
					'numero' : numero,
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
					url: "distritosFederales/mapa.php",
					data: {searchTable:searchTable,mapa:mapa},
					async: true,
					success: function(data) {
						$("#mapaLoad").html(data);
					}
				});

			});

			$('#distritos_federales-tabla').css( 'display', 'table' );
			$('#distritos_federales-tabla').resize();
			$('#distritos_federales-tabla').DataTable().columns.adjust().responsive.recalc();
			$("#distritos_federales-tabla_filter").css("display","none");  // hiding global search box
			$('#selectCheckbox').on("click", function(event){ // triggering delete one by one
				if( $('.checkselected:checked').length > 0 ){  // at-least one checkbox checked
					var ids = [];
					$('.checkselected').each(function(){
						if($(this).is(':checked')){ 
							ids.push($(this).val());
						}
					});
					var ids_string = ids.toString();  // array to string conversion   
					var link="distritosFederales/index.php?cot="+ids_string;   
					var link2="distritosFederales/index.php";
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

			$('#distritos_federales-tabla').on( 'page.dt', function () {
				var info = dataTable.page.info();
				//mostrar en mapa 
				var clave = document.getElementById("clave").value;
				var numero = document.getElementById("numero").value;
				var searchTable = [];
				var data = {   
					'clave' : clave, 
					'numero' : numero,
				}
				searchTable.push(data);
				var mapa = [];
				var data = {
						'pagina' : info.page,
					}
				mapa.push(data);
				$.ajax({
					type: "POST",
					url: "distritosFederales/mapa.php",
					data: {searchTable:searchTable,mapa:mapa},
					async: true,
					success: function(data) {
						$("#mapaLoad").html(data);
					}
				});
				//return false;
			});

		});
		function edit(valor){
			link="distritosFederales/update.php?id="+valor; 
			var link2="distritosFederales/update.php";
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

		function add(){	
			////ajax
			link="distritosFederales/create.php";
			var link2="distritosFederales/create.php";
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

		function borrar(valor){
			link="distritosFederales/delete.php?id="+valor; 
			var link2="distritosFederales/delete.php";
			dataString = 'urlink='+link2; 
			/*
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {}
				 });
			//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
			*/
			$("#homebody").load(link);
		}
	</script> 
	<table id="distritos_federales-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
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
				'clave',
				'numero',
				'latitud',
				'longitud',
			);

			$columnaTipo= array(
				'clave',
				'numero',
				'texto',
				'texto',
			);

			$_SESSION['reporte_Sistema']['nombres']=$columna;
			$_SESSION['reporte_Sistema']['nombres_tipo']=$columnaTipo;
			$_SESSION['reporte_Sistema']['nombres_excel']="Reporte Pages Tracking";
			foreach ($columna as $key => $value) {
				echo '<th >'.$value.'</th>';
			}
			echo '<th >Opciones</th>';
			?>
			</tr>
	</table>  
