<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/log_usuarios_tracking.php";
	include '../functions/usuario_permisos.php';
	@session_start();
	$_SESSION['Paguinasub']="logUsuariosTracking/index.php";
	$moduloAccionPermisos = moduloAccionPermisos('security','tracking_usuarios',$_COOKIE["id_usuario"]);
	?>
	<title>Usuarios Tracking</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subSecurity()">Tracking GPS</div> / <br>
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
			Usuarios Tracking
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
			<?php include "mapa.php"; ?>
		</div> 
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>