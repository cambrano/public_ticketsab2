<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";

	//metemos los valores para que se no tengamos error
	foreach($_POST["notificaciones_sistema"][0] as $keyPrincipal => $atributo) {
		$_POST["notificaciones_sistema"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$sql ="SELECT * FROM notificaciones_sistema WHERE 1 = 1 ";
	$result = $conexion->query($sql);
	$row=$result->fetch_assoc();
	$id=$row['id'];
	if(!empty($id)){ 
		$_POST['notificacion_sistema'][0]['id']=$id;
		$registroCompara= registrosCompara("notificaciones_sistema",$_POST['notificacion_sistema'][0],1);
		unset($_POST['notificacion_sistema'][0]['id']);
	}else{
		$registroCompara=true;
	}
	
	if($registroCompara){
		if(!empty($_POST['notificacion_sistema'][0])){
			$_POST['notificacion_sistema'][0]['codigo_plataforma']=$codigo_plataforma;
			//checamos si tiene un registro si no es update
			$sql ="SELECT * FROM notificaciones_sistema WHERE 1 = 1 ";
			$result = $conexion->query($sql);
			$row=$result->fetch_assoc();
			$id=$row['id'];
			$success=true;
			$_POST['notificacion_sistema'][0]['fechaR']=$fechaH;
			if(empty($id)){ 
				//agrega
				$tipo="Insert";
				$fields_pdo = "`".implode('`,`', array_keys($_POST['notificacion_sistema'][0]))."`";
				$values_pdo = "'".implode("','", $_POST['notificacion_sistema'][0])."'";
				$inset_notificaciones_sistema= "INSERT INTO notificaciones_sistema ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$inset_notificaciones_sistema=$conexion->query($inset_notificaciones_sistema);
				$num=$conexion->affected_rows;
				if(!$inset_notificaciones_sistema || $num=0){
					$success=false;
					echo "ERROR inset_notificaciones_sistema"; 
					var_dump($conexion->error);
				}
				$id=$_POST['id_notificacion_sistema_historico']=$conexion->insert_id;
				$fields_pdo = "`".implode('`,`', array_keys($_POST['notificacion_sistema'][0]))."`";
				$values_pdo = "'".implode("','", $_POST['notificacion_sistema'][0])."'";
				$inset_notificaciones_sistema_historicos= "INSERT INTO notificaciones_sistema_historicos ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$success=$inset_notificaciones_sistema_historicos=$conexion->query($inset_notificaciones_sistema_historicos);
				$num=$conexion->affected_rows;
				if(!$inset_notificaciones_sistema_historicos || $num=0){
					$success=false;
					echo "ERROR inset_notificaciones_sistema_historicos"; 
					var_dump($conexion->error);
				}
			}else{
				//edita
				$tipo="Update";
				$_POST['notificacion_sistema'][0]['fechaR']=$fechaH;
				foreach($_POST['notificacion_sistema'][0] as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}


				$conexion->autocommit(FALSE);
				$update_notificaciones_sistema = "UPDATE notificaciones_sistema SET ". join(",",$valueSets) . " WHERE id=".$id;
				$update_notificaciones_sistema=$conexion->query($update_notificaciones_sistema);
				$num=$conexion->affected_rows;
				if(!$update_notificaciones_sistema || $num=0){
					$success=false;
					echo "ERROR update_notificaciones_sistema"; 
					var_dump($conexion->error);
				}

				$_POST['notificacion_sistema'][0]['id_notificacion_sistema_historico']=$id;
				unset($_POST['notificacion_sistema'][0]['id']);
				$fields_pdo = "`".implode('`,`', array_keys($_POST['notificacion_sistema'][0]))."`";
				$values_pdo = "'".implode("','", $_POST['notificacion_sistema'][0])."'";
				$inset_notificaciones_sistema_historicos= "INSERT INTO notificaciones_sistema_historicos ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$success=$inset_notificaciones_sistema_historicos=$conexion->query($inset_notificaciones_sistema_historicos);
				$num=$conexion->affected_rows;
				if(!$inset_notificaciones_sistema_historicos || $num=0){
					$success=false;
					echo "ERROR inset_notificaciones_sistema_historicos"; 
					var_dump($conexion->error);
				}
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'notificaciones_sistema',$id,$tipo,'',$fechaH);
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