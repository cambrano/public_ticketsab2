<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/zonas_importantes.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('security',"zonas_importantes",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["zona_importante"][0] as $keyPrincipal => $atributo) {
		$_POST["zona_importante"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	if( registrosCompara("zonas_importantes",$_POST['zona_importante'][0],1) ){
		if(!empty($_POST)){
			$zona_importanteDatos=zona_importanteDatos($_POST['zona_importante'][0]['id']);


			//$_POST['registro']=$fechaH;
			$_POST["zona_importante"][0]['fechaR']=$fechaH;

			$_POST["zona_importante"][0]['codigo_plataforma']=$codigo_plataforma;
			$_POST["zona_importante"][0]["referencia_importacion"]=$zona_importanteDatos['referencia_importacion'];


			$success=true;
			foreach($_POST['zona_importante'] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}

			$update_zonas_importantes = "UPDATE zonas_importantes SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_zonas_importantes=$conexion->query($update_zonas_importantes);
			$num=$conexion->affected_rows;
			if(!$update_zonas_importantes || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_zonas_importantes"; 
				var_dump($conexion->error);
			}

			unset($_POST["zona_importante"][0]['id']); 
			$id_zona_importante=$_POST['zona_importante'][0]['id_zona_importante']=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["zona_importante"][0]))."`";
			$values_pdo = "'".implode("','", $_POST['zona_importante'][0])."'";
			$inset_zonas_importantes_historicos= "INSERT INTO zonas_importantes_historicos ($fields_pdo) VALUES ($values_pdo);";
			$inset_zonas_importantes_historicos=$conexion->query($inset_zonas_importantes_historicos);
			$num=$conexion->affected_rows;
			if(!$inset_zonas_importantes_historicos || $num=0){
				$success=false;
				echo "ERROR inset_zonas_importantes_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'zonas_importantes',$id_zona_importante,'Update','',$fechaH);
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
