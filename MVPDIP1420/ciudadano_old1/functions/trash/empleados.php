<?php
		function empleados($id_empleado=null) {
			include 'db.php';  
			$id;
			$select[$id_empleado]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT * FROM empleados e  WHERE 1 AND e.tipo=1 AND e.codigo_plataforma='{$codigo_plataforma}'  ";
			 

			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_array()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombre']." ".$row['apellido_paterno']." ".$row['apellido_materno']." Alias ".$row['seudonimo']."</option> ";
			} 
			$conexion->close();
			return $return;
		}

		function empleadoDatos($id_empleado=null){
			include 'db.php'; 
			$sql .="SELECT *,CONCAT_WS(' ',nombre,apellido_paterno,apellido_materno) nombre_empleado  FROM empleados  WHERE codigo_plataforma='{$codigo_plataforma}'";
			if($id_empleado!=""){
				$sql.= " AND id={$id_empleado}";
			}
			$resultado = $conexion->query($sql); 
			$row=$resultado->fetch_array();
			foreach($row as $key => $value){
				if(is_numeric($key)) unset($row[$key]);
			}
			$datos=$row;
			$conexion->close();
			return $datos;

		}

?>