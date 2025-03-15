<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/secciones_ine.php";
	include '../functions/usuario_permisos.php';
	@session_start();
	$_SESSION['Paguinasub']="seccionesIneCiudadanosCampanasSMSProgramadas/index.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_campanas_sms_programadas',$_COOKIE["id_usuario"]);
	?>
	<title>Ciuadadanos Campañas Programadas</title>
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
			Ciuadadanos Campañas SMS Programadas
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
				if( $moduloAccionPermisos['insertxx'] || $moduloAccionPermisos['allxxx']){
					?>
					<input type="button" value="Nuevo Ciudadano Correo" onClick="add();"> 
					<?php
				}
			?>
		</div>
		<br><br>
		<div style="padding: 10px 0px 0px 0px"><?php include "filtros.php"; ?></div>
		<div style="float: right; width: 100%; text-align: left;"> 
		<?php
			if( $moduloAccionPermisos['download'] || $moduloAccionPermisos['all']){
				?>
				<input type="button" value="Excel Encuestas" onClick="downloadExcel();"> 
				<?php
			}
			?>
		</div>
		<div style="clear: both;"></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>