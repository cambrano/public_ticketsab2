<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/tipos_giras.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"tipos_giras",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["tipo_gira"][0] as $keyPrincipal => $atributo) {
		$_POST["tipo_gira"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$tipo_giraClaveVerificacion=tipo_giraClaveVerificacion($_POST["tipo_gira"][0]["clave"],$_POST["tipo_gira"][0]['id'],1);
	if($tipo_giraClaveVerificacion){
		$claveF= clave("tipos_giras");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["tipo_gira"][0]["clave"] = $claveF["clave"];
		}
	}

	if( registrosCompara("tipos_giras",$_POST["tipo_gira"][0],1)){
		if(!empty($_POST)){ 
			$_POST["tipo_gira"][0]["fechaR"]=$fechaH;
			$_POST["tipo_gira"][0]["codigo_plataforma"]=$codigo_plataforma;
			$success=true;
			foreach($_POST["tipo_gira"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_tipos_giras = "UPDATE tipos_giras SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_tipos_giras=$conexion->query($update_tipos_giras);
			$num=$conexion->affected_rows;
			if(!$update_tipos_giras || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_tipos_giras"; 
				var_dump($conexion->error);
			}

			unset($_POST["tipo_gira"][0]['id']); 
			$id_tipo_gira=$_POST["tipo_gira"][0]["id_tipo_gira"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["tipo_gira"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["tipo_gira"][0])."'";
			$insert_tipos_giras_historicos= "INSERT INTO tipos_giras_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_tipos_giras_historicos=$conexion->query($insert_tipos_giras_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_tipos_giras_historicos || $num=0){
				$success=false;
				echo "ERROR insert_tipos_giras_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],"tipos_giras",$id_tipo_gira,'Update','',$fechaH);
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
