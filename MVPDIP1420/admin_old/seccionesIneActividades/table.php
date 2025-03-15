<?php
	include __DIR__.'/../functions/security.php';
	@session_start();
	if(!empty($_POST)){
		/*include '../functions/secciones_ine_actividades.php';*/
		foreach ($_POST['searchTable'][0] as $key => $value) {
			if($key == "tipo"){
				$value = str_replace("'", "", $value);
			}
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
			var dataTable = $('#secciones_ine_actividades-tabla').DataTable( {
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
								{ "bSortable": false, "aTargets": [ '<?= count($_SESSION['reporte_Sistema']['columnas_sql']) ?>' ] }
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
					url :"seccionesIneActividades/secciones_ine_actividades.php", // json datasource
					type: "post",  // method  , by default get
					error: function(){  // error handling
						$(".secciones_ine_actividades-tabla-error").html("");
						$("#secciones_ine_actividades-tabla").append('<tbody class="secciones_ine_actividades-tabla-error"><tr><th colspan="8">Registros no encontrados</th></tr></tbody>');
						$("#secciones_ine_actividades-tabla_processing").css("display","none");
						
					}
				}
			});


			$('#secciones_ine_actividades-tabla').on( 'order.dt', function () {
				var table = $('#secciones_ine_actividades-tabla').dataTable();
				var api = table.api();
				var order = table.api().order(); // this has column and order details 
				//console.log(order[0][0]);
				//console.log(order[0][1]);
				var clave = document.getElementById("clave").value;
				var folio = document.getElementById("folio").value;
				var nombre = document.getElementById("nombre").value;

				var cedula = document.getElementById("cedula").value;
				var numero_contrato = document.getElementById("numero_contrato").value;

				var tipo_input = document.getElementById("tipo");
				var tipo_array = [];
				for (var i = 0; i < tipo_input.length; i++) {
					if (tipo_input.options[i].selected){
						tipo_array.push("'"+tipo_input.options[i].value+"'");
					}
				}
				tipo = tipo_array.join(",");

				var id_tipo_infraestructura_input = document.getElementById("id_tipo_infraestructura");
				var id_tipo_infraestructura_array = [];
				for (var i = 0; i < id_tipo_infraestructura_input.length; i++) {
					if (id_tipo_infraestructura_input.options[i].selected){
						id_tipo_infraestructura_array.push(id_tipo_infraestructura_input.options[i].value);
					}
				}
				id_tipo_infraestructura = id_tipo_infraestructura_array.join(",");


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
					'clave' : clave,
					'folio' : folio,
					'nombre' : nombre,
					'tipo' : tipo,
					'id_tipo_infraestructura' : id_tipo_infraestructura,
					'id_seccion_ine' :id_seccion_ine,
					'id_distrito_local' : id_distrito_local,
					'id_distrito_federal' : id_distrito_federal,
					'id_municipio' : id_municipio,
					'id_localidad' : id_localidad,

					'cedula' : cedula,
					'numero_contrato' : numero_contrato,
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
					url: "seccionesIneActividades/mapa.php",
					data: {searchTable:searchTable,mapa:mapa},
					async: true,
					success: function(data) {
						$("#mapaLoad").html(data);
					}
				});
			});
			$('#secciones_ine_actividades-tabla').css( 'display', 'table' );
			$('#secciones_ine_actividades-tabla').resize();
			$('#secciones_ine_actividades-tabla').DataTable().columns.adjust().responsive.recalc();
			$("#secciones_ine_actividades-tabla_filter").css("display","none");  // hiding global search box
			$('#selectCheckbox').on("click", function(event){ // triggering delete one by one
				if( $('.checkselected:checked').length > 0 ){  // at-least one checkbox checked
					var ids = [];
					$('.checkselected').each(function(){
						if($(this).is(':checked')){ 
							ids.push($(this).val());
						}
					});
					var ids_string = ids.toString();  // array to string conversion   
					var link="seccionesIneActividades/index.php?cot="+ids_string;   
					var link2="seccionesIneActividades/index.php";
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

			$('#secciones_ine_actividades-tabla').on( 'page.dt', function () {
				var info = dataTable.page.info();
				//mostrar en mapa 
				var clave = document.getElementById("clave").value;
				var folio = document.getElementById("folio").value;
				var nombre = document.getElementById("nombre").value;

				var cedula = document.getElementById("cedula").value;
				var numero_contrato = document.getElementById("numero_contrato").value;

				var tipo_input = document.getElementById("tipo");
				var tipo_array = [];
				for (var i = 0; i < tipo_input.length; i++) {
					if (tipo_input.options[i].selected){
						tipo_array.push("'"+tipo_input.options[i].value+"'");
					}
				}
				tipo = tipo_array.join(",");

				var id_tipo_infraestructura_input = document.getElementById("id_tipo_infraestructura");
				var id_tipo_infraestructura_array = [];
				for (var i = 0; i < id_tipo_infraestructura_input.length; i++) {
					if (id_tipo_infraestructura_input.options[i].selected){
						id_tipo_infraestructura_array.push(id_tipo_infraestructura_input.options[i].value);
					}
				}
				id_tipo_infraestructura = id_tipo_infraestructura_array.join(",");


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
					'clave' : clave,
					'folio' : folio,
					'nombre' : nombre,
					'tipo' : tipo,
					'id_tipo_infraestructura' : id_tipo_infraestructura,
					'id_seccion_ine' :id_seccion_ine,
					'id_distrito_local' : id_distrito_local,
					'id_distrito_federal' : id_distrito_federal,
					'id_municipio' : id_municipio,
					'id_localidad' : id_localidad,

					'cedula' : cedula,
					'numero_contrato' : numero_contrato,
				}
				searchTable.push(data);
				var mapa = [];
				var data = {
						'pagina' : info.page,
					}
				mapa.push(data);
				$.ajax({
					type: "POST",
					url: "seccionesIneActividades/mapa.php",
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
			link="seccionesIneActividades/update.php?id="+valor; 
			var link2="seccionesIneActividades/update.php";
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
			link="seccionesIneActividades/create.php";
			var link2="seccionesIneActividades/create.php";
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
			link="seccionesIneActividades/delete.php?id="+valor; 
			var link2="seccionesIneActividades/delete.php";
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
		function printPdf(valor){
			var link="seccionesIneActividades/print/index.php?cot=<?=$_SESSION['pageService']?>"; 
			window.open(link,'_blank'); return false;
			/*
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {}
				 });
			//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
			*/
			//$("#homebody").load(link);
		}
		function downloadExcel(){
			<?php
			if($pageService != $_COOKIE["pageService"]){
				if($pageService!=''){
					$_COOKIE["pageService"] = $pageService ;
				}
			}
			?>
			var link="seccionesIneActividades/excel/index.php?cot=<?=$_COOKIE['pageService']?>"; 
			//window.open(link);
			//window.open(link,'newWindow','width=1280, height=460'); return false;
			window.open(link); 
		}
	</script> 
	<table id="secciones_ine_actividades-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
		<thead>
			<tr>
			<?php
				foreach ($_SESSION['reporte_Sistema']['columnas_nombres'] as $key => $value) {
					if($value['mostrar']==1){
						echo "<th>".$value['nombre']."</th>";
					}
				}
			?>
			<th>Opciones</th>
			</tr>
		</thead> 
	</table>  
