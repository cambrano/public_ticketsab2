<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/responsables_equipos.php";
	include __DIR__."/../functions/ubicaciones.php";
	include __DIR__."/../functions/tipos_equipos.php";
	include __DIR__."/../functions/sistemas_operativos.php";
	include __DIR__."/../functions/status.php";
	include __DIR__."/../functions/softwares.php";

	@session_start();
	if($_GET['refresh']==1){
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		die;
	}
	$claveF= clave('equipos');
	$equipoDatos['clave']=$claveF['clave'];
	$permiso="insert";
	?>
	<title>Create</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="equiposDirectorios/index.php";
			dataString = 'urlink='+urlink; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			$("#homebody").load(urlink);
		}
		function guardar() {
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");
			var espacios_invalidos= /\s+/g;

			var clave = document.getElementById("clave").value; 
			clave = clave.trim();
			clavex = clave.replace(espacios_invalidos, '');
			if(clavex == ""){
				document.getElementById("clave").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Clave requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_responsable_equipo = document.getElementById("id_responsable_equipo").value; 
			id_responsable_equipo = id_responsable_equipo.trim();
			id_responsable_equipox = id_responsable_equipo.replace(espacios_invalidos, '');
			if(id_responsable_equipox == ""){
				document.getElementById("id_responsable_equipox").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Responsable Equipo requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_ubicacion = document.getElementById("id_ubicacion").value; 
			id_ubicacion = id_ubicacion.trim();
			id_ubicacionx = id_ubicacion.replace(espacios_invalidos, '');
			if(id_ubicacionx == ""){
				document.getElementById("id_ubicacionx").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Ubicación requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_tipo_equipo = document.getElementById("id_tipo_equipo").value; 
			id_tipo_equipo = id_tipo_equipo.trim();
			id_tipo_equipox = id_tipo_equipo.replace(espacios_invalidos, '');
			if(id_tipo_equipox == ""){
				document.getElementById("id_tipo_equipox").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Tipo Equipo requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var folio = document.getElementById("folio").value; 
			folio = folio.trim();
			foliox = folio.replace(espacios_invalidos, '');
			if(foliox == ""){
				document.getElementById("folio").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Folio requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var serial = document.getElementById("serial").value; 
			serial = serial.trim();
			serialx = serial.replace(espacios_invalidos, '');
			if(serialx == ""){
				document.getElementById("serial").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Serial requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var marca = document.getElementById("marca").value; 
			marca = marca.trim();
			marcax = marca.replace(espacios_invalidos, '');
			if(marcax == ""){
				document.getElementById("marca").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Marca requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var modelo = document.getElementById("modelo").value; 
			modelo = modelo.trim();
			modelox = modelo.replace(espacios_invalidos, '');
			if(modelox == ""){
				document.getElementById("modelo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Modelo requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var ram = document.getElementById("ram").value; 
			ram = ram.trim();
			ramx = ram.replace(espacios_invalidos, '');
			if(ramx == ""){
				document.getElementById("ram").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Memoria RAM requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var procesador = document.getElementById("procesador").value; 
			procesador = procesador.trim();
			procesadorx = procesador.replace(espacios_invalidos, '');
			if(procesadorx == ""){
				document.getElementById("procesador").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Procesador requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}


			var ip = document.getElementById("ip").value; 
			ip = ip.trim();
			ipx = ip.replace(espacios_invalidos, '');
			if(ipx == ""){
				document.getElementById("ip").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("IP requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var macaddress = document.getElementById("macaddress").value; 
			macaddress = macaddress.trim();
			macaddressx = macaddress.replace(espacios_invalidos, '');
			if(macaddressx == ""){
				document.getElementById("macaddress").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Dirección MAC requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var observaciones = document.getElementById("observaciones").value; 
			observaciones = observaciones.trim();

			// Obtener los datos de todas las filas como un array
			const tableSoftwares = $('#softwares-tabla').DataTable();
			// Obtener los datos de todas las filas como un array
			const datosSoftwares = tableSoftwares.rows().data().toArray();
			let softwares = {};
			datosSoftwares.forEach((fila) => {
				softwares[fila[0]] = {
					id_equipo_software_licencia: fila[1],
					id_equipo: fila[2],
					id_software: fila[3],
					fecha_inicial: fila[5],
					fecha_final: fila[6],
					serial: fila[7],
					vigencia: fila[8],
					observaciones: fila[9]
				};
			});

			// Obtener los datos de todas las filas como un array
			const tableSistemasOperativos = $('#sistemas_operativos-tabla').DataTable();
			// Obtener los datos de todas las filas como un array
			const datosSistemasOperativos = tableSistemasOperativos.rows().data().toArray();
			let sistemas_operativos = {};
			datosSistemasOperativos.forEach((fila) => {
				sistemas_operativos[fila[0]] = {
					id_equipo_sistema_operativo_licencia: fila[1],
					id_equipo: fila[2],
					id_sistema_operativo: fila[3],
					fecha_inicial: fila[5],
					fecha_final: fila[6],
					serial: fila[7],
					vigencia: fila[8],
					observaciones: fila[9]
				};
			});

			// Obtener los datos de todas las filas como un array
			const tableUsuarios = $('#usuarios-tabla').DataTable();
			// Obtener los datos de todas las filas como un array
			const datosUsuarios = tableUsuarios.rows().data().toArray();
			let usuarios = {};
			datosUsuarios.forEach((fila) => {
				usuarios[
					fila[0]] = 
					{
						id: fila[1],
						id_equipo: fila[2],
						usuario: fila[3],
						password: fila[4],
						status: fila[6]
				};
			});


			var equipo = [];
			var data = {
				'clave' : clave,
				'id_responsable_equipo' : id_responsable_equipo,
				'id_ubicacion' : id_ubicacion,
				'id_tipo_equipo' : id_tipo_equipo,
				'folio' : folio,
				'serial' : serial,
				'marca' : marca,
				'modelo' : modelo,
				'ram' : ram,
				'procesador' : procesador,
				'macaddress' : macaddress,
				'ip' : ip,
				'observaciones' : observaciones
				
			}
			equipo.push(data);

			
			$.ajax({
				type: "POST",
				url: "equipos/db_add.php",
				data: {equipo: equipo,equipo_softwares:softwares,equipo_sistemas_operativos:sistemas_operativos,usuarios:usuarios },
				success: function(data) {
					//document.getElementById("form").reset();  
					//document.getElementById("form").style.border="";
					//
					if(data=="SI"){
						document.getElementById("sumbmit").disabled = true;
						$("#mensaje").html("&nbsp;");
						document.getElementById("mensaje").classList.remove("mensajeError");
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						urlink="equipos/index.php";
						dataString = 'urlink='+urlink; 
						$.ajax({
							type: "POST",
							url: "functions/backarray.php",
							data: dataString,
							success: function(data) { 	}
						});
						$("#homebody").load(urlink);
					}else{
						document.getElementById("mensaje").classList.add("mensajeError");
						document.getElementById("sumbmit").disabled = false;
						$("#mensaje").html(data);
					}
				}
			});

		}
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#mensaje").click(function(event) { 
				document.getElementById("mensaje").classList.remove("mensajeSucces");
				document.getElementById("mensaje").classList.remove("mensajeError");
				$("#mensaje").html("&nbsp");
			});
			$("#mensaje_sistema_operativo").click(function(event) { 
				document.getElementById("mensaje_sistema_operativo").classList.remove("mensajeSucces");
				document.getElementById("mensaje_sistema_operativo").classList.remove("mensajeError");
				$("#mensaje_sistema_operativo").html("&nbsp");
			});
			$("#mensaje_software").click(function(event) { 
				document.getElementById("mensaje_software").classList.remove("mensajeSucces");
				document.getElementById("mensaje_software").classList.remove("mensajeError");
				$("#mensaje_software").html("&nbsp");
			});
			$("#mensaje_usuario").click(function(event) { 
				document.getElementById("mensaje_usuario").classList.remove("mensajeSucces");
				document.getElementById("mensaje_usuario").classList.remove("mensajeError");
				$("#mensaje_usuario").html("&nbsp");
			});
		});
	</script> 
	<div class="bodymanager" id="bodymanager"> 
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para registrar y dar de nombrea a equipo.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>