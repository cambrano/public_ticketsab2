<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','equipos',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['delete'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST as $keyPrincipal => $atributo) {
			$_POST[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$conexion->autocommit(FALSE);
		$id=$_POST['id']; 
		$success=true;

		$update['id'] = $id;
		$update['fechaR'] = $fechaH;
		$update['status'] = 0;
		$update['codigo_plataforma'] = $codigo_plataforma;
		
		$success=true;
		foreach($update as $key => $value) {
			if($key !='id'){
				$valueSets[] = $key . " = '" . $value . "'";
			}else{
				$id=$value;
			}
		}

		$update_equipo_directorios = "UPDATE equipos_directorios SET ". join(",",$valueSets) . " WHERE id=".$id;
		$conexion->autocommit(FALSE);
		$update_equipo_directorios=$conexion->query($update_equipo_directorios);
		$num=$conexion->affected_rows;
		if(!$update_equipo_directorios || $num=0){
			$success=false;
			echo "<br>";
			echo "ERROR update_equipo_directorios"; 
			var_dump($conexion->error);
			echo "<br>";echo "<br>";
		}

		$update["id_equipo_directorio"]=$id;
		unset($update['id']);
		$fields_pdo = "`".implode('`,`', array_keys($update))."`";
		$values_pdo = "'".implode("','", $update)."'";
		$insert_equipo_directorios_historicos= "INSERT INTO equipos_directorios_historicos ($fields_pdo) VALUES ($values_pdo);";
		$insert_equipo_directorios_historicos=$conexion->query($insert_equipo_directorios_historicos);
		$num=$conexion->affected_rows;
		if(!$insert_equipo_directorios_historicos || $num=0){
			$success=false;
			echo "ERROR insert_equipo_directorios_historicos"; 
			var_dump($conexion->error);
			echo "<br>";echo "<br>";
		}

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'equipos_directorios',$id,'Update','',$fechaH);
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
			echo "";
			$conexion->rollback();
			$conexion->close();
		}
		 
	}
