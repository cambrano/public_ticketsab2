<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/cuarteles.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"cuarteles",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["cuartel"][0] as $keyPrincipal => $atributo) {
		$_POST["cuartel"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$cuartelClaveVerificacion=cuartelClaveVerificacion($_POST["cuartel"][0]["clave"],$_POST["cuartel"][0]['id'],1);
	if($cuartelClaveVerificacion){
		$claveF= clave("cuarteles");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["cuartel"][0]["clave"] = $claveF["clave"];
		}
	}

	if( registrosCompara("cuarteles",$_POST["cuartel"][0],1)){
		if(!empty($_POST)){ 
			$_POST["cuartel"][0]["fechaR"]=$fechaH;
			$_POST["cuartel"][0]["codigo_plataforma"]=$codigo_plataforma;
			$success=true;
			foreach($_POST["cuartel"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_cuarteles = "UPDATE cuarteles SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_cuarteles=$conexion->query($update_cuarteles);
			$num=$conexion->affected_rows;
			if(!$update_cuarteles || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_cuarteles"; 
				var_dump($conexion->error);
			}

			unset($_POST["cuartel"][0]['id']); 
			$id_cuartel=$_POST["cuartel"][0]["id_cuartel"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["cuartel"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["cuartel"][0])."'";
			$insert_cuarteles_historicos= "INSERT INTO cuarteles_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_cuarteles_historicos=$conexion->query($insert_cuarteles_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_cuarteles_historicos || $num=0){
				$success=false;
				echo "ERROR insert_cuarteles_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],"cuarteles",$id_cuartel,'Update','',$fechaH);
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
