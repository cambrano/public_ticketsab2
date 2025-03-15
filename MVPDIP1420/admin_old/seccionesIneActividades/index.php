<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/secciones_ine_parametros.php";
	include __DIR__."/../functions/secciones_ine_actividades.php";
	include __DIR__."/../functions/municipios.php";
	include __DIR__."/../functions/localidades.php";
	include __DIR__."/../functions/distritos_locales.php";
	include __DIR__."/../functions/distritos_federales.php";
	include __DIR__."/../functions/tipos_infraestructuras.php";

	@session_start();
	$_SESSION['Paguinasub']="seccionesIneActividades/index.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_actividades',$_COOKIE["id_usuario"]);
	?>
	<title>Actividades</title>
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
			Acciones u Obras
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
				if( $moduloAccionPermisos['insert'] || $moduloAccionPermisos['all']){
					?>
					<input type="button" value="Nuevo Acción u Obra" onClick="add();"> 
					<?php
				}
				if( $moduloAccionPermisos['download'] || $moduloAccionPermisos['all']){
					?>
					<input type="button" value="Excel Acciones u Obras" onClick="downloadExcel();"> 
					<?php
				}
			?>
		</div>
		<br><br>
		<div id="mapaLoad">
			<?php include "mapa.php"; ?>
		</div>
		<div style="padding: 10px 0px 0px 0px"><?php include "filtros.php"; ?></div>
		<div style="clear: both;"></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>