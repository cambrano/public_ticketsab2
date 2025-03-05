<?php
		function preguntas_2022_revocacion_mandato($id=null,$tipo=null,$sin_seleccione=null) {
			include 'db.php'; 
			$id;
			$select[$id]='selected="selected"';
			if($sin_seleccione==""){
				$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			}
			$sql="SELECT * FROM preguntas_2022_revocacion_mandato WHERE 1 = 1 ";
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

		function pregunta_2022_revocacion_mandatoDatos($id=null,$id_pregunta_2022_revocacion_mandato=null){
			include 'db.php';
			$sql=("SELECT * FROM preguntas_2022_revocacion_mandato WHERE 1 = 1 ");
			if($id_pregunta_2022_revocacion_mandato!=""){
				$sql.=" AND id_pregunta_2022_revocacion_mandato='{$id_pregunta_2022_revocacion_mandato}' ";
			}
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

		function pregunta_2022_revocacion_mandatoNombre($id=null){
			include 'db.php';
			$sql=("SELECT * FROM preguntas_2022_revocacion_mandato WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row['nombre']; 
			return $datos;
		}

		function pregunta_2022_revocacion_mandatoClaveVerificacion($clave=null,$id=null,$tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM preguntas_2022_revocacion_mandato WHERE 1 = 1 ");
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

		function pregunta_2022_revocacion_mandatoPrincipalDatos(){
			include 'db.php';
			$sql=("SELECT * FROM preguntas_2022_revocacion_mandato WHERE principal=1 ");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

		function preguntas_2022_revocacion_mandatoDatos($id=null,$orden=null,$tipo=null){
			include 'db.php'; 
			$sql=("SELECT * FROM preguntas_2022_revocacion_mandato WHERE 1 = 1 ");
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