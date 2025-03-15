<?php
		function partidos_2022($id=null,$tipo=null,$sin_seleccione=null) {
			include 'db.php'; 
			$id;
			$select[$id]='selected="selected"';
			if($sin_seleccione==""){
				$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			}
			$sql="SELECT * FROM partidos_2022 WHERE 1 = 1 ";
			if($tipo!=''){
				$sql.= " AND tipo='{$tipo}'; ";
			}
		
			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".str_replace("_"," - ",$row['nombre_corto'])."</option> ";
			} 
			$conexion->close();
			return $return;
		}

		function partido_2022Datos($id=null,$id_partido_2022=null){
			include 'db.php';
			$sql=("SELECT * FROM partidos_2022 WHERE 1 = 1 ");
			if($id_partido_2022!=""){
				$sql.=" AND id_partido_2022='{$id_partido_2022}' ";
			}
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

		function partido_2022Nombre($id=null){
			include 'db.php';
			$sql=("SELECT * FROM partidos_2022 WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row['nombre']; 
			return $datos;
		}

		function partido_2022ClaveVerificacion($clave=null,$id=null,$tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM partidos_2022 WHERE 1 = 1 ");
			if($clave!=""){
				$sql.=" AND clave='{$clave}' ";
			}
			if($id!=""){
				$sql.=" AND id !='{$id}' ";
			}
			$sql;
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row['id']; 
			return $datos;
		}

		function partido_2022PrincipalDatos(){
			include 'db.php';
			$sql=("SELECT * FROM partidos_2022 WHERE principal=1 ");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

		function partido_2022PrincipalTipoEleccionDatos($tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM partidos_2022 WHERE principal=1 ");
			if($tipo!=""){
				$sql.=" AND tipo={$tipo} ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

		function partidos_2022Datos($id=null,$orden=null,$tipo=null){
			include 'db.php'; 
			$sql=("SELECT * FROM partidos_2022 WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id={$id} ";
			}
			if($tipo!=""){
				$sql.=" AND tipo={$tipo} ";
			}
			if($orden!=""){
				$sql.=" ORDER BY {$orden} ";
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

?>