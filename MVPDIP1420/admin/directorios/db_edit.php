<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/directorios.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad',"directorios",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["directorio"][0] as $keyPrincipal => $atributo) {
		$_POST["directorio"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$directorioClaveVerificacion=directorioClaveVerificacion($_POST["directorio"][0]["clave"],$_POST["directorio"][0]['id'],1);
	if($directorioClaveVerificacion){
		$claveF= clave("directorios");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["directorio"][0]["clave"] = $claveF["clave"];
		}
	}

	if( registrosCompara("directorios",$_POST["directorio"][0],1)){
		if(!empty($_POST)){ 
			$_POST["directorio"][0]["fechaR"]=$fechaH;
			$_POST["directorio"][0]["codigo_plataforma"]=$codigo_plataforma;
			$success=true;
			foreach($_POST["directorio"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_directorios = "UPDATE directorios SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_directorios=$conexion->query($update_directorios);
			$num=$conexion->affected_rows;
			if(!$update_directorios || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_directorios"; 
				var_dump($conexion->error);
			}

			unset($_POST["directorio"][0]['id']); 
			$id_directorio=$_POST["directorio"][0]["id_directorio"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["directorio"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["directorio"][0])."'";
			$insert_directorios_historicos= "INSERT INTO directorios_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_directorios_historicos=$conexion->query($insert_directorios_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_directorios_historicos || $num=0){
				$success=false;
				echo "ERROR insert_directorios_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],"directorios",$id_directorio,'Update','',$fechaH);
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
