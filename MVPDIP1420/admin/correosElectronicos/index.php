<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/identidades.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET['cot'])){
		$id_identidad=$_GET['cot'];
		setcookie("paguinaId_1",encrypt_ab_check($id_identidad),time()+(60*60*24*650),"/",false);
	}else{
		$id_identidad = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	}

	if($id_identidad!=""){
		$id_identidad;
		$disbale_id_pricipal='disabled="disabled"';
		$identidadDatos = identidadDatos($id_identidad);
		$nombre_completo = $identidadDatos['nombre_completo'];
	}
	$modulosPermiso = modulosPermiso('perfiles','',$_COOKIE["id_usuario"]);
	$moduloAccionPermisos = moduloAccionPermisos('perfiles','correos_electronicos',$_COOKIE["id_usuario"]);
	?>
	<title>Tipos Actividades</title>
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
			Correos Eléctronicos
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
				if( $moduloAccionPermisos['insert'] || $moduloAccionPermisos['all']){
					?>
					<input type="button" value="Nuevo Correo Eléctronico" onClick="add();"> 
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