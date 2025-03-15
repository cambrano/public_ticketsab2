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
			var dataTable = $('#secciones_ine_ciudadanos_campanas_mailing_programadas-tabla').DataTable( {
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
								{ "bSortable": false, "aTargets": [ 14 ] }
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
					url :"seccionesIneCiudadanosCampanasMailingProgramadas/secciones_ine_ciudadanos_campanas_mailing_programadas.php", // json datasource
					type: "post",  // method  , by default get
					data: {
						postData: <?php echo $postData; ?>
					},
					error: function(){  // error handling
						$(".secciones_ine_ciudadanos_campanas_mailing_programadas-tabla-error").html("");
						$("#secciones_ine_ciudadanos_campanas_mailing_programadas-tabla").append('<tbody class="secciones_ine_ciudadanos_campanas_mailing_programadas-tabla-error"><tr><th colspan="8">Registros no encontrados</th></tr></tbody>');
						$("#secciones_ine_ciudadanos_campanas_mailing_programadas-tabla_processing").css("display","none");
						
					}
				}
			} );

			$('#secciones_ine_ciudadanos_campanas_mailing_programadas-tabla').on( 'order.dt', function () {
				var table = $('#secciones_ine_ciudadanos_campanas_mailing_programadas-tabla').dataTable();
				var api = table.api();
				var order = table.api().order(); // this has column and order details 
				//console.log(order[0][0]);
				//console.log(order[0][1]);
				//mostrar en mapa 
				var id_seccion_ine_input = document.getElementById("id_seccion_ine");
				var id_seccion_ine_array = [];
				for (var i = 0; i < id_seccion_ine_input.length; i++) {
					if (id_seccion_ine_input.options[i].selected){
						id_seccion_ine_array.push(id_seccion_ine_input.options[i].value);
					}
				}
				id_seccion_ine = id_seccion_ine_array.join(",");
				var f_tipo = document.getElementById("f_tipo").value;
				var f_status = document.getElementById("f_status").value;

				var searchTable = [];
				var data = {
						'id_seccion_ine' : id_seccion_ine,
						'tipo' : f_tipo,
						'status' : f_status,
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
					url: "seccionesIneCiudadanosCampanasMailingProgramadas/mapa.php",
					data: {searchTable:searchTable,mapa:mapa},
					async: true,
					success: function(data) {
						$("#mapaLoad").html(data);
					}
				});
			});

			$('#secciones_ine_ciudadanos_campanas_mailing_programadas-tabla').css( 'display', 'table' );
			$('#secciones_ine_ciudadanos_campanas_mailing_programadas-tabla').resize();
			$('#secciones_ine_ciudadanos_campanas_mailing_programadas-tabla').DataTable().columns.adjust().responsive.recalc();
			$("#secciones_ine_ciudadanos_campanas_mailing_programadas-tabla_filter").css("display","none");  // hiding global search box

			$('#secciones_ine_ciudadanos_campanas_mailing_programadas-tabla').on( 'page.dt', function () {
				var info = dataTable.page.info();
				//mostrar en mapa 
				var id_seccion_ine_input = document.getElementById("id_seccion_ine");
				var id_seccion_ine_array = [];
				for (var i = 0; i < id_seccion_ine_input.length; i++) {
					if (id_seccion_ine_input.options[i].selected){
						id_seccion_ine_array.push(id_seccion_ine_input.options[i].value);
					}
				}
				id_seccion_ine = id_seccion_ine_array.join(",");
				var f_tipo = document.getElementById("f_tipo").value;
				var f_status = document.getElementById("f_status").value;

				var searchTable = [];
				var data = {   
					'id_seccion_ine' : id_seccion_ine,
					'tipo' : f_tipo,
					'status' : f_status,
				}
				searchTable.push(data);
				var mapa = [];
				var data = {
						'pagina' : info.page,
					}
				mapa.push(data);
				$.ajax({
					type: "POST",
					url: "seccionesIneCiudadanosCampanasMailingProgramadas/mapa.php",
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
			link="seccionesIneCiudadanosCampanasMailingProgramadas/update.php?id="+valor; 
			var link2="seccionesIneCiudadanosCampanasMailingProgramadas/update.php";
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
		function downloadExcel(){
			<?php
			if($pageService != $_COOKIE["pageService"]){
				if($pageService!=''){
					$_COOKIE["pageService"] = $pageService ;
				}
			}
			?>
			var id_seccion_ine_input = document.getElementById("id_seccion_ine");
			var id_seccion_ine_array = [];
			for (var i = 0; i < id_seccion_ine_input.length; i++) {
				if (id_seccion_ine_input.options[i].selected){
					id_seccion_ine_array.push(id_seccion_ine_input.options[i].value);
				}
			}
			id_seccion_ine = id_seccion_ine_array.join(",");
			var f_tipo = document.getElementById("f_tipo").value;
			var f_status = document.getElementById("f_status").value;

			var data = {
					'id_seccion_ine' : id_seccion_ine,
					'tipo' : f_tipo,
					'status' : f_status,
				}
			// Crear un formulario oculto en el documento
			var form = document.createElement('form');
			form.method = 'POST';
			form.action = 'seccionesIneCiudadanosCampanasMailingProgramadas/excel/index.php?cot=<?=$_COOKIE['pageService']?>'; // URL de destino

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
	</script> 
	<table id="secciones_ine_ciudadanos_campanas_mailing_programadas-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
		<thead>
			<tr>
				<th>Fecha Registro</th>
				<th>Tipo</th>
				<th>Campaña</th>
				<th>Ciudadano</th>
				<th>Correo Electrónico</th>
				<th>Envío</th>
				<th>Leído</th>
				<th>IP</th>
				<th>Municipio</th>
				<th>D. Local</th>
				<th>D. Federal</th>
				<th>Sección</th>
				<th>Loc IP</th>
				<th>Loc GPS</th>
				<th>Estatus</th>
				<th>Opciones</th>
			</tr>
		</thead> 
	</table>  
