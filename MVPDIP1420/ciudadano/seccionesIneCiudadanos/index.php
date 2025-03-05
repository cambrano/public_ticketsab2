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
	include '../functions/switch_operaciones.php';
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/municipios.php";
	include __DIR__."/../functions/localidades.php";
	include __DIR__."/../functions/programas_apoyos.php";
	include __DIR__."/../functions/tipos_ciudadanos.php";
	include __DIR__."/../functions/tipos_categorias_ciudadanos.php";
	@session_start();
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
		<div style="padding: 10px 0px 0px 0px"><?php include "filtros.php"; ?></div>
		<div style="clear: both;"></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>