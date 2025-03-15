<?php
		function secciones($id_seccion=null) {
			include 'db.php';
			include 'functions/db.php'; 
			 
			$select[$id_seccion]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT * FROM secciones WHERE 1 AND status=1 ";
			 

			$result = $conexion->query($sql);  
			while($row=$result->fetch_array()){
				$sel=$row['id'];
				$nombre=str_replace("_"," ",$row['seccion']);
				$nombre=str_replace("empleados","tatuadores",$nombre);
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".ucwords($nombre)."</option> ";
			} 
			$conexion->close();
			return $return;
		}
		
		function seccionNombre($id_seccion){
			include 'db.php'; 
			include 'functions/db.php'; 
			$sql=("SELECT * FROM secciones WHERE id='$id_seccion'");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_array();
			$nombre=str_replace("_"," ",$row['seccion']);
			$nombre=str_replace("empleados","tatuadores",$nombre);
			$conexion->close();
			return $nombre;
		}

		 
?>