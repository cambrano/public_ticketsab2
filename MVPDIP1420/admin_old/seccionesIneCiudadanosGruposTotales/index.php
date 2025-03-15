<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/secciones_ine_grupos.php";
	include __DIR__."/../functions/tipos_nombramientos.php";

	@session_start();
	$_SESSION['Paguinasub']="seccionesIneCiudadanosGruposTotales/index.php";
	if(!empty($_GET['cot'])){
		$id_seccion_ine_grupo=$_GET['cot'];
		$_SESSION['id_seccion_ine_grupo']=$id_seccion_ine_grupo;
	}else{
		$id_seccion_ine_grupo=$_SESSION['id_seccion_ine_grupo'];
	}

	if($id_seccion_ine_grupo!=""){
		$id_seccion_ine_grupo;
		$seccion_ine_grupoDatos = seccion_ine_grupoDatos($id_seccion_ine_grupo);
		$nombre_completo = $seccion_ine_grupoDatos['nombre'];
		if($nombre_completo==""){
			echo $redirectSecurity=redirectSecurity('','secciones_ine_grupos','seccionesIneGrupos','index');
			if($redirectSecurity!=""){
				die;
			}
		}
	}else{
		echo $redirectSecurity=redirectSecurity($id_seccion_ine_grupo,'secciones_ine_grupos','seccionesIneGrupos','index');
		if($redirectSecurity!=""){
			die;
		}
	}
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_grupos',$_COOKIE["id_usuario"]);
?>
	<title>Programas Apoyos</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / 
		<div class="submenux" onclick="subSeccionesIneGrupos()">Secciones Ine Grupos</div> /
		<br>
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
			Miembros Grupos
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
				if( $moduloAccionPermisos['insert'] || $moduloAccionPermisos['all']){
					?>
					<input type="button" value="Nuevo miembro" onClick="add();"> 
					<?php
				}
				if( $moduloAccionPermisos['download'] || $moduloAccionPermisos['all']){
					?>
					<input type="button" value="Excel Miembros" onClick="downloadExcel();"> 
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