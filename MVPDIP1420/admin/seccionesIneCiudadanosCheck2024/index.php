<?php
	@session_start();
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
	include __DIR__."/../functions/secciones_ine_ciudadanos_check_2024.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../functions/cuarteles.php";
	include __DIR__."/../functions/localidades.php";
	include __DIR__."/../functions/municipios.php";
	include __DIR__."/../functions/partidos_legados.php";
	include __DIR__."/../functions/distritos_locales.php";
	include __DIR__."/../functions/distritos_federales.php";

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos',$_COOKIE["id_usuario"]);
	?>
	<title>Ciudadanos</title>
	<div id="bodymanager" class="bodymanager">
		<?php
			if($_COOKIE['subPage']==1){
				echo '<div class="submenux" onclick="subConfiguracionDiaD()">Día D</div> / ';
			}else{
				echo '<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / ';
			}
		?>
		<br>
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<?php
			if(empty($moduloAccionPermisos)){
				?>
				<script type="text/javascript">
					document.getElementById("mensaje").classList.add("mensajeError");
					$("#mensaje").html("No tiene permiso");
					urlink="home.php";
					dataString = 'urlink='+urlink; 
					$.ajax({
						type: "POST",
						url: "functions/backarray.php",
						data: dataString,
						success: function(data) { 	}
					});
					$("#homebody").load(urlink);
				</script>
				<?php
				die;
			}
		?>
		<label class="tituloForm">
			Ciudadanos Checks
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
					if( $moduloAccionPermisos['insert'] || $moduloAccionPermisos['all']){
						?>
						<?php
					}
					if( $moduloAccionPermisos['download'] || $moduloAccionPermisos['all']){
						?>
						<input type="button" value="Excel Ciudadano" onClick="downloadExcel();"> 
						<?php
					}
				?>
		</div> 
		<br><br>
		<div><?php include 'totales.php'; ?></div> 
		<div id="mapaLoad"></div> 
		<div> <?php include "filtros.php"; ?></div>
		<div style="clear: both;"></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>