<?php
	@session_start();
	include __DIR__."/../../functions/security.php";
	include __DIR__."/../../functions/redirect_security.php";
	include __DIR__."/../../functions/distritos_federales_parametros.php";
	include __DIR__."/../../functions/partidos_2022.php";
	include __DIR__."/../../functions/distritos_federales.php";
	include __DIR__."/../../functions/secciones_ine_parametros.php";
	include __DIR__."/../../functions/secciones_ine.php";
	include '../../functions/usuario_permisos.php';
	$_SESSION['Paguinasub']="seccionesIneReportes2022/distrito_federal/index.php";
	unset($_SESSION['partido_ganador_id']);
	unset($_SESSION['id_seccion_ine']);


	if($tipo_uso_plataforma=='all'){
		if(!empty($_GET['id'])){
			$id_distrito_federal=$_GET['id'];
			$_SESSION['id_distrito_federal']=$id_distrito_federal;
		}else{
			$id_distrito_federal=$_SESSION['id_distrito_federal']; 
		}
		$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','cartografias_distritos_federales_2022',$_COOKIE["id_usuario"]);
	}elseif($tipo_uso_plataforma=='distrito_federal'){
		$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','cartografias_distritos_federales_2022',$_COOKIE["id_usuario"]);
	}else{
		$moduloAccionPermisos=null;
	}


	$distrito_federalDatos = distrito_federalDatos($id_distrito_federal);
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
			echo '<div class="submenux" onclick="subConfiguracionDistritosFederalesReportes2022()">Distritos Federales Reporte 2022</div> /';
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
			Secciones INE Reportes 2022
		</label><br>
		<label class="tituloForm">
			<font style="font-weight: initial;font-size: 15px">Distrito Federal:</font> <?= $distrito_federalDatos['numero']; ?>
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