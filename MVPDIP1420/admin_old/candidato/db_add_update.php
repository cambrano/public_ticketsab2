<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/configuracion.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','candidato',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["candidato"][0] as $keyPrincipal => $atributo) {
		$_POST["candidato"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$sql ="SELECT * FROM candidato WHERE 1 = 1 ";
	$result = $conexion->query($sql);
	$row=$result->fetch_assoc();
	$id=$row['id'];
	//$_POST["candidato"][0]['referencia_importacion']=$row['referencia_importacion'];
	if(!empty($id)){ 
		$_POST["candidato"][0]['id']=$id;
		$registroCompara= registrosCompara("candidato",$_POST["candidato"][0],1);
		unset($_POST["candidato"][0]['id']);
	}else{
		$registroCompara=true;
	}
	
	if($registroCompara){
		if(!empty($_POST["candidato"][0])){
			$_POST["candidato"][0]['codigo_plataforma']=$codigo_plataforma;
			//checamos si tiene un registro si no es update
			$sql ="SELECT * FROM candidato WHERE 1 = 1 ";
			$result = $conexion->query($sql);
			$row=$result->fetch_assoc();
			$id=$row['id'];
			$success=true;
			$_POST["candidato"][0]['fechaR']=$fechaH;
			if(empty($id)){ 
				//agrega
				$tipo="Insert";
				$fields_pdo = "`".implode('`,`', array_keys($_POST["candidato"][0]))."`";
				$values_pdo = "'".implode("','", $_POST["candidato"][0])."'";
				$inset_candidato= "INSERT INTO candidato ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$inset_candidato=$conexion->query($inset_candidato);
				$num=$conexion->affected_rows;
				if(!$inset_candidato || $num=0){
					$success=false;
					echo "ERROR inset_candidato"; 
					var_dump($conexion->error);
				}
				$id=$_POST["candidato"][0]['id_candidato']=$conexion->insert_id;
				$fields_pdo = "`".implode('`,`', array_keys($_POST["candidato"][0]))."`";
				$values_pdo = "'".implode("','", $_POST["candidato"][0])."'";
				$inset_candidato_historicos= "INSERT INTO candidato_historicos ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$success=$inset_candidato_historicos=$conexion->query($inset_candidato_historicos);
				$num=$conexion->affected_rows;
				if(!$inset_candidato_historicos || $num=0){
					$success=false;
					echo "ERROR inset_candidato_historicos"; 
					var_dump($conexion->error);
				}
			}else{
				foreach($_POST["candidato"][0] as $keyPrincipal => $atributo) {
					if($atributo !=''){
						$_POST["candidato"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
					}else{
						$_POST["candidato"][0][$keyPrincipal] = 0;
					}
				}
				//edita
				$tipo="Update";
				$_POST["candidato"][0]['fechaR']=$fechaH;
				foreach($_POST["candidato"][0] as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}


				$conexion->autocommit(FALSE);
				$update_candidato = "UPDATE candidato SET ". join(",",$valueSets) . " WHERE id=".$id;
				$update_candidato=$conexion->query($update_candidato);
				$num=$conexion->affected_rows;
				if(!$update_candidato || $num=0){
					$success=false;
					echo "ERROR update_candidato"; 
					var_dump($conexion->error);
				}

				$_POST["candidato"][0]['id_candidato']=$id;
				unset($_POST["candidato"][0]['id']);
				$fields_pdo = "`".implode('`,`', array_keys($_POST["candidato"][0]))."`";
				$values_pdo = "'".implode("','", $_POST["candidato"][0])."'";
				$inset_candidato_historicos= "INSERT INTO candidato_historicos ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$success=$inset_candidato_historicos=$conexion->query($inset_candidato_historicos);
				$num=$conexion->affected_rows;
				if(!$inset_candidato_historicos || $num=0){
					$success=false;
					echo "ERROR inset_candidato_historicos"; 
					var_dump($conexion->error);
				}
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'candidato',$id,$tipo,'',$fechaH);
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