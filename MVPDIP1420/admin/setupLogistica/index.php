<?php
	@session_start(); 
	if($_GET['refresh']==1){
		setcookie("subPage", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("periodoInicial", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("periodoFinal", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("searchOpcionesSIC", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("PHPSESSID", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("ch201AB", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("searchTableSIC", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("searchTableLN", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("searchOpcionesLN", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("paguinaId", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("paguinaId_1", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("paguinaId_2", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("paguinaId_3", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("paguinaId_4", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("AB32BA51", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("qr", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		//setcookie("Paguinasub", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		die;
	}

	include '../functions/security.php'; 
	include '../functions/usuario_permisos.php';
	
	include '../functions/elecciones.php';
	include 'functions/elecciones.php';

	include 'functions/secciones_ine.php';

	$modulosPermiso = modulosPermiso('sistema_unico_beneficiarios','',$_COOKIE["id_usuario"]);
	if($modulosPermiso['tipos_casillas'] || $modulosPermiso['all'] ){
		$tipos_casillas = true;
	}
	$elecciones = elecciones();

	if($_COOKIE["id_usuario"]==1){
	//if($modulosPermiso['distritos_locales'] || $modulosPermiso['all'] ){
		$distritos_locales = true;
	//if($modulosPermiso['distritos_federales'] || $modulosPermiso['all'] ){
		$distritos_federales = true;
	//if($modulosPermiso['secciones_ine'] || $modulosPermiso['all'] ){
		$secciones_ine = true;
		$cuarteles = true;
		$candidato1 = true;
		$api_mailing1 = true;
		$api_sms1 = true;
		$api_whatsapp1 = true;
		$whatsapp_python1 = true;
	}
	if($modulosPermiso['partidos_2016'] || $modulosPermiso['all'] ){
		$partidos_2016 = true;
	}

	if($modulosPermiso['casillas_votos_2016'] || $modulosPermiso['all'] ){
		$casillas_votos_2016 = true;
	}

	if($modulosPermiso['cartografias_municipios_2016'] || $modulosPermiso['all'] ){
		$cartografias_municipios_2016 = true;
	}
	if($modulosPermiso['cartografias_distritos_locales_2016'] || $modulosPermiso['all'] ){
		$cartografias_distritos_locales_2016 = true;
	}

	if($modulosPermiso['cartografias_distritos_federales_2016'] || $modulosPermiso['all'] ){
		$cartografias_distritos_federales_2016 = true;
	}

	if($modulosPermiso['cartografias_gobernador_2016'] || $modulosPermiso['all'] ){
		$cartografias_gobernador_2016 = true;
	}

	if($modulosPermiso['cartografias_senador_2016'] || $modulosPermiso['all'] ){
		$cartografias_senador_2016 = true;
	}

	if($modulosPermiso['casillas_votos_2018'] || $modulosPermiso['all'] ){
		$casillas_votos_2018 = true;
	}
	if($modulosPermiso['partidos_2018'] || $modulosPermiso['all'] ){
		$partidos_2018 = true;
	}

	if($modulosPermiso['casillas_votos_2018'] || $modulosPermiso['all'] ){
		$casillas_votos_2018 = true;
	}

	if($modulosPermiso['cartografias_municipios_2018'] || $modulosPermiso['all'] ){
		$cartografias_municipios_2018 = true;
	}
	if($modulosPermiso['cartografias_distritos_locales_2018'] || $modulosPermiso['all'] ){
		$cartografias_distritos_locales_2018 = true;
	}

	if($modulosPermiso['cartografias_distritos_federales_2018'] || $modulosPermiso['all'] ){
		$cartografias_distritos_federales_2018 = true;
	}

	if($modulosPermiso['cartografias_gobernador_2018'] || $modulosPermiso['all'] ){
		$cartografias_gobernador_2018 = true;
	}

	if($modulosPermiso['cartografias_senador_2018'] || $modulosPermiso['all'] ){
		$cartografias_senador_2018 = true;
	}

	if($modulosPermiso['partidos_2021'] || $modulosPermiso['all'] ){
		$partidos_2021 = true;
	}

	if($modulosPermiso['casillas_votos_2021'] || $modulosPermiso['all'] ){
		$casillas_votos_2021 = true;
	}

	if($modulosPermiso['cartografias_municipios_2021'] || $modulosPermiso['all'] ){
		$cartografias_municipios_2021 = true;
	}

	if($modulosPermiso['cartografias_distritos_locales_2021'] || $modulosPermiso['all'] ){
		$cartografias_distritos_locales_2021 = true;
	}

	if($modulosPermiso['cartografias_distritos_federales_2021'] || $modulosPermiso['all'] ){
		$cartografias_distritos_federales_2021 = true;
	}

	if($modulosPermiso['cartografias_gobernador_2021'] || $modulosPermiso['all'] ){
		$cartografias_gobernador_2021 = true;
	}

	if($modulosPermiso['cartografias_senador_2021'] || $modulosPermiso['all'] ){
		$cartografias_senador_2021 = true;
	}

	if($modulosPermiso['partidos_2024'] || $modulosPermiso['all'] ){
		$partidos_2024 = true;
	}

	if($modulosPermiso['casillas_votos_2024'] || $modulosPermiso['all'] ){
		$casillas_votos_2024 = true;
	}

	if($modulosPermiso['cartografias_municipios_2024'] || $modulosPermiso['all'] ){
		$cartografias_municipios_2024 = true;
	}

	if($modulosPermiso['cartografias_distritos_locales_2024'] || $modulosPermiso['all'] ){
		$cartografias_distritos_locales_2024 = true;
	}

	if($modulosPermiso['cartografias_distritos_federales_2024'] || $modulosPermiso['all'] ){
		$cartografias_distritos_federales_2024 = true;
	}

	if($modulosPermiso['cartografias_gobernador_2024'] || $modulosPermiso['all'] ){
		$cartografias_gobernador_2024 = true;
	}

	if($modulosPermiso['cartografias_senador_2024'] || $modulosPermiso['all'] ){
		$cartografias_senador_2024 = true;
	}

	if($modulosPermiso['tipos_ciudadanos'] || $modulosPermiso['all'] ){
		$tipos_ciudadanos = true;
	}

	if($modulosPermiso['secciones_ine_ciudadanos'] || $modulosPermiso['all'] ){
		$secciones_ine_ciudadanos = true;
	}

	if($modulosPermiso['secciones_ine_ciudadanos_usuarios'] || $modulosPermiso['all'] ){
		$secciones_ine_ciudadanos_usuarios = true;
	}

	if($modulosPermiso['secciones_ine_ciudadanos_encuestas'] || $modulosPermiso['all'] ){
		$secciones_ine_ciudadanos_encuestas = true;
	}

	if($modulosPermiso['secciones_ine_ciudadanos_categorias'] || $modulosPermiso['all'] ){
		$secciones_ine_ciudadanos_categorias = true;
	}

	if($modulosPermiso['tipos_categorias_ciudadanos'] || $modulosPermiso['all'] ){
		$tipos_categorias_ciudadanos = true;
	}

	if($modulosPermiso['militantes_partidos'] || $modulosPermiso['all'] ){
		$militantes_partidos = true;
	}

	if($modulosPermiso['secciones_ine_ciudadanos_seguimientos'] || $modulosPermiso['all'] ){
		$secciones_ine_ciudadanos_seguimientos = true;
	}

	if($modulosPermiso['secciones_ine_actividades'] || $modulosPermiso['all'] ){
		$secciones_ine_actividades = true;
	}

	if($modulosPermiso['swich_operaciones'] || $modulosPermiso['all'] ){
		$swich_operaciones = true;
	}

	if($modulosPermiso['encuestas'] || $modulosPermiso['all'] ){
		$encuestas = true;
	}

	if($modulosPermiso['correos_mailing'] || $modulosPermiso['all'] ){
		$correos_mailing1 = true;
	}

	if($modulosPermiso['campañas_mailing'] || $modulosPermiso['all'] ){
		$campanas_mailing1 = true;
	}

	if($modulosPermiso['secciones_ine_ciudadanos_campañas_mailing'] || $modulosPermiso['all'] ){
		$secciones_ine_ciudadanos_campanas_mailing1 = true;
	}

	if($modulosPermiso['campañas_sms'] || $modulosPermiso['all'] ){
		$campanas_sms1 = true;
	}

	if($modulosPermiso['secciones_ine_ciudadanos_campañas_sms'] || $modulosPermiso['all'] ){
		$secciones_ine_ciudadanos_campanas_sms1 = true;
	}

	if($modulosPermiso['campañas_whatsapp'] || $modulosPermiso['all'] ){
		$campanas_whatsapp1 = true;
	}

	if($modulosPermiso['secciones_ine_ciudadanos_campañas_whatsapp'] || $modulosPermiso['all'] ){
		$secciones_ine_ciudadanos_campanas_whatsapp1 = true;
	}

	if($modulosPermiso['lista_nominal'] || $modulosPermiso['all'] ){
		$lista_nominal = true;
	}

	if($modulosPermiso['tipos_territorios'] || $modulosPermiso['all'] ){
		$tipos_territorios = true;
	}

	if($modulosPermiso['categorias_programas_apoyos'] || $modulosPermiso['all'] ){
		$categorias_programas_apoyos = true;
	}

	if($modulosPermiso['programas_apoyos'] || $modulosPermiso['all'] ){
		$programas_apoyos = true;
	}

	if($modulosPermiso['dependencias'] || $modulosPermiso['all'] ){
		$dependencias = true;
	}
	if($modulosPermiso['partidos_legados'] || $modulosPermiso['all'] ){
		$partidos_legados = true;
	}
	if($modulosPermiso['secciones_ine_grupos'] || $modulosPermiso['all'] ){
		$secciones_ine_grupos = true;
	}
	if($modulosPermiso['configuracion_matriz_rentabilidad_electoral_2016'] || $modulosPermiso['all'] ){
		$configuracion_matriz_rentabilidad_electoral_2016 = true;
	}
	if($modulosPermiso['configuracion_matriz_rentabilidad_electoral_2018'] || $modulosPermiso['all'] ){
		$configuracion_matriz_rentabilidad_electoral_2018 = true;
	}
	if($modulosPermiso['configuracion_matriz_rentabilidad_electoral_2021'] || $modulosPermiso['all'] ){
		$configuracion_matriz_rentabilidad_electoral_2021 = true;
	}
	if($modulosPermiso['configuracion_matriz_rentabilidad_electoral_2024'] || $modulosPermiso['all'] ){
		$configuracion_matriz_rentabilidad_electoral_2024 = true;
	}

	if($modulosPermiso['matriz_rentabilidad_electoral_2016'] || $modulosPermiso['all'] ){
		$matriz_rentabilidad_electoral_2016 = true;
	}
	if($modulosPermiso['matriz_rentabilidad_electoral_2018'] || $modulosPermiso['all'] ){
		$matriz_rentabilidad_electoral_2018 = true;
	}
	if($modulosPermiso['matriz_rentabilidad_electoral_2021'] || $modulosPermiso['all'] ){
		$matriz_rentabilidad_electoral_2021 = true;
	}
	if($modulosPermiso['matriz_rentabilidad_electoral_2024'] || $modulosPermiso['all'] ){
		$matriz_rentabilidad_electoral_2024 = true;
	}
	if($modulosPermiso['preguntas_2022_revocacion_mandato'] || $modulosPermiso['all'] ){
		$preguntas_2022_revocacion_mandato = true;
	}
	if($modulosPermiso['casillas_votos_2022_revocacion_mandato'] || $modulosPermiso['all'] ){
		$casillas_votos_2022_revocacion_mandato = true;
	}

	if($modulosPermiso['cartografias_municipios_2022_revocacion_mandato'] || $modulosPermiso['all'] ){
		$cartografias_municipios_2022_revocacion_mandato = true;
	}

	if($modulosPermiso['empresas_adjudicadas'] || $modulosPermiso['all'] ){
		$empresas_adjudicadas = true;
	}

	if($modulosPermiso['supervisores'] || $modulosPermiso['all'] ){
		$supervisores = true;
	}

	

	if($modulosPermiso['secciones_ine_ciudadanos_programas_apoyos'] || $modulosPermiso['all'] ){
		$secciones_ine_ciudadanos_programas_apoyos = true;
	}

	if($modulosPermiso['secciones_ine_agendas_gobierno'] || $modulosPermiso['all'] ){
		$secciones_ine_agendas_gobierno = true;
	}

	if($modulosPermiso['secciones_ine_ciudadanos_giras'] || $modulosPermiso['all'] ){
		$secciones_ine_ciudadanos_giras = true;
	}
	if($modulosPermiso['secciones_ine_ciudadanos_avance_semaforo'] || $modulosPermiso['all'] ){
		$secciones_ine_ciudadanos_avance_semaforo = true;
	}
	if($modulosPermiso['secciones_ine_ciudadanos_secciones_avance_semaforo'] || $modulosPermiso['all'] ){
		$secciones_ine_ciudadanos_secciones_avance_semaforo = true;
	}
	if($modulosPermiso['secciones_ine_ciudadanos_estructuras'] || $modulosPermiso['all'] ){
		$secciones_ine_ciudadanos_estructuras = true;
	}
	if($modulosPermiso['secciones_ine_ciudadanos_validores'] || $modulosPermiso['all'] ){
		$secciones_ine_ciudadanos_validores = true;
	}
	if($modulosPermiso['qr_scanner_ciudadano'] || $modulosPermiso['all'] ){
		$qr_scanner_ciudadano = true;
	}
	if($modulosPermiso['qr_scanner_militante'] || $modulosPermiso['all'] ){
		$qr_scanner_militante1 = true;
	}

	if($modulosPermiso['nomina'] || $modulosPermiso['all'] ){
		$nomina = true;
	}

	if($modulosPermiso['ejes_gobierno'] || $modulosPermiso['all'] ){
		$ejes_gobierno = true;
	}

	if($modulosPermiso['tipo_gira'] || $modulosPermiso['all'] ){
		$tipos_giras = true;
	}

?>
	<title>Perfiles Personas</title>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#modulo_tipos_casillas").click(function(event) { 
				document.getElementById("modulo_tipos_casillas").style.pointerEvents = "none";
				urlink="tiposCasillas/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_tipos_casillas").style.pointerEvents = "auto";
			});
			$("#modulo_distritos_locales").click(function(event) { 
				document.getElementById("modulo_distritos_locales").style.pointerEvents = "none";
				urlink="distritosLocales/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_distritos_locales").style.pointerEvents = "auto";
			});

			$("#modulo_distritos_federales").click(function(event) { 
				document.getElementById("modulo_distritos_federales").style.pointerEvents = "none";
				urlink="distritosFederales/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_distritos_federales").style.pointerEvents = "auto";
			});

			$("#modulo_secciones_ine").click(function(event) { 
				document.getElementById("modulo_secciones_ine").style.pointerEvents = "none";
				urlink="seccionesIne/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_secciones_ine").style.pointerEvents = "auto";
			});

			$("#modulo_cuarteles").click(function(event) { 
				document.getElementById("modulo_cuarteles").style.pointerEvents = "none";
				urlink="cuarteles/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_cuarteles").style.pointerEvents = "auto";
			});

			$("#modulo_secciones_ine_ciudadanos").click(function(event) { 
				document.getElementById("modulo_secciones_ine_ciudadanos").style.pointerEvents = "none";
				urlink="seccionesIneCiudadanos/index.php";
				dataString = 'urlink='+urlink;
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_secciones_ine_ciudadanos").style.pointerEvents = "auto";
				
			});

			$("#modulo_secciones_ine_ciudadanos_estructuras").click(function(event) { 
				document.getElementById("modulo_secciones_ine_ciudadanos_estructuras").style.pointerEvents = "none";
				urlink="seccionesIneCiudadanosEstructuras/index.php";
				dataString = 'urlink='+urlink;
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_secciones_ine_ciudadanos_estructuras").style.pointerEvents = "auto";
				
			});

			$("#modulo_secciones_ine_ciudadanos_estructuras_validador").click(function(event) { 
				document.getElementById("modulo_secciones_ine_ciudadanos_estructuras_validador").style.pointerEvents = "none";
				urlink="seccionesIneCiudadanosEstructurasValidador/index.php";
				dataString = 'urlink='+urlink;
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_secciones_ine_ciudadanos_estructuras_validador").style.pointerEvents = "auto";
				
			});

			$("#modulo_secciones_ine_ciudadanos_usuarios").click(function(event) { 
				document.getElementById("modulo_secciones_ine_ciudadanos_usuarios").style.pointerEvents = "none";
				urlink="seccionesIneCiudadanosUsuarios/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_secciones_ine_ciudadanos_usuarios").style.pointerEvents = "auto";
			});

			$("#modulo_partidos_2018").click(function(event) { 
				document.getElementById("modulo_partidos_2018").style.pointerEvents = "none";
				urlink="partidos2018/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_partidos_2018").style.pointerEvents = "auto";
			});

			$("#modulo_casillas_votos_2016").click(function(event) { 
				document.getElementById("modulo_casillas_votos_2018").style.pointerEvents = "none";
				urlink="casillasVotos2018/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_casillas_votos_2018").style.pointerEvents = "auto";
			});

			$("#modulo_gobernador_reportes_2018").click(function(event) { 
				document.getElementById("modulo_gobernador_reportes_2018").style.pointerEvents = "none";
				<?php
					if($forzar_gobernador=='true'){
						echo 'urlink="seccionesIneReportes2018/gobernador/index.php";';
					}elseif($tipo_uso_plataforma=='all'){
						echo 'urlink="gobernadorReportes2018/index.php";';
					}
				?>
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				//location.reload();
				//$("#homebody").load(urlink);
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_gobernador_reportes_2018").style.pointerEvents = "auto";
			});
			$("#modulo_senador_reportes_2018").click(function(event) { 
				document.getElementById("modulo_senador_reportes_2018").style.pointerEvents = "none";
				<?php
					if($forzar_senador=='true'){
						echo 'urlink="seccionesIneReportes2018/senador/index.php";';
					}elseif($tipo_uso_plataforma=='all'){
						echo 'urlink="senadorReportes2018/index.php";';
					}
				?>
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				//location.reload();
				//$("#homebody").load(urlink);
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_gobernador_reportes_2018").style.pointerEvents = "auto";
			});

			$("#modulo_municipios_reportes_2018").click(function(event) { 
				document.getElementById("modulo_municipios_reportes_2018").style.pointerEvents = "none";
				<?php
					if($tipo_uso_plataforma=='municipio'){
						echo 'urlink="seccionesIneReportes2018/municipio/index.php";';
					}elseif($tipo_uso_plataforma=='all'){
						echo 'urlink="municipiosReportes2018/index.php";';
					}
				?>
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				//location.reload();
				//$("#homebody").load(urlink);
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_municipios_reportes_2018").style.pointerEvents = "auto";
			});

			$("#modulo_distritos_locales_reportes_2018").click(function(event) { 
				document.getElementById("modulo_distritos_locales_reportes_2018").style.pointerEvents = "none";
				<?php
					if($tipo_uso_plataforma=='distrito_local'){
						echo 'urlink="seccionesIneReportes2018/distrito_local/index.php";';
					}elseif($forzar_distritos_locales=='true'){
						echo 'urlink="seccionesIneReportes2018/forzar_distrito_local/index.php";';
					}elseif($tipo_uso_plataforma=='all'){
						echo 'urlink="distritosLocalesReportes2018/index.php";';
					}
				?>
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				//location.reload();
				//$("#homebody").load(urlink);
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_distritos_locales_reportes_2018").style.pointerEvents = "auto";
			});

			$("#modulo_distritos_federales_reportes_2018").click(function(event) {
				document.getElementById("modulo_distritos_federales_reportes_2018").style.pointerEvents = "none";
				<?php
					if($tipo_uso_plataforma=='distrito_federal'){
						echo 'urlink="seccionesIneReportes2018/distrito_federal/index.php";';
					}elseif($forzar_distritos_federales=='true'){
						echo 'urlink="seccionesIneReportes2018/forzar_distrito_federal/index.php";';
					}elseif($tipo_uso_plataforma=='all'){
						echo 'urlink="distritosFederalesReportes2018/index.php";';
					}
				?>
				//urlink="seccionesIneReportes2018/distrito_federal/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				//location.reload();
				$("#homebody").load(urlink);
				document.getElementById("modulo_distritos_federales_reportes_2018").style.pointerEvents = "auto";
			});



			$("#modulo_secciones_ine_actividades").click(function(event) {
				document.getElementById("modulo_secciones_ine_actividades").style.pointerEvents = "none";
				urlink="seccionesIneActividades/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				//$("#homebody").load(urlink);
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_secciones_ine_actividades").style.pointerEvents = "auto";
			});

			$("#modulo_secciones_ine_agendas_gobierno").click(function(event) {
				document.getElementById("modulo_secciones_ine_agendas_gobierno").style.pointerEvents = "none";
				urlink="seccionesIneAgendasGobierno/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				//$("#homebody").load(urlink);
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_secciones_ine_giras").style.pointerEvents = "auto";
			});

			$("#modulo_partidos_2021").click(function(event) { 
				document.getElementById("modulo_partidos_2021").style.pointerEvents = "none";
				urlink="partidos2021/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_partidos_2021").style.pointerEvents = "auto";
			});

			$("#modulo_casillas_votos_2021").click(function(event) { 
				document.getElementById("modulo_casillas_votos_2021").style.pointerEvents = "none";
				urlink="casillasVotos2021/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_casillas_votos_2021").style.pointerEvents = "auto";
			});

			$("#modulo_tipos_ciudadanos").click(function(event) { 
				document.getElementById("modulo_tipos_ciudadanos").style.pointerEvents = "none";
				urlink="tiposCiudadanos/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_tipos_ciudadanos").style.pointerEvents = "auto";
			});

			$("#modulo_tipos_categorias_ciudadanos").click(function(event) { 
				document.getElementById("modulo_tipos_categorias_ciudadanos").style.pointerEvents = "none";
				urlink="tiposCategoriasCiudadanos/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_tipos_categorias_ciudadanos").style.pointerEvents = "auto";
			});

			$("#modulo_gobernador_reportes_2021").click(function(event) { 
				document.getElementById("modulo_gobernador_reportes_2021").style.pointerEvents = "none";
				<?php
					if($forzar_gobernador=='true'){
						echo 'urlink="seccionesIneReportes2021/gobernador/index.php";';
					}elseif($tipo_uso_plataforma=='all'){
						echo 'urlink="gobernadorReportes2021/index.php";';
					}
				?>
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				//location.reload();
				//$("#homebody").load(urlink);
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_gobernador_reportes_2021").style.pointerEvents = "auto";
			});
			$("#modulo_senador_reportes_2021").click(function(event) { 
				document.getElementById("modulo_senador_reportes_2021").style.pointerEvents = "none";
				<?php
					if($forzar_senador=='true'){
						echo 'urlink="seccionesIneReportes2021/senador/index.php";';
					}elseif($tipo_uso_plataforma=='all'){
						echo 'urlink="senadorReportes2021/index.php";';
					}
				?>
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				//location.reload();
				//$("#homebody").load(urlink);
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_gobernador_reportes_2021").style.pointerEvents = "auto";
			});

			$("#modulo_municipios_reportes_2021").click(function(event) { 
				document.getElementById("modulo_municipios_reportes_2021").style.pointerEvents = "none";
				<?php
					if($tipo_uso_plataforma=='municipio'){
						echo 'urlink="seccionesIneReportes2021/municipio/index.php";';
					}elseif($tipo_uso_plataforma=='all'){
						echo 'urlink="municipiosReportes2021/index.php";';
					}
				?>
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				//location.reload();
				//$("#homebody").load(urlink);
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_municipios_reportes_2021").style.pointerEvents = "auto";
			});

			$("#modulo_distritos_locales_reportes_2021").click(function(event) { 
				document.getElementById("modulo_distritos_locales_reportes_2021").style.pointerEvents = "none";
				<?php
					if($tipo_uso_plataforma=='distrito_local'){
						echo 'urlink="seccionesIneReportes2021/distrito_local/index.php";';
					}elseif($forzar_distritos_locales=='true'){
						echo 'urlink="seccionesIneReportes2021/forzar_distrito_local/index.php";';
					}elseif($tipo_uso_plataforma=='all'){
						echo 'urlink="distritosLocalesReportes2021/index.php";';
					}
				?>
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				//location.reload();
				//$("#homebody").load(urlink);
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_distritos_locales_reportes_2021").style.pointerEvents = "auto";
			});

			$("#modulo_distritos_federales_reportes_2021").click(function(event) {
				document.getElementById("modulo_distritos_federales_reportes_2021").style.pointerEvents = "none";
				<?php
					if($tipo_uso_plataforma=='distrito_federal'){
						echo 'urlink="seccionesIneReportes2021/distrito_federal/index.php";';
					}elseif($forzar_distritos_federales=='true'){
						echo 'urlink="seccionesIneReportes2021/forzar_distrito_federal/index.php";';
					}elseif($tipo_uso_plataforma=='all'){
						echo 'urlink="distritosFederalesReportes2021/index.php";';
					}
				?>
				//urlink="seccionesIneReportes2021/distrito_federal/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				//location.reload();
				//$("#homebody").load(urlink);
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_distritos_federales_reportes_2021").style.pointerEvents = "auto";
			});

			$("#modulo_switch_operaciones").click(function(event) { 
				document.getElementById("modulo_switch_operaciones").style.pointerEvents = "none";
				urlink="switchOperaciones/index.php";
				dataString = 'urlink='+urlink;
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) {
						if(data=="SI"){ 
							location.reload();
						}
					}
				});
				$("#homebody").load(urlink);
				document.getElementById("modulo_switch_operaciones").style.pointerEvents = "auto";
			});

			$("#modulo_encuestas").click(function(event) { 
				document.getElementById("modulo_encuestas").style.pointerEvents = "none";
				urlink="encuestas/index.php";
				dataString = 'urlink='+urlink;
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) {
						if(data=="SI"){ 
							location.reload();
						}
					}
				});
				$("#homebody").load(urlink);
				document.getElementById("modulo_encuestas").style.pointerEvents = "auto";
			});

			$("#modulo_correos_mailing").click(function(event) { 
				document.getElementById("modulo_correos_mailing").style.pointerEvents = "none";
				urlink="correosMailing/index.php";
				dataString = 'urlink='+urlink;
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) {
						if(data=="SI"){ 
							location.reload();
						}
					}
				});
				$("#homebody").load(urlink);
				document.getElementById("modulo_correos_mailing").style.pointerEvents = "auto";
			});


			$('#modulo_campanas_mailing').click(function(event) {
				document.getElementById("modulo_campanas_mailing").style.pointerEvents = "none";
				urlink='campanasMailing/index.php';
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: 'POST',
					url: 'functions/backarray.php',
					data: dataString,
					success: function(data) {}
				});
				$('#homebody').load(urlink);
				document.getElementById("modulo_campanas_mailing").style.pointerEvents = "auto"; 
			});

			$('#modulo_secciones_ine_ciudadanos_campanas_mailing').click(function(event) {
				document.getElementById("modulo_secciones_ine_ciudadanos_campanas_mailing").style.pointerEvents = "none";
				urlink='seccionesIneCiudadanosCampanasMailingProgramadas/index.php';
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: 'POST',
					url: 'functions/backarray.php',
					data: dataString,
					success: function(data) {}
				});
				$('#homebody').load(urlink);
				document.getElementById("modulo_secciones_ine_ciudadanos_campanas_mailing").style.pointerEvents = "auto";
			});


			$('#modulo_candidato').click(function(event) { 
				document.getElementById("modulo_candidato").style.pointerEvents = "none";
				urlink='candidato/index.php';
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: 'POST',
					url: 'functions/backarray.php',
					data: dataString,
					success: function(data) {}
				});
				$('#homebody').load(urlink);
				document.getElementById("modulo_candidato").style.pointerEvents = "auto";
			});

			$('#modulo_api_mailing').click(function(event) { 
				document.getElementById("modulo_api_mailing").style.pointerEvents = "none";
				urlink='apiMailing/index.php';
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: 'POST',
					url: 'functions/backarray.php',
					data: dataString,
					success: function(data) {}
				});
				$('#homebody').load(urlink);
				document.getElementById("modulo_api_mailing").style.pointerEvents = "auto";
			});
			$('#modulo_api_sms').click(function(event) { 
				document.getElementById("modulo_api_sms").style.pointerEvents = "none";
				urlink='apiSMS/index.php';
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: 'POST',
					url: 'functions/backarray.php',
					data: dataString,
					success: function(data) {}
				});
				$('#homebody').load(urlink);
				document.getElementById("modulo_api_sms").style.pointerEvents = "auto";
			});

			$('#modulo_api_sms_status').click(function(event) { 
				document.getElementById("modulo_api_sms_status").style.pointerEvents = "none";
				urlink='apiSMSStatus/index.php';
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: 'POST',
					url: 'functions/backarray.php',
					data: dataString,
					success: function(data) {}
				});
				$('#homebody').load(urlink);
				document.getElementById("modulo_api_sms_status").style.pointerEvents = "auto";
			});

			$('#modulo_campanas_sms').click(function(event) { 
				document.getElementById("modulo_campanas_sms").style.pointerEvents = "none";
				urlink='campanasSMS/index.php';
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: 'POST',
					url: 'functions/backarray.php',
					data: dataString,
					success: function(data) {}
				});
				$('#homebody').load(urlink);
				document.getElementById("modulo_campanas_sms").style.pointerEvents = "auto";
			});

			$('#modulo_secciones_ine_ciudadanos_campanas_sms').click(function(event) { 
				document.getElementById("modulo_secciones_ine_ciudadanos_campanas_sms").style.pointerEvents = "none";
				urlink='seccionesIneCiudadanosCampanasSMSProgramadas/index.php';
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: 'POST',
					url: 'functions/backarray.php',
					data: dataString,
					success: function(data) {}
				});
				$('#homebody').load(urlink);
				document.getElementById("modulo_secciones_ine_ciudadanos_campanas_sms").style.pointerEvents = "auto";
			});
			$('#modulo_whatsapp_python').click(function(event) { 
				document.getElementById("modulo_whatsapp_python").style.pointerEvents = "none";
				urlink='whatsappPython/index.php';
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: 'POST',
					url: 'functions/backarray.php',
					data: dataString,
					success: function(data) {}
				});
				$('#homebody').load(urlink);
				document.getElementById("modulo_whatsapp_python").style.pointerEvents = "auto";
			});
			$('#modulo_api_whatsapp').click(function(event) { 
				document.getElementById("modulo_api_whatsapp").style.pointerEvents = "none";
				urlink='apiWhatsapp/index.php';
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: 'POST',
					url: 'functions/backarray.php',
					data: dataString,
					success: function(data) {}
				});
				$('#homebody').load(urlink);
				document.getElementById("modulo_api_whatsapp").style.pointerEvents = "auto";
			});

			$('#modulo_api_whatsapp_status').click(function(event) { 
				document.getElementById("modulo_api_whatsapp_status").style.pointerEvents = "none";
				urlink='apiWhatsappStatus/index.php';
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: 'POST',
					url: 'functions/backarray.php',
					data: dataString,
					success: function(data) {}
				});
				$('#homebody').load(urlink);
				document.getElementById("modulo_api_whatsapp_status").style.pointerEvents = "auto";
			});

			$('#modulo_campanas_whatsapp').click(function(event) { 
				document.getElementById("modulo_campanas_whatsapp").style.pointerEvents = "none";
				urlink='campanasWhatsapp/index.php';
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: 'POST',
					url: 'functions/backarray.php',
					data: dataString,
					success: function(data) {}
				});
				$('#homebody').load(urlink);
				document.getElementById("modulo_campanas_whatsapp").style.pointerEvents = "auto";
			});

			$('#modulo_secciones_ine_ciudadanos_campanas_whatsapp').click(function(event) { 
				document.getElementById("modulo_secciones_ine_ciudadanos_campanas_whatsapp").style.pointerEvents = "none";
				urlink='seccionesIneCiudadanosCampanasWhatsappProgramadas/index.php';
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: 'POST',
					url: 'functions/backarray.php',
					data: dataString,
					success: function(data) {}
				});
				$('#homebody').load(urlink);
				document.getElementById("modulo_secciones_ine_ciudadanos_campanas_whatsapp").style.pointerEvents = "auto";
			});

			$('#modulo_api_whatsapp_mensajes').click(function(event) { 
				document.getElementById("modulo_api_whatsapp_mensajes").style.pointerEvents = "none";
				urlink='apiWhatsappMensajes/index.php';
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: 'POST',
					url: 'functions/backarray.php',
					data: dataString,
					success: function(data) {}
				});
				$('#homebody').load(urlink);
				document.getElementById("modulo_api_whatsapp_mensajes").style.pointerEvents = "auto";
			});

			$('#modulo_lista_nominal').click(function(event) { 
				document.getElementById("modulo_lista_nominal").style.pointerEvents = "none";
				urlink='listaNominal/index.php';
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: 'POST',
					url: 'functions/backarray.php',
					data: dataString,
					success: function(data) {}
				});
				$('#homebody').load(urlink);
				document.getElementById("modulo_lista_nominal").style.pointerEvents = "auto";
			});

			$("#modulo_tipos_territorios").click(function(event) { 
				document.getElementById("modulo_tipos_territorios").style.pointerEvents = "none";
				urlink="tiposTerritorios/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_tipos_territorios").style.pointerEvents = "auto";
			});

			$("#modulo_categorias_programas_apoyos").click(function(event) { 
				document.getElementById("modulo_categorias_programas_apoyos").style.pointerEvents = "none";
				urlink="categoriasProgramasApoyos/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_categorias_programas_apoyos").style.pointerEvents = "auto";
			});

			$("#modulo_programas_apoyos").click(function(event) { 
				document.getElementById("modulo_programas_apoyos").style.pointerEvents = "none";
				urlink="programasApoyos/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_programas_apoyos").style.pointerEvents = "auto";
			});

			$("#modulo_dependencias").click(function(event) { 
				document.getElementById("modulo_dependencias").style.pointerEvents = "none";
				urlink="dependencias/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_dependencias").style.pointerEvents = "auto";
			});
			$("#modulo_partidos_legados").click(function(event) { 
				document.getElementById("modulo_partidos_legados").style.pointerEvents = "none";
				urlink="partidosLegados/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_partidos_legados").style.pointerEvents = "auto";
			});

			$("#modulo_secciones_ine_grupos").click(function(event) { 
				document.getElementById("modulo_secciones_ine_grupos").style.pointerEvents = "none";
				urlink="seccionesIneGrupos/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_secciones_ine_grupos").style.pointerEvents = "auto";
			});

			$("#modulo_configuracion_matriz_rentabilidad_electoral_2016").click(function(event) { 
				document.getElementById("modulo_configuracion_matriz_rentabilidad_electoral_2016").style.pointerEvents = "none";
				urlink="configuracionMatrizRentabilidadSeccionesIne2016/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_configuracion_matriz_rentabilidad_electoral_2016").style.pointerEvents = "auto";
			});
			$("#modulo_configuracion_matriz_rentabilidad_electoral_2018").click(function(event) { 
				document.getElementById("modulo_configuracion_matriz_rentabilidad_electoral_2018").style.pointerEvents = "none";
				urlink="configuracionMatrizRentabilidadSeccionesIne2018/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_configuracion_matriz_rentabilidad_electoral_2018").style.pointerEvents = "auto";
			});
			$("#modulo_configuracion_matriz_rentabilidad_electoral_2021").click(function(event) {
				document.getElementById("modulo_configuracion_matriz_rentabilidad_electoral_2021").style.pointerEvents = "none";
				urlink="configuracionMatrizRentabilidadSeccionesIne2021/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_configuracion_matriz_rentabilidad_electoral_2021").style.pointerEvents = "auto";
			});

			$("#modulo_preguntas_2022_revocacion_mandato").click(function(event) { 
				document.getElementById("modulo_preguntas_2022_revocacion_mandato").style.pointerEvents = "none";
				urlink="preguntas2022RevocacionMandato/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_preguntas_2022_revocacion_mandato").style.pointerEvents = "auto";
			});


			$("#modulo_casillas_votos_2022_revocacion_mandato").click(function(event) { 
				document.getElementById("modulo_casillas_votos_2022_revocacion_mandato").style.pointerEvents = "none";
				urlink="casillasVotos2022RevocacionMandato/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_casillas_votos_2022_revocacion_mandato").style.pointerEvents = "auto";
			});


			$("#modulo_cartografias_municipios_2022_revocacion_mandato").click(function(event) { 
				document.getElementById("modulo_cartografias_municipios_2022_revocacion_mandato").style.pointerEvents = "none";
				<?php
					if($tipo_uso_plataforma=='municipio'){
						echo 'urlink="seccionesIneReportes2022RevocacionMandato/municipio/index.php";';
					}elseif($tipo_uso_plataforma=='distrito_local'){
						echo 'urlink="seccionesIneReportes2022RevocacionMandato/distrito_local/index.php";';
					}elseif($tipo_uso_plataforma=='distrito_federal'){
						echo 'urlink="seccionesIneReportes2022RevocacionMandato/distrito_federal/index.php";';
					}else{
						echo 'urlink="seccionesIneReportes2022RevocacionMandato/municipio/index.php";';
					}
				?>
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				//location.reload();
				$("#homebody").load(urlink);
				document.getElementById("modulo_cartografias_municipios_2022_revocacion_mandato").style.pointerEvents = "auto";
			});

			$("#modulo_empresas_adjudicadas").click(function(event) { 
				document.getElementById("modulo_empresas_adjudicadas").style.pointerEvents = "none";
				urlink="empresasAdjudicadas/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_empresas_adjudicadas").style.pointerEvents = "auto";
			});

			$("#modulo_supervisores").click(function(event) { 
				document.getElementById("modulo_supervisores").style.pointerEvents = "none";
				urlink="supervisores/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_supervisores").style.pointerEvents = "auto";
			});

			$("#modulo_secciones_ine_ciudadanos_avance_semaforo").click(function(event) { 
				document.getElementById("modulo_secciones_ine_ciudadanos_avance_semaforo").style.pointerEvents = "none";
				urlink="seccionesIneCiudadanosAvanceSemaforo/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_secciones_ine_ciudadanos_avance_semaforo").style.pointerEvents = "auto";
			});

			$("#modulo_secciones_ine_ciudadanos_secciones_avance_semaforo").click(function(event) { 
				document.getElementById("modulo_secciones_ine_ciudadanos_secciones_avance_semaforo").style.pointerEvents = "none";
				urlink="seccionesIneCiudadanosSeccionesAvanceSemaforo/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_secciones_ine_ciudadanos_avance_semaforo").style.pointerEvents = "auto";
			});

			$("#modulo_partidos_2016").click(function(event) { 
				document.getElementById("modulo_partidos_2016").style.pointerEvents = "none";
				urlink="partidos2016/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_partidos_2016").style.pointerEvents = "auto";
			});

			$("#modulo_casillas_votos_2016").click(function(event) { 
				document.getElementById("modulo_casillas_votos_2016").style.pointerEvents = "none";
				urlink="casillasVotos2016/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_casillas_votos_2016").style.pointerEvents = "auto";
			});

			$("#modulo_gobernador_reportes_2016").click(function(event) { 
				document.getElementById("modulo_gobernador_reportes_2016").style.pointerEvents = "none";
				<?php
					if($forzar_gobernador=='true'){
						echo 'urlink="seccionesIneReportes2016/gobernador/index.php";';
					}elseif($tipo_uso_plataforma=='all'){
						echo 'urlink="gobernadorReportes2016/index.php";';
					}
				?>
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				//location.reload();
				//$("#homebody").load(urlink);
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_gobernador_reportes_2016").style.pointerEvents = "auto";
			});
			$("#modulo_senador_reportes_2016").click(function(event) { 
				document.getElementById("modulo_senador_reportes_2016").style.pointerEvents = "none";
				<?php
					if($forzar_senador=='true'){
						echo 'urlink="seccionesIneReportes2016/senador/index.php";';
					}elseif($tipo_uso_plataforma=='all'){
						echo 'urlink="senadorReportes2016/index.php";';
					}
				?>
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				//location.reload();
				//$("#homebody").load(urlink);
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_gobernador_reportes_2016").style.pointerEvents = "auto";
			});
			$("#modulo_municipios_reportes_2016").click(function(event) { 
				document.getElementById("modulo_municipios_reportes_2016").style.pointerEvents = "none";
				<?php
					if($tipo_uso_plataforma=='municipio'){
						echo 'urlink="seccionesIneReportes2016/municipio/index.php";';
					}elseif($tipo_uso_plataforma=='all'){
						echo 'urlink="municipiosReportes2016/index.php";';
					}
				?>
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				//location.reload();
				//$("#homebody").load(urlink);
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_municipios_reportes_2016").style.pointerEvents = "auto";
			});

			$("#modulo_distritos_locales_reportes_2016").click(function(event) { 
				document.getElementById("modulo_distritos_locales_reportes_2016").style.pointerEvents = "none";
				<?php
					if($tipo_uso_plataforma=='distrito_local'){
						echo 'urlink="seccionesIneReportes2016/distrito_local/index.php";';
					}elseif($forzar_distritos_locales=='true'){
						echo 'urlink="seccionesIneReportes2016/forzar_distrito_local/index.php";';
					}elseif($tipo_uso_plataforma=='all'){
						echo 'urlink="distritosLocalesReportes2016/index.php";';
					}
				?>
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				//location.reload();
				//$("#homebody").load(urlink);
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_distritos_locales_reportes_2016").style.pointerEvents = "auto";
			});

			$("#modulo_distritos_federales_reportes_2016").click(function(event) {
				document.getElementById("modulo_distritos_federales_reportes_2016").style.pointerEvents = "none";
				<?php
					if($tipo_uso_plataforma=='distrito_federal'){
						echo 'urlink="seccionesIneReportes2016/distrito_federal/index.php";';
					}elseif($forzar_distritos_federales=='true'){
						echo 'urlink="seccionesIneReportes2016/forzar_distrito_federal/index.php";';
					}elseif($tipo_uso_plataforma=='all'){
						echo 'urlink="distritosFederalesReportes2016/index.php";';
					}
				?>
				//urlink="seccionesIneReportes2016/distrito_federal/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				//location.reload();
				$("#homebody").load(urlink);
				document.getElementById("modulo_distritos_federales_reportes_2016").style.pointerEvents = "auto";
			});
			$("#modulo_partidos_2024").click(function(event) { 
				document.getElementById("modulo_partidos_2024").style.pointerEvents = "none";
				urlink="partidos2024/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_partidos_2024").style.pointerEvents = "auto";
			});

			$("#modulo_casillas_votos_2024").click(function(event) { 
				document.getElementById("modulo_casillas_votos_2024").style.pointerEvents = "none";
				urlink="casillasVotos2024/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_casillas_votos_2024").style.pointerEvents = "auto";
			});
			$("#modulo_gobernador_reportes_2024").click(function(event) { 
				document.getElementById("modulo_gobernador_reportes_2024").style.pointerEvents = "none";
				<?php
					if($forzar_gobernador=='true'){
						echo 'urlink="seccionesIneReportes2024/gobernador/index.php";';
					}elseif($tipo_uso_plataforma=='all'){
						echo 'urlink="gobernadorReportes2024/index.php";';
					}
				?>
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				//location.reload();
				//$("#homebody").load(urlink);
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_gobernador_reportes_2024").style.pointerEvents = "auto";
			});
			$("#modulo_senador_reportes_2024").click(function(event) { 
				document.getElementById("modulo_senador_reportes_2024").style.pointerEvents = "none";
				<?php
					if($forzar_senador=='true'){
						echo 'urlink="seccionesIneReportes2024/senador/index.php";';
					}elseif($tipo_uso_plataforma=='all'){
						echo 'urlink="senadorReportes2024/index.php";';
					}
				?>
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				//location.reload();
				//$("#homebody").load(urlink);
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_gobernador_reportes_2024").style.pointerEvents = "auto";
			});
			$("#modulo_municipios_reportes_2024").click(function(event) { 
				document.getElementById("modulo_municipios_reportes_2024").style.pointerEvents = "none";
				<?php
					if($tipo_uso_plataforma=='municipio'){
						echo 'urlink="seccionesIneReportes2024/municipio/index.php";';
					}elseif($tipo_uso_plataforma=='all'){
						echo 'urlink="municipiosReportes2024/index.php";';
					}
				?>
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				//location.reload();
				//$("#homebody").load(urlink);
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_municipios_reportes_2024").style.pointerEvents = "auto";
			});
			$("#modulo_distritos_locales_reportes_2024").click(function(event) { 
				document.getElementById("modulo_distritos_locales_reportes_2024").style.pointerEvents = "none";
				<?php
					if($tipo_uso_plataforma=='distrito_local'){
						echo 'urlink="seccionesIneReportes2024/distrito_local/index.php";';
					}elseif($forzar_distritos_locales=='true'){
						echo 'urlink="seccionesIneReportes2024/forzar_distrito_local/index.php";';
					}elseif($tipo_uso_plataforma=='all'){
						echo 'urlink="distritosLocalesReportes2024/index.php";';
					}
				?>
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				//location.reload();
				//$("#homebody").load(urlink);
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_distritos_locales_reportes_2024").style.pointerEvents = "auto";
			});

			$("#modulo_distritos_federales_reportes_2024").click(function(event) {
				document.getElementById("modulo_distritos_federales_reportes_2024").style.pointerEvents = "none";
				<?php
					if($tipo_uso_plataforma=='distrito_federal'){
						echo 'urlink="seccionesIneReportes2024/distrito_federal/index.php";';
					}elseif($forzar_distritos_federales=='true'){
						echo 'urlink="seccionesIneReportes2024/forzar_distrito_federal/index.php";';
					}elseif($tipo_uso_plataforma=='all'){
						echo 'urlink="distritosFederalesReportes2024/index.php";';
					}
				?>
				//urlink="seccionesIneReportes2024/distrito_federal/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				//location.reload();
				//$("#homebody").load(urlink);
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_distritos_federales_reportes_2024").style.pointerEvents = "auto";
			});
			$("#modulo_configuracion_matriz_rentabilidad_electoral_2024").click(function(event) {
				document.getElementById("modulo_configuracion_matriz_rentabilidad_electoral_2024").style.pointerEvents = "none";
				urlink="configuracionMatrizRentabilidadSeccionesIne2024/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_configuracion_matriz_rentabilidad_electoral_2024").style.pointerEvents = "auto";
			});
			$("#modulo_qr_scanner_ciudadano").click(function(event) {
				document.getElementById("modulo_qr_scanner_ciudadano").style.pointerEvents = "none";
				urlink="qrScannerCiudadano/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_qr_scanner_ciudadano").style.pointerEvents = "auto";
			});
			$("#modulo_qr_scanner_militante").click(function(event) {
				document.getElementById("modulo_qr_scanner_militante").style.pointerEvents = "none";
				urlink="qrScannerCiudadano/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_qr_scanner_militante").style.pointerEvents = "auto";
			});
			$("#modulo_nomina").click(function(event) {
				document.getElementById("modulo_nomina").style.pointerEvents = "none";
				urlink="nomina/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_nomina").style.pointerEvents = "auto";
			});

			$("#modulo_ejes_gobierno").click(function(event) {
				document.getElementById("modulo_ejes_gobierno").style.pointerEvents = "none";
				urlink="ejesGobierno/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_ejes_gobierno").style.pointerEvents = "auto";
			});

			$("#modulo_tipos_giras").click(function(event) {
				document.getElementById("modulo_tipos_giras").style.pointerEvents = "none";
				urlink="tiposGiras/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_tipos_giras").style.pointerEvents = "auto";
			});

			
		});
	</script>
	<style type="text/css">
		.circulo {
			width: 2.5rem;
			height: 2.5rem;
			background: red;
			border-radius: 50%;
			display: flex;
			-webkit-box-shadow: 0px 0px 5px 1px rgba(0,0,0,.41);
			-moz-box-shadow: 0px 0px 5px 1px rgba(0,0,0,.41);
			box-shadow: 0px 0px 5px 1px rgba(0,0,0,.41);
			justify-content: center;
			align-items: center;
			text-align: center;
			margin:-1px -5px 0px auto;
			/*padding:5%;*/
			float: right;
		}

		.circulo > h2 {
			margin:10px auto 10px auto;
			font-family: sans-serif;
			color: white;
			font-size: 1rem;
			font-weight: bold;
			padding: 5%; 
		}
	</style>
	<div style="display: table;width: 100%;text-align: left; color:black; padding: 25px;" id="bodymanager"> 
		<div id="mensaje" class="mensajeSolo" ></div>
		<?php
			if(empty($modulosPermiso)){
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
			if(
				$tipos_casillas || 
				$distritos_locales || 
				$distritos_federales || 
				$cuarteles || 
				$secciones_ine || 
				$configuracion_matriz_rentabilidad_electoral_2016 || 
				$configuracion_matriz_rentabilidad_electoral_2018 || 
				$configuracion_matriz_rentabilidad_electoral_2021 || 
				$configuracion_matriz_rentabilidad_electoral_2024 ||
				$partidos_legados
			){
				?>
				<label class="tituloForm" style="text-align: center;width: 100%;border-top: 1px solid black;padding-top: 22px">
					<font style="font-size: 20px;">Configuración Electoral</font>
				</label>
				<br>
				<?php
			}
			if( $tipos_casillas ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_tipos_casillas" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/pub/configuracion/tipos_casillas.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Tipos <br>
							Casillas
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$distritos_locales ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_distritos_locales" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Bank-2-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Distritos <br> Locales
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$distritos_federales ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_distritos_federales" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Bank-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Distritos <br> Federales
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$cuarteles ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_cuarteles" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/pub/configuracion/cuarteles.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Cuarteles <br><br>
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$secciones_ine ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_secciones_ine" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Place-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Secciones <br><br>
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$configuracion_matriz_rentabilidad_electoral_2016 ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_configuracion_matriz_rentabilidad_electoral_2016" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/pub/configuracion/configuracion_matriz_rentabilidad_2016.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Config<br>Rentabilidad 1
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$configuracion_matriz_rentabilidad_electoral_2018 ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_configuracion_matriz_rentabilidad_electoral_2018" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/pub/configuracion/configuracion_matriz_rentabilidad_2018.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Config<br>Rentabilidad 2
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$configuracion_matriz_rentabilidad_electoral_2021 ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_configuracion_matriz_rentabilidad_electoral_2021" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/pub/configuracion/configuracion_matriz_rentabilidad_2021.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Config<br>Rentabilidad 3
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$configuracion_matriz_rentabilidad_electoral_2024 ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_configuracion_matriz_rentabilidad_electoral_2024" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/pub/configuracion/configuracion_matriz_rentabilidad_2024.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Config Matriz <br>Rentabilidad 4
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$partidos_legados ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_partidos_legados" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/pub/configuracion/partidos_legados.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Partidos <br>Legados
						</div>
					</div>
				</div> 
				<?php
			}
			if(
				$tipos_categorias_ciudadanos || 
				$tipos_ciudadanos || 
				$tipos_territorios || 
				$categorias_programas_apoyos || 
				$dependencias || 
				$correos_mailing || 
				$ejes_gobierno || 
				$tipos_giras 
				){
				?>
				<label class="tituloForm" style="text-align: center;width: 100%;border-top: 1px solid black;padding-top: 22px">
					<font style="font-size: 20px;">Configuración PUB</font>
				</label>
				<br>
				<?php
			}
			if(	$tipos_ciudadanos ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_tipos_ciudadanos" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/pub/configuracion/tipos_ciudadanos.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Tipos <br>
							Ciudadanos
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$tipos_categorias_ciudadanos ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_tipos_categorias_ciudadanos" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/pub/configuracion/tipos_categorias_ciudadanos.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Tipos <br>
							Categoría Ciudadanos
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$tipos_territorios ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_tipos_territorios" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/pub/configuracion/tipos_territorios.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Tipos <br>
							Territorios
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$categorias_programas_apoyos ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_categorias_programas_apoyos" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/pub/configuracion/tipos_categorias_programas_apoyos.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Categorías <br>
							Programas Apoyos
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$dependencias ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_dependencias" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/pub/configuracion/dependencias.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Dependencias <br><br>
						</div>
					</div>
				</div> 
				<?php
			}
			if( $correos_mailing ){
				?>
				<div class='moduloP' >
					<div class='modulo' id='modulo_correos_mailing'>
						<div class='moduloImagen' style='width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px'>
							<img src='images/modulos/Mail-icon.png' width='24%'></div>
						<div class='moduloDetalle'>
							Correos <br> Mailing
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$supervisores ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_supervisores" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/pub/configuracion/supervisor.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Supervisores <br><br>
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$empresas_adjudicadas ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_empresas_adjudicadas" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/pub/configuracion/empresas_adjuntas.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Empresas <br>Adjudicadas
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$ejes_gobierno ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_ejes_gobierno" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Basket-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Ejes de <br>
							Gobierno
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$tipos_giras ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_tipos_giras" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Webalys-Kameleon.pics-Clipboard-Plan.512.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Tipos de <br>
							Agendas
						</div>
					</div>
				</div> 
				<?php
			}
			if(	
				$tipos_casillas || 
				$distritos_locales || 
				$distritos_federales || 
				$cuarteles || 
				$secciones_ine || 
				$configuracion_matriz_rentabilidad_electoral_2016 || 
				$configuracion_matriz_rentabilidad_electoral_2018 || 
				$configuracion_matriz_rentabilidad_electoral_2021 || 
				$configuracion_matriz_rentabilidad_electoral_2024 ||
				$partidos_legados ||

				$tipos_ciudadanos ||
				$tipos_categorias_ciudadanos  ||

				$tipos_territorios ||
				$categorias_programas_apoyos  ||
				$dependencias ||
				$correos_mailing ||
				$supervisores ||
				$empresas_adjudicadas ||
				$nomina ||
				$ejes_gobierno ||
				$tipos_giras
			){
				?>
				<div style="width: 100%;display: table;padding: 0" >
					<hr>
				</div>
				<?php
			}
			if(	
				$partidos_2016 ||
				$casillas_votos_2016 ||
				$cartografias_municipios_2016 ||
				$cartografias_distritos_locales_2016 ||
				$distritos_federales_2016 ||
				$gobernador_2016 ||
				$senador_2016 ||
				$matriz_rentabilidad_electoral_2016 ||
				$partidos_2018 ||
				$casillas_votos_2018 ||
				$cartografias_municipios_2018 ||
				$cartografias_distritos_locales_2018 ||
				$distritos_federales_2018 ||
				$gobernador_2018 ||
				$senador_2018 ||
				$matriz_rentabilidad_electoral_2018 ||
				$partidos_2021 ||
				$casillas_votos_2021 ||
				$cartografias_municipios_2021 ||
				$cartografias_distritos_locales_2021 ||
				$cartografias_distritos_federales_2021 ||
				$gobernador_2021 ||
				$senador_2021 ||
				$matriz_rentabilidad_electoral_2021 ||
				$partidos_2024 || 
				$casillas_votos_2024 || 
				$cartografias_municipios_2024 || 
				$cartografias_distritos_locales_2024 || 
				$cartografias_distritos_federales_2024 || 
				$gobernador_2024 ||
				$senador_2024 ||
				$matriz_rentabilidad_electoral_2024
				){
				?>
				<label class="tituloForm" style="text-align: center;width: 100%;border-top: 1px solid black;padding-top: 22px">
					<font style="font-size: 20px;">Geografía Electoral</font>
				</label>
				<br>
				<?php
			}
		?>
		<?php
			if(	$preguntas_2022_revocacion_mandato ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_preguntas_2022_revocacion_mandato" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Plaster-patch-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Preguntas <br>Revocación 2022 <br>
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if(	$casillas_votos_2022_revocacion_mandato ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_casillas_votos_2022_revocacion_mandato" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/pub/configuracion/casillas_revocacion_2022.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Casilas <br>Revocación 2022 <br>
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if(	$cartografias_municipios_2022_revocacion_mandato && $tipo_uso_plataforma!='all' ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_cartografias_municipios_2022_revocacion_mandato" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Unlike-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Cartografía <br>Revocación 2022<br>
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if(
				$elecciones['2016']['municipios_show'] == 1 ||
				$elecciones['2016']['distritos_locales_show'] == 1 ||
				$elecciones['2016']['distritos_federales_show'] == 1 ||
				$elecciones['2016']['gobernador_show'] == 1 ||
				$elecciones['2016']['senador_show'] == 1
			){
				if($partidos_2016 || $casillas_votos_2016 || $cartografias_municipios_2016 || $cartografias_distritos_locales_2016 || $distritos_federales_2016 || $matriz_rentabilidad_electoral_2016 || $cartografias_gobernador_2016 || $cartografias_senador_2016 ){
					?>
					<div style="width: 100%;display: table;padding: 0" >
						<hr>
					</div>
					<?php
					unset($ano_eleccion);
					unset($ano_eleccion_unit);
					if($tipo_uso_plataforma=='municipio'){
						if($elecciones['2016']['municipios_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2016']['municipios'];
							if($forzar_gobernador==true){
								$ano_eleccion[] = $elecciones['2016']['gobernador'];
							}
							if($forzar_senador==true){
								$ano_eleccion[] = $elecciones['2016']['senador'];
							}
						}
					}elseif ($tipo_uso_plataforma=='distrito_local') {
						if($elecciones['2016']['distritos_locales_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2016']['distritos_locales'];
						}
					}elseif($tipo_uso_plataforma=='distrito_federal'){
						if($elecciones['2016']['distritos_federales_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2016']['distritos_federales'];
						}
					}elseif($tipo_uso_plataforma=='gobernador'){
						if($elecciones['2016']['gobernador_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2016']['gobernador'];
						}
					}elseif($tipo_uso_plataforma=='senador'){
						if($elecciones['2016']['senador_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2016']['senador'];
						}
					}else{
						if($elecciones['2016']['municipios_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2016']['municipios'];
						}
						if($elecciones['2016']['distritos_locales_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2016']['distritos_locales'];
						}
						if($elecciones['2016']['gobernador_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2016']['gobernador'];
						}
						if($elecciones['2016']['senador_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2016']['senador'];
						}
					}
					foreach ($ano_eleccion as $key => $value) {
						if($value!=NULL){
							$ano_eleccion_unit[$value] = $value;
						}
					}
					if(COUNT($ano_eleccion_unit)>1){
						$eleccion_plural = 'ones';
					}else{
						$eleccion_plural = 'ón';
					}
					$eleccion_fechas = implode(",", $ano_eleccion_unit);
					?>
					<label class="tituloForm" style="text-align: center;width: 100%;border-top: 1px solid black;padding-top: 22px">
						<font style="font-size: 20px;">Elecci<?= $eleccion_plural ?> <?= $eleccion_fechas ?></font>
					</label>
					<?php
				}
				if(	$partidos_2016 ){
					?>
					<div class="moduloP" >
						<div class="modulo" id="modulo_partidos_2016" >
							<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
								<img src="images/modulosV2/pub/2016/partidos_2016.png" width="24%">
							</div>
							<div class="moduloDetalle">
								Partidos <br><br>
							</div>
						</div>
					</div> 
					<?php
				}
				if(	$casillas_votos_2016 ){
					?>
					<div class="moduloP" >
						<div class="modulo" id="modulo_casillas_votos_2016" >
							<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
								<img src="images/modulosV2/pub/2016/casillas_2016.png" width="24%">
							</div>
							<div class="moduloDetalle">
								Casillas <br>Votos <br>
							</div>
						</div>
					</div> 
					<?php
				}
				if(	$cartografias_gobernador_2016 || $matriz_rentabilidad_electoral_2016 ){
					if($tipo_uso_plataforma=='gobernador' || $tipo_uso_plataforma=='all' || $forzar_gobernador == true){
						if($elecciones['2016']['gobernador_show']!=1){
							$display='style="display: none"';
						}else{
							$display = '';
						}
						?>
						<div class="moduloP" <?= $display ?> >
							<div class="modulo" id="modulo_gobernador_reportes_2016" >
								<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
									<img src="images/modulos/Street-View-icon.png" width="24%">
								</div>
								<div class="moduloDetalle">
									Gobernador <br> <?= $elecciones['2016']['gobernador'] ?>
								</div>
							</div>
						</div> 
						<?php
					}
				}
				if(	$cartografias_municipios_2016 || $matriz_rentabilidad_electoral_2016 ){
					if($tipo_uso_plataforma=='municipio' || $tipo_uso_plataforma=='all'){
						if($elecciones['2016']['municipios_show']!=1){
							$display='style="display: none"';
						}else{
							$display = '';
						}
						?>
						<div class="moduloP" <?= $display ?> >
							<div class="modulo" id="modulo_municipios_reportes_2016" >
								<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
									<img src="images/modulosV2/pub/2016/municipios_2016.png" width="24%">
								</div>
								<div class="moduloDetalle">
									Ayuntamientos <br> <?= $elecciones['2016']['municipios'] ?>
								</div>
							</div>
						</div> 
						<?php
					}
				}
				
				if(	$cartografias_distritos_locales_2016 || $matriz_rentabilidad_electoral_2016){
					if($tipo_uso_plataforma=='distrito_local' || $tipo_uso_plataforma=='all' || $forzar_distritos_locales == "true"){
						if($elecciones['2016']['distritos_locales_show']!=1){
							$display='style="display: none"';
						}else{
							$display = '';
						}
						?>
						<div class="moduloP" <?= $display ?> >
							<div class="modulo" id="modulo_distritos_locales_reportes_2016" >
								<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
									<img src="images/modulosV2/pub/2016/distritos_locales_2016.png" width="24%">
								</div>
								<div class="moduloDetalle">
									Distritos <br> Locales <?= $elecciones['2016']['distritos_locales'] ?>
								</div>
							</div>
						</div> 
						<?php
					}
				}
				if(	$cartografias_distritos_federales_2016 || $matriz_rentabilidad_electoral_2016){
					if($tipo_uso_plataforma=='distrito_federal' || $tipo_uso_plataforma=='all' || $forzar_distritos_federales == "true"){
						if($elecciones['2016']['distritos_federales_show']!=1){
							$display='style="display: none"';
						}else{
							$display = '';
						}
						?>
						<div class="moduloP" <?= $display ?> >
							<div class="modulo" id="modulo_distritos_federales_reportes_2016" >
								<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
									<img src="images/modulosV2/pub/2016/distritos_federales_2016.png" width="24%">
								</div>
								<div class="moduloDetalle">
									Distritos <br> Federales <?= $elecciones['2016']['distritos_federales'] ?>
								</div>
							</div>
						</div> 
						<?php
					}
				}
				if(	$cartografias_senador_2016 || $matriz_rentabilidad_electoral_2016 ){
					if($tipo_uso_plataforma=='senador' || $tipo_uso_plataforma=='all' || $forzar_senador == true){
						if($elecciones['2016']['senador_show']!=1){
							$display='style="display: none"';
						}else{
							$display = '';
						}
						?>
						<div class="moduloP" <?= $display ?> >
							<div class="modulo" id="modulo_senador_reportes_2016" >
								<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
									<img src="images/modulos/Speaker-desk-icon.png" width="24%">
								</div>
								<div class="moduloDetalle">
									Senadores <br> <?= $elecciones['2016']['senador'] ?>
								</div>
							</div>
						</div> 
						<?php
					}
				}
			}
		?>
		<?php
			if(
				$elecciones['2018']['municipios_show'] == 1 ||
				$elecciones['2018']['distritos_locales_show'] == 1 ||
				$elecciones['2018']['distritos_federales_show'] == 1 ||
				$elecciones['2018']['gobernador_show'] == 1 ||
				$elecciones['2018']['senador_show'] == 1
			){
				if($partidos_2018 || $casillas_votos_2018 || $cartografias_municipios_2018 || $cartografias_distritos_locales_2018 || $distritos_federales_2018 || $matriz_rentabilidad_electoral_2018 || $cartografias_gobernador_2018 || $cartografias_senador_2018 ){
					?>
					<div style="width: 100%;display: table;padding: 0" >
						<hr>
					</div>
					<?php
					unset($ano_eleccion);
					unset($ano_eleccion_unit);
					if($tipo_uso_plataforma=='municipio'){
						if($elecciones['2018']['municipios_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2018']['municipios'];
							if($forzar_gobernador==true){
								$ano_eleccion[] = $elecciones['2018']['gobernador'];
							}
							if($forzar_senador==true){
								$ano_eleccion[] = $elecciones['2018']['senador'];
							}
						}
					}elseif ($tipo_uso_plataforma=='distrito_local') {
						if($elecciones['2018']['distritos_locales_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2018']['distritos_locales'];
						}
					}elseif($tipo_uso_plataforma=='distrito_federal'){
						if($elecciones['2018']['distritos_federales_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2018']['distritos_federales'];
						}
					}elseif($tipo_uso_plataforma=='gobernador'){
						if($elecciones['2018']['gobernador_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2018']['gobernador'];
						}
					}elseif($tipo_uso_plataforma=='senador'){
						if($elecciones['2018']['senador_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2018']['senador'];
						}
					}else{
						if($elecciones['2018']['municipios_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2018']['municipios'];
						}
						if($elecciones['2018']['distritos_locales_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2018']['distritos_locales'];
						}
						if($elecciones['2018']['gobernador_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2018']['gobernador'];
						}
						if($elecciones['2018']['senador_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2018']['senador'];
						}
					}
					foreach ($ano_eleccion as $key => $value) {
						if($value!=NULL){
							$ano_eleccion_unit[$value] = $value;
						}
					}
					if(COUNT($ano_eleccion_unit)>1){
						$eleccion_plural = 'ones';
					}else{
						$eleccion_plural = 'ón';
					}
					$eleccion_fechas = implode(",", $ano_eleccion_unit);
					?>
					<label class="tituloForm" style="text-align: center;width: 100%;border-top: 1px solid black;padding-top: 22px">
						<font style="font-size: 20px;">Elecci<?= $eleccion_plural ?> <?= $eleccion_fechas ?></font>
					</label>
					<?php
				}
				if(	$partidos_2018 ){
					?>
					<div class="moduloP" >
						<div class="modulo" id="modulo_partidos_2018" >
							<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
								<img src="images/modulosV2/pub/2018/partidos_2018.png" width="24%">
							</div>
							<div class="moduloDetalle">
								Partidos <br><br>
							</div>
						</div>
					</div> 
					<?php
				}
				if(	$casillas_votos_2018 ){
					?>
					<div class="moduloP" >
						<div class="modulo" id="modulo_casillas_votos_2018" >
							<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
								<img src="images/modulosV2/pub/2018/casillas_2018.png" width="24%">
							</div>
							<div class="moduloDetalle">
								Casillas <br>Votos <br>
							</div>
						</div>
					</div> 
					<?php
				}
				if(	$cartografias_gobernador_2018 || $matriz_rentabilidad_electoral_2018 ){
					if($tipo_uso_plataforma=='gobernador' || $tipo_uso_plataforma=='all' || $forzar_gobernador == "true"){
						if($elecciones['2018']['gobernador_show']!=1){
							$display='style="display: none"';
						}else{
							$display = '';
						}
						?>
						<div class="moduloP" <?= $display ?> >
							<div class="modulo" id="modulo_gobernador_reportes_2018" >
								<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
									<img src="images/modulos/Street-View-icon.png" width="24%">
								</div>
								<div class="moduloDetalle">
									Gobernador <br> <?= $elecciones['2018']['gobernador'] ?>
								</div>
							</div>
						</div> 
						<?php
					}
				}
				if(	$cartografias_municipios_2018 || $matriz_rentabilidad_electoral_2018 ){
					if($tipo_uso_plataforma=='municipio' || $tipo_uso_plataforma=='all'){
						if($elecciones['2018']['municipios_show']!=1){
							$display='style="display: none"';
						}else{
							$display = '';
						}
						?>
						<div class="moduloP" <?= $display ?> >
							<div class="modulo" id="modulo_municipios_reportes_2018" >
								<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
									<img src="images/modulosV2/pub/2018/municipios_2018.png" width="24%">
								</div>
								<div class="moduloDetalle">
									Ayuntamientos <br> <?= $elecciones['2018']['municipios'] ?>
								</div>
							</div>
						</div> 
						<?php
					}
				}
				
				if(	$cartografias_distritos_locales_2018 || $matriz_rentabilidad_electoral_2018){
					if($tipo_uso_plataforma=='distrito_local' || $tipo_uso_plataforma=='all' || $forzar_distritos_locales == "true"){
						if($elecciones['2018']['distritos_locales_show']!=1){
							$display='style="display: none"';
						}else{
							$display = '';
						}
						?>
						<div class="moduloP" <?= $display ?> >
							<div class="modulo" id="modulo_distritos_locales_reportes_2018" >
								<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
									<img src="images/modulosV2/pub/2018/distritos_locales_2018.png" width="24%">
								</div>
								<div class="moduloDetalle">
									Distritos <br> Locales <?= $elecciones['2018']['distritos_locales'] ?>
								</div>
							</div>
						</div> 
						<?php
					}
				}
				if(	$cartografias_distritos_federales_2018 || $matriz_rentabilidad_electoral_2018){
					if($tipo_uso_plataforma=='distrito_federal' || $tipo_uso_plataforma=='all' || $forzar_distritos_federales == "true"){
						if($elecciones['2018']['distritos_federales_show']!=1){
							$display='style="display: none"';
						}else{
							$display = '';
						}
						?>
						<div class="moduloP" <?= $display ?> >
							<div class="modulo" id="modulo_distritos_federales_reportes_2018" >
								<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
									<img src="images/modulosV2/pub/2018/distritos_federales_2018.png" width="24%">
								</div>
								<div class="moduloDetalle">
									Distritos <br> Federales <?= $elecciones['2018']['distritos_federales'] ?>
								</div>
							</div>
						</div> 
						<?php
					}
				}
				if(	$cartografias_senador_2018 || $matriz_rentabilidad_electoral_2018 ){
					if($tipo_uso_plataforma=='senador' || $tipo_uso_plataforma=='all' || $forzar_senador == true){
						if($elecciones['2018']['senador_show']!=1){
							$display='style="display: none"';
						}else{
							$display = '';
						}
						?>
						<div class="moduloP" <?= $display ?> >
							<div class="modulo" id="modulo_senador_reportes_2018" >
								<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
									<img src="images/modulos/Speaker-desk-icon.png" width="24%">
								</div>
								<div class="moduloDetalle">
									Senadores <br> <?= $elecciones['2018']['senador'] ?>
								</div>
							</div>
						</div> 
						<?php
					}
				}
			}
		?>
		<?php
			if(
				$elecciones['2021']['municipios_show'] == 1 ||
				$elecciones['2021']['distritos_locales_show'] == 1 ||
				$elecciones['2021']['distritos_federales_show'] == 1 ||
				$elecciones['2021']['gobernador_show'] == 1 ||
				$elecciones['2021']['senador_show'] == 1
			){
				if($partidos_2021 || $casillas_votos_2021 || $cartografias_municipios_2021 || $cartografias_distritos_locales_2021 || $distritos_federales_2021 || $matriz_rentabilidad_electoral_2021 || $cartografias_gobernador_2021 || $cartografias_senador_2021 ){
					?>
					<div style="width: 100%;display: table;padding: 0" >
						<hr>
					</div>
					<?php
					unset($ano_eleccion);
					unset($ano_eleccion_unit);
					if($tipo_uso_plataforma=='municipio'){
						if($elecciones['2021']['municipios_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2021']['municipios'];
							if($forzar_gobernador==true){
								$ano_eleccion[] = $elecciones['2021']['gobernador'];
							}
							if($forzar_senador==true){
								$ano_eleccion[] = $elecciones['2021']['senador'];
							}
						}
					}elseif ($tipo_uso_plataforma=='distrito_local') {
						if($elecciones['2021']['distritos_locales_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2021']['distritos_locales'];
						}
					}elseif($tipo_uso_plataforma=='distrito_federal'){
						if($elecciones['2021']['distritos_federales_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2021']['distritos_federales'];
						}
					}elseif($tipo_uso_plataforma=='gobernador'){
						if($elecciones['2021']['gobernador_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2021']['gobernador'];
						}
					}elseif($tipo_uso_plataforma=='senador'){
						if($elecciones['2021']['senador_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2021']['senador'];
						}
					}else{
						if($elecciones['2021']['municipios_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2021']['municipios'];
						}
						if($elecciones['2021']['distritos_locales_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2021']['distritos_locales'];
						}
						if($elecciones['2021']['gobernador_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2021']['gobernador'];
						}
						if($elecciones['2021']['senador_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2021']['senador'];
						}
					}
					foreach ($ano_eleccion as $key => $value) {
						if($value!=NULL){
							$ano_eleccion_unit[$value] = $value;
						}
					}
					if(COUNT($ano_eleccion_unit)>1){
						$eleccion_plural = 'ones';
					}else{
						$eleccion_plural = 'ón';
					}
					$eleccion_fechas = implode(",", $ano_eleccion_unit);
					?>
					<label class="tituloForm" style="text-align: center;width: 100%;border-top: 1px solid black;padding-top: 22px">
						<font style="font-size: 20px;">Elecci<?= $eleccion_plural ?> <?= $eleccion_fechas ?></font>
					</label>
					<?php
				}
				if(	$partidos_2021 ){
					?>
					<div class="moduloP" >
						<div class="modulo" id="modulo_partidos_2021" >
							<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
								<img src="images/modulosV2/pub/2021/partidos_2021.png" width="24%">
							</div>
							<div class="moduloDetalle">
								Partidos <br><br>
							</div>
						</div>
					</div> 
					<?php
				}
				if(	$casillas_votos_2021 ){
					?>
					<div class="moduloP" >
						<div class="modulo" id="modulo_casillas_votos_2021" >
							<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
								<img src="images/modulosV2/pub/2021/casillas_2021.png" width="24%">
							</div>
							<div class="moduloDetalle">
								Casillas <br>Votos <br>
							</div>
						</div>
					</div> 
					<?php
				}
				if(	$cartografias_gobernador_2021 || $matriz_rentabilidad_electoral_2021 ){
					if($tipo_uso_plataforma=='gobernador' || $tipo_uso_plataforma=='all' || $forzar_gobernador == true){
						if($elecciones['2021']['gobernador_show']!=1){
							$display='style="display: none"';
						}else{
							$display = '';
						}
						?>
						<div class="moduloP" <?= $display ?> >
							<div class="modulo" id="modulo_gobernador_reportes_2021" >
								<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
									<img src="images/modulos/Street-View-icon.png" width="24%">
								</div>
								<div class="moduloDetalle">
									Gobernador <br> <?= $elecciones['2021']['gobernador'] ?>
								</div>
							</div>
						</div> 
						<?php
					}
				}
				if(	$cartografias_municipios_2021 || $matriz_rentabilidad_electoral_2021 ){
					if($tipo_uso_plataforma=='municipio' || $tipo_uso_plataforma=='all'){
						if($elecciones['2021']['municipios_show']!=1){
							$display='style="display: none"';
						}else{
							$display = '';
						}
						?>
						<div class="moduloP" <?= $display ?> >
							<div class="modulo" id="modulo_municipios_reportes_2021" >
								<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
									<img src="images/modulosV2/pub/2021/municipios_2021.png" width="24%">
								</div>
								<div class="moduloDetalle">
									Ayuntamientos <br> <?= $elecciones['2021']['municipios'] ?>
								</div>
							</div>
						</div> 
						<?php
					}
				}
				
				if(	$cartografias_distritos_locales_2021 || $matriz_rentabilidad_electoral_2021){
					if($tipo_uso_plataforma=='distrito_local' || $tipo_uso_plataforma=='all' || $forzar_distritos_locales == "true"){
						if($elecciones['2021']['distritos_locales_show']!=1){
							$display='style="display: none"';
						}else{
							$display = '';
						}
						?>
						<div class="moduloP" <?= $display ?> >
							<div class="modulo" id="modulo_distritos_locales_reportes_2021" >
								<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
									<img src="images/modulosV2/pub/2021/distritos_locales_2021.png" width="24%">
								</div>
								<div class="moduloDetalle">
									Distritos <br> Locales <?= $elecciones['2021']['distritos_locales'] ?>
								</div>
							</div>
						</div> 
						<?php
					}
				}
				if(	$cartografias_distritos_federales_2021 || $matriz_rentabilidad_electoral_2021){
					if($tipo_uso_plataforma=='distrito_federal' || $tipo_uso_plataforma=='all' || $forzar_distritos_federales == "true"){
						if($elecciones['2021']['distritos_federales_show']!=1){
							$display='style="display: none"';
						}else{
							$display = '';
						}
						?>
						<div class="moduloP" <?= $display ?> >
							<div class="modulo" id="modulo_distritos_federales_reportes_2021" >
								<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
									<img src="images/modulosV2/pub/2021/distritos_federales_2021.png" width="24%">
								</div>
								<div class="moduloDetalle">
									Distritos <br> Federales <?= $elecciones['2021']['distritos_federales'] ?>
								</div>
							</div>
						</div> 
						<?php
					}
				}
				if(	$cartografias_senador_2021 || $matriz_rentabilidad_electoral_2021 ){
					if($tipo_uso_plataforma=='senador' || $tipo_uso_plataforma=='all' || $forzar_senador == true){
						if($elecciones['2021']['senador_show']!=1){
							$display='style="display: none"';
						}else{
							$display = '';
						}
						?>
						<div class="moduloP" <?= $display ?> >
							<div class="modulo" id="modulo_senador_reportes_2021" >
								<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
									<img src="images/modulos/Speaker-desk-icon.png" width="24%">
								</div>
								<div class="moduloDetalle">
									Senadores <br> <?= $elecciones['2021']['senador'] ?>
								</div>
							</div>
						</div> 
						<?php
					}
				}
			}
		?>
		<?php
			if(
				$elecciones['2024']['municipios_show'] == 1 ||
				$elecciones['2024']['distritos_locales_show'] == 1 ||
				$elecciones['2024']['distritos_federales_show'] == 1 ||
				$elecciones['2024']['gobernador_show'] == 1 ||
				$elecciones['2024']['senador_show'] == 1
			){
				if($partidos_2024 || $casillas_votos_2024 || $cartografias_municipios_2024 || $cartografias_distritos_locales_2024 || $distritos_federales_2024 || $matriz_rentabilidad_electoral_2024 || $cartografias_gobernador_2024 || $cartografias_senador_2024 ){
					?>
					<div style="width: 100%;display: table;padding: 0" >
						<hr>
					</div>
					<?php
					unset($ano_eleccion);
					unset($ano_eleccion_unit);
					if($tipo_uso_plataforma=='municipio'){
						if($elecciones['2024']['municipios_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2024']['municipios'];
							if($forzar_gobernador==true){
								$ano_eleccion[] = $elecciones['2024']['gobernador'];
							}
							if($forzar_senador==true){
								$ano_eleccion[] = $elecciones['2024']['senador'];
							}
						}
					}elseif ($tipo_uso_plataforma=='distrito_local') {
						if($elecciones['2024']['distritos_locales_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2024']['distritos_locales'];
						}
					}elseif($tipo_uso_plataforma=='distrito_federal'){
						if($elecciones['2024']['distritos_federales_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2024']['distritos_federales'];
						}
					}elseif($tipo_uso_plataforma=='gobernador'){
						if($elecciones['2024']['gobernador_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2024']['gobernador'];
						}
					}elseif($tipo_uso_plataforma=='senador'){
						if($elecciones['2024']['senador_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2024']['senador'];
						}
					}else{
						if($elecciones['2024']['municipios_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2024']['municipios'];
						}
						if($elecciones['2024']['distritos_locales_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2024']['distritos_locales'];
						}
						if($elecciones['2024']['gobernador_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2024']['gobernador'];
						}
						if($elecciones['2024']['senador_show'] == 1 ){
							$ano_eleccion[] = $elecciones['2024']['senador'];
						}
					}
					foreach ($ano_eleccion as $key => $value) {
						if($value!=NULL){
							$ano_eleccion_unit[$value] = $value;
						}
					}
					if(COUNT($ano_eleccion_unit)>1){
						$eleccion_plural = 'ones';
					}else{
						$eleccion_plural = 'ón';
					}
					$eleccion_fechas = implode(",", $ano_eleccion_unit);
					?>
					<label class="tituloForm" style="text-align: center;width: 100%;border-top: 1px solid black;padding-top: 22px">
						<font style="font-size: 20px;">Elecci<?= $eleccion_plural ?> <?= $eleccion_fechas ?></font>
					</label>
					<?php
				}
				if(	$partidos_2024 ){
					?>
					<div class="moduloP" >
						<div class="modulo" id="modulo_partidos_2024" >
							<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
								<img src="images/modulosV2/pub/2024/partidos_2024.png" width="24%">
							</div>
							<div class="moduloDetalle">
								Partidos <br><br>
							</div>
						</div>
					</div> 
					<?php
				}
				if(	$casillas_votos_2024 ){
					?>
					<div class="moduloP" >
						<div class="modulo" id="modulo_casillas_votos_2024" >
							<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
								<img src="images/modulosV2/pub/2024/casillas_2024.png" width="24%">
							</div>
							<div class="moduloDetalle">
								Casillas <br>Votos <br>
							</div>
						</div>
					</div> 
					<?php
				}
				if(	$cartografias_gobernador_2024 || $matriz_rentabilidad_electoral_2024 ){
					if($tipo_uso_plataforma=='gobernador' || $tipo_uso_plataforma=='all' || $forzar_gobernador == true){
						if($elecciones['2024']['gobernador_show']!=1){
							$display='style="display: none"';
						}else{
							$display = '';
						}
						?>
						<div class="moduloP" <?= $display ?> >
							<div class="modulo" id="modulo_gobernador_reportes_2024" >
								<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
									<img src="images/modulos/Street-View-icon.png" width="24%">
								</div>
								<div class="moduloDetalle">
									Gobernador <br> <?= $elecciones['2024']['gobernador'] ?>
								</div>
							</div>
						</div> 
						<?php
					}
				}
				if(	$cartografias_municipios_2024 || $matriz_rentabilidad_electoral_2024 ){
					if($tipo_uso_plataforma=='municipio' || $tipo_uso_plataforma=='all'){
						if($elecciones['2024']['municipios_show']!=1){
							$display='style="display: none"';
						}else{
							$display = '';
						}
						?>
						<div class="moduloP" <?= $display ?> >
							<div class="modulo" id="modulo_municipios_reportes_2024" >
								<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
									<img src="images/modulosV2/pub/2024/municipios_2024.png" width="24%">
								</div>
								<div class="moduloDetalle">
									Ayuntamientos <br> <?= $elecciones['2024']['municipios'] ?>
								</div>
							</div>
						</div> 
						<?php
					}
				}
				
				if(	$cartografias_distritos_locales_2024 || $matriz_rentabilidad_electoral_2024){
					if($tipo_uso_plataforma=='distrito_local' || $tipo_uso_plataforma=='all' || $forzar_distritos_locales == "true"){
						if($elecciones['2024']['distritos_locales_show']!=1){
							$display='style="display: none"';
						}else{
							$display = '';
						}
						?>
						<div class="moduloP" <?= $display ?> >
							<div class="modulo" id="modulo_distritos_locales_reportes_2024" >
								<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
									<img src="images/modulosV2/pub/2024/distritos_locales_2024.png" width="24%">
								</div>
								<div class="moduloDetalle">
									Distritos <br> Locales <?= $elecciones['2024']['distritos_locales'] ?>
								</div>
							</div>
						</div> 
						<?php
					}
				}
				if(	$cartografias_distritos_federales_2024 || $matriz_rentabilidad_electoral_2024){
					if($tipo_uso_plataforma=='distrito_federal' || $tipo_uso_plataforma=='all' || $forzar_distritos_federales == "true"){
						if($elecciones['2024']['distritos_federales_show']!=1){
							$display='style="display: none"';
						}else{
							$display = '';
						}
						?>
						<div class="moduloP" <?= $display ?> >
							<div class="modulo" id="modulo_distritos_federales_reportes_2024" >
								<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
									<img src="images/modulosV2/pub/2024/distritos_federales_2024.png" width="24%">
								</div>
								<div class="moduloDetalle">
									Distritos <br> Federales <?= $elecciones['2024']['distritos_federales'] ?>
								</div>
							</div>
						</div> 
						<?php
					}
				}
				if(	$cartografias_senador_2024 || $matriz_rentabilidad_electoral_2024 ){
					if($tipo_uso_plataforma=='senador' || $tipo_uso_plataforma=='all' || $forzar_senador == true){
						if($elecciones['2024']['senador_show']!=1){
							$display='style="display: none"';
						}else{
							$display = '';
						}
						?>
						<div class="moduloP" <?= $display ?> >
							<div class="modulo" id="modulo_senador_reportes_2024" >
								<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
									<img src="images/modulos/Speaker-desk-icon.png" width="24%">
								</div>
								<div class="moduloDetalle">
									Senadores <br> <?= $elecciones['2024']['senador'] ?>
								</div>
							</div>
						</div> 
						<?php
					}
				}
			}
		?>
		<?php
			if(
				$partidos_2016 ||
				$casillas_votos_2016 ||
				$cartografias_municipios_2016 ||
				$cartografias_distritos_locales_2016 ||
				$distritos_federales_2016 ||
				$gobernador_2016 ||
				$senador_2016 ||
				$matriz_rentabilidad_electoral_2016 ||
				$partidos_2018 ||
				$casillas_votos_2018 ||
				$cartografias_municipios_2018 ||
				$cartografias_distritos_locales_2018 ||
				$distritos_federales_2018 ||
				$gobernador_2018 ||
				$senador_2018 ||
				$matriz_rentabilidad_electoral_2018 ||
				$partidos_2021 ||
				$casillas_votos_2021 ||
				$cartografias_municipios_2021 ||
				$cartografias_distritos_locales_2021 ||
				$cartografias_distritos_federales_2021 ||
				$gobernador_2021 ||
				$senador_2021 ||
				$matriz_rentabilidad_electoral_2021 ||
				$partidos_2024 || 
				$casillas_votos_2024 || 
				$cartografias_municipios_2024 || 
				$cartografias_distritos_locales_2024 || 
				$cartografias_distritos_federales_2024 || 
				$gobernador_2024 ||
				$senador_2024 ||
				$matriz_rentabilidad_electoral_2024 ||
				$nomina
			){
				?>
				<div style="width: 100%;display: table;padding: 0" >
					<hr>
				</div>
				<?php
			}
		?>
		<?php
			if( $secciones_ine_ciudadanos || $secciones_ine_ciudadanos_categorias || $secciones_ine_actividades || $swich_operaciones || $encuestas || $secciones_ine_ciudadanos_encuestas || $secciones_ine_ciudadanos_seguimientos || $campanas_mailing || $secciones_ine_ciudadanos_campanas_mailing || $api_mailing || $militantes_partidos || $secciones_ine_grupos || $secciones_ine_ciudadanos_giras || $programas_apoyos || $secciones_ine_ciudadanos_programas_apoyos || $qr_scanner_ciudadano || $qr_scanner_militante || $nomina ){
				?>
				<label class="tituloForm" style="text-align: center;width: 100%;border-top: 1px solid black;padding-top: 22px">
					<font style="font-size: 20px;"><img src="images/modulos_partes/sub.png" width="100px" ><br><br>Sistema Único De Beneficiarios  </font>
				</label>
				<br>
				<?php
			}
		?>
		<?php
			if(	$swich_operaciones ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_switch_operaciones" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Settings-2-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Switch <br>Operaciones
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$nomina ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_nomina" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Addressbook-3-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Nomina<br><br>
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$qr_scanner_ciudadano ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_qr_scanner_ciudadano" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/qr-code-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							QR<br>Ciudadano<br>
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$qr_scanner_militante ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_qr_scanner_militante" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/qr-code-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							QR<br>Militante<br>
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if(	$encuestas ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_encuestas" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Select_vote.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Encuestas<br><br>
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if(	$programas_apoyos || $secciones_ine_ciudadanos_programas_apoyos ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_programas_apoyos" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Heart-love-plus-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Programas <br>
							Sociales
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if(	$secciones_ine_ciudadanos_avance_semaforo ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_secciones_ine_ciudadanos_avance_semaforo" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Webalys-Kameleon.pics-Music-Equalizer.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Padrón & Estructura<br>
							Semáforo %
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$secciones_ine_ciudadanos_secciones_avance_semaforo ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_secciones_ine_ciudadanos_secciones_avance_semaforo" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Elegantthemes-Beautiful-Flat-Traffic.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Padrón & Estructura<br>
							Semáforo UNIDAD
						</div>
					</div>
				</div>  
				<?php
			}
			

			
		?>
		<?php
			if(	$secciones_ine_ciudadanos || $secciones_ine_ciudadanos_encuestas || $secciones_ine_ciudadanos_seguimientos || $secciones_ine_ciudadanos_categorias || $militantes_partidos || $secciones_ine_ciudadanos_programas_apoyos || $secciones_ine_ciudadanos_giras ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_secciones_ine_ciudadanos" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/pub/operativo/ciudadanos.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Padrón Beneficiarios <br> & Estructura<br>
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$secciones_ine_ciudadanos_estructuras ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_secciones_ine_ciudadanos_estructuras" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Networking-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Estructura<br>
							Completa
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$secciones_ine_ciudadanos_validores ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_secciones_ine_ciudadanos_estructuras_validador" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Validador-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Padrón <br>Validador<br>
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if(	$secciones_ine_ciudadanos_usuarios ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_secciones_ine_ciudadanos_usuarios" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Minus-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Ciudadanos <br>
							Usuarios
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		
		<?php
			if(	$lista_nominal ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_lista_nominal" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Checklist-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Lista<br><br>
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if(	$secciones_ine_actividades ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_secciones_ine_actividades" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/pub/operativo/programas_inversion.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Programas <br>de Inversión
						</div>
					</div>
				</div> 
				<?php
			}

			if(	$secciones_ine_grupos ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_secciones_ine_grupos" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Group-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Grupos <br> Interes
						</div>
					</div>
				</div> 
				<?php
			}

			if(	$secciones_ine_agendas_gobierno ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_secciones_ine_agendas_gobierno" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Video-Camera-2-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Agenda de<br>Gobierno
						</div>
					</div>
				</div> 
				<?php
			}

			
		?>
		<?php
			if( $secciones_ine_ciudadanos || $secciones_ine_ciudadanos_avance_semaforo || $secciones_ine_ciudadanos_categorias || $secciones_ine_actividades || $swich_operaciones || $encuestas || $secciones_ine_ciudadanos_encuestas || $secciones_ine_ciudadanos_seguimientos || $secciones_ine_grupos|| $qr_scanner_ciudadano || $qr_scanner_militante){
				?>
				<div style="width: 100%;display: table;padding: 0" >
					<hr>
				</div>
				<?php
			}
		?>
		<?php
			if( $campanas_mailing || $secciones_ine_ciudadanos_campanas_mailing || $candidato || $api_mailing || $api_sms || $campanas_sms || $secciones_ine_ciudadanos_campanas_sms ){
				?>
				<label class="tituloForm" style="text-align: center;width: 100%;border-top: 1px solid black;padding-top: 22px">
					<font style="font-size: 20px;">Comunicación</font>
				</label>
				<br>
				<?php
			}
		?>
		<?php
			if( $api_mailing ){
				?>
				<div class='moduloP' >
					<div class='modulo' id='modulo_api_mailing'>
						<div class='moduloImagen' style='width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px'>
							<img src='images/modulos/Arrow-upload-2-icon.png' width='24%'></div>
						<div class='moduloDetalle'>
							Api <br>Mailing<br>
						</div>
					</div>
				</div>
				<?php
			}
		?>
		<?php
			if( $campanas_mailing ){
				?>
				<div class='moduloP' >
					<div class='modulo' id='modulo_campanas_mailing'>
						<div class='moduloImagen' style='width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px'>
							<img src='images/modulos/Coding-Html-icon.png' width='24%'></div>
						<div class='moduloDetalle'>
							Campañas <br> Mails
						</div>
					</div>
				</div>
				<?php
			}
		?>
		<?php
			if( $secciones_ine_ciudadanos_campanas_mailing ){
				?>
				<div class='moduloP' >
					<div class='modulo' id='modulo_secciones_ine_ciudadanos_campanas_mailing'>
						<div class='moduloImagen' style='width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px'>
							<img src='images/modulos/Smartphone-Message-icon.png' width='24%'></div>
						<div class='moduloDetalle'>
							Ciudadanos <br> Campañas
						</div>
					</div>
				</div>
				<?php
			}
		?>
		<?php
			if( $candidato ){
				?>
				<div class='moduloP' >
					<div class='modulo' id='modulo_candidato'>
						<div class='moduloImagen' style='width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px'>
							<img src='images/modulos/Addressbook-3-icon.png' width='24%'></div>
						<div class='moduloDetalle'>
							Candidato <br><br>
						</div>
					</div>
				</div>
				<?php
			}
		?>
		<?php
			if( $api_sms || $campanas_sms || $secciones_ine_ciudadanos_campanas_sms ){
				?>
				<div style="width: 100%;display: table;padding: 0" >
					<hr>
				</div>
				<?php
			}
		?>
		<?php
			if( $api_sms ){
				?>
				<div class='moduloP' >
					<div class='modulo' id='modulo_api_sms'>
						<div class='moduloImagen' style='width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px'>
							<img src='images/modulos/Message-clouds-icon.png' width='24%'></div>
						<div class='moduloDetalle'>
							Api<br>SMS<br>
						</div>
					</div>
				</div>
				<div class='moduloP' >
					<div class='modulo' id='modulo_api_sms_status'>
						<div class='moduloImagen' style='width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px'>
							<img src='images/modulos/Files-icon.png' width='24%'></div>
						<div class='moduloDetalle'>
							Api<br>SMS Status
						</div>
					</div>
				</div>
				<?php
			}
		?>
		<?php
			if( $campanas_sms ){
				?>
				<div class='moduloP' >
					<div class='modulo' id='modulo_campanas_sms'>
						<div class='moduloImagen' style='width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px'>
							<img src='images/modulos/Mobile-3-icon.png' width='24%'></div>
						<div class='moduloDetalle'>
							Campañas <br> SMS
						</div>
					</div>
				</div>
				<?php
			}
		?>
		<?php
			if( $secciones_ine_ciudadanos_campanas_sms ){
				?>
				<div class='moduloP' >
					<div class='modulo' id='modulo_secciones_ine_ciudadanos_campanas_sms'>
						<div class='moduloImagen' style='width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px'>
							<img src='images/modulos/Message-clouds-icon.png' width='24%'></div>
						<div class='moduloDetalle'>
							Ciudadanos <br> SMS
						</div>
					</div>
				</div>
				<?php
			}
		?>
		<?php
			if( $whatsapp_python || $api_whatsapp || $campanas_whatsapp || $secciones_ine_ciudadanos_campanas_whatsapp ){
				?>
				<div style="width: 100%;display: table;padding: 0" >
					<hr>
				</div>
				<?php
			}
		?>
		<?php
			if( $whatsapp_python ){
				?>
				<div class='moduloP' >
					<div class='modulo' id='modulo_whatsapp_python'>
						<div class='moduloImagen' style='width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px'>
							<img src='images/modulos/WhatsApp-icon_python.png' width='24%'></div>
						<div class='moduloDetalle'>
							Python<br>Whatsapp<br>
						</div>
					</div>
				</div>
				<?php
			}
		?>
		<?php
			if( $api_whatsapp ){
				?>
				<div class='moduloP' >
					<div class='modulo' id='modulo_api_whatsapp'>
						<div class='moduloImagen' style='width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px'>
							<img src='images/modulos/WhatsApp-icon.png' width='24%'></div>
						<div class='moduloDetalle'>
							Api<br>Whatsapp<br>
						</div>
					</div>
				</div>
				<div class='moduloP' >
					<div class='modulo' id='modulo_api_whatsapp_status'>
						<div class='moduloImagen' style='width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px'>
							<img src='images/modulos/Checklist-icon.png' width='24%'></div>
						<div class='moduloDetalle'>
							Api<br>Whatsapp Status
						</div>
					</div>
				</div>
				<?php
			}
		?>
		<?php
			if( $campanas_whatsapp ){
				?>
				<div class='moduloP' >
					<div class='modulo' id='modulo_campanas_whatsapp'>
						<div class='moduloImagen' style='width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px'>
							<img src='images/modulos/Paper-Plane-icon.png' width='24%'></div>
						<div class='moduloDetalle'>
							Campañas <br> Whatsapp
						</div>
					</div>
				</div>
				<?php
			}
		?>
		<?php
			if( $secciones_ine_ciudadanos_campanas_whatsapp ){
				?>
				<div class='moduloP' >
					<div class='modulo' id='modulo_secciones_ine_ciudadanos_campanas_whatsapp'>
						<div class='moduloImagen' style='width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px'>
							<img src='images/modulos/Chat-2-icon.png' width='24%'></div>
						<div class='moduloDetalle'>
							Ciudadanos <br> Whatsapp
						</div>
					</div>
				</div>
				<div class='moduloP' >
					<div class='modulo' id='modulo_api_whatsapp_mensajes'>
						<div class='moduloImagen' style='width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px'>
							<img src='images/modulos/Networking-icon.png' width='24%'></div>
						<div class='moduloDetalle'>
							Whatsapp<br> Sin Clasificar 
						</div>
					</div>
				</div>
				<?php
			}
		?>
	</div>