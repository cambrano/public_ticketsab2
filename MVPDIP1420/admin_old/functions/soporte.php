<?php
		function soporteDatos(){
			include 'db.php'; 
			$sql=("SELECT * FROM soporte  ");
			$sql;
			$result = $conexion->query($sql); 
			$num=0; 
			while($row=$result->fetch_assoc()){
				foreach($row as $key => $value){
					if($key=="telefono"){
						$num_tlf1 = substr($row[$key], 0, 3);
						$num_tlf2 = substr($row[$key], 3, 3);
						$num_tlf3 = substr($row[$key], 6, 4);
						$row['telefono_sp'] = "$num_tlf1 $num_tlf2 $num_tlf3 ";
					}
					if($key=="whatsapp"){
						$num_tlf1 = substr($row[$key], 0, 3);
						$num_tlf2 = substr($row[$key], 3, 3);
						$num_tlf3 = substr($row[$key], 6, 4);
						$row['whatsapp_sp'] = "$num_tlf1 $num_tlf2 $num_tlf3 ";
					}
				}
				$datos[$num]=$row;
				$num=$num+1;
			}
			if($num==0){
				$datos=null;
			}
			$conexion->close();
			return $datos;
		}

		function soporteDatos_out(){
			include 'db.php'; 
			$sql=("SELECT * FROM soporte ");
			$sql;
			$result = $conexion->query($sql); 
			$num=0; 
			while($row=$result->fetch_assoc()){
				foreach($row as $key => $value){
					if($key=="telefono"){
						$num_tlf1 = substr($row[$key], 0, 3);
						$num_tlf2 = substr($row[$key], 3, 3);
						$num_tlf3 = substr($row[$key], 6, 4);
						$row['telefono_sp'] = "$num_tlf1 $num_tlf2 $num_tlf3 ";
					}
					if($key=="whatsapp"){
						$num_tlf1 = substr($row[$key], 0, 3);
						$num_tlf2 = substr($row[$key], 3, 3);
						$num_tlf3 = substr($row[$key], 6, 4);
						$row['whatsapp_sp'] = "$num_tlf1 $num_tlf2 $num_tlf3 ";
					}
				}
				$datos[$num]=$row;
				$num=$num+1;
			}
			if($num==0){
				$datos=null;
			}
			$conexion->close();
			return $datos;
		}
?>