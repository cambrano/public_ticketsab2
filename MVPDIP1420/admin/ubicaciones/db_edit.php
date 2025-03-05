<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/ubicaciones.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad',"ubicaciones",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["ubicacion"][0] as $keyPrincipal => $atributo) {
		$_POST["ubicacion"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$ubicacionClaveVerificacion=ubicacionClaveVerificacion($_POST["ubicacion"][0]["clave"],$_POST["ubicacion"][0]['id'],1);
	if($ubicacionClaveVerificacion){
		$claveF= clave("ubicaciones");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["ubicacion"][0]["clave"] = $claveF["clave"];
		}
	}

	if( registrosCompara("ubicaciones",$_POST["ubicacion"][0],1)){
		if(!empty($_POST)){ 
			$_POST["ubicacion"][0]["fechaR"]=$fechaH;
			$_POST["ubicacion"][0]["codigo_plataforma"]=$codigo_plataforma;
			$success=true;
			foreach($_POST["ubicacion"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_ubicaciones = "UPDATE ubicaciones SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_ubicaciones=$conexion->query($update_ubicaciones);
			$num=$conexion->affected_rows;
			if(!$update_ubicaciones || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_ubicaciones"; 
				var_dump($conexion->error);
			}

			unset($_POST["ubicacion"][0]['id']); 
			$id_ubicacion=$_POST["ubicacion"][0]["id_ubicacion"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["ubicacion"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["ubicacion"][0])."'";
			$insert_ubicaciones_historicos= "INSERT INTO ubicaciones_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_ubicaciones_historicos=$conexion->query($insert_ubicaciones_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_ubicaciones_historicos || $num=0){
				$success=false;
				echo "ERROR insert_ubicaciones_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],"ubicaciones",$id_ubicacion,'Update','',$fechaH);
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
