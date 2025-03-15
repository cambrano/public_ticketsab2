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
			var dataTable = $('#secciones_ine_ciudadanos_usuarios-tabla').DataTable( {
				"destroy": true,
				"responsive": false,
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
								{ "bSortable": false, "aTargets": [ 6 ] }
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
					url :"seccionesIneCiudadanosUsuarios/secciones_ine_ciudadanos_usuarios.php", // json datasource
					type: "post",  // method  , by default get
					error: function(){  // error handling
						$(".secciones_ine_ciudadanos_usuarios-tabla-error").html("");
						$("#secciones_ine_ciudadanos_usuarios-tabla").append('<tbody class="secciones_ine_ciudadanos_usuarios-tabla-error"><tr><th colspan="8">Registros no encontrados</th></tr></tbody>');
						$("#secciones_ine_ciudadanos_usuarios-tabla_processing").css("display","none");
						
					}
				}
			} );

			$('#secciones_ine_ciudadanos_usuarios-tabla').css( 'display', 'table' );
			$('#secciones_ine_ciudadanos_usuarios-tabla').resize();
			$('#secciones_ine_ciudadanos_usuarios-tabla').DataTable().columns.adjust().responsive.recalc();
			$("#secciones_ine_ciudadanos_usuarios-tabla_filter").css("display","none");  // hiding global search box
			$('#selectCheckbox').on("click", function(event){ // triggering delete one by one
				if( $('.checkselected:checked').length > 0 ){  // at-least one checkbox checked
					var ids = [];
					$('.checkselected').each(function(){
						if($(this).is(':checked')){ 
							ids.push($(this).val());
						}
					});
					var ids_string = ids.toString();  // array to string conversion   
					var link="seccionesIneCiudadanosUsuarios/index.php?cot="+ids_string;   
					var link2="seccionesIneCiudadanosUsuarios/index.php";
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
			link="seccionesIneCiudadanosUsuarios/update.php?id="+valor; 
			var link2="seccionesIneCiudadanosUsuarios/update.php";
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
			link="seccionesIneCiudadanosUsuarios/create.php";
			var link2="seccionesIneCiudadanosUsuarios/create.php";
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
			link="seccionesIneCiudadanosUsuarios/delete.php?id="+valor; 
			var link2="seccionesIneCiudadanosUsuarios/delete.php";
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
	<table id="secciones_ine_ciudadanos_usuarios-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
		<thead>
			<tr>
				<th>Clave</th>
				<th>Clave Elector</th>
				<th>Nombre Completo</th>
				<th>Usuario</th>
				<th>Password</th>
				<th>Estatus</th>
				<th>Opciones</th>
			</tr> 
		</thead> 
	</table>
