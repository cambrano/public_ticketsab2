<?php
	include __DIR__."/../../functions/security.php";
	include __DIR__."/../../functions/redirect_security.php";
	include __DIR__."/../../functions/municipios_parametros.php";
	include __DIR__."/../../functions/partidos_2021.php";
	include __DIR__."/../../functions/municipios.php";
	include __DIR__."/../../functions/secciones_ine_parametros.php";
	include __DIR__."/../../functions/secciones_ine.php";
	include '../../functions/usuario_permisos.php';
	@session_start();
	$_SESSION['Paguinasub']="casillasVotosReportes2021/municipio/index.php";


	if(!empty($_GET['id'])){
		unset($_SESSION['reset']);
		$id_seccion_ine=$_GET['id'];
		$_SESSION['id_seccion_ine']=$id_seccion_ine;
		$id_municipio=$_SESSION['id_municipio'];
	}else{
		$id_seccion_ine=$_SESSION['id_seccion_ine']; 
		$id_municipio=$_SESSION['id_municipio'];
	}

	if($_GET['reset'] == "" && $_SESSION['reset'] == "x"){
		$_GET['reset'] = "x";
	}

	$_POST['searchTable'][0]['id'] = $id_seccion_ine;

	$id_seccion_ine;
	$id_municipio;
	$seccion_ineDatos = seccion_ineDatos($id_seccion_ine);
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','cartografias_municipios_2021',$_COOKIE["id_usuario"]);

	$tipo = 0;
	$ano = 2022;
?>
	<script type="text/javascript">
		$('html, body').animate({ scrollTop: $("#body").offset().top }, 1);
	</script>
	<title>Casillas Votos Reportes</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / 
		<?php
		if($tipo_uso_plataforma=='all'){
			echo '<div class="submenux" onclick="subConfiguracionMunicipiosReportes2021()">Municipios Reporte '.$ano.'</div> /';
		}
		?>
		<div class="submenux" onclick="subConfiguracionMunicipiosSeccionesIneReportes2021()">Secciones INE Reportes <?= $ano ?></div> / 

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
		<label class="tituloForm">
			Casillas INE Reportes <?= $ano ?>
		</label><br>
		<label class="tituloForm">
			<font style="font-weight: initial;font-size: 15px">Sección</font> <?= $seccion_ineDatos['numero'] ?>
		</label><br>


		<div><?php include "mapa.php"; ?></div>
		<div><?php include "totales.php"; ?></div>
		<div style="clear: both;"></div>
	</div>