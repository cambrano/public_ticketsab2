<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/directorios.php";
	include __DIR__."/../functions/tipos_equipos.php";
	include __DIR__."/../functions/softwares.php";
	include __DIR__."/../functions/sistemas_operativos.php";

	@session_start();
	include '../functions/tool_xhpzab.php';
	$id_directorio = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
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

			
			var id_directorio = '<?= $id_directorio ?>'; 
			id_directorio = id_directorio.trim();
			id_directoriox = id_directorio.replace(espacios_invalidos, '');
			if(id_directoriox == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id Directorio requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var id_equipo = document.getElementById("id_equipo").value; 
			id_equipo = id_equipo.trim();
			id_equipox = id_equipo.replace(espacios_invalidos, '');
			if(id_equipox == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id Equipo requerido");
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


			var equipo_directorio = [];
			var data = {
					'id_directorio' : id_directorio,
					'id_equipo' : id_equipo,
					'clave' : clave,
					'folio' : folio,
				}
			equipo_directorio.push(data);

			$.ajax({
				type: "POST",
				url: "equiposDirectorios/db_add_reasignacion.php",
				data: {equipo_directorio: equipo_directorio},
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
		});
	</script> 
	<div class="bodymanager" id="bodymanager"> 
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<h2 style="text-transform:capitalize"><?= $nombre_completo ?> </h2>
				<label class="tituloForm">
					<font style="font-size: 25px;">Reasignar Equipo</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para reasignar equipo.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form_reasignacion.php";?>
		</div>
	</div>