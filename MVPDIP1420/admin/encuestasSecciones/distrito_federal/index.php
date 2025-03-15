<?php
	@session_start(); 
	include '../../functions/tool_xhpzab.php';
	if($_GET['refresh']==1){
		$id_encuesta=$_GET['id'];
		if($id_encuesta!=""){
			setcookie("paguinaId_1",encrypt_ab_check($id_encuesta), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
			$id_distrito_federal=$_GET['id_distrito_federal'];
			setcookie("paguinaId_2",encrypt_ab_check($id_distrito_federal), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		}else{
			$id_encuesta = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
			$id_distrito_federal = decrypt_ab_checkFinal($_COOKIE['paguinaId_2']);
		}
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		die;
	}else{
		$id_encuesta = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
		$id_distrito_federal = decrypt_ab_checkFinal($_COOKIE['paguinaId_2']);
	}
	include __DIR__."/../../functions/security.php";
	include __DIR__."/../../functions/redirect_security.php";
	include __DIR__."/../../functions/distritos_federales_parametros.php";
	include __DIR__."/../../functions/distritos_federales.php";
	include '../../functions/usuario_permisos.php';
	$distrito_federalDatos = distrito_federalDatos($id_distrito_federal);
	function truncar($numero, $digitos){
		$truncar = 10**$digitos;
		return intval($numero * $truncar) / $truncar;
	}
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','encuestas',$_COOKIE["id_usuario"]);
?>
	<title>Secciones INE</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / 
		<div class="submenux" onclick="subEncuestas()">Encuestas</div> /
		<?php
		if($tipo_uso_plataforma =='all'){
			echo '<div class="submenux" onclick="subEncuestasDistritosFederales()">Encuestas Distritos Federales</div> /';
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
			Encuestas
		</label><br>
		<label class="tituloForm">
			<font style="font-weight: initial;font-size: 15px">Distrito Federal:</font> <?= $distrito_federalDatos['numero']; ?>
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