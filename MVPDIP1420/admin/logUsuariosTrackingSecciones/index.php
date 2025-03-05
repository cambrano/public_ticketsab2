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
	include __DIR__."/../functions/log_usuarios_tracking_secciones.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/tipos_ciudadanos.php";
	include '../functions/usuario_permisos.php';
	include '../functions/usuarios.php';

	@session_start();
	$moduloAccionPermisos = moduloAccionPermisos('security','tracking_usuarios',$_COOKIE["id_usuario"]);
	?>
	<title>Usuarios Tracking</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionDiaD()">Día D</div> / 
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
			Usuarios Tracking Secciones
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