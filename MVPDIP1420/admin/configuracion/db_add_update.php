<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/configuracion.php";
	include __DIR__."/../functions/files_size.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/efs.php";
	$moduloAccionPermisos = moduloAccionPermisos('configuracion','configuracion_inicial',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}


	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST as $keyPrincipal => $atributo) {
			$_POST[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$configuracionDatos=configuracionDatos();
		if($configuracionDatos['id']==""){
			$tipo='Insert';
			$imagen=$_POST['imagen'];
			unset($_POST['imagen']);
			if($imagen!=""){
				//con imagen
				if($_FILES['imagen']['name'] != ""){
					if($_FILES['imagen']['error']==UPLOAD_ERR_OK) {
						if(is_uploaded_file($_FILES['imagen']['tmp_name'])){
							if($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES["imagen"]["type"] == "image/png" ){
								$configuracion=$_POST;
								$configuracion['file_size']=$_FILES['imagen']['size'];
								$configuracion['codigo_plataforma']=$codigo_plataforma;
								$configuracion['fechaR']=$fechaH;
								$fields_pdo = "`".implode('`,`', array_keys($configuracion))."`";
								$values_pdo = "'".implode("','", $configuracion)."'";
								$insert_configuracion= "INSERT INTO configuracion ($fields_pdo) VALUES ($values_pdo);";
								$conexion->autocommit(FALSE);
								$success=$insert_configuracion=$conexion->query($insert_configuracion);
								$num=$conexion->affected_rows;
								if(!$insert_configuracion || $num=0){
									$success=false;
									echo "ERROR insert_configuracion"; 
									var_dump($conexion->error);
								}

								$id = $conexion->insert_id;
								$insert_configuracion_historicos= "INSERT INTO configuracion_historicos ($fields_pdo) VALUES ($values_pdo);";
								$conexion->autocommit(FALSE);
								$success=$insert_configuracion_historicos=$conexion->query($insert_configuracion_historicos);
								$num=$conexion->affected_rows;
								if(!$insert_configuracion_historicos || $num=0){
									$success=false;
									echo "ERROR insert_configuracion_historicos"; 
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
				$configuracion=$_POST;
				$configuracion['file_size']=$_FILES['imagen']['size'];
				$configuracion['codigo_plataforma']=$codigo_plataforma;
				$configuracion['fechaR']=$fechaH;
				$fields_pdo = "`".implode('`,`', array_keys($configuracion))."`";
				$values_pdo = "'".implode("','", $configuracion)."'";
				$insert_configuracion= "INSERT INTO configuracion ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$success=$insert_configuracion=$conexion->query($insert_configuracion);
				$num=$conexion->affected_rows;
				if(!$insert_configuracion || $num=0){
					$success=false;
					echo "ERROR insert_configuracion"; 
					var_dump($conexion->error);
				}
				$id=$conexion->insert_id;
				$fields_pdo = "`".implode('`,`', array_keys($configuracion))."`";
				$values_pdo = "'".implode("','", $configuracion)."'";
				$insert_configuracion_historicos= "INSERT INTO configuracion_historicos ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$success=$insert_configuracion_historicos=$conexion->query($insert_configuracion_historicos);
				$num=$conexion->affected_rows;
				if(!$insert_configuracion_historicos || $num=0){
					$success=false;
					echo "ERROR insert_configuracion_historicos"; 
					var_dump($conexion->error);
				}
			}
		}else{
			$tipo='Update';
			$imagen=$_POST['imagen'];
			unset($_POST['imagen']);
			$configuracion= array_merge($configuracionDatos,$_POST);
			if($imagen!=""){
				$configuracion['fechaR']=$fechaH;
				$configuracion['file_size']=$_FILES['imagen']['size'];
				if( registrosCompara("configuracion",$configuracion,1)){
					foreach($configuracion as $key => $value) {
						if($key !='id'){
							$valueSets[] = $key . " = '" . $value . "'";
						}else{
							$id=$value;
						}
					}
					$update_configuracion = "UPDATE configuracion SET ". join(",",$valueSets) . "  WHERE id=".$id;
					$conexion->autocommit(FALSE);
					$update_configuracion=$conexion->query($update_configuracion);
					$num=$conexion->affected_rows;
					if(!$update_configuracion || $num=0){
						$success=false;
						echo "ERROR update_configuracion"; 
						var_dump($conexion->error);
					}
					unset($configuracion['id']);
					$fields_pdo = "`".implode('`,`', array_keys($configuracion))."`";
					$values_pdo = "'".implode("','", $configuracion)."'";
					$insert_configuracion_historicos= "INSERT INTO configuracion_historicos ($fields_pdo) VALUES ($values_pdo);";
					$conexion->autocommit(FALSE);
					$success=$insert_configuracion_historicos=$conexion->query($insert_configuracion_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_configuracion_historicos || $num=0){
						$success=false;
						echo "ERROR insert_configuracion_historicos"; 
						var_dump($conexion->error);
					}
				}
			}else{
				if( registrosCompara("configuracion",$configuracion,1)){
					foreach($configuracion as $key => $value) {
						if($key !='id'){
							$valueSets[] = $key . " = '" . $value . "'";
						}else{
							$id=$value;
						}
					}
					$update_configuracion['fechaR']=$fechaH;
					$update_configuracion = "UPDATE configuracion SET ". join(",",$valueSets) . "  WHERE id=".$id;
					$conexion->autocommit(FALSE);
					$update_configuracion=$conexion->query($update_configuracion);
					$num=$conexion->affected_rows;
					if(!$update_configuracion || $num=0){
						$success=false;
						echo "ERROR update_configuracion"; 
						var_dump($conexion->error);
					}
					unset($configuracion['id']);
					$fields_pdo = "`".implode('`,`', array_keys($configuracion))."`";
					$values_pdo = "'".implode("','", $configuracion)."'";
					$insert_configuracion_historicos= "INSERT INTO configuracion_historicos ($fields_pdo) VALUES ($values_pdo);";
					$conexion->autocommit(FALSE);
					$success=$insert_configuracion_historicos=$conexion->query($insert_configuracion_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_configuracion_historicos || $num=0){
						$success=false;
						echo "ERROR insert_configuracion_historicos"; 
						var_dump($conexion->error);
					}
				}else{
					die;
				}
			}
		}

		if($success && $imagen!="" && $configuracionDatos['file_size']<=$_FILES['imagen']['size'] ){
			$filesizeData=filesizeData();
			$capacidad_sistema_restante=$filesizeData['capacidad_sistema_file_restante'];
			$file_size_up=0;
			$capacidad_sistema_restante=$capacidad_sistema_restante-$_FILES['imagen']['size'];
			$file_size_up=$file_size_up+$_FILES['imagen']['size']-$configuracionDatos['file_size'];

			if($filesizeData['file_size_restante_tipo_print']=="MB"){
				$configuracionDatos['file_size']=$configuracionDatos['file_size']/1000000;
			}

			if($filesizeData['file_size_restante_tipo_print']=="GB"){
				$configuracionDatos['file_size']=$configuracionDatos['file_size']/1000000000;
			}

			if($capacidad_sistema_restante<0 && $file_size_up>0){
				$file_size_up_mb=$file_size_up/1000000;
				$file_size_up_mb=number_format($file_size_up_mb,4);
				//$capacidad_sistema_restante=$capacidad_sistema_restante/1000000;
				echo "Ya no cuenta con capacidad de almacenamiento si desea mas favor de comunicarte con nosotros gracias. Su capacidad de almacenamiento es ".number_format($filesizeData['file_size_capacidad_print'],0)." ".$filesizeData['file_size_capacidad_tipo_print']." , le resta ".number_format($filesizeData['file_size_restante_print']+$configuracionDatos['file_size'],4)." ".$filesizeData['file_size_restante_tipo_print']." y Usted quiere subir ".$file_size_up_mb." MB.";
				$conexion->rollback();
				$conexion->close();
				die;
			}

			$rutaTemporal=$_FILES['imagen']['tmp_name'];
			$rutaEfs = rutaEfs();
			$rutaDestino = $rutaEfs."logo_principal.png";
			if(!move_uploaded_file($rutaTemporal,$rutaDestino)){
				$success=false;
				echo "ERROR, Imagen Intente Subir otra vez";
			}
		}

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'configuracion_inicial',$id,$tipo,'',$fechaH);
			if($log==true){
				echo "SI";
				$conexion->commit();
				$conexion->close();
			}else{
				echo "NO";
				$conexion->rollback();
				$conexion->close();
			}
		}else{
			echo "NO";
			$conexion->rollback();
			$conexion->close();
		}


	}
?>