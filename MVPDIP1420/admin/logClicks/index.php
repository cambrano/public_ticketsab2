<?php
	if($_GET['refresh']==1){
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		die;
	}
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/log_clicks.php";
	include '../functions/usuario_permisos.php';
	@session_start();
	$reload_mapa = 1;
	$moduloAccionPermisos = moduloAccionPermisos('security','tracking_page',$_COOKIE["id_usuario"]);
	?>
	<title>Tracking Pages</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subSecurity()">Tracking GPS</div> / <br>
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
			Tracking Pages
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
				if( $moduloAccionPermisos['download'] || $moduloAccionPermisos['all']){
					?>
					<!--<input type="button" value="Exportar Excel" onClick="exportar();"><br>-->
					<?php
				}
			?>
		</div>
		<div><?php include "filtros.php"; ?></div>
		<div style="clear: both;"></div>
		<div id="mapaLoad">
		</div> 
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>