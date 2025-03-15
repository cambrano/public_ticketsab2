<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/encuestas.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"encuestas",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["encuesta"][0] as $keyPrincipal => $atributo) {
		$_POST["encuesta"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$encuestaClaveVerificacion=encuestaClaveVerificacion($_POST["encuesta"][0]["clave"],$_POST["encuesta"][0]['id'],1);
	if($encuestaClaveVerificacion){
		$_POST["encuesta"][0]['clave'] = $cod16M;
	}

	if( registrosCompara("encuestas",$_POST["encuesta"][0],1)){
		if(!empty($_POST)){ 
			$_POST["encuesta"][0]["fechaR"]=$fechaH;
			$_POST["encuesta"][0]["codigo_plataforma"]=$codigo_plataforma;
			$success=true;
			foreach($_POST["encuesta"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_encuestas = "UPDATE encuestas SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_encuestas=$conexion->query($update_encuestas);
			$num=$conexion->affected_rows;
			if(!$update_encuestas || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_encuestas"; 
				var_dump($conexion->error);
			}

			unset($_POST["encuesta"][0]['id']); 
			$id_encuesta=$_POST["encuesta"][0]["id_encuesta"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["encuesta"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["encuesta"][0])."'";
			$insert_encuestas_historicos= "INSERT INTO encuestas_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_encuestas_historicos=$conexion->query($insert_encuestas_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_encuestas_historicos || $num=0){
				$success=false;
				echo "ERROR insert_encuestas_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],"encuestas",$id_encuesta,'Update','',$fechaH);
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
