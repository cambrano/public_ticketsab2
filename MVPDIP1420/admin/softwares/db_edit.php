<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/softwares.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad',"softwares",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["software"][0] as $keyPrincipal => $atributo) {
		$_POST["software"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$softwareClaveVerificacion=softwareClaveVerificacion($_POST["software"][0]["clave"],$_POST["software"][0]['id'],1);
	if($softwareClaveVerificacion){
		$claveF= clave("softwares");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["software"][0]["clave"] = $claveF["clave"];
		}
	}

	if( registrosCompara("softwares",$_POST["software"][0],1)){
		if(!empty($_POST)){ 
			$_POST["software"][0]["fechaR"]=$fechaH;
			$_POST["software"][0]["codigo_plataforma"]=$codigo_plataforma;
			$success=true;
			foreach($_POST["software"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_softwares = "UPDATE softwares SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_softwares=$conexion->query($update_softwares);
			$num=$conexion->affected_rows;
			if(!$update_softwares || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_softwares"; 
				var_dump($conexion->error);
			}

			unset($_POST["software"][0]['id']); 
			$id_software=$_POST["software"][0]["id_software"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["software"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["software"][0])."'";
			$insert_softwares_historicos= "INSERT INTO softwares_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_softwares_historicos=$conexion->query($insert_softwares_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_softwares_historicos || $num=0){
				$success=false;
				echo "ERROR insert_softwares_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],"softwares",$id_software,'Update','',$fechaH);
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
