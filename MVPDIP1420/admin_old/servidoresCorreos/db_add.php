<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/servidores_correos.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('perfiles','servidores_correos',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["servidor_correo"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["servidor_correo"][0] as $keyPrincipal => $atributo) {
			$_POST["servidor_correo"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}

		$servidor_correoClaveVerificacion=servidor_correoClaveVerificacion($_POST["servidor_correo"][0]['clave'],'',1);
		if($servidor_correoClaveVerificacion){
			$claveF= clave('servidores_correos');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["servidor_correo"][0]['clave'] = $claveF['clave'];
			}
		}

		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["servidor_correo"][0]['fechaR']=$fechaH; 
		$_POST["servidor_correo"][0]['status']=1; 
		$_POST["servidor_correo"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['servidor_correo'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['servidor_correo'][0])."'";
		$insert_servidores_correos= "INSERT INTO servidores_correos ($fields_pdo) VALUES ($values_pdo);";

		$insert_servidores_correos=$conexion->query($insert_servidores_correos);
		$num=$conexion->affected_rows;
		if(!$insert_servidores_correos || $num=0){
			$success=false;
			echo "ERROR insert_servidores_correos"; 
			var_dump($conexion->error);
		}

		$id=$_POST['servidor_correo'][0]['id_servidor_correo']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['servidor_correo'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['servidor_correo'][0])."'";
		$insert_servidores_correos_historicos= "INSERT INTO servidores_correos_historicos ($fields_pdo) VALUES ($values_pdo);";

		$insert_servidores_correos_historicos=$conexion->query($insert_servidores_correos_historicos);
		$num=$conexion->affected_rows;
		if(!$insert_servidores_correos_historicos || $num=0){
			$success=false;
			echo "ERROR insert_servidores_correos_historicos"; 
			var_dump($conexion->error);
		}
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'servidores_correos',$id,'Insert','',$fechaH);
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