<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/configuracion.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','api_sms',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["api_sms"][0] as $keyPrincipal => $atributo) {
		$_POST["api_sms"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$sql ="SELECT * FROM api_sms WHERE 1 = 1 ";
	$result = $conexion->query($sql);
	$row=$result->fetch_assoc();
	$id=$row['id'];
	//$_POST["api_sms"][0]['referencia_importacion']=$row['referencia_importacion'];
	if(!empty($id)){ 
		$_POST["api_sms"][0]['id']=$id;
		$registroCompara= registrosCompara("api_sms",$_POST["api_sms"][0],1);
		unset($_POST["api_sms"][0]['id']);
	}else{
		$registroCompara=true;
	}
	
	if($registroCompara){
		if(!empty($_POST["api_sms"][0])){
			$_POST["api_sms"][0]['codigo_plataforma']=$codigo_plataforma;
			//checamos si tiene un registro si no es update
			$sql ="SELECT * FROM api_sms WHERE 1 = 1 ";
			$result = $conexion->query($sql);
			$row=$result->fetch_assoc();
			$id=$row['id'];
			$success=true;
			$_POST["api_sms"][0]['fechaR']=$fechaH;
			if(empty($id)){ 
				//agrega
				$tipo="Insert";
				$fields_pdo = "`".implode('`,`', array_keys($_POST["api_sms"][0]))."`";
				$values_pdo = "'".implode("','", $_POST["api_sms"][0])."'";
				$inset_api_sms= "INSERT INTO api_sms ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$inset_api_sms=$conexion->query($inset_api_sms);
				$num=$conexion->affected_rows;
				if(!$inset_api_sms || $num=0){
					$success=false;
					echo "ERROR inset_api_sms"; 
					var_dump($conexion->error);
				}
				$id=$_POST["api_sms"][0]['id_api_sms']=$conexion->insert_id;
				$fields_pdo = "`".implode('`,`', array_keys($_POST["api_sms"][0]))."`";
				$values_pdo = "'".implode("','", $_POST["api_sms"][0])."'";
				$inset_api_sms_historicos= "INSERT INTO api_sms_historicos ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$success=$inset_api_sms_historicos=$conexion->query($inset_api_sms_historicos);
				$num=$conexion->affected_rows;
				if(!$inset_api_sms_historicos || $num=0){
					$success=false;
					echo "ERROR inset_api_sms_historicos"; 
					var_dump($conexion->error);
				}
			}else{
				foreach($_POST["api_sms"][0] as $keyPrincipal => $atributo) {
					if($atributo !=''){
						$_POST["api_sms"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
					}else{
						$_POST["api_sms"][0][$keyPrincipal] = 0;
					}
				}
				//edita
				$tipo="Update";
				$_POST["api_sms"][0]['fechaR']=$fechaH;
				foreach($_POST["api_sms"][0] as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}


				$conexion->autocommit(FALSE);
				$update_api_sms = "UPDATE api_sms SET ". join(",",$valueSets) . " WHERE id=".$id;
				$update_api_sms=$conexion->query($update_api_sms);
				$num=$conexion->affected_rows;
				if(!$update_api_sms || $num=0){
					$success=false;
					echo "ERROR update_api_sms"; 
					var_dump($conexion->error);
				}

				$_POST["api_sms"][0]['id_api_sms']=$id;
				unset($_POST["api_sms"][0]['id']);
				$fields_pdo = "`".implode('`,`', array_keys($_POST["api_sms"][0]))."`";
				$values_pdo = "'".implode("','", $_POST["api_sms"][0])."'";
				$inset_api_sms_historicos= "INSERT INTO api_sms_historicos ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$success=$inset_api_sms_historicos=$conexion->query($inset_api_sms_historicos);
				$num=$conexion->affected_rows;
				if(!$inset_api_sms_historicos || $num=0){
					$success=false;
					echo "ERROR inset_api_sms_historicos"; 
					var_dump($conexion->error);
				}
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'api_sms',$id,$tipo,'',$fechaH);
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