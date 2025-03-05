<?php
		@session_start();
		$_SESSION['Paguinasub']="importacionesSistema/index.php"; 
		include "../functions/security.php"; 
		include "functions/security.php"; 
		include "../functions/importacion.php"; 
		include "functions/importacion.php"; 
	?>
	<title>Importación Rapida</title>
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

			if(tabla_operacion=="claves_2"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/claves_2/claves_2_validador_add.php";
				}else{
					var ruta = "importacionesSistema/claves_2/claves_2_validador_edit.php";
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

			if(tabla_operacion=="tipos_actividades"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/tipos_actividades/tipos_actividades_validador_add.php";
				}else{
					var ruta = "importacionesSistema/tipos_actividades/tipos_actividades_validador_edit.php";
				}
			}

			if(tabla_operacion=="redes_sociales"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/redes_sociales/redes_sociales_validador_add.php";
				}else{
					var ruta = "importacionesSistema/redes_sociales/redes_sociales_validador_edit.php";
				}
			}

			if(tabla_operacion=="servidores_correos"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/servidores_correos/servidores_correos_validador_add.php";
				}else{
					var ruta = "importacionesSistema/servidores_correos/servidores_correos_validador_edit.php";
				}
			}

			if(tabla_operacion=="identidades"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/identidades/identidades_validador_add.php";
				}else{
					var ruta = "importacionesSistema/identidades/identidades_validador_edit.php";
				}
			}

			if(tabla_operacion=="correos_electronicos"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/correos_electronicos/correos_electronicos_validador_add.php";
				}else{
					//$("#mensaje").html("Estas modificaciones tienen que ser manuales.");
					//document.getElementById("mensaje").classList.add("mensajeError");
					//return false;
					var ruta = "importacionesSistema/correos_electronicos/correos_electronicos_validador_edit.php";
				}
			}

			if(tabla_operacion=="cuentas_redes_sociales"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/cuentas_redes_sociales/cuentas_redes_sociales_validador_add.php";
				}else{
					var ruta = "importacionesSistema/cuentas_redes_sociales/cuentas_redes_sociales_validador_edit.php";
				}
			}

			if(tabla_operacion=="cuentas_redes_sociales_actividades"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/cuentas_redes_sociales_actividades/cuentas_redes_sociales_actividades_validador_add.php";
				}else{
					var ruta = "importacionesSistema/cuentas_redes_sociales_actividades/cuentas_redes_sociales_actividades_validador_edit.php";
				}
			}

			if(tabla_operacion=="secciones_ine"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/secciones_ine/secciones_ine_validador_add.php";
				}else{
					var ruta = "importacionesSistema/secciones_ine/secciones_ine_validador_edit.php";
				}
			}
			if(tabla_operacion=="secciones_ine_parametros"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/secciones_ine_parametros/secciones_ine_parametros_validador_add.php";
				}else{
					$("#mensaje").html("No disponible.");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
					var ruta = "importacionesSistema/secciones_ine_parametros/secciones_ine_parametros_validador_edit.php";
				}
			}


			if(tabla_operacion=="tipos_casillas"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/tipos_casillas/tipos_casillas_validador_add.php";
				}else{
					var ruta = "importacionesSistema/tipos_casillas/tipos_casillas_validador_edit.php";
				}
			}

			if(tabla_operacion=="partidos_2016"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/partidos_2016/partidos_2016_validador_add.php";
				}else{
					var ruta = "importacionesSistema/partidos_2016/partidos_2016_validador_edit.php";
				}
			}

			if(tabla_operacion=="partidos_2018"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/partidos_2018/partidos_2018_validador_add.php";
				}else{
					var ruta = "importacionesSistema/partidos_2018/partidos_2018_validador_edit.php";
				}
			}

			if(tabla_operacion=="casillas_votos_2018"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/casillas_votos_2018/casillas_votos_2018_validador_add.php";
				}else{
					var ruta = "importacionesSistema/casillas_votos_2018/casillas_votos_2018_validador_edit.php";
				}
			}

			if(tabla_operacion=="casillas_votos_partidos_2018"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/casillas_votos_partidos_2018/casillas_votos_partidos_2018_validador_add.php";
				}else{
					//$("#mensaje").html("No disponible.");
					//document.getElementById("mensaje").classList.add("mensajeError");
					//return false;
					var ruta = "importacionesSistema/casillas_votos_partidos_2018/casillas_votos_partidos_2018_validador_edit.php";
				}
			}

			if(tabla_operacion=="partidos_2021"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/partidos_2021/partidos_2021_validador_add.php";
				}else{
					var ruta = "importacionesSistema/partidos_2021/partidos_2021_validador_edit.php";
				}
			}

			if(tabla_operacion=="casillas_votos_2021"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/casillas_votos_2021/casillas_votos_2021_validador_add.php";
				}else{
					var ruta = "importacionesSistema/casillas_votos_2021/casillas_votos_2021_validador_edit.php";
				}
			}

			if(tabla_operacion=="casillas_votos_partidos_2021"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/casillas_votos_partidos_2021/casillas_votos_partidos_2021_validador_add.php";
				}else{
					$("#mensaje").html("No disponible.");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
					var ruta = "importacionesSistema/casillas_votos_partidos_2021/casillas_votos_partidos_2021_validador_edit.php";
				}
			}

			if(tabla_operacion=="partidos_2024"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/partidos_2024/partidos_2024_validador_add.php";
				}else{
					var ruta = "importacionesSistema/partidos_2024/partidos_2024_validador_edit.php";
				}
			}

			if(tabla_operacion=="casillas_votos_2024"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/casillas_votos_2024/casillas_votos_2024_validador_add.php";
				}else{
					var ruta = "importacionesSistema/casillas_votos_2024/casillas_votos_2024_validador_edit.php";
				}
			}

			if(tabla_operacion=="casillas_votos_partidos_2024"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/casillas_votos_partidos_2024/casillas_votos_partidos_2024_validador_add.php";
				}else{
					$("#mensaje").html("No disponible.");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
					var ruta = "importacionesSistema/casillas_votos_partidos_2024/casillas_votos_partidos_2024_validador_edit.php";
				}
			}

			if(tabla_operacion=="secciones_ine_ciudadanos"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/secciones_ine_ciudadanos/secciones_ine_ciudadanos_validador_add.php";
				}else{
					var ruta = "importacionesSistema/secciones_ine_ciudadanos/secciones_ine_ciudadanos_validador_edit.php";
				}
			}

			if(tabla_operacion=="tipos_ciudadanos"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/tipos_ciudadanos/tipos_ciudadanos_validador_add.php";
				}else{
					var ruta = "importacionesSistema/tipos_ciudadanos/tipos_ciudadanos_validador_edit.php";
				}
			}

			if(tabla_operacion=="distritos_locales"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/distritos_locales/distritos_locales_validador_add.php";
				}else{
					var ruta = "importacionesSistema/distritos_locales/distritos_locales_validador_edit.php";
				}
			}

			if(tabla_operacion=="distritos_locales_parametros"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/distritos_locales_parametros/distritos_locales_parametros_validador_add.php";
				}else{
					$("#mensaje").html("No disponible.");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
					var ruta = "importacionesSistema/distritos_locales_parametros/distritos_locales_parametros_validador_edit.php";
				}
			}

			if(tabla_operacion=="distritos_federales"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/distritos_federales/distritos_federales_validador_add.php";
				}else{
					var ruta = "importacionesSistema/distritos_federales/distritos_federales_validador_edit.php";
				}
			}

			if(tabla_operacion=="distritos_federales_parametros"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/distritos_federales_parametros/distritos_federales_parametros_validador_add.php";
				}else{
					$("#mensaje").html("No disponible.");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
					var ruta = "importacionesSistema/distritos_federales_parametros/distritos_federales_parametros_validador_edit.php";
				}
			}

			if(tabla_operacion=="tipos_categorias_ciudadanos"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/tipos_categorias_ciudadanos/tipos_categorias_ciudadanos_validador_add.php";
				}else{
					var ruta = "importacionesSistema/tipos_categorias_ciudadanos/tipos_categorias_ciudadanos_validador_edit.php";
				}
			}


			if(tabla_operacion=="secciones_ine_ciudadanos_categorias"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/secciones_ine_ciudadanos_categorias/secciones_ine_ciudadanos_categorias_validador_add.php";
				}else{
					$("#mensaje").html("No disponible.");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
					var ruta = "importacionesSistema/secciones_ine_ciudadanos_categorias/secciones_ine_ciudadanos_categorias_validador_edit.php";
				}
			}

			if(tabla_operacion=="encuestas"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/encuestas/encuestas_validador_add.php";
				}else{
					var ruta = "importacionesSistema/encuestas/encuestas_validador_edit.php";
				}
			}

			if(tabla_operacion=="cuestionarios"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/cuestionarios/cuestionarios_validador_add.php";
				}else{
					var ruta = "importacionesSistema/cuestionarios/cuestionarios_validador_edit.php";
				}
			}

			if(tabla_operacion=="cuestionarios_respuestas"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/cuestionarios_respuestas/cuestionarios_respuestas_validador_add.php";
				}else{
					var ruta = "importacionesSistema/cuestionarios_respuestas/cuestionarios_respuestas_validador_edit.php";
				}
			}

			if(tabla_operacion=="cuarteles"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/cuarteles/cuarteles_validador_add.php";
				}else{
					var ruta = "importacionesSistema/cuarteles/cuarteles_validador_edit.php";
				}
			}

			if(tabla_operacion=="secciones_ine_ciudadanos_encuestas"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/secciones_ine_ciudadanos_encuestas/secciones_ine_ciudadanos_encuestas_validador_add.php";
				}else{
					var ruta = "importacionesSistema/secciones_ine_ciudadanos_encuestas/secciones_ine_ciudadanos_encuestas_validador_edit.php";
				}
			}

			if(tabla_operacion=="secciones_ine_ciudadanos_encuestas_respuestas"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/secciones_ine_ciudadanos_encuestas_respuestas/secciones_ine_ciudadanos_encuestas_respuestas_validador_add.php";
				}else{
					$("#mensaje").html("No disponible.");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
					var ruta = "importacionesSistema/secciones_ine_ciudadanos_encuestas_respuestas/secciones_ine_ciudadanos_encuestas_respuestas_validador_edit.php";
				}
			}

			if(tabla_operacion=="tipos_ciudadanos"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/tipos_ciudadanos/tipos_ciudadanos_validador_add.php";
				}else{
					var ruta = "importacionesSistema/tipos_ciudadanos/tipos_ciudadanos_validador_edit.php";
				}
			}

			if(tabla_operacion=="tipos_territorios"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/tipos_territorios/tipos_territorios_validador_add.php";
				}else{
					var ruta = "importacionesSistema/tipos_territorios/tipos_territorios_validador_edit.php";
				}
			}

			if(tabla_operacion=="categorias_programas_apoyos"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/categorias_programas_apoyos/categorias_programas_apoyos_validador_add.php";
				}else{
					var ruta = "importacionesSistema/categorias_programas_apoyos/categorias_programas_apoyos_validador_edit.php";
				}
			}

			if(tabla_operacion=="dependencias"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/dependencias/dependencias_validador_add.php";
				}else{
					var ruta = "importacionesSistema/dependencias/dependencias_validador_edit.php";
				}
			}

			if(tabla_operacion=="partidos_legados"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/partidos_legados/partidos_legados_validador_add.php";
				}else{
					var ruta = "importacionesSistema/partidos_legados/partidos_legados_validador_edit.php";
				}
			}

			if(tabla_operacion=="militantes_partidos"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/militantes_partidos/militantes_partidos_validador_add.php";
				}else{
					var ruta = "importacionesSistema/militantes_partidos/militantes_partidos_validador_edit.php";
				}
			}

			if(tabla_operacion=="programas_apoyos"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/programas_apoyos/programas_apoyos_validador_add.php";
				}else{
					var ruta = "importacionesSistema/programas_apoyos/programas_apoyos_validador_edit.php";
				}
			}

			if(tabla_operacion=="programas_apoyos_territorios"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/programas_apoyos_territorios/programas_apoyos_territorios_validador_add.php";
				}else{
					$("#mensaje").html("No disponible.");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
					var ruta = "importacionesSistema/programas_apoyos_territorios/programas_apoyos_territorios_validador_edit.php";
				}
			}

			if(tabla_operacion=="programas_apoyos_categorias"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/programas_apoyos_categorias/programas_apoyos_categorias_validador_add.php";
				}else{
					$("#mensaje").html("No disponible.");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
					var ruta = "importacionesSistema/programas_apoyos_categorias/programas_apoyos_categorias_validador_edit.php";
				}
			}

			if(tabla_operacion=="programas_apoyos_dependencias"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/programas_apoyos_dependencias/programas_apoyos_dependencias_validador_add.php";
				}else{
					$("#mensaje").html("No disponible.");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
					var ruta = "importacionesSistema/programas_apoyos_dependencias/programas_apoyos_dependencias_validador_edit.php";
				}
			}

			if(tabla_operacion=="secciones_ine_ciudadanos_programas_apoyos"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/secciones_ine_ciudadanos_programas_apoyos/secciones_ine_ciudadanos_programas_apoyos_validador_add.php";
				}else{
					var ruta = "importacionesSistema/secciones_ine_ciudadanos_programas_apoyos/secciones_ine_ciudadanos_programas_apoyos_validador_edit.php";
				}
			}

			if(tabla_operacion=="secciones_ine_grupos"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/secciones_ine_grupos/secciones_ine_grupos_validador_add.php";
				}else{
					var ruta = "importacionesSistema/secciones_ine_grupos/secciones_ine_grupos_validador_edit.php";
				}
			}

			if(tabla_operacion=="secciones_ine_ciudadanos_grupos"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/secciones_ine_ciudadanos_grupos/secciones_ine_ciudadanos_grupos_validador_add.php";
				}else{
					var ruta = "importacionesSistema/secciones_ine_ciudadanos_grupos/secciones_ine_ciudadanos_grupos_validador_edit.php";
				}
			}

			if(tabla_operacion=="secciones_ine_actividades"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/secciones_ine_actividades/secciones_ine_actividades_validador_add.php";
				}else{
					var ruta = "importacionesSistema/secciones_ine_actividades/secciones_ine_actividades_validador_edit.php";
				}
			}

			if(tabla_operacion=="preguntas_2022_revocacion_mandato"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/preguntas_2022_revocacion_mandato/preguntas_2022_revocacion_mandato_validador_add.php";
				}else{
					var ruta = "importacionesSistema/preguntas_2022_revocacion_mandato/preguntas_2022_revocacion_mandato_validador_edit.php";
				}
			}

			if(tabla_operacion=="casillas_votos_2022_revocacion_mandato"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/casillas_votos_2022_revocacion_mandato/casillas_votos_2022_revocacion_mandato_validador_add.php";
				}else{
					var ruta = "importacionesSistema/casillas_votos_2022_revocacion_mandato/casillas_votos_2022_revocacion_mandato_validador_edit.php";
				}
			}

			if(tabla_operacion=="casillas_preguntas_2022_revocacion_mandato"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/casillas_preguntas_2022_revocacion_mandato/casillas_preguntas_2022_revocacion_mandato_validador_add.php";
				}else{
					var ruta = "importacionesSistema/casillas_preguntas_2022_revocacion_mandato/casillas_preguntas_2022_revocacion_mandato_validador_edit.php";
				}
			}

			if(tabla_operacion=="supervisores"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/supervisores/supervisores_validador_add.php";
				}else{
					var ruta = "importacionesSistema/supervisores/supervisores_validador_edit.php";
				}
			}

			if(tabla_operacion=="empresas_adjudicadas"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/empresas_adjudicadas/empresas_adjudicadas_validador_add.php";
				}else{
					$("#mensaje").html("No disponible.");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
					var ruta = "importacionesSistema/empresas_adjudicadas/empresas_adjudicadas_validador_edit.php";
				}
			}


			if(tabla_operacion=="secciones_ine_giras"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/secciones_ine_giras/secciones_ine_giras_validador_add.php";
				}else{
					var ruta = "importacionesSistema/secciones_ine_giras/secciones_ine_giras_validador_edit.php";
				}
			}

			if(tabla_operacion=="ejes_gobierno"){
				if(tipo_operacion==1){
					var ruta = "importacionesSistema/ejes_gobierno/ejes_gobierno_validador_add.php";
				}else{
					var ruta = "importacionesSistema/ejes_gobierno/ejes_gobiernos_validador_edit.php";
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
						<font style="font-size: 25px;">Importación Inicial</font>
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