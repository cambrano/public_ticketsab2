<?php
	function registrosCompara($tabla=null,$registro=null,$tipo=null) {
		include 'db.php'; 

		//foreach($registro as $keyPrincipal => $atributo) {
		//	$registro[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		//}

		if($registro['id']!=""){
			if($tipo==1){
				if($registro['status']=="x"){
					$registro['status']=0;
				}
				foreach($registro as $key => $value) {
					if($key !='id'){
						//$valueSetsx[] = $key . " = BINARY '" . $value . "'";
						if($value==""){
							$valueSetsx[] = " (".$key . " IS NULL  OR ".$key . " =  '' )";
						}else{
							$valueSetsx[] = $key . " = BINARY '" . $value . "'";
						}

					}else{
						$id=$value;
					}
				}
				//"<pre>";
				$search = "SELECT * FROM {$tabla} WHERE ". join(" AND ",$valueSetsx) . " AND id=".$id;
				$search;
				//"</pre>";
				$resultSearch = $conexion->query($search);
				if($conexion->error!=""){
					var_dump($conexion->error);
				}
				$rowSearch=$resultSearch->fetch_assoc();
				$id=$rowSearch['id'];
				if(empty($id)){
					$return= true;
				}else{
					$return= false;
				}
			}
		}
		$conexion->close();
		return $return;
	}
?>