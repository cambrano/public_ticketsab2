<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','equipos',$_COOKIE["id_usuario"]);
	if(empty($moduloAccionPermisos)){
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
?>
	<script>
		function datosUsuariosToggle() {
			const div = document.getElementById('divDatosUsuario');
			const boton = document.querySelector('.BotonWorkFlowUsuario');
			
			if (div.style.display === 'none') {
				div.style.display = 'block';
				boton.value = 'Ocultar';
			} else {
				div.style.display = 'none';
				boton.value = 'Mostrar';
			}
		}
		function datosSistemasOperativosToggle() {
			const div = document.getElementById('divDatosSistemasOperativos');
			const boton = document.querySelector('.BotonWorkFlowSistemasOperativos');
			
			if (div.style.display === 'none') {
				div.style.display = 'block';
				boton.value = 'Ocultar';
			} else {
				div.style.display = 'none';
				boton.value = 'Mostrar';
			}
		}
		function datosSoftwaresToggle() {
			const div = document.getElementById('divDatosSoftwares');
			const boton = document.querySelector('.BotonWorkFlowSoftwares');
			
			if (div.style.display === 'none') {
				div.style.display = 'block';
				boton.value = 'Ocultar';
			} else {
				div.style.display = 'none';
				boton.value = 'Mostrar';
			}
		}
		$( function() {
			$( "#sistema_operativo_licencia_fecha_inicial" ).datepicker({
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd',
				yearRange: "1890:2010",
				defaultDate: "2006-01-01",
				onSelect: function (date) { 
					document.getElementById("sistema_operativo_licencia_fecha_inicial").style.border= "";
				}
			});
			$( "#sistema_operativo_licencia_fecha_final" ).datepicker({
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd',
				yearRange: "1890:2010",
				defaultDate: "2006-01-01",
				onSelect: function (date) { 
					document.getElementById("sistema_operativo_licencia_fecha_final").style.border= "";
				}
			});
			$( "#software_licencia_fecha_inicial" ).datepicker({
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd',
				yearRange: "1890:2010",
				defaultDate: "2006-01-01",
				onSelect: function (date) { 
					document.getElementById("software_licencia_fecha_inicial").style.border= "";
				}
			});
			$( "#software_licencia_fecha_final" ).datepicker({
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd',
				yearRange: "1890:2010",
				defaultDate: "2006-01-01",
				onSelect: function (date) { 
					document.getElementById("software_licencia_fecha_final").style.border= "";
				}
			});
		});


		let dataTableSistemasOperativos;
		$(document).ready(function () {
			// Inicializa el DataTable con opciones de responsividad
			dataTableSistemasOperativos = $('#sistemas_operativos-tabla').DataTable({
				responsive: true, // Activa responsividad
				destroy: true,    // Permite reinicialización
				pageLength: 11,   // Registros por página
				retrieve: true,   // Recupera la tabla si ya fue inicializada
				info: true,       // Muestra información de registros
				processing: true, // Muestra estado de procesamiento
				order: [[0, "desc"]], // Orden inicial por la primera columna descendente
				paging: true,     // Activa paginación
				searching: false, // Desactiva el buscador global
				columnDefs: [
					{ orderable: false, targets: [9] } // Desactiva orden en la columna 'Acciones'
				],
				scrollX: true,    // Habilita scroll horizontal
				language: {       // Traducción de textos
					sProcessing: "Procesando...",
					sZeroRecords: "Registro no encontrado",
					sEmptyTable: "No Existe Registros",
					sInfo: "Mostrar (_START_ a _END_) de _TOTAL_ Registros",
					sInfoEmpty: "Mostrando 0 a 0 de 0 Registros",
					sInfoFiltered: "(Filtrado de _MAX_ Total Registros)",
					oPaginate: {
						sFirst: "<<",
						sLast: ">>",
						sNext: ">",
						sPrevious: "<"
					},
					oAria: {
						sSortAscending: ": Activar para ordenar la columna ascendente",
						sSortDescending: ": Activar para ordenar la columna descendente"
					}
				}
			});

			// Ajusta columnas y recalcula el diseño responsivo
			dataTableSistemasOperativos.columns.adjust().responsive.recalc();
		});


		function guardarSistemaOperativo() {
			document.getElementById("sumbmit_guardar_sistema_operativo").disabled = true;
			document.getElementById("mensaje_sistema_operativo").classList.remove("mensajeSucces");
			document.getElementById("mensaje_sistema_operativo").classList.remove("mensajeError");
			$("#mensaje_sistema_operativo").html("&nbsp");
			var espacios_invalidos= /\s+/g;

			var id_equipo_sistema_operativo_licencia = document.getElementById("id_equipo_sistema_operativo_licencia").value; 
			id_equipo_sistema_operativo_licencia = id_equipo_sistema_operativo_licencia.trim();
			id = id_equipo_sistema_operativo_licencia.replace(espacios_invalidos, '');
			
			var sistema_operativo_licencia_id_equipo = document.getElementById("sistema_operativo_licencia_id_equipo").value; 
			sistema_operativo_licencia_id_equipo = sistema_operativo_licencia_id_equipo.trim();
			id_equipo = sistema_operativo_licencia_id_equipo.replace(espacios_invalidos, '');



			var id_sistema_operativo = document.getElementById("id_sistema_operativo").value; 
			id_sistema_operativo = id_sistema_operativo.trim();
			id_sistema_operativox = id_sistema_operativo.replace(espacios_invalidos, '');
			if(id_sistema_operativox == ""){
				document.getElementById("id_sistema_operativo").focus(); 
				document.getElementById("sumbmit_guardar_sistema_operativo").disabled = false;
				$("#mensaje_sistema_operativo").html("Sistema Operativo requerido");
				document.getElementById("mensaje_sistema_operativo").classList.add("mensajeError");
				return false;
			}
			const sistema_operativoTexto = $('#id_sistema_operativo option:selected').text();

			var sistema_operativo_licencia_fecha_inicial = document.getElementById("sistema_operativo_licencia_fecha_inicial").value;
			sistema_operativo_licencia_fecha_inicial = sistema_operativo_licencia_fecha_inicial.trim();
			sistema_operativo_licencia_fecha_inicialx = sistema_operativo_licencia_fecha_inicial.replace(espacios_invalidos, '');
			if(sistema_operativo_licencia_fecha_inicialx == ""){
				document.getElementById("sistema_operativo_licencia_fecha_inicial").focus(); 
				document.getElementById("sumbmit_guardar_sistema_operativo").disabled = false;
				$("#mensaje_sistema_operativo").html("Fecha Inicial Válida requerido");
				document.getElementById("mensaje_sistema_operativo").classList.add("mensajeError");
				return false;
			} else{
				if(!fechaValida(sistema_operativo_licencia_fecha_inicialx)){ 
					document.getElementById("sistema_operativo_licencia_fecha_inicial").focus(); 
					document.getElementById("sumbmit_guardar_sistema_operativo").disabled = false;
					$("#mensaje_sistema_operativo").html("Fecha Inicial Válida requerido");
					document.getElementById("mensaje_sistema_operativo").classList.add("mensajeError");
					return false;
				}
			}
			var sistema_operativo_licencia_fecha_final = document.getElementById("sistema_operativo_licencia_fecha_final").value;
			sistema_operativo_licencia_fecha_final = sistema_operativo_licencia_fecha_final.trim();
			sistema_operativo_licencia_fecha_finalx = sistema_operativo_licencia_fecha_final.replace(espacios_invalidos, '');
			if(sistema_operativo_licencia_fecha_finalx == ""){
				document.getElementById("sistema_operativo_licencia_fecha_final").focus(); 
				document.getElementById("sumbmit_guardar_sistema_operativo").disabled = false;
				$("#mensaje_sistema_operativo").html("Fecha Final Válida requerido");
				document.getElementById("mensaje_sistema_operativo").classList.add("mensajeError");
				return false;
			} else{
				if(!fechaValida(sistema_operativo_licencia_fecha_finalx)){ 
					document.getElementById("sistema_operativo_licencia_fecha_final").focus(); 
					document.getElementById("sumbmit_guardar_sistema_operativo").disabled = false;
					$("#mensaje_sistema_operativo").html("Fecha Final Válida requerido");
					document.getElementById("mensaje_sistema_operativo").classList.add("mensajeError");
					return false;
				}
			}

			// Verificar si la fecha inicial es mayor que la fecha final
			if (new Date(sistema_operativo_licencia_fecha_inicial) > new Date(sistema_operativo_licencia_fecha_final)) {
				$("#mensaje_sistema_operativo").html('La fecha inicial no puede ser mayor a la fecha final.');
				document.getElementById("mensaje_sistema_operativo").classList.add("mensajeError");
				document.getElementById("sumbmit_guardar_sistema_operativo").disabled = false;
				return;
			}

			var sistema_operativo_licencia_serial = document.getElementById("sistema_operativo_licencia_serial").value; 
			sistema_operativo_licencia_serial = sistema_operativo_licencia_serial.trim();
			sistema_operativo_licencia_serialx = sistema_operativo_licencia_serial.replace(espacios_invalidos, '');
			if(sistema_operativo_licencia_serialx == ""){
				document.getElementById("sistema_operativo_licencia_serial").focus(); 
				document.getElementById("sumbmit_guardar_sistema_operativo").disabled = false;
				$("#mensaje_sistema_operativo").html("Serial requerido");
				document.getElementById("mensaje_sistema_operativo").classList.add("mensajeError");
				return false;
			}

			var sistema_operativo_licencia_vigencia = document.getElementById("sistema_operativo_licencia_vigencia").value; 
			sistema_operativo_licencia_vigencia = sistema_operativo_licencia_vigencia.trim();
			sistema_operativo_licencia_vigenciax = sistema_operativo_licencia_vigencia.replace(espacios_invalidos, '');
			if(sistema_operativo_licencia_vigenciax == ""){
				document.getElementById("sistema_operativo_licencia_vigencia").focus(); 
				document.getElementById("sumbmit_guardar_sistema_operativo").disabled = false;
				$("#mensaje_sistema_operativo").html("Vigencia requerido");
				document.getElementById("mensaje_sistema_operativo").classList.add("mensajeError");
				return false;
			}
			
			var sistema_operativo_licencia_observaciones = document.getElementById("sistema_operativo_licencia_observaciones").value; 
			sistema_operativo_licencia_observaciones = sistema_operativo_licencia_observaciones.trim();


			// Agrega una nueva fila al DataTable
			dataTableSistemasOperativos.row.add([
				id,
				id_equipo,
				id_sistema_operativo,
				sistema_operativoTexto,
				sistema_operativo_licencia_fecha_inicial,
				sistema_operativo_licencia_fecha_final,
				sistema_operativo_licencia_serial,
				sistema_operativo_licencia_vigencia,
				sistema_operativo_licencia_observaciones,
				`<button onclick="sistemaOperativoEliminarFila(this)">Eliminar</button>
				<button onclick="sistemaOperativoModificarFila(this)">Modificar</button>`
			]).draw(false);

			// Limpia los campos del formulario
			sistemaOperativoLimpiarCampos();
			document.getElementById("sumbmit_guardar_sistema_operativo").disabled = false;
		}

		function sistemaOperativoLimpiarCampos() {
			// Usamos jQuery para seleccionar y manipular los <select>
			const selects = $('#id_sistema_operativo, #sistema_operativo_licencia_vigencia');
			selects.val('').trigger('change');

			// Limpiar los campos de licencia
			$('#sistema_operativo_licencia_id_equipo, #sistema_operativo_licencia_fecha_inicial, #sistema_operativo_licencia_fecha_final, #sistema_operativo_licencia_serial, #sistema_operativo_licencia_observaciones').val('');

		}

		function sistemaOperativoEliminarFila(button) {
			const row = $(button).closest('tr');
			dataTableSistemasOperativos.row(row).remove().draw();
		}

		function sistemaOperativoModificarFila1(button) {
			const row = $(button).closest('tr');
			const data = dataTableSistemasOperativos.row(row).data();
			console.log(data);

			
		}

		function sistemaOperativoModificarFila(button) {
			const row = $(button).closest('tr'); // Buscar la fila del botón
			const dataTableRow = dataTableSistemasOperativos.row(row);

			// Si la fila se colapsa en modo responsive (child row)
			let data = dataTableRow.data(); // Datos de la fila

			if (!data && dataTableRow.child.isShown()) {
				// Si los datos están en el child row
				data = dataTableRow.child().data();
			}

			console.log(data); // Mostrar los datos
		}




		let dataTableSoftwares;
		$(document).ready(function () {
			// Variable para el estado responsivo
			let responsive = $(window).width() < 820;

			// Ajusta el valor de 'responsive' al cambiar el tamaño de la ventana
			$(window).resize(function () {
				responsive = $(window).width() < 820;
				dataTableSoftwares.responsive.recalc(); // Recalcula la respuesta al redimensionar
			});

			// Inicializa el DataTable
			dataTableSoftwares = $('#softwares-tabla').DataTable({
				destroy: true,
				responsive: responsive,
				pageLength: 11,
				retrieve: true,
				info: true,
				processing: true,
				sPaginationType: "full_numbers",
				fixedHeader: { header: true },
				order: [[0, "desc"]],
				ordering: true,
				searching: false, // Desactiva buscador global
				paging: true, // Activa paginación
				aoColumnDefs: [
					{
						"bSortable": false,
						"aTargets": [
							9
						]
					}, {
						/*"targets": [0, 1],"visible": false*/
					}
				],
				scrollY: "100%",
				scrollX: "100%",
				language: {
					sProcessing: "Procesando...",
					sLengthMenu: ' ',
					sSearch: "Buscar:",
					sZeroRecords: "Registro no encontrado",
					sEmptyTable: "No Existe Registros",
					sInfo: "Mostrar (_START_ a _END_) de _TOTAL_ Registros",
					sInfoEmpty: "Mostrando Registros del 0 al 0 de Total de 0 Registros",
					sInfoFiltered: "(Filtrado de _MAX_ Total Registros)",
					sLoadingRecords: "Cargando...",
					oPaginate: {
						sFirst: "<<",
						sLast: ">>",
						sNext: ">",
						sPrevious: "<"
					},
					oAria: {
						sSortAscending: ": Activar para ordenar la columna de manera ascendente",
						sSortDescending: ": Activar para ordenar la columna de manera descendente"
					}
				}
			});

			// Ajustes adicionales para la tabla
			$('#softwares-tabla').css('display', 'table');
			$('#softwares-tabla').DataTable().columns.adjust().responsive.recalc();
			$("#softwares-tabla_filter").css("display", "none"); // Oculta el buscador global
		});

		function guardarSoftware() {
			document.getElementById("sumbmit_guardar_software").disabled = true;
			document.getElementById("mensaje_software").classList.remove("mensajeSucces");
			document.getElementById("mensaje_software").classList.remove("mensajeError");
			$("#mensaje_software").html("&nbsp");
			var espacios_invalidos= /\s+/g;

			var id_equipo_software_licencia = document.getElementById("id_equipo_software_licencia").value; 
			id_equipo_software_licencia = id_equipo_software_licencia.trim();
			id = id_equipo_software_licencia.replace(espacios_invalidos, '');
			var software_licencia_id_equipo = document.getElementById("software_licencia_id_equipo").value; 
			software_licencia_id_equipo = software_licencia_id_equipo.trim();
			id_equipo = software_licencia_id_equipo.replace(espacios_invalidos, '');

			var id_software = document.getElementById("id_software").value; 
			id_software = id_software.trim();
			id_softwarex = id_software.replace(espacios_invalidos, '');
			if(id_softwarex == ""){
				document.getElementById("id_software").focus(); 
				document.getElementById("sumbmit_guardar_software").disabled = false;
				$("#mensaje_software").html("Software requerido");
				document.getElementById("mensaje_software").classList.add("mensajeError");
				return false;
			}
			const softwareTexto = $('#id_software option:selected').text();

			var software_licencia_fecha_inicial = document.getElementById("software_licencia_fecha_inicial").value;
			software_licencia_fecha_inicial = software_licencia_fecha_inicial.trim();
			software_licencia_fecha_inicialx = software_licencia_fecha_inicial.replace(espacios_invalidos, '');
			if(software_licencia_fecha_inicialx == ""){
				document.getElementById("software_licencia_fecha_inicial").focus(); 
				document.getElementById("sumbmit_guardar_software").disabled = false;
				$("#mensaje_software").html("Fecha Inicial Válida requerido");
				document.getElementById("mensaje_software").classList.add("mensajeError");
				return false;
			} else{
				if(!fechaValida(software_licencia_fecha_inicialx)){ 
					document.getElementById("software_licencia_fecha_inicial").focus(); 
					document.getElementById("sumbmit_guardar_software").disabled = false;
					$("#mensaje_software").html("Fecha Inicial Válida requerido");
					document.getElementById("mensaje_software").classList.add("mensajeError");
					return false;
				}
			}
			var software_licencia_fecha_final = document.getElementById("software_licencia_fecha_final").value;
			software_licencia_fecha_final = software_licencia_fecha_final.trim();
			software_licencia_fecha_finalx = software_licencia_fecha_final.replace(espacios_invalidos, '');
			if(software_licencia_fecha_finalx == ""){
				document.getElementById("software_licencia_fecha_final").focus(); 
				document.getElementById("sumbmit_guardar_software").disabled = false;
				$("#mensaje_software").html("Fecha Final Válida requerido");
				document.getElementById("mensaje_software").classList.add("mensajeError");
				return false;
			} else{
				if(!fechaValida(software_licencia_fecha_finalx)){ 
					document.getElementById("software_licencia_fecha_final").focus(); 
					document.getElementById("sumbmit_guardar_software").disabled = false;
					$("#mensaje_software").html("Fecha Final Válida requerido");
					document.getElementById("mensaje_software").classList.add("mensajeError");
					return false;
				}
			}

			// Verificar si la fecha inicial es mayor que la fecha final
			if (new Date(software_licencia_fecha_inicial) > new Date(software_licencia_fecha_final)) {
				$("#mensaje_software").html('La fecha inicial no puede ser mayor a la fecha final.');
				document.getElementById("mensaje_software").classList.add("mensajeError");
				document.getElementById("sumbmit_guardar_software").disabled = false;
				return;
			}

			var software_licencia_serial = document.getElementById("software_licencia_serial").value; 
			software_licencia_serial = software_licencia_serial.trim();
			software_licencia_serialx = software_licencia_serial.replace(espacios_invalidos, '');
			if(software_licencia_serialx == ""){
				document.getElementById("software_licencia_serial").focus(); 
				document.getElementById("sumbmit_guardar_software").disabled = false;
				$("#mensaje_software").html("Serial requerido");
				document.getElementById("mensaje_software").classList.add("mensajeError");
				return false;
			}

			var software_licencia_vigencia = document.getElementById("software_licencia_vigencia").value; 
			software_licencia_vigencia = software_licencia_vigencia.trim();
			software_licencia_vigenciax = software_licencia_vigencia.replace(espacios_invalidos, '');
			if(software_licencia_vigenciax == ""){
				document.getElementById("software_licencia_vigencia").focus(); 
				document.getElementById("sumbmit_guardar_software").disabled = false;
				$("#mensaje_software").html("Vigencia requerido");
				document.getElementById("mensaje_software").classList.add("mensajeError");
				return false;
			}
			
			var software_licencia_observaciones = document.getElementById("software_licencia_observaciones").value; 
			software_licencia_observaciones = software_licencia_observaciones.trim();


			// Agrega una nueva fila al DataTable
			dataTableSoftwares.row.add([
				id,
				id_equipo,
				id_software,
				softwareTexto,
				software_licencia_fecha_inicial,
				software_licencia_fecha_final,
				software_licencia_serial,
				software_licencia_vigencia,
				software_licencia_observaciones,
				`<button onclick="softwareEliminarFila(this)">Eliminar</button>
				<button onclick="softwareModificarFila(this)">Modificar</button>`
			]).draw(false);

			// Limpia los campos del formulario
			softwareLimpiarCampos();
			document.getElementById("sumbmit_guardar_software").disabled = false;
		}

		function softwareLimpiarCampos() {
			// Usamos jQuery para seleccionar y manipular los <select>
			const selects = $('#id_software, #software_licencia_vigencia');
			selects.val('').trigger('change');

			// Limpiar los campos de licencia
			$('#software_licencia_id_equipo, #software_licencia_fecha_inicial, #software_licencia_fecha_final, #software_licencia_serial, #software_licencia_observaciones').val('');

		}

		function softwareEliminarFila(button) {
			const row = $(button).closest('tr');
			dataTableSoftwares.row(row).remove().draw();
		}

		function softwareModificarFila(button) {
			const row = $(button).closest('tr');
			const data = dataTableSoftwares.row(row).data();

			// Rellenar los campos del formulario con los datos de la fila seleccionada
			$('#id_equipo_software_licencia').val(data[0]);
			$('#software_licencia_id_equipo').val(data[1]);
			$('#id_software').val(data[2]).trigger('change'); 
			$('#software_licencia_fecha_inicial').val(data[4]);
			$('#software_licencia_fecha_final').val(data[5]);
			$('#software_licencia_serial').val(data[6]);
			$('#software_licencia_vigencia').val(data[7]).trigger('change'); 
			$('#software_licencia_observaciones').val(data[8]);

			// Elimina la fila actual para que pueda ser reemplazada después de modificar
			dataTableSoftwares.row(row).remove().draw();
		}

	</script>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Equipo</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" <?= $claveF['input'] ?> type="text" style="width: 100%" name="clave" autocomplete="off"  id="clave" value="<?= $equipoDatos['clave'] ?>" placeholder="Clave" onkeyup="clave(this.value)" /><br>
		</div>
		<div class="sucForm" style="width:100%">
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Responsable Equipo<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_responsable_equipo">
				<?php
				echo responsables_equipos($equipoDatos['id_responsable_equipo']);
				?>
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Ubicación<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_ubicacion">
				<?php
				echo ubicaciones($equipoDatos['id_ubicacion']);
				?>
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo Equipo<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_tipo_equipo">
				<?php
				echo tipos_equipos($equipoDatos['id_tipo_equipo']);
				?>
			</select><br>
		</div>
		<div class="sucForm" style="width:100%" >
			<label class="labelForm" id="labeltemaname">Folio<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="folio" autocomplete="off"  id="folio" value="<?= $equipoDatos['folio'] ?>" placeholder="Área" /><br>
		</div>
		<div class="sucForm" style="width:100%" >
			<label class="labelForm" id="labeltemaname">Serial<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="serial" autocomplete="off"  id="serial" value="<?= $equipoDatos['serial'] ?>" placeholder="Serial" /><br>
		</div>
		<div class="sucForm" style="width:100%" >
			<label class="labelForm" id="labeltemaname">Marca<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="marca" autocomplete="off"  id="marca" value="<?= $equipoDatos['marca'] ?>" placeholder="Marca" /><br>
		</div>
		<div class="sucForm" style="width:100%" >
			<label class="labelForm" id="labeltemaname">Modelo<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="modelo" autocomplete="off"  id="modelo" value="<?= $equipoDatos['modelo'] ?>" placeholder="Modelo" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Memoria RAM(GB)<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="ram" autocomplete="off"  id="ram" value="<?= $equipoDatos['ram'] ?>" placeholder="RAM(GB)" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Procesador<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="procesador" autocomplete="off"  id="procesador" value="<?= $equipoDatos['procesador'] ?>" placeholder="Procesador" /><br>
		</div>
		<div class="sucFormTitulo">
			<div class="workFlowTitulo">
				<label class="labelForm" id="labeltemaname">Datos Usuario</label>
			</div>
			<div class="workFlowBotones">
				<input class="BotonWorkFlowUsuario" type="button" value="Mostrar" onclick="datosUsuariosToggle()" >
			</div>
		</div>
		<div id="divDatosUsuario" style="display:none">
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
		</div>

		<div class="sucFormTitulo">
			<div class="workFlowTitulo">
				<label class="labelForm" id="labeltemaname">Datos Sistemas Operativos</label>
			</div>
			<div class="workFlowBotones">
				<input class="BotonWorkFlowSistemasOperativos" type="button" value="Mostrar" onclick="datosSistemasOperativosToggle()" >
			</div>
		</div>
		<div id="divDatosSistemasOperativos" style="display:none">
			<div class="sucForm" style="display:">
				<label class="labelForm" id="labeltemaname">id Equipo Sistema Operativo Licencia<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" style="width: 100%" name="id_equipo_sistema_operativo_licencia" autocomplete="off"  id="id_equipo_sistema_operativo_licencia" value="<?= $equipoDatos['id_equipo_sistema_operativo_licencia'] ?>" placeholder="Id Equipo Sistema Operativo Licencia" /><br>
			</div>
			<div class="sucForm" style="display:">
				<label class="labelForm" id="labeltemaname">id Equipo<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" style="width: 100%" name="sistema_operativo_licencia_id_equipo" autocomplete="off"  id="sistema_operativo_licencia_id_equipo" value="<?= $equipoDatos['sistema_operativo_licencia_id_equipo'] ?>" placeholder="Id Equipo" /><br>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Sistemas Operativos<font color="#FF0004">*</font></label><br>
				<select class="myselect" id="id_sistema_operativo">
					<?php
					echo sistemas_operativos($equipoDatos['id_sistema_operativo']);
					?>
				</select><br>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Licencia Fecha Inicial<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" style="width: 100%" name="sistema_operativo_licencia_fecha_inicial" autocomplete="off"  id="sistema_operativo_licencia_fecha_inicial" value="<?= $equipoDatos['sistema_operativo_licencia_fecha_inicial'] ?>" placeholder="Fecha Inicial" /><br>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Licencia Fecha Final<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" style="width: 100%" name="sistema_operativo_licencia_fecha_final" autocomplete="off"  id="sistema_operativo_licencia_fecha_final" value="<?= $equipoDatos['sistema_operativo_licencia_fecha_final'] ?>" placeholder="Fecha Final" /><br>
			</div>
			<div class="sucForm" style="width:100%">
				<label class="labelForm" id="labeltemaname">Serial<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" style="width: 100%" name="sistema_operativo_licencia_serial" autocomplete="off"  id="sistema_operativo_licencia_serial" value="<?= $equipoDatos['sistema_operativo_licencia_serial'] ?>" placeholder="Serial" /><br>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Vigencia<font color="#FF0004">*</font></label><br>
				<select class="myselect" id="sistema_operativo_licencia_vigencia">
					<option value="">Seleccione</option>
					<option value="SI">SI</option>
					<option value="NO">NO</option>
				</select><br>
			</div>
			<div class="sucForm" style="width: 100%">
				<label class="labelForm" id="labeltemaname">Observaciones</label><br>
				<textarea id="sistema_operativo_licencia_observaciones" style="width: 99%;height: 150px"><?= $equipoDatos['sistema_operativo_licencia_observaciones'] ?></textarea> <br>
			</div>
			<div class="sucForm" style="width: 100%">
				<input type="button" id="sumbmit_guardar_sistema_operativo" onclick="guardarSistemaOperativo()" value="Guardar Sistema Operativo">
			</div>
			<div class="sucForm" style="width: 100%">
				<div id="mensaje_sistema_operativo" class="mensajeSolo" ><br></div>
			</div>
			<div class="sucForm" style="width: 100%">
				<div id="lista_sistema_operativo">
					

					<table id="sistemas_operativos-tabla" 
						class="table table-striped table-bordered cell-border compact stripe" 
						style="width:100%">
						<thead>
							<tr>
								<th>id_equipo_sistema_operativo_licencia</th>
								<th>id_equipo</th>
								<th>id_sistema_operativo</th>
								<th>Sistema Operativo</th>
								<th>Fecha Inicial</th>
								<th>Fecha Final</th>
								<th>Serial</th>
								<th>Vigencia</th>
								<th>Observaciones</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>


				</div>
			</div>
		</div>



		<div class="sucFormTitulo">
			<div class="workFlowTitulo">
				<label class="labelForm" id="labeltemaname">Datos Software</label>
			</div>
			<div class="workFlowBotones">
				<input class="BotonWorkFlowSoftwares" type="button" value="Mostrar" onclick="datosSoftwaresToggle()" >
			</div>
		</div>
		<div id="divDatosSoftwares" style="display:none">
			<div class="sucForm" style="display:">
				<label class="labelForm" id="labeltemaname">id Equipo Software Licencia<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" style="width: 100%" name="id_equipo_software_licencia" autocomplete="off"  id="id_equipo_software_licencia" value="<?= $equipoDatos['id_equipo_software_licencia'] ?>" placeholder="Id Equipo Software Licencia" /><br>
			</div>
			<div class="sucForm" style="display:">
				<label class="labelForm" id="labeltemaname">id Equipo<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" style="width: 100%" name="id_equipo" autocomplete="off"  id="id_equipo" value="<?= $equipoDatos['id_equipo'] ?>" placeholder="Id Equipo" /><br>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Software<font color="#FF0004">*</font></label><br>
				<select class="myselect" id="id_software">
					<?php
					echo softwares($equipoDatos['id_software']);
					?>
				</select><br>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Licencia Fecha Inicial<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" style="width: 100%" name="software_licencia_fecha_inicial" autocomplete="off"  id="software_licencia_fecha_inicial" value="<?= $equipoDatos['software_licencia_fecha_inicial'] ?>" placeholder="Fecha Inicial" /><br>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Licencia Fecha Final<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" style="width: 100%" name="software_licencia_fecha_final" autocomplete="off"  id="software_licencia_fecha_final" value="<?= $equipoDatos['software_licencia_fecha_final'] ?>" placeholder="Fecha Final" /><br>
			</div>
			<div class="sucForm" style="width:100%">
				<label class="labelForm" id="labeltemaname">Serial<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" style="width: 100%" name="software_licencia_serial" autocomplete="off"  id="software_licencia_serial" value="<?= $equipoDatos['software_licencia_serial'] ?>" placeholder="Serial" /><br>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Vigencia<font color="#FF0004">*</font></label><br>
				<select class="myselect" id="software_licencia_vigencia">
					<option value="">Seleccione</option>
					<option value="SI">SI</option>
					<option value="NO">NO</option>
				</select><br>
			</div>
			<div class="sucForm" style="width: 100%">
				<label class="labelForm" id="labeltemaname">Observaciones</label><br>
				<textarea id="software_licencia_observaciones" style="width: 99%;height: 150px"><?= $equipoDatos['software_licencia_observaciones'] ?></textarea> <br>
			</div>
			<div class="sucForm" style="width: 100%">
				<input type="button" id="sumbmit_guardar_software" onclick="guardarSoftware()" value="Guardar Software">
			</div>
			<div class="sucForm" style="width: 100%">
				<div id="mensaje_software" class="mensajeSolo" ><br></div>
			</div>
			<div class="sucForm" style="width: 100%">
				<div id="lista_software">
					<table id="softwares-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
						<thead>
							<tr>
								<th>id_equipo_software_licencia</th>
								<th>id_equipo</th>
								<th>id_software</th>
								<th>Software</th>
								<th>Fecha Inicial</th>
								<th>Fecha Final</th>
								<th>Serial</th>
								<th>Vigencia</th>
								<th>Observaciones</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>

				</div>
			</div>
		</div>






		
		

		<div class="sucForm" style="width: 100%">
			<br>
			<?php
			if($moduloAccionPermisos[$permiso] || $moduloAccionPermisos['all']){
				?>
				<input type="button" id="sumbmit" onclick="guardar()" value="Guardar">
				<!--<input type="button" onclick="ResetInput()" value="Borrar">-->
				<?php
			}
			?>
			<input type="button" value="Cancelar" onclick="cerrar()">
		</div>
	</div>
	<style type="text/css">
		.ui-autocomplete {
			max-height: 180px;
			margin-bottom: 10px;
			overflow-x: hidden;
			overflow-y: auto;
		}
		.select2-container--default.select2-container--focus .select2-selection--multiple {
			box-shadow: 0 0 10px #c5c5f2;
			-webkit-box-shadow: 0 0 10px #c5c5f2;
			-moz-box-shadow: 0 0 10px #c5c5f2;
			border: 1px solid #DDDDDD;
			width: 100%;
		}
		input[type=text] {
			height: 38px;
		}
		.select2-container--default .select2-selection--single {
			background-color: #fff;
			border: 1px solid #aaa;
			border-radius: 4px;
			height: 38px;
		}
		.select2-container--default .select2-selection--single .select2-selection__rendered {
			color: #444;
			line-height: 38px;
		}
		.select2-container--default .select2-selection--single .select2-selection__arrow {
			height: 32px;
			position: absolute;
			top: 1px;
			right: 1px;
			width: 20px;
		}
		.bs-actionsbox .btn-group button {
			width: 48%;
			font-size: 12px;
		}
	</style>
	<script type="text/javascript">
		$(".myselect").select2();
		$('.selectpicker').selectpicker({
			deselectAllText: '<span class="glyphicon glyphicon-remove-sign"></span>', 
			selectAllText: '<span class="glyphicon glyphicon-ok-sign"></span>',
			liveSearchNormalize : true,
			multipleSeparator: ' | ',
			noneResultsText: 'No Encontrado {0}',
		});
	</script>