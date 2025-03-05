<?php
	include '../functions/switch_operaciones.php';
	$switch_operacionesPermisos = switch_operacionesPermisos();
	$registro = switch_operacionesPermisos('registro');
	if($switch_operacionesPermisos['registro']==false){
		?>
		<script type="text/javascript">
			document.getElementById("mensaje").classList.add("mensajeError");
			$("#mensaje").html("No tiene permiso");
			urlink="home.php";
			dataString = 'urlink='+urlink; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			$("#homebody").load(urlink);
		</script>
		<?php
		die;
	}
	$api_maps="AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI";
	$secciones_ineDatosMapa = secciones_ineDatosForm();
	$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa();
?>
	<input type="hidden" id="SelectForm" value="0">
	<script type="text/javascript">
		document.addEventListener("keyup", function(event) {
			if (event.key === 'º') {
				guardar();
			}
			if (event.key === 'F1') {
				borrarCamposLista();
			}
			if (event.key === 'Enter') {	
				searchTable();
			}
		});
		function seccionSelectForm(){
			SelectForm = document.getElementById("SelectForm").value;
			if( SelectForm == 0){
				id_seccion_ine = document.getElementById("id_seccion_ine").value;
				//console.log(id_seccion_ine);
				seccionSelect(id_seccion_ine)
			}else{
				return false;
			}
		}
		function seccionSelect(valor){
			$(".loader").fadeIn(10);
			document.getElementById("SelectForm").value=1;
			$('#id_seccion_ine').val(valor);
			$('#id_seccion_ine').select2().trigger('change');
			latitud = document.getElementById("latitud").value;
			longitud = document.getElementById("longitud").value;
			id_seccion_ine = valor;

			var mapa = [];
			var data = { 
				'id_seccion_ine' : id_seccion_ine,
				'latitud' : latitud,
				'longitud' : longitud,
			}
			mapa.push(data);
			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanos/mapa_seccion_ine.php",
				data: {mapa: mapa},
				success: function(data) {
					$("#mapaLoad").html(data);
				}
			});
			document.getElementById("SelectForm").value=0;
		}
		function seccionManzanaSelect(valor){
			$(".loader").fadeIn(10);
			document.getElementById("SelectForm").value=1;
			$('#id_seccion_ine').val(valor);
			$('#id_seccion_ine').select2().trigger('change');
			//latitud = document.getElementById("latitud").value;
			//longitud = document.getElementById("longitud").value;
			id_seccion_ine = valor;

			var mapa = [];
			var data = { 
				'id_seccion_ine' : id_seccion_ine,
				//'latitud' : latitud,
				//'longitud' : longitud,
			}
			mapa.push(data);
			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanos/mapa_seccion_ine_manzana.php",
				data: {mapa: mapa},
				success: function(data) {
					$("#mapaLoadManzana").html(data);
				}
			});
			document.getElementById("SelectForm").value=0;
		}
		function seccionManzanaSelectIgual(valor){
			$(".loader").fadeIn(10);
			document.getElementById("SelectForm").value=1;
			$('#id_seccion_ine').val(valor);
			$('#id_seccion_ine').select2().trigger('change');
			latitud = document.getElementById("latitud").value;
			longitud = document.getElementById("longitud").value;
			id_seccion_ine = valor;

			var mapa = [];
			var data = { 
				'id_seccion_ine' : id_seccion_ine,
				'latitud' : latitud,
				'longitud' : longitud,
			}
			mapa.push(data);
			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanos/mapa_seccion_ine_manzana.php",
				data: {mapa: mapa},
				success: function(data) {
					$("#mapaLoadManzana").html(data);
				}
			});
			document.getElementById("SelectForm").value=0;
		}
		function manzanaSelect(valor){
			var id_manzana_ine = valor;
			var manzana = [];
			var data = { 
				'id' : id_manzana_ine,
				'modulo' : 'secciones_ine_ciudadanos_form'
			}
			manzana.push(data);
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "manzanas_ine/manzanas_ine_data.php",
				data: {manzana: manzana},
				success: function(data) { 
					if(data.mensaje =='OK'){
						id_seccion_ine = document.getElementById("id_seccion_ine").value;
						if(id_seccion_ine != data.id_seccion_ine){
							$('#id_seccion_ine').val(data.id_seccion_ine);
							$('#id_seccion_ine').select2().trigger('change');
						}
						document.getElementById("manzana").value = data.manzana;
					}
				}
			});
		}
		
		function generar_mapa_coordenadas(){
			document.getElementById("sumbmit").disabled = true;
			var espacios_invalidos= /\s+/g;
			var latitud = document.getElementById("latitud").value; 
			latitud = latitud.replace(espacios_invalidos, '');
			if(latitud == ""){
				document.getElementById("latitud").focus(); 
				document.getElementById("sumbmit").disabled = false;
				return false;
			}
			var longitud = document.getElementById("longitud").value; 
			longitud = longitud.replace(espacios_invalidos, '');
			if(longitud == ""){
				document.getElementById("longitud").focus(); 
				document.getElementById("sumbmit").disabled = false;
				return false;
			}
			location['lat'] = latitud;
			location['lng'] = longitud;
			zoom=18;
			myMap(location,zoom);
			document.getElementById("sumbmit").disabled = false;
		}
		function generar_mapa() {
			var id_pais = document.getElementById("id_pais").value;
			if(id_pais == ""){
				document.getElementById("id_pais").style.border= "1px solid red";
				return false;
			}else{
				document.getElementById("id_pais").style.border= "";
			}
			var id_estado = document.getElementById("id_estado").value;
			if(id_estado == ""){
				document.getElementById("id_estado").style.border= "1px solid red";
				return false;
			}else{
				document.getElementById("id_estado").style.border= "";
			}
			var id_municipio = document.getElementById("id_municipio").value;
			if(id_municipio == ""){
				document.getElementById("id_municipio").style.border= "1px solid red";
				return false;
			}else{
				document.getElementById("id_municipio").style.border= "";
			}
			var id_localidad = document.getElementById("id_localidad").value;
			if(id_localidad == ""){
				document.getElementById("id_localidad").style.border= "1px solid red";
				return false;
			}else{
				document.getElementById("id_localidad").style.border= "";
			}
			var calle = document.getElementById("calle").value;
			var calle = calle.replace(/^\s+|\s+$/g, ""); 
			document.getElementById("calle").value=calle;
			if(calle == ""){
				document.getElementById("calle").style.border= "1px solid red";
				return false;
			}else{
				document.getElementById("calle").style.border= "";
			}
			var num_ext = document.getElementById("num_ext").value;
			var num_int = document.getElementById("num_int").value;

			var colonia = document.getElementById("colonia").value;
			var colonia = colonia.replace(/^\s+|\s+$/g, ""); 
			document.getElementById("colonia").value=colonia;
			if(colonia == ""){
				document.getElementById("colonia").style.border= "1px solid red";
				return false;
			}else{
				document.getElementById("colonia").style.border= "";
			}

			var codigo_postal = document.getElementById("codigo_postal").value;
			var codigo_postal = codigo_postal.replace(/^\s+|\s+$/g, ""); 
			document.getElementById("codigo_postal").value=codigo_postal;
			if(codigo_postal == ""){
				document.getElementById("codigo_postal").style.border= "1px solid red";
				return false;
			}else{
				document.getElementById("codigo_postal").style.border= "";
			}

			var direccion_completa = []; 
			var data = {
					'id_pais' : id_pais,
					'id_estado' : id_estado,
					'id_municipio' : id_municipio,
					'id_localidad' : id_localidad,
					'calle' : calle,
					'num_int' : num_int,
					'num_ext' : num_ext,
					'colonia' : colonia,
					'codigo_postal' : codigo_postal,
					'tipo' : 'datos_formulario',
			}
			direccion_completa.push(data);

			$.ajax({
				type: "POST",
				dataType: "json",
				url: "mapas/rapidapi_trueway.php",
				data: {direccion_completa: direccion_completa},
				success: function(data) { 
					if(data.mensaje =='OK' && data.api_mensaje==null){
						if(data.location==null){
							alert("Error al Generar El mapa, Contacte con el área de soporte.");
						}else{
							/*console.log(data.location);*/
							zoom=18;
							myMap(data.location,zoom);
						}
					}else{
						alert("Error al Generar El mapa, Contacte con el área de soporte."+data.api_mensaje);
					}

				}
			});
		}
		function generar_mapa_seccion(){
			var id_pais = document.getElementById("id_pais").value;
			if(id_pais == ""){
				document.getElementById("id_pais").style.border= "1px solid red";
				return false;
			}else{
				document.getElementById("id_pais").style.border= "";
			}
			var id_estado = document.getElementById("id_estado").value;
			if(id_estado == ""){
				document.getElementById("id_estado").style.border= "1px solid red";
				return false;
			}else{
				document.getElementById("id_estado").style.border= "";
			}
			var id_municipio = document.getElementById("id_municipio_ine").value;
			if(id_municipio == ""){
				document.getElementById("id_municipio_ine").style.border= "1px solid red";
				return false;
			}else{
				document.getElementById("id_municipio_ine").style.border= "";
			}
			var id_localidad = document.getElementById("id_localidad_ine").value;
			if(id_localidad == ""){
				document.getElementById("id_localidad_ine").style.border= "1px solid red";
				return false;
			}else{
				document.getElementById("id_localidad_ine").style.border= "";
			}
			var calle = document.getElementById("calle_ine").value;
			var calle = calle.replace(/^\s+|\s+$/g, ""); 
			document.getElementById("calle_ine").value=calle;
			if(calle == ""){
				document.getElementById("calle_ine").style.border= "1px solid red";
				return false;
			}else{
				document.getElementById("calle_ine").style.border= "";
			}
			var num_ext = document.getElementById("num_ext_ine").value;
			var num_int = document.getElementById("num_int_ine").value;

			var colonia = document.getElementById("colonia_ine").value;
			var colonia = colonia.replace(/^\s+|\s+$/g, ""); 
			document.getElementById("colonia_ine").value=colonia;
			if(colonia == ""){
				document.getElementById("colonia_ine").style.border= "1px solid red";
				return false;
			}else{
				document.getElementById("colonia_ine").style.border= "";
			}

			var codigo_postal = document.getElementById("codigo_postal_ine").value;
			var codigo_postal = codigo_postal.replace(/^\s+|\s+$/g, ""); 
			document.getElementById("codigo_postal_ine").value=codigo_postal;
			if(codigo_postal == ""){
				document.getElementById("codigo_postal_ine").style.border= "1px solid red";
				return false;
			}else{
				document.getElementById("codigo_postal_ine").style.border= "";
			}
			var direccion_completa = []; 
			var data = {
					'id_pais' : id_pais,
					'id_estado' : id_estado,
					'id_municipio' : id_municipio,
					'id_localidad' : id_localidad,
					'calle' : calle,
					'num_int' : num_int,
					'num_ext' : num_ext,
					'colonia' : colonia,
					'codigo_postal' : codigo_postal,
					'tipo' : 'datos_formulario',
			}
			direccion_completa.push(data);
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "mapas/rapidapi_trueway.php",
				data: {direccion_completa: direccion_completa},
				success: function(data) { 
					if(data.mensaje =='OK' && data.api_mensaje==null){
						if(data.location==null){
							alert("Error al Generar El mapa, Contacte con el área de soporte.");
						}else{

							var mapa = [];
							var data = { 
								'location' : data.location,
								//'latitud' : latitud,
								//'longitud' : longitud,
							}
							mapa.push(data);
							$.ajax({
								type: "POST",
								url: "seccionesIneCiudadanos/mapa_seccion_ine_manzana_gps.php",
								data: {mapa: mapa},
								success: function(data) {
									$("#mapaLoadManzana").html(data);
								}
							});
						}
					}else{
						alert("Error al Generar El mapa, Contacte con el área de soporte."+data.api_mensaje);
					}

				}
			});
		}

		function locationEstado(){
			var id_estado = document.getElementById("id_estado").value;
			if(id_estado == ""){
				document.getElementById("id_estado").style.border= "1px solid red";
				document.getElementById("id_municipio").style.border= "";
				document.getElementById("id_municipio").value="";
				var dataString = 'id_estado=x';
				$.ajax({
					type: "POST",
					url: "municipios/ajax.php",
					data: dataString,
					success: function(data) {
						$("#id_municipio").html(data);
					}
				});
			}else{
				document.getElementById("id_estado").style.border= "";
				document.getElementById("id_municipio").style.border= "";
				var dataString = 'id_estado='+id_estado;
				$.ajax({
					type: "POST",
					url: "municipios/ajax.php",
					data: dataString,
					success: function(data) {
						$("#id_municipio").html(data);
					}
				});
				var dataString = 'id_estado='+id_estado+'&tipo=coordenadas';
				$.ajax({
					type: "POST",
					dataType: "json",
					url: "mapas/ajax.php",
					data: dataString,
					success: function(data) { 
						zoom=8;
						myMap(data,zoom);
					}
				});

			}
		}
		function locationMunicipio() {
			var id_estado = document.getElementById("id_estado").value;
			var id_municipio = document.getElementById("id_municipio").value;
			var id_municipio = id_municipio.replace(/^\s+|\s+$/g, ""); 
			document.getElementById("id_municipio").value=id_municipio;
			if(id_municipio == ""){
				document.getElementById("id_municipio").style.border= "1px solid red";
				document.getElementById("id_localidad").style.border= "";
				document.getElementById("id_localidad").value="";
				var dataString = 'id_estado=x';
				$.ajax({
					type: "POST",
					url: "localidades/ajax.php",
					data: dataString,
					success: function(data) {
						$("#id_localidad").html(data);
					}
				});
			}else{
				document.getElementById("id_municipio").style.border= "";
				document.getElementById("id_localidad").style.border= "";
				var dataString = 'id_estado='+id_estado+'&id_municipio='+id_municipio;
				$.ajax({
					type: "POST",
					url: "localidades/ajax.php",
					data: dataString,
					success: function(data) {
						$("#id_localidad").html(data);
					}
				});
				valid_ine = document.getElementById("valid_ine").value;
				if(valid_ine==1){
					return false;
				}
				var dataString = 'id_municipio='+id_municipio+'&tipo=coordenadas';
				$.ajax({
					type: "POST",
					dataType: "json",
					url: "mapas/ajax.php",
					data: dataString,
					success: function(data) { 
						zoom=14;
						myMap(data,zoom);
					}
				});
			}
		}

		function locationLocalidad() {
			valid_ine = document.getElementById("valid_ine").value;
			if(valid_ine==1){
				return false;
			}
			var id_localidad = document.getElementById("id_localidad").value;
			var dataString = 'id_localidad='+id_localidad+'&tipo=coordenadas';
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "mapas/ajax.php",
				data: dataString,
				success: function(data) { 
					zoom=14;
					myMap(data,zoom);
				}
			});
		}

		function locationMunicipio_ine() {
			var id_estado = document.getElementById("id_estado").value;
			var id_municipio_ine = document.getElementById("id_municipio_ine").value;
			var id_municipio_ine = id_municipio_ine.replace(/^\s+|\s+$/g, ""); 
			if(id_municipio_ine == ""){
				document.getElementById("id_municipio_ine").style.border= "1px solid red";
				document.getElementById("id_localidad_ine").style.border= "";
				document.getElementById("id_localidad_ine").value="";
				var dataString = 'id_estado=x';
				$.ajax({
					type: "POST",
					url: "localidades/ajax.php",
					data: dataString,
					success: function(data) {
						$("#id_localidad_ine").html(data);
					}
				});
			}else{
				document.getElementById("id_municipio_ine").style.border= "";
				document.getElementById("id_localidad_ine").style.border= "";
				var dataString = 'id_estado='+id_estado+'&id_municipio='+id_municipio_ine;
				$.ajax({
					type: "POST",
					url: "localidades/ajax.php",
					data: dataString,
					success: function(data) {
						$("#id_localidad_ine").html(data);
					}
				});
			}
		}
		$( function() {
			$( "#fecha_nacimiento" ).datepicker({
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd',
				yearRange: "1890:2005",
				defaultDate: "2003-01-01",
				onSelect: function (date) { 
					document.getElementById("fecha_nacimiento").style.border= "";
				}
			});
			$( "#fecha_rnm" ).datepicker({ 
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd', 
				onSelect: function (date) { 
					document.getElementById("fecha_rnm").style.border= "";
				}
			});
			$('#hora_rnm').timepicker({ 
				timeFormat: 'H:i:s',
				showDuration: true,
				interval: 15,
				scrollDefault: "now",
				onSelect: function (date) { 
					document.getElementById("hora_rnm").style.border= "";
				}
			}); 
			$( "#fecha_emision" ).datepicker({ 
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd', 
				onSelect: function (date) { 
					document.getElementById("fecha_emision").style.border= "";
				}
			});
			$('#hora_emision').timepicker({ 
				timeFormat: 'H:i:s',
				showDuration: true,
				interval: 15,
				scrollDefault: "now",
				onSelect: function (date) { 
					document.getElementById("hora_emision").style.border= "";
				}
			}); 
			$( '#monstar_contraseña' ).on( 'click', function() {
				if( $(this).is(':checked') ){
					// Hacer algo si el checkbox ha sido seleccionado
					$('#labelMostrar').html("Ocultar");
					document.getElementById("password").type = "text";
					document.getElementById("password1").type = "text";
				} else {
					// Hacer algo si el checkbox ha sido deseleccionado
					$('#labelMostrar').html("Mostrar");
					document.getElementById("password").type = "password";
					document.getElementById("password1").type = "password";
				}
			});
			$("#usuario").keyup(function(){              
					var ta      =   $("#usuario");
					letras      =   ta.val().replace(/ /g, "");
					ta.val(letras)
			}); 
			$("#password").keyup(function(){              
					var ta      =   $("#password");
					letras      =   ta.val().replace(/ /g, "");
					ta.val(letras)
			}); 
			$("#password1").keyup(function(){              
					var ta      =   $("#password1");
					letras      =   ta.val().replace(/ /g, "");
					ta.val(letras)
			});
			$( "#seccion_ine_ciudadano_compartido_nombre" ).autocomplete({
				maxShowItems: 5,
				source: function( request, response ) {
					$.ajax({
						url: "seccionesIneCiudadanos/search.php",
						type: 'post',
						dataType: "json",
						data: {
							search: request.term
						},
						success: function( data ) {
							if(data==""){
								document.getElementById("id_seccion_ine_ciudadano_compartido").value = "";
							}
							if(data==null){
								document.getElementById("id_seccion_ine_ciudadano_compartido").value = "";
							}
							response( data );
						}
					});
				},
				select: function (event, ui) {
					if(ui.item.label==null){
						document.getElementById("seccion_ine_ciudadano_compartido_nombre").value = "";
						document.getElementById("id_seccion_ine_ciudadano_compartido").value = "";
					}else{
						$('#seccion_ine_ciudadano_compartido_nombre').val(ui.item.label); // display the selected text
						$('#id_seccion_ine_ciudadano_compartido').val(ui.item.value); // save selected id to input
					}
					return false;
				}
			});
		});
		function copiar_direccion_acual(){
			var espacios_invalidos= /\s+/g;
			var id_municipio = document.getElementById("id_municipio").value; 
			if(id_municipio == ""){
				document.getElementById("id_municipio").focus(); 
				$("#mensaje").html("Municipio requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			//alert(id_municipio);
			var id_localidad = document.getElementById("id_localidad").value; 
			if(id_localidad == ""){
				document.getElementById("id_localidad").focus(); 
				$("#mensaje").html("Localidad requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var calle = document.getElementById("calle").value; 
			if(calle == ""){
				document.getElementById("calle").focus(); 
				$("#mensaje").html("Calle requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var num_ext = document.getElementById("num_ext").value;
			var num_int = document.getElementById("num_int").value;
			//alert(calle);
			var colonia = document.getElementById("colonia").value; 
			if(colonia == ""){
				document.getElementById("colonia").focus(); 
				$("#mensaje").html("Colonia requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var codigo_postal = document.getElementById("codigo_postal").value;
			codigo_postal = codigo_postal.replace(espacios_invalidos, '');
			if(codigo_postal == ""){
				document.getElementById("codigo_postal").focus(); 
				$("#mensaje").html("Codigo Postal requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			//$('#id_municipio_ine').val(id_municipio);
			//$('#id_municipio_ine').select2().trigger('change');

			//$('#id_localidad_ine').val(id_localidad);
			//$('#id_localidad_ine').select2().trigger('change');


			$('#id_municipio_ine').val(id_municipio);
			$('#id_municipio_ine').select2().trigger('change');
			document.getElementById("id_municipio").style.border= "";
			document.getElementById("id_localidad").style.border= "";
			var dataString = 'id_estado='+id_estado+'&id_municipio='+id_municipio+'&id_localidad='+id_localidad;
			$.ajax({
				type: "POST",
				url: "localidades/ajax.php",
				data: dataString,
				success: function(data) {
					$("#id_localidad_ine").html(data);
				}
			});
			$('#id_localidad_ine').val(id_localidad);
			$('#id_localidad_ine').select2().trigger('change');


			document.getElementById("calle_ine").value = calle;
			document.getElementById("num_ext_ine").value = num_ext;
			document.getElementById("num_int_ine").value = num_int;
			document.getElementById("colonia_ine").value = colonia;
			document.getElementById("codigo_postal_ine").value = codigo_postal;

		}
		function buscar_clave_electoral(){
			var clave_elector = document.getElementById("clave_elector").value; 
			if(clave_elector == ""){
				document.getElementById("clave_elector").focus();
				return false;
			}
			var dataString = 'search_clave_elector='+clave_elector;
			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanos/search_clave_elector.php",
				data: dataString,
				dataType: 'json',
				success: function(data) {
					//console.log(data);
					//console.table(data);
					$("#ine_nombre").html(data.nombre);
					$("#ine_apellido_paterno").html(data.apellido_paterno);
					$("#ine_apellido_materno").html(data.apellido_materno);
					$("#ine_fecha_nacimiento").html(data.fecha_nacimiento);
					$("#ine_sexo").html(data.sexo);
					$("#ine_seccion").html(data.seccion);
					$("#ine_manzana").html(data.manzana);
					$("#ine_calle").html(data.calle);
					$("#ine_no_exterior").html(data.no_exterior);
					$("#ine_no_interior").html(data.no_interior);
					$("#ine_colonia").html(data.colonia);
					$("#ine_codigo_postal").html(data.codigo_postal);
					$("#ine_ocr").html(data.ocr);
					$("#ine_curp").html(data.curp);

					if(data.status_verificacion == 1){
						document.getElementById("valid_ine").value = 1;
						$('#status_verificacion').val(data.status_verificacion);
						$('#status_verificacion').select2().trigger('change');

						document.getElementById("nombre").value = data.nombre;
						document.getElementById("apellido_paterno").value = data.apellido_paterno;
						document.getElementById("apellido_materno").value = data.apellido_materno;
						document.getElementById("fecha_nacimiento").value = data.fecha_nacimiento;
						document.getElementById("codigo_postal").value = data.codigo_postal;
						document.getElementById("colonia").value = data.colonia;
						document.getElementById("ocr").value = data.ocr;
						document.getElementById("latitud").value = data.latitud;
						document.getElementById("longitud").value = data.longitud;
						document.getElementById("colonia_ine").value = data.colonia;
						document.getElementById("manzana").value = data.manzana;
						document.getElementById("codigo_postal_ine").value = data.codigo_postal;

						if(data.no_exterior == 'No Encontrado'){
							data.no_exterior = '';
						}else{
							data.no_exterior = data.no_exterior;
						}
						if(data.no_interior == 'No Encontrado'){
							data.no_interior = '';
						}else{
							data.no_interior = data.no_interior;
						}
						document.getElementById("calle").value = data.calle;
						document.getElementById("num_ext").value = data.no_exterior;
						document.getElementById("num_int").value = data.no_interior;

						document.getElementById("calle_ine").value = data.calle;
						document.getElementById("num_ext_ine").value = data.no_exterior;
						document.getElementById("num_int_ine").value = data.no_interior;

						$('#sexo').val(data.sexo);
						$('#sexo').select2().trigger('change');
						
						$('#id_seccion_ine').val(data.id_seccion_ine);
						$('#id_seccion_ine').select2().trigger('change');

						//document.getElementById("id_municipio").onchange="";
						$('#id_municipio').val(data.id_municipio);
						$('#id_municipio').select2().trigger('change');
						document.getElementById("id_municipio").style.border= "";
						document.getElementById("id_localidad").style.border= "";
						var dataString = 'id_estado='+id_estado+'&id_municipio='+data.id_municipio+'&id_localidad='+data.id_localidad;
						$.ajax({
							type: "POST",
							url: "localidades/ajax.php",
							data: dataString,
							success: function(data) {
								$("#id_localidad").html(data);
							}
						});



						//document.getElementById("id_localidad").onchange="";
						$('#id_localidad').val(data.id_localidad);
						$('#id_localidad').select2().trigger('change');
						//document.getElementById("id_localidad").onBlur=locationLocalidad(this);

						$('#id_municipio_ine').val(data.id_municipio);
						$('#id_municipio_ine').select2().trigger('change');
						document.getElementById("id_municipio").style.border= "";
						document.getElementById("id_localidad").style.border= "";
						var dataString = 'id_estado='+id_estado+'&id_municipio='+data.id_municipio+'&id_localidad='+data.id_localidad;
						$.ajax({
							type: "POST",
							url: "localidades/ajax.php",
							data: dataString,
							success: function(data) {
								$("#id_localidad_ine").html(data);
							}
						});
						$('#id_localidad_ine').val(data.id_localidad);
						$('#id_localidad_ine').select2().trigger('change');

						if(data.curp == clave_elector){
							// calcula CURP
							var appellido_paterno = data.apellido_paterno;
							var appellido_materno = data.apellido_materno;
							var nombre = data.nombre;
							const arrayfecha_nacimiento = data.fecha_nacimiento.split("-");
							var fecha_nacimiento = arrayfecha_nacimiento[0].substr(-2)+arrayfecha_nacimiento[1]+arrayfecha_nacimiento[2];
							var sexo = data.sexo.substr(0,1);  //h =  hombre  m= mujer
							var estado = data.estado_dig_2;
							//FILTRA ACENTOS
							var apellido_paterno_f = CurpFiltraAcentos(appellido_paterno.toLowerCase());
							var apellido_materno_f = CurpFiltraAcentos(appellido_materno.toLowerCase());
							var nombre_f = CurpFiltraAcentos(nombre.toLowerCase());
							//GUARDA NOMBRE ORIGINAL PARA GENERAR HOMOCLAVE
							var apellido_paterno_orig = apellido_paterno_f;
							var apellido_materno_orig = apellido_materno_f;
							var nombre_orig = nombre_f;
							//ELIMINA PALABRAS SOBRANTES DE LOS NOMBRES
							apellido_paterno_f = CurpFiltraNombres(apellido_paterno_f);
							apellido_materno_f = CurpFiltraNombres(apellido_materno_f);
							nombre_f = CurpFiltraNombres(nombre_f);
							var curp = CalculaCURP(nombre_f.toUpperCase(), apellido_paterno_f.toUpperCase(), apellido_materno_f.toUpperCase(), fecha_nacimiento, sexo, estado);
							//console.log(curp);
							$("#ine_curp").html(curp);
							document.getElementById("curp").value = curp;
							//calcula RFC
							if (apellido_paterno_f.length > 0 && apellido_materno_f.length > 0) {
								if (apellido_paterno_f.length < 3) {
									rfc = RFCApellidoCorto(apellido_paterno_f, apellido_materno_f, nombre_f);
								} else {
									rfc = RFCArmalo(apellido_paterno_f, apellido_materno_f, nombre_f);
								}
							}
							if (apellido_paterno_f.length == 0 && apellido_materno_f.length > 0) {
								rfc = RFCUnApellido(nombre_f, apellido_materno_f);
							}
							if (apellido_paterno_f.length > 0 && apellido_materno_f.length == 0) {
								rfc = RFCUnApellido(nombre_f, apellido_paterno_f);
							}
							rfc = RFCQuitaProhibidas(rfc);
							rfc = rfc.toUpperCase() + fecha_nacimiento + homonimia(apellido_paterno_orig, apellido_materno_orig, nombre_orig);
							rfc = RFCDigitoVerificador(rfc);
							document.getElementById("rfc").value = rfc;

						}else{
							document.getElementById("curp").value = data.curp;
							$("#ine_curp").html(data.curp);
						}
						document.getElementById("valid_ine").value = '';
						zoom=14;
						const location = {lng:data.longitud, lat:data.latitud};
						myMap(location,zoom);
					}else{
						$("#status_verificacion").select2("val", "0");
					}
					if(data.disponible==true){
						$("#mensaje_ine_disponible").html("&nbsp;");
						document.getElementById("mensaje_ine_disponible").classList.remove("mensajeError");
						$("#mensaje_ine_disponible").html("Disponible,Este ciudadano no esta en el sistema."); 
						document.getElementById("mensaje_ine_disponible").classList.add("mensajeSucces");
					}else{
						document.getElementById("mensaje_ine_disponible").classList.add("mensajeError");
						$("#mensaje_ine_disponible").html("No Disponible, Este ciudadano esta en el sistema.");
					}
				}
			});
		}
		function buscar_curp(){
			var espacios_invalidos= /\s+/g;
			var curp = document.getElementById("curp").value;
			curp = curp.replace(espacios_invalidos, '');
			if(curp == ""){
				document.getElementById("curp").focus();
				return false;
			}
			var usuario = 'prueba';
			var contrasenia = 'sC%7D9pW1Q%5Dc';
			//var valor = 'CAUA880807HYNMCL01'; //reemplazar por el curp a consultar
			var metodo = 'curp';
			var dataString = '?m='+metodo+'&user='+usuario+'&pass='+contrasenia+'&val='+curp;
			$.ajax({
				type: "GET", 
				url: "https://conectame.ddns.net/rest/api.php"+dataString,
				success: function(response){
					//console.log(response['Response']);
					
					if(response['Response']=='correct'){
						document.getElementById("nombre").value = response['Nombre'];
						document.getElementById("apellido_paterno").value = response['Paterno'];
						document.getElementById("apellido_materno").value = response['Materno'];
						document.getElementById("sexo").value = response['Sexo'];
						document.getElementById("fecha_nacimiento").value = response['FechaNacimiento'];
						document.getElementById("rfc").value = response['DatosFiscales']['Rfc'];
						if(response['Sexo']=='H'){
							sexo='Hombre';
						}else{
							sexo='Mujer';
						}
						$('#sexo').val(sexo);
						$('#sexo').select2().trigger('change');
					}else{
						$("#mensaje").html("Error en el CURP, validar por favor.");
						document.getElementById("mensaje").classList.add("mensajeError");
					}
				},
				error: function(response){
					//console.log(response);
					$("#mensaje").html("Error en el CURP, validar por favor.");
					document.getElementById("mensaje").classList.add("mensajeError");
				}
			});
		}
		function CurpFiltraNombres(strTexto) {
			var i = 0;
			var strArPalabras = [".", ",", "de ", "del ", "la ", "los ", "las ", "y ", "mc ", "mac ", "von ", "van "];
			for (i = 0; i <= strArPalabras.length; i++) {
				//alert(strArPalabras[i]);
				strTexto = strTexto.replace(strArPalabras[i], "");
			}

			strArPalabras = ["jose ", "maria ", "j ", "ma "];
			for (i = 0; i <= strArPalabras.length; i++) {
				//alert(strArPalabras[i]);
				strTexto = strTexto.replace(strArPalabras[i], "");
			}

			switch (strTexto.substr(0, 2)) {
				case 'ch':
					strTexto = strTexto.replace('ch', 'c')
					break;
				case 'll':
					strTexto = strTexto.replace('ll', 'l')
					break;
			}

			return strTexto
		}
		function CurpFiltraAcentos(strTexto) {
			strTexto = strTexto.replace('á', 'a');
			strTexto = strTexto.replace('é', 'e');
			strTexto = strTexto.replace('í', 'i');
			strTexto = strTexto.replace('ó', 'o');
			strTexto = strTexto.replace('ú', 'u');
			return strTexto;
		}
		function CalculaCURP(pstNombre, pstPaterno, pstMaterno, dfecha, pstSexo, pnuCveEntidad) {
			pstCURP = "";
			pstDigVer = "";
			contador = 0;
			contador1 = 0;
			pstCom = "";
			numVer = 0.00;
			valor = 0;
			sumatoria = 0;
			// se declaran las varibale que se van a utilizar para ontener la CURP
			NOMBRES = "";
			APATERNO = "";
			AMATERNO = "";
			T_NOMTOT = "";
			NOMBRE1 = ""; //PRIMER NOMBRE
			NOMBRE2 = ""; //DEMAS NOMBRES
			NOMBRES_LONGITUD = 0; //LONGITUD DE TODOS @NOMBRES
			var NOMBRE1_LONGITUD = 0; //LONGITUD DEL PRIMER NOMBRE(MAS UNO,EL QUE SOBRA ES UN ESPACIO EN BLANCO)
			APATERNO1 = ""; //PRIMER NOMBRE
			APATERNO2 = ""; //DEMAS NOMBRES
			APATERNO_LONGITUD = 0; //LONGITUD DE TODOS @NOMBRES
			APATERNO1_LONGITUD = 0; //LONGITUD DEL PRIMER NOMBRE(MAS UNO,EL QUE SOBRA ES UN ESPACIO EN BLANCO)
			AMATERNO1 = ""; //PRIMER NOMBRE
			AMATERNO2 = ""; //DEMAS NOMBRES
			AMATERNO_LONGITUD = 0; //LONGITUD DE TODOS @NOMBRES
			AMATERNO1_LONGITUD = 0; //LONGITUD DEL PRIMER NOMBRE(MAS UNO,EL QUE SOBRA ES UN ESPACIO EN BLANCO)
			VARLOOPS = 0; //VARIABLE PARA LOS LOOPS, SE INICIALIZA AL INICIR UN LOOP
			// Se inicializan las variables para obtener la primera parte de la CURP
			NOMBRES = pstNombre.replace(/^\s+|\s+$/g, "");
			APATERNO = pstPaterno.replace(/^\s+|\s+$/g, "");
			AMATERNO = pstMaterno.replace(/^\s+|\s+$/g, "");
			T_NOMTOT = APATERNO + ' ' + AMATERNO + ' ' + NOMBRES;
			// Se procesan los nombres de pila
			VARLOOPS = 0;
			while (VARLOOPS != 1) {
				NOMBRES_LONGITUD = NOMBRES.length
				var splitNombres = NOMBRES.split(" ");
				var splitNombre1 = splitNombres[0];
				NOMBRE1_LONGITUD = splitNombre1.length;
				//      NOMBRE1_LONGITUD = PATINDEX('% %',@NOMBRES)
				if (NOMBRE1_LONGITUD = 0) {
					NOMBRE1_LONGITUD = NOMBRES_LONGITUD;
				}
				NOMBRE1 = NOMBRES.substring(0, splitNombre1.length);
				NOMBRE2 = NOMBRES.substring(splitNombre1.length + 1, NOMBRES.length);
				if (NOMBRE1 == 'JOSE' && NOMBRE2 != '') {
					NOMBRES = NOMBRE2;
				}
				else {
					VARLOOPS = 1;
				}
				if (NOMBRE1 == 'MARIA' && NOMBRE2 != '') {
					NOMBRES = NOMBRE2;
				}
				else {
					VARLOOPS = 1;
				}
				if (NOMBRE1 == 'MA.' && NOMBRE2 != '') {
					NOMBRES = NOMBRE2;
				}
				else {
					VARLOOPS = 1;
				}
				if (NOMBRE1 == 'MA' && NOMBRE2 != '') {
					NOMBRES = NOMBRE2;
				}
				else {
					VARLOOPS = 1;
				}
				if (NOMBRE1 == 'DE' && NOMBRE2 != '') {
					NOMBRES = NOMBRE2;
				}
				else {
					VARLOOPS = 1;
				}
				if (NOMBRE1 == 'LA' && NOMBRE2 != '') {
					NOMBRES = NOMBRE2;
				}
				else {
					VARLOOPS = 1;
				}
				if (NOMBRE1 == 'LAS' && NOMBRE2 != '') {
					NOMBRES = NOMBRE2;
				}
				else {
					VARLOOPS = 1;
				}
				if (NOMBRE1 == 'MC' && NOMBRE2 != '') {
					NOMBRES = NOMBRE2;
				}
				else {
					VARLOOPS = 1;
				}
				if (NOMBRE1 == 'VON' && NOMBRE2 != '') {
					NOMBRES = NOMBRE2;
				}
				else {
					VARLOOPS = 1;
				}
				if (NOMBRE1 == 'DEL' && NOMBRE2 != '') {
					NOMBRES = NOMBRE2;
				}
				else {
					VARLOOPS = 1;
				}
				if (NOMBRE1 == 'LOS' && NOMBRE2 != '') {
					NOMBRES = NOMBRE2;
				}
				else {
					VARLOOPS = 1;
				}
				if (NOMBRE1 == 'Y' && NOMBRE2 != '') {
					NOMBRES = NOMBRE2;
				}
				else {
					VARLOOPS = 1;
				}
				if (NOMBRE1 == 'MAC' && NOMBRE2 != '') {
					NOMBRES = NOMBRE2;
				}
				else {
					VARLOOPS = 1;
				}
				if (NOMBRE1 == 'VAN' && NOMBRE2 != '') {
					NOMBRES = NOMBRE2;
				}
				else {
					VARLOOPS = 1;
				}
			} // fin varloops <> 1
			// Se procesan los APELLIDOS, PATERNO EN UN LOOP
			VARLOOPS = 0;
			while (VARLOOPS != 1) {
				APATERNO_LONGITUD = APATERNO.length;
				var splitPaterno = APATERNO.split(" ");
				var splitPaterno1 = splitPaterno[0];
				APATERNO1_LONGITUD = splitPaterno1.length;
				if (APATERNO1_LONGITUD = 0) {
					APATERNO1_LONGITUD = APATERNO_LONGITUD;
				}
				APATERNO1 = APATERNO.substring(0, splitPaterno1.length);
				APATERNO2 = APATERNO.substring(splitPaterno1.length + 1, APATERNO.length);
				// Se quitan los sufijos
				if (APATERNO1 == 'DE' && APATERNO2 != '') {
					APATERNO = APATERNO2;
				}
				else {
					VARLOOPS = 1;
				}
				if (APATERNO1 == 'LA' && APATERNO2 != '') {
					APATERNO = APATERNO2;
				}
				else {
					VARLOOPS = 1;
				}
				if (APATERNO1 == 'LAS' && APATERNO2 != '') {
					APATERNO = APATERNO2;
				}
				else {
					VARLOOPS = 1;
				}
				if (APATERNO1 == 'MC' && APATERNO2 != '') {
					APATERNO = APATERNO2;
				}
				else {
					VARLOOPS = 1;
				}
				if (APATERNO1 == 'VON' && APATERNO2 != '') {
					APATERNO = APATERNO2;
				}
				else {
					VARLOOPS = 1;
				}
				if (APATERNO1 == 'DEL' && APATERNO2 != '') {
					APATERNO = APATERNO2;
				}
				else {
					VARLOOPS = 1;
				}
				if (APATERNO1 == 'LOS' && APATERNO2 != '') {
					APATERNO = APATERNO2;
				}
				else {
					VARLOOPS = 1;
				}
				if (APATERNO1 == 'Y' && APATERNO2 != '') {
					APATERNO = APATERNO2;
				}
				else {
					VARLOOPS = 1;
				}
				if (APATERNO1 == 'MAC' && APATERNO2 != '') {
					APATERNO = APATERNO2;
				}
				else {
					VARLOOPS = 1;
				}
				if (APATERNO1 == 'VAN' && APATERNO2 != '') {
					APATERNO = APATERNO2;
				}
				else {
					VARLOOPS = 1;
				}
			} // fin varloops
			// Faltan: )
			// Se procesan los APELLIDOS, MATERNO EN UN LOOP
			VARLOOPS = 0;
			while (VARLOOPS != 1) {
				//SET @APATERNO_LONGITUD = LEN(@APATERNO)
				AMATERNO_LONGITUD = AMATERNO.length;
				//SET @APATERNO1_LONGITUD = PATINDEX('% %',@APATERNO)
				var splitMaterno = AMATERNO.split(" ");
				var splitMaterno1 = splitMaterno[0];
				AMATERNO1_LONGITUD = splitMaterno1.length;
				if (AMATERNO1_LONGITUD = 0) {
					AMATERNO1_LONGITUD = AMATERNO_LONGITUD;
				}
				AMATERNO1 = AMATERNO.substring(0, splitMaterno1.length);
				AMATERNO2 = AMATERNO.substring(splitMaterno1.length + 1, AMATERNO.length);
				// Se quitan los sufijos
				if (AMATERNO1 == 'DE' && AMATERNO2 != '') {
					AMATERNO = AMATERNO2;
				}
				else {
					VARLOOPS = 1;
				}
				if (AMATERNO1 == 'LA' && AMATERNO2 != '') {
					AMATERNO = AMATERNO2;
				}
				else {
					VARLOOPS = 1;
				}
				if (AMATERNO1 == 'LAS' && AMATERNO2 != '') {
					AMATERNO = AMATERNO2;
				}
				else {
					VARLOOPS = 1;
				}
				if (AMATERNO1 == 'MC' && AMATERNO2 != '') {
					AMATERNO = AMATERNO2;
				}
				else {
					VARLOOPS = 1;
				}
				if (AMATERNO1 == 'VON' && AMATERNO2 != '') {
					AMATERNO = AMATERNO2;
				}
				else {
					VARLOOPS = 1;
				}
				if (AMATERNO1 == 'DEL' && AMATERNO2 != '') {
					AMATERNO = AMATERNO2;
				}
				else {
					VARLOOPS = 1;
				}
				if (AMATERNO1 == 'LOS' && AMATERNO2 != '') {
					AMATERNO = AMATERNO2;
				}
				else {
					VARLOOPS = 1;
				}
				if (AMATERNO1 == 'Y' && AMATERNO2 != '') {
					AMATERNO = AMATERNO2;
				}
				else {
					VARLOOPS = 1;
				}
				if (AMATERNO1 == 'MAC' && AMATERNO2 != '') {
					AMATERNO = AMATERNO2;
				}
				else {
					VARLOOPS = 1;
				}
				if (AMATERNO1 == 'VAN' && AMATERNO2 != '') {
					AMATERNO = AMATERNO2;
				}
				else {
					VARLOOPS = 1;
				}
			} // fin varloops
			// Se obtiene del primer apellido la primer letra y la primer vocal interna
			pstCURP = APATERNO1.substring(0, 1);
			APATERNO1_LONGITUD = APATERNO1.length;
			VARLOOPS = 0 // EMPIEZA EN UNO POR LA PRIMERA LETRA SE LA VA A SALTAR
			while (APATERNO1_LONGITUD > VARLOOPS) {
				VARLOOPS = VARLOOPS + 1;
				// if SUBSTRING(@APATERNO1,@VARLOOPS,1) IN ('A','E','I','O','U')
				var compara = APATERNO1.substr(parseInt(VARLOOPS), 1);
				if (compara == 'A') {
					pstCURP = pstCURP + compara;
					VARLOOPS = APATERNO1_LONGITUD;
				}
				if (compara == 'E') {
					pstCURP = pstCURP + compara;
					VARLOOPS = APATERNO1_LONGITUD;
				}
				if (compara == 'I') {
					pstCURP = pstCURP + compara;
					VARLOOPS = APATERNO1_LONGITUD;
				}
				if (compara == 'O') {
					pstCURP = pstCURP + compara;
					VARLOOPS = APATERNO1_LONGITUD;
				}
				if (compara == 'U') {
					pstCURP = pstCURP + compara;
					VARLOOPS = APATERNO1_LONGITUD;
				}
			}
			// Se obtiene la primer letra del apellido materno 
			pstCURP = pstCURP + AMATERNO1.substring(0, 1);
			// Se le agrega la primer letra del nombre
			pstCURP = pstCURP + NOMBRES.substring(0, 1);
			pstCURP = pstCURP + dfecha + pstSexo + pnuCveEntidad;
			// Se obtiene la primera consonante interna del apellido paterno
			VARLOOPS = 0;
			while (splitPaterno1.length > VARLOOPS) {
				VARLOOPS = VARLOOPS + 1
				var compara = APATERNO1.substr(parseInt(VARLOOPS), 1);
				if (compara != 'A' && compara != 'E' && compara != 'I' && compara != 'O' && compara != 'U') {
					if (compara == 'Ñ') {
						pstCURP = pstCURP + 'X';
					}
					else {
						pstCURP = pstCURP + compara;
					}
					VARLOOPS = splitPaterno1.length;
				}
			}
			// Se obtiene la primera consonante interna del apellido materno
			VARLOOPS = 0;
			while (splitMaterno1.length > VARLOOPS) {
				VARLOOPS = VARLOOPS + 1;
				var compara = AMATERNO1.substr(parseInt(VARLOOPS), 1);

				if (compara != 'A' && compara != 'E' && compara != 'I' && compara != 'O' && compara != 'U') {
					if (compara == 'Ñ') {
						pstCURP = pstCURP + 'X';
					}
					else {
						pstCURP = pstCURP + compara;
					}
					VARLOOPS = splitMaterno1.length;
				}
			}
			// Se obtiene la primera consonante interna del nombre
			VARLOOPS = 0;
			while (splitNombre1.length > VARLOOPS) {
				VARLOOPS = VARLOOPS + 1;
				var compara = NOMBRE1.substr(parseInt(VARLOOPS), 1);
				if (compara != 'A' && compara != 'E' && compara != 'I' && compara != 'O' && compara != 'U') {
					if (compara == 'Ñ') {
						pstCURP = pstCURP + 'X';
					}
					else {
						pstCURP = pstCURP + compara;
					}
					VARLOOPS = splitNombre1.length;
				}
			}
			// Se obtiene el digito verificador
			var contador = 18;
			var contador1 = 0;
			var valor = 0;
			var sumatoria = 0;
			while (contador1 <= 15) {
				//pstCom = SUBSTRING(@pstCURP,@contador1,1)
				var pstCom = pstCURP.substr(parseInt(contador1), 1);
				if (pstCom == '0') {
					valor = 0 * contador;
				}
				if (pstCom == '1') {
					valor = 1 * contador;
				}
				if (pstCom == '2') {
					valor = 2 * contador;
				}
				if (pstCom == '3') {
					valor = 3 * contador;
				}
				if (pstCom == '4') {
					valor = 4 * contador;
				}
				if (pstCom == '5') {
					valor = 5 * contador;
				}
				if (pstCom == '6') {
					valor = 6 * contador;
				}
				if (pstCom == '7') {
					valor = 7 * contador;
				}
				if (pstCom == '8') {
					valor = 8 * contador;
				}
				if (pstCom == '9') {
					valor = 9 * contador;
				}
				if (pstCom == 'A') {
					valor = 10 * contador;
				}
				if (pstCom == 'B') {
					valor = 11 * contador;
				}
				if (pstCom == 'C') {
					valor = 12 * contador;
				}
				if (pstCom == 'D') {
					valor = 13 * contador;
				}
				if (pstCom == 'E') {
					valor = 14 * contador;
				}
				if (pstCom == 'F') {
					valor = 15 * contador;
				}
				if (pstCom == 'G') {
					valor = 16 * contador;
				}
				if (pstCom == 'H') {
					valor = 17 * contador;
				}
				if (pstCom == 'I') {
					valor = 18 * contador;
				}
				if (pstCom == 'J') {
					valor = 19 * contador;
				}
				if (pstCom == 'K') {
					valor = 20 * contador;
				}
				if (pstCom == 'L') {
					valor = 21 * contador;
				}
				if (pstCom == 'M') {
					valor = 22 * contador;
				}
				if (pstCom == 'N') {
					valor = 23 * contador;
				}
				if (pstCom == 'Ñ') {
					valor = 24 * contador;
				}
				if (pstCom == 'O') {
					valor = 25 * contador;
				}
				if (pstCom == 'P') {
					valor = 26 * contador;
				}
				if (pstCom == 'Q') {
					valor = 27 * contador;
				}
				if (pstCom == 'R') {
					valor = 28 * contador;
				}
				if (pstCom == 'S') {
					valor = 29 * contador;
				}
				if (pstCom == 'T') {
					valor = 30 * contador;
				}
				if (pstCom == 'U') {
					valor = 31 * contador;
				}
				if (pstCom == 'V') {
					valor = 32 * contador;
				}
				if (pstCom == 'W') {
					valor = 33 * contador;
				}
				if (pstCom == 'X') {
					valor = 34 * contador;
				}
				if (pstCom == 'Y') {
					valor = 35 * contador;
				}
				if (pstCom == 'Z') {
					valor = 36 * contador;
				}
				contador = contador - 1;
				contador1 = contador1 + 1;
				sumatoria = sumatoria + valor;
			}
			numVer = sumatoria % 10;
			numVer = Math.abs(10 - numVer);
			anio = dfecha.substr(2, 2);
			if (numVer == 10) {
				numVer = 0;
			}
			if (anio < 2000) {
				pstDigVer = '0' + '' + numVer;
			}
			if (anio >= 2000) {
				pstDigVer = 'A' + '' + numVer;
			}
			pstCURP = pstCURP + pstDigVer;
			// document.getElementById("curp").value = pstCURP;
			//console.log('curp:', pstCURP)
			return pstCURP
		}
		function RFCApellidoCorto(ap_paterno, ap_materno, nombre) {
			var rfc = ap_paterno.substr(0, 1) + ap_materno.substr(0, 1) + nombre.substr(0, 2);
			return rfc;
		}
		function RFCArmalo(ap_paterno, ap_materno, nombre) {
			var strVocales = 'aeiou';
			var strPrimeraVocal = '';
			var i = 0;
			var x = 0;
			var y = 0;
			for (i = 1; i <= ap_paterno.length; i++) {
				//alert(ap_paterno.substr(i,1));
				if (y == 0) {
					for (x = 0; x <= strVocales.length; x++) {
						//alert(strVocales.substr(x,1));
						if (ap_paterno.substr(i, 1) == strVocales.substr(x, 1)) {
							y = 1;
							strPrimeraVocal = ap_paterno.substr(i, 1);
						}
					}
				}
				//break;
			}
			var rfc = ap_paterno.substr(0, 1) + strPrimeraVocal + ap_materno.substr(0, 1) + nombre.substr(0, 1);
			return rfc;
		}
		function RFCUnApellido(nombre, apellido) {
			var rfc = apellido.substr(0, 2) + nombre.substr(0, 2);
			return rfc
		}
		function RFCQuitaProhibidas(rfc) {
			var res;
			rfc = rfc.toUpperCase();
			var strPalabras = "BUEI*BUEY*CACA*CACO*CAGA*CAGO*CAKA*CAKO*COGE*COJA*";
			strPalabras = strPalabras + "KOGE*KOJO*KAKA*KULO*MAME*MAMO*MEAR*";
			strPalabras = strPalabras + "MEAS*MEON*MION*COJE*COJI*COJO*CULO*";
			strPalabras = strPalabras + "FETO*GUEY*JOTO*KACA*KACO*KAGA*KAGO*";
			strPalabras = strPalabras + "MOCO*MULA*PEDA*PEDO*PENE*PUTA*PUTO*";
			strPalabras = strPalabras + "QULO*RATA*RUIN*";
			res = strPalabras.match(rfc);
			if (res != null) {
				rfc = rfc.substr(0, 3) + 'X';
				return rfc;
			} else {
				return rfc;
			}
		}
		function homonimia(ap_paterno, ap_materno, nombre) {
			var nombre_completo = ap_paterno.trim() + ' ' + ap_materno.trim() + ' ' + nombre.trim();
			var numero = '0';
			var letra;
			var numero1;
			var numero2;
			var numeroSuma = 0;
			//alert(nombre_completo);
			//alert(nombre_completo.length);
			for (i = 0; i <= nombre_completo.length; i++) {
				letra = nombre_completo.substr(i, 1);
				switch (letra) {
					case 'ñ':
						numero = numero + '10'
						break;
					case 'ü':
						numero = numero + '10'
						break;
					case 'a':
						numero = numero + '11'
						break;
					case 'b':
						numero = numero + '12'
						break;
					case 'c':
						numero = numero + '13'
						break;
					case 'd':
						numero = numero + '14'
						break;
					case 'e':
						numero = numero + '15'
						break;
					case 'f':
						numero = numero + '16'
						break;
					case 'g':
						numero = numero + '17'
						break;
					case 'h':
						numero = numero + '18'
						break;
					case 'i':
						numero = numero + '19'
						break;
					case 'j':
						numero = numero + '21'
						break;
					case 'k':
						numero = numero + '22'
						break;
					case 'l':
						numero = numero + '23'
						break;
					case 'm':
						numero = numero + '24'
						break;
					case 'n':
						numero = numero + '25'
						break;
					case 'ñ':
						numero = numero + '40'
						break;
					case 'o':
						numero = numero + '26'
						break;
					case 'p':
						numero = numero + '27'
						break;
					case 'q':
						numero = numero + '28'
						break;
					case 'r':
						numero = numero + '29'
						break;
					case 's':
						numero = numero + '32'
						break;
					case 't':
						numero = numero + '33'
						break;
					case 'u':
						numero = numero + '34'
						break;
					case 'v':
						numero = numero + '35'
						break;
					case 'w':
						numero = numero + '36'
						break;
					case 'x':
						numero = numero + '37'
						break;
					case 'y':
						numero = numero + '38'
						break;
					case 'z':
						numero = numero + '39'
						break;
					case ' ':
						numero = numero + '00'
						break;
				}
			}
			//alert(numero);
			for (i = 0; i <= numero.length + 1; i++) {
				numero1 = numero.substr(i, 2);
				numero2 = numero.substr(i + 1, 1);
				numeroSuma = numeroSuma + (numero1 * numero2);

			}
			//alert(numeroSuma);
			var numero3 = numeroSuma % 1000;
			//alert(numero3);
			var numero4 = numero3 / 34;
			var numero5 = numero4.toString().split(".")[0];
			//alert(numero5);
			var numero6 = numero3 % 34;
			//alert(numero6);
			var homonimio = '';
			switch (numero5) {
				case '0':
					homonimio = '1'
					break;
				case '1':
					homonimio = '2'
					break;
				case '2':
					homonimio = '3'
					break;
				case '3':
					homonimio = '4'
					break;
				case '4':
					homonimio = '5'
					break;
				case '5':
					homonimio = '6'
					break;
				case '6':
					homonimio = '7'
					break;
				case '7':
					homonimio = '8'
					break;
				case '8':
					homonimio = '9'
					break;
				case '9':
					homonimio = 'A'
					break;
				case '10':
					homonimio = 'B'
					break;
				case '11':
					homonimio = 'C'
					break;
				case '12':
					homonimio = 'D'
					break;
				case '13':
					homonimio = 'E'
					break;
				case '14':
					homonimio = 'F'
					break;
				case '15':
					homonimio = 'G'
					break;
				case '16':
					homonimio = 'H'
					break;
				case '17':
					homonimio = 'I'
					break;
				case '18':
					homonimio = 'J'
					break;
				case '19':
					homonimio = 'K'
					break;
				case '20':
					homonimio = 'L'
					break;
				case '21':
					homonimio = 'M'
					break;
				case '22':
					homonimio = 'N'
					break;
				case '23':
					homonimio = 'P'
					break;
				case '24':
					homonimio = 'Q'
					break;
				case '25':
					homonimio = 'R'
					break;
				case '26':
					homonimio = 'S'
					break;
				case '27':
					homonimio = 'T'
					break;
				case '28':
					homonimio = 'U'
					break;
				case '29':
					homonimio = 'V'
					break;
				case '30':
					homonimio = 'W'
					break;
				case '31':
					homonimio = 'X'
					break;
				case '32':
					homonimio = 'Y'
					break;
				case '33':
					homonimio = 'Z'
					break;

			}
			switch (numero6.toString()) {
				case '0':
					homonimio = homonimio + '1'
					break;
				case '1':
					homonimio = homonimio + '2'
					break;
				case '2':
					homonimio = homonimio + '3'
					break;
				case '3':
					homonimio = homonimio + '4'
					break;
				case '4':
					homonimio = homonimio + '5'
					break;
				case '5':
					homonimio = homonimio + '6'
					break;
				case '6':
					homonimio = homonimio + '7'
					break;
				case '7':
					homonimio = homonimio + '8'
					break;
				case '8':
					homonimio = homonimio + '9'
					break;
				case '9':
					homonimio = homonimio + 'A'
					break;
				case '10':
					homonimio = homonimio + 'B'
					break;
				case '11':
					homonimio = homonimio + 'C'
					break;
				case '12':
					homonimio = homonimio + 'D'
					break;
				case '13':
					homonimio = homonimio + 'E'
					break;
				case '14':
					homonimio = homonimio + 'F'
					break;
				case '15':
					homonimio = homonimio + 'G'
					break;
				case '16':
					homonimio = homonimio + 'H'
					break;
				case '17':
					homonimio = homonimio + 'I'
					break;
				case '18':
					homonimio = homonimio + 'J'
					break;
				case '19':
					homonimio = homonimio + 'K'
					break;
				case '20':
					homonimio = homonimio + 'L'
					break;
				case '21':
					homonimio = homonimio + 'M'
					break;
				case '22':
					homonimio = homonimio + 'N'
					break;
				case '23':
					homonimio = homonimio + 'P'
					break;
				case '24':
					homonimio = homonimio + 'Q'
					break;
				case '25':
					homonimio = homonimio + 'R'
					break;
				case '26':
					homonimio = homonimio + 'S'
					break;
				case '27':
					homonimio = homonimio + 'T'
					break;
				case '28':
					homonimio = homonimio + 'U'
					break;
				case '29':
					homonimio = homonimio + 'V'
					break;
				case '30':
					homonimio = homonimio + 'W'
					break;
				case '31':
					homonimio = homonimio + 'X'
					break;
				case '32':
					homonimio = homonimio + 'Y'
					break;
				case '33':
					homonimio = homonimio + 'Z'
					break;
			}
			return homonimio;
		}
		function RFCDigitoVerificador(rfc) {
			var rfcsuma = [];
			var nv = 0;
			var y = 0;
			for (i = 0; i <= rfc.length; i++) {
				var letra = rfc.substr(i, 1);
				switch (letra) {
					case '0':
						rfcsuma.push('00')
						break;
					case '1':
						rfcsuma.push('01')
						break;
					case '2':
						rfcsuma.push('02')
						break;
					case '3':
						rfcsuma.push('03')
						break;
					case '4':
						rfcsuma.push('04')
						break;
					case '5':
						rfcsuma.push('05')
						break;
					case '6':
						rfcsuma.push('06')
						break;
					case '7':
						rfcsuma.push('07')
						break;
					case '8':
						rfcsuma.push('08')
						break;
					case '9':
						rfcsuma.push('09')
						break;
					case 'A':
						rfcsuma.push('10')
						break;
					case 'B':
						rfcsuma.push('11')
						break;
					case 'C':
						rfcsuma.push('12')
						break;
					case 'D':
						rfcsuma.push('13')
						break;
					case 'E':
						rfcsuma.push('14')
						break;
					case 'F':
						rfcsuma.push('15')
						break;
					case 'G':
						rfcsuma.push('16')
						break;
					case 'H':
						rfcsuma.push('17')
						break;
					case 'I':
						rfcsuma.push('18')
						break;
					case 'J':
						rfcsuma.push('19')
						break;
					case 'K':
						rfcsuma.push('20')
						break;
					case 'L':
						rfcsuma.push('21')
						break;
					case 'M':
						rfcsuma.push('22')
						break;
					case 'N':
						rfcsuma.push('23')
						break;
					case '&':
						rfcsuma.push('24')
						break;
					case 'O':
						rfcsuma.push('25')
						break;
					case 'P':
						rfcsuma.push('26')
						break;
					case 'Q':
						rfcsuma.push('27')
						break;
					case 'R':
						rfcsuma.push('28')
						break;
					case 'S':
						rfcsuma.push('29')
						break;
					case 'T':
						rfcsuma.push('30')
						break;
					case 'U':
						rfcsuma.push('31')
						break;
					case 'V':
						rfcsuma.push('32')
						break;
					case 'W':
						rfcsuma.push('33')
						break;
					case 'X':
						rfcsuma.push('34')
						break;
					case 'Y':
						rfcsuma.push('35')
						break;
					case 'Z':
						rfcsuma.push('36')
						break;
					case ' ':
						rfcsuma.push('37')
						break;
					case 'Ñ':
						rfcsuma.push('38')
						break;
					default:
						rfcsuma.push('00');
				}
			}
			for (i = 13; i > 1; i--) {
				nv = nv + (rfcsuma[y] * i);
				y++;
			}
			nv = nv % 11;
			//alert(nv);
			if (nv == 0) {
				rfc = rfc + nv;
			} else if (nv <= 10) {
				nv = 11 - nv;
				if (nv == '10') {
					nv = 'A';
				}
				rfc = rfc + nv;
			} else if (nv == '10') {
				nv = 'A';
				rfc = rfc + nv;
			}
			return rfc
		}
		function generar_manzanas1(value){
			id_seccion_ine = document.getElementById("id_seccion_ine").value;
			if(id_seccion_ine!=''){
				//console.log(id_seccion_ine);
				seccionManzanaSelect(id_seccion_ine)
			}
		}
		function generar_manzanas(value){
			id_seccion_ine = document.getElementById("id_seccion_ine").value;
			if(id_seccion_ine!=''){
				///direccion_actual
				var espacios_invalidos= /\s+/g;
				var id_municipio = document.getElementById("id_municipio").value; 
				var id_localidad = document.getElementById("id_localidad").value; 
				var calle = document.getElementById("calle").value;
				var num_ext = document.getElementById("num_ext").value;
				var num_int = document.getElementById("num_int").value;
				var colonia = document.getElementById("colonia").value; 
				var codigo_postal = document.getElementById("codigo_postal").value;
				var id_municipio_ine = document.getElementById("id_municipio_ine").value; 
				var id_localidad_ine = document.getElementById("id_localidad_ine").value; 
				var calle_ine = document.getElementById("calle_ine").value;
				var num_ext_ine = document.getElementById("num_ext_ine").value;
				var num_int_ine = document.getElementById("num_int_ine").value;
				var colonia_ine = document.getElementById("colonia_ine").value;
				var codigo_postal_ine = document.getElementById("codigo_postal_ine").value;
				var igual = true;
				if(id_municipio != id_municipio_ine){
					var igual = false;
				}
				if(id_localidad != id_localidad_ine){
					var igual = false;
				}
				if(calle != calle_ine){
					var igual = false;
				}
				if(num_ext != num_ext_ine){
					var igual = false;
				}
				if(num_int != num_int_ine){
					var igual = false;
				}
				if(colonia != colonia_ine){
					var igual = false;
				}
				if(codigo_postal != codigo_postal_ine){
					var igual = false;
				}
				//console.log(id_seccion_ine);
				if(igual==true){
					seccionManzanaSelectIgual(id_seccion_ine);
				}else{
					seccionManzanaSelect(id_seccion_ine);
				}
			}
		}
		function generar_manzanasForm(value){
			id_seccion_ine = value;
			//console.log(id_seccion_ine);
			seccionManzanaSelect(id_seccion_ine)
		}
	</script>
	<style type="text/css">
		.ui-autocomplete {
			max-height: 180px;
			margin-bottom: 10px;
			overflow-x: hidden;
			overflow-y: auto;
		}
		.data_interior{
			width: 50%;
			float: left;
			padding-left: 10px;
			padding-right: 10px;
			color: #191919;
		}
		.data_interior_left{
			/*width: 50%;*/
			float: left;
			padding-left: 10px;
			padding-right: 10px;
			color: #191919;
			border-right: 1px solid #191919;
		}
		@media only screen and (max-width:1600px) {
			.data_interior{
				width: 100%;
			}
			.data_interior_left{
				border-right: none;
			}
		}
	</style>
	<script>
		function datosCiudadanoSig(){
			document.getElementById("divDatosCiudadano").style.display = "none";
			document.getElementById("divDatosPersonales").style.display = "initial";
		}
		function datosPersonalesAnt(){
			document.getElementById("divDatosCiudadano").style.display = "initial";
			document.getElementById("divDatosPersonales").style.display = "none";
		}
		function datosPersonalesSig(){
			document.getElementById("divDatosPersonales").style.display = "none";
			document.getElementById("divDatosContacto").style.display = "initial";
		}
		function datosContactoAnt(){
			document.getElementById("divDatosContacto").style.display = "none";
			document.getElementById("divDatosPersonales").style.display = "initial";
		}
		function datosContactoSig(){
			document.getElementById("divDatosContacto").style.display = "none";
			document.getElementById("divDatosDireccionActual").style.display = "initial";

		}
		function datosDireccionActualAnt(){
			document.getElementById("divDatosContacto").style.display = "initial";
			document.getElementById("divDatosDireccionActual").style.display = "none";
		}
		function datosDireccionActualSig(){
			document.getElementById("divDatosDireccionActual").style.display = "none";
			document.getElementById("divDatosDireccionIne").style.display = "initial";

		}
		function datosDireccionIneAnt(){
			document.getElementById("divDatosDireccionActual").style.display = "initial";
			document.getElementById("divDatosDireccionIne").style.display = "none";
		}
		function datosDireccionIneSig(){
			document.getElementById("divDatosDireccionIne").style.display = "none";
			document.getElementById("divObservaciones").style.display = "initial";
		}
		function datosObervacionesAnt(){
			document.getElementById("divDatosDireccionIne").style.display = "initial";
			document.getElementById("divObservaciones").style.display = "none";
		}
		function buscadorArea(){
			buscardorAmigo = document.getElementById("buscardorAmigo").style.display;
			if(buscardorAmigo=='block'){
				document.getElementById("buscardorAmigo").style.display='none';
			}else{
				document.getElementById("buscardorAmigo").style.display='block';
			}
		}
	</script>
	<style>
		.sucFormTituloBoton{
			cursor: pointer;
			-webkit-user-select: none; /* Safari */
  			-ms-user-select: none; /* IE 10 and IE 11 */
  			user-select: none; /* Standard syntax */
		}
		.sucFormTituloBoton:hover{
			background-color:#b2e2f2;
		}
		.sucFormTituloBoton:active{
			background-color:#e3edfc;
		}
	</style>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucForm" style="width: 100%"></div>
		<div class="sucFormTitulo sucFormTituloBoton" onclick="buscadorArea()"  >
			<label class="sucFormTituloBoton labelForm " id="labeltemaname">Buscador Datos Amigos</label><br>
		</div>
		<div id="buscardorAmigo" style="background-color: none;display: block;">
			<div style="padding: 10px 0px 0px 0px;background-color: none">
				<?php include "filtros_lista_nominal.php"; ?></div>
			<div style="clear: both;"></div>
			<div id="dataTable">
				<?php //include "table_lista_nominal.php"; ?>
			</div> 
		</div>
		<div id="divDatosCiudadano"  >
			<div class="sucFormTitulo">
				<div class="workFlowTitulo">
					<label class="labelForm" id="labeltemaname">Datos Ciudadano</label>
				</div>
				<div class="workFlowBotones">
					<input class="BotonWorkFlowUnit" type="button" value="Siguiente >>" onclick="datosCiudadanoSig()" >
				</div>
			</div>
			<script>
				$(document).ready(function(){
					$("#id_seccion_ine_ciudadano_compartido").select2({ 
						language: {
							errorLoading:function(){ 
								return "" 
							},
							inputTooLong:function(e){
								var n=e.input.length-e.maximum,r="Por favor, elimine "+n+" car";return r+=1==n?"ácter":"acteres"
							},
							inputTooShort:function(e){
								var n=e.minimum-e.input.length,r="Por favor, introduzca minimo "+n+" car";return r+=1==n?"ácter":"acteres"
							},
							loadingMore:function(){
								return"Cargando más resultados…"
							},
							maximumSelected:function(e){
								var n="Sólo puede seleccionar "+e.maximum+" elemento";return 1!=e.maximum&&(n+="s"),n
							},
							noResults:function(){
								return"No se encontraron resultados"
							},
							searching:function(){
								return"Buscando…"
							},
							removeAllItems:function(){
								return"Eliminar todos los elementos"
							}
						},
						ajax: {
							url: "seccionesIneCiudadanos/search.php",
							type: "post",
							dataType: 'json',
							delay: 250,
							data: function (params) {
								return {
									search: params.term // search term
								};
							},
							processResults: function (response) {
								return {
									results: response
								};
							},
							cache: true
						}
					});
				});
			</script>
			<div class="sucForm" style="width: 100%"></div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Clave Electoral<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text"  name="clave_elector" autocomplete="off"  id="clave_elector" value="<?= $seccion_ine_ciudadanoDatos['clave_elector'] ?>" placeholder="" onblur="aMays(event, this)" size="40" maxlength="18"/><br>
			</div>
			<div class="sucForm" style="width: 100%">
				<input type="button" onclick="buscar_clave_electoral()" value="Buscar Datos INE"><input type="hidden" id="valid_ine">
			</div> 
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">C.U.R.P<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="curp" autocomplete="off"  id="curp" value="<?= $seccion_ine_ciudadanoDatos['curp'] ?>" placeholder="" onblur="aMays(event, this)" size="40" maxlength="18"/><br>
			</div>
			<?php
			/*
			echo '<div class="sucForm" style="width: 100%">
					<input type="button" onclick="buscar_curp()" value="Buscar Datos C.U.R.P">
				</div> ';
			*/
			?>
			<div class="sucForm" style="display: none;">
				<label class="labelForm" id="labeltemaname">R.F.C<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="rfc" autocomplete="off"  id="rfc" value="<?= $seccion_ine_ciudadanoDatos['rfc'] ?>" placeholder="" onblur="aMays(event, this)"/><br>
			</div> 
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">OCR<font color="#FF0004">*</font></label><br>
				<input maxlength="13" size="40" class="inputlogin" type="text" name="ocr" autocomplete="off"  id="ocr" value="<?= $seccion_ine_ciudadanoDatos['ocr'] ?>" placeholder="" onblur="aMays(event, this)"/><br>
			</div>
			<div class="sucForm" style="width: 100%; background-color: #e3edfc;padding: 20px;">
				<h5>Datos INE</h5>
				<div id="busqueda_clave_electoral">
					<div id="mensaje_ine_disponible"></div>
					<div class="data_interior_left">
						C.U.R.P: <b id="ine_curp"><?= $row['curp'] =='' ? 'No Encontrado' : $row['curp'] ?></b><br>
						Nombre: <b id="ine_nombre"><?= $row['nombre'] =='' ? 'No Encontrado' : $row['nombre'] ?></b><br>
						Apellido Paterno: <b id="ine_apellido_paterno"><?= $row['apellido_paterno'] =='' ? 'No Encontrado' : $row['apellido_paterno'] ?></b><br>
						Apellido Materno: <b id="ine_apellido_materno"><?= $row['apellido_materno'] =='' ? 'No Encontrado' : $row['apellido_materno'] ?></b><br>
						Fecha Nacimiento: <b id="ine_fecha_nacimiento"><?= $row['fecha_nacimiento'] =='' ? 'No Encontrado' : $row['fecha_nacimiento'] ?></b><br>
						Sexo: <b id="ine_sexo"><?= $row['sexo'] =='' ? 'No Encontrado' : $row['sexo'] ?></b><br>
					</div>
					<div class="data_interior">
						Calle: <b id="ine_calle"><?= $row['calle'] =='' ? 'No Encontrado' : $row['calle'] ?></b><br>
						No. Exterior: <b id="ine_no_exterior"><?= $row['no_exterior'] =='' ? 'No Encontrado' : $row['no_exterior'] ?></b><br>
						No. Interior: <b id="ine_no_interior"><?= $row['no_interior'] =='' ? 'No Encontrado' : $row['no_interior'] ?></b><br>
						Colonia: <b id="ine_colonia"><?= $row['colonia'] =='' ? 'No Encontrado' : $row['colonia'] ?></b><br>
						Código Postal: <b id="ine_codigo_postal"><?= $row['codigo_postal'] =='' ? 'No Encontrado' : $row['codigo_postal'] ?></b><br>
					</div>
				</div>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Relacionado</label><br>
				<select id='id_seccion_ine_ciudadano_compartido' style='width: 200px;' >
					<?= $option_relacionado ?>
				</select>
			</div>
			<div class="sucForm" style="width: 100%"></div>
			<?php
				$select_status_verificacion[$seccion_ine_ciudadanoDatos['status_verificacion']]='selected="selected"';
			?>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Verificación<font color="#FF0004">*</font></label><br>
				<select class="myselect" id="status_verificacion" >
					<option>Seleccione</option>
					<option <?= $select_status_verificacion[0] ?> value="0">No Encontrado</option>
					<option <?= $select_status_verificacion[1] ?> value="1">Encontrado</option>
					<option <?= $select_status_verificacion[2] ?> value="2">Verificado</option>
					<option <?= $select_status_verificacion[3] ?> value="3">Por Validar</option>
				</select><br>
			</div>
		</div>
		<div class="sucForm" style="width: 100%"></div>
		<div id="divDatosPersonales" style="display:none">
			<div class="sucFormTitulo">
				<div class="workFlowTitulo">
					<label class="labelForm" id="labeltemaname">Datos Personales</label>
				</div>
				<div class="workFlowBotones">
					<input class="BotonWorkFlow" type="button" value="<< Anterior" onclick="datosPersonalesAnt()" >
					<input class="BotonWorkFlow" type="button" value="Siguiente >>" onclick="datosPersonalesSig()" >
				</div>
			</div>
			<div class="sucForm" style="width: 100%"></div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Tipo<font color="#FF0004">*</font></label><br>
				<select name="id_tipo_ciudadano" id="id_tipo_ciudadano" class='myselect'>  
					<?php echo tipos_ciudadanos($seccion_ine_ciudadanoDatos['id_tipo_ciudadano']) ?>
				</select>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Nombre<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="nombre" autocomplete="off"  id="nombre" value="<?= $seccion_ine_ciudadanoDatos['nombre'] ?>" placeholder="" onkeyup="aMays(event, this)" /><br>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Apellido Paterno<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="apellido_paterno" autocomplete="off"  id="apellido_paterno" value="<?= $seccion_ine_ciudadanoDatos['apellido_paterno'] ?>" placeholder="" onkeyup="aMays(event, this)" /><br>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Apellido Materno<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="apellido_materno" autocomplete="off"  id="apellido_materno" value="<?= $seccion_ine_ciudadanoDatos['apellido_materno'] ?>" placeholder="" onkeyup="aMays(event, this)" /><br>
			</div>
			<div class="sucForm" style="width: 100%"></div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Sexo<font color="#FF0004">*</font></label><br>
				<?php
				$select[$seccion_ine_ciudadanoDatos['sexo']] = 'selected="selected"';
				?>
				<select name="sexo" id="sexo" class='myselect'>  
					<option value="">Seleccione</option>
					<option <?= $select['Mujer'] ?> value="Mujer">Mujer</option>
					<option <?= $select['Hombre'] ?> value="Hombre">Hombre</option>
				</select>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Fecha Nacimiento<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="fecha_nacimiento" autocomplete="off"  id="fecha_nacimiento" value="<?= $seccion_ine_ciudadanoDatos['fecha_nacimiento'] ?>" placeholder="" /><br>
			</div>
		</div>
		<div id="divDatosUsuario" style="display:none">
			<div class="sucFormTitulo">
				<label class="labelForm" id="labeltemaname">Datos Usuario</label>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Usuario<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="usuario" autocomplete="off"  id="usuario" value="<?= $usuarioDatos['usuario']  ?>" placeholder="" maxlength="45" /><br>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Password<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="password" autocomplete="off"  id="password" value="<?= $usuarioDatos['password'] ?>" placeholder="" maxlength="10" /><br>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Repetir Password<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="password1" autocomplete="off"  id="password1" value="<?= $usuarioDatos['password'] ?>" placeholder="" maxlength="10" />
			</div>
			<div class="sucForm" style="width: 100%"></div>
			<div class="sucForm">
				<label class="labelForm" id="labelMostrar">Mostrar</label><input type="checkbox"  id="monstar_contraseña" value="1"><br>
			</div>
			<div class="sucForm" style="width: 100%">
				<label class="labelForm" id="labeltemaname">Estatus<font color="#FF0004">*</font></label><br>
					<select id="status" class="myselect" name="status" >
					<?php	echo statusGeneralForm($usuarioDatos['status']); ?>
				</select><br><br>
			</div>
			<div class="sucFormTitulo">
				<label class="labelForm" id="labeltemaname">Permisos Usuario</label>
			</div>

			<?php
				if($seccion_ine_ciudadano_permisosDatos['entrega'] == 1){
					$chkEntrega = 'checked="checked"';
				}

				if($seccion_ine_ciudadano_permisosDatos['recibe'] == 1){
					$chkRecibe = 'checked="checked"';
				}

				if($seccion_ine_ciudadano_permisosDatos['casilla'] == 1){
					$chkCasilla = 'checked="checked"';
				}
			?>

			<div class="sucForm">
				<label class="labelForm" id="labelMostrar">Lleva Ciudadano</label><input type="checkbox" <?= $chkEntrega ?> id="entrega" value="1"><br>
			</div>
			<div class="sucForm" style="width: 100%"></div>
			<div class="sucForm">
				<label class="labelForm" id="labelMostrar">Recibe Ciudadano</label><input type="checkbox" <?= $chkRecibe ?> id="recibe" value="1"><br>
			</div>
			<div class="sucForm" style="width: 100%"></div>
			<div class="sucForm">
				<label class="labelForm" id="labelMostrar">Info. Casilla</label><input type="checkbox" <?= $chkCasilla ?> id="casilla" value="1"><br>
			</div>
			<div class="sucForm" style="width: 100%"></div>
		</div>
		<div id="divDatosContacto" style="display:none">
			<div class="sucFormTitulo">
				<div class="workFlowTitulo">
					<label class="labelForm" id="labeltemaname">Datos Contacto</label>
				</div>
				<div class="workFlowBotones">
					<input class="BotonWorkFlow" type="button" value="<< Anterior" onclick="datosContactoAnt()" >
					<input class="BotonWorkFlow" type="button" value="Siguiente >>" onclick="datosContactoSig()" >
				</div>
			</div>
			<div class="sucForm" style="width: 100%">
				<label class="labelForm" id="labeltemaname">Correo Eletrónico<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="correo_electronico" autocomplete="off"  id="correo_electronico" value="<?= $seccion_ine_ciudadanoDatos['correo_electronico'] ?>" placeholder="" /><br>
			</div>

			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Whatsapp<font color="#FF0004">*</font></label>(Solo Numero 10 Digitos)<br>
				<input class="inputlogin" type="text" pattern="[0-9]*" maxlength="10"  name="whatsapp" autocomplete="off"  id="whatsapp" value="<?= $seccion_ine_ciudadanoDatos['whatsapp'] ?>" placeholder="9991742151" onkeypress="return CheckNumeric()" /><br>
			</div>

			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Teléfono</label><br>
				<input class="inputlogin" type="text" name="telefono" autocomplete="off"  id="telefono" value="<?= $seccion_ine_ciudadanoDatos['telefono'] ?>" placeholder="9992154554 ext 10" /><br>
			</div>

			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Celular</label>(Solo Numero 10 Digitos, Si no tiene dejar en blanco)<br>
				<input class="inputlogin" type="text" pattern="[0-9]*" name="celular" autocomplete="off"  id="celular" value="<?= $seccion_ine_ciudadanoDatos['celular'] ?>" placeholder="9991742151" onkeypress="return CheckNumeric()" /><br>
			</div>
		</div>
		<div id="divDatosDireccionActual" style="display:none">
			<div class="sucFormTitulo">
				<div class="workFlowTitulo">
					<label class="labelForm" id="labeltemaname">Dirección Actual</label>
				</div>
				<div class="workFlowBotones">
					<input class="BotonWorkFlow" type="button" value="<< Anterior" onclick="datosDireccionActualAnt()" >
					<input class="BotonWorkFlow" type="button" value="Siguiente >>" onclick="datosDireccionActualSig()" >
				</div>
			</div>
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;display: none">
				<label class="labelForm" id="labeltemaname">Pais<font color="#FF0004">*</font></label><br>
				<select   name="id_pais" id="id_pais" class='myselect' disabled="disabled" >
					<?php
					echo paises($seccion_ine_ciudadanoDatos['id_pais']);
					?>
				</select>
			</div>
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;display: none">
				<label class="labelForm" id="labeltemaname">Pais<font color="#FF0004">*</font></label><br>
				<select   name="id_pais" id="id_pais" class='myselect' disabled="disabled" >
					<?php
					echo paises($seccion_ine_ciudadanoDatos['id_pais']);
					?>
				</select>
			</div>
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;display: none">
				<label class="labelForm" id="labeltemaname">Estado<font color="#FF0004">*</font></label><br>
				<select   name="id_estado" id="id_estado" class='myselect' onchange="locationEstado(this);" disabled="disabled" >  
					<?php
					echo estados($seccion_ine_ciudadanoDatos['id_estado']);
					?>
				</select>
			</div>
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;">
				<label class="labelForm" id="labeltemaname">Municipio<font color="#FF0004">*</font></label><br>
				<select   name="id_municipio" id="id_municipio" class='myselect' onchange="locationMunicipio(this)">  
					<?php
					echo municipios($seccion_ine_ciudadanoDatos['id_municipio'],$seccion_ine_ciudadanoDatos['id_estado']);
					?>
				</select>
			</div>
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
				<label class="labelForm" id="labeltemaname">Localidad<font color="#FF0004">*</font></label><br>
				<select   name="id_localidad" id="id_localidad" class='myselect' onchange="locationLocalidad(this)">  
					<?php
					echo localidades($seccion_ine_ciudadanoDatos['id_localidad'],$seccion_ine_ciudadanoDatos['id_municipio'],$seccion_ine_ciudadanoDatos['id_estado']);
					?>
				</select>
			</div>
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;width: 100%">
				<label class="labelForm" id="labeltemaname">Calle<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="calle" autocomplete="off" id="calle" value="<?= $seccion_ine_ciudadanoDatos['calle'] ?>" placeholder="" maxlength="120" onkeyup="aMays(event, this)" /><br>
			</div>
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
				<label class="labelForm" id="labeltemaname">Num Ext.</label><br>
				<input class="inputlogin" type="text" name="num_ext" autocomplete="off"  id="num_ext" value="<?= $seccion_ine_ciudadanoDatos['num_ext'] ?>" placeholder="" maxlength="120" onkeyup="aMays(event, this)" /><br>
			</div>
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
				<label class="labelForm" id="labeltemaname">Num Int.</label><br>
				<input class="inputlogin" type="text" name="num_int" autocomplete="off"  id="num_int" value="<?= $seccion_ine_ciudadanoDatos['num_int'] ?>" placeholder="" maxlength="120" onkeyup="aMays(event, this)" /><br>
			</div>
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
				<label class="labelForm" id="labeltemaname">Colonia<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="colonia" autocomplete="off"  id="colonia" value="<?= $seccion_ine_ciudadanoDatos['colonia'] ?>" placeholder="" maxlength="120" onkeyup="aMays(event, this)" /><br>
			</div> 
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
				<label class="labelForm" id="labeltemaname">Código Postal<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="codigo_postal" autocomplete="off"  id="codigo_postal" value="<?= $seccion_ine_ciudadanoDatos['codigo_postal'] ?>" placeholder="" maxlength="120" onkeypress="return CheckNumeric()" /><br>
			</div>
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
				<label class="labelForm" id="labeltemaname"><br></label><br>
				<input type="button" value="Generar Mapa Dirección" onclick="generar_mapa()">
			</div>
			<div class="sucForm" style="width: 100%">
				<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
					<label class="labelForm" id="labeltemaname">Latitud<font color="#FF0004">*</font></label><br>
					<input class="inputlogin" type="text" name="latitud" autocomplete="off"  id="latitud" value="<?= $seccion_ine_ciudadanoDatos['latitud'] ?>" placeholder="" maxlength="120" onkeypress="" /><br>
				</div>
				<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
					<label class="labelForm" id="labeltemaname">Longitud<font color="#FF0004">*</font></label><br>
					<input class="inputlogin" type="text" name="longitud" autocomplete="off"  id="longitud" value="<?= $seccion_ine_ciudadanoDatos['longitud'] ?>" placeholder="" maxlength="120" onkeypress=" " /><br>
				</div>
				<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
					<label class="labelForm" id="labeltemaname"><br></label><br>
					<input type="button" value="Generar Mapa Coordenadas" onclick="generar_mapa_coordenadas()">
				</div>
				<input type="hidden" name="latitud_r" id="latitud_r" value="" placeholder="latitud">
				<input type="hidden" name="longitud_r" id="longitud_r" value="" placeholder="longitud">
				<br><br>
			</div>
			<div class="sucForm" style="width: 100%">
				<?= $mensaje_medio ?>
			</div>
			<?php
			if($tipo_perfil_usuario=='1' || $tipo_perfil_usuario=='2' ){
					?>
						
					<?php
				}
			?>
			<div id="mapaLoad">
				<?php include "mapa_seccion_ine.php"; ?>
			</div>
		</div>
		<div id="divDatosDireccionIne" style="display:none">
			<div class="sucFormTitulo">
				<div class="workFlowTitulo">
					<label class="labelForm" id="labeltemaname">Datos INE</label>
				</div>
				<div class="workFlowBotones">
					<input class="BotonWorkFlow" type="button" value="<< Anterior" onclick="datosDireccionIneAnt()" >
					<input class="BotonWorkFlow" type="button" value="Siguiente >>" onclick="datosDireccionIneSig()" >
				</div>
			</div>
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
				<label class="labelForm" id="labeltemaname"><br></label>
				<input type="button" value="Copiar Dirección Actual" onclick="copiar_direccion_acual()">
			</div>
			<div class="sucForm" style="width: 100%"></div>
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;">
				<label class="labelForm" id="labeltemaname">Municipio<font color="#FF0004">*</font></label><br>
				<select   name="id_municipio_ine" id="id_municipio_ine" class='myselect' onchange="locationMunicipio_ine(this)">  
					<?php
					echo municipios($seccion_ine_ciudadanoDatos['id_municipio_ine'],$seccion_ine_ciudadanoDatos['id_estado']);
					?>
				</select>
			</div>
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
				<label class="labelForm" id="labeltemaname">Localidad<font color="#FF0004">*</font></label><br>
				<select   name="id_localidad_ine" id="id_localidad_ine" class='myselect'>  
					<?php
					echo localidades($seccion_ine_ciudadanoDatos['id_localidad_ine'],$seccion_ine_ciudadanoDatos['id_municipio_ine'],$seccion_ine_ciudadanoDatos['id_estado']);
					?>
				</select>
			</div>
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;width: 100%">
				<label class="labelForm" id="labeltemaname">Calle<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="calle_ine" autocomplete="off" id="calle_ine" value="<?= $seccion_ine_ciudadanoDatos['calle_ine'] ?>" placeholder="" maxlength="120" onkeyup="aMays(event, this)" /><br>
			</div>
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
				<label class="labelForm" id="labeltemaname">Num Ext.</label><br>
				<input class="inputlogin" type="text" name="num_ext_ine" autocomplete="off"  id="num_ext_ine" value="<?= $seccion_ine_ciudadanoDatos['num_ext_ine'] ?>" placeholder="" maxlength="120" onkeyup="aMays(event, this)" /><br>
			</div>
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
				<label class="labelForm" id="labeltemaname">Num Int.</label><br>
				<input class="inputlogin" type="text" name="num_int_ine" autocomplete="off"  id="num_int_ine" value="<?= $seccion_ine_ciudadanoDatos['num_int_ine'] ?>" placeholder="" maxlength="120" onkeyup="aMays(event, this)" /><br>
			</div>
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
				<label class="labelForm" id="labeltemaname">Colonia<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="colonia_ine" autocomplete="off"  id="colonia_ine" value="<?= $seccion_ine_ciudadanoDatos['colonia_ine'] ?>" placeholder="" maxlength="120" onkeyup="aMays(event, this)" /><br>
			</div> 
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
				<label class="labelForm" id="labeltemaname">Código Postal<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="codigo_postal_ine" autocomplete="off"  id="codigo_postal_ine" value="<?= $seccion_ine_ciudadanoDatos['codigo_postal_ine'] ?>" placeholder="" maxlength="120" onkeypress="return CheckNumeric()" /><br>
			</div>
			<div class="sucForm" style="width: 100%"></div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Sección<font color="#FF0004">*</font></label><br>
				<select class="myselect" id="id_seccion_ine">
					<?php
					echo secciones_ine($seccion_ine_ciudadanoDatos['id_seccion_ine']);
					?>
				</select><br>
			</div>
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
				<label class="labelForm" id="labeltemaname"><br></label><br>
				<input type="button" value="Generar Manzanas" onclick="generar_manzanas()">
			</div>
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
				<label class="labelForm" id="labeltemaname"><br></label><br>
				<input type="button" value="Generar Mapa Dirección Si No Sabes La Sección" onclick="generar_mapa_seccion()">
			</div>
			<div class="sucForm" style="width: 100%"></div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Manzana<font color="#FF0004">*</font></label><br>
				<input maxlength="4" class="inputlogin" type="text" name="manzana" autocomplete="off"  id="manzana" value="<?= $seccion_ine_ciudadanoDatos['manzana'] ?>" placeholder="" /><br>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Vigencia INE<font color="#FF0004">*</font></label><br>
				<input maxlength="4" class="inputlogin" type="text" name="vigencia" autocomplete="off"  id="vigencia" value="<?= $seccion_ine_ciudadanoDatos['vigencia'] ?>" placeholder="2022" maxlength='4' onkeypress="return CheckNumeric()" /><br>
			</div>			
			<div class="sucForm" style="width: 100%"></div>
			<div id="mapaLoadManzana">
				<?php //include "mapa_seccion_ine_manzana.php"; ?>
			</div>
			
		</div>
		<div id="divObservaciones" style="display:none">
			<div class="sucFormTitulo">
				<div class="workFlowTitulo">
					<label class="labelForm" id="labeltemaname">Datos Observaciones</label>
				</div>
				<div class="workFlowBotones">
					<input class="BotonWorkFlowUnit" type="button" value="<< Anterior" onclick="datosObervacionesAnt()" >
				</div>
			</div>
			<div class="sucForm" style="width: 100%">
				<label class="labelForm" id="labeltemaname">Observaciones</label><br>
				<textarea id="observaciones" style="width: 99%;height: 150px"><?= $seccion_ine_ciudadanoDatos['observaciones'] ?></textarea> <br>
			</div>
		</div>
		<div class="sucForm" style="width: 100%" >
			<br>
			<?php
			if($switch_operacionesPermisos['registro']==true){
				?>
				<input style="width: 100%;margin-bottom: 10px" type="button" id="sumbmit" onclick="guardar()" value="Guardar">
				<?php
			}
			?>
				<!--<input type="button" onclick="ResetInput()" value="Borrar">-->
				<input style="width: 100%;margin-bottom: 10px" type="button" value="Cancelar" onclick="cerrar()">
		</div>
	</div> 
	<script type="text/javascript">
		$(".myselect").select2();
		<?php
			if ($id==""){
				?>
				localize();
				<?php
			}
		?>
		function error(errorCode){
			if(errorCode.code == 1){
				//alert("Debes activar tu geolocation para poder trabajar mejor con usted.");
				document.getElementById("mensaje").innerHTML = "Debes activar tu geolocation para poder trabajar mejor con usted.";
				document.getElementById("mensaje").style.borderBottom= "1px solid red";
				document.getElementById("mensaje").style.color = "Red";
			}
			else if (errorCode.code==2){
				//alert("Posicion no disponible,Debes activar tu geolocation para poder trabajar mejor con usted.");
				document.getElementById("mensaje").innerHTML = "Posicion no disponible,Debes activar tu geolocation para poder trabajar mejor con usted.";
				document.getElementById("mensaje").style.borderBottom= "1px solid red";
				document.getElementById("mensaje").style.color = "Red";
			}
			else{
				//alert("Ha ocurrido un error,Debes activar tu geolocation para poder trabajar mejor con usted.");
				document.getElementById("mensaje").innerHTML = "Ha ocurrido un error,Debes activar tu geolocation para poder trabajar mejor con usted.";
				document.getElementById("mensaje").style.borderBottom= "1px solid red";
				document.getElementById("mensaje").style.color = "Red";
			}
		}
		function localize(){
			if(navigator.geolocation){
				navigator.geolocation.getCurrentPosition(myMap,error);
			}
		}
	</script>