<?php
		function casillas_votos_2021_status($id=null) {
			include 'db.php'; 
			$id;
			$select[$id]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT * FROM casillas_votos_2021_status WHERE 1 = 1 ";
		
			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['status']."</option> ";
			} 
			$conexion->close();
			return $return;
		}

		function casilla_voto_2021_statusDatos($id=null){
			include 'db.php';
			$sql=("SELECT * FROM casillas_votos_2021_status WHERE 1 = 1 ");
			 
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}