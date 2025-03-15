<?php
	include __DIR__."/../functions/security.php";
	@session_start();
	if(!empty($_POST)){
		foreach ($_POST['searchTable'][0] as $key => $value) {
			$escapedValue = mysqli_real_escape_string($conexion, $value);
			$_POST['searchTable'][0][$key] = $escapedValue;
		}
		$postData = json_encode($_POST);
		//setcookie("searchTableLN", json_encode($_POST['searchTable'][0]),time()+(60*60*8),"/",false);
		//setcookie("searchOpcionesLN", json_encode($_POST['searchOpciones'][0]),time()+(60*60*8),"/",false);
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
			var dataTable = $('#lista_nominal-tabla').DataTable( {
				"destroy": true,
				"responsive": <?= $_POST['searchOpciones'][0]['tipo_tabla_responsive'] ?>,
				<?php
				if($_POST['searchOpciones'][0]['tipo_limite']!='x'){
					?>
					"pageLength": <?= $_POST['searchOpciones'][0]['tipo_limite'] ?>,
					<?php
				}else{
					?>
					"pageLength": -1,
					<?php
				}
				?>
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
								{ "bSortable": false, "aTargets": [ 14 ] }
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
					url :"listaNominal/lista_nominal.php", // json datasource
					type: "post",  // method  , by default get
					data: {
						postData: <?php echo $postData; ?>
					},
					error: function(){  // error handling
						$(".lista_nominal-tabla-error").html("");
						$("#lista_nominal-tabla").append('<tbody class="lista_nominal-tabla-error"><tr><th colspan="8">Registros no encontrados</th></tr></tbody>');
						$("#lista_nominal-tabla_processing").css("display","none");
						
					}
				}
			} );

			$('#lista_nominal-tabla').css( 'display', 'table' );
			$('#lista_nominal-tabla').resize();
			$('#lista_nominal-tabla').DataTable().columns.adjust().responsive.recalc();
			$("#lista_nominal-tabla_filter").css("display","none");  // hiding global search box
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
			$('#lista_nominal-tabla').on( 'page.dt', function () {
				var info = dataTable.page.info();
				//mostrar en mapa 

				/*var id_seccion_ine = document.getElementById("id_seccion_ine").value;*/
				var clave_elector = document.getElementById("clave_elector").value;
				var curp = document.getElementById("curp").value;
				var nombre = document.getElementById("nombre").value;
				var apellido_paterno = document.getElementById("apellido_paterno").value;
				var apellido_materno = document.getElementById("apellido_materno").value;
				var id_seccion_ine = document.getElementById("id_seccion_ine").value;
				var id_municipio = document.getElementById("id_municipio").value;
				var id_localidad = document.getElementById("id_localidad").value;
				var militante_partido = document.getElementById("militante_partido").value;
				var padrones_especificos = document.getElementById("padrones_especificos").value;
				var tipo_ciudadano = document.getElementById("tipo_ciudadano").value;
				var manzana = document.getElementById("manzana").value;
				var id_distrito_local = document.getElementById("id_distrito_local").value;
				var id_distrito_federal = document.getElementById("id_distrito_federal").value;

				//opciones de busqueda
				var tipo_mapa = document.getElementById("tipo_mapa").value;
				var tipo_limite = document.getElementById("tipo_limite").value;
				var tipo_tabla = document.getElementById("tipo_tabla").checked;
				if(tipo_tabla==false){
					tipo_tabla=0
				}else{
					tipo_tabla=1
				}
				var tipo_tabla_responsive = document.getElementById("tipo_tabla_responsive").checked;
				if(tipo_tabla_responsive==false){
					tipo_tabla_responsive=0
				}else{
					tipo_tabla_responsive=1
				}
				searchOpciones = [];
				var data = {
					'tipo_tabla_responsive' : tipo_tabla_responsive,
					'tipo_tabla' :tipo_tabla,
					'tipo_limite' : tipo_limite,
					'tipo_mapa' : tipo_mapa,
				}
				searchOpciones.push(data);

				var searchTable = [];
				var data = {   
					'clave_elector' : clave_elector,
					'curp' : curp,
					'nombre' : nombre,
					'apellido_paterno' : apellido_paterno,
					'apellido_materno' : apellido_materno,
					'id_seccion_ine' : id_seccion_ine,
					'id_municipio' : id_municipio,
					'id_localidad' : id_localidad,
					'militante_partido' : militante_partido,
					'padrones_especificos' : padrones_especificos,
					'tipo_ciudadano' : tipo_ciudadano,
					'tipo_ciudadano' : tipo_ciudadano,
					'manzana' : manzana,
					'id_distrito_local' : id_distrito_local,
					'id_distrito_federal' : id_distrito_federal,
				}
				searchTable.push(data);
				if(tipo_mapa!='sin_mapa'){
					var mapa = [];
					var data = {
						'pagina' : info.page,
					}
					mapa.push(data);
					if(tipo_mapa=='mapa_calor'){
						url = "listaNominal/mapaCalor.php";
					}else{
						url = "listaNominal/mapa.php";
					}
					$.ajax({
						type: "POST",
						url: url,
						data: {searchTable: searchTable,mapa:mapa,searchOpciones:searchOpciones},
						async: true,
						success: function(data) {
							$("#mapaLoad").html(data);
						}
					});
				}
				//return false;
			});
			$('#lista_nominal-tabla').on( 'order.dt', function () {
				var table = $('#lista_nominal-tabla').dataTable();
				var api = table.api();
				var order = table.api().order(); // this has column and order details 
				//console.log(order[0][0]);
				//console.log(order[0][1]);
				/*var id_seccion_ine = document.getElementById("id_seccion_ine").value;*/
				var clave_elector = document.getElementById("clave_elector").value;
				var curp = document.getElementById("curp").value;
				var nombre = document.getElementById("nombre").value;
				var apellido_paterno = document.getElementById("apellido_paterno").value;
				var apellido_materno = document.getElementById("apellido_materno").value;
				var id_seccion_ine = document.getElementById("id_seccion_ine").value;
				var id_municipio = document.getElementById("id_municipio").value;
				var id_localidad = document.getElementById("id_localidad").value;
				var militante_partido = document.getElementById("militante_partido").value;
				var padrones_especificos = document.getElementById("padrones_especificos").value;
				var tipo_ciudadano = document.getElementById("tipo_ciudadano").value;
				var manzana = document.getElementById("manzana").value;
				var id_distrito_local = document.getElementById("id_distrito_local").value;
				var id_distrito_federal = document.getElementById("id_distrito_federal").value;


				//opciones de busqueda
				var tipo_mapa = document.getElementById("tipo_mapa").value;
				var tipo_limite = document.getElementById("tipo_limite").value;
				var tipo_tabla = document.getElementById("tipo_tabla").checked;
				var tipo_tabla_responsive = document.getElementById("tipo_tabla_responsive").checked;
				searchOpciones = [];
				var data = {
					'tipo_tabla_responsive' : tipo_tabla_responsive,
					'tipo_tabla' :tipo_tabla,
					'tipo_limite' : tipo_limite,
					'tipo_mapa' : tipo_mapa,
				}
				searchOpciones.push(data);

				var searchTable = [];
				var data = {
					'clave_elector' : clave_elector,
					'curp' : curp,
					'nombre' : nombre,
					'apellido_paterno' : apellido_paterno,
					'apellido_materno' : apellido_materno,
					'id_seccion_ine' : id_seccion_ine,
					'id_municipio' : id_municipio,
					'id_localidad' : id_localidad,
					'militante_partido' : militante_partido,
					'padrones_especificos' : padrones_especificos,
					'tipo_ciudadano' : tipo_ciudadano,
					'manzana' : manzana,
					'id_distrito_local' : id_distrito_local,
					'id_distrito_federal' : id_distrito_federal,
				}

				searchTable.push(data);
				var mapa = [];
				var data = {
						'order' : order[0][0],
						'order_tipo' : order[0][1],
					}
				mapa.push(data);
				if(tipo_mapa!='sin_mapa'){
					if(tipo_mapa=='mapa_calor'){
						url = "listaNominal/mapaCalor.php";
					}else{
						url = "listaNominal/mapa.php";
					}
					$.ajax({
						type: "POST",
						url: url,
						data: {searchTable:searchTable,mapa:mapa,searchOpciones:searchOpciones},
						async: true,
						success: function(data) {
							$("#mapaLoad").html(data);
						}
					});
				}
			});
		});
		
	</script> 
	<table id="lista_nominal-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
		<thead>
			<tr>
				<th>Sección</th>
				<th>Militante Partido</th>
				<th>Clave Elector</th>
				<th>C.U.R.P</th>
				<th>Nombre</th>
				<th>Apellido Paterno</th>
				<th>Apellido Materno</th>
				<th>Fecha Nacimiento</th>
				<th>Calle</th>
				<th>No. Int</th>
				<th>No. Ext</th>
				<th>Colonia</th>
				<th>Municipio</th>
				<th>Localidad</th>
				<th>Distrito Local</th>
				<th>Distrito Federal</th>
				<th>GPS</th>
			</tr> 
		</thead> 
	</table>
