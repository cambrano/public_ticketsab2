<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/partidos_legados.php";
	include __DIR__."/../functions/configuracion_matriz_rentabilidad_secciones_ine_2016.php";
	include __DIR__."/../functions/configuracion_matriz_rentabilidad_secciones_ine_2018.php";
	include __DIR__."/../functions/configuracion_matriz_rentabilidad_secciones_ine_2021.php";
	include __DIR__."/../functions/configuracion_matriz_rentabilidad_secciones_ine_2024.php";
	include __DIR__."/../functions/partidos_2016.php";
	include __DIR__."/../functions/partidos_2018.php";
	include __DIR__."/../functions/partidos_2021.php";
	include __DIR__."/../functions/partidos_2024.php";

	include __DIR__."/../functions/estados.php";
	include __DIR__."/../functions/municipios.php";
	include __DIR__."/../functions/distritos_locales.php";
	include __DIR__."/../functions/distritos_federales.php";

	if($_COOKIE["id_usuario"]!=1){
		die;
	}
	$success=true;
	$conexion->autocommit(FALSE);
	foreach($_POST["elecciones"] as $keyPrincipal => $atributos) {
		unset($valueSets);
		foreach ($atributos as $key => $valueT) {
			if($key !='id'){
				$valueSets[] = $key . " = '" . $valueT . "'";
			}else{
				$id=$valueT;
			}
		}
		$update_elecciones = "UPDATE elecciones SET ". join(",",$valueSets) . " WHERE id=".$id;
		$update_elecciones=$conexion->query($update_elecciones);
		$num=$conexion->affected_rows;
		if(!$update_elecciones || $num=0){
			$success=false;
			echo "<br>";
			echo "ERROR update_elecciones"; 
			var_dump($conexion->error);
		}
	}

	if($success){
		echo "SI";
		$conexion->commit();
		$conexion->close();
	}else{
		echo "NO";
		$conexion->rollback();
		$conexion->close();
	}

