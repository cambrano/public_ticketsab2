<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/dependencias.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad',"dependencias",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["dependencia"][0] as $keyPrincipal => $atributo) {
		$_POST["dependencia"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$dependenciaClaveVerificacion=dependenciaClaveVerificacion($_POST["dependencia"][0]["clave"],$_POST["dependencia"][0]['id'],1);
	if($dependenciaClaveVerificacion){
		$claveF= clave("dependencias");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["dependencia"][0]["clave"] = $claveF["clave"];
		}
	}

	if( registrosCompara("dependencias",$_POST["dependencia"][0],1)){
		if(!empty($_POST)){ 
			$_POST["dependencia"][0]["fechaR"]=$fechaH;
			$_POST["dependencia"][0]["codigo_plataforma"]=$codigo_plataforma;
			$success=true;
			foreach($_POST["dependencia"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_dependencias = "UPDATE dependencias SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_dependencias=$conexion->query($update_dependencias);
			$num=$conexion->affected_rows;
			if(!$update_dependencias || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_dependencias"; 
				var_dump($conexion->error);
			}

			unset($_POST["dependencia"][0]['id']); 
			$id_dependencia=$_POST["dependencia"][0]["id_dependencia"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["dependencia"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["dependencia"][0])."'";
			$insert_dependencias_historicos= "INSERT INTO dependencias_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_dependencias_historicos=$conexion->query($insert_dependencias_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_dependencias_historicos || $num=0){
				$success=false;
				echo "ERROR insert_dependencias_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],"dependencias",$id_dependencia,'Update','',$fechaH);
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
