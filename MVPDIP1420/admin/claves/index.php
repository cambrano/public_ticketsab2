<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/claves.php";
	include '../functions/usuario_permisos.php';
	@session_start();
	$claveDatos=claveDatos();
	$id=$claveDatos['id']; 
	if($id!=""){
		$permiso="update";
	}else{
		$permiso="insert";
	}
	$inputs= array(
		'empleado'=>'empleado',
		'forma_empleado'=>'forma_empleado',

		'ubicacion'=>'ubicacion',
		'forma_ubicacion'=>'forma_ubicacion',
		
		'titulo_personal'=>'titulo_personal',
		'forma_titulo_personal'=>'forma_titulo_personal',

		'directorio'=>'directorio',
		'forma_directorio'=>'forma_directorio',

		'tipo_equipo'=>'tipo_equipo',
		'forma_tipo_equipo'=>'forma_tipo_equipo',

		'responsable_equipo'=>'responsable_equipo',
		'forma_responsable_equipo'=>'forma_responsable_equipo',

		'sistema_operativo'=>'sistema_operativo',
		'forma_sistema_operativo'=>'forma_sistema_operativo',

		'software'=>'software',
		'forma_software'=>'forma_software',

		'equipo'=>'equipo',
		'forma_equipo'=>'forma_equipo',

	);
	$moduloAccionPermisos = moduloAccionPermisos('configuracion','claves',$_COOKIE["id_usuario"]);
	//var_dump($switch_operacionesDatos);
	?>
	<title>Claves</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="setupmanagerpanel/index.php";
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
			<?php
			foreach ($inputs as $key => $value) {
				echo "var {$key} = document.getElementById('{$key}').value; ";
				echo "if({$key} == ''){";
				echo "document.getElementById('{$key}').focus();";
				echo "document.getElementById('sumbmit').disabled = false;";
				echo "$('#mensaje').html('".ucwords(strtolower(str_replace('_',' ', $value)))." requerido');";
				echo "document.getElementById('mensaje').classList.add('mensajeError');";
				echo "return false;";
				echo "}"; 
			}
			?>
			var claves = []; 
			var data = {
					<?php
					foreach ($inputs as $key => $value) {
						echo "'{$key}' : {$key},";
					}
					?>
				}
			claves.push(data);
			$.ajax({
				type: "POST",
				url: "claves/db_add_update.php",
				data: {claves: claves},
				success: function(data) {
					if(data=="SI"){ 
						document.getElementById("sumbmit").disabled = true;
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						urlink="setupmanagerpanel/index.php";
						dataString = 'urlink='+urlink; 
						$.ajax({
							type: "POST",
							url: "functions/backarray.php",
							data: dataString,
							success: function(data) { 	}
						});
						$("#homebody").load(urlink);
					}else{
						if(data=="SINCAMBIOS"){
							urlink="setupmanagerpanel/index.php";
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
		});
	</script>
	<script type="text/javascript">
		function aMays(e, elemento) {
			tecla=(document.all) ? e.keyCode : e.which; 
				elemento.value = elemento.value.toUpperCase();
		}
	</script>
	<div class="bodymanager" id="bodymanager"> 
		<div class="submenux" onclick="subConfiguracion()">Configuración</div> / 
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Configuración de las claves</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Formulario para configurar claves.</font><br>
				</label>
				<font style="font-size: 10px;">
					<strong>
						<br>
						1. Se coloca los prefijos(letra), antes de la clave con la cantidad de cifras(números) que se requiera.<br> 
						Ejemplo: EMP(empleados) con tres cifras, quedaría así EMP(001), con un máximo de 20 dígitos.<br>
						2. Se puede seleccionar el modo automática o manual a criterio de la empresa.<br>
					</strong>
				</font>
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>
