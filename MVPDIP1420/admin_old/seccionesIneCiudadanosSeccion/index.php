<?php
	@session_start(); 
	$_SESSION['page_secciones_ine_ciudadanos_seccion'] = true;
	$_SESSION['Paguinasub']="seccionesIneCiudadanosSeccion/index.php";
	if(!empty($_GET['cot'])){
		unset($_SESSION['reset']);
		$id_seccion_ine=$_GET['cot'];
		$_SESSION['id_seccion_ine']=$id_seccion_ine;
	}else{
		$id_seccion_ine=$_SESSION['id_seccion_ine']; 
	}
	if($_GET['refresh']==1){
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		die;
	}
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/secciones_ine_parametros.php";
	include __DIR__."/../functions/manzanas_ine.php";
	include __DIR__."/../functions/manzanas_ine_parametros.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../functions/tipos_categorias_ciudadanos.php";
	include __DIR__."/../functions/tipos_ciudadanos.php";
	include __DIR__."/../functions/cuarteles.php";
	include __DIR__."/../functions/localidades.php";
	include __DIR__."/../functions/municipios.php";
	include __DIR__."/../functions/partidos_legados.php";
	include __DIR__."/../functions/distritos_locales.php";
	include __DIR__."/../functions/distritos_federales.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/programas_apoyos.php";
	include __DIR__."/../functions/secciones_ine_grupos.php";

	echo $redirectSecurity=redirectSecurity($id_seccion_ine,'secciones_ine','seccionesIneCiudadanosSeccion','index');
	if($redirectSecurity!=""){
		die;
	}
	

	$seccion_ineDatos = seccion_ineDatos($id_seccion_ine);
	$latitud = $seccion_ineDatos['latitud'];
	$longitud = $seccion_ineDatos['longitud'];
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos',$_COOKIE["id_usuario"]);
	$moduloAccionPermisos_categoprias = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_categorias',$_COOKIE["id_usuario"]);
	$moduloAccionPermisos_encuestas = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_encuestas',$_COOKIE["id_usuario"]);
	$moduloAccionPermisos_seguimientos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_seguimientos',$_COOKIE["id_usuario"]);
	$moduloAccionPermisos_programas_apoyos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_programas_apoyos',$_COOKIE["id_usuario"]);
	$moduloAccionPermisos_giras = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_giras',$_COOKIE["id_usuario"]);
	$moduloAccionPermisos_militantes_partidos = moduloAccionPermisos('sistema_unico_beneficiarios','militantes_partidos',$_COOKIE["id_usuario"]);

	unset($_SESSION['searchTable']);
	unset($_SESSION['reporte_Sistema']);

	function truncar($numero, $digitos){
	    $truncar = 10**$digitos;
	    return number_format(intval($numero * $truncar) / $truncar, 2, '.', ',');
	}
?>
	<title>Ciudadanos Seccion</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / 
		<div class="submenux" onclick="subSeccionesIneCiudadanos()">Ciudadanos</div> /
		<br>
		<div id="mensaje" class="mensajeSolo" ></div>
		<?php
			if(empty($moduloAccionPermisos) && empty($moduloAccionPermisos_encuestas) && empty($moduloAccionPermisos_categoprias)  && empty($moduloAccionPermisos_seguimientos
			) && empty($moduloAccionPermisos_programas_apoyos) && empty($moduloAccionPermisos_giras) && $moduloAccionPermisos_militantes_partidos ){
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
		<label class="tituloForm">
			Ciudadanos
		</label><br>
		<label class="tituloForm">
			<font style="font-weight: initial;font-size: 15px">Sección</font> <?= $seccion_ineDatos['numero'] ?>
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
					if( $moduloAccionPermisos['insert'] || $moduloAccionPermisos['all']){
						?>
						<input id="btn_nuevo_seccion_ine_ciudadano" type="button" value="Nuevo Ciudadano" onClick="add();"> 
						<?php
					}
					if( $moduloAccionPermisos['download'] || $moduloAccionPermisos['all']){
						?>
						<input type="button" value="Excel Ciudadano" onClick="downloadExcel();"> 
						<?php
					}else{
						?>
						<input type="button" style="display: none" value="Excel Ciudadano" onClick=""> 
						<?php
					}

				?>
		</div>
		<br><br>
		<br><br>
		<div><?php include 'totales.php'; ?></div> 
		<div style="clear: both;"></div>
		<div id="mapaLoad">
			<?php include "mapa.php"; ?>
		</div> 
		<div style="padding: 10px 0px 0px 0px"><?php include "filtros.php"; ?></div>
		<div style="clear: both;"></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>
