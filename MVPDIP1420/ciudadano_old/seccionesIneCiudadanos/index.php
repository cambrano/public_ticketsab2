<?php
	include __DIR__."/../functions/security.php";
	include '../functions/switch_operaciones.php';
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/programas_apoyos.php";
	@session_start();
	$_SESSION['Paguinasub']="seccionesIneCiudadanos/index.php";


	$switch_operacionesPermisos = switch_operacionesPermisos();

?>
	<title>Ciudadanos</title>
	<div id="bodymanager" class="bodymanager">
		<div id="mensaje" class="mensajeSolo" ></div>
		<?php
			if($switch_operacionesPermisos['registro']==false && $switch_operacionesPermisos['evaluacion']==false){
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
			Amigos
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
				if($switch_operacionesPermisos['registro']==true){
					?>
					<input type="button" value="Nuevo Amigo" onClick="add();"> 
					<?php
				}
				?>
		</div>
		<br><br>
		<div><?php include "filtros.php"; ?></div>
		<div style="clear: both;"></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>