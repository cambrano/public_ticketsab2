<?php
		function cuestionarios_respuestas($id=null) {
			include 'db.php'; 
			$id;
			$select[$id]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT * FROM cuestionarios_respuestas WHERE 1 = 1 ";
		
			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombre']."</option> ";
			} 
			$conexion->close();
			return $return;
		}

		function cuestionario_respuestasIdDatos($id=null,$id_cuestionario=null,$id_encuesta=null,$orden=null){
			include 'db.php'; 
			$sql="SELECT 
					*
					FROM cuestionarios_respuestas hi WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND hi.id = '{$id}' ";
			}
			if($id_cuestionario!=""){
				$sql.=" AND hi.id_cuestionario = '{$id_cuestionario}' ";
			}

			if($id_encuesta!=""){
				$sql.=" AND hi.id_encuesta = '{$id_encuesta}' ";
			}

			if($orden!=""){
				$sql.=" ORDER BY {$orden} ";
			}
			 $sql;
			$result = $conexion->query($sql); 
			$num=0; 
			while($row=$result->fetch_assoc()){
				$datos[$row['id_cuestionario']][]=$row;
				$num=$num+1;
			}
			if($num==0){
				$datos=null;
			}
			$conexion->close();
			return $datos;
		}

		function cuestionario_respuestasDatos($id=null,$id_cuestionario=null,$id_encuesta=null,$orden=null){
			include 'db.php'; 
			$sql="SELECT 
					*
					FROM cuestionarios_respuestas hi WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND hi.id = '{$id}' ";
			}
			if($id_cuestionario!=""){
				$sql.=" AND hi.id_cuestionario = '{$id_cuestionario}' ";
			}

			if($id_encuesta!=""){
				$sql.=" AND hi.id_encuesta = '{$id_encuesta}' ";
			}

			if($orden!=""){
				$sql.=" ORDER BY {$orden} ";
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
			$conexion->close();
			return $datos;
		}

		function cuestionario_respuestaNombre($id=null){
			include 'db.php';
			$sql=("SELECT * FROM cuestionarios_respuestas WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row['nombre']; 
			return $datos;
		}


		function cuestionario_respuestaClaveVerificacion($clave=null,$id=null,$tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM cuestionarios_respuestas WHERE 1 = 1 ");
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


?>