<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/configuracion.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','switch_operaciones',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	

	//metemos los valores para que se no tengamos error
	foreach($_POST["switch_operaciones"][0] as $keyPrincipal => $atributo) {
		$_POST["switch_operaciones"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$sql ="SELECT * FROM switch_operaciones WHERE 1 = 1 ";
	$result = $conexion->query($sql);
	$row=$result->fetch_assoc();
	$id=$row['id'];
	$_POST["switch_operaciones"][0]['referencia_importacion']=$row['referencia_importacion'];
	if(!empty($id)){ 
		$_POST["switch_operaciones"][0]['id']=$id;
		$registroCompara= registrosCompara("switch_operaciones",$_POST["switch_operaciones"][0],1);
		unset($_POST["switch_operaciones"][0]['id']);
	}else{
		$registroCompara=true;
	}

	
	
	if($registroCompara){
		if(!empty($_POST["switch_operaciones"][0])){
			$_POST["switch_operaciones"][0]['codigo_plataforma']=$codigo_plataforma;
			//checamos si tiene un registro si no es update
			$sql ="SELECT * FROM switch_operaciones WHERE 1 = 1 ";
			$result = $conexion->query($sql);
			$row=$result->fetch_assoc();
			$id=$row['id'];
			$success=true;
			$_POST["switch_operaciones"][0]['fechaR']=$fechaH;
			if(empty($id)){ 
				//agrega
				$tipo="Insert";
				$fields_pdo = "`".implode('`,`', array_keys($_POST["switch_operaciones"][0]))."`";
				$values_pdo = "'".implode("','", $_POST["switch_operaciones"][0])."'";
				$inset_switch_operaciones= "INSERT INTO switch_operaciones ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$inset_switch_operaciones=$conexion->query($inset_switch_operaciones);
				$num=$conexion->affected_rows;
				if(!$inset_switch_operaciones || $num=0){
					$success=false;
					echo "ERROR inset_switch_operaciones"; 
					var_dump($conexion->error);
				}
				$id=$_POST["switch_operaciones"][0]['id_switch_operacion']=$conexion->insert_id;
				$fields_pdo = "`".implode('`,`', array_keys($_POST["switch_operaciones"][0]))."`";
				$values_pdo = "'".implode("','", $_POST["switch_operaciones"][0])."'";
				$inset_switch_operaciones_historicos= "INSERT INTO switch_operaciones_historicos ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$success=$inset_switch_operaciones_historicos=$conexion->query($inset_switch_operaciones_historicos);
				$num=$conexion->affected_rows;
				if(!$inset_switch_operaciones_historicos || $num=0){
					$success=false;
					echo "ERROR inset_switch_operaciones_historicos"; 
					var_dump($conexion->error);
				}
			}else{
				
				//edita
				$tipo="Update";
				$_POST["switch_operaciones"][0]['fechaR']=$fechaH;
				foreach($_POST["switch_operaciones"][0] as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}


				$conexion->autocommit(FALSE);
				$update_switch_operaciones = "UPDATE switch_operaciones SET ". join(",",$valueSets) . " WHERE id=".$id;
				$update_switch_operaciones=$conexion->query($update_switch_operaciones);
				$num=$conexion->affected_rows;
				if(!$update_switch_operaciones || $num=0){
					$success=false;
					echo "ERROR update_switch_operaciones"; 
					var_dump($conexion->error);
				}

				$_POST["switch_operaciones"][0]['id_switch_operacion']=$id;
				unset($_POST["switch_operaciones"][0]['id']);
				$fields_pdo = "`".implode('`,`', array_keys($_POST["switch_operaciones"][0]))."`";
				$values_pdo = "'".implode("','", $_POST["switch_operaciones"][0])."'";
				$inset_switch_operaciones_historicos= "INSERT INTO switch_operaciones_historicos ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$success=$inset_switch_operaciones_historicos=$conexion->query($inset_switch_operaciones_historicos);
				$num=$conexion->affected_rows;
				if(!$inset_switch_operaciones_historicos || $num=0){
					$success=false;
					echo "ERROR inset_switch_operaciones_historicos"; 
					var_dump($conexion->error);
				}
			}

			if($success){
				
				$log= logUsuario($_COOKIE["id_usuario"],'switch_operaciones',$id,$tipo,'',$fechaH);
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