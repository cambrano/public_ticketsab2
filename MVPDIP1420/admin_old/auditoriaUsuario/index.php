<?php
	include __DIR__."/../functions/security.php";
	@session_start();  
	$_SESSION['Paguinasub']="auditoriaUsuario/index.php";
	?>
	<title>Auditoria Usuario</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracion()">Configuración</div> / <br>
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<label class="tituloForm">
			<font style="font-size: 20px;">Auditoria de Usuarios</font>
		</label><br>

		<div><?php include "filtros.php"; ?></div>
		
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div>
	</div>

<?php

	include '../functions/usuario_permisos.php';
	if(moduloPermiso('auditoria_usuarios','configuracion',$_COOKIE["id_usuario"])==true){
		$permiso_usuario_action = true;
	}else{
		$permiso_usuario_action = false;
		?>
		<script type="text/javascript">
			document.getElementById("mensaje").classList.add("mensajeError");
			//document.getElementById("sumbmit").disabled = false;
			$("#mensaje").html("No tiene permiso.");
			$("#homebody").load('home.php');
		</script>
		<?php 
		die;
	}