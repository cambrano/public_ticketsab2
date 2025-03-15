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
			var dataTable = $('#campanas_sms-tabla').DataTable( {
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
								{ "bSortable": false, "aTargets": [ 3,4,5,6 ] }
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
					url :"campanasSMS/campanas_sms.php", // json datasource
					type: "post",  // method  , by default get
					error: function(){  // error handling
						$(".campanas_sms-tabla-error").html("");
						$("#campanas_sms-tabla").append('<tbody class="campanas_sms-tabla-error"><tr><th colspan="8">Registros no encontrados</th></tr></tbody>');
						$("#campanas_sms-tabla_processing").css("display","none");
						
					}
				}
			} );

			$('#campanas_sms-tabla').css( 'display', 'table' );
			$('#campanas_sms-tabla').resize();
			$('#campanas_sms-tabla').DataTable().columns.adjust().responsive.recalc();
			$("#campanas_sms-tabla_filter").css("display","none");  // hiding global search box
			$('#selectCheckbox').on("click", function(event){ // triggering delete one by one
				if( $('.checkselected:checked').length > 0 ){  // at-least one checkbox checked
					var ids = [];
					$('.checkselected').each(function(){
						if($(this).is(':checked')){ 
							ids.push($(this).val());
						}
					});
					var ids_string = ids.toString();  // array to string conversion   
					var link="campanasSMS/index.php?cot="+ids_string;   
					var link2="campanasSMS/index.php";
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
			link="campanasSMS/update.php?id="+valor; 
			var link2="campanasSMS/update.php";
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
			link="campanasSMS/create.php";
			var link2="campanasSMS/create.php";
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
			link="campanasSMS/delete.php?id="+valor; 
			var link2="campanasSMS/delete.php";
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
		function cancelar_masivo(valor){
			link="campanasSMS/cancelar_masivos.php?id="+valor; 
			var link2="campanasSMS/cancelar_masivos.php";
			dataString = 'urlink='+link2;  
			$("#homebody").load(link);
		}
		function reenviar_masivo_configurada(valor){
			link="campanasSMS/reenviar_masivos_configurada.php?id="+valor; 
			var link2="campanasSMS/reenviar_masivos_configurada.php";
			dataString = 'urlink='+link2;  
			$("#homebody").load(link);
		}
		function reenviar_masivo_segmentada(valor){
			link="campanasSMS/reenviar_masivos_segmentada.php?id="+valor; 
			var link2="campanasSMS/reenviar_masivos_segmentada.php";
			dataString = 'urlink='+link2;  
			$("#homebody").load(link);
		}
	</script> 
	<table id="campanas_sms-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
		<thead>
			<tr>
				<th>Nombre</th>
				<th>Tipo</th>
				<th>Estatus</th>
				<th>Reenviar Configuración</th>
				<th>Reenviar Segmentada</th>
				<th>Cancelar Programados</th>
				<th>Opciones</th>
			</tr> 
		</thead> 
	</table>
