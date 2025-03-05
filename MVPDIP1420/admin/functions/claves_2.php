<?php
		function clave21($tipo){ 
			include 'db.php';
			$sql="SELECT * FROM claves_2 WHERE 1 = 1 ";
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			
			$tipo_gasto=$row['tipo_gasto'];
			$forma_tipo_gasto=$row['forma_tipo_gasto'];
			if($tipo=="tipos_gastos"){
				//servidor_correo
				$clave=preg_replace("/\((.*?)\)/i", "", $tipo_gasto);
				$textual=$tipo_gasto;
				$countTotal=strlen($tipo_gasto);
				$tabla=$tipo;
				$forma=$forma_tipo_gasto;
			}

			$tipo_gasto_asignado=$row['tipo_gasto_asignado'];
			$forma_tipo_gasto_asignado=$row['forma_tipo_gasto_asignado'];
			if($tipo=="tipos_gastos_asignados"){
				//servidor_correo
				$clave=preg_replace("/\((.*?)\)/i", "", $tipo_gasto_asignado);
				$textual=$tipo_gasto_asignado;
				$countTotal=strlen($tipo_gasto_asignado);
				$tabla=$tipo;
				$forma=$forma_tipo_gasto_asignado;
			}



			$sql="SELECT * FROM {$tabla} WHERE clave LIKE '%{$clave}%'  ";
			$result = $conexion->query($sql);
			while($row=$result->fetch_assoc()){
				$row['clave'];
				$tablaCalve = str_replace($clave,"", $row['clave']);
				if(strlen($coincidencias[1]) == strlen($tablaCalve)){
					$arrayNumero[]= $tablaCalve;
				} 
			}
			sort($arrayNumero);
			foreach ($arrayNumero as $key => $value) {
				$numero= $value; 
			}
			$numero=$numero+1; 
			
			$forma = strtolower($forma);
			$arrayClave['tipo']=$forma;
			if($forma=='automatico'){
				$arrayClave['input']='disabled="disabled"';
				$arrayClave['clave']=$clave.str_pad($numero, strlen($coincidencias[1]),0,STR_PAD_LEFT);
			}else{
				$arrayClave['input']='';
				$arrayClave['clave']='';
			}
			$conexion->close();
			return $arrayClave;
		}
		function clave2($tipo){ 
			include 'db.php';
			$sql="SELECT * FROM claves_2 WHERE 1 = 1 ";
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			
			$tipo_gasto=$row['tipo_gasto'];
			$forma_tipo_gasto=$row['forma_tipo_gasto'];
			if($tipo=="tipos_gastos"){
				//empleado
				$clave=preg_replace("/\((.*?)\)/i", "", $tipo_gasto);
				$textual=$tipo_gasto;
				$countTotal=strlen($tipo_gasto);
				$tabla=$tipo;
				$forma=$forma_tipo_gasto;
			}

			$tipo_gasto_asignado=$row['tipo_gasto_asignado'];
			$forma_tipo_gasto_asignado=$row['forma_tipo_gasto_asignado'];
			if($tipo=="tipos_gastos_asignados"){
				//empleado
				$clave=preg_replace("/\((.*?)\)/i", "", $tipo_gasto_asignado);
				$textual=$tipo_gasto_asignado;
				$countTotal=strlen($tipo_gasto_asignado);
				$tabla=$tipo;
				$forma=$forma_tipo_gasto_asignado;
			}


			$control_gasto_ingreso=$row['control_gasto_ingreso'];
			$forma_control_gasto_ingreso=$row['forma_control_gasto_ingreso'];
			if($tipo=="control_gastos_ingresos"){
				//empleado
				$clave=preg_replace("/\((.*?)\)/i", "", $control_gasto_ingreso);
				$textual=$control_gasto_ingreso;
				$countTotal=strlen($control_gasto_ingreso);
				$tabla=$tipo;
				$forma=$forma_control_gasto_ingreso;
			}

			$clave; 
			preg_match('/\((.+)\)/', $textual, $coincidencias);
			$coincidencias[1]; 
			strlen($coincidencias[1]); 

			//preg_replace("/\[(.*?)\]/i", "", $empleado);


			$sql="SELECT * FROM {$tabla} WHERE clave LIKE '%{$clave}%'  ";
			$result = $conexion->query($sql);
			while($row=$result->fetch_assoc()){
				$row['clave'];
				$tablaCalve = str_replace($clave,"", $row['clave']);
				if(strlen($coincidencias[1]) == strlen($tablaCalve)){
					$arrayNumero[]= $tablaCalve;
				} 
			}
			sort($arrayNumero);
			foreach ($arrayNumero as $key => $value) {
				$numero= $value; 
			}
			$numero=$numero+1; 
			
			$forma = strtolower($forma);
			$arrayClave['tipo']=$forma;
			if($forma=='automatico'){
				$arrayClave['input']='disabled="disabled"';
				$arrayClave['clave']=$clave.str_pad($numero, strlen($coincidencias[1]),0,STR_PAD_LEFT);
			}else{
				$arrayClave['input']='';
				$arrayClave['clave']='';
			}
			$conexion->close();
			return $arrayClave;
		}
		function clave2ValidadorSistema($clave=null,$tipo=null,$id_diferente=null){ 
			include 'db.php';
			$sql="SELECT * FROM {$tipo} WHERE clave = '{$clave}' ";
			if($id_diferente!=""){
				$sql.=" AND id != {$id_diferente} ";
			}
			$sql;
			$resultado = $conexion->query($sql); 
			$row=$resultado->fetch_assoc();
			if($row['id']==""){
				$existe=false;
			}else{
				$existe=true;
			}
			$conexion->close();
			return $existe; 
		}
		function clave2Id($clave=null,$tipo=null){
			include 'db.php';
			$sql="SELECT * FROM {$tipo} WHERE 1 = 1 ";
			if($clave !=""){
				$sql.=" AND clave = '{$clave}' ";
			}
			$resultado = $conexion->query($sql); 
			$row=$resultado->fetch_assoc();
			if($row['id']=="" && $clave==""){
				$existe=false;
			}else{
				$existe=$row['id'];
			}
			$conexion->close();
			return $existe; 
		}

		function clave2IdDatos($clave=null,$tipo=null){
			include 'db.php';
			$sql="SELECT * FROM {$tipo} WHERE 1 = 1 ";
			if($clave !=""){
				$sql.=" AND clave = '{$clave}' ";
			}
			$resultado = $conexion->query($sql); 
			$row=$resultado->fetch_assoc();
			$existe=$row;
			$conexion->close();
			return $existe; 
		}


		function clave2Denominacion($clave=null,$tipo=null){
			include 'db.php';
			$sql="SELECT * FROM {$tipo} WHERE clave = '{$clave}'  ";
			$resultado = $conexion->query($sql); 
			$row=$resultado->fetch_assoc();
			if($row['id']==""){
				$existe=false;
			}else{
				$existe=$row['denominacion'];
			}
			$conexion->close();
			return $existe; 
		}
		function clave2Datos(){
			include 'db.php';
			$sql="SELECT * FROM claves_2 WHERE 1 = 1 ";
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			
			$datos=$row;
			$conexion->close();
			return $datos; 
		}
?>