<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/identidades.php";
	include '../functions/tool_xhpzab.php';
	@session_start();
	if(!empty($_GET['cot'])){
		$id_identidad=$_GET['cot'];
		setcookie("paguinaId_1",encrypt_ab_check($id_identidad), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id_identidad = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	}

	//este reset es para saber si tiene que mostrar todos los correos sin id_identidad
	if($id_identidad!=""){
		$disbale_id_pricipal='disabled="disabled"';
		echo $redirectSecurity=redirectSecurity($id_identidad,'identidades','identidades','index');
		if($redirectSecurity!=""){
			die;
		}
	}else{
		echo $redirectSecurity=redirectSecurity($id_identidad,'identidades','identidades','index');
		if($redirectSecurity!=""){
			die;
		}
	}
	if($id_identidad!=""){
		$id_identidad;
		$identidadDatos = identidadDatos($id_identidad);
		$nombre_completo = $identidadDatos['nombre_completo'];
	}
	$modulosPermiso = modulosPermiso('perfiles','',$_COOKIE["id_usuario"]);
	$moduloAccionPermisos = moduloAccionPermisos('perfiles','cuentas_redes_sociales',$_COOKIE["id_usuario"]);
	?>
	<title>Cuentas Redes Sociales</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPerfilesPersonas()">Configuración Perfiles Personas</div> /
		<?php
			if($modulosPermiso['identidades'] || $modulosPermiso['all'] && $id_identidad!="" ){
				?>
				<div class="submenux" onclick="subIdentidades()">Identidades</div> / 
				<?php
			}
		?>
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
		<?php
		if($id_identidad!=""){
			echo "<h2>".$nombre_completo."</h2>";
		}
		?>
		<label class="tituloForm">
			Cuentas Redes Sociales
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
				if( $moduloAccionPermisos['insert'] || $moduloAccionPermisos['all']){
					?>
					<input type="button" value="Nueva Cuenta Red Social" onClick="add();"> 
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