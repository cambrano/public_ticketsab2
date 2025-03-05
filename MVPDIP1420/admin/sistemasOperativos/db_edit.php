<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/sistemas_operativos.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad',"sistemas_operativos",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["sistema_operativo"][0] as $keyPrincipal => $atributo) {
		$_POST["sistema_operativo"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$sistema_operativoClaveVerificacion=sistema_operativoClaveVerificacion($_POST["sistema_operativo"][0]["clave"],$_POST["sistema_operativo"][0]['id'],1);
	if($sistema_operativoClaveVerificacion){
		$claveF= clave("sistemas_operativos");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["sistema_operativo"][0]["clave"] = $claveF["clave"];
		}
	}

	if( registrosCompara("sistemas_operativos",$_POST["sistema_operativo"][0],1)){
		if(!empty($_POST)){ 
			$_POST["sistema_operativo"][0]["fechaR"]=$fechaH;
			$_POST["sistema_operativo"][0]["codigo_plataforma"]=$codigo_plataforma;
			$success=true;
			foreach($_POST["sistema_operativo"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_sistemas_operativos = "UPDATE sistemas_operativos SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_sistemas_operativos=$conexion->query($update_sistemas_operativos);
			$num=$conexion->affected_rows;
			if(!$update_sistemas_operativos || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_sistemas_operativos"; 
				var_dump($conexion->error);
			}

			unset($_POST["sistema_operativo"][0]['id']); 
			$id_sistema_operativo=$_POST["sistema_operativo"][0]["id_sistema_operativo"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["sistema_operativo"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["sistema_operativo"][0])."'";
			$insert_sistemas_operativos_historicos= "INSERT INTO sistemas_operativos_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_sistemas_operativos_historicos=$conexion->query($insert_sistemas_operativos_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_sistemas_operativos_historicos || $num=0){
				$success=false;
				echo "ERROR insert_sistemas_operativos_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],"sistemas_operativos",$id_sistema_operativo,'Update','',$fechaH);
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
