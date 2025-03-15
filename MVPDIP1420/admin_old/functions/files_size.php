<?php
	function filesizeBD(){
		include 'db.php';
		$sql="
			SELECT 
				table_schema 'base_datos', 
				SUM(data_length + index_length )-0  'file_size' 
			FROM 
				information_schema.TABLES 
			WHERE 
				table_schema = '{$db}' 
			GROUP BY 
				table_schema; ";
		$sql;
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$conexion->close();
		$datos=$row;
		return $datos;
	}

	function filesizeArchivo(){
		include 'db.php';
		$sql="
			SELECT DISTINCT
				TABLE_NAME AS tabla, COLUMN_NAME AS columna
			FROM
				INFORMATION_SCHEMA.COLUMNS
			WHERE
				COLUMN_NAME LIKE '%file_size%'
				AND
				TABLE_NAME NOT LIKE '%_historicos%'
					AND TABLE_SCHEMA = '{$db}' ;";

		$sql;
		$result = $conexion->query($sql); 
		$num=0; 
		while($row=$result->fetch_assoc()){
			$datos[$num]=$row;
			$num=$num+1;
		}
		if($num==0){
			$datos=null;
		}

		$file_size['file_size']=0;
		foreach ($datos as $key => $value) {
			$tabla=$value['tabla'];
			$columna=$value['columna'];
			$sql="SELECT  COALESCE(SUM({$columna}),0) columna_suma FROM {$tabla} ";
			$result = $conexion->query($sql);  
			$row=$result->fetch_assoc();
			$file_size['file_size']=$file_size['file_size'] + $row['columna_suma'];
		}
		
		return $file_size;
	}

	function filesizeData(){
		include 'db.php';
		$filesizeBD=filesizeBD();
		$filesizeArchivo=filesizeArchivo();

		$sql=("SELECT files_capacidad,database_capacidad  FROM configuracion_paquete WHERE 1 = 1  ");
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$files_capacidad=$row['files_capacidad'];
		$database_capacidad=$row['database_capacidad'];


		//$datos['file_size_db']=$filesizeBD['file_size'];
		$datos['file_size_files']=$filesizeArchivo['file_size'];
		$datos['file_size_sistema']=$datos['file_size_files'];
		$datos['capacidad_sistema_file']=$row['files_capacidad'];
		$datos['capacidad_sistema_file_restante']=$row['files_capacidad']-($datos['file_size_files']);
		$datos['file_size_sistema_file_mb']=$datos['file_size_sistema']/1000000;
		$datos['capacidad_sistema_file_mb']=$datos['capacidad_sistema_file']/1000000;
		$datos['capacidad_sistema_file_restante_mb']=$datos['capacidad_sistema_file_restante']/1000000;
		$datos['capacidad_sistema_file_porcentaje']=(100*$datos['file_size_sistema'])/$datos['capacidad_sistema_file'];

		$datos['file_size_sistema_file_gb']=$datos['file_size_sistema']/1000000000;
		$datos['capacidad_sistema_file_gb']=$datos['capacidad_sistema_file']/1000000000;
		$datos['capacidad_sistema_file_restante_gb']=$datos['capacidad_sistema_file_restante']/1000000000;

		//print
		$file_print=number_format($datos['file_size_sistema'],4,'.',',');
		$file_print=substr_count($file_print, ',');
		if($file_print<=2){
			$datos['file_size_tipo_print']="MB";
			$datos['file_size_print']=$datos['file_size_sistema_file_mb'];
		}
		if($file_print>=3){
			$datos['file_size_tipo_print']="GB";
			$datos['file_size_print']=$datos['file_size_sistema_file_gb'];
		}

		$file_print=number_format($datos['capacidad_sistema_file_restante'],4,'.',',');
		$file_print=substr_count($file_print, ',');
		if($file_print<=3 && ($datos['files_capacidad']< $datos['file_size_sistema']) ){
			$datos['file_size_restante_tipo_print']="MB";
			$datos['file_size_restante_print']=$datos['capacidad_sistema_file_restante_mb'];
		}
		if($file_print>=3 && ($datos['files_capacidad']==$datos['file_size_sistema']) ){
			$datos['file_size_restante_tipo_print']="GB";
			$datos['file_size_restante_print']=$datos['capacidad_sistema_file_restante_gb'];
		}

		$file_print=number_format($datos['capacidad_sistema_file'],4,'.',',');
		$file_print=substr_count($file_print, ',');
		if($file_print<=2){
			$datos['file_size_capacidad_tipo_print']="MB";
			$datos['file_size_capacidad_print']=$datos['capacidad_sistema_file_mb'];
		}
		if($file_print>=3){
			$datos['file_size_capacidad_tipo_print']="GB";
			$datos['file_size_capacidad_print']=$datos['capacidad_sistema_file_gb'];
		}

		//base de datos
		$datos['database_size']=$filesizeBD['file_size'];
		$datos['database_size_capacidad']=$database_capacidad;
		$datos['database_size_restante']=$database_capacidad-$filesizeBD['file_size'];
		$datos['database_size_porcentaje']=(100*$datos['database_size'])/$datos['database_size_capacidad'];

		$datos['database_size_mb']=$filesizeBD['file_size']/1000000;
		$datos['database_size_capacidad_mb']=$database_capacidad/1000000;
		$datos['database_size_restante_mb']=($database_capacidad-$filesizeBD['file_size'])/1000000;

		$datos['database_size_gb']=$filesizeBD['file_size']/1000000000;
		$datos['database_size_capacidad_gb']=$database_capacidad/1000000000;
		$datos['database_size_restante_gb']=($database_capacidad-$filesizeBD['file_size'])/1000000000;

		$datos['database_size_porcentaje']=(100*$datos['database_size'])/$datos['database_size_capacidad'];

		

		//print database
		$file_print=number_format($datos['database_size'],4,'.',',');
		$file_print=substr_count($file_print, ',');
		if($file_print<=2){
			$datos['database_size_tipo_print']="MB";
			$datos['database_size_print']=$datos['database_size_mb'];
		}
		if($file_print>=3){
			$datos['database_size_tipo_print']="GB";
			$datos['database_size_print']=$datos['database_size_gb'];
		}

		$file_print=number_format($datos['database_size_restante'],4,'.',',');
		$file_print=substr_count($file_print, ',');
		if($file_print<=3 && ($datos['database_size_capacidad']< $datos['database_size']) ){
			$datos['database_size_restante_tipo_print']="MB";
			$datos['database_size_restante_print']=$datos['database_size_restante_mb'];
		}
		if($file_print>=3 && ($datos['database_size_capacidad']==$datos['database_size']) ){
			$datos['database_size_restante_tipo_print']="GB";
			$datos['database_size_restante_print']=$datos['database_size_restante_gb'];
		}

		$file_print=number_format($datos['database_size_capacidad'],4,'.',',');
		$file_print=substr_count($file_print, ',');
		if($file_print<=2){
			$datos['database_size_capacidad_tipo_print']="MB";
			$datos['database_size_capacidad_print']=$datos['database_size_capacidad_mb'];
		}
		if($file_print>=3){
			$datos['database_size_capacidad_tipo_print']="GB";
			$datos['database_size_capacidad_print']=$datos['database_size_capacidad_gb'];
		}



		return$datos;
	}


	function filesizeArchivoold(){
		include 'db.php';
		$sql="
			SELECT DISTINCT
				TABLE_NAME AS tabla, 
				COLUMN_NAME AS columna,
				SUM(COLUMN_NAME) file_size
			FROM
				INFORMATION_SCHEMA.COLUMNS
			WHERE
				COLUMN_NAME LIKE '%file_size%'
					AND TABLE_SCHEMA = 'sada_1';";

		$sql;
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$conexion->close();
		$datos=$row;
		
		return $datos;
	}
?>