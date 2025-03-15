<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/configuracion.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','configuracion_matriz_rentabilidad_secciones_ine_2018',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["configuracion_matriz_rentabilidad_secciones_ine_2018"][0] as $keyPrincipal => $atributo) {
		$_POST["configuracion_matriz_rentabilidad_secciones_ine_2018"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$sql ="SELECT * FROM configuracion_matriz_rentabilidad_secciones_ine_2018 WHERE 1 = 1 ";
	$result = $conexion->query($sql);
	$row=$result->fetch_assoc();
	$id=$row['id'];
	//$_POST["configuracion_matriz_rentabilidad_secciones_ine_2018"][0]['referencia_importacion']=$row['referencia_importacion'];
	if(!empty($id)){ 
		$_POST["configuracion_matriz_rentabilidad_secciones_ine_2018"][0]['id']=$id;
		$registroCompara= registrosCompara("configuracion_matriz_rentabilidad_secciones_ine_2018",$_POST["configuracion_matriz_rentabilidad_secciones_ine_2018"][0],1);
		unset($_POST["configuracion_matriz_rentabilidad_secciones_ine_2018"][0]['id']);
	}else{
		$registroCompara=true;
	}
	
	if($registroCompara){
		if(!empty($_POST["configuracion_matriz_rentabilidad_secciones_ine_2018"][0])){
			$_POST["configuracion_matriz_rentabilidad_secciones_ine_2018"][0]['codigo_plataforma']=$codigo_plataforma;
			//checamos si tiene un registro si no es update
			$sql ="SELECT * FROM configuracion_matriz_rentabilidad_secciones_ine_2018 WHERE 1 = 1 ";
			$result = $conexion->query($sql);
			$row=$result->fetch_assoc();
			$id=$row['id'];
			$success=true;
			$_POST["configuracion_matriz_rentabilidad_secciones_ine_2018"][0]['fechaR']=$fechaH;
			if(empty($id)){ 
				//agrega
				$tipo="Insert";
				$fields_pdo = "`".implode('`,`', array_keys($_POST["configuracion_matriz_rentabilidad_secciones_ine_2018"][0]))."`";
				$values_pdo = "'".implode("','", $_POST["configuracion_matriz_rentabilidad_secciones_ine_2018"][0])."'";
				$inset_configuracion_matriz_rentabilidad_secciones_ine_2018= "INSERT INTO configuracion_matriz_rentabilidad_secciones_ine_2018 ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$inset_configuracion_matriz_rentabilidad_secciones_ine_2018=$conexion->query($inset_configuracion_matriz_rentabilidad_secciones_ine_2018);
				$num=$conexion->affected_rows;
				if(!$inset_configuracion_matriz_rentabilidad_secciones_ine_2018 || $num=0){
					$success=false;
					echo "ERROR inset_configuracion_matriz_rentabilidad_secciones_ine_2018"; 
					var_dump($conexion->error);
				}
				$id=$_POST["configuracion_matriz_rentabilidad_secciones_ine_2018"][0]['id_configuracion_matriz_rentabilidad_secciones_ine_2018']=$conexion->insert_id;
				$fields_pdo = "`".implode('`,`', array_keys($_POST["configuracion_matriz_rentabilidad_secciones_ine_2018"][0]))."`";
				$values_pdo = "'".implode("','", $_POST["configuracion_matriz_rentabilidad_secciones_ine_2018"][0])."'";
				$inset_configuracion_matriz_rentabilidad_secciones_ine_2018_historicos= "INSERT INTO configuracion_matriz_rentabilidad_secciones_ine_2018_historicos ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$success=$inset_configuracion_matriz_rentabilidad_secciones_ine_2018_historicos=$conexion->query($inset_configuracion_matriz_rentabilidad_secciones_ine_2018_historicos);
				$num=$conexion->affected_rows;
				if(!$inset_configuracion_matriz_rentabilidad_secciones_ine_2018_historicos || $num=0){
					$success=false;
					echo "ERROR inset_configuracion_matriz_rentabilidad_secciones_ine_2018_historicos"; 
					var_dump($conexion->error);
				}
			}else{
				foreach($_POST["configuracion_matriz_rentabilidad_secciones_ine_2018"][0] as $keyPrincipal => $atributo) {
					if($atributo !=''){
						$_POST["configuracion_matriz_rentabilidad_secciones_ine_2018"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
					}else{
						$_POST["configuracion_matriz_rentabilidad_secciones_ine_2018"][0][$keyPrincipal] = 0;
					}
				}
				//edita
				$tipo="Update";
				$_POST["configuracion_matriz_rentabilidad_secciones_ine_2018"][0]['fechaR']=$fechaH;
				foreach($_POST["configuracion_matriz_rentabilidad_secciones_ine_2018"][0] as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}


				$conexion->autocommit(FALSE);
				$update_configuracion_matriz_rentabilidad_secciones_ine_2018 = "UPDATE configuracion_matriz_rentabilidad_secciones_ine_2018 SET ". join(",",$valueSets) . " WHERE id=".$id;
				$update_configuracion_matriz_rentabilidad_secciones_ine_2018=$conexion->query($update_configuracion_matriz_rentabilidad_secciones_ine_2018);
				$num=$conexion->affected_rows;
				if(!$update_configuracion_matriz_rentabilidad_secciones_ine_2018 || $num=0){
					$success=false;
					echo "ERROR update_configuracion_matriz_rentabilidad_secciones_ine_2018"; 
					var_dump($conexion->error);
				}

				$_POST["configuracion_matriz_rentabilidad_secciones_ine_2018"][0]['id_configuracion_matriz_rentabilidad_secciones_ine_2018']=$id;
				unset($_POST["configuracion_matriz_rentabilidad_secciones_ine_2018"][0]['id']);
				$fields_pdo = "`".implode('`,`', array_keys($_POST["configuracion_matriz_rentabilidad_secciones_ine_2018"][0]))."`";
				$values_pdo = "'".implode("','", $_POST["configuracion_matriz_rentabilidad_secciones_ine_2018"][0])."'";
				$inset_configuracion_matriz_rentabilidad_secciones_ine_2018_historicos= "INSERT INTO configuracion_matriz_rentabilidad_secciones_ine_2018_historicos ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$success=$inset_configuracion_matriz_rentabilidad_secciones_ine_2018_historicos=$conexion->query($inset_configuracion_matriz_rentabilidad_secciones_ine_2018_historicos);
				$num=$conexion->affected_rows;
				if(!$inset_configuracion_matriz_rentabilidad_secciones_ine_2018_historicos || $num=0){
					$success=false;
					echo "ERROR inset_configuracion_matriz_rentabilidad_secciones_ine_2018_historicos"; 
					var_dump($conexion->error);
				}
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'configuracion_matriz_rentabilidad_secciones_ine_2018',$id,$tipo,'',$fechaH);
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