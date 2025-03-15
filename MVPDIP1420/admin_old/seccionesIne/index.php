<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/secciones_ine_parametros.php";
	include '../functions/usuario_permisos.php';
	unset($_SESSION['clave']);
	unset($_SESSION['numero']);


	@session_start(); 
	$_SESSION['Paguinasub']="seccionesIne/index.php";
	unset($_SESSION['reporte_Sistema']);
	$_GET['cot'];
?>
	<title>Secciones INE</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / 
		<br>
		<div id="mensaje" class="mensajeSolo" ></div>
		<label class="tituloForm">
			Secciones INE
		</label><br>
		<?php
			if( moduloAccion('sistema_unico_beneficiarios','secciones_ine',$_COOKIE["id_usuario"],'Insert')==true || 
				moduloAccion('sistema_unico_beneficiarios','secciones_ine',$_COOKIE["id_usuario"],'All')==true){
				?>
				<input type="button" value="Nueva Sección" onClick="add();"><br>
				<?php
			}
		?>
		<br><br>
		<div><?php include "filtros.php"; ?></div>
		
		<div style="clear: both;"></div>
		<div id="mapaLoad">
			<?php include "mapa.php"; ?>
		</div> 
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>

	<?php
		if(
			moduloPermiso('secciones_ine','sistema_unico_beneficiarios',$_COOKIE["id_usuario"])
		){
			$permiso_usuario_action = true;
		}else{
			$permiso_usuario_action = false;
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