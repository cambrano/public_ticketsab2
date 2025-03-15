<?php
	@session_start(); 
	unset($_SESSION['reporte_Sistema']);
	$_SESSION['Paguinasub']="seccionesIneReportes2022RevocacionMandato/distrito_local/index.php";
	include __DIR__."/../../functions/security.php";
	include '../../functions/usuario_permisos.php';
	if($tipo_uso_plataforma=='all'){
		if(!empty($_GET['id'])){
			$id_distrito_local=$_GET['id'];
			$_SESSION['id_distrito_local']=$id_distrito_local;
		}else{
			$id_distrito_local=$_SESSION['id_distrito_local']; 
		}
		$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','cartografias_distritos_locales_2022_revocacion_mandato',$_COOKIE["id_usuario"]);
	}elseif($tipo_uso_plataforma=='distrito_local'){
		$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','cartografias_distritos_locales_2022_revocacion_mandato',$_COOKIE["id_usuario"]);
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
	include __DIR__."/../../functions/preguntas_2022_revocacion_mandato.php";
	include __DIR__."/../../functions/distritos_locales.php";
	include __DIR__."/../../functions/secciones_ine_parametros.php";
	include __DIR__."/../../functions/secciones_ine.php";
	
	unset($_SESSION['partido_ganador_id']);
	unset($_SESSION['id_seccion_ine']);


	

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
		if($tipo_uso_plataforma=='distrito_local'){
			echo '<div class="submenux" onclick="subConfiguracionDistritosLocalesSeccionesIneReportes2018()">Secciones INE Reportes 2019</div> / ';
		}else{
			echo '<div class="submenux" onclick="subConfiguracionDistritosLocalesReportes2018()">Distritos Locales Reporte 2019</div> /';
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
			Secciones INE 2022 Revocación de mandato
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