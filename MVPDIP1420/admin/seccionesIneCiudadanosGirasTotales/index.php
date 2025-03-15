<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/secciones_ine_giras.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET['cot'])){
		$id_seccion_ine_gira=$_GET['cot'];
		setcookie("paguinaId_2",encrypt_ab_check($id_seccion_ine_gira), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id_seccion_ine_gira = decrypt_ab_checkFinal($_COOKIE['paguinaId_2']);
	}
	if($id_seccion_ine_gira!=""){
		$id_seccion_ine_gira;
		$seccion_ine_giraDatos = seccion_ine_giraDatos($id_seccion_ine_gira);
		$nombre_completo = $seccion_ine_giraDatos['nombre'];
		if($nombre_completo==""){
			echo $redirectSecurity=redirectSecurity('','secciones_ine_giras','seccionesIneGiras','index');
			if($redirectSecurity!=""){
				die;
			}
		}
	}else{
		echo $redirectSecurity=redirectSecurity($id_seccion_ine_gira,'secciones_ine_giras','seccionesIneGiras','index');
		if($redirectSecurity!=""){
			die;
		}
	}
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_giras',$_COOKIE["id_usuario"]);
?>
	<title>Participantes</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / 
		<div class="submenux" onclick="subSeccionesIneGiras()">Agenda de Gobierno</div> / <br>
		<br>
		<div id="mensaje" class="mensajeSolo" ><br></div>
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
		<h2><?= $nombre_completo ?> </h2>
		<label class="tituloForm">
			Participantes
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
				if( $moduloAccionPermisos['insert'] || $moduloAccionPermisos['all']){
					?>
					<input type="button" value="Nuevo ciudadano" onClick="add();"> 
					<?php
				}
				if( $moduloAccionPermisos['download'] || $moduloAccionPermisos['all']){
					?>
					<input type="button" value="Excel Participantes Agenda Ciudadanos" onClick="downloadExcel();"> 
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