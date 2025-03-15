<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/partidos_legados.php";

	@session_start();
	$_SESSION['Paguinasub']="militantesPartidosTotales/index.php";
	if(!empty($_GET['cot'])){
		$id_partido_legado=$_GET['cot'];
		$_SESSION['id_partido_legado']=$id_partido_legado;
	}else{
		$id_partido_legado=$_SESSION['id_partido_legado'];
	}

	if($id_partido_legado!=""){
		$id_partido_legado;
		$partido_legadoDatos = partido_legadoDatos($id_partido_legado);
		$nombre_completo = $partido_legadoDatos['nombre_corto'];
		if($nombre_completo==""){
			echo $redirectSecurity=redirectSecurity('','partidos_legados','partidosLegados','index');
			if($redirectSecurity!=""){
				die;
			}
		}
	}else{
		echo $redirectSecurity=redirectSecurity($id_partido_legado,'partidos_legados','partidosLegados','index');
		if($redirectSecurity!=""){
			die;
		}
	}
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','militantes_partidos',$_COOKIE["id_usuario"]);
?>
	<title>Programas Apoyos</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / 
		<div class="submenux" onclick="subPartidosLegados()">Partidos Legados</div> / 
		<br>
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
		<h2><?= $nombre_completo ?> </h2>
		<label class="tituloForm">
			Militantes Partidos
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
				if( $moduloAccionPermisos['insert'] || $moduloAccionPermisos['all']){
					?>
					<input type="button" value="Nuevo partido militante" onClick="add();"> 
					<?php
				}
				if( $moduloAccionPermisos['download'] || $moduloAccionPermisos['all']){
					?>
					<input type="button" value="Excel Militantes" onClick="downloadExcel();"> 
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