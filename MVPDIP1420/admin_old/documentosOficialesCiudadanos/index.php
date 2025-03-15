<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../functions/plataformas.php";
	include '../functions/usuario_permisos.php';
	@session_start();
	$_SESSION['Paguinasub']="documentosOficialesCiudadanos/index.php";
	if(!empty($_GET['cot'])){
		$id_seccion_ine_ciudadano=$_GET['cot'];
		$_SESSION['id_seccion_ine_ciudadano']=$id_seccion_ine_ciudadano;
	}else{
		$id_seccion_ine_ciudadano=$_SESSION['id_seccion_ine_ciudadano']; 
	}

	validar_plataforma_vista($id,'secciones_ine_ciudadanos','seccionesIneCiudadanos','index',$codigo_plataforma);

	if($id_seccion_ine_ciudadano!=""){
		echo $redirectSecurity=redirectSecurity($id_seccion_ine_ciudadano,'secciones_ine_ciudadanos','seccionesIneCiudadanos','index');
		if($redirectSecurity!=""){
			die;
		}
	}else{
		echo $redirectSecurity=redirectSecurity($id_seccion_ine_ciudadano,'secciones_ine_ciudadanos','seccionesIneCiudadanos','index');
		if($redirectSecurity!=""){
			die;
		}
	}
	$id_seccion_ine_ciudadano;

	if($id_seccion_ine_ciudadano!=""){
		$id_seccion_ine_ciudadano;
		$seccion_ine_ciudadanoDatos = seccion_ine_ciudadanoDatos($id_seccion_ine_ciudadano);
		$nombre_completo = $seccion_ine_ciudadanoDatos['nombre_completo'];
	}
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','documentos_oficiales',$_COOKIE["id_usuario"]);

?>
	<title>Documentos Oficiales</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / 
		<div class="submenux" onclick="subSeccionesIneCiudadanos()">Ciudadanos</div> /
		<?php
		if(!empty($_SESSION['page_secciones_ine_ciudadanos_seccion'])){
			echo '<div class="submenux" onclick="subSeccionesIneCiudadanosSeccion()">Ciudadanos Sección</div> /';
		}
		?>
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