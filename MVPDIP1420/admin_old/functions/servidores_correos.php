<?php
	function servidores_correos($id=null,$sin_seleccione=null) {
		include 'db.php'; 
		$id;
		$select[$id]='selected="selected"';
		if($sin_seleccione==""){
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
		}
		$sql="SELECT * FROM servidores_correos WHERE 1 = 1 ";
	
		$result = $conexion->query($sql);  
		 
		while($row=$result->fetch_assoc()){
			$sel=$row['id'];
			$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombre']."</option> ";
		} 
		$conexion->close();
		return $return;
	}

	function servidor_correoDatos($id=null,$id_servidor_correo=null){
		include 'db.php';
		$sql=("SELECT * FROM servidores_correos WHERE 1 = 1 ");
		if($id_servidor_correo!=""){
			$sql.=" AND id_servidor_correo='{$id_servidor_correo}' ";
		}
		if($id!=""){
			$sql.=" AND id='{$id}' ";
		}
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$datos=$row; 
		return $datos;
	}

	function servidor_correoClaveVerificacion($clave=null,$id=null,$tipo=null){
		include 'db.php';
		$sql=("SELECT * FROM servidores_correos WHERE 1 = 1 ");
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