<?php
	include __DIR__."/../functions/security.php";
	@session_start();
	if(!empty($_POST)){
		foreach ($_POST['searchTable'][0] as $key => $value) {
			$_SESSION['searchTable'][$key] = mysqli_real_escape_string($conexion,$value);
		}
	}
	$_SESSION['reporte_Sistema']['columnas_sql'] = array(
		0 =>'fechaR',
		1 =>'tipo',
		2 =>'nombre',
		3  =>'nombre_completo',
		4  =>'celular',
		5  =>'fecha_hora_envio',
		6  =>'municipio',
		7  =>'distrito_local',
		8  =>'distrito_federal',
		9 =>'seccion',
		10 =>'mensaje_proveedor',
		11  =>'status',
	);
	$_SESSION['reporte_Sistema']['columnas_nombres'] = array(
		0 => array('row' => 'fechaR' ,'nombre' => 'Fecha Registro' ,'tipo' => 'datetime','mostrar' => 1 ),
		1 => array('row' => 'tipo' ,'nombre' => 'Tipo' ,'tipo' => 'string','mostrar' => 1),
		2 => array('row' => 'nombre' ,'nombre' => 'Campaña' ,'tipo' => 'string','mostrar' => 1),
		3 => array('row' => 'nombre_completo' ,'nombre' => 'Ciudadano' ,'tipo' => 'string','mostrar' => 1),
		4 => array('row' => 'celular' ,'nombre' => 'celular','tipo' => 'string','mostrar' => 1),
		5 => array('row' => 'fecha_hora_envio' ,'nombre' => 'Envío' ,'tipo' => 'datetime','mostrar' => 1),
		6 => array('row' => 'municipio' ,'nombre' => 'Municipio' ,'tipo' => 'string','mostrar' => 1),
		7 => array('row' => 'distrito_local' ,'nombre' => 'D. Local' ,'tipo' => 'string','mostrar' => 1),
		8 => array('row' => 'distrito_federal' ,'nombre' => 'D. Federal' ,'tipo' => 'string','mostrar' => 1),
		9 => array('row' => 'seccion' ,'nombre' => 'Sección' ,'tipo' => 'string','mostrar' => 1),
		10 => array('row' => 'mensaje_proveedor' ,'nombre' => 'Mensaje' ,'tipo' => 'string','mostrar' => 1),
		11 => array('row' => 'status' ,'nombre' => 'Estatus' ,'tipo' => 'string','mostrar' => 1), 
	);
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
			var dataTable = $('#secciones_ine_ciudadanos_campanas_sms_programadas-tabla').DataTable( {
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
								{ "bSortable": false, "aTargets": [ 12 ] }
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
					url :"seccionesIneCiudadanosCampanasSMSProgramadas/secciones_ine_ciudadanos_campanas_sms_programadas.php", // json datasource
					type: "post",  // method  , by default get
					error: function(){  // error handling
						$(".secciones_ine_ciudadanos_campanas_sms_programadas-tabla-error").html("");
						$("#secciones_ine_ciudadanos_campanas_sms_programadas-tabla").append('<tbody class="secciones_ine_ciudadanos_campanas_sms_programadas-tabla-error"><tr><th colspan="8">Registros no encontrados</th></tr></tbody>');
						$("#secciones_ine_ciudadanos_campanas_sms_programadas-tabla_processing").css("display","none");
						
					}
				}
			} );

			$('#secciones_ine_ciudadanos_campanas_sms_programadas-tabla').css( 'display', 'table' );
			$('#secciones_ine_ciudadanos_campanas_sms_programadas-tabla').resize();
			$('#secciones_ine_ciudadanos_campanas_sms_programadas-tabla').DataTable().columns.adjust().responsive.recalc();
			$("#secciones_ine_ciudadanos_campanas_sms_programadas-tabla_filter").css("display","none");  // hiding global search box
			$('#selectCheckbox').on("click", function(event){ // triggering delete one by one
				if( $('.checkselected:checked').length > 0 ){  // at-least one checkbox checked
					var ids = [];
					$('.checkselected').each(function(){
						if($(this).is(':checked')){ 
							ids.push($(this).val());
						}
					});
					var ids_string = ids.toString();  // array to string conversion   
					var link="seccionesIneCiudadanosCampanasSMSProgramadas/index.php?cot="+ids_string;   
					var link2="seccionesIneCiudadanosCampanasSMSProgramadas/index.php";
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
			link="seccionesIneCiudadanosCampanasSMSProgramadas/update.php?id="+valor; 
			var link2="seccionesIneCiudadanosCampanasSMSProgramadas/update.php";
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
			link="seccionesIneCiudadanosCampanasSMSProgramadas/create.php";
			var link2="seccionesIneCiudadanosCampanasSMSProgramadas/create.php";
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
			link="seccionesIneCiudadanosCampanasSMSProgramadas/delete.php?id="+valor; 
			var link2="seccionesIneCiudadanosCampanasSMSProgramadas/delete.php";
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
		function downloadExcel(){
			var link="seccionesIneCiudadanosCampanasSMSProgramadas/excel/index.php?cot=<?=$_SESSION['pageService']?>"; 
			//window.open(link);
			//window.open(link,'newWindow','width=1280, height=460'); return false;
			//document.location = link;
			window.open(link); 
		}
	</script> 
	<table id="secciones_ine_ciudadanos_campanas_sms_programadas-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
		<thead>
			<tr>
				<th>FechaR</th>
				<th>Tipo</th>
				<th>Nombre</th>
				<th>Ciudadano</th>
				<th>Celular</th>
				<th>Envío</th>
				<th>Municipio</th>
				<th>Distrito Local</th>
				<th>Distrito Federal</th>
				<th>Sección</th>
				<th>Mensaje</th>
				<th>Estatus</th>
				<th>Opciones</th>
			</tr> 
		</thead> 
	</table>
