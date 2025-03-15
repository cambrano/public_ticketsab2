<?php
	include __DIR__.'/../functions/security.php';
	@session_start();
	$postData = "''";
	if(!empty($_POST)){
		include '../functions/secciones_ine_ciudadanos.php';
		if (!empty($_POST)) {
			foreach ($_POST['searchTable'][0] as $key => $value) {
				$escapedValue = mysqli_real_escape_string($conexion, $value);
				$_POST['searchTable'][0][$key] = $escapedValue;
			}
			$postData = json_encode($_POST);
		}else{
			$postData = "''";
		}
		if(!empty($_POST['searchOpciones'])){
			setcookie("searchTableSIC", json_encode($_POST['searchTable'][0]),time()+(60*60*8),"/",false);
			setcookie("searchOpcionesSIC", json_encode($_POST['searchOpciones'][0]),time()+(60*60*8),"/",false);
			$searchOpciones = $_POST['searchOpciones'][0];
		}else{
			$searchOpciones = $_COOKIE["searchOpcionesSIC"];
			$searchOpciones = json_decode($searchOpciones,true);
		}
	}else{
		$searchOpciones = $_COOKIE["searchOpcionesSIC"];
		$searchOpciones = json_decode($searchOpciones,true);
	}
	if($_POST['mapa'][0]['order']==""){
		$order =0;
	}
	if($_POST['mapa'][0]['order_tipo']==""){
		$order_tipo ="desc";
	}
	?>
	<script>
		function edit(valor){
			link="seccionesIneCiudadanosSeccion/updaterapid.php?id="+valor; 
			var link2="seccionesIneCiudadanosSeccion/update.php";
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
			document.getElementById("btn_nuevo_seccion_ine_ciudadano").disabled = true;
			link="seccionesIneCiudadanosSeccion/createrapid.php";
			var link2="seccionesIneCiudadanosSeccion/create.php";
			dataString = 'urlink='+link2;  
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {
					//document.getElementById("btn_nuevo_seccion_ine_ciudadano").disabled = false;
				}
			});
			////
			//$("#homebody").load(link);
			$("#homebody").load(link+"?refresh=1");
			
		}

		function borrar(valor){
			link="seccionesIneCiudadanosSeccion/deleterapid.php?id="+valor; 
			var link2="seccionesIneCiudadanosSeccion/delete.php";
			dataString = 'urlink='+link2; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {}
				});
			//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
			$("#homebody").load(link+"&refresh=1");
		}
		function downloadExcel(){
			<?php
			if($pageService != $_COOKIE["pageService"]){
				if($pageService!=''){
					$_COOKIE["pageService"] = $pageService ;
				}
			}
			?>
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



			var id_seccion_ine_input = document.getElementById("id_seccion_ine");
			var id_seccion_ine_array = [];
			for (var i = 0; i < id_seccion_ine_input.length; i++) {
				if (id_seccion_ine_input.options[i].selected){
					id_seccion_ine_array.push(id_seccion_ine_input.options[i].value);
				}
			}
			id_seccion_ine = id_seccion_ine_array.join(",");

			var id_cuartel_input = document.getElementById("id_cuartel");
			var id_cuartel_array = [];
			for (var i = 0; i < id_cuartel_input.length; i++) {
				if (id_cuartel_input.options[i].selected){
					id_cuartel_array.push(id_cuartel_input.options[i].value);
				}
			}
			id_cuartel = id_cuartel_array.join(",");

			/*var id_seccion_ine = document.getElementById("id_seccion_ine").value;*/
			var clave = document.getElementById("clave").value;
			var plataforma = document.getElementById("plataforma").value;
			var sexo = document.getElementById("sexo").value;
			var nombre = document.getElementById("nombre").value;
			nombre = nombre.trim();
			var apellido_paterno = document.getElementById("apellido_paterno").value; 
			apellido_paterno = apellido_paterno.trim();
			var apellido_materno = document.getElementById("apellido_materno").value; 
			apellido_materno = apellido_materno.trim();
			var fecha_nacimiento_1 = document.getElementById("fecha_nacimiento_1").value;
			var fecha_nacimiento_2 = document.getElementById("fecha_nacimiento_2").value;

			var fecha_nacimiento_dia = document.getElementById("fecha_nacimiento_dia").value;
			var fecha_nacimiento_mes = document.getElementById("fecha_nacimiento_mes").value;
			var fecha_nacimiento_edad = document.getElementById("fecha_nacimiento_edad").value;

			var status_verificacion = document.getElementById("status_verificacion").value;


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

			var id_tipo_ciudadano_input = document.getElementById("id_tipo_ciudadano");
			var id_tipo_ciudadano_array = [];
			for (var i = 0; i < id_tipo_ciudadano_input.length; i++) {
				if (id_tipo_ciudadano_input.options[i].selected){
					id_tipo_ciudadano_array.push(id_tipo_ciudadano_input.options[i].value);
				}
			}
			id_tipo_ciudadano = id_tipo_ciudadano_array.join(",");

			var id_tipo_categoria_ciudadano_input = document.getElementById("id_tipo_categoria_ciudadano");
			var id_tipo_categoria_ciudadano_array = [];
			for (var i = 0; i < id_tipo_categoria_ciudadano_input.length; i++) {
				if (id_tipo_categoria_ciudadano_input.options[i].selected){
					id_tipo_categoria_ciudadano_array.push(id_tipo_categoria_ciudadano_input.options[i].value);
				}
			}
			id_tipo_categoria_ciudadano = id_tipo_categoria_ciudadano_array.join(",");

			var medio_registro_input = document.getElementById("medio_registro");
			var medio_registro_array = [];
			for (var i = 0; i < medio_registro_input.length; i++) {
				if (medio_registro_input.options[i].selected){
					medio_registro_array.push(medio_registro_input.options[i].value);
				}
			}
			medio_registro = medio_registro_array.join(",");

			var distancia_alert = document.getElementById("distancia_alert").value;

			var relacion = document.getElementById("relacion").value;

			var solo_padre = document.getElementById("solo_padre").value;
			var folio = document.getElementById("folio").value;

			var num_seguimiento = document.getElementById("num_seguimiento").value;

			var clave_elector = document.getElementById("clave_elector").value;
			var curp = document.getElementById("curp").value;


			var documentos_oficiales = document.getElementById("documentos_oficiales").value;
			var vigencia_documentos_oficiales = document.getElementById("vigencia_documentos_oficiales").value;
			var info_vigente = document.getElementById("info_vigente").value;

			
			var programas_apoyos = document.getElementById("programas_apoyos").value;

			var id_partido_legado_input = document.getElementById("id_partido_legado");
			var id_partido_legado_array = [];
			for (var i = 0; i < id_partido_legado_input.length; i++) {
				if (id_partido_legado_input.options[i].selected){
					id_partido_legado_array.push(id_partido_legado_input.options[i].value);
				}
			}
			id_partido_legado = id_partido_legado_array.join(",");

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

			var id_programa_apoyo_input = document.getElementById("id_programa_apoyo");
			var id_programa_apoyo_array = [];
			for (var i = 0; i < id_programa_apoyo_input.length; i++) {
				if (id_programa_apoyo_input.options[i].selected){
					id_programa_apoyo_array.push(id_programa_apoyo_input.options[i].value);
				}
			}
			id_programa_apoyo = id_programa_apoyo_array.join(",");

			var id_seccion_ine_grupo_input = document.getElementById("id_seccion_ine_grupo");
			var id_seccion_ine_grupo_array = [];
			for (var i = 0; i < id_seccion_ine_grupo_input.length; i++) {
				if (id_seccion_ine_grupo_input.options[i].selected){
					id_seccion_ine_grupo_array.push(id_seccion_ine_grupo_input.options[i].value);
				}
			}
			id_seccion_ine_grupo = id_seccion_ine_grupo_array.join(",");

			var tipo_seccion_input = document.getElementById("tipo_seccion");
			var tipo_seccion_array = [];
			for (var i = 0; i < tipo_seccion_input.length; i++) {
				if (tipo_seccion_input.options[i].selected){
					tipo_seccion_array.push(tipo_seccion_input.options[i].value);
				}
			}
			tipo_seccion = tipo_seccion_array.join(",");

			//opciones de busqueda
			var tipo_mapa = document.getElementById("tipo_mapa").value;
			var tipo_limite = document.getElementById("tipo_limite").value;
			var tipo_tabla_responsive = document.getElementById("tipo_tabla_responsive").checked;
			if(tipo_tabla_responsive==true){
				tipo_tabla_responsive=1
			}else{
				tipo_tabla_responsive=0
			}

			if(tipo_limite != 'x'){
				if(tipo_mapa=='sin_mapa' && tipo_limite >1000){
					$("#mapaLoad").html('Debe seleccionar algún tipo de mapa');
					document.getElementById("btn_descargarExcel").style.opacity= "0.6";
					document.getElementById("btn_descargarExcel").style.cursor= "not-allowed";
					document.getElementById("btn_descargarExcel").style.pointerEvents= "none";
					return false;
				}else{
					document.getElementById("btn_descargarExcel").style.opacity= "1";
					document.getElementById("btn_descargarExcel").style.cursor= "pointer";
					document.getElementById("btn_descargarExcel").style.pointerEvents= "initial";
				}
			}else{
				if(tipo_mapa=='sin_mapa'){
					document.getElementById("btn_descargarExcel").style.opacity= "0.6";
					document.getElementById("btn_descargarExcel").style.cursor= "not-allowed";
					document.getElementById("btn_descargarExcel").style.pointerEvents= "none";
					$("#mapaLoad").html('Debe seleccionar algún tipo de mapa');
					return false;
				}else{
					document.getElementById("btn_descargarExcel").style.opacity= "0.6";
					document.getElementById("btn_descargarExcel").style.cursor= "not-allowed";
					document.getElementById("btn_descargarExcel").style.pointerEvents= "none";
				}
			}



			var searchTable = [];
			var data = {
					'id_seccion_ine' : id_seccion_ine, 
					'clave' : clave, 
					'plataforma' : plataforma, 
					'sexo' : sexo,
					'nombre' : nombre,
					'apellido_paterno' : apellido_paterno,
					'apellido_materno' : apellido_materno,
					'fecha_nacimiento_1' : fecha_nacimiento_1,
					'fecha_nacimiento_2' : fecha_nacimiento_2,
					'fecha_nacimiento_dia' : fecha_nacimiento_dia,
					'fecha_nacimiento_mes' : fecha_nacimiento_mes,
					'fecha_nacimiento_edad' : fecha_nacimiento_edad,
					'id_seccion_ine_ciudadano_compartido' : id_seccion_ine_ciudadano_compartido,
					'id_tipo_ciudadano' : id_tipo_ciudadano,
					'medio_registro' : medio_registro,
					'distancia_alert' : distancia_alert,
					'id_tipo_categoria_ciudadano' : id_tipo_categoria_ciudadano,
					'status_verificacion' : status_verificacion,
					'relacion' : relacion,
					'solo_padre' : solo_padre,
					'id_cuartel' : id_cuartel,
					'folio' : folio,
					'num_seguimiento' : num_seguimiento,
					'clave_elector' : clave_elector, 
					'documentos_oficiales' :documentos_oficiales,
					'vigencia_documentos_oficiales' :vigencia_documentos_oficiales,
					'programas_apoyos' :programas_apoyos,
					'id_localidad' :id_localidad,
					'id_municipio' :id_municipio,
					'id_partido_legado' :id_partido_legado,
					'id_distrito_local' :id_distrito_local,
					'id_distrito_federal' :id_distrito_federal,
					'id_programa_apoyo' :id_programa_apoyo,
					'id_seccion_ine_grupo' :id_seccion_ine_grupo,
					'tipo_seccion' : tipo_seccion,
					'info_vigente' : info_vigente,
					'curp' : curp, 
				}
			//!searchTable.push(data);
			//!var link="seccionesIneCiudadanosSeccion/excel/index.php?cot=<?=$_COOKIE['pageService']?>"; 
			// Crear un formulario oculto en el documento
			// Crear un formulario oculto en el documento
			var form = document.createElement('form');
			form.method = 'POST';
			form.action = 'seccionesIneCiudadanosSeccion/excel/index.php?cot=<?=$_COOKIE['pageService']?>'; // URL de destino

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
		function ciudadano_categoria(valor){
			link="seccionesIneCiudadanosCategorias/index.php?cot="+valor; 
			var link2="seccionesIneCiudadanosCategorias/index.php";
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
		function encuestas(valor){
			link="seccionesIneCiudadanosEncuestas/index.php?cot="+valor; 
			var link2="seccionesIneCiudadanosEncuestas/index.php";
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

		function seguimientos(valor){
			link="seccionesIneCiudadanosSeguimientos/index.php?cot="+valor; 
			var link2="seccionesIneCiudadanosSeguimientos/index.php";
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

		function mostrarSeccionCiudadanos(valor){
			link="seccionesIneCiudadanosSeccion/index.php?cot="+valor; 
			var link2="seccionesIneCiudadanosSeccion/index.php";
			dataString = 'urlink='+link2;  
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {}
				});
			//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
			$("#homebody").load(link+"&refresh=1");
		}
		function campanas_mailing_ciudadano(valor){
			link="seccionesIneCiudadanosMailing/index.php?cot="+valor; 
			var link2="seccionesIneCiudadanosMailing/index.php";
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

		function campanas_sms_ciudadano(valor){
			link="seccionesIneCiudadanosSMS/index.php?cot="+valor; 
			var link2="seccionesIneCiudadanosSMS/index.php";
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
		function campanas_whatsapp_ciudadano(valor){
			link="seccionesIneCiudadanosWhatsapp/index.php?cot="+valor; 
			var link2="seccionesIneCiudadanosWhatsapp/index.php";
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

		function documentos_oficiales(valor){
			link="documentosOficialesCiudadanos/index.php?cot="+valor; 
			var link2="documentosOficialesCiudadanos/index.php";
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

		function programas_apoyos(valor){
			link="seccionesIneCiudadanosProgramasApoyos/index.php?cot="+valor; 
			var link2="seccionesIneCiudadanosProgramasApoyos/index.php";
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

		function militantes_partidos(valor){
			link="militantesPartidos/index.php?cot="+valor; 
			var link2="militantesPartidos/index.php";
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

		function secciones_ine_gira(valor){
			link="seccionesIneCiudadanosGiras/index.php?cot="+valor; 
			var link2="seccionesIneCiudadanosGiras/index.php";
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

		function secciones_ine_ciudadanos_grupos(valor){
			link="seccionesIneCiudadanosGrupos/index.php?cot="+valor; 
			var link2="seccionesIneCiudadanosGrupos/index.php";
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
		function secciones_ine_ciudadanos_expediente(valor){
			var link="seccionesIneCiudadanos/print/index.php?cot="+valor;
			//window.open(link);
			//window.open(link,'pdf','width=1280, height=460'); return false;
			//document.location = link;
			window.open(link); 
			return false;
		}
		function secciones_ine_ciudadanos_estructura(valor){
			var link="seccionesIneCiudadanos/show/index.php?cot="+valor;
			//window.open(link);
			//window.open(link,'pdf','width=1280, height=460'); return false;
			//document.location = link;
			//window.open(link); 
			window.open(link, 'pdf', 'width=800, height=460, location=no');
			return false;
		}
		function secciones_ine_ciudadanos_credencial(valor){
			var link="seccionesIneCiudadanos/print/credencialQR.php?cot="+valor;
			//window.open(link);
			//window.open(link,'pdf','width=1280, height=460'); return false;
			//document.location = link;
			window.open(link); 
			return false;
		}
	</script>
	<?php
	if($searchOpciones['tipo_limite'] !='x'){
		if($searchOpciones['tipo_limite'] <=1000){
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
					var dataTable = $('#secciones_ine_ciudadanos-tabla').DataTable( {
						"destroy": true,
						"responsive": <?= $searchOpciones['tipo_tabla_responsive']==''?'false':$searchOpciones['tipo_tabla_responsive'] ?>,
						<?php
						if($searchOpciones['tipo_limite']!='x'){
							?>
							"pageLength": <?= $searchOpciones['tipo_limite']==''?'10':$searchOpciones['tipo_limite'] ?>,
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
						"order": [[ <?= $order ?>, "<?= $order_tipo ?>" ]],
						"ordering": true,
						"searching": false,/* para el buscador*/
						"paging": true,/* para la paginacion y todo salga en una sola hora*/
						"aoColumnDefs": [
										{ "bSortable": false, "aTargets": [ 30 ] }
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
							url :"seccionesIneCiudadanosSeccion/secciones_ine_ciudadanos.php", // json datasource
							type: "post",  // method  , by default get
							data: {
								postData: <?php echo $postData; ?>
							},
							error: function(){  // error handling
								$(".secciones_ine_ciudadanos-tabla-error").html("");
								$("#secciones_ine_ciudadanos-tabla").append('<tbody class="secciones_ine_ciudadanos-tabla-error"><tr><th colspan="8">Registros no encontrados</th></tr></tbody>');
								$("#secciones_ine_ciudadanos-tabla_processing").css("display","none");
								
							}
						}
					});
	
	
					$('#secciones_ine_ciudadanos-tabla').on( 'order.dt', function () {
						var table = $('#secciones_ine_ciudadanos-tabla').dataTable();
						var api = table.api();
						var order = table.api().order(); // this has column and order details 
						//console.log(order[0][0]);
						//console.log(order[0][1]);
						/*var id_seccion_ine = document.getElementById("id_seccion_ine").value;*/
						var clave = document.getElementById("clave").value;
						var plataforma = document.getElementById("plataforma").value;
						var sexo = document.getElementById("sexo").value;
						var nombre = document.getElementById("nombre").value; 
						var apellido_paterno = document.getElementById("apellido_paterno").value; 
						var apellido_materno = document.getElementById("apellido_materno").value; 
						var fecha_nacimiento_1 = document.getElementById("fecha_nacimiento_1").value;
						var fecha_nacimiento_2 = document.getElementById("fecha_nacimiento_2").value;
						var fecha_nacimiento_dia = document.getElementById("fecha_nacimiento_dia").value;
						var fecha_nacimiento_mes = document.getElementById("fecha_nacimiento_mes").value;
						var fecha_nacimiento_edad = document.getElementById("fecha_nacimiento_edad").value;
						var status_verificacion = document.getElementById("status_verificacion").value;
	
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
	
						var id_tipo_ciudadano_input = document.getElementById("id_tipo_ciudadano");
						var id_tipo_ciudadano_array = [];
						for (var i = 0; i < id_tipo_ciudadano_input.length; i++) {
							if (id_tipo_ciudadano_input.options[i].selected){
								id_tipo_ciudadano_array.push(id_tipo_ciudadano_input.options[i].value);
							}
						}
						id_tipo_ciudadano = id_tipo_ciudadano_array.join(",");
	
						var id_tipo_categoria_ciudadano_input = document.getElementById("id_tipo_categoria_ciudadano");
						var id_tipo_categoria_ciudadano_array = [];
						for (var i = 0; i < id_tipo_categoria_ciudadano_input.length; i++) {
							if (id_tipo_categoria_ciudadano_input.options[i].selected){
								id_tipo_categoria_ciudadano_array.push(id_tipo_categoria_ciudadano_input.options[i].value);
							}
						}
						id_tipo_categoria_ciudadano = id_tipo_categoria_ciudadano_array.join(",");
	
						var medio_registro_input = document.getElementById("medio_registro");
						var medio_registro_array = [];
						for (var i = 0; i < medio_registro_input.length; i++) {
							if (medio_registro_input.options[i].selected){
								medio_registro_array.push(medio_registro_input.options[i].value);
							}
						}
						medio_registro = medio_registro_array.join(",");
	
						var distancia_alert = document.getElementById("distancia_alert").value;
						var relacion = document.getElementById("relacion").value;
						var solo_padre = document.getElementById("solo_padre").value;
						var folio = document.getElementById("folio").value;
						var num_seguimiento = document.getElementById("num_seguimiento").value;
						var clave_elector = document.getElementById("clave_elector").value;
						var documentos_oficiales = document.getElementById("documentos_oficiales").value;
						var vigencia_documentos_oficiales = document.getElementById("vigencia_documentos_oficiales").value;
						var programas_apoyos = document.getElementById("programas_apoyos").value;
	
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
	
						var id_partido_legado_input = document.getElementById("id_partido_legado");
						var id_partido_legado_array = [];
						for (var i = 0; i < id_partido_legado_input.length; i++) {
							if (id_partido_legado_input.options[i].selected){
								id_partido_legado_array.push(id_partido_legado_input.options[i].value);
							}
						}
						id_partido_legado = id_partido_legado_array.join(",");
	
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
	
						var id_seccion_ine_input = document.getElementById("id_seccion_ine");
						var id_seccion_ine_array = [];
						for (var i = 0; i < id_seccion_ine_input.length; i++) {
							if (id_seccion_ine_input.options[i].selected){
								id_seccion_ine_array.push(id_seccion_ine_input.options[i].value);
							}
						}
						id_seccion_ine = id_seccion_ine_array.join(",");
	
						var id_cuartel_input = document.getElementById("id_cuartel");
						var id_cuartel_array = [];
						for (var i = 0; i < id_cuartel_input.length; i++) {
							if (id_cuartel_input.options[i].selected){
								id_cuartel_array.push(id_cuartel_input.options[i].value);
							}
						}
						id_cuartel = id_cuartel_array.join(",");
	
						var id_programa_apoyo_input = document.getElementById("id_programa_apoyo");
						var id_programa_apoyo_array = [];
						for (var i = 0; i < id_programa_apoyo_input.length; i++) {
							if (id_programa_apoyo_input.options[i].selected){
								id_programa_apoyo_array.push(id_programa_apoyo_input.options[i].value);
							}
						}
						id_programa_apoyo = id_programa_apoyo_array.join(",");
	
						var id_seccion_ine_grupo_input = document.getElementById("id_seccion_ine_grupo");
						var id_seccion_ine_grupo_array = [];
						for (var i = 0; i < id_seccion_ine_grupo_input.length; i++) {
							if (id_seccion_ine_grupo_input.options[i].selected){
								id_seccion_ine_grupo_array.push(id_seccion_ine_grupo_input.options[i].value);
							}
						}
						id_seccion_ine_grupo = id_seccion_ine_grupo_array.join(",");
	
						var tipo_seccion_input = document.getElementById("tipo_seccion");
						var tipo_seccion_array = [];
						for (var i = 0; i < tipo_seccion_input.length; i++) {
							if (tipo_seccion_input.options[i].selected){
								tipo_seccion_array.push(tipo_seccion_input.options[i].value);
							}
						}
						tipo_seccion = tipo_seccion_array.join(",");
						var info_vigente = document.getElementById("info_vigente").value;
	
						//opciones de busqueda
						var tipo_mapa = document.getElementById("tipo_mapa").value;
						var tipo_limite = document.getElementById("tipo_limite").value;
						var tipo_tabla_responsive = document.getElementById("tipo_tabla_responsive").checked;
						if(tipo_tabla_responsive==false){
							tipo_tabla_responsive=0
						}else{
							tipo_tabla_responsive=1
						}
						searchOpciones = [];
						var data = {
							'tipo_tabla_responsive' : tipo_tabla_responsive,
							'tipo_limite' : tipo_limite,
							'tipo_mapa' : tipo_mapa,
						}
						searchOpciones.push(data);
						var searchTable = [];
						var data = {
							'id_seccion_ine' : id_seccion_ine,
							'clave' : clave, 
							'plataforma' : plataforma, 
							'sexo' : sexo,
							'nombre' : nombre,
							'apellido_paterno' : apellido_paterno,
							'apellido_materno' : apellido_materno,
							'fecha_nacimiento_1' : fecha_nacimiento_1,
							'fecha_nacimiento_2' : fecha_nacimiento_2,
							'fecha_nacimiento_dia' : fecha_nacimiento_dia,
							'fecha_nacimiento_mes' : fecha_nacimiento_mes,
							'fecha_nacimiento_edad' : fecha_nacimiento_edad,
							'id_seccion_ine_ciudadano_compartido' : id_seccion_ine_ciudadano_compartido,
							'id_tipo_ciudadano' : id_tipo_ciudadano,
							'medio_registro' : medio_registro,
							'distancia_alert' : distancia_alert,
							'id_tipo_categoria_ciudadano' : id_tipo_categoria_ciudadano,
							'status_verificacion' : status_verificacion,
							'relacion' : relacion,
							'solo_padre' : solo_padre,
							'id_cuartel' : id_cuartel,
							'folio' : folio,
							'num_seguimiento' : num_seguimiento,
							'clave_elector' : clave_elector,
							'documentos_oficiales' :documentos_oficiales,
							'vigencia_documentos_oficiales' :vigencia_documentos_oficiales,
							'programas_apoyos' :programas_apoyos,
							'id_localidad' :id_localidad,
							'id_municipio' :id_municipio,
							'id_partido_legado' :id_partido_legado,
							'id_distrito_local' :id_distrito_local,
							'id_distrito_federal' :id_distrito_federal,
							'id_programa_apoyo' :id_programa_apoyo,
							'id_seccion_ine_grupo' :id_seccion_ine_grupo,
							'tipo_seccion' : tipo_seccion,
							'info_vigente' : info_vigente,
						}
	
						searchTable.push(data);
						var mapa = [];
						var data = {
								'order' : order[0][0],
								'order_tipo' : order[0][1],
							}
						mapa.push(data);
						if(tipo_mapa=='mapa_calor'){
							url = "seccionesIneCiudadanosSeccion/mapaCalor.php";
						}else{
							url = "seccionesIneCiudadanosSeccion/mapa.php";
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
					});
	
					$('#secciones_ine_ciudadanos-tabla').css( 'display', 'table' );
					$('#secciones_ine_ciudadanos-tabla').resize();
					$('#secciones_ine_ciudadanos-tabla').DataTable().columns.adjust().responsive.recalc();
					$("#secciones_ine_ciudadanos-tabla_filter").css("display","none");  // hiding global search box
					$('#selectCheckbox').on("click", function(event){ // triggering delete one by one
						if( $('.checkselected:checked').length > 0 ){  // at-least one checkbox checked
							var ids = [];
							$('.checkselected').each(function(){
								if($(this).is(':checked')){ 
									ids.push($(this).val());
								}
							});
							var ids_string = ids.toString();  // array to string conversion   
							var link="seccionesIneCiudadanosSeccion/index.php?cot="+ids_string;   
							var link2="seccionesIneCiudadanosSeccion/index.php";
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
	
					$('#secciones_ine_ciudadanos-tabla').on( 'page.dt', function () {
						var info = dataTable.page.info();
						//mostrar en mapa 
	
						/*var id_seccion_ine = document.getElementById("id_seccion_ine").value;*/
						var clave = document.getElementById("clave").value;
						var plataforma = document.getElementById("plataforma").value;
						var sexo = document.getElementById("sexo").value;
						var nombre = document.getElementById("nombre").value; 
						var apellido_paterno = document.getElementById("apellido_paterno").value; 
						var apellido_materno = document.getElementById("apellido_materno").value; 
	
						var fecha_nacimiento_1 = document.getElementById("fecha_nacimiento_1").value;
						var fecha_nacimiento_2 = document.getElementById("fecha_nacimiento_2").value;
						var fecha_nacimiento_dia = document.getElementById("fecha_nacimiento_dia").value;
						var fecha_nacimiento_mes = document.getElementById("fecha_nacimiento_mes").value;
						var fecha_nacimiento_edad = document.getElementById("fecha_nacimiento_edad").value;
						var status_verificacion = document.getElementById("status_verificacion").value;
	
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
	
						var id_tipo_ciudadano_input = document.getElementById("id_tipo_ciudadano");
						var id_tipo_ciudadano_array = [];
						for (var i = 0; i < id_tipo_ciudadano_input.length; i++) {
							if (id_tipo_ciudadano_input.options[i].selected){
								id_tipo_ciudadano_array.push(id_tipo_ciudadano_input.options[i].value);
							}
						}
						id_tipo_ciudadano = id_tipo_ciudadano_array.join(",");
	
						var id_tipo_categoria_ciudadano_input = document.getElementById("id_tipo_categoria_ciudadano");
						var id_tipo_categoria_ciudadano_array = [];
						for (var i = 0; i < id_tipo_categoria_ciudadano_input.length; i++) {
							if (id_tipo_categoria_ciudadano_input.options[i].selected){
								id_tipo_categoria_ciudadano_array.push(id_tipo_categoria_ciudadano_input.options[i].value);
							}
						}
						id_tipo_categoria_ciudadano = id_tipo_categoria_ciudadano_array.join(",");
	
						var medio_registro_input = document.getElementById("medio_registro");
						var medio_registro_array = [];
						for (var i = 0; i < medio_registro_input.length; i++) {
							if (medio_registro_input.options[i].selected){
								medio_registro_array.push(medio_registro_input.options[i].value);
							}
						}
						medio_registro = medio_registro_array.join(",");
	
						var distancia_alert = document.getElementById("distancia_alert").value;
						var relacion = document.getElementById("relacion").value;
						var solo_padre = document.getElementById("solo_padre").value;
						var folio = document.getElementById("folio").value;
						var num_seguimiento = document.getElementById("num_seguimiento").value;
						var clave_elector = document.getElementById("clave_elector").value;
						var documentos_oficiales = document.getElementById("documentos_oficiales").value;
						var vigencia_documentos_oficiales = document.getElementById("vigencia_documentos_oficiales").value;
						var programas_apoyos = document.getElementById("programas_apoyos").value;
	
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
	
						var id_partido_legado_input = document.getElementById("id_partido_legado");
						var id_partido_legado_array = [];
						for (var i = 0; i < id_partido_legado_input.length; i++) {
							if (id_partido_legado_input.options[i].selected){
								id_partido_legado_array.push(id_partido_legado_input.options[i].value);
							}
						}
						id_partido_legado = id_partido_legado_array.join(",");
	
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
	
						var id_seccion_ine_input = document.getElementById("id_seccion_ine");
						var id_seccion_ine_array = [];
						for (var i = 0; i < id_seccion_ine_input.length; i++) {
							if (id_seccion_ine_input.options[i].selected){
								id_seccion_ine_array.push(id_seccion_ine_input.options[i].value);
							}
						}
						id_seccion_ine = id_seccion_ine_array.join(",");
	
						var id_cuartel_input = document.getElementById("id_cuartel");
						var id_cuartel_array = [];
						for (var i = 0; i < id_cuartel_input.length; i++) {
							if (id_cuartel_input.options[i].selected){
								id_cuartel_array.push(id_cuartel_input.options[i].value);
							}
						}
						id_cuartel = id_cuartel_array.join(",");
	
						var id_programa_apoyo_input = document.getElementById("id_programa_apoyo");
						var id_programa_apoyo_array = [];
						for (var i = 0; i < id_programa_apoyo_input.length; i++) {
							if (id_programa_apoyo_input.options[i].selected){
								id_programa_apoyo_array.push(id_programa_apoyo_input.options[i].value);
							}
						}
						id_programa_apoyo = id_programa_apoyo_array.join(",");
	
						var id_seccion_ine_grupo_input = document.getElementById("id_seccion_ine_grupo");
						var id_seccion_ine_grupo_array = [];
						for (var i = 0; i < id_seccion_ine_grupo_input.length; i++) {
							if (id_seccion_ine_grupo_input.options[i].selected){
								id_seccion_ine_grupo_array.push(id_seccion_ine_grupo_input.options[i].value);
							}
						}
						id_seccion_ine_grupo = id_seccion_ine_grupo_array.join(",");
	
						var tipo_seccion_input = document.getElementById("tipo_seccion");
						var tipo_seccion_array = [];
						for (var i = 0; i < tipo_seccion_input.length; i++) {
							if (tipo_seccion_input.options[i].selected){
								tipo_seccion_array.push(tipo_seccion_input.options[i].value);
							}
						}
						tipo_seccion = tipo_seccion_array.join(",");
	
						var info_vigente = document.getElementById("info_vigente").value;
	
						//opciones de busqueda
						var tipo_mapa = document.getElementById("tipo_mapa").value;
						var tipo_limite = document.getElementById("tipo_limite").value;
						var tipo_tabla_responsive = document.getElementById("tipo_tabla_responsive").checked;
						if(tipo_tabla_responsive==false){
							tipo_tabla_responsive=0
						}else{
							tipo_tabla_responsive=1
						}
						searchOpciones = [];
						var data = {
							'tipo_tabla_responsive' : tipo_tabla_responsive,
							'tipo_limite' : tipo_limite,
							'tipo_mapa' : tipo_mapa,
						}
						searchOpciones.push(data);
	
						var searchTable = [];
						var data = {   
							'id_seccion_ine' : id_seccion_ine,
							'clave' : clave, 
							'plataforma' : plataforma, 
							'sexo' : sexo,
							'nombre' : nombre,
							'apellido_paterno' : apellido_paterno,
							'apellido_materno' : apellido_materno,
							'fecha_nacimiento_1' : fecha_nacimiento_1,
							'fecha_nacimiento_2' : fecha_nacimiento_2,
							'fecha_nacimiento_dia' : fecha_nacimiento_dia,
							'fecha_nacimiento_mes' : fecha_nacimiento_mes,
							'fecha_nacimiento_edad' : fecha_nacimiento_edad,
							'id_seccion_ine_ciudadano_compartido' : id_seccion_ine_ciudadano_compartido,
							'id_tipo_ciudadano' : id_tipo_ciudadano,
							'medio_registro' : medio_registro,
							'distancia_alert' : distancia_alert,
							'id_tipo_categoria_ciudadano' : id_tipo_categoria_ciudadano,
							'status_verificacion' : status_verificacion,
							'relacion' : relacion,
							'solo_padre' : solo_padre,
							'id_cuartel' : id_cuartel,
							'folio' : folio,
							'num_seguimiento' : num_seguimiento,
							'clave_elector' : clave_elector,
							'documentos_oficiales' :documentos_oficiales,
							'vigencia_documentos_oficiales' :vigencia_documentos_oficiales,
							'programas_apoyos' :programas_apoyos,
							'id_localidad' :id_localidad,
							'id_municipio' :id_municipio,
							'id_partido_legado' :id_partido_legado,
							'id_distrito_local' :id_distrito_local,
							'id_distrito_federal' :id_distrito_federal,
							'id_programa_apoyo' :id_programa_apoyo,
							'id_seccion_ine_grupo' :id_seccion_ine_grupo,
							'tipo_seccion' : tipo_seccion,
							'info_vigente' : info_vigente,
						}
						searchTable.push(data);
						var mapa = [];
						var data = {
							'pagina' : info.page,
						}
						mapa.push(data);
						if(tipo_mapa=='mapa_calor'){
							url = "seccionesIneCiudadanosSeccion/mapaCalor.php";
						}else{
							url = "seccionesIneCiudadanosSeccion/mapa.php";
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
						//return false;
					});
	
				});
			</script> 
			<table id="secciones_ine_ciudadanos-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
				<thead>
					<tr>
						<th>Clave</th>
						<th>Plataforma</th>
						<th>Folio</th>
						<th>C.U.R.P</th>
						<th>Clave Elector</th>
						<th>Tipo Sección</th>
						<th>Sección</th>
						<th>Manzana</th>
						<th>D. Local</th>
						<th>D. Federal</th>
						<th>D.(km) Aprox</th>
						<th>Referenciado</th>
						<th>Tipo Ciudadano</th>
						<th>Nombre Completo</th>
						<th>Sexo</th>
						<th>F. Nacimiento</th>
						<th>Whatsapp</th>
						<th>Celular</th>
						<th>Teléfono</th>
						<th>Correo Electrónico</th>
						<th>Municipio</th>
						<th>Localidad</th>
						<th>Categorías</th>
						<th>Medio Registro</th>
						<th>Alerta Distancia</th>
						<th>Seguimientos</th>
						<th>Verificación</th>
						<th>Documentos Oficiales</th>
						<th>Programas Apoyos</th>
						<th>Programas Apoyos Categorías</th>
						<th>Militante</th>
						<th>Opciones</th>
					</tr>
				</thead> 
			</table> 
			<?php
		}
	}
?> 
