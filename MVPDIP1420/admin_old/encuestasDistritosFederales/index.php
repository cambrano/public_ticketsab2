<?php
	@session_start();
	$_SESSION['Paguinasub']="encuestasDistritosFederales/index.php";
	if(!empty($_GET['cot'])){
		$id_encuesta=$_GET['cot'];
		$_SESSION['id_encuesta']=$id_encuesta;
	}else{
		$id_encuesta=$_SESSION['id_encuesta'];
	}
	unset($_SESSION['paguinaId']);
	if($_GET['refresh']==1){
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		die;
	}
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/encuestas.php";
	include __DIR__."/../functions/distritos_federales_parametros.php";
	include __DIR__."/../functions/distritos_federales.php";
	include __DIR__."/../functions/secciones_ine.php";
	include '../functions/usuario_permisos.php';

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
	function truncar($numero, $digitos){
	    $truncar = 10**$digitos;
	    return intval($numero * $truncar) / $truncar;
	}
?>
	<title>Encuestas Municipios</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / 
		<div class="submenux" onclick="subEncuestas()">Encuestas</div> /
		<br>
		<div id="mensaje" class="mensajeSolo" ></div>
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
			Encuestas Distritos Federales
		</label><br>
		<div><?php include 'totales.php'; ?></div> 
		<div style="clear: both;"></div>
		<div id="mapaLoad">
			<?php include "mapa.php"; ?>
		</div> 
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