<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/directorios.php";
	include __DIR__."/../functions/sistemas_operativos.php";
	include __DIR__."/../functions/softwares.php";
	include __DIR__."/../functions/tipos_equipos.php";
	include '../functions/tool_xhpzab.php';
	@session_start();
	if(!empty($_GET)){
		if($_GET['cot']!=""){
			$id_directorio=$_GET['cot'];
			setcookie("paguinaId_1",encrypt_ab_check($id_directorio), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		}else{
			$id_directorio = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
		}
	}else{
		$id_directorio = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	}
	if($_GET['refresh']==1){
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		die;
	}
	if($id_directorio!=""){
		$id_directorio;
		$directorioDatos = directorioDatos($id_directorio);
		$nombre_completo = $directorioDatos['nombre']." ".$directorioDatos['apellido_paterno']." ".$directorioDatos['apellido_materno'];
		if($nombre_completo==""){
			echo $redirectSecurity=redirectSecurity('','directorios','directorios','index');
			if($redirectSecurity!=""){
				die;
			}
		}
	}else{
		echo $redirectSecurity=redirectSecurity($id_directorio,'directorios','directorios','index');
		if($redirectSecurity!=""){
			die;
		}
	}
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','equipos',$_COOKIE["id_usuario"]);
	?>
	<title>Equipos</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / 
		<div class="submenux" onclick="subDirectorios()">Directorio Institucional</div> / <br>
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
		<h2 style="text-transform:capitalize"><?= $nombre_completo ?> </h2>
		<label class="tituloForm">
			Equipos
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
				if( $moduloAccionPermisos['insert'] || $moduloAccionPermisos['all']){
					?>
					<input type="button" value="Nuevo Equipo Inventario" onClick="add();"> 
					<input type="button" value=" Reasignaciar Equipo" onClick="reasignacion();"> 
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