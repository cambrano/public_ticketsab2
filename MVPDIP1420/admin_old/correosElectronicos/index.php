<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/identidades.php";
	@session_start();
	$_SESSION['Paguinasub']="correosElectronicos/index.php";
	if(!empty($_GET['cot'])){
		unset($_SESSION['reset']);
		$id_identidad=$_GET['cot'];
		$_SESSION['id_identidad']=$id_identidad;
	}else{
		$id_identidad=$_SESSION['id_identidad']; 
	}

	if($_GET['reset'] == "" && $_SESSION['reset'] == "x"){
		$_GET['reset'] = "x";
	}

	//este reset es para saber si tiene que mostrar todos los correos sin id_identidad
	if($_GET['reset']=="x"){
		unset($_SESSION['id_identidad']);
		$id_identidad = "";
		$_SESSION['reset'] = "x";
		
	}else{
		unset($_SESSION['reset']);
		if($id_identidad!=""){
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
		$disbale_id_pricipal='disabled="disabled"';
	}
	if($id_identidad!=""){
		$id_identidad;
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
			if($modulosPermiso['identidades'] || $modulosPermiso['all'] && $_SESSION['id_identidad']!="" ){
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
					$("#homebody").load('home.php');
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