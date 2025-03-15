<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	$moduloAccionPermisos = moduloAccionPermisos('configuracion','claves',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["claves"][0] as $keyPrincipal => $atributo) {
		$_POST["claves"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$sql ="SELECT * FROM claves_2 WHERE 1 = 1 ";
	$result = $conexion->query($sql);
	$row=$result->fetch_assoc();
	$id=$row['id'];
	$_POST["claves"][0]['referencia_importacion']=$row['referencia_importacion'];
	if(!empty($id)){ 
		$_POST["claves"][0]['id']=$id;
		$registroCompara= registrosCompara("claves_2",$_POST["claves"][0],1);
		unset($_POST["claves"][0]['id']);
	}else{
		$registroCompara=true;
	}
	
	if($registroCompara){
		if(!empty($_POST["claves"][0])){
			$_POST["claves"][0]['codigo_plataforma']=$codigo_plataforma;
			//checamos si tiene un registro si no es update
			$sql ="SELECT * FROM claves_2 WHERE 1 = 1 ";
			$result = $conexion->query($sql);
			$row=$result->fetch_assoc();
			$id=$row['id'];
			$success=true;
			$_POST["claves"][0]['fechaR']=$fechaH;
			if(empty($id)){ 
				//agrega
				$tipo="Insert";
				$fields_pdo = "`".implode('`,`', array_keys($_POST["claves"][0]))."`";
				$values_pdo = "'".implode("','", $_POST["claves"][0])."'";
				$inset_claves= "INSERT INTO claves_2 ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$inset_claves=$conexion->query($inset_claves);
				$num=$conexion->affected_rows;
				if(!$inset_claves || $num=0){
					$success=false;
					echo "ERROR inset_claves"; 
					var_dump($conexion->error);
				}
				$id=$_POST["claves"][0]['id_clave']=$conexion->insert_id;
				$fields_pdo = "`".implode('`,`', array_keys($_POST["claves"][0]))."`";
				$values_pdo = "'".implode("','", $_POST["claves"][0])."'";
				$inset_claves_historicos= "INSERT INTO claves_2_historicos ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$success=$inset_claves_historicos=$conexion->query($inset_claves_historicos);
				$num=$conexion->affected_rows;
				if(!$inset_claves_historicos || $num=0){
					$success=false;
					echo "ERROR inset_claves_historicos"; 
					var_dump($conexion->error);
				}
			}else{
				//edita
				$tipo="Update";
				$_POST["claves"][0]['fechaR']=$fechaH;
				foreach($_POST["claves"][0] as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}


				$conexion->autocommit(FALSE);
				$update_claves = "UPDATE claves_2 SET ". join(",",$valueSets) . " WHERE id=".$id;
				$update_claves=$conexion->query($update_claves);
				$num=$conexion->affected_rows;
				if(!$update_claves || $num=0){
					$success=false;
					echo "ERROR update_claves"; 
					var_dump($conexion->error);
				}

				$_POST["claves"][0]['id_clave']=$id;
				unset($_POST["claves"][0]['id']);
				$fields_pdo = "`".implode('`,`', array_keys($_POST["claves"][0]))."`";
				$values_pdo = "'".implode("','", $_POST["claves"][0])."'";
				$inset_claves_historicos= "INSERT INTO claves_2_historicos ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$success=$inset_claves_historicos=$conexion->query($inset_claves_historicos);
				$num=$conexion->affected_rows;
				if(!$inset_claves_historicos || $num=0){
					$success=false;
					echo "ERROR inset_claves_historicos"; 
					var_dump($conexion->error);
				}
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'claves_2',$id,$tipo,'',$fechaH);
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