<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/programas_apoyos.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET)){
		if($_GET['cot']!=""){
			$id_programa_apoyo=$_GET['cot'];
			setcookie("paguinaId_1",encrypt_ab_check($id_programa_apoyo), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		}else{
			$id_programa_apoyo = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
		}
	}else{
		$id_programa_apoyo = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	}
	if($id_programa_apoyo!=""){
		$id_programa_apoyo;
		$programa_apoyoDatos = programa_apoyoDatos($id_programa_apoyo);
		$nombre_completo = $programa_apoyoDatos['nombre'];
		if($nombre_completo==""){
			echo $redirectSecurity=redirectSecurity('','programas_apoyos','programasApoyos','index');
			if($redirectSecurity!=""){
				die;
			}
		}
	}else{
		echo $redirectSecurity=redirectSecurity($id_programa_apoyo,'programas_apoyos','programasApoyos','index');
		if($redirectSecurity!=""){
			die;
		}
	}
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_programas_apoyos',$_COOKIE["id_usuario"]);
?>
	<title>Programas Apoyos</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / 
		<div class="submenux" onclick="subProgramasApoyos()">Programas Apoyos</div> / <br>
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
			Programas Apoyos
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
					<input type="button" value="Excel Programas Apoyos General Ciudadanos" onClick="downloadExcel();"> 
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