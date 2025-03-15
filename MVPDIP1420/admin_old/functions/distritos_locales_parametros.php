<?php
		function distritos_locales_parametrosDatos($id=null,$id_distrito_local=null,$orden=null){
			include 'db.php'; 
			$sql="SELECT 
					*
					FROM distritos_locales_parametros WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND id = '{$id}' ";
			}
			if($id_distrito_local!=""){
				$sql.=" AND id_distrito_local = '{$id_distrito_local}' ";
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

		function distritos_locales_parametrosDatosMapa_old($id=null,$id_distrito_local=null){
			include 'db.php'; 
			$sql="SELECT 
					*
					FROM distritos_locales_parametros WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND id = '{$id}' ";
			}
			if($id_distrito_local!=""){
				$sql.=" AND id_distrito_local = '{$id_distrito_local}' ";
			}

			$sql.= " ORDER BY id_distrito_local,orden DESC ";
			$result = $conexion->query($sql); 
			$num=0; 
			while($row=$result->fetch_assoc()){
				$datos[$row['id_distrito_local']][]=$row;
				$num=$num+1;
			}
			if($num==0){
				$datos=null;
			}
			$conexion->close();
			return $datos;
		}

		function distritos_locales_parametrosDatosMapa($id=null,$id_distrito_local=null){
			include 'db.php'; 
			$sql="SELECT id_distrito_local,tipo,orden,latitud,longitud,id FROM distritos_locales_parametros sip WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND sip.id = '{$id}' ";
			}
			if($id_distrito_local!=""){
				$sql.=" AND sip.id_distrito_local = '{$id_distrito_local}' ";
			}
			if($orden!=""){
				$sql.= " ORDER BY sip.{$orden}";
			}
			$sql;
			$result = $conexion->query($sql); 
			$num=0; 
			while($row=$result->fetch_assoc()){
				$datos[$row['id_distrito_local']][$row['tipo']][$row['orden']]=$row;
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