<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/empleados_dependencias.php";
	include __DIR__."/../functions/usuario_permisos.php";

	$moduloAccionPermisos = moduloAccionPermisos('configuracion','empleados_dependencias',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}
	
	if(!empty($_POST)){
		foreach($_POST["empleado_dependencia"][0] as $keyPrincipal => $atributo) {
			$_POST["empleado_dependencia"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}

		// validamos si tiene alguna depedencia este usuario
		$empleado_dependenciaValidadorUnico = empleado_dependenciaValidadorUnico('',$_POST["empleado_dependencia"][0]['id_empleado'],$_POST["empleado_dependencia"][0]['id_dependencia']);
		if($empleado_dependenciaValidadorUnico==true){
			echo "Ya tiene asignado esta dependencia.";
			die;
		}
		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["empleado_dependencia"][0]['fechaR']=$fechaH; 
		$_POST["empleado_dependencia"][0]['codigo_plataforma']=$codigo_plataforma;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['empleado_dependencia'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['empleado_dependencia'][0])."'";
		$insert_tipos_giras= "INSERT INTO empleados_dependencias ($fields_pdo) VALUES ($values_pdo);";

		$insert_tipos_giras=$conexion->query($insert_tipos_giras);
		$num=$conexion->affected_rows;
		if(!$insert_tipos_giras || $num=0){
			$success=false;
			echo "ERROR insert empleados_dependencias"; 
			var_dump($conexion->error);
		}

		$id=$_POST['empleado_dependencia'][0]['id_empleado_dependencia']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['empleado_dependencia'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['empleado_dependencia'][0])."'";
		$insert_tipos_giras_historicos= "INSERT INTO empleados_dependencias_historicos ($fields_pdo) VALUES ($values_pdo);";

		$insert_tipos_giras_historicos=$conexion->query($insert_tipos_giras_historicos);
		$num=$conexion->affected_rows;
		if(!$insert_tipos_giras_historicos || $num=0){
			$success=false;
			echo "ERROR insert empleados_dependencias_historicos"; 
			var_dump($conexion->error);
		}
		

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'empleados_dependencias',$id,'Insert','',$fechaH);
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
