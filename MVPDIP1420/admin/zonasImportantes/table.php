<?php
	include __DIR__.'/../functions/security.php';
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
			var dataTable = $('#zonas_importantes-tabla').DataTable( {
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
					url :"zonasImportantes/zonas_importantes.php", // json datasource
					type: "post",  // method  , by default get
					data: {
						postData: <?php echo $postData; ?>
					},
					error: function(){  // error handling
						$(".zonas_importantes-tabla-error").html("");
						$("#zonas_importantes-tabla").append('<tbody class="zonas_importantes-tabla-error"><tr><th colspan="8">Registros no encontrados</th></tr></tbody>');
						$("#zonas_importantes-tabla_processing").css("display","none");
						
					}
				}
			});


			$('#zonas_importantes-tabla').on( 'order.dt', function () {
				var table = $('#zonas_importantes-tabla').dataTable();
				var api = table.api();
				var order = table.api().order(); // this has column and order details 
				//console.log(order[0][0]);
				//console.log(order[0][1]);
				var tipo = document.getElementById("tipo").value;
				var nombre = document.getElementById("nombre").value;

				var id_seccion_ine_input = document.getElementById("id_seccion_ine");
				var id_seccion_ine_array = [];
				for (var i = 0; i < id_seccion_ine_input.length; i++) {
					if (id_seccion_ine_input.options[i].selected){
						id_seccion_ine_array.push(id_seccion_ine_input.options[i].value);
					}
				}
				id_seccion_ine = id_seccion_ine_array.join(",");

				var id_distrito_local_input = document.getElementById("id_distrito_local");
				var id_distrito_local_array = [];
				for (var i = 0; i < id_distrito_local_input.length; i++) {
					if (id_distrito_local_input.options[i].selected){
						id_distrito_local_array.push(id_distrito_local_input.options[i].value);
					}
				}
				id_distrito_local = id_distrito_local_array.join(",");

				var id_distrito_federal_input = document.getElementById("id_distrito_federal");
				var id_distrito_federal_array = [];
				for (var i = 0; i < id_distrito_federal_input.length; i++) {
					if (id_distrito_federal_input.options[i].selected){
						id_distrito_federal_array.push(id_distrito_federal_input.options[i].value);
					}
				}
				id_distrito_federal = id_distrito_federal_array.join(",");

				var id_municipio_input = document.getElementById("id_municipio");
				var id_municipio_array = [];
				for (var i = 0; i < id_municipio_input.length; i++) {
					if (id_municipio_input.options[i].selected){
						id_municipio_array.push(id_municipio_input.options[i].value);
					}
				}
				id_municipio = id_municipio_array.join(",");

				var id_localidad_input = document.getElementById("id_localidad");
				var id_localidad_array = [];
				for (var i = 0; i < id_localidad_input.length; i++) {
					if (id_localidad_input.options[i].selected){
						id_localidad_array.push(id_localidad_input.options[i].value);
					}
				}
				id_localidad = id_localidad_array.join(",");

				var searchTable = [];
				var data = {
						'tipo' : tipo,
						'nombre' : nombre,
						'id_seccion_ine' :id_seccion_ine,
						'id_distrito_local' : id_distrito_local,
						'id_distrito_federal' : id_distrito_federal,
						'id_municipio' : id_municipio,
						'id_localidad' : id_localidad,
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
					url: "zonasImportantes/mapa.php",
					data: {searchTable:searchTable,mapa:mapa},
					async: true,
					success: function(data) {
						$("#mapaLoad").html(data);
					}
				});
			});
			$('#zonas_importantes-tabla').css( 'display', 'table' );
			$('#zonas_importantes-tabla').resize();
			$('#zonas_importantes-tabla').DataTable().columns.adjust().responsive.recalc();
			$("#zonas_importantes-tabla_filter").css("display","none");  // hiding global search box
			$('#selectCheckbox').on("click", function(event){ // triggering delete one by one
				if( $('.checkselected:checked').length > 0 ){  // at-least one checkbox checked
					var ids = [];
					$('.checkselected').each(function(){
						if($(this).is(':checked')){ 
							ids.push($(this).val());
						}
					});
					var ids_string = ids.toString();  // array to string conversion   
					var link="zonasImportantes/index.php?cot="+ids_string;   
					var link2="zonasImportantes/index.php";
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

			$('#zonas_importantes-tabla').on( 'page.dt', function () {
				var info = dataTable.page.info();
				//mostrar en mapa 
				var tipo = document.getElementById("tipo").value;
				var nombre = document.getElementById("nombre").value;

				var id_seccion_ine_input = document.getElementById("id_seccion_ine");
				var id_seccion_ine_array = [];
				for (var i = 0; i < id_seccion_ine_input.length; i++) {
					if (id_seccion_ine_input.options[i].selected){
						id_seccion_ine_array.push(id_seccion_ine_input.options[i].value);
					}
				}
				id_seccion_ine = id_seccion_ine_array.join(",");

				var id_distrito_local_input = document.getElementById("id_distrito_local");
				var id_distrito_local_array = [];
				for (var i = 0; i < id_distrito_local_input.length; i++) {
					if (id_distrito_local_input.options[i].selected){
						id_distrito_local_array.push(id_distrito_local_input.options[i].value);
					}
				}
				id_distrito_local = id_distrito_local_array.join(",");

				var id_distrito_federal_input = document.getElementById("id_distrito_federal");
				var id_distrito_federal_array = [];
				for (var i = 0; i < id_distrito_federal_input.length; i++) {
					if (id_distrito_federal_input.options[i].selected){
						id_distrito_federal_array.push(id_distrito_federal_input.options[i].value);
					}
				}
				id_distrito_federal = id_distrito_federal_array.join(",");

				var id_municipio_input = document.getElementById("id_municipio");
				var id_municipio_array = [];
				for (var i = 0; i < id_municipio_input.length; i++) {
					if (id_municipio_input.options[i].selected){
						id_municipio_array.push(id_municipio_input.options[i].value);
					}
				}
				id_municipio = id_municipio_array.join(",");

				var id_localidad_input = document.getElementById("id_localidad");
				var id_localidad_array = [];
				for (var i = 0; i < id_localidad_input.length; i++) {
					if (id_localidad_input.options[i].selected){
						id_localidad_array.push(id_localidad_input.options[i].value);
					}
				}
				id_localidad = id_localidad_array.join(",");

				var searchTable = [];
				var data = {
						'tipo' : tipo,
						'nombre' : nombre,
						'id_seccion_ine' :id_seccion_ine,
						'id_distrito_local' : id_distrito_local,
						'id_distrito_federal' : id_distrito_federal,
						'id_municipio' : id_municipio,
						'id_localidad' : id_localidad,
					}
				searchTable.push(data);
				var mapa = [];
				var data = {
						'pagina' : info.page,
					}
				mapa.push(data);
				$.ajax({
					type: "POST",
					url: "zonasImportantes/mapa.php",
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
			link="zonasImportantes/update.php?id="+valor; 
			var link2="zonasImportantes/update.php";
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
			link="zonasImportantes/create.php";
			var link2="zonasImportantes/create.php";
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
			link="zonasImportantes/delete.php?id="+valor; 
			var link2="zonasImportantes/delete.php";
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
	<table id="zonas_importantes-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
		<thead>
			<tr>
				<th>Sección</th>
				<th>Nombre</th>
				<th>Tipo</th>
				<th>Municipio</th>
				<th>Opciones</th>
			</tr>
		</thead> 
	</table>  
