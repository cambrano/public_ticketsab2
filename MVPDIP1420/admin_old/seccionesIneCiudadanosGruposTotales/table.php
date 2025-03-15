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
		3 =>'tipo_nombramiento',
		4 =>'nombre_completo',
		5 =>'fecha_inicio',
		6 =>'fecha_final',
		7 =>'colonia',
		8 =>'localidad',
		9 =>'seccion',
		10 =>'distrito_local',
		11 =>'distrito_federal',
		12 =>'status', 
	);
	$_SESSION['reporte_Sistema']['columnas_nombres'] = array(
		0 => array('row' => 'clave' ,'nombre' => 'Clave' ,'tipo' => 'string','mostrar' => 1 ),
		1 => array('row' => 'folio' ,'nombre' => 'Folio' ,'tipo' => 'string','mostrar' => 1 ),
		2 => array('row' => 'clave_elector' ,'nombre' => 'Clave Elector' ,'tipo' => 'string','mostrar' => 1 ),
		3 => array('row' => 'tipo_nombramiento' ,'nombre' => 'Tipo Nombramiento' ,'tipo' => 'string','mostrar' => 1 ),
		4 => array('row' => 'nombre_completo' ,'nombre' => 'Nombre Completo' ,'tipo' => 'string','mostrar' => 1 ),
		5 => array('row' => 'fecha_inicio' ,'nombre' => 'Fecha Inicio' ,'tipo' => 'date','mostrar' => 1 ),
		6 => array('row' => 'fecha_final' ,'nombre' => 'Fecha Final' ,'tipo' => 'date','mostrar' => 1 ),
		7 => array('row' => 'colonia' ,'nombre' => 'Colonia' ,'tipo' => 'string','mostrar' => 1 ),
		8 => array('row' => 'localidad' ,'nombre' => 'Localidad' ,'tipo' => 'string','mostrar' => 1 ),
		9 => array('row' => 'seccion' ,'nombre' => 'Sección' ,'tipo' => 'string','mostrar' => 1 ),
		10 => array('row' => 'distrito_local' ,'nombre' => 'Distrito Local' ,'tipo' => 'string','mostrar' => 1 ),
		11 => array('row' => 'distrito_Federal' ,'nombre' => 'Distrito Federal' ,'tipo' => 'string','mostrar' => 1 ),
		12 => array('row' => 'status' ,'nombre' => 'Estatus' ,'tipo' => 'string','mostrar' => 1 ),
		13 => array('row' => 'observaciones' ,'nombre' => 'Orservaciones' ,'tipo' => 'string','mostrar' => 0 ),
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
			var dataTable = $('#secciones_ine_ciudadanos_grupos-tabla').DataTable( {
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
					url :"seccionesIneCiudadanosGruposTotales/secciones_ine_ciudadanos_grupos.php", // json datasource
					type: "post",  // method  , by default get
					error: function(){  // error handling
						$(".secciones_ine_ciudadanos_grupos-tabla-error").html("");
						$("#secciones_ine_ciudadanos_grupos-tabla").append('<tbody class="secciones_ine_ciudadanos_grupos-tabla-error"><tr><th colspan="8">Registros no encontrados</th></tr></tbody>');
						$("#secciones_ine_ciudadanos_grupos-tabla_processing").css("display","none");
						
					}
				}
			} );

			$('#secciones_ine_ciudadanos_grupos-tabla').css( 'display', 'table' );
			$('#secciones_ine_ciudadanos_grupos-tabla').resize();
			$('#secciones_ine_ciudadanos_grupos-tabla').DataTable().columns.adjust().responsive.recalc();
			$("#secciones_ine_ciudadanos_grupos-tabla_filter").css("display","none");  // hiding global search box
			$('#selectCheckbox').on("click", function(event){ // triggering delete one by one
				if( $('.checkselected:checked').length > 0 ){  // at-least one checkbox checked
					var ids = [];
					$('.checkselected').each(function(){
						if($(this).is(':checked')){ 
							ids.push($(this).val());
						}
					});
					var ids_string = ids.toString();  // array to string conversion   
					var link="seccionesIneCiudadanosGruposTotales/index.php?cot="+ids_string;   
					var link2="seccionesIneCiudadanosGruposTotales/index.php";
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
			link="seccionesIneCiudadanosGruposTotales/update.php?id="+valor; 
			var link2="seccionesIneCiudadanosGruposTotales/update.php";
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
			link="seccionesIneCiudadanosGruposTotales/create.php";
			var link2="seccionesIneCiudadanosGruposTotales/create.php";
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
			link="seccionesIneCiudadanosGruposTotales/delete.php?id="+valor; 
			var link2="seccionesIneCiudadanosGruposTotales/delete.php";
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
			var link="seccionesIneCiudadanosGruposTotales/excel/index.php?cot=<?=$_COOKIE['pageService']?>"; 
			//window.open(link);
			//window.open(link,'newWindow','width=1280, height=460'); return false;
			//document.location = link;
			window.open(link); 
		}
	</script> 
	<table id="secciones_ine_ciudadanos_grupos-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
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
