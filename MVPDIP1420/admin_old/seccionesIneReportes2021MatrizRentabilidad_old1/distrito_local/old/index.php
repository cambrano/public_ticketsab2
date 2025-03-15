<?php
	@session_start();
	include __DIR__."/../../functions/security.php";
	include __DIR__."/../../functions/redirect_security.php";
	include __DIR__."/../../functions/distritos_locales_parametros.php";
	include __DIR__."/../../functions/partidos_2021.php";
	include __DIR__."/../../functions/distritos_locales.php";
	include __DIR__."/../../functions/secciones_ine_parametros.php";
	include __DIR__."/../../functions/secciones_ine.php";
	include '../../functions/usuario_permisos.php';
	$_SESSION['Paguinasub']="seccionesIneReportes2021MatrizRentabilidadMatrizRentabilidad/distrito_local/index.php";
	unset($_SESSION['partido_ganador_id']);
	unset($_SESSION['id_seccion_ine']);


	if(!empty($_GET['id'])){
		$id_distrito_local=$_GET['id'];
		$_SESSION['id_distrito_local']=$id_distrito_local;
	}else{
		$id_distrito_local=$_SESSION['id_distrito_local']; 
	}
	//$id_distrito_local=15;
	

	$distrito_localDatos = distrito_localDatos($id_distrito_local);
	function truncar($numero, $digitos){
		$truncar = 10**$digitos;
		return intval($numero * $truncar) / $truncar;
	}
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','cartografias_distritos_locales_2021',$_COOKIE["id_usuario"]);

	$tipo = 1;
	$ano = 2022;

?>
	<title>Secciones INE</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> /
		<?php
		if($tipo_uso_plataforma=='distrito_local'){
			echo '<div class="submenux" onclick="subConfiguracionDistritosLocalesSeccionesIneReportes2021()">Secciones INE Reportes '.$ano.'</div> / ';
		}else{
			echo '<div class="submenux" onclick="subConfiguracionDistritosLocalesReportes2021()">Distritos Locales Reporte '.$ano.'</div> /';
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
			Matriz de Rentabilidad Electoral <?= $ano ?>
		</label><br>
		<label class="tituloForm">
			<font style="font-weight: initial;font-size: 15px">Distrito Local:</font> <?= $distrito_localDatos['numero']; ?>
		</label><br>
		<?php
			if( $moduloAccionPermisos['download'] || $moduloAccionPermisos['all']){
				?>
				<input type="button" value="Excel Matriz de Rentabilidad Electoral" onClick="downloadExcel();"> 
				<?php
			}
		?>
		<br><br>
		<div><?php include "totales.php"; ?></div> 
		<div style="clear: both;"></div>
		<div id="mapaLoad">
			<?php include "mapa.php"; ?>
		</div> 
		<div style="padding: 10px 0px 0px 0px"><?php include "filtros1.php"; ?></div>
		<div id="dataTable">
			<?php include "table1.php"; ?>
		</div> 
	</div>