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
	include __DIR__."/../functions/directorios.php";
	include __DIR__."/../functions/equipos.php";
	include __DIR__."/../functions/equipos_directorios.php";
	include __DIR__."/../functions/equipos_sistemas_operativos_licencias.php";
	include __DIR__."/../functions/equipos_softwares_licencias.php";
	include __DIR__."/../functions/equipos_usuarios.php";
	
	include '../functions/tool_xhpzab.php';
	@session_start(); 
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}
	if($_GET['refresh']==1){
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		die;
	}
	echo $redirectSecurity=redirectSecurity($id,'equipos','equipos','index');
	if($redirectSecurity!=""){
		die;
	}

	$claveF = clave('equipos');
	$equipoDatos = equipoDatos($id);
	if($equipoDatos['clave']==""){
		$equipoDatos['clave']=$claveF['clave'];
	}
	$equipo_directorioDatos = equipo_directorioDatos('',$id,$id_directorio,1);
	$id_directorio = $equipo_directorioDatos['id_directorio'];
	if($id_directorio!=""){
		$id_directorio;
		$directorioDatos = directorioDatos($id_directorio);
		$nombre_completo = $directorioDatos['nombre']." ".$directorioDatos['apellido_paterno']." ".$directorioDatos['apellido_materno'];
	}else{
		echo $redirectSecurity=redirectSecurity($id_directorio,'directorios','directorios','index');
		if($redirectSecurity!=""){
			die;
		}
	}
	
	$equipos_sistemas_operativos_licenciasDatos = equipos_sistemas_operativos_licenciasDatos('',$id);
	$equipos_softwares_licenciasDatos = equipos_softwares_licenciasDatos('',$id);
	$equipos_usuariosDatos = equipos_usuariosDatos('',$id);

	$permiso="update";
	?>
	<title>Update</title>
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

			var id = '<?= $id?>';
			if(id == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_directorio = '<?= $id_directorio ?>';
			id_directorio = id_directorio.trim();
			id_directoriox = id_directorio.replace(espacios_invalidos, '');
			if(id_directoriox == ""){
				document.getElementById("clave").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id Directorio requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

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
					id: fila[1],
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
					id: fila[1],
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
			var equipo_directorio = [];
			var data = {
					'id' : id,
					'id_directorio' : id_directorio,
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
					'observaciones' : observaciones,
				}
				equipo_directorio.push(data);
			$.ajax({
				type: "POST",
				url: "equiposDirectorios/db_edit.php",
				data: {equipo_directorio: equipo_directorio,equipo_softwares:softwares,equipo_sistemas_operativos:sistemas_operativos,usuarios:usuarios },
				success: function(data) {
					//document.getElementById("form").reset();  
					//document.getElementById("form").style.border="";
					//
					if(data=="SI"){
						document.getElementById("sumbmit").disabled = true;
						document.getElementById("mensaje").classList.remove("mensajeError");
						$("#mensaje").html("&nbsp;");
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						urlink="equiposDirectorios/index.php";
						dataString = 'urlink='+urlink; 
						$.ajax({
							type: "POST",
							url: "functions/backarray.php",
							data: dataString,
							success: function(data) { 	}
						});
						$("#homebody").load(urlink);
					}else{
						if(data==""){
							urlink="equiposDirectorios/index.php";
							dataString = 'urlink='+urlink; 
							$.ajax({
								type: "POST",
								url: "functions/backarray.php",
								data: dataString,
								success: function(data) { 	}
							});
							$("#homebody").load(urlink);
						}else{
							document.getElementById("sumbmit").disabled = false;
							$("#mensaje").html(data);
							document.getElementById("mensaje").classList.add("mensajeError");
							
						}
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
				<h2 style="text-transform:capitalize"><?= $nombre_completo ?> </h2>
				<label class="tituloForm">
					<font style="font-size: 25px;">Modificar Equipo</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar a equipo.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>