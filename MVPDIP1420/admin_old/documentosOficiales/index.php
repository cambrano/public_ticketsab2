<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/identidades.php";
	include '../functions/usuario_permisos.php';
	@session_start();
	$_SESSION['Paguinasub']="documentosOficiales/index.php";
	if(!empty($_GET['cot'])){
		$id_identidad=$_GET['cot'];
		$_SESSION['id_identidad']=$id_identidad;
	}else{
		$id_identidad=$_SESSION['id_identidad']; 
	}
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
	$id_identidad;

	if($id_identidad!=""){
		$id_identidad;
		$identidadDatos = identidadDatos($id_identidad);
		$nombre_completo = $identidadDatos['nombre_completo'];
	}
	$moduloAccionPermisos = moduloAccionPermisos('perfiles','documentos_oficiales',$_COOKIE["id_usuario"]);

?>
	<title>Documentos Oficiales</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPerfilesPersonas()">Configuración Perfiles Personas</div> / 
		<div class="submenux" onclick="subIdentidades()">Identidades</div> <br>
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
		<h2><?= $nombre_completo ?> </h2>
		<label class="tituloForm">
			Documentos Oficiales
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
				if( $moduloAccionPermisos['insert'] || $moduloAccionPermisos['all']){
					?>
					<input type="button" value="Nuevo Documento Oficial" onClick="add();"> 
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