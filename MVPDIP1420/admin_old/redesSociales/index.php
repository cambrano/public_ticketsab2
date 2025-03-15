<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	@session_start();
	$_SESSION['Paguinasub']="redesSociales/index.php";
	$moduloAccionPermisos = moduloAccionPermisos('perfiles','redes_sociales',$_COOKIE["id_usuario"]);
	?>
	<title>Redes Sociales</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPerfilesPersonas()">Configuración Perfiles Personas</div> / <br>
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
			Redes Sociales
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
				if( $moduloAccionPermisos['insert'] || $moduloAccionPermisos['all']){
					?>
					<input type="button" value="Nueva Red Social" onClick="add();"> 
					<?php
				}
			?>
		</div>
		<br><br>
		<div> <?php include "filtros.php"; ?></div>
		<div style="clear: both;"></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>