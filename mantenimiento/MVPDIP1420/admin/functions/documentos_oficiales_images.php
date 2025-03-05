<?php
		function documento_oficial_imagesDatos($id=null,$id_documento_oficial=null){
			include 'db.php'; 
			$sql="SELECT 
					*
					FROM documentos_oficiales_images hi WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND hi.id = '{$id}' ";
			}
			if($id_documento_oficial!=""){
				$sql.=" AND hi.id_documento_oficial = '{$id_documento_oficial}' ";
			}

			
			$sql;
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