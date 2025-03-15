<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','distritos_federales',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	if(!empty($_POST)){

		include __DIR__."/../functions/distritos_federales.php";
		$tipo_actividadClaveVerificacion=tipo_actividadClaveVerificacion($_POST["distrito_federal"][0]['clave'],'',1);
		if($tipo_actividadClaveVerificacion){
			$claveF= clave('distritos_federales');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["distrito_federal"][0]['clave'] = $claveF['clave'];
			}
		}

		//metemos los valores para que se no tengamos error
		foreach($_POST["distrito_federal"][0] as $keyPrincipal => $atributo) {
			$_POST["distrito_federal"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$success=true;
		$_POST["distrito_federal"][0]['fechaR']=$fechaH;
		$_POST["distrito_federal"][0]['codigo_plataforma']=$codigo_plataforma; 

		$fields_pdo = "`".implode('`,`', array_keys($_POST["distrito_federal"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["distrito_federal"][0])."'";
		$insert_distritos_federales_ine= "INSERT INTO distritos_federales ($fields_pdo) VALUES ($values_pdo);";
		$conexion->autocommit(FALSE);
		$insert_distritos_federales_ine=$conexion->query($insert_distritos_federales_ine);
		$num=$conexion->affected_rows;
		if(!$insert_distritos_federales_ine || $num=0){
			$success=false;
			echo "ERROR insert_distritos_federales"; 
			var_dump($conexion->error);
		}
		$id=$_POST["distrito_federal"][0]['id_distrito_federal']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST["distrito_federal"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["distrito_federal"][0])."'";
		$insert_distritos_federaleshistoricos= "INSERT INTO distritos_federales_historicos ($fields_pdo) VALUES ($values_pdo);";
		$insert_distritos_federaleshistoricos=$conexion->query($insert_distritos_federaleshistoricos);
		$num=$conexion->affected_rows;
		if(!$insert_distritos_federaleshistoricos || $num=0){
			$success=false;
			echo "ERROR insert_distritos_federales_historicos"; 
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
				$value['id_distrito_federal'] = $id;
				$value['fechaR'] = $fechaH;
				$value['codigo_plataforma'] = $codigo_plataforma;
				$fields_pdo = "`".implode('`,`', array_keys($value))."`";
				$values_pdo = "'".implode("','", $value)."'";
				$insert_distritos_federales_parametros= "INSERT INTO distritos_federales_parametros ($fields_pdo) VALUES ($values_pdo);";
				$insert_distritos_federales_parametros=$conexion->query($insert_distritos_federales_parametros);
				$num=$conexion->affected_rows;
				if(!$insert_distritos_federales_parametros || $num=0){
					$success=false;
					echo "ERROR insert_distritos_federales_parametros"; 
					var_dump($conexion->error);
				}

				$value['id_distrito_federal_parametro'] = $conexion->insert_id;
				$fields_pdo = "`".implode('`,`', array_keys($value))."`";
				$values_pdo = "'".implode("','", $value)."'";
				$insert_distritos_federales_parametros_historicos= "INSERT INTO distritos_federales_parametros_historicos ($fields_pdo) VALUES ($values_pdo);";
				$insert_distritos_federales_parametros_historicos=$conexion->query($insert_distritos_federales_parametros_historicos);
				$num=$conexion->affected_rows;
				if(!$insert_distritos_federales_parametros_historicos || $num=0){
					$success=false;
					echo "ERROR insert_distritos_federales_parametros_historicos"; 
					var_dump($conexion->error);
				}

			}
			if($value['status']==0 && $value['id']!=''){
				//eliminamos
				$id = $value['id'];
				$delete_distritos_federales_parametros = "DELETE FROM distritos_federales_parametros  WHERE  id='$id' ";
				$conexion->autocommit(FALSE);
				$delete_distritos_federales_parametros=$conexion->query($delete_distritos_federales_parametros);
				$num=$conexion->affected_rows;
				if(!$delete_distritos_federales_parametros || $num=0){
					$success=false;
					echo "ERROR delete_distritos_federales_parametros"; 
					var_dump($conexion->error);
				}
			}
			if($value['status']==1 && $value['id']!=''){
				//editamos

				unset($value['numero']);
				unset($value['status']);

				if( registrosCompara("distritos_federales_parametros",$value,1) ){
					unset($valueSets);
					$value['fechaR']=$fechaH;
					$value['codigo_plataforma']=$codigo_plataforma;
					$value['id_distrito_federal'] = $_POST['distrito_federal'][0]['id'];
					foreach ($value as $keyT => $valueT) {
						if($keyT !='id'){
							$valueSets[] = $keyT . " = '" . $valueT . "'";
						}else{
							$id = $valueT;
						}
					}

					$update_distritos_federales_parametros = "UPDATE distritos_federales_parametros SET ". join(",",$valueSets) . " WHERE id=".$id;
					$conexion->autocommit(FALSE);
					$update_distritos_federales_parametros=$conexion->query($update_distritos_federales_parametros);
					$num=$conexion->affected_rows;
					if(!$update_distritos_federales_parametros || $num=0){
						$success=false;
						echo "<br>";
						echo "ERROR update_distritos_federales_parametros"; 
						var_dump($conexion->error);
					}

					$value['id_distrito_federal_parametro'] = $value['id'];
					unset($value['id']); 
					$fields_pdo = "`".implode('`,`', array_keys($value))."`";
					$values_pdo = "'".implode("','", $value)."'";
					$insert_distritos_federales_parametros_historicos= "INSERT INTO distritos_federales_parametros_historicos ($fields_pdo) VALUES ($values_pdo);";
					$insert_distritos_federales_parametros_historicos=$conexion->query($insert_distritos_federales_parametros_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_distritos_federales_parametros_historicos || $num=0){
						$success=false;
						echo "ERROR insert_distritos_federales_parametros_historicos"; 
						var_dump($conexion->error);
					}
				}
			}
		}


		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'distritos_federales',$id,'Insert','',$fechaH);
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
