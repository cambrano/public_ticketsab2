<?php
		function distritos_federales_parametrosDatos($id=null,$id_distrito_federal=null,$orden=null){
			include 'db.php'; 
			$sql="SELECT 
					*
					FROM distritos_federales_parametros WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND id = '{$id}' ";
			}
			if($id_distrito_federal!=""){
				$sql.=" AND id_distrito_federal = '{$id_distrito_federal}' ";
			}

			if($orden!=""){
				$sql.= " ORDER BY {$orden}";
			}
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
			$conexion->close();
			return $datos;
		}

		function distritos_federales_parametrosDatosMapa_old($id=null,$id_distrito_federal=null){
			include 'db.php'; 
			$sql="SELECT 
					*
					FROM distritos_federales_parametros WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND id = '{$id}' ";
			}
			if($id_distrito_federal!=""){
				$sql.=" AND id_distrito_federal = '{$id_distrito_federal}' ";
			}

			$sql.= " ORDER BY id_distrito_federal,orden DESC ";
			$result = $conexion->query($sql); 
			$num=0; 
			while($row=$result->fetch_assoc()){
				$datos[$row['id_distrito_federal']][]=$row;
				$num=$num+1;
			}
			if($num==0){
				$datos=null;
			}
			$conexion->close();
			return $datos;
		}

		function distritos_federales_parametrosDatosMapa($id=null,$id_distrito_federal=null){
			include 'db.php'; 
			$sql="SELECT id_distrito_federal,tipo,orden,latitud,longitud FROM distritos_federales_parametros sip WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND sip.id = '{$id}' ";
			}
			if($id_distrito_federal!=""){
				$sql.=" AND sip.id_distrito_federal = '{$id_distrito_federal}' ";
			}
			if($orden!=""){
				$sql.= " ORDER BY sip.{$orden}";
			}
			$sql;
			$result = $conexion->query($sql); 
			$num=0; 
			while($row=$result->fetch_assoc()){
				$datos[$row['id_distrito_federal']][$row['tipo']][$row['orden']]=$row;
				$num=$num+1;
			}
			if($num==0){
				$datos=null;
			}

			$sql;
			$conexion->close();
			return $datos;
		}
?>