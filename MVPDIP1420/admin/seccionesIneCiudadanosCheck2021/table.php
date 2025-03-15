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

	if($_POST['mapa'][0]['order']==""){
		$order =0;
	}
	if($_POST['mapa'][0]['order_tipo']==""){
		$order_tipo ="desc";
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
			
			var dataTable = $('#secciones_ine_ciudadanos_check_2021-tabla').DataTable( {
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
				"order": [[ <?= $order ?>, "<?= $order_tipo ?>" ]],
				"ordering": true,
				"searching": false,/* para el buscador*/
				"paging": true,/* para la paginacion y todo salga en una sola hora*/
				"aoColumnDefs": [
								{ "bSortable": false, "aTargets": [ 5,6 ] }
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
					url :"seccionesIneCiudadanosCheck2021/secciones_ine_ciudadanos_check_2021.php", // json datasource
					type: "post",  // method  , by default get
					data: {
						postData: <?php echo $postData; ?>
					},
					error: function(){  // error handling
						$(".secciones_ine_ciudadanos_check_2021-tabla-error").html("");
						$("#secciones_ine_ciudadanos_check_2021-tabla").append('<tbody class="secciones_ine_ciudadanos_check_2021-tabla-error"><tr><th colspan="8">Registros no encontrados</th></tr></tbody>');
						$("#secciones_ine_ciudadanos_check_2021-tabla_processing").css("display","none");
						
					}
				}
			});

			$('#secciones_ine_ciudadanos_check_2021-tabla').on( 'order.dt', function () {
				var table = $('#secciones_ine_ciudadanos_check_2021-tabla').dataTable();
				var api = table.api();
				var order = table.api().order(); // this has column and order details 
				//console.log(order[0][0]);
				//console.log(order[0][1]);
				var id_seccion_ine_input = document.getElementById("id_seccion_ine");
				var id_seccion_ine_array = [];
				for (var i = 0; i < id_seccion_ine_input.length; i++) {
					if (id_seccion_ine_input.options[i].selected){
						id_seccion_ine_array.push(id_seccion_ine_input.options[i].value);
					}
				}
				id_seccion_ine = id_seccion_ine_array.join(",");

				/*var id_seccion_ine = document.getElementById("id_seccion_ine").value;*/
				var clave = document.getElementById("clave").value;
				var nombre = document.getElementById("nombre").value; 
				var apellido_paterno = document.getElementById("apellido_paterno").value; 
				var apellido_materno = document.getElementById("apellido_materno").value;
				var sexo = document.getElementById("sexo").value;
				var fecha_nacimiento_dia = document.getElementById("fecha_nacimiento_dia").value;
				var fecha_nacimiento_mes = document.getElementById("fecha_nacimiento_mes").value;
				var fecha_nacimiento_edad = document.getElementById("fecha_nacimiento_edad").value;
				var fecha_nacimiento_1 = document.getElementById("fecha_nacimiento_1").value;
				var fecha_nacimiento_2 = document.getElementById("fecha_nacimiento_2").value;
				var id_seccion_ine_ciudadano_compartido = document.getElementById("id_seccion_ine_ciudadano_compartido").value;

				/*
				var id_seccion_ine_ciudadano_compartido_input = document.getElementById("id_seccion_ine_ciudadano_compartido");
				var id_seccion_ine_ciudadano_compartido_array = [];
				for (var i = 0; i < id_seccion_ine_ciudadano_compartido_input.length; i++) {
					if (id_seccion_ine_ciudadano_compartido_input.options[i].selected){
						id_seccion_ine_ciudadano_compartido_array.push(id_seccion_ine_ciudadano_compartido_input.options[i].value);
					}
				}
				id_seccion_ine_ciudadano_compartido = id_seccion_ine_ciudadano_compartido_array.join(",");
				*/
				var checks_input = document.getElementById("checks");
				var checks_array = [];
				for (var i = 0; i < checks_input.length; i++) {
					if (checks_input.options[i].selected){
						checks_array.push(checks_input.options[i].value);
					}
				}
				checks = checks_array.join(",");
				var id_municipio_input = document.getElementById("id_municipio");
				var id_municipio_array = [];
				for (var i = 0; i < id_municipio_input.length; i++) {
					if (id_municipio_input.options[i].selected){
						id_municipio_array.push(id_municipio_input.options[i].value);
					}
				}
				id_municipio = id_municipio_array.join(",");

				var id_localidad_input = document.getElementById("id_localidad");
				var id_localidad_array = [];
				for (var i = 0; i < id_localidad_input.length; i++) {
					if (id_localidad_input.options[i].selected){
						id_localidad_array.push(id_localidad_input.options[i].value);
					}
				}
				id_localidad = id_localidad_array.join(",");


				var id_cuartel_input = document.getElementById("id_cuartel");
				var id_cuartel_array = [];
				for (var i = 0; i < id_cuartel_input.length; i++) {
					if (id_cuartel_input.options[i].selected){
						id_cuartel_array.push(id_cuartel_input.options[i].value);
					}
				}
				id_cuartel = id_cuartel_array.join(",");

				var id_distrito_local_input = document.getElementById("id_distrito_local");
				var id_distrito_local_array = [];
				for (var i = 0; i < id_distrito_local_input.length; i++) {
					if (id_distrito_local_input.options[i].selected){
						id_distrito_local_array.push(id_distrito_local_input.options[i].value);
					}
				}
				id_distrito_local = id_distrito_local_array.join(",");
				var id_distrito_federal_input = document.getElementById("id_distrito_federal");
				var id_distrito_federal_array = [];
				for (var i = 0; i < id_distrito_federal_input.length; i++) {
					if (id_distrito_federal_input.options[i].selected){
						id_distrito_federal_array.push(id_distrito_federal_input.options[i].value);
					}
				}
				id_distrito_federal = id_distrito_federal_array.join(",");
				var searchTable = [];
				var data = {   
					'id_seccion_ine' : id_seccion_ine, 
					'clave' : clave, 
					'nombre' : nombre,
					'apellido_paterno' : apellido_paterno,
					'apellido_materno' : apellido_materno,
					'sexo' : sexo,
					'fecha_nacimiento_dia' : fecha_nacimiento_dia,
					'fecha_nacimiento_mes' : fecha_nacimiento_mes,
					'fecha_nacimiento_edad' : fecha_nacimiento_edad,
					'fecha_nacimiento_1' : fecha_nacimiento_1,
					'fecha_nacimiento_2' : fecha_nacimiento_2,
					'id_seccion_ine_ciudadano_compartido' : id_seccion_ine_ciudadano_compartido,
					'checks' : checks,
					'id_localidad' :id_localidad,
					'id_municipio' :id_municipio,
					'id_distrito_local' :id_distrito_local,
					'id_distrito_federal' :id_distrito_federal,
					'id_cuartel' : id_cuartel,
				}
				searchTable.push(data);
				var mapa = [];
				var data = {
						'order' : order[0][0],
						'order_tipo' : order[0][1],
					}
				mapa.push(data);
				$.ajax({
					type: "POST",
					url: "seccionesIneCiudadanosCheck2021/mapa.php",
					data: {searchTable:searchTable,mapa:mapa},
					async: true,
					success: function(data) {
						$("#mapaLoad").html(data);
					}
				});
			});

			$('#secciones_ine_ciudadanos_check_2021-tabla').css( 'display', 'table' );
			$('#secciones_ine_ciudadanos_check_2021-tabla').resize();
			$('#secciones_ine_ciudadanos_check_2021-tabla').DataTable().columns.adjust().responsive.recalc();
			$("#secciones_ine_ciudadanos_check_2021-tabla_filter").css("display","none");  // hiding global search box
			$('#selectCheckbox').on("click", function(event){ // triggering delete one by one
				if( $('.checkselected:checked').length > 0 ){  // at-least one checkbox checked
					var ids = [];
					$('.checkselected').each(function(){
						if($(this).is(':checked')){ 
							ids.push($(this).val());
						}
					});
					var ids_string = ids.toString();  // array to string conversion   
					var link="seccionesIneCiudadanosCheck2021/index.php?cot="+ids_string;   
					var link2="seccionesIneCiudadanosCheck2021/index.php";
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
			$('#secciones_ine_ciudadanos_check_2021-tabla').on( 'page.dt', function () {
				var info = dataTable.page.info();
				//mostrar en mapa 
				var id_seccion_ine_input = document.getElementById("id_seccion_ine");
				var id_seccion_ine_array = [];
				for (var i = 0; i < id_seccion_ine_input.length; i++) {
					if (id_seccion_ine_input.options[i].selected){
						id_seccion_ine_array.push(id_seccion_ine_input.options[i].value);
					}
				}
				id_seccion_ine = id_seccion_ine_array.join(",");

				/*var id_seccion_ine = document.getElementById("id_seccion_ine").value;*/
				var clave = document.getElementById("clave").value;
				var nombre = document.getElementById("nombre").value; 
				var apellido_paterno = document.getElementById("apellido_paterno").value; 
				var apellido_materno = document.getElementById("apellido_materno").value;
				var sexo = document.getElementById("sexo").value;
				var fecha_nacimiento_dia = document.getElementById("fecha_nacimiento_dia").value;
				var fecha_nacimiento_mes = document.getElementById("fecha_nacimiento_mes").value;
				var fecha_nacimiento_edad = document.getElementById("fecha_nacimiento_edad").value;
				var fecha_nacimiento_1 = document.getElementById("fecha_nacimiento_1").value;
				var fecha_nacimiento_2 = document.getElementById("fecha_nacimiento_2").value;
				var id_seccion_ine_ciudadano_compartido = document.getElementById("id_seccion_ine_ciudadano_compartido").value;

				/*
				var id_seccion_ine_ciudadano_compartido_input = document.getElementById("id_seccion_ine_ciudadano_compartido");
				var id_seccion_ine_ciudadano_compartido_array = [];
				for (var i = 0; i < id_seccion_ine_ciudadano_compartido_input.length; i++) {
					if (id_seccion_ine_ciudadano_compartido_input.options[i].selected){
						id_seccion_ine_ciudadano_compartido_array.push(id_seccion_ine_ciudadano_compartido_input.options[i].value);
					}
				}
				id_seccion_ine_ciudadano_compartido = id_seccion_ine_ciudadano_compartido_array.join(",");
				*/
				var checks_input = document.getElementById("checks");
				var checks_array = [];
				for (var i = 0; i < checks_input.length; i++) {
					if (checks_input.options[i].selected){
						checks_array.push(checks_input.options[i].value);
					}
				}
				checks = checks_array.join(",");
				var id_municipio_input = document.getElementById("id_municipio");
				var id_municipio_array = [];
				for (var i = 0; i < id_municipio_input.length; i++) {
					if (id_municipio_input.options[i].selected){
						id_municipio_array.push(id_municipio_input.options[i].value);
					}
				}
				id_municipio = id_municipio_array.join(",");

				var id_localidad_input = document.getElementById("id_localidad");
				var id_localidad_array = [];
				for (var i = 0; i < id_localidad_input.length; i++) {
					if (id_localidad_input.options[i].selected){
						id_localidad_array.push(id_localidad_input.options[i].value);
					}
				}
				id_localidad = id_localidad_array.join(",");


				var id_cuartel_input = document.getElementById("id_cuartel");
				var id_cuartel_array = [];
				for (var i = 0; i < id_cuartel_input.length; i++) {
					if (id_cuartel_input.options[i].selected){
						id_cuartel_array.push(id_cuartel_input.options[i].value);
					}
				}
				id_cuartel = id_cuartel_array.join(",");

				var id_distrito_local_input = document.getElementById("id_distrito_local");
				var id_distrito_local_array = [];
				for (var i = 0; i < id_distrito_local_input.length; i++) {
					if (id_distrito_local_input.options[i].selected){
						id_distrito_local_array.push(id_distrito_local_input.options[i].value);
					}
				}
				id_distrito_local = id_distrito_local_array.join(",");
				var id_distrito_federal_input = document.getElementById("id_distrito_federal");
				var id_distrito_federal_array = [];
				for (var i = 0; i < id_distrito_federal_input.length; i++) {
					if (id_distrito_federal_input.options[i].selected){
						id_distrito_federal_array.push(id_distrito_federal_input.options[i].value);
					}
				}
				id_distrito_federal = id_distrito_federal_array.join(",");
				var searchTable = [];
				var data = {   
					'id_seccion_ine' : id_seccion_ine, 
					'clave' : clave, 
					'nombre' : nombre,
					'apellido_paterno' : apellido_paterno,
					'apellido_materno' : apellido_materno,
					'sexo' : sexo,
					'fecha_nacimiento_dia' : fecha_nacimiento_dia,
					'fecha_nacimiento_mes' : fecha_nacimiento_mes,
					'fecha_nacimiento_edad' : fecha_nacimiento_edad,
					'fecha_nacimiento_1' : fecha_nacimiento_1,
					'fecha_nacimiento_2' : fecha_nacimiento_2,
					'id_seccion_ine_ciudadano_compartido' : id_seccion_ine_ciudadano_compartido,
					'checks' : checks,
					'id_localidad' :id_localidad,
					'id_municipio' :id_municipio,
					'id_distrito_local' :id_distrito_local,
					'id_distrito_federal' :id_distrito_federal,
					'id_cuartel' : id_cuartel,
				}
				searchTable.push(data);
				var mapa = [];
				var data = {
						'pagina' : info.page,
					}
				mapa.push(data);
				$.ajax({
					type: "POST",
					url: "seccionesIneCiudadanosCheck2021/mapa.php",
					data: {searchTable:searchTable,mapa:mapa},
					async: true,
					success: function(data) {
						$("#mapaLoad").html(data);
					}
				});
				//return false;
			});
		});
		function entrega(value){
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");
			document.getElementById("entrega_"+value).disabled = false;
			var id_seccion_ine_ciudadano = value;
			var casilla_voto = [];
			var data = {
					'id_seccion_ine_ciudadano' : id_seccion_ine_ciudadano,
				}
			casilla_voto.push(data);
			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanosCheck2021/entrega_ciudadano.php",
				data: {casilla_voto: casilla_voto},
				async: true,
				success: function(data) {
					var res = data.substr(0,2);
					var hora = data.substr(2);
					if(res=="SI"){
						document.getElementById("entrega_"+value).classList.remove("btn-warning");
						document.getElementById("entrega_"+value).classList.add("btn-success");
						document.getElementById("entrega_img_"+value).src = "img/pasajero20.png";
						document.getElementById("entrega_"+value).disabled = true;
						document.getElementById("mensaje").classList.add("mensajeSucces");
						$("#entrega_hora_"+value).html(hora);
						document.getElementById("entrega_hora_"+value).style.backgroundColor="green";
						$("#mensaje").html("Gracias.");
					}else{
						document.getElementById("entrega_"+value).disabled = false;
						document.getElementById("mensaje").classList.add("mensajeError");
						$("#mensaje").html("Error, refresque gracias.");
						$("#mensaje").html(data);
					}
					
				}
			});
		}
		function recibe(value){
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");
			document.getElementById("recibe_"+value).disabled = false;

			var id_seccion_ine_ciudadano = value;

			var casilla_voto = [];
			var data = {
					'id_seccion_ine_ciudadano' : id_seccion_ine_ciudadano,
				}
			casilla_voto.push(data);
			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanosCheck2021/recibe_ciudadano.php",
				data: {casilla_voto: casilla_voto},
				async: true,
				success: function(data) {
					var res = data.substr(0,2);
					var hora = data.substr(2);
					if(res=="SI"){
						document.getElementById("recibe_"+value).classList.remove("btn-warning");
						document.getElementById("recibe_"+value).classList.add("btn-success");
						document.getElementById("recibe_img_"+value).src = "img/pasajero20.png";
						document.getElementById("recibe_"+value).disabled = true;
						document.getElementById("mensaje").classList.add("mensajeSucces");
						$("#recibe_hora_"+value).html(hora);
						document.getElementById("recibe_hora_"+value).style.backgroundColor="green";
						$("#mensaje").html("Gracias.");
					}else{
						document.getElementById("recibe_"+value).disabled = false;
						document.getElementById("mensaje").classList.add("mensajeError");
						$("#mensaje").html("Error, refresque gracias.");
						$("#mensaje").html(data);
					}
					
				}
			});
		}
		function downloadExcel(){
			<?php
			if($pageService != $_COOKIE["pageService"]){
				if($pageService!=''){
					$_COOKIE["pageService"] = $pageService ;
				}
			}
			?>
			var id_seccion_ine_input = document.getElementById("id_seccion_ine");
			var id_seccion_ine_array = [];
			for (var i = 0; i < id_seccion_ine_input.length; i++) {
				if (id_seccion_ine_input.options[i].selected){
					id_seccion_ine_array.push(id_seccion_ine_input.options[i].value);
				}
			}
			id_seccion_ine = id_seccion_ine_array.join(",");

			var clave = document.getElementById("clave").value;
			var nombre = document.getElementById("nombre").value; 
			var apellido_paterno = document.getElementById("apellido_paterno").value; 
			var apellido_materno = document.getElementById("apellido_materno").value;
			var sexo = document.getElementById("sexo").value;
			var fecha_nacimiento_dia = document.getElementById("fecha_nacimiento_dia").value;
			var fecha_nacimiento_mes = document.getElementById("fecha_nacimiento_mes").value;
			var fecha_nacimiento_edad = document.getElementById("fecha_nacimiento_edad").value;
			var fecha_nacimiento_1 = document.getElementById("fecha_nacimiento_1").value;
			var fecha_nacimiento_2 = document.getElementById("fecha_nacimiento_2").value;
			var id_seccion_ine_ciudadano_compartido = document.getElementById("id_seccion_ine_ciudadano_compartido").value;

			
			/*
			var id_seccion_ine_ciudadano_compartido_array = [];
			for (var i = 0; i < id_seccion_ine_ciudadano_compartido_input.length; i++) {
				if (id_seccion_ine_ciudadano_compartido_input.options[i].selected){
					id_seccion_ine_ciudadano_compartido_array.push(id_seccion_ine_ciudadano_compartido_input.options[i].value);
				}
			}
			id_seccion_ine_ciudadano_compartido = id_seccion_ine_ciudadano_compartido_array.join(",");
			*/

			var checks_input = document.getElementById("checks");
			var checks_array = [];
			for (var i = 0; i < checks_input.length; i++) {
				if (checks_input.options[i].selected){
					checks_array.push(checks_input.options[i].value);
				}
			}
			checks = checks_array.join(",");

			var id_municipio_input = document.getElementById("id_municipio");
			var id_municipio_array = [];
			for (var i = 0; i < id_municipio_input.length; i++) {
				if (id_municipio_input.options[i].selected){
					id_municipio_array.push(id_municipio_input.options[i].value);
				}
			}
			id_municipio = id_municipio_array.join(",");

			var id_localidad_input = document.getElementById("id_localidad");
			var id_localidad_array = [];
			for (var i = 0; i < id_localidad_input.length; i++) {
				if (id_localidad_input.options[i].selected){
					id_localidad_array.push(id_localidad_input.options[i].value);
				}
			}
			id_localidad = id_localidad_array.join(",");


			var id_cuartel_input = document.getElementById("id_cuartel");
			var id_cuartel_array = [];
			for (var i = 0; i < id_cuartel_input.length; i++) {
				if (id_cuartel_input.options[i].selected){
					id_cuartel_array.push(id_cuartel_input.options[i].value);
				}
			}
			id_cuartel = id_cuartel_array.join(",");

			var id_distrito_local_input = document.getElementById("id_distrito_local");
			var id_distrito_local_array = [];
			for (var i = 0; i < id_distrito_local_input.length; i++) {
				if (id_distrito_local_input.options[i].selected){
					id_distrito_local_array.push(id_distrito_local_input.options[i].value);
				}
			}
			id_distrito_local = id_distrito_local_array.join(",");
			var id_distrito_federal_input = document.getElementById("id_distrito_federal");
			var id_distrito_federal_array = [];
			for (var i = 0; i < id_distrito_federal_input.length; i++) {
				if (id_distrito_federal_input.options[i].selected){
					id_distrito_federal_array.push(id_distrito_federal_input.options[i].value);
				}
			}
			id_distrito_federal = id_distrito_federal_array.join(",");


			var searchTable = [];
			var data = {
					'id_seccion_ine' : id_seccion_ine, 
					'clave' : clave, 
					'nombre' : nombre,
					'apellido_paterno' : apellido_paterno,
					'apellido_materno' : apellido_materno,
					'sexo' : sexo,
					'fecha_nacimiento_dia' : fecha_nacimiento_dia,
					'fecha_nacimiento_mes' : fecha_nacimiento_mes,
					'fecha_nacimiento_edad' : fecha_nacimiento_edad,
					'fecha_nacimiento_1' : fecha_nacimiento_1,
					'fecha_nacimiento_2' : fecha_nacimiento_2,
					'id_seccion_ine_ciudadano_compartido' : id_seccion_ine_ciudadano_compartido,
					'checks' : checks,
					'id_localidad' :id_localidad,
					'id_municipio' :id_municipio,
					'id_distrito_local' :id_distrito_local,
					'id_distrito_federal' :id_distrito_federal,
					'id_cuartel' : id_cuartel,
			}
			//!searchTable.push(data);
			//!var link="seccionesIneCiudadanos/excel/index.php?cot=<?=$_COOKIE['pageService']?>"; 
			// Crear un formulario oculto en el documento
			// Crear un formulario oculto en el documento
			var form = document.createElement('form');
			form.method = 'POST';
			form.action = 'seccionesIneCiudadanosCheck2021/excel/index.php?cot=<?=$_COOKIE['pageService']?>'; // URL de destino

			// Iterar a través del objeto data y crear campos de entrada
			for (var key in data) {
				if (data.hasOwnProperty(key)) {
					var input = document.createElement('input');
					input.type = 'hidden';
					input.name = 'form_excel_' + key; // Agrega el prefijo "form_excel_" al nombre
					input.id = 'form_excel_' + key; // Agrega el prefijo "form_excel_" al ID
					input.value = data[key]; // Asigna el valor desde el objeto data

					// Agregar el campo de entrada al formulario
					form.appendChild(input);
				}
			}

			// Agregar el formulario al cuerpo del documento (opcional)
			document.body.appendChild(form);

			// Función para abrir la nueva página y enviar el formulario
			function openNewPageAndSubmitForm() {
				// Abre una nueva página
				var nuevaVentana = window.open('about:blank');
				
				// Asigna el formulario al contenido de la nueva ventana
				nuevaVentana.document.body.appendChild(form);
				
				// Enviar el formulario en la nueva ventana
				form.submit();
			}

			// Llamar a la función para abrir la nueva página y enviar el formulario
			openNewPageAndSubmitForm();


			
			//window.open(link);
			//window.open(link,'newWindow','width=1280, height=460'); return false;
			//document.location = link;
			//!window.open(link); 
		}
	</script> 
	<table id="secciones_ine_ciudadanos_check_2021-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
		<thead>
			<tr>
				<th>Clave</th>
				<th>Sección</th>
				<th>D.(km) Aprox</th>
				<th>Relacionado</th>
				<th>Nombre Completo</th>
				<!--<th>C. IN</th>-->
				<th>C. OUT</th>
				<th>F. Nacimiento</th>
				<th>Whatsapp</th>
				<th>Celular</th>
			</tr>
		</thead> 
	</table>  
