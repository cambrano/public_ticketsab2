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
			var dataTable = $('#secciones_ine_ciudadanos-tabla').DataTable( {
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
								{ "bSortable": false, "aTargets": [ 17 ] }
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
					url :"seccionesIneCiudadanos/secciones_ine_ciudadanos.php", // json datasource
					type: "post",  // method  , by default get
					data: {
						postData: <?php echo $postData; ?>
					},
					error: function(){  // error handling
						$(".secciones_ine_ciudadanos-tabla-error").html("");
						$("#secciones_ine_ciudadanos-tabla").append('<tbody class="secciones_ine_ciudadanos-tabla-error"><tr><th colspan="8">Registros no encontrados</th></tr></tbody>');
						$("#secciones_ine_ciudadanos-tabla_processing").css("display","none");
						
					}
				}
			});

			$('#secciones_ine_ciudadanos-tabla').css( 'display', 'table' );
			$('#secciones_ine_ciudadanos-tabla').resize();
			$('#secciones_ine_ciudadanos-tabla').DataTable().columns.adjust().responsive.recalc();
			$("#secciones_ine_ciudadanos-tabla_filter").css("display","none");  // hiding global search box
			$('#selectCheckbox').on("click", function(event){ // triggering delete one by one
				if( $('.checkselected:checked').length > 0 ){  // at-least one checkbox checked
					var ids = [];
					$('.checkselected').each(function(){
						if($(this).is(':checked')){ 
							ids.push($(this).val());
						}
					});
					var ids_string = ids.toString();  // array to string conversion   
					var link="seccionesIneCiudadanos/index.php?cot="+ids_string;   
					var link2="seccionesIneCiudadanos/index.php";
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
			link="seccionesIneCiudadanos/update.php?id="+valor; 
			var link2="seccionesIneCiudadanos/update.php";
			dataString = 'urlink='+link2;
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {}
			});
			//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
			//$("#homebody").load(link);
			$("#homebody").load(link+"&refresh=1");
		}

		function add(){	
			////ajax
			link="seccionesIneCiudadanos/create.php";
			var link2="seccionesIneCiudadanos/create.php";
			dataString = 'urlink='+link2;
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			////
			//$("#homebody").load(link);
			$("#homebody").load(link+"?refresh=1");
		}

		function borrar(valor){
			link="seccionesIneCiudadanos/delete.php?id="+valor; 
			var link2="seccionesIneCiudadanos/delete.php";
			dataString = 'urlink='+link2;
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {}
			});
			//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
			//$("#homebody").load(link);
			$("#homebody").load(link+"&refresh=1");
		}
		function ciudadano_categoria(valor){
			link="seccionesIneCiudadanosCategorias/index.php?cot="+valor; 
			var link2="seccionesIneCiudadanosCategorias/index.php";
			dataString = 'urlink='+link2;
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {}
			});
			//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
			//$("#homebody").load(link);
			$("#homebody").load(link+"&refresh=1");
		}
		function encuestas(valor){
			link="seccionesIneCiudadanosEncuestas/index.php?cot="+valor; 
			var link2="seccionesIneCiudadanosEncuestas/index.php";
			dataString = 'urlink='+link2;
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {}
			});
			//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
			//$("#homebody").load(link);
			$("#homebody").load(link+"&refresh=1");
		}
		function seguimientos(valor){
			link="seccionesIneCiudadanosSeguimientos/index.php?cot="+valor; 
			var link2="seccionesIneCiudadanosSeguimientos/index.php";
			dataString = 'urlink='+link2;
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {}
			});
			//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
			//$("#homebody").load(link);
			$("#homebody").load(link+"&refresh=1");
		}

		function programas_apoyos(valor){
			link="seccionesIneCiudadanosProgramasApoyos/index.php?cot="+valor; 
			var link2="seccionesIneCiudadanosProgramasApoyos/index.php";
			dataString = 'urlink='+link2;
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {}
			});
			//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
			//$("#homebody").load(link);
			$("#homebody").load(link+"&refresh=1");
		}
	</script> 
	<table id="secciones_ine_ciudadanos-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
		<thead>
			<tr>
				<th>Clave</th>
				<th>Folio</th>
				<th>Sección</th>
				<th>D.(km) Aprox</th>
				<th>Manzana</th>
				<th>C.U.R.P</th> 
				<th>CVE</th>
				<th>Nombre Completo</th> 
				<th>Fecha Nacimiento</th> 
				<th>Sexo</th> 
				<th>Whatsapp</th>
				<th>Celular</th>
				<th>Teléfono</th>
				<th>Correo Electrónico</th>
				<th>Dirección</th>
				<th>Colonia</th>
				<th>Municipio</th>
				<th>Localidad</th>
				<th>Opciones</th>
			</tr> 
		</thead> 
	</table>  
