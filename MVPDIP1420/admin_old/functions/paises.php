<?php
		function paises($id_pais=null) {
			include 'db.php'; 
			$id;
			if(empty($id_pais) ){
				$id_pais=141;
			}
			$select[$id_pais]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT * FROM paises WHERE 1 = 1 ";
			if(!empty($id_pais) ){
				$sql .=" AND id={$id_pais} ";
			}

			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['pais']."</option> ";
			} 
			$conexion->close();
			return $return;
		}

		function paisesCodigoTelefonico($id_pais=null) {
			include 'db.php'; 
			$id;
			$select[$id_pais]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT * FROM paises WHERE 1 = 1 ";
			if(!empty($id_pais) ){
				//$sql .=" AND id={$id_pais} ";
			}

			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				if($row['codigo_telefono']=="NULL"){
					$row['codigo_telefono']=0;
				}
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['pais']." +".$row['codigo_telefono']."</option> ";
			} 
			$conexion->close();
			return $return;
		}

		function paisNombre($id_pais){
			include 'db.php'; 
			$sql=("SELECT * FROM paises WHERE id='$id_pais'");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$conexion->close();
			return $row['pais'];
		}

		function paisNombreCortoTelefonoNumero($paisCorto){
			include 'db.php'; 
			$sql=("SELECT id,codigo_telefono FROM paises WHERE iso2 LIKE '%{$paisCorto}%'");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$conexion->close();
			return $row;
		}

		function paisCodigoTelefonicoNumero($id_pais){
			include 'db.php'; 
			$sql=("SELECT * FROM paises WHERE id='$id_pais'");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$conexion->close();
			return $row['codigo_telefono'];
		}

		function paisCorrecto($pais){
			include 'db.php'; 
			$sql=("SELECT * FROM paises WHERE pais LIKE '%{$pais}%'");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$conexion->close();
			return $row['pais'];
		}

		function paisId($pais){
			include 'db.php'; 
			$sql=("SELECT * FROM paises WHERE pais LIKE '%{$pais}%'");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$conexion->close();
			return $row['id'];
		}
?>