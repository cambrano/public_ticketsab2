<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/secciones_ine.php";

	if(!empty($_POST)){
		$id_seccion_ine=$_POST['id_seccion_ine'];
		$tipo_show=$_POST['tipo_show'];
		
		if($tipo_show == 'gps_json' && $id_seccion_ine !='' ){
			$seccion_ineDatos = seccion_ineDatos($id_seccion_ine);

			$data = array(
				"id" => $seccion_ineDatos['id'],
				"tipo" => $seccion_ineDatos['tipo'],
				"tipo_seccion" => $seccion_ineDatos['tipo']==1 ? 'Urbana': 'Rural',
				"seccion" => $seccion_ineDatos['numero'],
				"latitud" => $seccion_ineDatos['latitud'],
				"longitud" => $seccion_ineDatos['longitud'],
			);
			// Convertir el array a formato JSON
			$json_data = json_encode($data);

			// Imprimir el JSON resultante
			echo $json_data;
		}
	}
?>
