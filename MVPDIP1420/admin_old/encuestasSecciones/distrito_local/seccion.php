<?php
	@session_start(); 
	$_SESSION['Paguinasub']="encuestasSecciones/distrito_local/seccion.php";
	$id_distrito_local=$_SESSION['id_distrito_local'];
	$id_encuesta=$_SESSION['id_encuesta'];
	$id_seccion_ine=$_SESSION['id_seccion_ine'];
	if(!empty($_GET['id'])){
		$id_seccion_ine=$_GET['id'];
		$_SESSION['id_seccion_ine'] = $id_seccion_ine;
	}else{
		$id_seccion_ine=$_SESSION['id_seccion_ine']; 
	}
	if($_GET['refresh']==1){
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		die;
	}
	include __DIR__."/../../functions/security.php";
	include __DIR__."/../../functions/redirect_security.php";
	include __DIR__."/../../functions/distritos_locales_parametros.php";
	include __DIR__."/../../functions/partidos_2021.php";
	include __DIR__."/../../functions/distritos_locales.php";
	include __DIR__."/../../functions/secciones_ine.php";
	include '../../functions/usuario_permisos.php';
	$id_encuesta=$_SESSION['id_encuesta']; 
	$distrito_localDatos = distrito_localDatos($id_distrito_local);
	$seccion_ineDatos = seccion_ineDatos($id_seccion_ine);

	function truncar($numero, $digitos){
		$truncar = 10**$digitos;
		return intval($numero * $truncar) / $truncar;
	}
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','encuestas',$_COOKIE["id_usuario"]);
?>
	<title>Secciones INE</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / 
		<div class="submenux" onclick="subEncuestas()">Encuestas</div> /
		<?php
		if($tipo_uso_plataforma =='all'){
			echo '<div class="submenux" onclick="subEncuestasDistritosLocales()">Encuestas Distritos Locales</div> /';
		}
		?>
		<div class="submenux" onclick="subEncuestasDistritoLocal()">Encuestas Distrito Local </div> /
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
			Encuestas
		</label><br>
		<label class="tituloForm">
			<font style="font-weight: initial;font-size: 15px">Distrito Local:</font> <?= $distrito_localDatos['numero']; ?>
		</label><br>
		<label class="tituloForm">
			<font style="font-weight: initial;font-size: 15px">Sección:</font> <?= $seccion_ineDatos['numero']; ?>
		</label><br>
		<div><?php include 'totales.php'; ?></div> 
		<div style="clear: both;"></div>
		<div style="padding: 10px 0px 0px 0px"><?php include "filtros_seccion.php"; ?></div>
		<div style="float: right; width: 100%; text-align: left;"> 
		<?php
			if( $moduloAccionPermisos['download'] || $moduloAccionPermisos['all']){
				?>
				<input type="button" value="Excel Encuestas" onClick="downloadExcel();"> 
				<?php
			}
			?>
		</div>
		<div style="clear: both;"></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>