<?php
		function modulos($id_modulo=null,$id_seccion=null) {
			include 'db.php'; 
			include '../functions/db.php'; 
			$select[$id_modulo]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT * FROM modulos WHERE status=1 ";
			$sql.=" AND id_seccion='{$id_seccion}' ";

			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$nombre=str_replace("_"," ",$row['modulo']);
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".ucwords($nombre)."</option> ";
			} 
			$conexion->close();
			return $return;
		}
		function moduloNombre($id_modulo=null){
			include 'db.php'; 
			include '../functions/db.php'; 
			$sql=("SELECT * FROM modulos WHERE id='$id_modulo'");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$nombre=str_replace("_"," ",$row['modulo']);
			$conexion->close();
			return $nombre;
		}
?>