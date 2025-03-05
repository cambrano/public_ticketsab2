<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/equipos.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/genid.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','equipos',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}
	
	//var_dump($_POST["equipo_directorio"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["equipo_directorio"][0] as $keyPrincipal => $atributo) {
			$_POST["equipo_directorio"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		
		//! buscamos 
		$sql = "SELECT id FROM equipos WHERE clave = '".$_POST["equipo_directorio"][0]['clave']."' AND folio = '".$_POST["equipo_directorio"][0]['folio']."' AND id = '".$_POST["equipo_directorio"][0]['id_equipo']."'; ";
		$resultado = $conexion->query($sql); 
		$row=$resultado->fetch_assoc();
		if($row['id']==""){
			echo "Error: La información proporcionada no coincide con ningún equipo registrado.";
			die;
		}
		//! validamos si ya esta asignado
		$sql = "SELECT id FROM equipos_directorios WHERE id_equipo = '".$row['id']."' AND status = 1; ";
		$resultado = $conexion->query($sql); 
		$row=$resultado->fetch_assoc();
		if($row['id']!=""){
			echo "Error: Este equipo ya esta asignado.";
			die;
		}


		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["equipo_directorio"][0]['fechaR'] = $fechaH; 
		$_POST["equipo_directorio"][0]['status'] = 1; 
		$_POST["equipo_directorio"][0]['codigo_plataforma'] = $codigo_plataforma;
		unset($_POST["equipo_directorio"][0]['clave']);
		unset($_POST["equipo_directorio"][0]['folio']);
		
		


		$fields_pdo = "`".implode('`,`', array_keys($_POST["equipo_directorio"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["equipo_directorio"][0])."'";
		$insert_equipos= "INSERT INTO equipos_directorios ($fields_pdo) VALUES ($values_pdo);";

		$insert_equipos=$conexion->query($insert_equipos);
		$num=$conexion->affected_rows;
		if(!$insert_equipos || $num=0){
			$success=false;
			echo "ERROR insert_equipos_directorios"; 
			var_dump($conexion->error);
			echo "<br>";echo "<br>";
			die;
		}

		$id=$_POST["equipo_directorio"][0]['id_equipo_directorio']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST["equipo_directorio"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["equipo_directorio"][0])."'";
		$insert_equipos_historicos= "INSERT INTO equipos_directorios_historicos ($fields_pdo) VALUES ($values_pdo);";

		$insert_equipos_historicos=$conexion->query($insert_equipos_historicos);
		$num=$conexion->affected_rows;
		if(!$insert_equipos_historicos || $num=0){
			$success=false;
			echo "ERROR insert_equipos_historicos"; 
			var_dump($conexion->error);
			echo "<br>";echo "<br>";
			die;
		}


		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'equipos_directorios',$id,'Insert','',$fechaH);
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