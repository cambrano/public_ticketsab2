<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/encuestas.php";
	include '../functions/usuario_permisos.php';
	@session_start();
	$_SESSION['Paguinasub']="cuestionarios/index.php";
	unset($_SESSION['paguinaId']);
	if(!empty($_GET['cot'])){
		$id_encuesta=$_GET['cot'];
		$_SESSION['id_encuesta']=$id_encuesta;
	}else{
		$id_encuesta=$_SESSION['id_encuesta']; 
	}
	if($id_encuesta!=""){
		echo $redirectSecurity=redirectSecurity($id_encuesta,'encuestas','encuestas','index');
		if($redirectSecurity!=""){
			die;
		}
	}else{
		echo $redirectSecurity=redirectSecurity($id_encuesta,'encuestas','encuestas','index');
		if($redirectSecurity!=""){
			die;
		}
	}
	$id_encuesta;

	if($id_encuesta!=""){
		$id_encuesta;
		$encuestaDatos = encuestaDatos($id_encuesta);
		$nombre = $encuestaDatos['nombre'];
	}
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','encuestas',$_COOKIE["id_usuario"]);

?>
	<title>Cuestionarios</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / 
		<div class="submenux" onclick="subEncuestas()">Encuestas</div> <br>
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
		<h2><?= $nombre ?> </h2>
		<label class="tituloForm">
			Cuestionarios
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
				if( $moduloAccionPermisos['insert'] || $moduloAccionPermisos['all']){
					?>
					<input type="button" value="Nueva Pregunta" onClick="add();"> 
					<?php
				}
				if( $moduloAccionPermisos['download'] || $moduloAccionPermisos['all']){
					?>
					<input type="button" value="PDF Cuestionario" onClick="printPdf('<?= $id_encuesta ?>');"> 
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