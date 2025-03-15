<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuarios.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos_permisos.php";
	include __DIR__."/../functions/gps_distancias.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/lista_nominal.php";

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"secciones_ine_ciudadanos",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	 

	//metemos los valores para que se no tengamos error
	foreach($_POST["usuarios"][0] as $keyPrincipal => $atributo) {
		$_POST["usuarios"][0][$keyPrincipal]= rtrim(ltrim(mysqli_real_escape_string($conexion,$atributo)));
	}

	



	if( 
		registrosCompara("usuarios",$_POST['usuarios'][0],1) || 
		registrosCompara("secciones_ine_ciudadanos_permisos",$_POST['usuarios_permisos'][0],1) ||
		$_POST['usuarios_permisos'][0]['id'] == ""
	){
		$success = true;
		if(!empty($_POST)){
			foreach($_POST['usuarios'] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
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
			$id_seccion_ine_ciudadano = $_POST['usuarios'][0]['id_seccion_ine_ciudadano']=$_POST['seccion_ine_ciudadano'][0]['id'];
			$_POST['usuarios'][0]['id_usuario']=$id_usuario=$id;
			$_POST['usuarios'][0]['fechaR']=$fechaH;
			$fields_pdo = "`".implode('`,`', array_keys($_POST['usuarios'][0]))."`";
			$values_pdo = "'".implode("','", $_POST['usuarios'][0])."'";
			$insert_usuarios_historicos= "INSERT INTO usuarios_historicos ($fields_pdo) VALUES ($values_pdo);";
			$conexion->autocommit(FALSE);

			$insert_usuarios_historicos=$conexion->query($insert_usuarios_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_usuarios_historicos || $num=0){
				$success=false;
				echo "ERROR insert_usuarios_historicos"; 
				var_dump($conexion->error);
			}

			////usuarios permisos
			$seccion_ine_ciudadano_permisosDatos=seccion_ine_ciudadano_permisosDatos('',$id_seccion_ine_ciudadano);

			if($seccion_ine_ciudadano_permisosDatos['id'] =="" ){
				//no existe y creamos
				$_POST['usuarios_permisos'][0]['id_seccion_ine_ciudadano'] = $id_seccion_ine_ciudadano;
				$_POST['usuarios_permisos'][0]['id_usuario'] = $id_usuario;
				$_POST['usuarios_permisos'][0]['fechaR']=$fechaH;
				$_POST['usuarios_permisos'][0]['codigo_plataforma']=$codigo_plataforma;
				unset($_POST['usuarios_permisos'][0]['id']);
				$fields_pdo = "`".implode('`,`', array_keys($_POST['usuarios_permisos'][0]))."`";
				$values_pdo = "'".implode("','", $_POST['usuarios_permisos'][0])."'";
				$insert_usuarios_permsiso= "INSERT INTO secciones_ine_ciudadanos_permisos ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$insert_usuarios_permsiso=$conexion->query($insert_usuarios_permsiso);
				$num=$conexion->affected_rows;
				if(!$insert_usuarios_permsiso || $num=0){
					$success=false;
					echo "ERROR insert_usuarios_permsiso"; 
					var_dump($conexion->error);
				}

				$id_usuario=$_POST['usuarios_permisos'][0]['id_seccion_ine_ciudadano_permiso']=$conexion->insert_id;
				$fields_pdo = "`".implode('`,`', array_keys($_POST['usuarios_permisos'][0]))."`";
				$values_pdo = "'".implode("','", $_POST['usuarios_permisos'][0])."'";
				$insert_usuarios_permiso_historicos= "INSERT INTO secciones_ine_ciudadanos_permisos_historicos ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);

				$insert_usuarios_permiso_historicos=$conexion->query($insert_usuarios_permiso_historicos);
				$num=$conexion->affected_rows;
				if(!$insert_usuarios_permiso_historicos || $num=0){
					$success=false;
					echo "ERROR insert_usuarios_permiso_historicos"; 
					var_dump($conexion->error);
				}
			}else{
				//editamos
				unset($valueSets);
				$valueSets=array();

				$_POST['usuarios_permisos'][0]['id_seccion_ine_ciudadano'] = $id_seccion_ine_ciudadano;
				$_POST['usuarios_permisos'][0]['id_usuario'] = $id_usuario;
				$_POST['usuarios_permisos'][0]['fechaR'] = $fechaH;
				$_POST['usuarios_permisos'][0]['referencia_importacion'] = $seccion_ine_ciudadano_permisosDatos['referencia_importacion'];
				foreach($_POST['usuarios_permisos'] as $keyPrincipal => $atributos) {
					foreach ($atributos as $key => $value) {
						if($key !='id'){
							$valueSets[] = $key . " = '" . $value . "'";
						}else{
							$id=$value;
						}
					}
				}
				$update_usuarios_permsiso = "UPDATE secciones_ine_ciudadanos_permisos SET ". join(",",$valueSets) . " WHERE id=".$id;
				$conexion->autocommit(FALSE);
				$update_usuarios_permsiso=$conexion->query($update_usuarios_permsiso);
				$num=$conexion->affected_rows;
				if(!$update_usuarios_permsiso || $num=0){
					$success=false;
					echo "ERROR update_usuarios_permsiso"; 
					var_dump($conexion->error);
				}

				unset($_POST["usuarios_permisos"][0]['id']); 
				$id_seccion_ine_ciudadano_permiso=$_POST['usuarios_permisos'][0]['id_seccion_ine_ciudadano_permiso']=$id;
				$fields_pdo = "`".implode('`,`', array_keys($_POST["usuarios_permisos"][0]))."`";
				$values_pdo = "'".implode("','", $_POST['usuarios_permisos'][0])."'";
				$insert_secciones_ine_ciudadanos_permisos_historicos= "INSERT INTO secciones_ine_ciudadanos_permisos_historicos ($fields_pdo) VALUES ($values_pdo);";
				$insert_secciones_ine_ciudadanos_permisos_historicos=$conexion->query($insert_secciones_ine_ciudadanos_permisos_historicos);
				$num=$conexion->affected_rows;
				if(!$insert_secciones_ine_ciudadanos_permisos_historicos || $num=0){
					$success=false;
					echo "ERROR insert_secciones_ine_ciudadanos_permisos_historicos"; 
					var_dump($conexion->error);
				}
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_ciudadanos',$id_seccion_ine_ciudadano,'Update','',$fechaH);
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
