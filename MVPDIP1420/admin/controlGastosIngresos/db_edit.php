<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/control_gastos_ingresos.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad',"control_gastos_ingresos",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["tipo_equipo"][0] as $keyPrincipal => $atributo) {
		$_POST["tipo_equipo"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$tipo_equipoClaveVerificacion=tipo_equipoClaveVerificacion($_POST["tipo_equipo"][0]["clave"],$_POST["tipo_equipo"][0]['id'],1);
	if($tipo_equipoClaveVerificacion){
		$claveF= clave("control_gastos_ingresos");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["tipo_equipo"][0]["clave"] = $claveF["clave"];
		}
	}

	if( registrosCompara("control_gastos_ingresos",$_POST["tipo_equipo"][0],1)){
		if(!empty($_POST)){ 
			$_POST["tipo_equipo"][0]["fechaR"]=$fechaH;
			$_POST["tipo_equipo"][0]["codigo_plataforma"]=$codigo_plataforma;
			$success=true;
			foreach($_POST["tipo_equipo"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_control_gastos_ingresos = "UPDATE control_gastos_ingresos SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_control_gastos_ingresos=$conexion->query($update_control_gastos_ingresos);
			$num=$conexion->affected_rows;
			if(!$update_control_gastos_ingresos || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_control_gastos_ingresos"; 
				var_dump($conexion->error);
			}

			unset($_POST["tipo_equipo"][0]['id']); 
			$id_tipo_equipo=$_POST["tipo_equipo"][0]["id_tipo_equipo"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["tipo_equipo"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["tipo_equipo"][0])."'";
			$insert_control_gastos_ingresos_historicos= "INSERT INTO control_gastos_ingresos_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_control_gastos_ingresos_historicos=$conexion->query($insert_control_gastos_ingresos_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_control_gastos_ingresos_historicos || $num=0){
				$success=false;
				echo "ERROR insert_control_gastos_ingresos_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],"control_gastos_ingresos",$id_tipo_equipo,'Update','',$fechaH);
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
