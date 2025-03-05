<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/api_sms_status.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','api_sms_status',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["api_sms_status"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["api_sms_status"][0] as $keyPrincipal => $atributo) {
			$_POST["api_sms_status"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		

		 

		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["api_sms_status"][0]['fechaR']=$fechaH; 
		$_POST["api_sms_status"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['api_sms_status'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['api_sms_status'][0])."'";
		$inset_api_sms_status= "INSERT INTO api_sms_status ($fields_pdo) VALUES ($values_pdo);";

		$inset_api_sms_status=$conexion->query($inset_api_sms_status);
		$num=$conexion->affected_rows;
		if(!$inset_api_sms_status || $num=0){
			$success=false;
			echo "ERROR inset_api_sms_status"; 
			var_dump($conexion->error);
		}

		$id=$_POST['api_sms_status'][0]['id_api_sms_status']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['api_sms_status'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['api_sms_status'][0])."'";
		$inset_api_sms_status_historicos= "INSERT INTO api_sms_status_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_api_sms_status_historicos=$conexion->query($inset_api_sms_status_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_api_sms_status_historicos || $num=0){
			$success=false;
			echo "ERROR inset_api_sms_status_historicos"; 
			var_dump($conexion->error);
		}
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'api_sms_status',$id,'Insert','',$fechaH);
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