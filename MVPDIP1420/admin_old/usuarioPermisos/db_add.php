<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/usuarios.php";
	$moduloAccionPermisos = moduloAccionPermisos('configuracion','empleados_permisos',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}
	foreach($_POST["permiso_modulo"][0] as $keyPrincipal => $atributo) {
		$_POST["permiso_modulo"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	foreach($_POST["permisos_valor"][0] as $keyPrincipal => $atributo) {
		$_POST["permisos_valor"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	if(!empty($_POST)){
		
		//var_dump($_POST);
		$id_empleado=$_POST["permiso_modulo"][0]['id_empleado'];
		$sql ="SELECT * FROM usuarios WHERE id_empleado='{$id_empleado}' ";
		$result = $conexion->query($sql);
		$row=$result->fetch_assoc();
		$id_usuario=$row['id'];


		$success=true;
		$_POST["permiso_modulo"][0]['fechaR']=$fechaH;
		$_POST["permiso_modulo"][0]['codigo_plataforma']=$codigo_plataforma;
		$_POST["permiso_modulo"][0]['id_usuario']=$id_usuario;
		if($_COOKIE["id_usuario"]==$id_usuario){
			echo "No tiene permiso.";
			die;
		}

		
		$fields_pdo = "`".implode('`,`', array_keys($_POST['permiso_modulo'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['permiso_modulo'][0])."'";
		$insert_usuarios_modulos= "INSERT INTO usuarios_modulos ($fields_pdo) VALUES ($values_pdo);";
		$conexion->autocommit(FALSE);
		$insert_usuarios_modulos=$conexion->query($insert_usuarios_modulos);
		$num=$conexion->affected_rows;
		if(!$insert_usuarios_modulos || $num=0){
			$success=false;
			echo "ERROR insert_usuarios_modulos"; 
			var_dump($conexion->error);
		}
		$id_usuario_modulo=$_POST['permiso_modulo'][0]['id_usuario_modulo']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['permiso_modulo'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['permiso_modulo'][0])."'";
		$insert_usuarios_modulos_historicos= "INSERT INTO usuarios_modulos_historicos ($fields_pdo) VALUES ($values_pdo);";
		$insert_usuarios_modulos_historicos=$conexion->query($insert_usuarios_modulos_historicos);
		$num=$conexion->affected_rows;
		if(!$insert_usuarios_modulos_historicos || $num=0){
			$success=false;
			echo "ERROR insert_usuarios_modulos_historicos"; 
			var_dump($conexion->error);
		}

		//var_dump($_POST["permisos_valor"]);
		$id_seccion=$_POST["permiso_modulo"][0]['id_seccion'];
		$sql ="SELECT * FROM secciones WHERE id='{$id_seccion}' ";
		$result = $conexion->query($sql);
		$row=$result->fetch_assoc();
		$seccion=$row['seccion'];

		$id_modulo=$_POST["permiso_modulo"][0]['id_modulo'];
		$sql ="SELECT * FROM modulos WHERE id='{$id_modulo}' ";
		$result = $conexion->query($sql);
		$row=$result->fetch_assoc();
		$modulo=$row['modulo']; 

		
		$successx=true;
		foreach ($_POST["permisos_valor"] as $key => $value) {
			unset($usuario_permiso);
			if($value=="SI"){
				$usuario_permiso['id_usuario']=$id_usuario;
				$usuario_permiso['id_empleado']=$id_empleado;
				$usuario_permiso['id_seccion']=$id_seccion;
				$usuario_permiso['seccion']=$seccion;
				$usuario_permiso['id_modulo']=$id_modulo;
				$usuario_permiso['modulo']=$modulo;

				$id_permiso=$key;
				$sql ="SELECT * FROM permisos WHERE id='{$id_permiso}' ";
				$result = $conexion->query($sql);
				$row=$result->fetch_assoc();
				$permiso=$row['permiso']; 

				$usuario_permiso['id_permiso']=$id_permiso;
				$usuario_permiso['permiso']=$permiso;
				$usuario_permiso['status']=1;
				$usuario_permiso['fechaR']=$fechaH;
				$usuario_permiso['id_usuario_modulo']=$id_usuario_modulo;
				$usuario_permiso['codigo_plataforma']=$codigo_plataforma;

				$fields_pdo = "`".implode('`,`', array_keys($usuario_permiso))."`";
				$values_pdo = "'".implode("','", $usuario_permiso)."'";
				$insert_usuario_permiso= "INSERT INTO usuarios_permisos ($fields_pdo) VALUES ($values_pdo);";
				$insert_usuario_permiso=$conexion->query($insert_usuario_permiso);
				$num=$conexion->affected_rows;
				if(!$insert_usuario_permiso || $num=0){
					$successx=false;
					echo "ERROR insert_usuario_permiso"; 
					var_dump($conexion->error);
				}
				$usuario_permiso['id_usuario_permiso']=$conexion->insert_id;
				$fields_pdo = "`".implode('`,`', array_keys($usuario_permiso))."`";
				$values_pdo = "'".implode("','", $usuario_permiso)."'";
				$insert_usuario_permiso_historicos= "INSERT INTO usuarios_permisos_historicos ($fields_pdo) VALUES ($values_pdo);";
				$success=$insert_usuario_permiso_historicos=$conexion->query($insert_usuario_permiso_historicos);
				$num=$conexion->affected_rows;
				if(!$insert_usuario_permiso_historicos || $num=0){
					$successx=false;
					echo "ERROR insert_usuario_permiso_historicos"; 
					var_dump($conexion->error);
				}
			}
		}

		if($success && $successx){
			$log= logUsuario($_COOKIE["id_usuario"],'usuarios_modulos',$id_usuario_modulo,'Insert','',$fechaH);
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
