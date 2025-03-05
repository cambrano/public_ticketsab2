<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/paquetes_sistema.php";
	include __DIR__."/../functions/usuarios.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/empleados.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/genid.php";
	$moduloAccionPermisos = moduloAccionPermisos('configuracion','empleados',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	$permisoPaquete= paquetesSistema("usuarios_generales");
	if($permisoPaquete['permiso']==false){
		echo $permisoPaquete['mensaje'];
		die;
	}
	//metemos los valores para que se no tengamos error
	foreach($_POST["empleados"][0] as $keyPrincipal => $atributo) {
		$_POST["empleados"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	//metemos los valores para que se no tengamos error
	foreach($_POST["usuarios"][0] as $keyPrincipal => $atributo) {
		$_POST["usuarios"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	$usuarioValidadorSistema=usuarioValidadorSistema($_POST["usuarios"][0]['usuario'],'','');
	if($usuarioValidadorSistema){
		echo "Favor de Ingresar un Usuario Válido o que no exista en sistema.";
		die;
	}

	$empleadoClaveVerificacion=empleadoClaveVerificacion($_POST["empleados"][0]['clave'],'',1);
	if($empleadoClaveVerificacion){
		$claveF= clave('empleados');
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["empleados"][0]['clave'] = $claveF['clave'];
		}
	}

	if(!empty($_POST)){
		$success=true;
		$_POST["empleados"][0]['fechaR']=$fechaH;
		$_POST["usuarios"][0]['fechaR']=$fechaH;
		$_POST["empleados"][0]['status']=1;
		$_POST["usuarios"][0]['status']=1;
		$_POST["usuarios"][0]['id_perfil_usuario']=3;

		$_POST["empleados"][0]['codigo_plataforma']=$codigo_plataforma;
		$_POST["usuarios"][0]['codigo_plataforma']=$codigo_plataforma;

		$_POST["usuarios"][0]['identificador']=$gen_id3.'_'.$cod32;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['empleados'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['empleados'][0])."'";
		$inset_empleados= "INSERT INTO empleados ($fields_pdo) VALUES ($values_pdo);";
		$conexion->autocommit(FALSE);

		$inset_empleados=$conexion->query($inset_empleados);
		$num=$conexion->affected_rows;
		if(!$inset_empleados || $num=0){
			$success=false;
			echo "ERROR inset_empleados"; 
			var_dump($conexion->error);
		}

		$id=$_POST['empleados'][0]['id_empleado']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['empleados'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['empleados'][0])."'";
		$inset_empleados_historicos= "INSERT INTO empleados_historicos ($fields_pdo) VALUES ($values_pdo);";
		 

		$inset_empleados_historicos=$conexion->query($inset_empleados_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_empleados_historicos || $num=0){
			$success=false;
			echo "ERROR inset_empleados_historicos"; 
			var_dump($conexion->error);
		}

		$_POST['usuarios'][0]['tabla'] = 'empleados';
		$id_empleado=$_POST['usuarios'][0]['id_empleado']=$_POST['empleados'][0]['id_empleado'];
		$fields_pdo = "`".implode('`,`', array_keys($_POST['usuarios'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['usuarios'][0])."'";
		$inset_usuarios= "INSERT INTO usuarios ($fields_pdo) VALUES ($values_pdo);";
		$conexion->autocommit(FALSE);

		$inset_usuarios=$conexion->query($inset_usuarios);
		$num=$conexion->affected_rows;
		if(!$inset_usuarios || $num=0){
			$success=false;
			echo "ERROR inset_usuarios"; 
			var_dump($conexion->error);
		}

		

		$id_usuario=$_POST['usuarios'][0]['id_usuario']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['usuarios'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['usuarios'][0])."'";
		$inset_usuarios_historicos= "INSERT INTO usuarios_historicos ($fields_pdo) VALUES ($values_pdo);";
		$conexion->autocommit(FALSE);

		$inset_usuarios_historicos=$conexion->query($inset_usuarios_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_usuarios_historicos || $num=0){
			$success=false;
			echo "ERROR inset_usuarios_historicos"; 
			var_dump($conexion->error);
		}


		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'empleados',$id,'Insert','',$fechaH);
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
