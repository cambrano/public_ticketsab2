<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/usuarios_permisos.php";
	include __DIR__."/../functions/permisos.php";
	$success=true;
	

	//metemos los valores para que se no tengamos error
	foreach($_POST["permiso_modulo"][0] as $keyPrincipal => $atributo) {
		$_POST["permiso_modulo"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["permisos_valor"][0] as $keyPrincipal => $atributo) {
		$_POST["permisos_valor"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["chkvalPermisos"][0] as $keyPrincipal => $atributo) {
		$_POST["chkvalPermisos"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$success=true;
	$id_usuario_modulo=$_POST['permiso_modulo'][0]['id'];
	$usuario_permisosDatos=usuario_permisosDatos('',$id_usuario_modulo,'');

	if($usuario_permisosDatos['id_usuario'] == $_COOKIE["id_usuario"]) {
		echo "No tiene permiso.";
		die;
	}


	foreach ($usuario_permisosDatos as $key => $value) {
		$arrayusuario_permiso[$value['id_permiso']]= array('id' => $value['id'],'status' => $value['status'],);
	}
	foreach ($_POST['permisos_valor'] as $key => $value) {
		if($value=="SI"){
			$value=1;
		}else{
			$value=0;
		}
		$ids_permiso[$key]['id']=$value;
	}

	

	$permisosDatos=permisosDatos();
	$conexion->autocommit(FALSE);
	$nodelete=false;
	foreach ($ids_permiso as $key => $value) {
		$key; // es el id permiso
		$value['id'];
		$arrayusuario_permiso[$key]['id'];
		if($value['id'] == $arrayusuario_permiso[$key]['status']){
			//si entra no elimina nada 
			if($arrayusuario_permiso[$key]['status'] !='' ){
				$nodelete=true;
			}
			//no hace nada 
		}else{
			if($value['id']==0){
				//elimina
				$tipo = "Update";
				$id_usuario_permiso = $arrayusuario_permiso[$key]['id'];
				$delete_usuario_permisos = "DELETE FROM usuarios_permisos  WHERE  id='{$id_usuario_permiso}'; ";
				$delete_usuario_permisos=$conexion->query($delete_usuario_permisos);
				if(!$delete_usuario_permisos || $num=0){
					$success=false;
					echo "ERROR delete_usuario_permisos"; 
					var_dump($conexion->error);
				}
			}else{
				$tipo = "Update";
				$nodelete=true;
				//insert
				unset($arrayusuario_permisoReg);

				$id_usuario_permiso = $arrayusuario_permiso[$key]['id'];
				$arrayusuario_permisoReg['id_usuario']=$usuario_permisosDatos[0]['id_usuario'];
				$arrayusuario_permisoReg['id_empleado']=$_POST["permiso_modulo"][0]['id_empleado'];
				$id_seccion = $arrayusuario_permisoReg['id_seccion']=$_POST["permiso_modulo"][0]['id_seccion'];

				$sql ="SELECT * FROM secciones WHERE id='{$id_seccion}' ";
				$result = $conexion->query($sql);
				$row=$result->fetch_assoc();
				$seccion=$row['seccion'];
				$arrayusuario_permisoReg['seccion']=$seccion;

				$id_modulo = $arrayusuario_permisoReg['id_modulo']=$_POST["permiso_modulo"][0]['id_modulo'];

				$sql ="SELECT * FROM modulos WHERE id='{$id_modulo}' ";
				$result = $conexion->query($sql);
				$row=$result->fetch_assoc();
				$modulo=$row['modulo']; 
				$arrayusuario_permisoReg['modulo']=$modulo;

				$id_permiso=$key;
				$sql ="SELECT * FROM permisos WHERE id='{$id_permiso}' ";
				$result = $conexion->query($sql);
				$row=$result->fetch_assoc();
				$permiso=$row['permiso']; 
				$arrayusuario_permisoReg['permiso']=$permiso;
				$arrayusuario_permisoReg['id_permiso']=$id_permiso;

				$arrayusuario_permisoReg['status']=1;
				$arrayusuario_permisoReg['fechaR']=$fechaH;
				$arrayusuario_permisoReg['id_usuario_modulo']=$_POST["permiso_modulo"][0]['id'];

				$fields_pdo = "`".implode('`,`', array_keys($arrayusuario_permisoReg))."`";
				$values_pdo = "'".implode("','", $arrayusuario_permisoReg)."'";
				$insert_usuario_permiso= "INSERT INTO usuarios_permisos ($fields_pdo) VALUES ($values_pdo);";
				$insert_usuario_permiso=$conexion->query($insert_usuario_permiso);
				$num=$conexion->affected_rows;
				if(!$insert_usuario_permiso || $num=0){
					$success=false;
					echo "ERROR insert_usuario_permiso"; 
					var_dump($conexion->error);
				}
				$arrayusuario_permisoReg['id_usuario_permiso']=$conexion->insert_id;
				$fields_pdo = "`".implode('`,`', array_keys($arrayusuario_permisoReg))."`";
				$values_pdo = "'".implode("','", $arrayusuario_permisoReg)."'";
				$insert_usuario_permiso_historicos= "INSERT INTO usuarios_permisos_historicos ($fields_pdo) VALUES ($values_pdo);";
				$success=$insert_usuario_permiso_historicos=$conexion->query($insert_usuario_permiso_historicos);
				$num=$conexion->affected_rows;
				if(!$insert_usuario_permiso_historicos || $num=0){
					$success=false;
					echo "ERROR insert_usuario_permiso_historicos"; 
					var_dump($conexion->error);
				}
			}
		}
	}

	if($nodelete==false){
		//elmiminamos el usuario modulo
		$tipo = 'Delete';
		$id_usuario_modulo = $_POST["permiso_modulo"][0]['id'];

		$delete_usuario_modulos = "DELETE FROM usuarios_permisos  WHERE id_usuario_modulo='{$id_usuario_modulo}' AND id <> 0 ";
		$delete_usuario_modulos=$conexion->query($delete_usuario_modulos);
		if(!$delete_usuario_modulos || $num=0){
			$success=false;
			echo "ERROR delete_usuario_modulos"; 
			var_dump($conexion->error);
		}


		$delete_usuario_modulos = "DELETE FROM usuarios_modulos  WHERE id='{$id_usuario_modulo}' ";
		$delete_usuario_modulos=$conexion->query($delete_usuario_modulos);
		if(!$delete_usuario_modulos || $num=0){
			$success=false;
			echo "ERROR delete_usuario_modulos"; 
			var_dump($conexion->error);
		}
		

	}

	if($success){
		$log= logUsuario($_COOKIE["id_usuario"],'usuarios_modulos',$id_usuario_modulo,$tipo,'',$fechaH);
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