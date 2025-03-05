<?php
	include __DIR__.'/../functions/security.php';
	include '../functions/usuario_permisos.php';
	@session_start();
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','api_whatsapp',$_COOKIE['id_usuario']);
	?>
	<title>Whatsapp Sin Clasificar</title>
	<div id='bodymanager' class='bodymanager'>
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> <br>
		<div id='mensaje' class='mensajeSolo' ><br></div>
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
		<label class='tituloForm'>
			Whatsapp Sin Clasificar
		</label><br>
		<div style='float: right; width: 100%; text-align: left;'></div>
		<br><br>
		<div> <?php include 'filtros.php'; ?></div>
		<div style='clear: both;'></div>
		<div id='dataTable'>
			<?php include 'table.php'; ?>
		</div> 
	</div>