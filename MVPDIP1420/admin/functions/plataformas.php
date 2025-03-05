<?php
	function validar_plataforma_vista($idL,$tablaL,$folderL,$fileL,$codigo_plataformaL) {
		include 'db.php'; 

		$sql = "SELECT * FROM plataformas WHERE plataforma = '{$codigo_plataformaL}';";
		$resultado = $conexion->query($sql);
		$row = $resultado->fetch_assoc();
		if($row['tipo']=='x'){
			//suporte ve todo
			$datos = true;
		}else{
			$sql ="SELECT id  FROM {$tablaL}  WHERE codigo_plataforma = '{$codigo_plataformaL}' AND id ='{$idL}' ";
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			if($row['id']==''){
				$datos = false;
			}else{
				$datos = true;
			}
		}
		$conexion->close();
		if($datos == false ){
			echo $div = "<div style='padding: 10px;background-color: #f44336; color: white;font-size: 8pt;cursor: pointer;'>No tiene permiso </div>";
			echo $return = "<script type='text/javascript'>
				$('#homebody').load('{$folderL}/{$fileL}.php');
				</script>";
			//die;
		}
		return $datos;
	}

	function validar_codigo_plataforma($codigo_plataformaL){
		include 'db.php'; 
		$sql = "SELECT * FROM plataformas WHERE plataforma = '{$codigo_plataformaL}';";
		$resultado = $conexion->query($sql);
		$row = $resultado->fetch_assoc();
		if($row['tipo']=='x'){
			//suporte ve todo
			$datos = true;
		}else{
			$datos = false;
		}
		return $datos;
	}

	function plataformas(){
		include 'db.php'; 
		$sql = "SELECT * FROM plataformas ;";
		$result = $conexion->query($sql);  
		while($row=$result->fetch_assoc()){
			$sel=$row['id'];
			$return .="<option ".$select[$sel]." value='".$row['plataforma']."' >".str_replace("_"," - ",$row['nombre'])."</option> ";
		} 
		$conexion->close();
		return $return;
	}

	function plataformasDatos($id=null,$tipo=null){
		include 'db.php'; 
		$sql = "SELECT * FROM plataformas WHERE 1";
		if(!empty($id)){
			$sql .= " AND id = '".$id."'";
		}
		if(!empty($tipo)){
			$sql .= " AND tipo = '".$tipo."'";
		}
		$result = $conexion->query($sql);  
		while($row=$result->fetch_assoc()){
			$datos[] = $row;
		} 
		$conexion->close();
		return $datos;
		
	}