<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/cuentas_redes_sociales.php";
	include __DIR__."/../functions/identidades.php";
	include '../functions/usuario_permisos.php';
	@session_start();
	$_SESSION['Paguinasub']="cuentasRedesSocialesActividades/index.php";
	if(!empty($_GET['cot'])){
		unset($_SESSION['reset']);
		$id_cuenta_red_social=$_GET['cot'];
		$_SESSION['id_cuenta_red_social']=$id_cuenta_red_social;
	}else{
		$id_cuenta_red_social=$_SESSION['id_cuenta_red_social']; 
	}

	if($_GET['reset'] == "" && $_SESSION['reset'] == "x"){
		$_GET['reset'] = "x";
	}

	//este reset es para saber si tiene que mostrar todos los correos sin id_cuenta_red_social
	if($_GET['reset']=="x"){
		unset($_SESSION['id_cuenta_red_social']);
		$id_cuenta_red_social = "";
		$_SESSION['reset'] = "x";
		
	}else{
		unset($_SESSION['reset']);
		if($id_cuenta_red_social!=""){
			echo $redirectSecurity=redirectSecurity($id_cuenta_red_social,'cuentas_redes_sociales','cuentasRedesSociales','index');
			if($redirectSecurity!=""){
				die;
			}
		}else{
			echo $redirectSecurity=redirectSecurity($id_cuenta_red_social,'cuentas_redes_sociales','cuentasRedesSociales','index');
			if($redirectSecurity!=""){
				die;
			}
		}
	}
	$cuenta_red_socialDatos=cuenta_red_socialDatos($id_cuenta_red_social);

	//var_dump($cuenta_red_socialDatos);
	$modulosPermiso = modulosPermiso('perfiles','',$_COOKIE["id_usuario"]);
	$moduloAccionPermisos = moduloAccionPermisos('perfiles','tipos_actividades',$_COOKIE["id_usuario"]);
	$id_identidad =  $cuenta_red_socialDatos['id_identidad'];
	$identidadDatos = identidadDatos($id_identidad);
	$nombre_completo = $identidadDatos['nombre_completo'];

?>
	<title>Cuentas Redes Sociales</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPerfilesPersonas()">Configuración Perfiles Personas</div> / 
		<?php
			if($modulosPermiso['identidades'] || $modulosPermiso['all'] && $_SESSION['id_identidad']!="" ){
				?>
				<div class="submenux" onclick="subIdentidades()">Identidades</div> / 
				<?php
			}
		?>
		<div class="submenux" onclick="subCuentasRedesSociales()">Cuentas Redes Sociales</div> / 
		<br>
		<div id="mensaje" class="mensajeSolo" ></div>
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
		<h2><?= $nombre_completo ?></h2>
		<label class="tituloForm">
			Actividades
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
				if( $moduloAccionPermisos['insert'] || $moduloAccionPermisos['all']){
					?>
					<input type="button" value="Nueva Actividad" onClick="add();"> 
					<?php
				}
			?>
		</div>
		<br><br>
		<div><?php include "filtros.php"; ?></div>
		<div style="clear: both;"></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>