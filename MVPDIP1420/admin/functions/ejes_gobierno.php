<?php
		function ejes_gobierno($id=null) {
			include 'db.php'; 
			$id;
			$select[$id]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT * FROM ejes_gobierno WHERE 1 = 1 ";
		
			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['numero']." - ".$row['nombre']."</option> ";
			} 
			$conexion->close();
			return $return;
		}

		function eje_gobiernoDatos($id=null,$id_eje_gobierno=null){
			include 'db.php';
			$sql=("SELECT * FROM ejes_gobierno WHERE 1 = 1 ");
			if($id_eje_gobierno!=""){
				$sql.=" AND id_eje_gobierno='{$id_eje_gobierno}' ";
			}
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

		function eje_gobiernoNombre($id=null){
			include 'db.php';
			$sql=("SELECT * FROM ejes_gobierno WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row['nombre']; 
			return $datos;
		}


?>