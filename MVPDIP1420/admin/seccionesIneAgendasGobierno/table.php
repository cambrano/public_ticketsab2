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
			var dataTable = $('#secciones_ine_agendas_gobierno-tabla').DataTable( {
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
								{ "bSortable": false, "aTargets": [ 15 ] }
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
					url :"seccionesIneAgendasGobierno/secciones_ine_agendas_gobierno.php", // json datasource
					type: "post",  // method  , by default get
					data: {
						postData: <?php echo $postData; ?>
					},
					error: function(){  // error handling
						$(".secciones_ine_agendas_gobierno-tabla-error").html("");
						$("#secciones_ine_agendas_gobierno-tabla").append('<tbody class="secciones_ine_agendas_gobierno-tabla-error"><tr><th colspan="8">Registros no encontrados</th></tr></tbody>');
						$("#secciones_ine_agendas_gobierno-tabla_processing").css("display","none");
						
					}
				}
			});


			$('#secciones_ine_agendas_gobierno-tabla').on( 'order.dt', function () {
				var table = $('#secciones_ine_agendas_gobierno-tabla').dataTable();
				var api = table.api();
				var order = table.api().order(); // this has column and order details 
				//console.log(order[0][0]);
				//console.log(order[0][1]);
				var clave = document.getElementById("clave").value;
				var folio = document.getElementById("folio").value;
				var nombre = document.getElementById("nombre").value;

				/*
				var tipo_input = document.getElementById("tipo");
				var tipo_array = [];
				for (var i = 0; i < tipo_input.length; i++) {
					if (tipo_input.options[i].selected){
						tipo_array.push("'"+tipo_input.options[i].value+"'");
					}
				}
				tipo = tipo_array.join(",");
				*/

				var id_tipo_gira_input = document.getElementById("id_tipo_gira");
				var id_tipo_gira_array = [];
				for (var i = 0; i < id_tipo_gira_input.length; i++) {
					if (id_tipo_gira_input.options[i].selected){
						id_tipo_gira_array.push("'"+id_tipo_gira_input.options[i].value+"'");
					}
				}
				id_tipo_gira = id_tipo_gira_array.join(",");

				var fecha_1 = document.getElementById("fecha_1").value;
				var fecha_2 = document.getElementById("fecha_2").value;

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
				
				var id_dependencias_input = document.getElementById("id_dependencias");
				var id_dependencias_array = [];
				for (var i = 0; i < id_dependencias_input.length; i++) {
					if (id_dependencias_input.options[i].selected){
						id_dependencias_array.push("'"+id_dependencias_input.options[i].value+"'");
					}
				}
				id_dependencias = id_dependencias_array.join(",");

				var id_dependencias_colaborativas_input = document.getElementById("id_dependencias_colaborativas");
				var id_dependencias_colaborativas_array = [];
				for (var i = 0; i < id_dependencias_colaborativas_input.length; i++) {
					if (id_dependencias_colaborativas_input.options[i].selected){
						id_dependencias_colaborativas_array.push("'"+id_dependencias_colaborativas_input.options[i].value+"'");
					}
				}
				id_dependencias_colaborativas = id_dependencias_colaborativas_array.join(",");

				var id_dependencias_general_input = document.getElementById("id_dependencias_general");
				var id_dependencias_general_array = [];
				for (var i = 0; i < id_dependencias_general_input.length; i++) {
					if (id_dependencias_general_input.options[i].selected){
						id_dependencias_general_array.push("'"+id_dependencias_general_input.options[i].value+"'");
					}
				}
				id_dependencias_general = id_dependencias_general_array.join(",");

				var id_eje_gobierno = document.getElementById("id_eje_gobierno").value;

				var searchTable = [];
				var data = {   
					'clave' : clave,
					'folio' : folio,
					'nombre' : nombre,
					'id_tipo_gira' : id_tipo_gira,
					'fecha_1' : fecha_1,
					'fecha_2' : fecha_2,
					'id_seccion_ine' :id_seccion_ine,
					'id_distrito_local' : id_distrito_local,
					'id_distrito_federal' : id_distrito_federal,
					'id_municipio' : id_municipio,
					'id_localidad' : id_localidad,
					'id_dependencias' : id_dependencias,
					'id_dependencias_colaborativas' : id_dependencias_colaborativas,
					'id_dependencias_general' : id_dependencias_general,
					'id_eje_gobierno' : id_eje_gobierno,
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
					url: "seccionesIneAgendasGobierno/mapa.php",
					data: {searchTable:searchTable,mapa:mapa},
					async: true,
					success: function(data) {
						$("#mapaLoad").html(data);
					}
				});
			});
			$('#secciones_ine_agendas_gobierno-tabla').css( 'display', 'table' );
			$('#secciones_ine_agendas_gobierno-tabla').resize();
			$('#secciones_ine_agendas_gobierno-tabla').DataTable().columns.adjust().responsive.recalc();
			$("#secciones_ine_agendas_gobierno-tabla_filter").css("display","none");  // hiding global search box
			$('#selectCheckbox').on("click", function(event){ // triggering delete one by one
				if( $('.checkselected:checked').length > 0 ){  // at-least one checkbox checked
					var ids = [];
					$('.checkselected').each(function(){
						if($(this).is(':checked')){ 
							ids.push($(this).val());
						}
					});
					var ids_string = ids.toString();  // array to string conversion   
					var link="seccionesIneAgendasGobierno/index.php?cot="+ids_string;   
					var link2="seccionesIneAgendasGobierno/index.php";
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

			$('#secciones_ine_agendas_gobierno-tabla').on( 'page.dt', function () {
				var info = dataTable.page.info();
				//mostrar en mapa 
				var clave = document.getElementById("clave").value;
				var folio = document.getElementById("folio").value;
				var nombre = document.getElementById("nombre").value;


				/*
				var tipo_input = document.getElementById("tipo");
				var tipo_array = [];
				for (var i = 0; i < tipo_input.length; i++) {
					if (tipo_input.options[i].selected){
						tipo_array.push("'"+tipo_input.options[i].value+"'");
					}
				}
				tipo = tipo_array.join(",");
				*/

				var id_tipo_gira_input = document.getElementById("id_tipo_gira");
				var id_tipo_gira_array = [];
				for (var i = 0; i < id_tipo_gira_input.length; i++) {
					if (id_tipo_gira_input.options[i].selected){
						id_tipo_gira_array.push("'"+id_tipo_gira_input.options[i].value+"'");
					}
				}
				id_tipo_gira = id_tipo_gira_array.join(",");

				var fecha_1 = document.getElementById("fecha_1").value;
				var fecha_2 = document.getElementById("fecha_2").value;

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

				var id_dependencias_input = document.getElementById("id_dependencias");
				var id_dependencias_array = [];
				for (var i = 0; i < id_dependencias_input.length; i++) {
					if (id_dependencias_input.options[i].selected){
						id_dependencias_array.push("'"+id_dependencias_input.options[i].value+"'");
					}
				}
				id_dependencias = id_dependencias_array.join(",");

				var id_dependencias_colaborativas_input = document.getElementById("id_dependencias_colaborativas");
				var id_dependencias_colaborativas_array = [];
				for (var i = 0; i < id_dependencias_colaborativas_input.length; i++) {
					if (id_dependencias_colaborativas_input.options[i].selected){
						id_dependencias_colaborativas_array.push("'"+id_dependencias_colaborativas_input.options[i].value+"'");
					}
				}
				id_dependencias_colaborativas = id_dependencias_colaborativas_array.join(",");

				var id_dependencias_general_input = document.getElementById("id_dependencias_general");
				var id_dependencias_general_array = [];
				for (var i = 0; i < id_dependencias_general_input.length; i++) {
					if (id_dependencias_general_input.options[i].selected){
						id_dependencias_general_array.push("'"+id_dependencias_general_input.options[i].value+"'");
					}
				}
				id_dependencias_general = id_dependencias_general_array.join(",");

				var id_eje_gobierno = document.getElementById("id_eje_gobierno").value;

				var searchTable = [];
				var data = {   
					'clave' : clave,
					'folio' : folio,
					'id_tipo_gira' : id_tipo_gira,
					'nombre' : nombre,
					'fecha_1' : fecha_1,
					'fecha_2' : fecha_2,
					'id_seccion_ine' :id_seccion_ine,
					'id_distrito_local' : id_distrito_local,
					'id_distrito_federal' : id_distrito_federal,
					'id_municipio' : id_municipio,
					'id_localidad' : id_localidad,
					'id_dependencias' : id_dependencias,
					'id_dependencias_colaborativas' : id_dependencias_colaborativas,
					'id_dependencias_general' : id_dependencias_general,
					'id_eje_gobierno' : id_eje_gobierno,

				}
				searchTable.push(data);
				var mapa = [];
				var data = {
						'pagina' : info.page,
					}
				mapa.push(data);
				$.ajax({
					type: "POST",
					url: "seccionesIneAgendasGobierno/mapa.php",
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
			link="seccionesIneAgendasGobierno/update.php?id="+valor; 
			var link2="seccionesIneAgendasGobierno/update.php";
			dataString = 'urlink='+link2;  
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {}
				});
			//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
			$("#homebody").load(link+"&refresh=1");
			//$("#homebody").load(link);
		}

		function add(){	
			////ajax
			link="seccionesIneAgendasGobierno/create.php";
			var link2="seccionesIneAgendasGobierno/create.php";
			dataString = 'urlink='+link2;  
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			////
			$("#homebody").load(link+"?refresh=1");
			//$("#homebody").load(link);
		}

		function borrar(valor){
			link="seccionesIneAgendasGobierno/delete.php?id="+valor; 
			var link2="seccionesIneAgendasGobierno/delete.php";
			dataString = 'urlink='+link2; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {}
			});
			//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
			$("#homebody").load(link+"&refresh=1");
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
			var clave = document.getElementById("clave").value;
			var folio = document.getElementById("folio").value;
			var nombre = document.getElementById("nombre").value;

			/*
			var tipo_input = document.getElementById("tipo");
			var tipo_array = [];
			for (var i = 0; i < tipo_input.length; i++) {
				if (tipo_input.options[i].selected){
					tipo_array.push("'"+tipo_input.options[i].value+"'");
				}
			}
			tipo = tipo_array.join(",");
			*/

			var id_tipo_gira_input = document.getElementById("id_tipo_gira");
			var id_tipo_gira_array = [];
			for (var i = 0; i < id_tipo_gira_input.length; i++) {
				if (id_tipo_gira_input.options[i].selected){
					id_tipo_gira_array.push("'"+id_tipo_gira_input.options[i].value+"'");
				}
			}
			id_tipo_gira = id_tipo_gira_array.join(",");

			var fecha_1 = document.getElementById("fecha_1").value;
			var fecha_2 = document.getElementById("fecha_2").value;



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

			var id_dependencias_input = document.getElementById("id_dependencias");
			var id_dependencias_array = [];
			for (var i = 0; i < id_dependencias_input.length; i++) {
				if (id_dependencias_input.options[i].selected){
					id_dependencias_array.push("'"+id_dependencias_input.options[i].value+"'");
				}
			}
			id_dependencias = id_dependencias_array.join(",");

			var id_dependencias_colaborativas_input = document.getElementById("id_dependencias_colaborativas");
			var id_dependencias_colaborativas_array = [];
			for (var i = 0; i < id_dependencias_colaborativas_input.length; i++) {
				if (id_dependencias_colaborativas_input.options[i].selected){
					id_dependencias_colaborativas_array.push("'"+id_dependencias_colaborativas_input.options[i].value+"'");
				}
			}
			id_dependencias_colaborativas = id_dependencias_colaborativas_array.join(",");

			var id_dependencias_general_input = document.getElementById("id_dependencias_general");
			var id_dependencias_general_array = [];
			for (var i = 0; i < id_dependencias_general_input.length; i++) {
				if (id_dependencias_general_input.options[i].selected){
					id_dependencias_general_array.push("'"+id_dependencias_general_input.options[i].value+"'");
				}
			}
			id_dependencias_general = id_dependencias_general_array.join(",");

			var id_eje_gobierno = document.getElementById("id_eje_gobierno").value;


			var searchTable = [];
			var data = {
					'clave' : clave,
					'folio' : folio,
					'id_tipo_gira' : id_tipo_gira,
					'nombre' : nombre,
					'fecha_1' : fecha_1,
					'fecha_2' : fecha_2,
					'id_seccion_ine' :id_seccion_ine,
					'id_distrito_local' : id_distrito_local,
					'id_distrito_federal' : id_distrito_federal,
					'id_municipio' : id_municipio,
					'id_localidad' : id_localidad,
					'id_dependencias' : id_dependencias,
					'id_dependencias_colaborativas' : id_dependencias_colaborativas,
					'id_dependencias_general' : id_dependencias_general,
					'id_eje_gobierno' : id_eje_gobierno,
				}
			searchTable.push(data);
			// Crear un formulario oculto en el documento
			var form = document.createElement('form');
			form.method = 'POST';
			form.action = 'seccionesIneAgendasGobierno/excel/index.php?cot=<?=$_COOKIE['pageService']?>'; // URL de destino

			// Iterar a través del objeto data y crear campos de entrada
			for (var key in data) {
				if (data.hasOwnProperty(key)) {
					var input = document.createElement('input');
					input.type = 'hidden';
					input.name = 'form_excel_' + key; // Agrega el prefijo "form_excel_" al nombre
					input.id = 'form_excel_' + key; // Agrega el prefijo "form_excel_" al ID
					input.value = data[key]; // Asigna el valor desde el objeto data

					// Agregar el campo de entrada al formulario
					form.appendChild(input);
				}
			}
			// Agregar el formulario al cuerpo del documento (opcional)
			document.body.appendChild(form);
			// Función para abrir la nueva página y enviar el formulario
			function openNewPageAndSubmitForm() {
				// Abre una nueva página
				var nuevaVentana = window.open('about:blank');
				
				// Asigna el formulario al contenido de la nueva ventana
				nuevaVentana.document.body.appendChild(form);
				
				// Enviar el formulario en la nueva ventana
				form.submit();
			}
			// Llamar a la función para abrir la nueva página y enviar el formulario
			openNewPageAndSubmitForm();
			//window.open(link);
			//window.open(link,'newWindow','width=1280, height=460'); return false;
			//document.location = link;
			//!window.open(link); 
		}
		function secciones_ine_ciudadanos_giras_totales(valor){
			link="seccionesIneCiudadanosGirasTotales/index.php?cot="+valor; 
			var link2="seccionesIneCiudadanosGirasTotales/index.php";
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
	<table id="secciones_ine_agendas_gobierno-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
		<thead>
			<tr>
			<th>Clave</th>
			<th>Tipo Agenda</th>
			<th>Dependencia Coord</th>
			<th>Eje Gobierno</th>
			<th>Nombre</th>
			<th>Fecha Hora</th>
			<th>Num Beneficiarios</th>
			<th>Num Asistentes</th>
			<th>Observaciones</th>
			<th>Municipio</th>
			<th>Localidad</th>
			<th>Colonia</th>
			<th>Sección</th>
			<th>Distrito Local</th>
			<th>Distrito Federal</th>
			<th>Opciones</th>
			</tr>
		</thead> 
	</table>  
