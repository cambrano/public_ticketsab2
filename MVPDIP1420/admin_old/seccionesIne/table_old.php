<?php
	include __DIR__.'/../functions/security.php'; 
	@session_start();
	//var_dump($_POST);
	if(!empty($_POST)){
		include '../functions/secciones_ine.php';
		foreach ($_POST['searchTable'][0] as $key => $value) {
			//echo "XX".$key." = XX_SESSION['".$key."'];";
			$_SESSION[$key]=mysqli_real_escape_string($conexion,$value);
			//echo "<br>";
		}
	}
?> 
	<script type="text/javascript" language="javascript" >
		$(document).ready(function() {
			var responsive=false;
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
			var dataTable = $('#secciones_ine-tabla').DataTable( {
				"destroy": true,
				"responsive": responsive,
				"pageLength": 11,
				"retrieve": true,
				"info": true,
				"processing": true,
				"serverSide": true,
				"sPaginationType": "full_numbers", 
				/*"fixedHeader": true,*/
				"fixedHeader": {
					header: true,
					footer: true
				},
				"order": [[ 0, "desc" ]],
				"ordering": true,
				"searching": false,/* para el buscador*/
				"paging": true,/* para la paginacion y todo salga en una sola hora*/
				"aoColumnDefs": [
								{ "bSortable": false, "aTargets": [ /*1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16*/ ] }
								],
				"columnDefs":[{targets: 0, render: function ( data, type, row ) {return '<span>' + data + '</span>';}}],
				"scrollY": "100%", 
				"scrollX": "100%",

				"language": {
					"sProcessing":     "Procesando...",
					//"sLengthMenu":     "Mostrar _MENU_ registros",
					"sLengthMenu": ' ',
					/*"lengthMenu": 'Mostrar <select id="total_registros">'+
				      '<option value="11">11</option>'+
				      '<option value="2000000">Todos</option>'+
				      '</select> registros',*/
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
					url :"seccionesIne/secciones_ine.php", // json datasource
					type: "post",  // method  , by default get
					error: function(){  // error handling
						$(".secciones_ine-tabla-error").html("");
						$("#secciones_ine-tabla").append('<tbody class="secciones_ine-tabla-error"><tr><th colspan="8">Registros no encontrados</th></tr></tbody>');
						$("#secciones_ine-tabla_processing").css("display","none");
						
					}
				},
				initComplete: function () {
				 /*dataTable.page(2).draw(false);*/
				}
			} );

			$('#secciones_ine-tabla').css( 'display', 'table' );
			$('#secciones_ine-tabla').resize();

			$('#secciones_ine-tabla').DataTable().columns.adjust().responsive.recalc();
			$("#secciones_ine-tabla_filter").css("display","none");  // hiding global search box

			$('#secciones_ine-tabla tbody').on('click', 'tr', function () {
		        var data = dataTable.row( this ).data();
		        //alert( 'You clicked on '+data[0]+'\'s row' );

		    } );

		    //dataTable.columns( [0] ).visible( false );//poner invisible un dato

			$('#secciones_ine-tabla').on( 'page.dt', function () {
				var info = dataTable.page.info();
				//console.log('Showing page: '+info.page+' of '+info.pages );
				//mostrar en mapa 
				var clave = document.getElementById("clave").value;
				var numero = document.getElementById("numero").value; 
				var searchTable = [];
				var data = {   
						'clave' : clave,
						'numero' : numero, 
					}
				searchTable.push(data);
				var mapa = [];
				var data = {   
						'pagina' : info.page,
					}
				mapa.push(data);
				$.ajax({
					type: "POST",
					url: "seccionesIne/mapa.php",
					data: {searchTable:searchTable,mapa:mapa},
					async: true,
					success: function(data) {
						$("#mapaLoad").html(data);
					}
				});
				//return false;
			} );

			

		});
		function edit(valor){
			link="seccionesIne/update.php?id="+valor; 
			var link2="seccionesIne/update.php";
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
			link="seccionesIne/create.php";
			var link2="seccionesIne/create.php";
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
			link="seccionesIne/delete.php?id="+valor; 
			var link2="seccionesIne/delete.php";
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
	<table id="secciones_ine-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
		<thead>
			<!--
			<tr>
				<th>Fecha Registro</th>
				<th>Tipo</th>
				<th>Distancia(KM)</th>
				<th>Not. Distancia</th>
				<th>Not. Contagio</th>
				<th>Nombre Ciudadano</th>
				<th>Nombre Agente</th>
				<th>Location GPS</th>
				<th>Dirección Completa</th>
				<th>Ciudad</th>
				<th>Region</th>
				<th>País</th>
				<th>OS</th>
				<th>IP</th> 
			</tr>-->
			<tr>
			<?php
			$columna= array(
				'clave',
				'numero',
				'latitud',
				'longitud',
			);

			$columnaTipo= array(
				'clave',
				'numero',
				'texto',
				'texto',
			);

			$_SESSION['reporte_Sistema']['nombres']=$columna;
			$_SESSION['reporte_Sistema']['nombres_tipo']=$columnaTipo;
			$_SESSION['reporte_Sistema']['nombres_excel']="Reporte Pages Tracking";
			foreach ($columna as $key => $value) {
				echo '<th >'.$value.'</th>';
			}
			echo '<th >Opciones</th>';
			?>
			</tr>
		</thead> 
	</table>
