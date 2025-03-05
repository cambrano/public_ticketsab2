<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/api_sms_status.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad',"api_sms_status",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["api_sms_status"][0] as $keyPrincipal => $atributo) {
		$_POST["api_sms_status"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}


	if( registrosCompara("api_sms_status",$_POST["api_sms_status"][0],1)){
		if(!empty($_POST)){ 
			$_POST["api_sms_status"][0]["fechaR"]=$fechaH;
			$_POST["api_sms_status"][0]["codigo_plataforma"]=$codigo_plataforma;
			$success=true;
			foreach($_POST["api_sms_status"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_api_sms_status = "UPDATE api_sms_status SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_api_sms_status=$conexion->query($update_api_sms_status);
			$num=$conexion->affected_rows;
			if(!$update_api_sms_status || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_api_sms_status"; 
				var_dump($conexion->error);
			}

			unset($_POST["api_sms_status"][0]['id']); 
			$id_api_sms_status=$_POST["api_sms_status"][0]["id_api_sms_status"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["api_sms_status"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["api_sms_status"][0])."'";
			$insert_api_sms_status_historicos= "INSERT INTO api_sms_status_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_api_sms_status_historicos=$conexion->query($insert_api_sms_status_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_api_sms_status_historicos || $num=0){
				$success=false;
				echo "ERROR insert_api_sms_status_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],"api_sms_status",$id_api_sms_status,'Update','',$fechaH);
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
	}
