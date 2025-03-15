<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/claves.php";
	include '../functions/usuario_permisos.php';
	@session_start();
	$_SESSION['Paguinasub']="claves/index.php";
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

		'tipo_actividad'=>'tipo_actividad',
		'forma_tipo_actividad'=>'forma_tipo_actividad',

		'red_social'=>'red_social',
		'forma_red_social'=>'forma_red_social',

		'servidor_correo'=>'servidor_correo',
		'forma_servidor_correo'=>'forma_servidor_correo',

		'identidad'=>'identidad',
		'forma_identidad'=>'forma_identidad',

		'correo_electronico'=>'correo_electronico',
		'forma_correo_electronico'=>'forma_correo_electronico',

		'cuenta_red_social'=>'cuenta_red_social',
		'forma_cuenta_red_social'=>'forma_cuenta_red_social',

		'cuenta_red_social_actividad'=>'cuenta_red_social_actividad',
		'forma_cuenta_red_social_actividad'=>'forma_cuenta_red_social_actividad',

		'distrito_local'=>'distrito_local',
		'forma_distrito_local'=>'forma_distrito_local',

		'distrito_federal'=>'distrito_federal',
		'forma_distrito_federal'=>'forma_distrito_federal',

		'cuartel'=>'cuartel',
		'forma_cuartel'=>'forma_cuartel',

		'seccion_ine'=>'seccion_ine',
		'forma_seccion_ine'=>'forma_seccion_ine',

		'tipo_ciudadano'=>'tipo_ciudadano',
		'forma_tipo_ciudadano'=>'forma_tipo_ciudadano',

		'tipo_territorio'=>'tipo_territorio',
		'forma_tipo_territorio'=>'forma_tipo_territorio',

		'dependencia'=>'dependencia',
		'forma_dependencia'=>'forma_dependencia',

		'categoria_programa_apoyo'=>'categoria_programa_apoyo',
		'forma_categoria_programa_apoyo'=>'forma_categoria_programa_apoyo',

		'seccion_ine_ciudadano_programa_apoyo'=>'seccione_ine_ciudadano_programa_apoyo',
		'forma_seccion_ine_ciudadano_programa_apoyo'=>'forma_seccione_ine_ciudadano_programa_apoyo',

		'programa_apoyo'=>'programa_apoyo',
		'forma_programa_apoyo'=>'forma_programa_apoyo',

		'tipo_categoria_ciudadano'=>'tipo_categoria_ciudadano',
		'forma_tipo_categoria_ciudadano'=>'forma_tipo_categoria_ciudadano',

		'seccion_ine_ciudadano'=>'ciudadano',
		'forma_seccion_ine_ciudadano'=>'forma_ciudadano',

		'seccion_ine_actividad'=>'seccion_ine_actividad',
		'forma_seccion_ine_actividad'=>'forma_seccion_ine_actividad',

		'tipo_casilla'=>'tipo_casilla',
		'forma_tipo_casilla'=>'forma_tipo_casilla',

		'partido_2018'=>'partido_2018',
		'forma_partido_2018'=>'forma_partido_2018',

		'casilla_voto_2018'=>'casilla_voto_2018',
		'forma_casilla_voto_2018'=>'forma_casilla_voto_2018',


		'partido_2021'=>'partido_2021',
		'forma_partido_2021'=>'forma_partido_2021',

		'casilla_voto_2021'=>'casilla_voto_2021',
		'forma_casilla_voto_2021'=>'forma_casilla_voto_2021',

		'partido_legado'=>'partido_legado',
		'forma_partido_legado'=>'forma_partido_legado',

		'militante_partido'=>'militante_partido',
		'forma_militante_partido'=>'forma_militante_partido',

		'seccion_ine_grupo'=>'seccion_ine_grupo',
		'forma_seccion_ine_grupo'=>'forma_seccion_ine_grupo',

		'seccion_ine_ciudadano_grupo'=>'seccion_ine_ciudadano_grupo',
		'forma_seccion_ine_ciudadano_grupo'=>'forma_seccion_ine_ciudadano_grupo',


	);
	$moduloAccionPermisos = moduloAccionPermisos('configuracion','claves',$_COOKIE["id_usuario"]);
	//var_dump($switch_operacionesDatos);
	?>
	<title>Claves</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('setupmanagerpanel/index.php');
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
						$("#homebody").load('setupmanagerpanel/index.php');
					}else{
						if(data=="SINCAMBIOS"){
							$("#homebody").load('setupmanagerpanel/index.php');
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
