<?php
	include __DIR__."/../functions/security.php";
	@session_start();
	if(!empty($_POST)){
		include __DIR__."/../functions/efs.php";
		foreach ($_POST['searchTable'][0] as $key => $value) {
			$escapedValue = mysqli_real_escape_string($conexion, $value);
			$_POST['searchTable'][0][$key] = $escapedValue;
		}
		$postData = json_encode($_POST);
		$caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$longitud = 5;
		$stringAleatorio = '';

		for ($i = 0; $i < $longitud; $i++) {
			$posicionAleatoria = rand(0, strlen($caracteres) - 1);
			$stringAleatorio .= $caracteres[$posicionAleatoria];
		}
		// Nombre del archivo CSV
		$rutaEfsSaveFileInternos = rutaEfsSaveFileInternos();
		$nombre_csv = 'SICln_'.$_COOKIE['id_usuario']."_I".date(Y_m_d).'-'.date(H_i_s)."I_".$stringAleatorio;
		$nombreArchivo = $rutaEfsSaveFileInternos.$nombre_csv.'.csv';
		// Abrir el archivo en modo escritura
		$archivoCSV = fopen($nombreArchivo, 'w');
		// Escribir los encabezados en el archivo CSV
		fputcsv($archivoCSV, array_keys($_POST['searchTable'][0]));
		// Escribir los valores en otra línea del archivo CSV
		fputcsv($archivoCSV, array_values($_POST['searchTable'][0]));
		// Cerrar el archivo
		fclose($archivoCSV);
		// Validar si el archivo se creó exitosamente
		if (file_exists($nombreArchivo)) {
		    //echo "OK!";
		} else {
		    //echo "bad";
		}
	}else{
		//die;
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
			var dataTable = $('#equipos-tabla').DataTable( {
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
				"order": [[ 1, "desc" ]],
				"ordering": true,
				"searching": false,/* para el buscador*/
				"paging": true,/* para la paginacion y todo salga en una sola hora*/
				"aoColumnDefs": [
								{ "bSortable": false, "aTargets": [ 0 ] },
								{/*"targets": [0, 1],"visible": false*/}
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
					url :"equipos/tabla_equipos.php", // json datasource
					type: "post",  // method  , by default get
					data: {
						<?php
						if($postData != ""){
							?>
							postData: <?php echo $postData; ?>
							<?php
						}
						?>
					},
					error: function(){  // error handling
						$(".equipos-tabla-error").html("");
						$("#equipos-tabla").append('<tbody class="equipos-tabla-error"><tr><th colspan="8">Registros no encontrados</th></tr></tbody>');
						$("#equipos-tabla_processing").css("display","none");
						
					}
				}
			} );

			$('#equipos-tabla').css( 'display', 'table' );
			$('#equipos-tabla').resize();
			$('#equipos-tabla').DataTable().columns.adjust().responsive.recalc();
			$("#equipos-tabla_filter").css("display","none");  // hiding global search box
			$('#selectCheckbox').on("click", function(event){ // triggering delete one by one
				if( $('.checkselected:checked').length > 0 ){  // at-least one checkbox checked
					var ids = [];
					$('.checkselected').each(function(){
						if($(this).is(':checked')){ 
							ids.push($(this).val());
						}
					});
					var ids_string = ids.toString();  // array to string conversion   
					var link="listaNominal/index.php?cot="+ids_string;   
					var link2="listaNominal/index.php";
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
		
	</script> 
	<table id="equipos-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
		<thead>
			<tr>
				<th>Asignar</th>
				<th>Clave</th>
				<th>Folio</th>
				<th>Ubicación</th>
				<th>Tipo Equipo</th>
				<th>Marca</th>
				<th>Modelo</th>
				<th>Memoria RAM(GB)</th>
				<th>Procesador</th>
			</tr> 
		</thead> 
	</table>
	<script>
		$(".loader").fadeOut(2000);
	</script>
