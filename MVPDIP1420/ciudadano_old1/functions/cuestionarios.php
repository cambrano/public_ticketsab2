<?php
		function cuestionarios($id=null) {
			include 'db.php'; 
			$id;
			$select[$id]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT * FROM cuestionarios WHERE 1 = 1 ";
		
			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombre']."</option> ";
			} 
			$conexion->close();
			return $return;
		}

		function cuestionarioDatos($id=null,$id_encuesta=null){
			include 'db.php';
			$sql=("SELECT * FROM cuestionarios WHERE 1 = 1 ");
			if($id_encuesta!=""){
				$sql.=" AND id_encuesta='{$id_encuesta}' ";
			}
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}


		function cuestionarioPreguntaDatos($id=null,$id_encuesta=null){ 
			include 'db.php'; 
			$sql="SELECT 
					c.id,
					c.pregunta,
					c.campo,
					( SELECT COUNT(*) FROM cuestionarios_respuestas cr WHERE cr.id_cuestionario = c.id ) num_respuestas
				FROM cuestionarios c WHERE 1 = 1 ";

			if($id!=""){
				$sql.=" AND c.id='{$id}' ";
			}

			if($id_encuesta!=""){
				$sql.=" AND c.id_encuesta='{$id_encuesta}' ";
			}

			$sql;
			$result = $conexion->query($sql); 
			while($row=$result->fetch_assoc()){
				$datos[]=$row; 
			} 
			$conexion->close();
			return $datos;
		}

		function cuestionariosDatos($id=null,$id_encuesta=null){ 
			include 'db.php'; 
			$sql="SELECT * FROM cuestionarios WHERE 1 = 1 ";

			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}

			if($id_encuesta!=""){
				$sql.=" AND id_encuesta='{$id_encuesta}' ";
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

		function cuestionariosIdDatos($id=null,$id_encuesta=null,$orden=null){ 
			include 'db.php'; 
			$sql="SELECT * FROM cuestionarios WHERE 1 = 1 ";

			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}

			if($id_encuesta!=""){
				$sql.=" AND id_encuesta='{$id_encuesta}' ";
			}

			if($orden!=""){
				$sql.=" ORDER BY {$orden} ";
			}

			$result = $conexion->query($sql); 
			$num=0; 
			while($row=$result->fetch_assoc()){
				$datos[$num]=$row;
				$num = $num + 1;
			}
			if($num==0){
				$datos=null;
			}
			$conexion->close();
			return $datos;
		}

		function cuestionarioNombre($id=null){
			include 'db.php';
			$sql=("SELECT * FROM cuestionarios WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row['nombre']; 
			return $datos;
		}


		function cuestionarioClaveVerificacion($clave=null,$id=null,$tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM cuestionarios WHERE 1 = 1 ");
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