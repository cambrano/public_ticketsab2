<?php
	@session_start();
	include __DIR__."/../../functions/security.php";
	include '../../functions/usuario_permisos.php';
	include '../../functions/tool_xhpzab.php';
	if($tipo_uso_plataforma=='all'){
		$id_municipio = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
		$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','cartografias_municipios_2018',$_COOKIE["id_usuario"]);
	}elseif($tipo_uso_plataforma=='municipio'){
		$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','cartografias_municipios_2018',$_COOKIE["id_usuario"]);
	}else{
		$moduloAccionPermisos=null;
	}
	if(!empty($_GET['id'])){
		$id_seccion_ine = $_GET['id'];
		setcookie("paguinaId_2",encrypt_ab_check($id_seccion_ine), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id_seccion_ine = decrypt_ab_checkFinal($_COOKIE['paguinaId_2']);
	}
	if($_GET['refresh']==1){
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		die;
	}
	include __DIR__."/../../functions/security.php";
	include __DIR__."/../../functions/redirect_security.php";
	include __DIR__."/../../functions/municipios_parametros.php";
	include __DIR__."/../../functions/partidos_2018.php";
	include __DIR__."/../../functions/municipios.php";
	include __DIR__."/../../functions/secciones_ine_parametros.php";
	include __DIR__."/../../functions/secciones_ine.php";
	include '../../functions/usuario_permisos.php';
	include __DIR__."/../../functions/elecciones.php";
	$elecciones = eleccionesModulo('2018');
	$_POST['searchTable'][0]['id'] = $id_seccion_ine;
	$id_seccion_ine;
	$id_municipio;
	$seccion_ineDatos = seccion_ineDatos($id_seccion_ine);
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','cartografias_municipios_2018',$_COOKIE["id_usuario"]);
	$tipo = 0;
	$ano = $elecciones['municipios'];
?>
	<script type="text/javascript">
		$('html, body').animate({ scrollTop: $("#body").offset().top }, 1);
	</script>
	<title>Casillas Votos Reportes</title>
	<div id="bodymanager" class="bodymanager">
		<?php
		if($_COOKIE['subPage']==1){
			echo '<div class="submenux" onclick="subConfiguracionDiaD()">Día D</div> / ';
		}else{
			echo '<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / ';
		}
		if($tipo_uso_plataforma=='all'){
			echo '<div class="submenux" onclick="subConfiguracionMunicipiosReportes2018()">Municipios Reporte '.$ano.'</div> /';
		}
		?>
		<div class="submenux" onclick="subConfiguracionMunicipiosSeccionesIneReportes2018()">Secciones INE Reportes <?= $ano ?></div> / 
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
			Casillas INE Reportes <?= $ano ?>
		</label><br>
		<label class="tituloForm">
			<font style="font-weight: initial;font-size: 15px">Sección</font> <?= $seccion_ineDatos['numero'] ?>
		</label><br>


		<div><?php include "mapa.php"; ?></div>
		<div><?php include "totales.php"; ?></div>
		<div style="clear: both;"></div>
	</div>