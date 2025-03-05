<?php
		function empleados($id_empleado=null) {
			include 'db.php';  
			$id;
			$select[$id_empleado]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT * FROM empleados e  WHERE e.tipo=1    ";
			 

			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombre']." ".$row['apellido_paterno']." ".$row['apellido_materno']." Alias ".$row['seudonimo']."</option> ";
			} 
			$conexion->close();
			return $return;
		}

		function empleadoDatos($id_empleado=null){
			include 'db.php'; 
			$sql .="SELECT *,CONCAT_WS(' ',nombre,apellido_paterno,apellido_materno) nombre_empleado  FROM empleados  WHERE 1 = 1 ";
			if($id_empleado!=""){
				$sql.= " AND id={$id_empleado}";
			}
			$resultado = $conexion->query($sql); 
			$row=$resultado->fetch_assoc();
			$datos=$row;
			$conexion->close();
			return $datos;
		}

		function empleadoClaveVerificacion($clave=null,$id=null,$tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM empleados WHERE 1 = 1 ");
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