<?php
		function equipos_usuariosDatos($id=null,$id_equipo=null,$usuario=null,$status=null,$orden=null,$limit=null){
			include 'db.php'; 
			$sql="SELECT 
					*
					FROM equipos_usuarios eu WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND eu.id = '{$id}' ";
			}
			if($id_equipo!=""){
				$sql.=" AND eu.id_equipo = '{$id_equipo}' ";
			}
			if($usuario!=""){
				$sql.=" AND eu.usuario = '{$usuario}' ";
			}
			if($status!=""){
				$sql.=" AND eu.status = '{$status}' ";
			}

			if($orden!=""){
				$sql.= " ORDER BY {$orden}";
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
?>