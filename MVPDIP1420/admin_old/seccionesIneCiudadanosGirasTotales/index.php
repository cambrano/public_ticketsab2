<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/secciones_ine_giras.php";

	@session_start();
	$_SESSION['Paguinasub']="seccionesIneCiudadanosGirasTotales/index.php";
	if(!empty($_GET['cot'])){
		$id_seccion_ine_gira=$_GET['cot'];
		$_SESSION['id_seccion_ine_gira']=$id_seccion_ine_gira;
	}else{
		$id_seccion_ine_gira=$_SESSION['id_seccion_ine_gira'];
	}
	$_SESSION['paguinaId'] = $id_seccion_ine_gira;


	if($id_seccion_ine_gira!=""){
		$id_seccion_ine_gira;
		$seccion_ine_giraDatos = seccion_ine_giraDatos($id_seccion_ine_gira);
		$nombre_completo = $seccion_ine_giraDatos['nombre'];
		if($nombre_completo==""){
			echo $redirectSecurity=redirectSecurity('','secciones_ine_giras','programasApoyos','index');
			if($redirectSecurity!=""){
				die;
			}
		}
	}else{
		echo $redirectSecurity=redirectSecurity($id_seccion_ine_gira,'secciones_ine_giras','programasApoyos','index');
		if($redirectSecurity!=""){
			die;
		}
	}
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_giras',$_COOKIE["id_usuario"]);
?>
	<title>Participantes</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / 
		<div class="submenux" onclick="subSeccionesIneGiras()">Giras</div> / <br>
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
			Participantes
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
				if( $moduloAccionPermisos['insert'] || $moduloAccionPermisos['all']){
					?>
					<input type="button" value="Nuevo ciudadano" onClick="add();"> 
					<?php
				}
				if( $moduloAccionPermisos['download'] || $moduloAccionPermisos['all']){
					?>
					<input type="button" value="Excel Participantes Giras Ciudadanos" onClick="downloadExcel();"> 
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