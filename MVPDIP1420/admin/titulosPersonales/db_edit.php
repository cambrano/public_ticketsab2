<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/titulos_personales.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad',"titulos_personales",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["titulo_personal"][0] as $keyPrincipal => $atributo) {
		$_POST["titulo_personal"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$titulo_personalClaveVerificacion=titulo_personalClaveVerificacion($_POST["titulo_personal"][0]["clave"],$_POST["titulo_personal"][0]['id'],1);
	if($titulo_personalClaveVerificacion){
		$claveF= clave("titulos_personales");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["titulo_personal"][0]["clave"] = $claveF["clave"];
		}
	}

	if( registrosCompara("titulos_personales",$_POST["titulo_personal"][0],1)){
		if(!empty($_POST)){ 
			$_POST["titulo_personal"][0]["fechaR"]=$fechaH;
			$_POST["titulo_personal"][0]["codigo_plataforma"]=$codigo_plataforma;
			$success=true;
			foreach($_POST["titulo_personal"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_titulos_personales = "UPDATE titulos_personales SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_titulos_personales=$conexion->query($update_titulos_personales);
			$num=$conexion->affected_rows;
			if(!$update_titulos_personales || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_titulos_personales"; 
				var_dump($conexion->error);
			}

			unset($_POST["titulo_personal"][0]['id']); 
			$id_titulo_personal=$_POST["titulo_personal"][0]["id_titulo_personal"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["titulo_personal"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["titulo_personal"][0])."'";
			$insert_titulos_personales_historicos= "INSERT INTO titulos_personales_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_titulos_personales_historicos=$conexion->query($insert_titulos_personales_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_titulos_personales_historicos || $num=0){
				$success=false;
				echo "ERROR insert_titulos_personales_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],"titulos_personales",$id_titulo_personal,'Update','',$fechaH);
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
