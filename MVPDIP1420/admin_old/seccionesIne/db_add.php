<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/usuario_permisos.php";
	if(
		moduloAccion('sistema_unico_beneficiarios','secciones_ine',$_COOKIE["id_usuario"],'Insert') ||
		moduloAccion('sistema_unico_beneficiarios','secciones_ine',$_COOKIE["id_usuario"],'All') ){
	}else{
		echo "No tiene permiso.";
		die;
	}

	if(!empty($_POST)){

		include __DIR__."/../functions/secciones_ine.php";
		$seccion_ineClaveVerificacion=seccion_ineClaveVerificacion($_POST["seccion_ine"][0]['clave'],'',1);
		if($seccion_ineClaveVerificacion){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}

		//metemos los valores para que se no tengamos error
		foreach($_POST["seccion_ine"][0] as $keyPrincipal => $atributo) {
			$_POST["seccion_ine"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$success=true;
		$_POST["seccion_ine"][0]['fechaR']=$fechaH;
		$_POST["seccion_ine"][0]['codigo_plataforma']=$codigo_plataforma; 

		$fields_pdo = "`".implode('`,`', array_keys($_POST["seccion_ine"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["seccion_ine"][0])."'";
		$insert_secciones_ine_ine= "INSERT INTO secciones_ine ($fields_pdo) VALUES ($values_pdo);";
		$conexion->autocommit(FALSE);
		$insert_secciones_ine_ine=$conexion->query($insert_secciones_ine_ine);
		$num=$conexion->affected_rows;
		if(!$insert_secciones_ine_ine || $num=0){
			$success=false;
			echo "ERROR insert_secciones_ine"; 
			var_dump($conexion->error);
		}
		$id=$_POST["seccion_ine"][0]['id_seccion_ine']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST["seccion_ine"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["seccion_ine"][0])."'";
		$insert_secciones_inehistoricos= "INSERT INTO secciones_ine_historicos ($fields_pdo) VALUES ($values_pdo);";
		$insert_secciones_inehistoricos=$conexion->query($insert_secciones_inehistoricos);
		$num=$conexion->affected_rows;
		if(!$insert_secciones_inehistoricos || $num=0){
			$success=false;
			echo "ERROR insert_secciones_ine_historicos"; 
			var_dump($conexion->error);
		}

		foreach ($_SESSION['limites'] as $key => $value) {
			$numero = $numero +1;
			if($value['status']==1 && $value['id']==''){
				//insertamos
				$conexion->autocommit(FALSE);
				unset($value['id']);
				unset($value['numero']);
				unset($value['status']);
				$value['id_seccion_ine'] = $id;
				$value['fechaR'] = $fechaH;
				$value['codigo_plataforma'] = $codigo_plataforma;
				$fields_pdo = "`".implode('`,`', array_keys($value))."`";
				$values_pdo = "'".implode("','", $value)."'";
				$insert_secciones_ine_parametros= "INSERT INTO secciones_ine_parametros ($fields_pdo) VALUES ($values_pdo);";
				$insert_secciones_ine_parametros=$conexion->query($insert_secciones_ine_parametros);
				$num=$conexion->affected_rows;
				if(!$insert_secciones_ine_parametros || $num=0){
					$success=false;
					echo "ERROR insert_secciones_ine_parametros"; 
					var_dump($conexion->error);
				}

				$value['id_seccion_ine_parametro'] = $conexion->insert_id;
				$fields_pdo = "`".implode('`,`', array_keys($value))."`";
				$values_pdo = "'".implode("','", $value)."'";
				$insert_secciones_ine_parametros_historicos= "INSERT INTO secciones_ine_parametros_historicos ($fields_pdo) VALUES ($values_pdo);";
				$insert_secciones_ine_parametros_historicos=$conexion->query($insert_secciones_ine_parametros_historicos);
				$num=$conexion->affected_rows;
				if(!$insert_secciones_ine_parametros_historicos || $num=0){
					$success=false;
					echo "ERROR insert_secciones_ine_parametros_historicos"; 
					var_dump($conexion->error);
				}

			}
			if($value['status']==0 && $value['id']!=''){
				//eliminamos
				$id = $value['id'];
				$delete_secciones_ine_parametros = "DELETE FROM secciones_ine_parametros  WHERE  id='$id' ";
				$conexion->autocommit(FALSE);
				$delete_secciones_ine_parametros=$conexion->query($delete_secciones_ine_parametros);
				$num=$conexion->affected_rows;
				if(!$delete_secciones_ine_parametros || $num=0){
					$success=false;
					echo "ERROR delete_secciones_ine_parametros"; 
					var_dump($conexion->error);
				}
			}
			if($value['status']==1 && $value['id']!=''){
				//editamos

				unset($value['numero']);
				unset($value['status']);

				if( registrosCompara("secciones_ine_parametros",$value,1) ){
					unset($valueSets);
					$value['fechaR']=$fechaH;
					$value['codigo_plataforma']=$codigo_plataforma;
					$value['id_seccion_ine'] = $_POST['seccion_ine'][0]['id'];
					foreach ($value as $keyT => $valueT) {
						if($keyT !='id'){
							$valueSets[] = $keyT . " = '" . $valueT . "'";
						}else{
							$id = $valueT;
						}
					}

					$update_secciones_ine_parametros = "UPDATE secciones_ine_parametros SET ". join(",",$valueSets) . " WHERE id=".$id;
					$conexion->autocommit(FALSE);
					$update_secciones_ine_parametros=$conexion->query($update_secciones_ine_parametros);
					$num=$conexion->affected_rows;
					if(!$update_secciones_ine_parametros || $num=0){
						$success=false;
						echo "<br>";
						echo "ERROR update_secciones_ine_parametros"; 
						var_dump($conexion->error);
					}

					$value['id_seccion_ine_parametro'] = $value['id'];
					unset($value['id']); 
					$fields_pdo = "`".implode('`,`', array_keys($value))."`";
					$values_pdo = "'".implode("','", $value)."'";
					$insert_secciones_ine_parametros_historicos= "INSERT INTO secciones_ine_parametros_historicos ($fields_pdo) VALUES ($values_pdo);";
					$insert_secciones_ine_parametros_historicos=$conexion->query($insert_secciones_ine_parametros_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_secciones_ine_parametros_historicos || $num=0){
						$success=false;
						echo "ERROR insert_secciones_ine_parametros_historicos"; 
						var_dump($conexion->error);
					}
				}
			}
		}


		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine',$id,'Insert','',$fechaH);
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
