<?php
		function militantes_partidos($id=null) {
			include 'db.php'; 
			$id;
			$select[$id]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT * FROM militantes_partidos WHERE 1 = 1 ";
		
			$result = $conexion->query($sql);  
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombre']."</option> ";
			} 
			$conexion->close();
			return $return;
		}

		function militantes_partidosDatos($id=null){
			include 'db.php';
			$sql="SELECT * FROM militantes_partidos sicpa WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND sicpa.id='{$id}' ";
			}
			$sql.=";";
			$resultado = $conexion->query($sql);
			while($row=$resultado->fetch_assoc()){
				$datos[]=$row; 
			} 
			$conexion->close();
			return $datos;
		}

		function militante_partidoDatos($id=null,$id_seccion_ine_ciudadano=null){
			include 'db.php';
			$sql="SELECT * ,
				(SELECT pa.nombre_corto FROM partidos_legados pa WHERE pa.id =  sicpa.id_partido_legado) partido_nombre_corto,
				(SELECT pa.nombre FROM partidos_legados pa WHERE pa.id =  sicpa.id_partido_legado) partido_nombre,
				(SELECT pa.logo FROM partidos_legados pa WHERE pa.id =  sicpa.id_partido_legado) partido_logo
				FROM militantes_partidos sicpa WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND sicpa.id='{$id}' ";
			}
			if($id_seccion_ine_ciudadano!=""){
				$sql.=" AND sicpa.id_seccion_ine_ciudadano='{$id_seccion_ine_ciudadano}' ";
			}
			$sql.=";";
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}


		function militante_partidoClaveVerificacion($clave=null,$id=null){
			include 'db.php';
			$sql=("SELECT * FROM militantes_partidos WHERE 1 = 1 ");
			if($clave!=""){
				$sql.=" AND clave='{$clave}' ";
			}
			if($id!=""){
				$sql.=" AND id !='{$id}' ";
			}
			$sql;
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row['id']; 
			return $datos;
		}


?>