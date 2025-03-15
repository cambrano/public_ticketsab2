<?php
	function secciones_ine_reportes2024DatosArray($registros=null,$orderby=null,$limit=null) {
		include 'db.php'; 
		$sql="
		SELECT
			si.id,
			si.clave,
			si.numero,
			si.latitud,
			si.longitud,
			si.votos_nulos,
			si.votos_can_nreg,
			si.lista_nominal,
			si.votos_validos,
			si.fechaR,
			si.id_partido_2024_ganador,
			si.votos_partido_ganador,
			p.icono,
			p.id id_partido_2024,
			p.logo,
			p.color_border,
			p.color_background,
			p.nombre_corto,
			p.nombre,
			si.votos_partido_sistema,
			si.logo_partido_sistema,
			si.id_partido_2024_sistema,
			si.color_partido_sistema,
			si.nombre_corto_partido_sistema,
			si.nombre_partido_sistema
			FROM(
				SELECT 
			si.id,
			si.clave,
			si.numero,
			si.latitud,
			si.longitud,
			si.fechaR,
			'' partido_ganador,
			'' partido,
			'' informacion_seccion,
			(SELECT SUM(IFNULL(cv.votos_nulos,0)) FROM casillas_votos_2024 cv WHERE cv.id_seccion_ine = si.id) votos_nulos,
			(SELECT SUM(IFNULL(cv.votos_can_nreg,0)) FROM casillas_votos_2024 cv WHERE cv.id_seccion_ine = si.id) votos_can_nreg,
			(SELECT cv.lista_nominal FROM casillas_votos_2024 cv WHERE cv.id_seccion_ine = si.id LIMIT 1 ) lista_nominal,
			(SELECT SUM(IFNULL(cvp.votos,0)) FROM casillas_votos_partidos_2024 cvp WHERE cvp.id_seccion_ine = si.id) votos_validos,
			(SELECT cvp.id_partido_2024 FROM casillas_votos_partidos_2024 cvp WHERE cvp.id_seccion_ine = si.id GROUP BY cvp.id_partido_2024 ORDER BY SUM(cvp.votos) DESC limit 1 ) id_partido_2024_ganador,
			(SELECT SUM(cvp.votos) FROM casillas_votos_partidos_2024 cvp WHERE cvp.id_seccion_ine = si.id GROUP BY cvp.id_partido_2024 ORDER BY SUM(cvp.votos) DESC limit 1 ) votos_partido_ganador,
			(SELECT SUM(cvp.votos)  FROM casillas_votos_partidos_2024 cvp WHERE EXISTS (SELECT p.id  FROM partidos_2024 p WHERE p.nombre_corto = 'pri' AND p.id = cvp.id_partido_2024  ) AND cvp.id_seccion_ine=si.id) votos_partido_sistema,
			(SELECT p.id  FROM partidos_2024 p WHERE p.nombre_corto = 'pri' LIMIT 1) id_partido_2024_sistema,
			(SELECT p.color_border  FROM partidos_2024 p WHERE p.nombre_corto = 'pri' LIMIT 1) color_partido_sistema,
			(SELECT p.nombre_corto  FROM partidos_2024 p WHERE p.nombre_corto = 'pri' LIMIT 1) nombre_corto_partido_sistema,
			(SELECT p.nombre  FROM partidos_2024 p WHERE p.nombre_corto = 'pri' LIMIT 1) nombre_partido_sistema,
			'pri.png' logo_partido_sistema
			FROM secciones_ine si
			WHERE 1 = 1 
			) as si
			LEFT JOIN partidos_2024 p
			ON si.id_partido_2024_ganador = p.id
		WHERE 1 = 1 
		";

		foreach ($registros as $key => $value) {
			//echo $key;
			//echo "-";
			//echo $value;
			//echo "<br>";
			$value;
			if($value =="id_partido_2024_ganador"){
				$sql.= " AND  si.{$key} = '{$value}' ";
			}elseif($key =="id"){
				$sql.= " AND  si.{$key} = '{$value}' ";
			}else{

				$sql.= " AND  si.{$key} like '%{$value}%' ";
			}
		}




		if($orderby!=""){
			$sql.=" {$orderby} ";
		}

		if($limit!=""){
			$sql.=" {$limit} ";
		}
		$sql;
		//$resultado = $conexion->query($sql);
		$result = $conexion->query($sql); 
		$num=0; 
		while($row=$result->fetch_assoc()){
			$datos[$num]=$row;
			//$datos[$num]['nombre_ciudadano']=$row['nombre']." ".$row['apellido_paterno']." ".$row['apellido_materno'];
			$num=$num+1;
		}
		if($num==0){
			$datos=null;
		}
		$conexion->close();
		return $datos;
	} 
