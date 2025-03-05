<?php
		@session_start();
		$_SESSION['Paguinasub']="importacionesSistema/index.php"; 
		include "../functions/security.php"; 
		include "functions/security.php"; 
		include "../functions/importacion.php"; 
		include "functions/importacion.php"; 
	?>
	<title>Importacion Rapida</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('setupmanagerpanel/index.php');
		}

		function guardar() {
			
			document.getElementById("sumbmit").disabled = false;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$('#importacionArea').html("&nbsp;");
			$("#mensaje").html("&nbsp");
			var tipo_vista = document.getElementById("tipo_vista").value; 
			if(tipo_vista == ""){
				document.getElementById("tipo_vista").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Tipo Vista requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			} 
			var tipo_operacion = document.getElementById("tipo_operacion").value; 
			if(tipo_operacion == ""){
				document.getElementById("tipo_operacion").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Tipo Operación requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			} 
			var tabla_operacion = document.getElementById("tabla_operacion").value; 
			if(tabla_operacion == ""){
				document.getElementById("tabla_operacion").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Tipo Información requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var file = document.getElementById("file").value; 
			if(file == ""){
				document.getElementById("file").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Archivo Requerido requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var formData = new FormData($("#form")[0]);
			formData.append('tipo_operacion', tipo_operacion);
			formData.append('tabla_operacion', tabla_operacion);
			formData.append('tipo_vista', tipo_vista);

			if(tabla_operacion=="configuracion"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/configuracion_inicial/configuracion_inicial_validador_add.php";
				}else{
					var ruta = "importacionesSistema/configuracion_inicial/configuracion_inicial_validador_edit.php";
				}
			}

			if(tabla_operacion=="claves"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/claves/claves_validador_add.php";
				}else{
					var ruta = "importacionesSistema/claves/claves_validador_edit.php";
				}
			}

			if(tabla_operacion=="avisos_privacidad"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/avisos_privacidad/avisos_privacidad_validador_add.php";
				}else{
					var ruta = "importacionesSistema/avisos_privacidad/avisos_privacidad_validador_edit.php";
				}
			}

			if(tabla_operacion=="administradores_sistema"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/empleados/administradores_sistema_validador_add.php";
				}else{
					var ruta = "importacionesSistema/empleados/administradores_sistema_validador_edit.php";
				}
			}

			if(tabla_operacion=="empleados"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/empleados/empleados_validador_add.php";
				}else{
					var ruta = "importacionesSistema/empleados/empleados_validador_edit.php";
				}
			}

			if(tabla_operacion=="sucursales"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/sucursales/sucursales_validador_add.php";
				}else{
					var ruta = "importacionesSistema/sucursales/sucursales_validador_edit.php";
				}
			}

			if(tabla_operacion=="clientes"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/clientes/clientes_validador_add.php";
				}else{
					var ruta = "importacionesSistema/clientes/clientes_validador_edit.php";
				}
			}

			if(tabla_operacion=="bancos"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/bancos/bancos_validador_add.php";
				}else{
					var ruta = "importacionesSistema/bancos/bancos_validador_edit.php";
				}
			}

			if(tabla_operacion=="terminales"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/terminales/terminales_validador_add.php";
				}else{
					var ruta = "importacionesSistema/terminales/terminales_validador_edit.php";
				}
			}

			if(tabla_operacion=="tipos_productos"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/tipos_productos/tipos_productos_validador_add.php";
				}else{
					var ruta = "importacionesSistema/tipos_productos/tipos_productos_validador_edit.php";
				}
			}

			if(tabla_operacion=="productos"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/productos/productos_validador_add.php";
				}else{
					var ruta = "importacionesSistema/productos/productos_validador_edit.php";
				}
			}

			if(tabla_operacion=="tipos_habitaciones"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/tipos_habitaciones/tipos_habitaciones_validador_add.php";
				}else{
					var ruta = "importacionesSistema/tipos_habitaciones/tipos_habitaciones_validador_edit.php";
				}
			}

			if(tabla_operacion=="hoteles"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/hoteles/hoteles_validador_add.php";
				}else{
					var ruta = "importacionesSistema/hoteles/hoteles_validador_edit.php";
				}
			}

			if(tabla_operacion=="proveedores"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/proveedores/proveedores_validador_add.php";
				}else{
					var ruta = "importacionesSistema/proveedores/proveedores_validador_edit.php";
				}
			}

			if(tabla_operacion=="grupos"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/grupos/grupos_validador_add.php";
				}else{
					var ruta = "importacionesSistema/grupos/grupos_validador_edit.php";
				}
			}

			if(tabla_operacion=="ventas"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/ventas/ventas_validador_add.php";
				}else{
					var ruta = "importacionesSistema/ventas/ventas_validador_edit.php";
				}
			}

			if(tabla_operacion=="ventas_productos"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/ventas_productos/ventas_productos_validador_add.php";
				}else{
					var ruta = "importacionesSistema/ventas_productos/ventas_productos_validador_edit.php";
					$("#importacionArea").html("<br><div class='mensajeError'>No se puede editar por importación rápida</div>");
					return false;
				}
			}

			if(tabla_operacion=="ventas_pasajeros"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/ventas_pasajeros/ventas_pasajeros_validador_add.php";
				}else{
					var ruta = "importacionesSistema/ventas_pasajeros/ventas_pasajeros_validador_edit.php";
					$("#importacionArea").html("<br><div class='mensajeError'>No se puede editar por importación rápida</div>");
					return false;
				}
			}

			if(tabla_operacion=="tipos_gastos"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/tipos_gastos/tipos_gastos_validador_add.php";
				}else{
					var ruta = "importacionesSistema/tipos_gastos/tipos_gastos_validador_edit.php";
				}
			}

			if(tabla_operacion=="gastos"){
				if(tipo_operacion==1){
					//var ruta = "importacionesSistema/import_validador_gastos_add.php";
					var ruta = "importacionesSistema/gastos/gastos_validador_add.php";
				}else{
					//var ruta = "importacionesSistema/import_validador_gastos_edit.php";
					var ruta = "importacionesSistema/gastos/gastos_validador_edit.php";
				}
			}

			if(tabla_operacion=="pagos_clientes"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/import_validador_pagos_clientes_add.php";
					var ruta = "importacionesSistema/pagos_clientes/pagos_clientes_validador_add.php";
				}else{
					var ruta = "importacionesSistema/import_validador_pagos_clientes_edit.php";
					var ruta = "importacionesSistema/pagos_clientes/pagos_clientes_validador_edit.php";
				}
			}

			if(tabla_operacion=="pagos_proveedores"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/import_validador_pagos_proveedores_add.php";
					var ruta = "importacionesSistema/pagos_clientes/pagos_proveedores_validador_add.php";
				}else{
					var ruta = "importacionesSistema/import_validador_pagos_proveedores_edit.php";
					var ruta = "importacionesSistema/pagos_clientes/pagos_proveedores_validador_edit.php";
				}
			}

			if(tabla_operacion=="pagos_proveedores_comisiones"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/import_validador_pagos_proveedores_comisiones_add.php";
					var ruta = "importacionesSistema/pagos_proveedores_comisiones/pagos_proveedores_comisiones_validador_add.php";
				}else{
					var ruta = "importacionesSistema/import_validador_pagos_proveedores_comisiones_edit.php";
					var ruta = "importacionesSistema/pagos_proveedores_comisiones/pagos_proveedores_comisiones_validador_edit.php";
				}
			}

			if(tabla_operacion=="cobros_proveedores"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/import_validador_cobros_proveedores_add.php";
					var ruta = "importacionesSistema/cobros_proveedores/cobros_proveedores_validador_add.php";
				}else{
					var ruta = "importacionesSistema/import_validador_cobros_proveedores_edit.php";
					var ruta = "importacionesSistema/cobros_proveedores/cobros_proveedores_validador_add.php";
				}
			}

			if(tabla_operacion=="abonos_bancos"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/import_validador_abonos_bancos_add.php";
					var ruta = "importacionesSistema/abonos_bancos/abonos_bancos_validador_add.php";
				}else{
					var ruta = "importacionesSistema/import_validador_abonos_bancos_edit.php";
					var ruta = "importacionesSistema/abonos_bancos/abonos_bancos_validador_add.php";
				}
			}

			if(tabla_operacion=="tipos_destinos"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/tipos_destinos/tipos_destinos_validador_add.php";
				}else{
					var ruta = "importacionesSistema/tipos_destinos/tipos_destinos_validador_edit.php";
				}
			}

			if(tabla_operacion=="destinos"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/destinos/destinos_validador_add.php";
				}else{
					var ruta = "importacionesSistema/destinos/destinos_validador_edit.php";
				}
			}

			document.getElementById('loadSistema').style.display = "inline-block";
			 $.ajax({
				url: ruta,
				type: "POST",
				data: formData, 
				contentType: false,
				processData: false,
				success: function(data){
					document.getElementById('importacionArea').style.display = "block";
					document.getElementById('loadSistema').style.display = "none"; 
					$("#importacionArea").html(data);
					document.getElementById("sumbmit").disabled = false;
				}
			});
		}

		function resetMensajes(){
			document.getElementById('loadSistema').style.display = "none"; 
			$('#importacionArea').html("&nbsp;");
			$('#mensaje').html("&nbsp;");
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
		}
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#mensaje").click(function(event) { 
				document.getElementById("mensaje").classList.remove("mensajeSucces");
				document.getElementById("mensaje").classList.remove("mensajeError");
				$("#mensaje").html("&nbsp;");
			});
		});
	</script>
	<div class="bodymanager" id="bodymanager">
		<div class="submenux" onclick="subConfiguracion()">Configuración</div> / 
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
						<font style="font-size: 25px;">Importacion Inicial</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Layout</font><br>
					<a href="importacionesSistema/layout_sistema.xlsx" target="_blank">Descargar Layout</a>
				</label>
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>