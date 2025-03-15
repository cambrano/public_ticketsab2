<?php
		function campanas_mailing_tipos_categorias_ciudadanos($id=null) {
			include 'db.php'; 
			$id;
			$select[$id]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT * FROM campanas_mailing_tipos_categorias_ciudadanos WHERE 1 = 1 ";
		
			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombre']."</option> ";
			} 
			$conexion->close();
			return $return;
		}

		function campanas_mailing_tipos_categorias_ciudadanosDatos($id=null,$id_campana_mailing=null){
			include 'db.php';
			$sql=("SELECT * FROM campanas_mailing_tipos_categorias_ciudadanos WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			if($id_campana_mailing!=""){
				$sql.=" AND id_campana_mailing='{$id_campana_mailing}' ";
			}
			$sql;
			$resultado = $conexion->query($sql);
			while($row=$resultado->fetch_assoc()){
				$datos[]=$row;
			} 
			$conexion->close(); 
			return $datos;
		}

		function campanas_mailing_tipos_categorias_ciudadanosIdDatos($id=null,$id_campana_mailing=null){
			include 'db.php';
			$sql=("SELECT * FROM campanas_mailing_tipos_categorias_ciudadanos WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			if($id_campana_mailing!=""){
				$sql.=" AND id_campana_mailing='{$id_campana_mailing}' ";
			}
			$sql;
			$resultado = $conexion->query($sql);
			while($row=$resultado->fetch_assoc()){
				$datos[$row['id_tipo_categoria_ciudadano']]=$row;
			} 
			$conexion->close(); 
			return $datos;
		}
?>