<?php
	function sexos($id=null,$sin_seleccione=null) {
		include 'db.php'; 
		$id;
		$select[$id]='selected="selected"';
		if($sin_seleccione==""){
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
		}
		$sql="SELECT 
		id,
		nombre
		FROM sexos WHERE 1 = 1";
		$result = $conexion->query($sql);  
		while($row=$result->fetch_assoc()){
			$sel=$row['id'];
			$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombre']."</option> ";
		} 
		$conexion->close();
		return $return;
	}
	