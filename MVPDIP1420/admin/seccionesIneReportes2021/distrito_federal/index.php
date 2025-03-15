<?php
	@session_start();
	include __DIR__."/../../functions/security.php";
	include '../../functions/usuario_permisos.php';
	include '../../functions/tool_xhpzab.php';
	if($tipo_uso_plataforma=='all'){
		if(!empty($_GET['id'])){
			$id_distrito_federal = $_GET['id'];
			setcookie("paguinaId_1",encrypt_ab_check($id_distrito_federal), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		}else{
			$id_distrito_federal = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
		}
		$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','cartografias_distritos_federales_2021',$_COOKIE["id_usuario"]);
	}elseif($tipo_uso_plataforma=='distrito_federal'){
		$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','cartografias_distritos_federales_2021',$_COOKIE["id_usuario"]);
	}else{
		$moduloAccionPermisos=null;
	}
	if($_GET['refresh']==1){
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		die;
	}
	include __DIR__."/../../functions/redirect_security.php";
	include __DIR__."/../../functions/distritos_federales_parametros.php";
	include __DIR__."/../../functions/partidos_2021.php";
	include __DIR__."/../../functions/distritos_federales.php";
	include __DIR__."/../../functions/secciones_ine_parametros.php";
	include __DIR__."/../../functions/secciones_ine.php";
	include __DIR__."/../../functions/tools.php";
	include __DIR__."/../../functions/municipios.php";
	include __DIR__."/../../functions/elecciones.php";
	include __DIR__."/../../functions/secciones_ine_colonias.php";
	include __DIR__."/../../functions/localidades_secciones_ine.php";
	$elecciones = eleccionesModulo('2021');
	$distrito_federalDatos = distrito_federalDatos($id_distrito_federal);
	function truncar($numero, $digitos){
		$truncar = 10**$digitos;
		return intval($numero * $truncar) / $truncar;
	}
	$tipo = 2;
	$ano = $elecciones['distritos_federales'];
?>
	<script type="text/javascript">
		$('html, body').animate({ scrollTop: $("#body").offset().top }, 1);
	</script>
	<title>Secciones INE</title>
	<div id="bodymanager" class="bodymanager">
		<?php
		if($_COOKIE['subPage']==1){
			echo '<div class="submenux" onclick="subConfiguracionDiaD()">Día D</div> / ';
		}else{
			echo '<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / ';
		}
		if($tipo_uso_plataforma=='all'){
			echo '<div class="submenux" onclick="subConfiguracionDistritosFederalesReportes2021()">Distritos Federales Reporte '.$ano.'</div> / ';
		}
		?>
		<br>
		<div id="mensaje" class="mensajeSolo" ></div>
		<?php
			if(empty($moduloAccionPermisos)){
				?>
				<script type="text/javascript">
					document.getElementById("mensaje").classList.add("mensajeError");
					$("#mensaje").html("No tiene permiso");
					urlink="home.php";
					dataString = 'urlink='+urlink; 
					$.ajax({
						type: "POST",
						url: "functions/backarray.php",
						data: dataString,
						success: function(data) { 	}
					});
					$("#homebody").load(urlink);
				</script>
				<?php
				die;
			}
		?>
		<label class="tituloForm">
			Distritos Federales Reporte <?= $ano ?>
		</label><br>
		<label class="tituloForm">
			<font style="font-weight: initial;font-size: 15px">Distrito Federal:</font> <?= $distrito_federalDatos['numero']; ?>
		</label><br>
		<div id="totalesLoad">
			<?php include "totales.php"; ?>
		</div> 
		<div style="clear: both;"></div>
		<div id="mapaLoad">
			<?php include "mapa.php"; ?>
		</div> 
		<div style="padding: 10px 0px 0px 0px"><?php include "filtros.php"; ?></div>
		<div style="clear: both;"></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>