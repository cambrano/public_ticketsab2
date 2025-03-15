<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/casillas_votos_2024.php";
	include __DIR__."/../functions/secciones_ine.php";
	@session_start();
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','casillas_votos_2024',$_COOKIE["id_usuario"]);
?>
	<title>Casillas Votos 2024 Incidencias</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionReportes()">Reportes</div> / 
		<br>
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<label class="tituloForm">
			Casillas Incidencias
		</label><br>
		<div> <?php include "totales.php"; ?></div>
		<br><br>
		<div> <?php include "filtros.php"; ?></div>
		<div style="clear: both;"></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>