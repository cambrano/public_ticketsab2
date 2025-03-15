<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/configuracion_matriz_rentabilidad_secciones_ine_2018.php";
	include __DIR__."/../functions/partidos_2018.php";
	include __DIR__."/../functions/partidos_legados.php";
	include __DIR__."/../functions/tipos_categorias_ciudadanos.php";
	include '../functions/usuario_permisos.php';
	@session_start();
	$configuracion_matriz_rentabilidad_secciones_ine_2018Datos=configuracion_matriz_rentabilidad_secciones_ine_2018Datos();
	if($configuracion_matriz_rentabilidad_secciones_ine_2018Datos['id']!=""){
		$permiso="update";
	}else{
		$permiso="insert";
	}
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','configuracion_matriz_rentabilidad_secciones_ine_2018',$_COOKIE["id_usuario"]);
	//var_dump($configuracionMatrizRentabilidadSeccionesIneDatos);
	?>
	<title>Api Mailing</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="setupLogistica/index.php";
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
				var id_partido_2018_ayuntamiento = document.getElementById("id_partido_2018_ayuntamiento").value; 
				if(id_partido_2018_ayuntamiento == ""){
					document.getElementById("id_partido_2018_ayuntamiento").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Partido Ayuntamiento requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
				<?php
			}elseif($tipo_uso_plataforma=='distrito_local'){
				?>
				var id_partido_2018_distrito_local = document.getElementById("id_partido_2018_distrito_local").value; 
				if(id_partido_2018_distrito_local == ""){
					document.getElementById("id_partido_2018_distrito_local").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Partido Distrito Local requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
				<?php
			}elseif($tipo_uso_plataforma=='distrito_federal'){
				?>
				var id_partido_2018_distrito_federal = document.getElementById("id_partido_2018_distrito_federal").value; 
				if(id_partido_2018_distrito_federal == ""){
					document.getElementById("id_partido_2018_distrito_federal").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Partido Distrito Federal requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
				<?php
			}elseif($tipo_uso_plataforma=='gobernador'){
				?>
				var id_partido_2018_gobernador = document.getElementById("id_partido_2018_gobernador").value; 
				if(id_partido_2018_gobernador == ""){
					document.getElementById("id_partido_2018_gobernador").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Partido Gobernador requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
				<?php
			}elseif($tipo_uso_plataforma=='gobernador'){
				?>
				var id_partido_2018_gobernador = document.getElementById("id_partido_2018_gobernador").value; 
				if(id_partido_2018_gobernador == ""){
					document.getElementById("id_partido_2018_gobernador").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Partido Gobernador requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
				<?php
			}elseif($tipo_uso_plataforma=='senador'){
				?>
				var id_partido_2018_senador = document.getElementById("id_partido_2018_senador").value; 
				if(id_partido_2018_senador == ""){
					document.getElementById("id_partido_2018_senador").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Partido Gobernador requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
				<?php
			}else{
				?>
				var id_partido_2018_ayuntamiento = document.getElementById("id_partido_2018_ayuntamiento").value; 
				if(id_partido_2018_ayuntamiento == ""){
					//document.getElementById("id_partido_2018_ayuntamiento").focus(); 
					//document.getElementById("sumbmit").disabled = false;
					//$("#mensaje").html("Partido Ayuntamiento requerido");
					//document.getElementById("mensaje").classList.add("mensajeError");
					//return false;
				}
				var id_partido_2018_distrito_local = document.getElementById("id_partido_2018_distrito_local").value; 
				if(id_partido_2018_distrito_local == ""){
					//document.getElementById("id_partido_2018_distrito_local").focus(); 
					//document.getElementById("sumbmit").disabled = false;
					//$("#mensaje").html("Partido Distrito Local requerido");
					//document.getElementById("mensaje").classList.add("mensajeError");
					//return false;
				}
				var id_partido_2018_distrito_federal = document.getElementById("id_partido_2018_distrito_federal").value; 
				if(id_partido_2018_distrito_federal == ""){
					//document.getElementById("id_partido_2018_distrito_federal").focus(); 
					//document.getElementById("sumbmit").disabled = false;
					//$("#mensaje").html("Partido Distrito Federal requerido");
					//document.getElementById("mensaje").classList.add("mensajeError");
					//return false;
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
				var configuracion_matriz_rentabilidad_secciones_ine_2018 = []; 
				var data = {
						'votos_semaforo_amarillo' : votos_semaforo_amarillo,
						'id_tipo_categoria_ciudadano' : id_tipo_categoria_ciudadano,
						'id_partido_2018_ayuntamiento' : id_partido_2018_ayuntamiento,
						'id_partido_legado' : id_partido_legado,
					}
				configuracion_matriz_rentabilidad_secciones_ine_2018.push(data);
				<?php
			}elseif($tipo_uso_plataforma=='distrito_local'){
				?>
				var configuracion_matriz_rentabilidad_secciones_ine_2018 = []; 
				var data = {
						'votos_semaforo_amarillo' : votos_semaforo_amarillo,
						'id_tipo_categoria_ciudadano' : id_tipo_categoria_ciudadano,
						'id_partido_2018_distrito_local' : id_partido_2018_distrito_local,
						'id_partido_legado' : id_partido_legado,
					}
				configuracion_matriz_rentabilidad_secciones_ine_2018.push(data);
				<?php
			}elseif($tipo_uso_plataforma=='distrito_federal'){
				?>
				var configuracion_matriz_rentabilidad_secciones_ine_2018 = []; 
				var data = {
						'votos_semaforo_amarillo' : votos_semaforo_amarillo,
						'id_tipo_categoria_ciudadano' : id_tipo_categoria_ciudadano,
						'id_partido_2018_distrito_federal' : id_partido_2018_distrito_federal,
						'id_partido_legado' : id_partido_legado,
					}
				configuracion_matriz_rentabilidad_secciones_ine_2018.push(data);
				<?php
			}else{
				?>
				var configuracion_matriz_rentabilidad_secciones_ine_2018 = []; 
				var data = {
						'votos_semaforo_amarillo' : votos_semaforo_amarillo,
						'id_tipo_categoria_ciudadano' : id_tipo_categoria_ciudadano,
						'id_partido_2018_ayuntamiento' : id_partido_2018_ayuntamiento,
						'id_partido_2018_distrito_local' : id_partido_2018_distrito_local,
						'id_partido_2018_distrito_federal' : id_partido_2018_distrito_federal,
						'id_partido_legado' : id_partido_legado,
					}
				configuracion_matriz_rentabilidad_secciones_ine_2018.push(data);
				<?php
			}
			?>

			$.ajax({
				type: "POST",
				url: "configuracionMatrizRentabilidadSeccionesIne2018/db_add_update.php",
				data: {configuracion_matriz_rentabilidad_secciones_ine_2018: configuracion_matriz_rentabilidad_secciones_ine_2018},
				success: function(data) {
					if(data=="SI"){ 
						document.getElementById("sumbmit").disabled = true;
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						urlink="configuracionMatrizRentabilidadSeccionesIne2018/index.php";
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
							urlink="configuracionMatrizRentabilidadSeccionesIne2018/index.php";
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

	<div class="bodymanager" id="bodymanager"> 
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> <br>
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<?php
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
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Configuracón Matriz Rentabilidad Pasada</font>
				</label><br>
				<label class="tiempo_espera_segundosForm">
					<font style="font-size: 13px;">Por favor, complete el siguiente formulario para configuracón matriz de rentabilidad pasada.</font><br><br>
				</label>
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>
