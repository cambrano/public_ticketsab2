<?php

	if(!empty($_POST)){
		include __DIR__.'/../functions/security.php'; 
		include __DIR__.'/../functions/db.php'; 
		include __DIR__.'/../functions/secciones_ine_ciudadanos.php'; 
		@session_start();
		$clave = trim($_POST['clave']);
		$clave = mysqli_real_escape_string($conexion,$clave);
		//bucamos en la lista_nominal
		$xcl = $clave;
		$sql="
			SELECT 
				e.id AS id_equipo,
				e.clave,
				(SELECT u.nombre FROM ubicaciones u WHERE u.id = e.id_ubicacion) ubicacion,
				(SELECT te.nombre FROM tipos_equipos te WHERE te.id = e.id_tipo_equipo) tipo_equipo,
				e.folio,
				e.marca,
				e.modelo,
				e.ram,
				e.procesador
			FROM equipos e 
			WHERE e.clave ='{$xcl}'
			";
		$result = $conexion->query($sql);
		$row=$result->fetch_assoc();

		if(!empty($row)){
            $myObj->id_equipo = $row['id_equipo'];
			$myObj->clave = $row['clave'];
			$myObj->ubicacion = $row['ubicacion'];
			$myObj->tipo_equipo = $row['tipo_equipo'];
			$myObj->folio = $row['folio'];
			$myObj->marca = $row['marca'];
			$myObj->modelo = $row['modelo'];
			$myObj->ram = $row['ram'];
			$myObj->procesador = $row['procesador'];

		}else{
            $myObj->id_equipo = '';
            $myObj->clave = 'No Encontrado';
			$myObj->ubicacion = 'No Encontrado';
			$myObj->tipo_equipo = 'No Encontrado';
			$myObj->folio = 'No Encontrado';
			$myObj->marca = 'No Encontrado';
			$myObj->modelo = 'No Encontrado';
			$myObj->ram = 'No Encontrado';
			$myObj->procesador = 'No Encontrado';
		}
		$myJSON = json_encode($myObj);
		echo $myJSON;
	}
?>