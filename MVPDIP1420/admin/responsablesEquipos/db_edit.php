<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/responsables_equipos.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad',"responsables_equipos",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["responsable_equipo"][0] as $keyPrincipal => $atributo) {
		$_POST["responsable_equipo"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$responsable_equipoClaveVerificacion=responsable_equipoClaveVerificacion($_POST["responsable_equipo"][0]["clave"],$_POST["responsable_equipo"][0]['id'],1);
	if($responsable_equipoClaveVerificacion){
		$claveF= clave("responsables_equipos");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["responsable_equipo"][0]["clave"] = $claveF["clave"];
		}
	}

	if( registrosCompara("responsables_equipos",$_POST["responsable_equipo"][0],1)){
		if(!empty($_POST)){ 
			$_POST["responsable_equipo"][0]["fechaR"]=$fechaH;
			$_POST["responsable_equipo"][0]["codigo_plataforma"]=$codigo_plataforma;
			$success=true;
			foreach($_POST["responsable_equipo"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_responsables_equipos = "UPDATE responsables_equipos SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_responsables_equipos=$conexion->query($update_responsables_equipos);
			$num=$conexion->affected_rows;
			if(!$update_responsables_equipos || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_responsables_equipos"; 
				var_dump($conexion->error);
			}

			unset($_POST["responsable_equipo"][0]['id']); 
			$id_responsable_equipo=$_POST["responsable_equipo"][0]["id_responsable_equipo"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["responsable_equipo"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["responsable_equipo"][0])."'";
			$insert_responsables_equipos_historicos= "INSERT INTO responsables_equipos_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_responsables_equipos_historicos=$conexion->query($insert_responsables_equipos_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_responsables_equipos_historicos || $num=0){
				$success=false;
				echo "ERROR insert_responsables_equipos_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],"responsables_equipos",$id_responsable_equipo,'Update','',$fechaH);
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
