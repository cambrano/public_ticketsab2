<?php
	@session_start();
	include __DIR__."/../../functions/security.php";
	include __DIR__."/../../functions/redirect_security.php";
	include __DIR__."/../../functions/distritos_locales_parametros.php";
	include __DIR__."/../../functions/partidos_2022.php";
	include __DIR__."/../../functions/distritos_locales.php";
	include __DIR__."/../../functions/secciones_ine_parametros.php";
	include __DIR__."/../../functions/secciones_ine.php";
	include '../../functions/usuario_permisos.php';
	$_SESSION['Paguinasub']="seccionesIneReportes2022/distrito_local/index.php";
	unset($_SESSION['partido_ganador_id']);
	unset($_SESSION['id_seccion_ine']);


	if($tipo_uso_plataforma=='all'){
		if(!empty($_GET['id'])){
			$id_distrito_local=$_GET['id'];
			$_SESSION['id_distrito_local']=$id_distrito_local;
		}else{
			$id_distrito_local=$_SESSION['id_distrito_local']; 
		}
		$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','cartografias_distritos_locales_2022',$_COOKIE["id_usuario"]);
	}elseif($tipo_uso_plataforma=='distrito_local'){
		$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','cartografias_distritos_locales_2022',$_COOKIE["id_usuario"]);
	}else{
		$moduloAccionPermisos=null;
	}

	$distrito_localDatos = distrito_localDatos($id_distrito_local);
	function truncar($numero, $digitos){
		$truncar = 10**$digitos;
		return intval($numero * $truncar) / $truncar;
	}

?>
	<title>Secciones INE</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> /
		<?php
		if($tipo_uso_plataforma=='all'){
			echo '<div class="submenux" onclick="subConfiguracionDistritosLocalesReportes2022()">Distritos Locales Reporte 2019</div> /';
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
			Secciones INE Reportes 2019
		</label><br>
		<label class="tituloForm">
			<font style="font-weight: initial;font-size: 15px">Distrito Local:</font> <?= $distrito_localDatos['numero']; ?>
		</label><br>
		<div><?php include "totales.php"; ?></div> 
		<div style="clear: both;"></div>
		<div id="mapaLoad">
			<?php include "mapa.php"; ?>
		</div> 
		<div style="padding: 10px 0px 0px 0px"><?php include "filtros.php"; ?></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>