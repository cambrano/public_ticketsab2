<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/usuarios.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/empleados.php";
	include __DIR__."/../functions/usuario_permisos.php";



	$moduloAccionPermisos = moduloAccionPermisos('configuracion',"administrador_sistema",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
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

	$empleadoClaveVerificacion=empleadoClaveVerificacion($_POST["empleados"][0]["clave"],$_POST["empleados"][0]['id'],1);
	if($empleadosClaveVerificacion){
		$claveF= clave("empleados");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["empleados"][0]["clave"] = $claveF["clave"];
		}
	}

	$usuarioValidadorSistema=usuarioValidadorSistema($_POST["usuarios"][0]['usuario'],2,$_POST["empleados"][0]['id']);
	if($usuarioValidadorSistema){
		echo "Favor de Ingresar un Usuario Válido o que no exista en sistema.";
		die;
	}

	if( registrosCompara("empleados",$_POST['empleados'][0],1) || registrosCompara("usuarios",$_POST['usuarios'][0],1) ){
		if(!empty($_POST)){
			//$_POST['registro']=$fechaH;
			$_POST["empleados"][0]['fechaR']=$fechaH;
			$_POST["empleados"][0]['codigo_plataforma']=$codigo_plataforma;
			$sql=("SELECT * FROM empleados WHERE id={$_POST["empleados"][0]['id']} ");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$_POST["empleados"][0]['referencia_importacion']=$row['referencia_importacion'];
			$_POST["usuarios"][0]['referencia_importacion']=$row['referencia_importacion'];

			$success=true;
			foreach($_POST['empleados'] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_empleados = "UPDATE empleados SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_empleados=$conexion->query($update_empleados);
			$num=$conexion->affected_rows;
			if(!$update_empleados || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_empleados"; 
				var_dump($conexion->error);
			}

			unset($_POST["empleados"][0]['id']); 
			$id_empleado=$_POST['empleados'][0]['id_empleado']=$id;
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

			//usuarios
			$valueSets=array();
			foreach($_POST['usuarios'] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}

			
			$_POST['usuarios'][0]['codigo_plataforma']=$codigo_plataforma;
			$_POST["usuarios"][0]['id_perfil_usuario']=2;
			$_POST["empleados"][0]['notificaciones_sistema']=1;
			$update_usuarios = "UPDATE usuarios SET ". join(",",$valueSets) . " WHERE id=".$id;
			$update_usuarios=$conexion->query($update_usuarios);
			$num=$conexion->affected_rows;
			if(!$update_usuarios || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_usuarios"; 
				var_dump($conexion->error);
			}
			unset($_POST['usuarios'][0]['id']);
			$_POST['usuarios'][0]['id_empleado']=$id_empleado;
			$_POST['usuarios'][0]['id_usuario']=$id_usuario=$id;
			$_POST['usuarios'][0]['fechaR']=$fechaH;
			$_POST['usuarios'][0]['tabla'] = 'empleados';
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
				$log= logUsuario($_COOKIE["id_usuario"],'administradores_sistema',$id_empleado,'Update','',$fechaH);
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