<?php
	include __DIR__."/../functions/security.php";
	@session_start();
	if(!empty($_POST)){
		foreach ($_POST['searchTable'][0] as $key => $value) {
			$_SESSION['searchTable'][$key] = mysqli_real_escape_string($conexion,$value);
		}
	}
	$_SESSION['reporte_Sistema']['columnas_sql'] = array(
		0 =>'clave',
		1 =>'folio',
		2 =>'clave_elector',
		3 =>'nombre_completo',
		4 =>'fecha_hora',
		5 =>'colonia',
		6 =>'localidad',
		7 =>'seccion',
		8 =>'distrito_local',
		9 =>'distrito_federal',
		10 =>'status',
	);
	$_SESSION['reporte_Sistema']['columnas_nombres'] = array(
		0 => array('row' => 'clave' ,'nombre' => 'Clave' ,'tipo' => 'string','mostrar' => 1 ),
		1 => array('row' => 'folio' ,'nombre' => 'Folio' ,'tipo' => 'string','mostrar' => 1 ),
		2 => array('row' => 'clave_elector' ,'nombre' => 'Clave Elector' ,'tipo' => 'string','mostrar' => 1 ),
		3 => array('row' => 'nombre_completo' ,'nombre' => 'Nombre Completo' ,'tipo' => 'string','mostrar' => 1 ),
		4 => array('row' => 'fecha_hora' ,'nombre' => 'Fecha Hora Registro' ,'tipo' => 'datetime','mostrar' => 1 ),
		5 => array('row' => 'colonia' ,'nombre' => 'Colonia' ,'tipo' => 'string','mostrar' => 1 ),
		6 => array('row' => 'localidad' ,'nombre' => 'Localidad' ,'tipo' => 'string','mostrar' => 1 ),
		7 => array('row' => 'seccion' ,'nombre' => 'Sección' ,'tipo' => 'integer','mostrar' => 1 ),
		8 => array('row' => 'distrito_local' ,'nombre' => 'Distrito Local' ,'tipo' => 'integer','mostrar' => 1 ),
		9 => array('row' => 'distrito_Federal' ,'nombre' => 'Distrito Federal' ,'tipo' => 'integer','mostrar' => 1 ),
		10 => array('row' => 'status' ,'nombre' => 'Estatus' ,'tipo' => 'string','mostrar' => 1 ),
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
			var dataTable = $('#militantes_partidos-tabla').DataTable( {
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
								{ "bSortable": false, "aTargets": [ '<?= count($_SESSION['reporte_Sistema']['columnas_sql']) ?>' ] },
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
					url :"militantesPartidosTotales/militantes_partidos.php", // json datasource
					type: "post",  // method  , by default get
					error: function(){  // error handling
						$(".militantes_partidos-tabla-error").html("");
						$("#militantes_partidos-tabla").append('<tbody class="militantes_partidos-tabla-error"><tr><th colspan="8">Registros no encontrados</th></tr></tbody>');
						$("#militantes_partidos-tabla_processing").css("display","none");
						
					}
				}
			} );

			$('#militantes_partidos-tabla').css( 'display', 'table' );
			$('#militantes_partidos-tabla').resize();
			$('#militantes_partidos-tabla').DataTable().columns.adjust().responsive.recalc();
			$("#militantes_partidos-tabla_filter").css("display","none");  // hiding global search box
			$('#selectCheckbox').on("click", function(event){ // triggering delete one by one
				if( $('.checkselected:checked').length > 0 ){  // at-least one checkbox checked
					var ids = [];
					$('.checkselected').each(function(){
						if($(this).is(':checked')){ 
							ids.push($(this).val());
						}
					});
					var ids_string = ids.toString();  // array to string conversion   
					var link="militantesPartidosTotales/index.php?cot="+ids_string;   
					var link2="militantesPartidosTotales/index.php";
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
			link="militantesPartidosTotales/update.php?id="+valor; 
			var link2="militantesPartidosTotales/update.php";
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
			link="militantesPartidosTotales/create.php";
			var link2="militantesPartidosTotales/create.php";
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
			link="militantesPartidosTotales/delete.php?id="+valor; 
			var link2="militantesPartidosTotales/delete.php";
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
			<?php
			if($pageService != $_COOKIE["pageService"]){
				if($pageService!=''){
					$_COOKIE["pageService"] = $pageService ;
				}
			}
			?>
			var link="militantesPartidosTotales/excel/index.php?cot=<?=$_COOKIE['pageService']?>"; 
			//window.open(link);
			//window.open(link,'newWindow','width=1280, height=460'); return false;
			//document.location = link;
			window.open(link); 
		}
		function militantesPartidosTotalesCredenciales(valor){
			link="militantesPartidosTotalesCredenciales/index.php?cot="+valor+"&refresh=1"; 
			var link2="militantesPartidosTotalesCredenciales/index.php";
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
	<table id="militantes_partidos-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
		<thead>
			<tr>
			<?php
				foreach ($_SESSION['reporte_Sistema']['columnas_nombres'] as $key => $value) {
					if($value['mostrar']==1){
						echo "<th>".$value['nombre']."</th>";
					}
				}
			?>
			<th>Opciones</th>
			</tr>
		</thead> 
	</table>
