<?php
	@session_start(); 
	unset( $_SESSION['page_secciones_ine_ciudadanos_seccion']);
	$_SESSION['Paguinasub']="seccionesIneGiras/index.php";
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
	include __DIR__."/../functions/secciones_ine_giras.php";
	include __DIR__."/../functions/secciones_ine_giras_puntos.php";
	include __DIR__."/../functions/municipios.php";
	include __DIR__."/../functions/localidades.php";
	include __DIR__."/../functions/distritos_locales.php";
	include __DIR__."/../functions/distritos_federales.php";
	include __DIR__."/../functions/tipos_infraestructuras.php";
	unset($_SESSION['reporte_Sistema']['sql']);
	unset($_SESSION['reporte_Sistema']['columnas_nombres']);

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_giras',$_COOKIE["id_usuario"]);
	?>
	<title>Giras</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / <br>
		<div id="mensaje" class="mensajeSolo" ><br></div>
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
			Giras
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
				if( $moduloAccionPermisos['insert'] || $moduloAccionPermisos['all']){
					?>
					<input type="button" value="Nuevo Gira" onClick="add();"> 
					<?php
				}
				if( $moduloAccionPermisos['download'] || $moduloAccionPermisos['all']){
					?>
					<input type="button" value="Excel Giras" onClick="downloadExcel();"> 
					<?php
				}
			?>
		</div>
		<br><br>
		<div id="mapaLoad">
			<?php include "mapa.php"; ?>
		</div>
		<div style="padding: 10px 0px 0px 0px"><?php include "filtros.php"; ?></div>
		<div style="clear: both;"></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>