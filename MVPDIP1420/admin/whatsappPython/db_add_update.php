<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/configuracion.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','whatsapp_python',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["whatsapp_python_data"][0] as $keyPrincipal => $atributo) {
		$_POST["whatsapp_python_data"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$sql ="SELECT * FROM whatsapp_python WHERE 1 = 1 ";
	$result = $conexion->query($sql);
	$row=$result->fetch_assoc();
	//$_POST["whatsapp_python_data"][0]['referencia_importacion']=$row['referencia_importacion'];
	if(!empty($id)){ 
		$_POST["whatsapp_python_data"][0]['id']=$id;
		$registroCompara= registrosCompara("whatsapp_python",$_POST["whatsapp_python_data"][0],1);
		unset($_POST["whatsapp_python_data"][0]['id']);
	}else{
		$registroCompara=true;
	}

 
	if($registroCompara){
		if(!empty($_POST["whatsapp_python_data"][0])){
			$_POST["whatsapp_python_data"][0]['codigo_plataforma']=$codigo_plataforma;
			//checamos si tiene un registro si no es update
			$sql ="SELECT * FROM whatsapp_python WHERE 1 = 1 ";
			$result = $conexion->query($sql);
			$row=$result->fetch_assoc();
			$id=$row['id'];
			$success=true;
			$_POST["whatsapp_python_data"][0]['fechaR']=$fechaH;
			if(empty($id)){ 
				//agrega
				$tipo="Insert";
				$fields_pdo = "`".implode('`,`', array_keys($_POST["whatsapp_python_data"][0]))."`";
				$values_pdo = "'".implode("','", $_POST["whatsapp_python_data"][0])."'";
				$inset_whatsapp_python= "INSERT INTO whatsapp_python ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$inset_whatsapp_python=$conexion->query($inset_whatsapp_python);
				$num=$conexion->affected_rows;
				if(!$inset_whatsapp_python || $num=0){
					$success=false;
					echo "ERROR inset_whatsapp_python"; 
					var_dump($conexion->error);
				}
				$id=$_POST["whatsapp_python_data"][0]['id_whatsapp_python']=$conexion->insert_id;
				$fields_pdo = "`".implode('`,`', array_keys($_POST["whatsapp_python_data"][0]))."`";
				$values_pdo = "'".implode("','", $_POST["whatsapp_python_data"][0])."'";
				$inset_whatsapp_python_historicos= "INSERT INTO whatsapp_python_historicos ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$success=$inset_whatsapp_python_historicos=$conexion->query($inset_whatsapp_python_historicos);
				$num=$conexion->affected_rows;
				if(!$inset_whatsapp_python_historicos || $num=0){
					$success=false;
					echo "ERROR inset_whatsapp_python_historicos"; 
					var_dump($conexion->error);
				}
			}else{
				//edita
				$tipo="Update";
				$_POST["whatsapp_python_data"][0]['fechaR']=$fechaH;
				foreach($_POST["whatsapp_python_data"][0] as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}


				$conexion->autocommit(FALSE);
				$update_whatsapp_python = "UPDATE whatsapp_python SET ". join(",",$valueSets) . " WHERE id=".$id;
				$update_whatsapp_python=$conexion->query($update_whatsapp_python);
				$num=$conexion->affected_rows;
				if(!$update_whatsapp_python || $num=0){
					$success=false;
					echo "ERROR update_whatsapp_python"; 
					var_dump($conexion->error);
				}

				$_POST["whatsapp_python_data"][0]['id_whatsapp_python']=$id;
				unset($_POST["whatsapp_python_data"][0]['id']);
				$fields_pdo = "`".implode('`,`', array_keys($_POST["whatsapp_python_data"][0]))."`";
				$values_pdo = "'".implode("','", $_POST["whatsapp_python_data"][0])."'";
				$inset_whatsapp_python_historicos= "INSERT INTO whatsapp_python_historicos ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$success=$inset_whatsapp_python_historicos=$conexion->query($inset_whatsapp_python_historicos);
				$num=$conexion->affected_rows;
				if(!$inset_whatsapp_python_historicos || $num=0){
					$success=false;
					echo "ERROR inset_whatsapp_python_historicos"; 
					var_dump($conexion->error);
				}
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'whatsapp_python',$id,$tipo,'',$fechaH);
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

	}else{
		echo "SINCAMBIOS";
	}