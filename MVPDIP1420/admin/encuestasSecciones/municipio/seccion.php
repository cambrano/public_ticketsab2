<?php
	
	include '../../functions/tool_xhpzab.php';
	if($_GET['refresh']==1){
		$id_seccion_ine=$_GET['id'];
		if($id_seccion_ine!=""){
			setcookie("paguinaId_3",encrypt_ab_check($id_seccion_ine), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		}else{
			$id_seccion_ine = decrypt_ab_checkFinal($_COOKIE['paguinaId_3']);
		}
		
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		die;
	}else{
		$id_seccion_ine = decrypt_ab_checkFinal($_COOKIE['paguinaId_3']);
	}
	$id_encuesta = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	$id_municipio = decrypt_ab_checkFinal($_COOKIE['paguinaId_2']);

	include __DIR__."/../../functions/security.php";
	include __DIR__."/../../functions/redirect_security.php";
	include __DIR__."/../../functions/municipios_parametros.php";
	include __DIR__."/../../functions/municipios.php";
	include __DIR__."/../../functions/secciones_ine.php";
	include '../../functions/usuario_permisos.php'; 
	$municipioNombre = municipioNombre($id_municipio);
	$seccion_ineDatos = seccion_ineDatos($id_seccion_ine);

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
			echo '<div class="submenux" onclick="subEncuestasMunicipios()">Encuestas Municipios</div> /';
		}
		?>
		<div class="submenux" onclick="subEncuestasMunicipio()">Encuestas Municipio</div> /
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
			<font style="font-weight: initial;font-size: 15px">Municipio:</font> <?= $municipioNombre; ?>
		</label><br>
		<label class="tituloForm">
			<font style="font-weight: initial;font-size: 15px">Sección:</font> <?= $seccion_ineDatos['numero']; ?>
		</label><br>
		<div><?php include 'totales.php'; ?></div> 
		<div style="clear: both;"></div>
		<div id="mapaLoad">
			<?php include "mapa1.php"; ?>
		</div> 
		<div style="padding: 10px 0px 0px 0px"><?php include "filtros_seccion.php"; ?></div>
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
			<?php include "table_seccion.php"; ?>
		</div> 
	</div>