<?php
	if(!empty($_POST)){
		include __DIR__.'/../functions/security.php';
		include __DIR__.'/../functions/db.php';
		include __DIR__.'/../functions/manzanas_ine.php';
		@session_start();


		$id = $_POST['manzana'][0]['id'];
		$modulo = $_POST['manzana'][0]['modulo'];

		if($modulo=='secciones_ine_ciudadanos_form' && !empty($id) ){
			$manzana_ineDatos = manzana_ineDatos($id);

			if(!empty($manzana_ineDatos)){
				$myObj->status = true;
				$myObj->mensaje = 'OK';
				$myObj->id_seccion_ine = $manzana_ineDatos['id_seccion_ine'];
				$myObj->manzana = $manzana_ineDatos['numero'];
			}else{
				$myObj->status = false;
				$myObj->mensaje = 'No Encontrado';
				$myObj->id_seccion_ine = $manzana_ineDatos['id_seccion_ine'];
				$myObj->manzana = $manzana_ineDatos['numero'];
			}




		}else{
			$myObj->status = false;
			$myObj->mensaje = 'Error, Faltan atributos';
		}

		$myJSON = json_encode($myObj);
		echo $myJSON;

	}
?>