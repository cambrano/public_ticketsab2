<?php
	include __DIR__."/../../functions/security.php";
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
	if($id_seccion_ine !=""){
		$_POST['searchTable'][0]['id_seccion_ine'] = $id_seccion_ine;
		$_POST['searchTable'][0]['id_municipio'] = $id_municipio;
		$postData = json_encode($_POST);
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
			var dataTable = $('#secciones_ine_ciudadanos_encuestas-tabla').DataTable( {
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
								{ "bSortable": false, "aTargets": [ 8 ] }
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
					url :"encuestasSecciones/municipio/secciones_ine_ciudadanos_encuestas_seccion.php", // json datasource
					type: "post",  // method  , by default get
					data: {
						postData: <?php echo $postData; ?>
					},
					error: function(){  // error handling
						$(".secciones_ine_ciudadanos_encuestas-tabla-error").html("");
						$("#secciones_ine_ciudadanos_encuestas-tabla").append('<tbody class="secciones_ine_ciudadanos_encuestas-tabla-error"><tr><th colspan="8">Registros no encontrados</th></tr></tbody>');
						$("#secciones_ine_ciudadanos_encuestas-tabla_processing").css("display","none");
						
					}
				}
			} );

			$('#secciones_ine_ciudadanos_encuestas-tabla').css( 'display', 'table' );
			$('#secciones_ine_ciudadanos_encuestas-tabla').resize();
			$('#secciones_ine_ciudadanos_encuestas-tabla').DataTable().columns.adjust().responsive.recalc();
			$("#secciones_ine_ciudadanos_encuestas-tabla_filter").css("display","none");  // hiding global search box
			$('#selectCheckbox').on("click", function(event){ // triggering delete one by one
				if( $('.checkselected:checked').length > 0 ){  // at-least one checkbox checked
					var ids = [];
					$('.checkselected').each(function(){
						if($(this).is(':checked')){ 
							ids.push($(this).val());
						}
					});
					var ids_string = ids.toString();  // array to string conversion   
					var link="encuestasSecciones/municipio/index.php?cot="+ids_string;   
					var link2="encuestasSecciones/municipio/index.php";
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
		});
		function edit(valor){
			link="encuestasSecciones/municipio/update_seccion.php?id="+valor; 
			var link2="encuestasSecciones/municipio/update_seccion.php";
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
			var sexo = document.getElementById("sexo").value;
			var edad = document.getElementById("edad").value;
			var id_municipio = document.getElementById("id_municipio").value;
			var id_seccion_ine = document.getElementById("id_seccion_ine").value;

			var searchTable = [];
			var data = {
					'sexo' : sexo,
					'edad' : edad,
					'id_municipio' : id_municipio,
					'id_seccion_ine' : id_seccion_ine,
				}
			searchTable.push(data);
			// Crear un formulario oculto en el documento
			var form = document.createElement('form');
			form.method = 'POST';
			form.action = 'encuestasMunicipios/excel/index.php?cot=<?=$_COOKIE['pageService']?>'; // URL de destino

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
		function encuestasSeccionesMunicipio(valor){
			link="encuestasSecciones/municipio/index.php?id="+valor; 
			var link2="encuestasSecciones/municipio/index.php";
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
		function encuestasSeccion(valor){
			link="encuestasSecciones/municipio/seccion.php?id="+valor; 
			var link2="encuestasSecciones/municipio/seccion.php";
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
	</script> 
	<table id="secciones_ine_ciudadanos_encuestas-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
		<thead>
			<tr>
				<th>Fecha</th>
				<th>Clave</th>
				<th>Municipio</th>
				<th>Sección</th>
				<th>Nombre Completo</th>
				<th>Sexo</th>
				<th>Edad</th>
				<th>Relacionado</th>
				<th>Opciones</th>
			</tr> 
		</thead> 
	</table>
