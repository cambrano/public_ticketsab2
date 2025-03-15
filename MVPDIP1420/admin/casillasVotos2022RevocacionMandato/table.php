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
			var dataTable = $('#casillas_votos_2022_revocacion_mandato-tabla').DataTable( {
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
					url :"casillasVotos2022RevocacionMandato/casillas_votos_2022_revocacion_mandato.php", // json datasource
					type: "post",  // method  , by default get
					data: {
						postData: <?php echo $postData; ?>
					},
					error: function(){  // error handling
						$(".casillas_votos_2022_revocacion_mandato-tabla-error").html("");
						$("#casillas_votos_2022_revocacion_mandato-tabla").append('<tbody class="casillas_votos_2022_revocacion_mandato-tabla-error"><tr><th colspan="8">Registros no encontrados</th></tr></tbody>');
						$("#casillas_votos_2022_revocacion_mandato-tabla_processing").css("display","none");
						
					}
				}
			} );

			$('#casillas_votos_2022_revocacion_mandato-tabla').css( 'display', 'table' );
			$('#casillas_votos_2022_revocacion_mandato-tabla').resize();
			$('#casillas_votos_2022_revocacion_mandato-tabla').DataTable().columns.adjust().responsive.recalc();
			$("#casillas_votos_2022_revocacion_mandato-tabla_filter").css("display","none");  // hiding global search box
			$('#selectCheckbox').on("click", function(event){ // triggering delete one by one
				if( $('.checkselected:checked').length > 0 ){  // at-least one checkbox checked
					var ids = [];
					$('.checkselected').each(function(){
						if($(this).is(':checked')){ 
							ids.push($(this).val());
						}
					});
					var ids_string = ids.toString();  // array to string conversion   
					var link="casillasVotos2022RevocacionMandato/index.php?cot="+ids_string;   
					var link2="casillasVotos2022RevocacionMandato/index.php";
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
			link="casillasVotos2022RevocacionMandato/update.php?id="+valor; 
			var link2="casillasVotos2022RevocacionMandato/update.php";
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

		function addAyuntamiento(){
			////ajax
			link="casillasVotos2022RevocacionMandato/create.php?tipo=0"; 
			var link2="casillasVotos2022RevocacionMandato/create.php";

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
		function addDistritoFederal(){
			////ajax
			link="casillasVotos2022RevocacionMandato/create.php?tipo=2"; 
			var link2="casillasVotos2022RevocacionMandato/create.php";

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
		function addDistritoLocal(){
			////ajax
			link="casillasVotos2022RevocacionMandato/create.php?tipo=1"; 
			var link2="casillasVotos2022RevocacionMandato/create.php";

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
			link="casillasVotos2022RevocacionMandato/delete.php?id="+valor; 
			var link2="casillasVotos2022RevocacionMandato/delete.php";
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
	<table id="casillas_votos_2022_revocacion_mandato-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
		<thead>
			<tr>
				<th>Clave</th>
				<th>Municipio</th>
				<th>D. Local</th>
				<th>D. Federal</th>
				<th>Sección</th>
				<th>Tipo Casilla</th>
				<th>Codigo</th>
				<th>Lista Nominal</th>
				<th>Opciones</th>
			</tr> 
		</thead> 
	</table>
