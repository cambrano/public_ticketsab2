<?php
	function usuario_permisosDatos($id=null,$id_usuario_modulo=null,$status=null,$id_modulo=null,$id_empleado=null) {
		include 'db.php'; 
		$sql="SELECT * FROM usuarios_permisos WHERE 1 = 1 ";
		if($id!=""){
			$sql.=" AND id='$id' ";
		}
		if($id_usuario_modulo!=""){
			$sql.=" AND id_usuario_modulo='$id_usuario_modulo' ";
		}
		if($id_modulo!=""){
			$sql.=" AND id_modulo='$id_modulo' ";
		}
		if($id_empleado!=""){
			$sql.=" AND id_empleado='$id_empleado' ";
		}
		if($status!=""){
			$sql.=" AND status='$status' ";
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