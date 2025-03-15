<?php
		function partidos_2018($id=null,$tipo=null,$coalicion=null,$sin_seleccione=null) {
			include 'db.php'; 
			$id;
			$select[$id]='selected="selected"';
			if($sin_seleccione==""){
				$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			}
			$sql="SELECT * FROM partidos_2018 WHERE 1 = 1 ";
			if($tipo!=''){
				$sql .=" AND tipo = '{$tipo}' ";
			}

			if($coalicion='sin_coalicion'){
				$sql .=" AND clave_partidos_coaliciones = '' ";
			}
			if($coalicion='solo_coalicion'){
				$sql .=" AND clave_partidos_coaliciones = '' ";
			}
		
			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".str_replace("_"," - ",$row['nombre_corto'])."</option> ";
			} 
			$conexion->close();
			return $return;
		}

		function partido_2018Datos($id=null,$id_partido_2018=null){
			include 'db.php';
			$sql=("SELECT * FROM partidos_2018 WHERE 1 = 1 ");
			if($id_partido_2018!=""){
				$sql.=" AND id_partido_2018='{$id_partido_2018}' ";
			}
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

		function partido_2018Nombre($id=null){
			include 'db.php';
			$sql=("SELECT * FROM partidos_2018 WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row['nombre']; 
			return $datos;
		}

		function partido_2018ClaveVerificacion($clave=null,$id=null,$tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM partidos_2018 WHERE 1 = 1 ");
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

		function partido_2018PrincipalDatos(){
			include 'db.php';
			$sql=("SELECT * FROM partidos_2018 WHERE principal=1 ");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

		function partidos_2018CompletaDatos($id=null,$nombre_corto=null){
			include 'db.php';
			$sql=("SELECT * FROM partidos_2018 WHERE 1 ");
			if($id!=''){
				$sql.= " AND id = '{$id}' ";
			}
			if($nombre_corto!=''){
				$sql.= " AND nombre_corto = '{$nombre_corto}' ";
			}
			$result = $conexion->query($sql); 
			$num=0; 
			while($row=$result->fetch_assoc()){
				$datos[$num]=$row;
				$num=$num+1;
			}
			if($num==0){
				$datos=null;
			}
			return $datos;
		}

		function partido_2018PrincipalTipoEleccionDatos($tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM partidos_2018 WHERE principal=1 ");
			if($tipo!=""){
				$sql.=" AND tipo={$tipo} ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

		function partidos_2018Datos($id=null,$orden=null,$tipo=null){
			include 'db.php'; 
			$sql=("SELECT * FROM partidos_2018 WHERE 1 = 1 ");
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