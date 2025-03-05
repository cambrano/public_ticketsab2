<?php
		function equipo_directorioDatos($id=null,$id_equipo=null,$id_directorio=null,$status=null){
			include 'db.php';
			$sql=("SELECT * FROM equipos_directorios WHERE 1 = 1 ");
			if($id!=""){
				
				$sql.=" AND id='{$id}' ";
			}
			if($id_equipo!=""){
				
				$sql.=" AND id_equipo='{$id_equipo}' ";
			}
			if($id_directorio!=""){
				
				$sql.=" AND id_directorio='{$id_directorio}' ";
			}
			if($status!=""){
				
				$sql.=" AND status='{$status}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}


?>