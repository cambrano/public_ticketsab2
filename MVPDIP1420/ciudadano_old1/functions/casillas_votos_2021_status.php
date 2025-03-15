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

		function casilla_voto_2021_statusDatosAll($id=null,$id_casilla_voto_2021=null,$script=null){
			include 'db.php';
			$sql=("SELECT * FROM casillas_votos_2021_status WHERE 1 = 1 ");
			 
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			if($id_casilla_voto_2021!=""){
				$sql.=" AND id_casilla_voto_2021='{$id_casilla_voto_2021}' ";
			}

			if($script!=""){
				$sql.=" $script ";
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