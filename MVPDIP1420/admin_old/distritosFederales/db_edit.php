<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/distritos_federales.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"distritos_federales",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["distrito_federal"][0] as $keyPrincipal => $atributo) {
		$_POST["distrito_federal"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$tipo_actividadClaveVerificacion=tipo_actividadClaveVerificacion($_POST["distrito_federal"][0]["clave"],$_POST["distrito_federal"][0]['id'],1);
	if($tipo_actividadClaveVerificacion){
		$claveF= clave("distritos_federales");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["distrito_federal"][0]["clave"] = $claveF["clave"];
		}
	}

	if(!empty($_POST)){
		if( registrosCompara("distritos_federales",$_POST['distrito_federal'][0],1) ){
			$distrito_federalDatos=distrito_federalDatos($_POST['distrito_federal'][0]['id']);
			//$_POST['registro']=$fechaH;
			$_POST["distrito_federal"][0]['fechaR']=$fechaH;
			$_POST["distrito_federal"][0]['codigo_plataforma']=$codigo_plataforma;
			$id_distrito_federal = $_POST['distrito_federal'][0]['id'];


			$success=true;
			foreach($_POST['distrito_federal'] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_distritos_federales = "UPDATE distritos_federales SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_distritos_federales=$conexion->query($update_distritos_federales);
			$num=$conexion->affected_rows;
			if(!$update_distritos_federales || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_distritos_federales"; 
				var_dump($conexion->error);
			}

			unset($_POST["distrito_federal"][0]['id']); 
			$id_distrito_federal=$_POST['distrito_federal'][0]['id_distrito_federal']=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["distrito_federal"][0]))."`";
			$values_pdo = "'".implode("','", $_POST['distrito_federal'][0])."'";
			$insert_distritos_federales_historicos= "INSERT INTO distritos_federales_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_distritos_federales_historicos=$conexion->query($insert_distritos_federales_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_distritos_federales_historicos || $num=0){
				$success=false;
				echo "ERROR insert_distritos_federales_historicos"; 
				var_dump($conexion->error);
			}

			foreach ($_SESSION['limites'] as $key => $value) {
				$success=true;
				$numero = $numero +1;
				if($value['status']==1 && $value['id']==''){
					//insertamos
					$conexion->autocommit(FALSE);
					unset($value['id']);
					unset($value['numero']);
					unset($value['status']);
					$value['id_distrito_federal'] = $id_distrito_federal;
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
						$value['id_distrito_federal'] = $id_distrito_federal;
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
				$log= logUsuario($_COOKIE["id_usuario"],'distritos_federales',$id_distrito_federal,'Update','',$fechaH);
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
		}else{
			$numero = 0;
			foreach ($_SESSION['limites'] as $key => $value) {
				$success=true;
				$numero = $numero +1;
				if($value['status']==1 && $value['id']==''){
					//insertamos
					$conexion->autocommit(FALSE);
					unset($value['id']);
					unset($value['numero']);
					unset($value['status']);
					$value['id_distrito_federal'] = $_POST['distrito_federal'][0]['id'];
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
			if($numero > 0){
				if($success){
					$log= logUsuario($_COOKIE["id_usuario"],'distritos_federales',$id_distrito_federal,'Update','',$fechaH);
					if($log==true){
						echo "SI";
						unset($_SESSION['limites']);
						unset($_SESSION['limites_num']);
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
	}
