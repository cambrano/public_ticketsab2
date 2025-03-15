<?php
	function secciones_ine_ciudadanos_categoriasDatos($id=null,$id_seccion_ine_ciudadano=null){ 
		include 'db.php'; 
		$sql="SELECT * FROM secciones_ine_ciudadanos_categorias WHERE 1 = 1 ";

		if($id!=""){
			$sql.=" AND id='{$id}' ";
		}

		if($id_seccion_ine_ciudadano!=""){
			$sql.=" AND id_seccion_ine_ciudadano='{$id_seccion_ine_ciudadano}' ";
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