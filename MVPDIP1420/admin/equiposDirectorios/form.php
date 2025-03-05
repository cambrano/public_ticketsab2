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
		function copiar_clave_acual(){
			var clave = document.getElementById("clave").value;
			document.getElementById("folio").value = clave;
		}
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
			const table = document.getElementById('sistemas_operativos-tabla');
			const boton = document.querySelector('.BotonWorkFlowSistemasOperativos');
			
			if (div.style.display === 'none') {
				div.style.display = 'block';
				table.style.display = 'block';
				boton.value = 'Ocultar';
			} else {
				div.style.display = 'none';
				table.style.display = 'none';
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
			// Inicializa el DataTable
			dataTableSistemasOperativos = $('#sistemas_operativos-tabla').DataTable({
				destroy: true,
				responsive: true,
				pageLength: 11,
				retrieve: true,
				info: true,
				processing: true,
				sPaginationType: "full_numbers",
				fixedHeader: { header: true },
				//order: [[0, "desc"]],
				ordering: false,
				searching: false, // Desactiva buscador global
				paging: true, // Activa paginación
				aoColumnDefs: [
					{"bSortable": false,"aTargets": [9]},
					{ "targets": [ 0,1,2,3 ],"visible": false},
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
			$('#sistemas_operativos-tabla').css('display', 'table');
			dataTableSistemasOperativos.columns.adjust().responsive.recalc();
			$("#sistemas_operativos-tabla_filter").css("display", "none"); // Oculta el buscador global

			// Evento para recalcular al redimensionar
			$(window).resize(function () {
				dataTableSistemasOperativos.responsive.recalc();
			});
			/*
			$('#sistemas_operativos-tabla tbody').on('click', 'tr', function () {
				const data = $('#sistemas_operativos-tabla').DataTable().row(this).data();
				console.log(data); // para depurar
			});
			// Configura el evento de clic para los botones con la clase 'btn-modificar' dentro de la tabla
			$('#sistemas_operativos-tabla tbody').on('click', '.btn-modificar', function () {
				// Obtiene la fila correspondiente al botón clicado
				const row = $(this).closest('tr'); // Encuentra la fila más cercana al botón
				const dataTableSistemasOperativos = $('#sistemas_operativos-tabla').DataTable(); // Asegúrate de inicializar DataTable

				// Obtiene los datos de la fila usando DataTables
				const data = dataTableSistemasOperativos.row(row).data();
				console.log(data); // para depurar
			});
			*/
		});
		
		function guardarSistemaOperativo() {
			document.getElementById("sumbmit_guardar_sistema_operativo").disabled = true;
			document.getElementById("mensaje_sistema_operativo").classList.remove("mensajeSucces");
			document.getElementById("mensaje_sistema_operativo").classList.remove("mensajeError");
			$("#mensaje_sistema_operativo").html("&nbsp");
			var espacios_invalidos= /\s+/g;

			
			var sistema_operativo_licencia_row = document.getElementById("sistema_operativo_licencia_row").value; 
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
			sistema_operativo_licencia_row

			sistema_operativo_licencia_row
			if (sistema_operativo_licencia_row == "") {
				// Agrega una nueva fila al DataTable
				const rowNode = dataTableSistemasOperativos.row.add([
					dataTableSistemasOperativos.rows().count(),
					id,
					id_equipo,
					id_sistema_operativo,
					sistema_operativoTexto,
					sistema_operativo_licencia_fecha_inicial,
					sistema_operativo_licencia_fecha_final,
					sistema_operativo_licencia_serial,
					sistema_operativo_licencia_vigencia,
					sistema_operativo_licencia_observaciones,
					`<button onclick="sistemaOperativoEliminarFila(${dataTableSistemasOperativos.rows().count()})">Eliminar</button>
					<button onclick="sistemaOperativoModificarFila(${dataTableSistemasOperativos.rows().count()})">Modificar</button>`
				]).draw(false);
			}else{
				const newRowData = [
					sistema_operativo_licencia_row,
					id,
					id_equipo,
					id_sistema_operativo,
					sistema_operativoTexto,
					sistema_operativo_licencia_fecha_inicial,
					sistema_operativo_licencia_fecha_final,
					sistema_operativo_licencia_serial,
					sistema_operativo_licencia_vigencia,
					sistema_operativo_licencia_observaciones,
					`<button onclick="sistemaOperativoEliminarFila(${sistema_operativo_licencia_row})">Eliminar</button>
					<button onclick="sistemaOperativoModificarFila(${sistema_operativo_licencia_row})">Modificar</button>`
				];
				// Obtener el índice de la última fila agregada
				table = $('#sistemas_operativos-tabla').DataTable();
				const dataArray = [];
				numero_rows = 0;
				table.rows().every(function () {
					const data = this.data(); // Obtener los datos de la fila actual
					numero_rows = numero_rows + 1;
					dataArray.push(data); // Agregar al array
				});
				//console.log(numero_rows);
				const datos = {};
				dataArray.forEach((fila, index) => {
					datos[fila[0]] = fila;
				});
				datos[sistema_operativo_licencia_row] = newRowData
				
				// Eliminar todas las filas actuales de la tabla
				table.clear();
				// Agregar las nuevas filas desde el array 'datos'

				Object.values(datos).forEach((row) => {
					table.row.add(row).draw(false); // Agregar cada fila y redibujar parcialmente
				});

			}
			sistemaOperativoLimpiarCampos();
			document.getElementById("sumbmit_guardar_sistema_operativo").disabled = false;
		}
		function sistemaOperativoModificarFila(rowIndex) {
			const dataTableSistemasOperativos = $('#sistemas_operativos-tabla').DataTable();
			// Obtiene los datos de la fila para modificar
			const data = dataTableSistemasOperativos.row(rowIndex).data();
			//console.log(data); // Para depurar
			// Aquí puedes agregar la lógica para modificar la fila
			// Rellenar los campos del formulario con los datos de la fila seleccionada
			$('#id_equipo_sistema_operativo_licencia').val(data[1]);
			$('#sistema_operativo_licencia_id_equipo').val(data[2]);
			$('#id_sistema_operativo').val(data[3]).trigger('change'); 
			$('#sistema_operativo_licencia_fecha_inicial').val(data[5]);
			$('#sistema_operativo_licencia_fecha_final').val(data[6]);
			$('#sistema_operativo_licencia_serial').val(data[7]);
			$('#sistema_operativo_licencia_vigencia').val(data[8]).trigger('change'); 
			$('#sistema_operativo_licencia_observaciones').val(data[9]);
			$('#sistema_operativo_licencia_row').val(rowIndex);
			

			// Elimina la fila actual para que pueda ser reemplazada después de modificar
			dataTableSistemasOperativos.row(rowIndex).remove().draw();

		}

		function sistemaOperativoLimpiarCampos() {
			// Usamos jQuery para seleccionar y manipular los <select>
			const selects = $('#id_sistema_operativo, #sistema_operativo_licencia_vigencia');
			selects.val('').trigger('change');

			// Limpiar los campos de licencia
			$('#sistema_operativo_licencia_row, #sistema_operativo_licencia_id_equipo, #sistema_operativo_licencia_fecha_inicial, #sistema_operativo_licencia_fecha_final, #sistema_operativo_licencia_serial, #sistema_operativo_licencia_observaciones').val('');

		}
		function sistemaOperativoEliminarFila(rowIndex) {
			const dataTableSistemasOperativos = $('#sistemas_operativos-tabla').DataTable();
			// Elimina la fila usando el índice
			dataTableSistemasOperativos.row(rowIndex).remove().draw();
		}
		function guardarSistemaOperativo1() {
			document.getElementById("sumbmit_guardar_sistema_operativo").disabled = true;
			document.getElementById("mensaje_sistema_operativo").classList.remove("mensajeSucces");
			document.getElementById("mensaje_sistema_operativo").classList.remove("mensajeError");
			$("#mensaje_sistema_operativo").html("&nbsp");
			var espacios_invalidos= /\s+/g;

			
			var sistema_operativo_licencia_row = document.getElementById("sistema_operativo_licencia_row").value; 
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

			/*
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
			*/
			sistema_operativo_licencia_row
			if (sistema_operativo_licencia_row === "") {
				// Agrega una nueva fila al DataTable
				const rowNode = dataTableSistemasOperativos.row.add([
					id,
					id_equipo,
					id_sistema_operativo,
					sistema_operativoTexto,
					sistema_operativo_licencia_fecha_inicial,
					sistema_operativo_licencia_fecha_final,
					sistema_operativo_licencia_serial,
					sistema_operativo_licencia_vigencia,
					sistema_operativo_licencia_observaciones,
					`<button onclick="sistemaOperativoEliminarFila(${dataTableSistemasOperativos.rows().count()})">Eliminar</button>
					<button onclick="sistemaOperativoModificarFila(${dataTableSistemasOperativos.rows().count()})">Modificar</button>`
				]).draw(false);
			}else{
				// sistema_operativo_licencia_row es la varible que obtengo de un input para saber el index del row
				// Es el numero de row donde debe estar
				console.log(sistema_operativo_licencia_row);
				sistema_operativo_licencia_row
				const newRowData = [
					id,
					id_equipo,
					id_sistema_operativo,
					sistema_operativoTexto,
					sistema_operativo_licencia_fecha_inicial,
					sistema_operativo_licencia_fecha_final,
					sistema_operativo_licencia_serial,
					sistema_operativo_licencia_vigencia,
					sistema_operativo_licencia_observaciones,
					`<button onclick="sistemaOperativoEliminarFila(${sistema_operativo_licencia_row})">Eliminar</button>
					<button onclick="sistemaOperativoModificarFila(${sistema_operativo_licencia_row})">Modificar</button>`
				];
				dataTableSistemasOperativos.row(sistema_operativo_licencia_row).data(newRowData).draw();
				// Convertir los índices a un array
				//const totalRows = Array.from(dataTableSistemasOperativos.rows().indexes());
				/*
				if (totalRows.includes(sistema_operativo_licencia_row)) {
					dataTableSistemasOperativos.row(sistema_operativo_licencia_row).data(newRowData).draw(false);
				} else {
					console.error(`El índice ${sistema_operativo_licencia_row} no se encuentra disponible.`);
				}
					*/
				// Actualizar la fila existente
				//dataTableSistemasOperativos.row(sistema_operativo_licencia_row).data(newRowData).draw(false);
			}
			// Obtén el índice de la fila que se acaba de agregar
			//const rowIndex = dataTableSistemasOperativos.rows().count() - 1;

			// Limpia los campos del formulario
			//sistemaOperativoLimpiarCampos();
			document.getElementById("sumbmit_guardar_sistema_operativo").disabled = false;
		}
		function sistemaOperativoModificarFila1(button) {
			const row = $(button).closest('tr');
			const data = dataTableSistemasOperativos.row(row).data();
		}
		function sistemaOperativoModificarFila2(button) {
			// Obtiene la fila correspondiente al botón clicado
			const row = $(button).closest('tr'); // Encuentra la fila más cercana al botón
			console.log(row);
			const dataTableSistemasOperativos = $('#sistemas_operativos-tabla').DataTable(); // Asegúrate de inicializar DataTable
			console.log(dataTableSistemasOperativos)
			//const dataTableSistemasOperativos = $('#sistemas_operativos-tabla_wrapper').DataTable(); // Asegúrate de inicializar DataTable
			

			// Obtiene los datos de la fila usando DataTables
			const data = dataTableSistemasOperativos.row(row).data();
			console.log(data); // para depurar
		}
		function sistemaOperativoEliminarFila1(button) {
			const row = $(button).closest('tr');
			dataTableSistemasOperativos.row(row).remove().draw();
		}



		let dataTableSoftwares;
		$(document).ready(function () {
			// Inicializa el DataTable
			dataTableSoftwares = $('#softwares-tabla').DataTable({
				destroy: true,
				responsive: true,
				pageLength: 11,
				retrieve: true,
				info: true,
				processing: true,
				sPaginationType: "full_numbers",
				fixedHeader: { header: true },
				//order: [[0, "desc"]],
				ordering: false,
				searching: false, // Desactiva buscador global
				paging: true, // Activa paginación
				aoColumnDefs: [
					{"bSortable": false,"aTargets": [9]},
					{ "targets": [ 0,1,2,3 ],"visible": false},
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
			dataTableSoftwares.columns.adjust().responsive.recalc();
			$("#softwares-tabla_filter").css("display", "none"); // Oculta el buscador global

			// Evento para recalcular al redimensionar
			$(window).resize(function () {
				dataTableSoftwares.responsive.recalc();
			});
			/*
			$('#softwares-tabla tbody').on('click', 'tr', function () {
				const data = $('#softwares-tabla').DataTable().row(this).data();
				console.log(data); // para depurar
			});
			// Configura el evento de clic para los botones con la clase 'btn-modificar' dentro de la tabla
			$('#softwares-tabla tbody').on('click', '.btn-modificar', function () {
				// Obtiene la fila correspondiente al botón clicado
				const row = $(this).closest('tr'); // Encuentra la fila más cercana al botón
				const dataTableSoftwares = $('#softwares-tabla').DataTable(); // Asegúrate de inicializar DataTable

				// Obtiene los datos de la fila usando DataTables
				const data = dataTableSoftwares.row(row).data();
				console.log(data); // para depurar
			});
			*/
		});
		
		function guardarSoftware() {
			document.getElementById("sumbmit_guardar_software").disabled = true;
			document.getElementById("mensaje_software").classList.remove("mensajeSucces");
			document.getElementById("mensaje_software").classList.remove("mensajeError");
			$("#mensaje_software").html("&nbsp");
			var espacios_invalidos= /\s+/g;

			
			var software_licencia_row = document.getElementById("software_licencia_row").value; 
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
			software_licencia_row

			software_licencia_row
			if (software_licencia_row == "") {
				// Agrega una nueva fila al DataTable
				const rowNode = dataTableSoftwares.row.add([
					dataTableSoftwares.rows().count(),
					id,
					id_equipo,
					id_software,
					softwareTexto,
					software_licencia_fecha_inicial,
					software_licencia_fecha_final,
					software_licencia_serial,
					software_licencia_vigencia,
					software_licencia_observaciones,
					`<button onclick="softwareEliminarFila(${dataTableSoftwares.rows().count()})">Eliminar</button>
					<button onclick="softwareModificarFila(${dataTableSoftwares.rows().count()})">Modificar</button>`
				]).draw(false);
			}else{
				const newRowData = [
					software_licencia_row,
					id,
					id_equipo,
					id_software,
					softwareTexto,
					software_licencia_fecha_inicial,
					software_licencia_fecha_final,
					software_licencia_serial,
					software_licencia_vigencia,
					software_licencia_observaciones,
					`<button onclick="softwareEliminarFila(${software_licencia_row})">Eliminar</button>
					<button onclick="softwareModificarFila(${software_licencia_row})">Modificar</button>`
				];
				// Obtener el índice de la última fila agregada
				table = $('#softwares-tabla').DataTable();
				const dataArray = [];
				numero_rows = 0;
				table.rows().every(function () {
					const data = this.data(); // Obtener los datos de la fila actual
					numero_rows = numero_rows + 1;
					dataArray.push(data); // Agregar al array
				});
				//console.log(numero_rows);
				const datos = {};
				dataArray.forEach((fila, index) => {
					datos[fila[0]] = fila;
				});
				datos[software_licencia_row] = newRowData
				
				// Eliminar todas las filas actuales de la tabla
				table.clear();
				// Agregar las nuevas filas desde el array 'datos'

				Object.values(datos).forEach((row) => {
					table.row.add(row).draw(false); // Agregar cada fila y redibujar parcialmente
				});

			}
			softwareLimpiarCampos();
			document.getElementById("sumbmit_guardar_software").disabled = false;
		}
		function softwareModificarFila(rowIndex) {
			const dataTableSoftwares = $('#softwares-tabla').DataTable();
			// Obtiene los datos de la fila para modificar
			const data = dataTableSoftwares.row(rowIndex).data();
			//console.log(data); // Para depurar
			// Aquí puedes agregar la lógica para modificar la fila
			// Rellenar los campos del formulario con los datos de la fila seleccionada
			$('#id_equipo_software_licencia').val(data[1]);
			$('#software_licencia_id_equipo').val(data[2]);
			$('#id_software').val(data[3]).trigger('change'); 
			$('#software_licencia_fecha_inicial').val(data[5]);
			$('#software_licencia_fecha_final').val(data[6]);
			$('#software_licencia_serial').val(data[7]);
			$('#software_licencia_vigencia').val(data[8]).trigger('change'); 
			$('#software_licencia_observaciones').val(data[9]);
			$('#software_licencia_row').val(rowIndex);
			

			// Elimina la fila actual para que pueda ser reemplazada después de modificar
			dataTableSoftwares.row(rowIndex).remove().draw();

		}
		function softwareLimpiarCampos() {
			// Usamos jQuery para seleccionar y manipular los <select>
			const selects = $('#id_software, #software_licencia_vigencia');
			selects.val('').trigger('change');

			// Limpiar los campos de licencia
			$('#software_licencia_row, #id_equipo_software_licencia, #software_licencia_id_equipo, #software_licencia_fecha_inicial, #software_licencia_fecha_final, #software_licencia_serial, #software_licencia_observaciones').val('');

		}
		function softwareEliminarFila(rowIndex) {
			const dataTableSoftwares = $('#softwares-tabla').DataTable();
			// Elimina la fila usando el índice
			dataTableSoftwares.row(rowIndex).remove().draw();
		}

		let dataTableUsuarios;
		$(document).ready(function () {
			// Inicializa el DataTable
			dataTableUsuarios = $('#usuarios-tabla').DataTable({
				destroy: true,
				responsive: true,
				pageLength: 11,
				retrieve: true,
				info: true,
				processing: true,
				sPaginationType: "full_numbers",
				fixedHeader: { header: true },
				//order: [[0, "desc"]],
				ordering: false,
				searching: false, // Desactiva buscador global
				paging: true, // Activa paginación
				aoColumnDefs: [
					{"bSortable": false,"aTargets": [7]},
					{ "targets": [ 0,1,2,6 ],"visible": false},
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
			$('#usuarios-tabla').css('display', 'table');
			dataTableUsuarios.columns.adjust().responsive.recalc();
			$("#usuarios-tabla_filter").css("display", "none"); // Oculta el buscador global

			// Evento para recalcular al redimensionar
			$(window).resize(function () {
				dataTableUsuarios.responsive.recalc();
			});
			/*
			$('#softwares-tabla tbody').on('click', 'tr', function () {
				const data = $('#softwares-tabla').DataTable().row(this).data();
				console.log(data); // para depurar
			});
			// Configura el evento de clic para los botones con la clase 'btn-modificar' dentro de la tabla
			$('#softwares-tabla tbody').on('click', '.btn-modificar', function () {
				// Obtiene la fila correspondiente al botón clicado
				const row = $(this).closest('tr'); // Encuentra la fila más cercana al botón
				const dataTableSoftwares = $('#softwares-tabla').DataTable(); // Asegúrate de inicializar DataTable

				// Obtiene los datos de la fila usando DataTables
				const data = dataTableSoftwares.row(row).data();
				console.log(data); // para depurar
			});
			*/
		});
		function guardarUsuario() {
			document.getElementById("sumbmit_guardar_usuario").disabled = true;
			document.getElementById("mensaje_usuario").classList.remove("mensajeSucces");
			document.getElementById("mensaje_usuario").classList.remove("mensajeError");
			$("#mensaje_usuario").html("&nbsp");
			var espacios_invalidos= /\s+/g;

			var equipo_usuario_row = document.getElementById("equipo_usuario_row").value; 
			equipo_usuario_row = equipo_usuario_row.trim();
			equipo_usuario_row = equipo_usuario_row.replace(espacios_invalidos, '');

			var equipo_usuario_id_equipo_usuario = document.getElementById("equipo_usuario_id_equipo_usuario").value; 
			equipo_usuario_id_equipo_usuario = equipo_usuario_id_equipo_usuario.trim();
			id = equipo_usuario_id_equipo_usuario.replace(espacios_invalidos, '');

			var equipo_usuario_id_equipo = document.getElementById("equipo_usuario_id_equipo").value; 
			equipo_usuario_id_equipo = equipo_usuario_id_equipo.trim();
			id_equipo = equipo_usuario_id_equipo.replace(espacios_invalidos, '');

			var equipo_usuario_usuario = document.getElementById("equipo_usuario_usuario").value; 
			equipo_usuario_usuario = equipo_usuario_usuario.trim();
			equipo_usuario_usuariox = equipo_usuario_usuario.replace(espacios_invalidos, '');
			if(equipo_usuario_usuariox == ""){
				document.getElementById("id_software").focus(); 
				document.getElementById("sumbmit_guardar_usuario").disabled = false;
				$("#mensaje_software").html("Usuario requerido");
				document.getElementById("mensaje_usuario").classList.add("mensajeError");
				return false;
			}

			var equipo_usuario_password = document.getElementById("equipo_usuario_password").value;
			equipo_usuario_passwordx = equipo_usuario_password.replace(espacios_invalidos, '');
			if(equipo_usuario_passwordx == ""){
				document.getElementById("equipo_usuario_password").focus(); 
				document.getElementById("sumbmit_guardar_usuario").disabled = false;
				$("#mensaje").html("Constraseña requerido");
				document.getElementById("mensaje_usuario").classList.add("mensajeError");
				return false;
			}

			var equipo_usuario_password1 = document.getElementById("equipo_usuario_password1").value;
			equipo_usuario_password1x = equipo_usuario_password1.replace(espacios_invalidos, '');
			if(equipo_usuario_password1x == ""){
				document.getElementById("equipo_usuario_password").focus(); 
				document.getElementById("sumbmit_guardar_usuario").disabled = false;
				$("#mensaje").html("Constraseña Repetida requerido");
				document.getElementById("mensaje_usuario").classList.add("mensajeError");
				return false;
			}

			if(equipo_usuario_password1 != equipo_usuario_password1x){
				document.getElementById("equipo_usuario_password1").focus(); 
				document.getElementById("sumbmit_guardar_usuario").disabled = false;
				$("#mensaje").html("Constraseña No Coinciden requerido");
				document.getElementById("mensaje_usuario").classList.add("mensajeError");
				return false;
			}

			var equipo_usuario_status = document.getElementById("equipo_usuario_status").value; 
			if(equipo_usuario_status == ""){
				document.getElementById("equipo_usuario_status").focus(); 
				document.getElementById("sumbmit_guardar_usuario").disabled = false;
				$("#mensaje").html("Estatus requerido");
				document.getElementById("mensaje_usuario").classList.add("mensajeError");
				return false;
			}
			const equipo_usuario_statusTexto = $('#equipo_usuario_status option:selected').text();
			
			equipo_usuario_row
			if (equipo_usuario_row == "") {
				// Agrega una nueva fila al DataTable
				const rowNode = dataTableUsuarios.row.add([
					dataTableUsuarios.rows().count(),
					id,
					id_equipo,
					equipo_usuario_usuario,
					equipo_usuario_password,
					equipo_usuario_statusTexto,
					equipo_usuario_status,
					`<button onclick="usuarioEliminarFila(${dataTableUsuarios.rows().count()})">Eliminar</button>
					<button onclick="usuarioModificarFila(${dataTableUsuarios.rows().count()})">Modificar</button>`
				]).draw(false);
			}else{
				const newRowData = [
					equipo_usuario_row,
					id,
					id_equipo,
					equipo_usuario_usuario,
					equipo_usuario_password,
					equipo_usuario_statusTexto,
					equipo_usuario_status,
					`<button onclick="usuarioEliminarFila(${equipo_usuario_row})">Eliminar</button>
					<button onclick="usuarioModificarFila(${equipo_usuario_row})">Modificar</button>`
				];
				// Obtener el índice de la última fila agregada
				table = $('#usuarios-tabla').DataTable();
				const dataArray = [];
				numero_rows = 0;
				table.rows().every(function () {
					const data = this.data(); // Obtener los datos de la fila actual
					numero_rows = numero_rows + 1;
					dataArray.push(data); // Agregar al array
				});
				//console.log(numero_rows);
				const datos = {};
				dataArray.forEach((fila, index) => {
					datos[fila[0]] = fila;
				});
				datos[equipo_usuario_row] = newRowData
				
				// Eliminar todas las filas actuales de la tabla
				table.clear();
				// Agregar las nuevas filas desde el array 'datos'

				Object.values(datos).forEach((row) => {
					table.row.add(row).draw(false); // Agregar cada fila y redibujar parcialmente
				});

			}
			usuarioLimpiarCampos();
			document.getElementById("sumbmit_guardar_usuario").disabled = false;


			
		}
		function usuarioLimpiarCampos() {
			// Usamos jQuery para seleccionar y manipular los <select>
			const selects = $('#equipo_usuario_status');
			selects.val('').trigger('change');

			// Limpiar los campos de licencia
			$('#equipo_usuario_row,#equipo_usuario_id_equipo_usuario,#equipo_usuario_id_equipo,#equipo_usuario_usuario,#equipo_usuario_password,#equipo_usuario_password1').val('');

		}
		function usuarioEliminarFila(rowIndex) {
			const dataTableUsuarios = $('#usuarios-tabla').DataTable();
			// Elimina la fila usando el índice
			dataTableUsuarios.row(rowIndex).remove().draw();
		}
		function usuarioModificarFila(rowIndex) {
			const dataTableUsuarios = $('#usuarios-tabla').DataTable();
			// Obtiene los datos de la fila para modificar
			const data = dataTableUsuarios.row(rowIndex).data();
			//console.log(data); // Para depurar
			// Aquí puedes agregar la lógica para modificar la fila
			// Rellenar los campos del formulario con los datos de la fila seleccionada
			$('#equipo_usuario_row').val(rowIndex);
			$('#equipo_usuario_id_equipo_usuario').val(data[1]);
			$('#equipo_usuario_id_equipo').val(data[2]);
			$('#equipo_usuario_usuario').val(data[3]);
			$('#equipo_usuario_password').val(data[4]);
			$('#equipo_usuario_password1').val(data[4]);
			$('#equipo_usuario_status').val(data[6]).trigger('change'); 
			// Elimina la fila actual para que pueda ser reemplazada después de modificar
			dataTableUsuarios.row(rowIndex).remove().draw();
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
		<div class="sucForm" style="width:100%">
			<label class="labelForm" id="labeltemaname"><br></label>
			<input type="button" value="Copiar Clave" onclick="copiar_clave_acual()">
		</div>
		<div class="sucForm" style="width:100%" >
			<label class="labelForm" id="labeltemaname">Folio<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="folio" autocomplete="off"  id="folio" value="<?= $equipoDatos['folio'] ?>" placeholder="Folio" /><br>
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
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">IP<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="ip" autocomplete="off"  id="ip" value="<?= $equipoDatos['ip'] ?>" placeholder="192.168.1.1" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Dirección MAC<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="macaddress" autocomplete="off"  id="macaddress" value="<?= $equipoDatos['macaddress'] ?>" placeholder="80:f6:50:1b:2c:22" /><br>
		</div>
		<div class="sucForm" style="width: 100%">
				<label class="labelForm" id="labeltemaname">Observaciones</label><br>
				<textarea id="observaciones" style="width: 99%;height: 150px"><?= $equipoDatos['observaciones'] ?></textarea> <br>
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
		<div class="sucForm" style="display:none">
				<label class="labelForm" id="labeltemaname">row<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" style="width: 100%" name="equipo_usuario_row" autocomplete="off"  id="equipo_usuario_row" value="" placeholder="row" /><br>
			</div>
			<div class="sucForm" style="display:none">
				<label class="labelForm" id="labeltemaname">id Equipo Usuario<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" style="width: 100%" name="equipo_usuario_id_equipo_usuario" autocomplete="off"  id="equipo_usuario_id_equipo_usuario" value="<?= $equipoDatos['equipo_usuario_id_equipo_usuario'] ?>" placeholder="Id Equipo Usuario" /><br>
			</div>
			<div class="sucForm" style="display:none">
				<label class="labelForm" id="labeltemaname">id Equipo<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" style="width: 100%" name="equipo_usuario_id_equipo" autocomplete="off"  id="equipo_usuario_id_equipo" value="<?= $equipoDatos['equipo_usuario_id_equipo'] ?>" placeholder="Id Equipo" /><br>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Usuario<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="equipo_usuario_usuario" autocomplete="off"  id="equipo_usuario_usuario" value="<?= $usuarioDatos['equipo_usuario_usuario']  ?>" placeholder="" maxlength="45" /><br>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Password<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="equipo_usuario_password" autocomplete="off"  id="equipo_usuario_password" value="<?= $usuarioDatos['equipo_usuario_password'] ?>" placeholder="" maxlength="10" /><br>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Repetir Password<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="equipo_usuario_password1" autocomplete="off"  id="equipo_usuario_password1" value="<?= $usuarioDatos['equipo_usuario_password1'] ?>" placeholder="" maxlength="10" />
			</div>
			<div class="sucForm" style="width: 100%"></div>
			<div class="sucForm">
				<label class="labelForm" id="labelMostrar">Mostrar</label><input type="checkbox"  id="monstar_contraseña" value="1"><br>
			</div>
			<div class="sucForm" style="width: 100%">
				<label class="labelForm" id="labeltemaname">Estatus<font color="#FF0004">*</font></label><br>
					<select id="equipo_usuario_status" class="myselect" name="equipo_usuario_status" >
					<?php	echo statusGeneralForm($usuarioDatos['status']); ?>
				</select><br><br>
			</div>
			<div class="sucForm" style="width: 100%">
				<input type="button" id="sumbmit_guardar_usuario" onclick="guardarUsuario()" value="Guardar Usuario">
			</div>
			<div class="sucForm" style="width: 100%">
				<div id="mensaje_usuario" class="mensajeSolo" ><br></div>
			</div>
			<div style="clear: both;"></div>
			<div id="dataTable">
				<table id="usuarios-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
					<thead>
						<tr>
							<th>row</th>
							<th>id_equipo_usuario</th>
							<th>id_equipo</th>
							<th>usuario</th>
							<th>password</th>
							<th>status</th>
							<th>status_numero</th>
							<th>Opciones</th>
						</tr>
					</thead>
					<tbody>
						
							<?php
								$num = 0;
								foreach ($equipos_usuariosDatos as $key => $value) {
									echo '<tr>';
									echo '<td>'.$num.'</td>';
									echo '<td>'.$value['id'].'</td>';
									echo '<td>'.$value['id_equipo'].'</td>';
									echo '<td>'.$value['usuario'].'</td>';
									echo '<td>'.$value['password'].'</td>';
									echo '<td>' . ($value['status'] == 1 ? 'Activo' : 'No Activo') . '</td>';
									echo '<td>'.$value['status'].'</td>';
									echo '<td>
										<button onclick="usuarioEliminarFila('.$num.')">Eliminar</button>
										<button onclick="usuarioModificarFila('.$num.')">Modificar</button></td>';
									$num = $num + 1;
									echo '</tr>';
								}
							?>
					</tbody>
				</table>
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
			<div class="sucForm" style="display:none">
				<label class="labelForm" id="labeltemaname">row<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" style="width: 100%" name="sistema_operativo_licencia_row" autocomplete="off"  id="sistema_operativo_licencia_row" value="" placeholder="row" /><br>
			</div>
			<div class="sucForm" style="display:none">
				<label class="labelForm" id="labeltemaname">id Equipo Sistema Operativo Licencia<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" style="width: 100%" name="id_equipo_sistema_operativo_licencia" autocomplete="off"  id="id_equipo_sistema_operativo_licencia" value="<?= $equipoDatos['id_equipo_sistema_operativo_licencia'] ?>" placeholder="Id Equipo Sistema Operativo Licencia" /><br>
			</div>
			<div class="sucForm" style="display:none">
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
					<option  value="SI">SI</option>
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
			<div style="clear: both;"></div>
			<div id="dataTable">
				<table id="sistemas_operativos-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
					<thead>
						<tr>
							<th>row</th>
							<th>id_equipo_sistema_operativo_licencia</th>
							<th>id_equipo</th>
							<th>id_sistema_operativo</th>
							<th>Sistema Operativo</th>
							<th>Fecha Inicial</th>
							<th>Fecha Final</th>
							<th>Serial</th>
							<th>Vigencia</th>
							<th>Observaciones</th>
							<th>Opciones</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$num = 0;
							foreach ($equipos_sistemas_operativos_licenciasDatos as $key => $value) {
								echo '<tr>';
								echo '<td>'.$num.'</td>';
								echo '<td>'.$value['id'].'</td>';
								echo '<td>'.$value['id_equipo'].'</td>';
								echo '<td>'.$value['id_sistema_operativo'].'</td>';
								echo '<td>'.$value['sistema_operativo'].'</td>';
								echo '<td>'.$value['fecha_inicial'].'</td>';
								echo '<td>'.$value['fecha_final'].'</td>';
								echo '<td>'.$value['serial'].'</td>';
								echo '<td>'.$value['vigencia'].'</td>';
								echo '<td>'.$value['observaciones'].'</td>';
								echo '<td>
									<button onclick="sistemaOperativoEliminarFila('.$num.')">Eliminar</button>
									<button onclick="sistemaOperativoModificarFila('.$num.')">Modificar</button></td>';
								$num = $num + 1;
								echo '</tr>';
							}
						?>
					</tbody>
				</table>
			</div> 
			
		</div>
		



		<div class="sucFormTitulo">
			<div class="workFlowTitulo">
				<label class="labelForm" id="labeltemaname">Datos Softwares</label>
			</div>
			<div class="workFlowBotones">
				<input class="BotonWorkFlowSoftwares" type="button" value="Mostrar" onclick="datosSoftwaresToggle()" >
			</div>
		</div>
		<div id="divDatosSoftwares" style="display:none">
		<div class="sucForm" style="display:none">
				<label class="labelForm" id="labeltemaname">row<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" style="width: 100%" name="software_licencia_row" autocomplete="off"  id="software_licencia_row" value="" placeholder="row" /><br>
			</div>
			<div class="sucForm" style="display:none">
				<label class="labelForm" id="labeltemaname">id Equipo Software Licencia<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" style="width: 100%" name="id_equipo_software_licencia" autocomplete="off"  id="id_equipo_software_licencia" value="<?= $equipoDatos['id_equipo_software_licencia'] ?>" placeholder="Id Equipo Software Licencia" /><br>
			</div>
			<div class="sucForm" style="display:none">
				<label class="labelForm" id="labeltemaname">id Equipo<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" style="width: 100%" name="software_licencia_id_equipo" autocomplete="off"  id="software_licencia_id_equipo" value="<?= $equipoDatos['software_licencia_id_equipo'] ?>" placeholder="Id Equipo" /><br>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Softwares<font color="#FF0004">*</font></label><br>
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
					<option  value="SI">SI</option>
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
			<div style="clear: both;"></div>
			<div id="dataTable">
				<table id="softwares-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
					<thead>
						<tr>
							<th>row</th>
							<th>id_equipo_softwares_licencia</th>
							<th>id_equipo</th>
							<th>id_software</th>
							<th>Software</th>
							<th>Fecha Inicial</th>
							<th>Fecha Final</th>
							<th>Serial</th>
							<th>Vigencia</th>
							<th>Observaciones</th>
							<th>Opciones</th>
						</tr>
					</thead>
					<tbody>
						
							<?php
								$num = 0;
								foreach ($equipos_softwares_licenciasDatos as $key => $value) {
									echo '<tr>';
									echo '<td>'.$num.'</td>';
									echo '<td>'.$value['id'].'</td>';
									echo '<td>'.$value['id_equipo'].'</td>';
									echo '<td>'.$value['id_software'].'</td>';
									echo '<td>'.$value['software'].'</td>';
									echo '<td>'.$value['fecha_inicial'].'</td>';
									echo '<td>'.$value['fecha_final'].'</td>';
									echo '<td>'.$value['serial'].'</td>';
									echo '<td>'.$value['vigencia'].'</td>';
									echo '<td>'.$value['observaciones'].'</td>';
									echo '<td>
										<button onclick="softwareEliminarFila('.$num.')">Eliminar</button>
										<button onclick="softwareModificarFila('.$num.')">Modificar</button></td>';
									$num = $num + 1;
									echo '</tr>';
								}
							?>
					</tbody>
				</table>
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