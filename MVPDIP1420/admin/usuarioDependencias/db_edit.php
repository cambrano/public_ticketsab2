<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/empleados_dependencias.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('configuracion','empleados_dependencias',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["empleado_dependencia"][0] as $keyPrincipal => $atributo) {
		$_POST["empleado_dependencia"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}


	if( registrosCompara("empleados_dependencias",$_POST["empleado_dependencia"][0],1)){
		if(!empty($_POST)){ 
			$_POST["empleado_dependencia"][0]["fechaR"]=$fechaH;
			$_POST["empleado_dependencia"][0]["codigo_plataforma"]=$codigo_plataforma;
			$success=true;
			foreach($_POST["empleado_dependencia"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_tipos_giras = "UPDATE empleados_dependencias SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_tipos_giras=$conexion->query($update_tipos_giras);
			$num=$conexion->affected_rows;
			if(!$update_tipos_giras || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_empleados_dependencias"; 
				var_dump($conexion->error);
			}

			unset($_POST["empleado_dependencia"][0]['id']); 
			$id_empleado_dependencia=$_POST["empleado_dependencia"][0]["id_empleado_dependencia"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["empleado_dependencia"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["empleado_dependencia"][0])."'";
			$insert_tipos_giras_historicos= "INSERT INTO empleados_dependencias_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_tipos_giras_historicos=$conexion->query($insert_tipos_giras_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_tipos_giras_historicos || $num=0){
				$success=false;
				echo "ERROR insert_empleados_dependencias_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],"empleados_dependencias",$id_empleado_dependencia,'Update','',$fechaH);
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
