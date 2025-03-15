<?php
	include __DIR__."/../functions/security.php";
	include '../functions/switch_operaciones.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../functions/encuestas.php";

	@session_start();
	$_SESSION['Paguinasub']="seccionesIneCiudadanosEncuestas/index.php";
	if(!empty($_GET['cot'])){
		$id_seccion_ine_ciudadano=$_GET['cot'];
		$_SESSION['id_seccion_ine_ciudadano']=$id_seccion_ine_ciudadano;
	}else{
		$id_seccion_ine_ciudadano=$_COOKIE['paguinaId']; 
	}

	if($id_seccion_ine_ciudadano!=""){
		$id_seccion_ine_ciudadano;
		$seccion_ine_ciudadanoDatos = seccion_ine_ciudadanoDatos($id_seccion_ine_ciudadano);
		$nombre_completo = $seccion_ine_ciudadanoDatos['nombre_completo'];
		if($nombre_completo==""){
			echo $redirectSecurity=redirectSecurity('','secciones_ine_ciudadanos','seccionesIneCiudadanos','index');
			if($redirectSecurity!=""){
				die;
			}
		}
	}else{
		echo $redirectSecurity=redirectSecurity($id_seccion_ine_ciudadano,'secciones_ine_ciudadanos','seccionesIneCiudadanos','index');
		if($redirectSecurity!=""){
			die;
		}
	}

	$switch_operacionesPermisos = switch_operacionesPermisos();

	?>
	<title>Encuestas</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subSeccionesIneCiudadanos()">Ciudadanos</div> / 
		<br>
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<?php
			if($switch_operacionesPermisos['evaluacion']==false){
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
			Encuestas
		</label><br>
		<br><br>
		<div> <?php include "filtros.php"; ?></div>
		<div style="clear: both;"></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>