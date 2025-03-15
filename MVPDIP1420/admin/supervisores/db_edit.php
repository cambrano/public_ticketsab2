<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/supervisores.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"supervisores",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error

	$_POST['supervisor'][0]['nombre_completo'] = $_POST['supervisor'][0]['nombre'].' '.$_POST['supervisor'][0]['apellido_paterno'].' '.$_POST['supervisor'][0]['apellido_materno'];

	foreach($_POST["supervisor"][0] as $keyPrincipal => $atributo) {
		$_POST["supervisor"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$supervisorClaveVerificacion=supervisorClaveVerificacion($_POST["supervisor"][0]["clave"],$_POST["supervisor"][0]['id'],1);
	if($supervisorClaveVerificacion){
		$claveF= clave("supervisores");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["supervisor"][0]["clave"] = $claveF["clave"];
		}
	}

	if( registrosCompara("supervisores",$_POST["supervisor"][0],1)){
		if(!empty($_POST)){ 
			$_POST["supervisor"][0]["fechaR"]=$fechaH;
			$_POST["supervisor"][0]["codigo_plataforma"]=$codigo_plataforma;
			$success=true;
			foreach($_POST["supervisor"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_supervisores = "UPDATE supervisores SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_supervisores=$conexion->query($update_supervisores);
			$num=$conexion->affected_rows;
			if(!$update_supervisores || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_supervisores"; 
				var_dump($conexion->error);
			}

			unset($_POST["supervisor"][0]['id']); 
			$id_supervisor=$_POST["supervisor"][0]["id_supervisor"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["supervisor"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["supervisor"][0])."'";
			$insert_supervisores_historicos= "INSERT INTO supervisores_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_supervisores_historicos=$conexion->query($insert_supervisores_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_supervisores_historicos || $num=0){
				$success=false;
				echo "ERROR insert_supervisores_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],"supervisores",$id_supervisor,'Update','',$fechaH);
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
