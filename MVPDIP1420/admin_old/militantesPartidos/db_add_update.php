<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/productos.php";
	include __DIR__."/../functions/files_size.php";
	include __DIR__."/../functions/genid.php";
	if(!empty($_POST)){

		foreach($_POST as $keyPrincipal => $atributo) {
			$_POST[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}

		$success=true;
		if($_POST['id']=='' ){
			$tipo='Insert';
			$imagen=$_POST['imagen'];
			$tipo=$_POST['permiso'];
			unset($_POST['imagen']);
			unset($_POST['permiso']);
			if($imagen!=""){
				//con imagen
				if($_FILES['imagen']['name'] != ""){
					if($_FILES['imagen']['error']==UPLOAD_ERR_OK) {
						if(is_uploaded_file($_FILES['imagen']['tmp_name'])){
							if($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES["imagen"]["type"] == "image/png" ){
								$producto=$_POST;
								unset($producto['id']);
								$extension = pathinfo($_FILES['imagen']['name']);
								$producto['name']='web_producto-'.$mk_id.'_'.$cod32.'.'.$extension['extension'];
								$rutaDestino='../../../img/productos/'.$producto['name'];
								
								
								$producto['nombre']=$_POST['nombre'];
								$producto['orden']=$_POST['orden'];
								$producto['file']=$rutaDestino;
								$producto['type']=$_FILES['imagen']['type'];
								$producto['file_size']=$_FILES['imagen']['size'];
								$producto['codigo_empresa']=$codigo_empresa;
								$producto['fechaR']=$fechaH;
								$producto['id_tipo_producto']=$_POST['id_tipo_producto'];
								$producto['id_sub_tipo_producto']=$_POST['id_sub_tipo_producto'];
								$producto['id_marca']=$_POST['id_marca'];
								$producto['descripcion']=$_POST['descripcion'];



								$fields_pdo = "`".implode('`,`', array_keys($producto))."`";
								$values_pdo = "'".implode("','", $producto)."'";
								$insert_productos= "INSERT INTO productos ($fields_pdo) VALUES ($values_pdo);";
								$conexion->autocommit(FALSE);
								$success=$insert_productos=$conexion->query($insert_productos);
								$num=$conexion->affected_rows;
								if(!$insert_productos || $num=0){
									$success=false;
									echo "ERROR insert_productos"; 
									var_dump($conexion->error);
								}

							}else{
								echo "Error no puede subir archivos con la extension ".pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION).', Solo puedes subir archivos PNG o JPG';
							}
						}else{
							echo "Error Subir Archivo";
						}
					}else{
						echo "Error Subir Archivo";
					}
				}

			}else{
				//sin imagen
				$producto=$_POST;
				unset($producto['id']);
				$producto['nombre']=$_POST['nombre'];
				$producto['orden']=$_POST['orden'];
				$producto['codigo_empresa']=$codigo_empresa;
				$producto['id_tipo_producto']=$_POST['id_tipo_producto'];
				$producto['id_sub_tipo_producto']=$_POST['id_sub_tipo_producto'];
				$producto['id_marca']=$_POST['id_marca'];
				$producto['descripcion']=$_POST['descripcion'];
				$producto['fechaR']=$fechaH;
				$fields_pdo = "`".implode('`,`', array_keys($producto))."`";
				$values_pdo = "'".implode("','", $producto)."'";
				$insert_productos= "INSERT INTO productos ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$success=$insert_productos=$conexion->query($insert_productos);
				$num=$conexion->affected_rows;
				if(!$insert_productos || $num=0){
					$success=false;
					echo "ERROR insert_productos"; 
					var_dump($conexion->error);
				}
			}
		}else{
			$productoDatos=productoDatos($_POST['id']);
			unset($productoDatos['tipo_producto']);
			unset($productoDatos['sub_tipo_producto']);
			$tipo='Update';
			$imagen=$_POST['imagen'];
			$tipo=$_POST['permiso'];
			unset($_POST['imagen']);
			unset($_POST['permiso']);
			$producto= array_merge($productoDatos,$_POST);
			$producto['id'] = $productoDatos['id'];
			if($imagen!=""){
				$file_delete = $productoDatos['file'];
				$extension = pathinfo($_FILES['imagen']['name']);
				$producto['name']='web_producto-'.$mk_id.'_'.$cod32.'.'.$extension['extension'];
				$rutaDestino='../../../img/productos/'.$producto['name'];
				$producto['nombre']=$_POST['nombre'];
				$producto['orden']=$_POST['orden'];
				$producto['file']=$rutaDestino;
				$producto['type']=$_FILES['imagen']['type'];
				$producto['file_size']=$_FILES['imagen']['size'];
				$producto['codigo_empresa']=$codigo_empresa;
				$producto['fechaR']=$fechaH;
				$producto['id_tipo_producto']=$_POST['id_tipo_producto'];
				$producto['id_sub_tipo_producto']=$_POST['id_sub_tipo_producto'];
				if($producto['id_sub_tipo_producto']==''){
					$producto['id_sub_tipo_producto'] =0;
				}
				$producto['id_marca']=$_POST['id_marca'];
				$producto['descripcion']=$_POST['descripcion'];

				foreach($producto as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
				$update_productos = "UPDATE productos SET ". join(",",$valueSets) . "  WHERE id=".$id;
				$conexion->autocommit(FALSE);
				$update_productos=$conexion->query($update_productos);
				$num=$conexion->affected_rows;
				if(!$update_productos || $num=0){
					$success=false;
					echo "ERROR update_productos"; 
					var_dump($conexion->error);
				}
				

			}else{
				if($producto['id_sub_tipo_producto']==''){
					$producto['id_sub_tipo_producto'] =0;
				}
				foreach($producto as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
				$producto['fechaR']=$fechaH;
				$update_productos = "UPDATE productos SET ". join(",",$valueSets) . "  WHERE id=".$id;
				$conexion->autocommit(FALSE);
				$update_productos=$conexion->query($update_productos);
				$num=$conexion->affected_rows;
				if(!$update_productos || $num=0){
					$success=false;
					echo "ERROR update_productos"; 
					var_dump($conexion->error);
				}
				
			}
			if($success){
				unlink($file_delete);
				if (file_exists($file_delete)) {
					$success=false;
					echo "Error Eliminar Imagen";
				}
			}
		}

		if($success && $imagen!="" ){
			$filesizeData=filesizeData();
			$capacidad_sistema_restante=$filesizeData['capacidad_sistema_file_restante'];
			$file_size_up=0;
			$capacidad_sistema_restante=$capacidad_sistema_restante-$_FILES['imagen']['size'];
			$file_size_up=$file_size_up+$_FILES['imagen']['size']-$productoDatos['file_size'];

			if($filesizeData['file_size_restante_tipo_print']=="MB"){
				$productoDatos['file_size']=$productoDatos['file_size']/1000000;
			}

			if($filesizeData['file_size_restante_tipo_print']=="GB"){
				$productoDatos['file_size']=$productoDatos['file_size']/1000000000;
			}

			if($capacidad_sistema_restante<0 && $file_size_up>0){
				$file_size_up_mb=$file_size_up/1000000;
				$file_size_up_mb=number_format($file_size_up_mb,4);
				//$capacidad_sistema_restante=$capacidad_sistema_restante/1000000;
				echo "Ya no cuenta con capacidad de almacenamiento si desea mas favor de comunicarte con productos gracias. Su capacidad de almacenamiento es ".number_format($filesizeData['file_size_capacidad_print'],0)." ".$filesizeData['file_size_capacidad_tipo_print']." , le resta ".number_format($filesizeData['file_size_restante_print']+$productoDatos['file_size'],4)." ".$filesizeData['file_size_restante_tipo_print']." y Usted quiere subir ".$file_size_up_mb." MB.";
				$conexion->rollback();
				$conexion->close();
				die;
			}

			$rutaTemporal=$_FILES['imagen']['tmp_name'];
			if(!move_uploaded_file($rutaTemporal,$rutaDestino)){
				$success=false;
				echo "ERROR, Imagen Intente Subir otra vez";
			}
			if (!file_exists($rutaDestino)) {
				$success=false;
				echo "ERROR, Imagen Intente Subir otra vez";
			}
		}

		if($success){
			echo "SI";
			$conexion->commit();
			$conexion->close();
		}else{
			echo "NO";
			$conexion->rollback();
			$conexion->close();
		}


	}
?>