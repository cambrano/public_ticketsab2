<?php
	@session_start();
	include __DIR__."/../../functions/security.php";
	include '../../functions/usuario_permisos.php';
	include '../../functions/tool_xhpzab.php';
	include '../../functions/efs.php';
	if($tipo_uso_plataforma=='all'){
		if(!empty($_GET['id'])){
			$id_municipio = $_GET['id'];
			setcookie("paguinaId_1",encrypt_ab_check($id_municipio), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		}else{
			$id_municipio = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
		}
		$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','cartografias_distritos_locales_2024',$_COOKIE["id_usuario"]);
	}elseif($tipo_uso_plataforma=='municipio' || $forzar_forzar_distrito_local=='true'){
		$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','cartografias_distritos_locales_2024',$_COOKIE["id_usuario"]);
	}else{
		$moduloAccionPermisos=null;
	}
	if($_GET['refresh']==1){
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		die;
	}
	include __DIR__."/../../functions/redirect_security.php";
	include __DIR__."/../../functions/municipios_parametros.php";
	include __DIR__."/../../functions/partidos_2024.php";
	include __DIR__."/../../functions/municipios.php";
	include __DIR__."/../../functions/secciones_ine_parametros.php";
	include __DIR__."/../../functions/secciones_ine.php";
	include __DIR__."/../../functions/localidades_secciones_ine.php";
	include __DIR__."/../../functions/secciones_ine_colonias.php";
	include __DIR__."/../../functions/tools.php";
	include __DIR__."/../../functions/configuracion_matriz_rentabilidad_secciones_ine_2024.php";
	include __DIR__."/../../functions/elecciones.php";
	include __DIR__."/../../functions/tipos_giras.php";
	$elecciones = eleccionesModulo('2024');
	$municipioNombre = municipioNombre($id_municipio);
	function truncar($numero, $digitos){
		$truncar = 10**$digitos;
		return intval($numero * $truncar) / $truncar;
	}
	$tipo = 1;
	$ano = $elecciones['distritos_locales'];
?>
	<script type="text/javascript">
		$('html, body').animate({ scrollTop: $("#body").offset().top }, 1);
	</script>
	<title>Secciones INE</title>
	<div id="bodymanager" class="bodymanager">
		<?php
		if($_COOKIE['subPage']==1){
			echo '<div class="submenux" onclick="subConfiguracionDiaD()">Día D</div> / ';
		}else{
			echo '<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / ';
		}
		if($tipo_uso_plataforma=='all'){
			echo '<div class="submenux" onclick="subConfiguracionF_Distrito_LocalReportes2024()">F_Distrito_Local Reporte '.$ano.'</div> / ';
			echo '<div class="submenux" onclick="subConfiguracionF_Distrito_LocalSeccionesIneReportes2024()">Secciones INE Reportes '.$ano.'</div> / ';
		}else{
			echo '<div class="submenux" onclick="subConfiguracionF_Distrito_LocalSeccionesIneReportes2024()">Secciones INE Reportes '.$ano.'</div> / ';
		}
		?>
		<br>
		<div id="mensaje" class="mensajeSolo" ></div>
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
		<label class="tituloForm">
			Análisis De La Votación del <?= $ano ?>
		</label><br>
		<label class="tituloForm">
			<?php 
				$seccion_ineDistritosLocalesNumero = seccion_ineDistritosLocalesNumero($id_municipio);
			?>
			<font style="font-weight: initial;font-size: 15px">Distritos Locales <?= $seccion_ineDistritosLocalesNumero ?> en Municipio:</font> <?= $municipioNombre; ?>
		</label><br>
		<div><?php include "generar_periodo_form.php"; ?></div> 
		<div><?php include "totales.php"; ?></div> 
		<div style="clear: both;"></div>
		<div><?php include "mostrar_mapa.php"; ?></div> 
		<div id="mapaLoad">
			<?php include "mapa.php"; ?>
		</div> 
		<div>
			<?php include "semaforo.php" ?>
		</div>
		<div style="padding: 10px 0px 0px 0px"><?php include "filtros.php"; ?></div>
		<div style="clear: both;"></div>
		<div id="graficasLoad" style="width:100%">
			<?php include "graficas.php"; ?>
		</div> 
		<div style="clear: both;"></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>