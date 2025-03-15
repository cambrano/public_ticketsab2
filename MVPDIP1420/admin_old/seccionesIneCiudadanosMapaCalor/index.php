<?php
	@session_start(); 
	unset( $_SESSION['page_secciones_ine_ciudadanos_seccion']);
	$_SESSION['Paguinasub']="seccionesIneCiudadanosMapaCalor/index.php";
	unset($_SESSION['paguinaId']);
	if($_GET['refresh']==1){
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		die;
	}
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

	@session_start();
	$_SESSION['Paguinasub']="seccionesIneCiudadanosMapaCalor/index.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos',$_COOKIE["id_usuario"]);

	unset($_SESSION['searchTable']);
	unset($_SESSION['reporte_Sistema']);

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
					$("#homebody").load('home.php');
				</script>
				<?php
				die;
			}
		?>
		<label class="tituloForm">
			Ciudadanos Mapa de Calor
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
					if( $moduloAccionPermisos['download'] || $moduloAccionPermisos['all']){
						?>
						<input type="button" value="Excel Ciudadano" onClick="downloadExcel();"> 
						<?php
					}
				?>
		</div>
		<br><br>
		<br><br>
		<div><?php include 'totales.php'; ?></div> 
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