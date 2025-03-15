<?php
	@session_start(); 
	include '../functions/tool_xhpzab.php';
	if($_GET['refresh']==1){
		if($_GET['cot']!=""){
			setcookie("paguinaId_1",encrypt_ab_check($_GET['cot']), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
			///Agregamos la cookie para que piense la plataforma que estamo en secciones_ine_ciudadanos_seccion
			setcookie("subPage",1, array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		}
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		die;
	}else{
		$id_seccion_ine = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	}
	$reload_mapa = 1;
	

	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/secciones_ine_parametros.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../functions/tipos_categorias_ciudadanos.php";
	include __DIR__."/../functions/tipos_ciudadanos.php";
	include __DIR__."/../functions/cuarteles.php";
	include __DIR__."/../functions/localidades.php";
	include __DIR__."/../functions/municipios.php";
	include __DIR__."/../functions/partidos_legados.php";
	include __DIR__."/../functions/distritos_locales.php";
	include __DIR__."/../functions/distritos_federales.php";
	include __DIR__."/../functions/programas_apoyos.php";
	include __DIR__."/../functions/secciones_ine_grupos.php";
	include __DIR__."/../functions/plataformas.php";

	@session_start();
	$seccion_ineDatos = seccion_ineDatos($id_seccion_ine);
	$latitud = $seccion_ineDatos['latitud'];
	$longitud = $seccion_ineDatos['longitud'];
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos',$_COOKIE["id_usuario"]);
	$moduloAccionPermisos_categoprias = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_categorias',$_COOKIE["id_usuario"]);
	$moduloAccionPermisos_encuestas = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_encuestas',$_COOKIE["id_usuario"]);
	$moduloAccionPermisos_seguimientos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_seguimientos',$_COOKIE["id_usuario"]);
	$moduloAccionPermisos_programas_apoyos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_programas_apoyos',$_COOKIE["id_usuario"]);
	$moduloAccionPermisos_giras = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_giras',$_COOKIE["id_usuario"]);
	$moduloAccionPermisos_militantes_partidos = moduloAccionPermisos('sistema_unico_beneficiarios','militantes_partidos',$_COOKIE["id_usuario"]);

	function truncar($numero, $digitos){
		$truncar = 10**$digitos;
	    return number_format(intval($numero * $truncar) / $truncar, 2, '.', ',');
	}
?>
	<title>Ciudadanos</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / 
		<div class="submenux" onclick="subSeccionesIneCiudadanos()">Ciudadanos</div> /
		<br>
		<div id="mensaje" class="mensajeSolo" ></div>
		<?php
			if(empty($moduloAccionPermisos) && empty($moduloAccionPermisos_encuestas) && empty($moduloAccionPermisos_categoprias)  && empty($moduloAccionPermisos_seguimientos
			) && empty($moduloAccionPermisos_programas_apoyos) && empty($moduloAccionPermisos_giras) && $moduloAccionPermisos_militantes_partidos ){
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
		<label class="tituloForm">
			Ciudadanos
		</label><br>
		<label class="tituloForm">
			<font style="font-weight: initial;font-size: 15px">Sección</font> <?= $seccion_ineDatos['numero'] ?>
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
					if( $moduloAccionPermisos['insert'] || $moduloAccionPermisos['all']){
						?>
						<input id="btn_nuevo_seccion_ine_ciudadano" type="button" value="Nuevo Ciudadano" onClick="add();"> 
						<?php
					}
					if( $moduloAccionPermisos['download'] || $moduloAccionPermisos['all']){
						?>
						<input style="opacity: 0.6;cursor: not-allowed;pointer-events:none;" id="btn_descargarExcel" type="button" value="Excel Ciudadano" onClick="downloadExcel();"> 
						<?php
					}else{
						?>
						<input style="opacity: 0.6;cursor: not-allowed;pointer-events:none;display: none" id="btn_descargarExcel" type="button" value="Excel Ciudadano"> 
						<?php
					}
				?>
		</div> 
		<?php
		if($moduloAccionPermisos['captura']!=true){
			?>
			<div><?php include 'totales.php'; ?></div> 
			<?php
		}
		?>
		<div style="clear: both;"></div>
		<div style="padding: 10px 0px 0px 0px"><?php include "filtros.php"; ?></div>
		<div style="clear: both;"></div>
		<div id="mapaLoad">
			<?php
				$searchOpciones = $_COOKIE["searchOpcionesSIC"];
				$searchOpciones = json_decode($searchOpciones,true);
				if($searchOpciones['tipo_mapa']=='mapa_calor'){
					include "mapaCalor.php";
				}else{
					include "mapa.php";
				}
			?>
		</div> 
		<div style="clear: both;"></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>