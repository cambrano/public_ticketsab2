<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/empleados.php";
	include __DIR__."/../functions/plataformas.php";
	include '../functions/usuario_permisos.php';
	include "../functions/usuarios.php";
	include '../functions/tool_xhpzab.php';
	@session_start(); 
	if(!empty($_GET)){
		$id_empleado = $_GET['cot'];
		setcookie("paguinaId",encrypt_ab_check($id_empleado), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id_empleado = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}
	



	$id_empleado;
	validar_plataforma_vista($id_empleado,'empleados','adminGenerales','index',$codigo_plataforma);
	

	echo $redirectSecurity=redirectSecurity($id_empleado,'empleados','adminGenerales','index');
	if($redirectSecurity!=""){
		die;
	}
	
	$empleadoDatos=empleadoDatos($id_empleado);
	$moduloAccionPermisos = moduloAccionPermisos('configuracion','empleados_permisos',$_COOKIE["id_usuario"]);
	$usuarioDatos=usuarioDatos('',$id_empleado);

	if($_COOKIE["id_usuario"]==$usuarioDatos['id']){
		$moduloAccionPermisos = false;
	}
	
	?> 
	<title>Permisos Usuarios</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracion()">Configuración</div> / 
		<div class="submenux" onclick="subEmpleados()">Empleados</div> / <br>
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
			Permisos Empleado
		</label><br>
		<h3>
			<?= $empleadoDatos['nombre_empleado'] ?>
		</h3>
		<br>

		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
				if( $moduloAccionPermisos['insert'] || $moduloAccionPermisos['all']){
					?>
					<input type="button" value="Nuevo Permiso" onClick="add();"> 
					<?php
				}
			?>
		</div>
		<br><br>
		<div><?php include "filtros.php" ?></div>
		<div style="clear: both;"></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div>
	</div>