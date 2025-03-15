<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	@session_start();
	$_SESSION['Paguinasub']="preguntas2022RevocacionMandato/index.php";
	unset($_SESSION['paguinaId']);
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','preguntas_2022_revocacion_mandato',$_COOKIE["id_usuario"]);
	?>
	<title>Partidos</title>
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
			Preguntas Consulta 2022
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
				if( $moduloAccionPermisos['insert'] || $moduloAccionPermisos['all']){
					?>
					<input type="button" value="Nueva Pregunta" onClick="add();"> 
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