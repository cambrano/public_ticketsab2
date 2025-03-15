<?php
		function partidos_2016($id=null,$tipo=null,$coalicion=null,$sin_seleccione=null) {
			include 'db.php'; 
			$id;
			$select[$id]='selected="selected"';
			if($sin_seleccione==""){
				$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			}
			$sql="SELECT * FROM partidos_2016 WHERE 1 = 1 ";
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

		function partido_2016Datos($id=null,$id_partido_2016=null){
			include 'db.php';
			$sql=("SELECT * FROM partidos_2016 WHERE 1 = 1 ");
			if($id_partido_2016!=""){
				$sql.=" AND id_partido_2016='{$id_partido_2016}' ";
			}
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

		function partido_2016Nombre($id=null){
			include 'db.php';
			$sql=("SELECT * FROM partidos_2016 WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row['nombre']; 
			return $datos;
		}

		function partido_2016ClaveVerificacion($clave=null,$id=null,$tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM partidos_2016 WHERE 1 = 1 ");
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

		function partido_2016PrincipalDatos(){
			include 'db.php';
			$sql=("SELECT * FROM partidos_2016 WHERE principal=1 ");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

		function partidos_2016CompletaDatos($id=null,$nombre_corto=null){
			include 'db.php';
			$sql=("SELECT * FROM partidos_2016 WHERE 1 ");
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

		function partido_2016PrincipalTipoEleccionDatos($tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM partidos_2016 WHERE principal=1 ");
			if($tipo!=""){
				$sql.=" AND tipo={$tipo} ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

		function partidos_2016Datos($id=null,$orden=null,$tipo=null){
			include 'db.php'; 
			$sql=("SELECT * FROM partidos_2016 WHERE 1 = 1 ");
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