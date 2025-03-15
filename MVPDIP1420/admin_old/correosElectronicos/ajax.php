<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/identidades.php";
	include __DIR__."/../functions/correos_electronicos.php";

	if(!empty($_POST)){

		if($_POST['datos'][0]['tipo'] == "datos_correos_electronicos_select" && $_POST['datos'][0]['metodo'] == "mostrar"){
			$id_identidad = $_POST['datos'][0]['id_identidad'];
			echo correos_electronicos('',$id_identidad);
		}

		if($_POST['datos'][0]['tipo'] == "datos_correo_electronico" && $_POST['datos'][0]['metodo'] == "json"){
			$correo_electronicoDatos = correo_electronicoDatos($id_identidad);
			if(!empty($correo_electronicoDatos)){
				//bien

				$datos = array(
					'usuario' => $correo_electronicoDatos['usuario'],
					'password' => $correo_electronicoDatos['password'],
					'status' => 'success',
				);
				echo json_encode($datos, JSON_FORCE_OBJECT);
			}else{
				$datos = array( 
					'status' => 'error',
				);
				echo json_encode($datos, JSON_FORCE_OBJECT);
			}
		}
	}
?>
