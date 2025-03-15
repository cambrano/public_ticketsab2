<?php
	@session_start();
	unset($_SESSION['reporte_Sistema']);
	$_SESSION['Paguinasub']="seccionesIneReportes2018MatrizRentabilidad/distrito_local/index.php";
	include __DIR__."/../../functions/security.php";
	include '../../functions/usuario_permisos.php';
	if($tipo_uso_plataforma=='all'){
		if(!empty($_GET['id'])){
			$id_distrito_local=$_GET['id'];
			$_SESSION['id_distrito_local']=$id_distrito_local;
		}else{
			$id_distrito_local=$_SESSION['id_distrito_local']; 
		}
		$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','cartografias_distritos_locales_2018',$_COOKIE["id_usuario"]);
	}elseif($tipo_uso_plataforma=='distrito_local'){
		$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','cartografias_distritos_locales_2018',$_COOKIE["id_usuario"]);
	}else{
		$moduloAccionPermisos=null;
	}
	$_SESSION['paguinaId'] = $id_distrito_local;
	if($_GET['refresh']==1){
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		die;
	}
	include __DIR__."/../../functions/redirect_security.php";
	include __DIR__."/../../functions/distritos_locales_parametros.php";
	include __DIR__."/../../functions/partidos_2018.php";
	include __DIR__."/../../functions/distritos_locales.php";
	include __DIR__."/../../functions/secciones_ine_parametros.php";
	include __DIR__."/../../functions/secciones_ine.php";
	include __DIR__."/../../functions/tools.php";
	include __DIR__."/../../functions/municipios.php";
	include __DIR__."/../../functions/configuracion_matriz_rentabilidad_secciones_ine_2018.php";

	include __DIR__."/../../functions/elecciones.php";
	$elecciones = eleccionesModulo('2018');

	unset($_SESSION['partido_ganador_id']);
	unset($_SESSION['id_seccion_ine']);
	$distrito_localDatos = distrito_localDatos($id_distrito_local);
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
			if($_SESSION['dia_d']==1){
				echo '<div class="submenux" onclick="subConfiguracionDiaD()">Día D</div> / ';
			}else{
				echo '<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / ';
			}
		if($tipo_uso_plataforma=='all'){
			echo '<div class="submenux" onclick="subConfiguracionDistritosLocalesReportes2018()">Distritos Locales Reporte '.$ano.'</div> / ';
			echo '<div class="submenux" onclick="subConfiguracionDistritosLocalesSeccionesIneReportes2018()">Secciones INE Reportes '.$ano.'</div> / ';
		}else{
			echo '<div class="submenux" onclick="subConfiguracionDistritosLocalesSeccionesIneReportes2018()">Secciones INE Reportes '.$ano.'</div> / ';
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
					$("#homebody").load('home.php');
				</script>
				<?php
				die;
			}
		?>
		<label class="tituloForm">
			Análisis De La Votación del <?= $ano ?>
		</label><br>
		<label class="tituloForm">
			<font style="font-weight: initial;font-size: 15px">Distrito Local:</font> <?= $distrito_localDatos['numero']; ?>
		</label><br>
		<div><?php include "generar_periodo_form.php"; ?></div> 
		<div><?php include "totales.php"; ?></div> 
		<div style="clear: both;"></div>
		<div><?php include "mostrar_mapa.php"; ?></div> 
		<div id="mapaLoad">
			<?php include "mapa.php"; ?>
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