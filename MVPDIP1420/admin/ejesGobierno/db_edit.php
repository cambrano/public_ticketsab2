<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/ejes_gobierno.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"ejes_gobierno",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["eje_gobierno"][0] as $keyPrincipal => $atributo) {
		$_POST["eje_gobierno"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}


	if( registrosCompara("ejes_gobierno",$_POST["eje_gobierno"][0],1)){
		if(!empty($_POST)){ 
			$_POST["eje_gobierno"][0]["fechaR"]=$fechaH;
			$_POST["eje_gobierno"][0]["codigo_plataforma"]=$codigo_plataforma;
			$success=true;
			foreach($_POST["eje_gobierno"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_ejes_gobierno = "UPDATE ejes_gobierno SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_ejes_gobierno=$conexion->query($update_ejes_gobierno);
			$num=$conexion->affected_rows;
			if(!$update_ejes_gobierno || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_ejes_gobierno"; 
				var_dump($conexion->error);
			}

			unset($_POST["eje_gobierno"][0]['id']); 
			$id_eje_gobierno=$_POST["eje_gobierno"][0]["id_eje_gobierno"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["eje_gobierno"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["eje_gobierno"][0])."'";
			$insert_ejes_gobierno_historicos= "INSERT INTO ejes_gobierno_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_ejes_gobierno_historicos=$conexion->query($insert_ejes_gobierno_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_ejes_gobierno_historicos || $num=0){
				$success=false;
				echo "ERROR insert_ejes_gobierno_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],"ejes_gobierno",$id_eje_gobierno,'Update','',$fechaH);
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
