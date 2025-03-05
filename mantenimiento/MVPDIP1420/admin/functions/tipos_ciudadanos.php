<?php
		function tipos_ciudadanos($id=null,$sin_seleccione=null) {
			include 'db.php'; 
			$id;
			$ids = explode(",", $id);
			if($sin_seleccione==""){
				$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			}
			$sql="SELECT id,nombre FROM tipos_ciudadanos WHERE 1 = 1 ;";
			$result = $conexion->query($sql);  
			while($row=$result->fetch_assoc()){
				if (in_array($row['id'], $ids)) {
					$return .="<option selected value='".$row['id']."' >".$row['nombre']."</option> ";
				}else{
					$return .="<option value='".$row['id']."' >".$row['nombre']."</option> ";
				}
			} 
			$conexion->close();
			return $return;
		}


		function tipos_ciudadanosDatos($id=null,$id_tipo_estructura_ciudadano=null){
			include 'db.php';
			$sql="SELECT * FROM tipos_ciudadanos WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			if($id_tipo_estructura_ciudadano!=""){
				$sql.=" AND id_tipo_estructura_ciudadano='{$id_tipo_estructura_ciudadano}' ";
			}
			$sql.=";";
			$resultado = $conexion->query($sql);
			while($row=$resultado->fetch_assoc()){
				$datos[]=$row; 
			} 
			$conexion->close();
			return $datos;
		}

		function tipo_ciudadanoDatos($id=null){
			include 'db.php';
			$sql=("SELECT * FROM tipos_ciudadanos WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$sql.=";";
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

		function tipo_ciudadanoNombre($id=null){
			include 'db.php';
			$sql=("SELECT * FROM tipos_ciudadanos WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$sql.=";";
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row['nombre']; 
			return $datos;
		}


		function tipo_ciudadanoClaveVerificacion($clave=null,$id=null,$tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM tipos_ciudadanos WHERE 1 = 1 ");
			if($clave!=""){
				$sql.=" AND clave='{$clave}' ";
			}
			if($id!=""){
				$sql.=" AND id !='{$id}' ";
			}
			$sql.=";";
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row['id']; 
			return $datos;
		}

		function tipos_ciudadanos_secciones_ineDatos($id_tipo_ciudadadano=null,$id_tipo_estructura_ciudadano = null,$id_seccion_ine=null){
			include 'db.php';
			$sql = "
				SELECT 
					COUNT(*) total,
					sic.id_seccion_ine,
					sic.id_tipo_ciudadano
				FROM secciones_ine_ciudadanos sic
				LEFT JOIN tipos_ciudadanos tc
				ON sic.id_tipo_ciudadano = tc.id
				WHERE 1
				
			";
			if($id_tipo_ciudadadano!=""){
				$sql .= " AND tc.id_tipo_estructura_ciudadano = ".$id_tipo_ciudadadano;
			}
			if($id_tipo_estructura_ciudadano!=""){
				$sql .= " AND tc.id_tipo_estructura_ciudadano = ".$id_tipo_estructura_ciudadano;
			}
			if($id_seccion_ine!=""){
				$sql .= " AND sic.id_seccion_ine IN (".$id_seccion_ine.")";
			}
			$sql .= " GROUP BY sic.id_seccion_ine,sic.id_tipo_ciudadano;";
			
			$resultado = $conexion->query($sql);
			while($row=$resultado->fetch_assoc()){
				$datos[$row['id_seccion_ine']][$row['id_tipo_ciudadano']]=$row; 
			}
			$conexion->close();
			return $datos;
		}


?>