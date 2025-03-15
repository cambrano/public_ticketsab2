<?php
	@session_start();
	$_SESSION['Paguinasub']="municipiosReportes2021/index.php";
	if($_GET['refresh']==1){
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		die;
	}
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/municipios_parametros.php";
	include __DIR__."/../functions/partidos_2021.php";
	include __DIR__."/../functions/municipios.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/elecciones.php";
	$elecciones = eleccionesModulo('2021');

	unset($_SESSION['id_municipio']);
	unset($_SESSION['partido_ganador_id']);

	include "../functions/security.php"; 
	function truncar($numero, $digitos){
		$truncar = 10**$digitos;
	    return intval($numero * $truncar) / $truncar;
	}

	unset($_SESSION['reporte_Sistema']);
	$_GET['cot'];
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','cartografias_municipios_2021',$_COOKIE["id_usuario"]);

	$tipo = 0;
	$ano = $elecciones['municipios'];
?>
	<script type="text/javascript">
		$('html, body').animate({ scrollTop: $("#body").offset().top }, 1);
	</script>
	<title>Municipios Reporte</title>
	<div id="bodymanager" class="bodymanager">
		<?php
			if($_SESSION['dia_d']==1){
				echo '<div class="submenux" onclick="subConfiguracionDiaD()">Día D</div> / ';
			}else{
				echo '<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / ';
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
			Municipios Reporte <?= $ano ?>
		</label><br>
		<br><br>
		<div><?php include "totales.php"; ?></div> 
		<div style="clear: both;"></div>
		<div id="mapaLoad">
			<?php include "mapa.php"; ?>
		</div> 
		<div style="padding: 10px 0px 0px 0px"><?php include "filtros.php"; ?></div>
		<div style="clear: both;"></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>