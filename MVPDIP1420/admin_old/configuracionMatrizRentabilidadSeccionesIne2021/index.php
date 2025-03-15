<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/configuracion_matriz_rentabilidad_secciones_ine_2021.php";
	include __DIR__."/../functions/partidos_2021.php";
	include __DIR__."/../functions/partidos_legados.php";
	include __DIR__."/../functions/tipos_categorias_ciudadanos.php";
	include '../functions/usuario_permisos.php';
	@session_start();
	$_SESSION['Paguinasub']="configuracionMatrizRentabilidadSeccionesIne2021/index.php";
	unset($_SESSION['paguinaId']);

	$configuracion_matriz_rentabilidad_secciones_ine_2021Datos=configuracion_matriz_rentabilidad_secciones_ine_2021Datos();
	if($configuracion_matriz_rentabilidad_secciones_ine_2021Datos['id']!=""){
		$permiso="update";
	}else{
		$permiso="insert";
	}
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','configuracion_matriz_rentabilidad_secciones_ine_2021',$_COOKIE["id_usuario"]);
	//var_dump($configuracionMatrizRentabilidadSeccionesIneDatos);
	?>
	<title>Api Mailing</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load("setupLogistica/index.php");
		}
		function guardar() {
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");

			var votos_semaforo_amarillo = document.getElementById("votos_semaforo_amarillo").value; 
			if(votos_semaforo_amarillo == ""){
				document.getElementById("votos_semaforo_amarillo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Nombre requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			<?php
			if($tipo_uso_plataforma=='municipio'){
				?>
				var id_partido_2021_ayuntamiento = document.getElementById("id_partido_2021_ayuntamiento").value; 
				if(id_partido_2021_ayuntamiento == ""){
					document.getElementById("id_partido_2021_ayuntamiento").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Partido Ayuntamiento requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
				<?php
			}elseif($tipo_uso_plataforma=='distrito_local'){
				?>
				var id_partido_2021_distrito_local = document.getElementById("id_partido_2021_distrito_local").value; 
				if(id_partido_2021_distrito_local == ""){
					document.getElementById("id_partido_2021_distrito_local").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Partido Distrito Local requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
				<?php
			}elseif($tipo_uso_plataforma=='distrito_federal'){
				?>
				var id_partido_2021_distrito_federal = document.getElementById("id_partido_2021_distrito_federal").value; 
				if(id_partido_2021_distrito_federal == ""){
					document.getElementById("id_partido_2021_distrito_federal").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Partido Distrito Federal requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
				<?php
			}else{
				?>
				var id_partido_2021_ayuntamiento = document.getElementById("id_partido_2021_ayuntamiento").value; 
				if(id_partido_2021_ayuntamiento == ""){
					document.getElementById("id_partido_2021_ayuntamiento").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Partido Ayuntamiento requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
				var id_partido_2021_distrito_local = document.getElementById("id_partido_2021_distrito_local").value; 
				if(id_partido_2021_distrito_local == ""){
					document.getElementById("id_partido_2021_distrito_local").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Partido Distrito Local requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
				var id_partido_2021_distrito_federal = document.getElementById("id_partido_2021_distrito_federal").value; 
				if(id_partido_2021_distrito_federal == ""){
					document.getElementById("id_partido_2021_distrito_federal").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Partido Distrito Federal requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
				<?php
			}
			?>

			var id_partido_legado = document.getElementById("id_partido_legado").value; 
			if(id_partido_legado == ""){
				document.getElementById("id_partido_legado").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Key Sandbox requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_tipo_categoria_ciudadano = document.getElementById("id_tipo_categoria_ciudadano").value; 
			if(id_tipo_categoria_ciudadano == ""){
				document.getElementById("id_tipo_categoria_ciudadano").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Categoría Funcionario requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			<?php
			if($tipo_uso_plataforma=='municipio'){
				?>
				var configuracion_matriz_rentabilidad_secciones_ine_2021 = []; 
				var data = {
						'votos_semaforo_amarillo' : votos_semaforo_amarillo,
						'id_tipo_categoria_ciudadano' : id_tipo_categoria_ciudadano,
						'id_partido_2021_ayuntamiento' : id_partido_2021_ayuntamiento,
						'id_partido_legado' : id_partido_legado,
					}
				configuracion_matriz_rentabilidad_secciones_ine_2021.push(data);
				<?php
			}elseif($tipo_uso_plataforma=='distrito_local'){
				?>
				var configuracion_matriz_rentabilidad_secciones_ine_2021 = []; 
				var data = {
						'votos_semaforo_amarillo' : votos_semaforo_amarillo,
						'id_tipo_categoria_ciudadano' : id_tipo_categoria_ciudadano,
						'id_partido_2021_distrito_local' : id_partido_2021_distrito_local,
						'id_partido_legado' : id_partido_legado,
					}
				configuracion_matriz_rentabilidad_secciones_ine_2021.push(data);
				<?php
			}elseif($tipo_uso_plataforma=='distrito_federal'){
				?>
				var configuracion_matriz_rentabilidad_secciones_ine_2021 = []; 
				var data = {
						'votos_semaforo_amarillo' : votos_semaforo_amarillo,
						'id_tipo_categoria_ciudadano' : id_tipo_categoria_ciudadano,
						'id_partido_2021_distrito_federal' : id_partido_2021_distrito_federal,
						'id_partido_legado' : id_partido_legado,
					}
				configuracion_matriz_rentabilidad_secciones_ine_2021.push(data);
				<?php
			}else{
				?>
				var configuracion_matriz_rentabilidad_secciones_ine_2021 = []; 
				var data = {
						'votos_semaforo_amarillo' : votos_semaforo_amarillo,
						'id_tipo_categoria_ciudadano' : id_tipo_categoria_ciudadano,
						'id_partido_2021_ayuntamiento' : id_partido_2021_ayuntamiento,
						'id_partido_2021_distrito_local' : id_partido_2021_distrito_local,
						'id_partido_2021_distrito_federal' : id_partido_2021_distrito_federal,
						'id_partido_legado' : id_partido_legado,
					}
				configuracion_matriz_rentabilidad_secciones_ine_2021.push(data);
				<?php
			}
			?>

			$.ajax({
				type: "POST",
				url: "configuracionMatrizRentabilidadSeccionesIne2021/db_add_update.php",
				data: {configuracion_matriz_rentabilidad_secciones_ine_2021: configuracion_matriz_rentabilidad_secciones_ine_2021},
				success: function(data) {
					if(data=="SI"){ 
						document.getElementById("sumbmit").disabled = true;
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						$("#homebody").load('configuracionMatrizRentabilidadSeccionesIne2021/index.php');
					}else{
						if(data=="SINCAMBIOS"){
							$("#homebody").load('configuracionMatrizRentabilidadSeccionesIne2021/index.php');
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

	<div class="bodymanager" id="bodymanager"> 
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> <br>
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<?php
			if(empty($moduloAccionPermisos)){
				?>
				<script type="text/javascript">
					document.getElementById("mensaje").classList.add("mensajeError");
					$("#mensaje").html("No tiene permiso");
					$("#homebody").load('home.php');
				</script>
				<?php
				die;
			}
		?>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Configuracón Matriz Rentabilidad Actual</font>
				</label><br>
				<label class="tiempo_espera_segundosForm">
					<font style="font-size: 13px;">Por favor, complete el siguiente formulario para configuracón matriz de rentabilidad actual.</font><br><br>
				</label>
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>
