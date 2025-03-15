<?php
	function secciones_ine_ciudadanos($id=null) {
		include 'db.php'; 
		$id;
		$select[$id]='selected="selected"';
		$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
		$sql="SELECT 
		id,
		CONCAT(sim.nombre,' ',sim.apellido_paterno,' ',sim.apellido_materno ) nombre_completo,
		(SELECT tm.nombre FROM tipos_ciudadanos tm WHERE tm.id = sim.id_tipo_ciudadano ) tipo_ciudadano
		FROM secciones_ine_ciudadanos sim WHERE 1 AND sim.codigo_plataforma='{$codigo_plataforma}' ORDER BY nombre_completo ASC";
	
		$result = $conexion->query($sql);  
		 
		while($row=$result->fetch_array()){
			$sel=$row['id'];
			$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombre_completo']." - ".$row['tipo_ciudadano']."</option> ";
		} 
		$conexion->close();
		return $return;
	}


	function seccion_ine_ciudadanoDatos($id_seccion_ine_ciudadano=null){ 
		include 'db.php'; 
		$sql="
			SELECT * ,
			CONCAT_WS(' ',sim.nombre,sim.apellido_paterno,sim.apellido_materno ) nombre_completo,
			(SELECT si.numero FROM secciones_ine si WHERE si.id = sim.id_seccion_ine) seccion,
			(SELECT tm.nombre FROM tipos_ciudadanos tm WHERE tm.id = sim.id_tipo_ciudadano ) tipo_ciudadano
		FROM secciones_ine_ciudadanos sim WHERE sim.id='$id_seccion_ine_ciudadano' AND sim.codigo_plataforma='{$codigo_plataforma}'  ";
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_array();
		$datos=$row; 
		$conexion->close();
		return $datos;
	}

	function seccion_ine_ciudadanoClaveVerificacion($clave=null,$id=null,$tipo=null){
		include 'db.php';
		$sql=("SELECT * FROM secciones_ine_ciudadanos WHERE codigo_plataforma='{$codigo_plataforma}' ");
		if($clave!=""){
			$sql.=" AND clave='{$clave}' ";
		}
		if($id!=""){
			$sql.=" AND id !='{$id}' ";
		}
		$sql;
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_array();
		foreach($row as $key => $value){
			if(is_numeric($key)) unset($row[$key]);
		}
		$datos=$row['id']; 
		return $datos;
	}


	function secciones_ine_ciudadanosDatosArray($registros=null,$orderby=null,$limit=null) {
		include 'db.php'; 
		$sql="
			SELECT 
			*,
			CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno ) nombre_completo,
			(SELECT si.numero FROM secciones_ine si WHERE si.id = e.id_seccion_ine) seccion,
			(SELECT tm.nombre FROM tipos_ciudadanos tm WHERE tm.id = e.id_tipo_ciudadano ) tipo_ciudadano,
			(SELECT tm.municipio FROM municipios tm WHERE tm.id = e.id_municipio AND tm.id_estado=31 ) municipio
			FROM secciones_ine_ciudadanos e
			WHERE 1
			";

		foreach ($registros as $key => $value) {
			//echo $key;
			//echo "-";
			//echo $value;
			//echo "<br>";
			if($value !=""){
				if($key!="fecha_1" && $key!="fecha_2"){
					$sql.= " AND  {$key} = '{$value}' ";
				}
				if($key=="fecha_1"){
					$fecha_1 = $value;
				}
				if($key=="fecha_2"){
					$fecha_2 = $value;
				}
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
		while($row=$result->fetch_array()){
			foreach($row as $key => $value){
				if(is_numeric($key)) unset($row[$key]);
			}
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