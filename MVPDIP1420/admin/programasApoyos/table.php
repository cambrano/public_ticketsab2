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
			var dataTable = $('#programas_apoyos-tabla').DataTable( {
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
					url :"programasApoyos/programas_apoyos.php", // json datasource
					type: "post",  // method  , by default get
					data: {
						postData: <?php echo $postData; ?>
					},
					error: function(){  // error handling
						$(".programas_apoyos-tabla-error").html("");
						$("#programas_apoyos-tabla").append('<tbody class="programas_apoyos-tabla-error"><tr><th colspan="8">Registros no encontrados</th></tr></tbody>');
						$("#programas_apoyos-tabla_processing").css("display","none");
						
					}
				}
			} );

			$('#programas_apoyos-tabla').css( 'display', 'table' );
			$('#programas_apoyos-tabla').resize();
			$('#programas_apoyos-tabla').DataTable().columns.adjust().responsive.recalc();
			$("#programas_apoyos-tabla_filter").css("display","none");  // hiding global search box
			$('#selectCheckbox').on("click", function(event){ // triggering delete one by one
				if( $('.checkselected:checked').length > 0 ){  // at-least one checkbox checked
					var ids = [];
					$('.checkselected').each(function(){
						if($(this).is(':checked')){ 
							ids.push($(this).val());
						}
					});
					var ids_string = ids.toString();  // array to string conversion   
					var link="programasApoyos/index.php?cot="+ids_string;   
					var link2="programasApoyos/index.php";
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
			link="programasApoyos/update.php?id="+valor; 
			var link2="programasApoyos/update.php";
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
			link="programasApoyos/create.php";
			var link2="programasApoyos/create.php";
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
			link="programasApoyos/delete.php?id="+valor; 
			var link2="programasApoyos/delete.php";
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
		function programas_apoyos_totales(valor){
			link="seccionesIneCiudadanosProgramasApoyosTotales/index.php?cot="+valor; 
			var link2="seccionesIneCiudadanosProgramasApoyosTotales/index.php";
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
			var clave = document.getElementById("clave").value;
			var folio = document.getElementById("folio").value;
			var nombre = document.getElementById("nombre").value;
			var id_tipo_territorio_input = document.getElementById("id_tipo_territorio");
			var id_tipo_territorio_array = [];
			for (var i = 0; i < id_tipo_territorio_input.length; i++) {
				if (id_tipo_territorio_input.options[i].selected){
					id_tipo_territorio_array.push(id_tipo_territorio_input.options[i].value);
				}
			}
			id_tipo_territorio = id_tipo_territorio_array.join(",");

			var id_dependencia_input = document.getElementById("id_dependencia");
			var id_dependencia_array = [];
			for (var i = 0; i < id_dependencia_input.length; i++) {
				if (id_dependencia_input.options[i].selected){
					id_dependencia_array.push(id_dependencia_input.options[i].value);
				}
			}
			id_dependencia = id_dependencia_array.join(",");
			var searchTable = [];
			var data = {
				'clave' : clave,
				'nombre' : nombre,
				'id_tipo_territorio' : id_tipo_territorio,
				'id_dependencia' : id_dependencia,
			}
			searchTable.push(data);
			// Crear un formulario oculto en el documento
			var form = document.createElement('form');
			form.method = 'POST';
			form.action = 'programasApoyos/excel/index.php?cot=<?=$_COOKIE['pageService']?>'; // URL de destino

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
	<table id="programas_apoyos-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
		<thead>
			<tr>
			<th>Clave</th>
			<th>Folio</th>
			<th>Nombre</th>
			<th>Fecha Inicio</th>
			<th>Fecha Final</th>
			<th>Total Beneficaidos</th>
			<th>Tipos Territorios</th>
			<th>Dependencias</th>
			<th>Opciones</th>
			</tr>
		</thead> 
	</table>
