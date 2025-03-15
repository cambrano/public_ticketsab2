<?php
	function configuracion_matriz_rentabilidad_secciones_ine_2024($id=null) {
		include 'db.php'; 
		$id;
		$select[$id]='selected="selected"';
		$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
		$sql="SELECT * FROM configuracion_matriz_rentabilidad_secciones_ine_2024 WHERE 1 = 1 ";
	
		$result = $conexion->query($sql);  
		 
		while($row=$result->fetch_assoc()){
			$sel=$row['id'];
			$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombre']."</option> ";
		} 
		$conexion->close();
		return $return;
	}

	function configuracion_matriz_rentabilidad_secciones_ine_2024Datos($id=null){
		include 'db.php';
		$sql="SELECT * FROM configuracion_matriz_rentabilidad_secciones_ine_2024 WHERE 1 = 1 ";
		if($id!=""){
			$sql.=" AND id='{$id}' ";
		}
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$datos=$row; 
		return $datos;
	}

?>