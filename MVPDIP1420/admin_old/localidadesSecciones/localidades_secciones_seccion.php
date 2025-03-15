<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	
	if(!empty($_POST['seccion_ine'])){
		include __DIR__.'/../functions/secciones_ine.php'; 
		include __DIR__.'/../functions/localidades_secciones.php'; 
		$id_seccion_ine = $_POST['seccion_ine'][0]['id_seccion_ine'];

		$seccion_ineDatos = seccion_ineDatos($id_seccion_ine);
		$localidades_seccionesDatosSeccion = localidades_seccionesDatosSeccion($id_estadoL=$id_estado,'',$seccion_ineDatos['numero']);
		echo "<div style='font-size:11px'>";
		echo "<h5>Localidades en la sección: ".$seccion_ineDatos['numero']."</h5>";
		foreach ($localidades_seccionesDatosSeccion as $key => $value) {
			$datos_localidades[] = "Localidad: <b>".$value['clave']." - ".$value['localidad']."</b> &nbsp;&nbsp;&nbsp;&nbsp;";
		}
		echo implode('| &nbsp;&nbsp;&nbsp;&nbsp;',$datos_localidades);
		echo "</div>";

	}