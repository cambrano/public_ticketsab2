<?php
	@session_start();
	include __DIR__."/../../functions/security.php";
	include __DIR__."/../../functions/redirect_security.php";
	include __DIR__."/../../functions/municipios_parametros.php";
	include __DIR__."/../../functions/partidos_2021.php";
	include __DIR__."/../../functions/municipios.php";
	include __DIR__."/../../functions/secciones_ine_parametros.php";
	include __DIR__."/../../functions/secciones_ine.php";
	include '../../functions/usuario_permisos.php';
	$_SESSION['Paguinasub']="seccionesIneReportes2021MatrizRentabilidad/municipio/index.php";
	unset($_SESSION['partido_ganador_id']);
	unset($_SESSION['id_seccion_ine']);


	if(!empty($_GET['id'])){
		$id_municipio=$_GET['id'];
		$_SESSION['id_municipio'] = $id_municipio;
	}else{
		$id_municipio=$_SESSION['id_municipio']; 
	}
	//$id_municipio='342';
	$id_municipio;
	$municipioNombre = municipioNombre($id_municipio);

	function truncar($numero, $digitos){
		$truncar = 10**$digitos;
		return intval($numero * $truncar) / $truncar;
	}
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','cartografias_municipios_2021',$_COOKIE["id_usuario"]);

?>
	<title>Secciones INE</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / 
		<?php
		if($tipo_uso_plataforma=='municipio'){
			echo '<div class="submenux" onclick="subConfiguracionMunicipiosSeccionesIneReportes2021()">Secciones INE Reportes 2021</div> / ';
		}else{
			echo '<div class="submenux" onclick="subConfiguracionMunicipiosReportes2021()">Municipios Reporte 2021</div> /';
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
			Matriz de rentabilidad electoral 2021
		</label><br>
		<label class="tituloForm">
			<font style="font-weight: initial;font-size: 15px">Municipio:</font> <?= $municipioNombre; ?>
		</label><br>
		<?php
			if( $moduloAccionPermisos['download'] || $moduloAccionPermisos['all']){
				?>
				<input type="button" value="Excel Matriz de Rentabilidad Electoral" onClick="downloadExcel();"> 
				<?php
			}
		?>
		<br><br>
		<div><?php include "totales1.php"; ?></div> 
		<div style="clear: both;"></div>
		<div id="mapaLoad">
			<?php include "mapa.php"; ?>
		</div> 
		<div style="padding: 10px 0px 0px 0px"><?php include "filtros.php"; ?></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>