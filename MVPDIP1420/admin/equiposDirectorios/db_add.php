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
		$equipoClaveVerificacion=equipoClaveVerificacion($_POST["equipo_directorio"][0]['clave'],'',1);
		if($equipoClaveVerificacion){
			$claveF= clave('equipos');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["equipo_directorio"][0]['clave'] = $claveF['clave'];
			}
		}

		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["equipo_directorio"][0]['fechaR']=$fechaH; 
		$_POST["equipo_directorio"][0]['codigo_plataforma']=$codigo_plataforma;
		$_POST["equipo_directorio"][0]['identificador']=$gen_id3.'_'.$cod32;
		$id_directorio = $_POST["equipo_directorio"][0]['id_directorio'];
		unset($_POST["equipo_directorio"][0]['id_directorio']);


		$fields_pdo = "`".implode('`,`', array_keys($_POST["equipo_directorio"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["equipo_directorio"][0])."'";
		$insert_equipos= "INSERT INTO equipos ($fields_pdo) VALUES ($values_pdo);";

		$insert_equipos=$conexion->query($insert_equipos);
		$num=$conexion->affected_rows;
		if(!$insert_equipos || $num=0){
			$success=false;
			echo "ERROR insert_equipos"; 
			var_dump($conexion->error);
			echo "<br>";echo "<br>";
			die;
		}

		$id=$_POST["equipo_directorio"][0]['id_equipo']=$conexion->insert_id;
		$log[] = array(
			'id' => $id,
			'tabla' => 'equipos',
			'tipo' => 'Insert',
		);
		$fields_pdo = "`".implode('`,`', array_keys($_POST["equipo_directorio"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["equipo_directorio"][0])."'";
		$insert_equipos_historicos= "INSERT INTO equipos_historicos ($fields_pdo) VALUES ($values_pdo);";

		$insert_equipos_historicos=$conexion->query($insert_equipos_historicos);
		$num=$conexion->affected_rows;
		if(!$insert_equipos_historicos || $num=0){
			$success=false;
			echo "ERROR insert_equipos_historicos"; 
			var_dump($conexion->error);
			echo "<br>";echo "<br>";
			die;
		}

		//metemos en la base de datos de directorio.
		$equipo_directorio = array();
		$equipo_directorio['id_directorio'] = $id_directorio;
		$equipo_directorio['id_equipo'] = $id;
		$equipo_directorio['fechaR'] = $fechaH;
		$equipo_directorio['status'] = 1;
		$equipo_directorio['codigo_plataforma'] = $codigo_plataforma;

		$fields_pdo = "`".implode('`,`', array_keys($equipo_directorio))."`";
		$values_pdo = "'".implode("','", $equipo_directorio)."'";
		$insert_equipos_directorios= "INSERT INTO equipos_directorios ($fields_pdo) VALUES ($values_pdo);";

		$insert_equipos_directorios=$conexion->query($insert_equipos_directorios);
		$num=$conexion->affected_rows;
		if(!$insert_equipos_directorios || $num=0){
			$success=false;
			echo "ERROR insert_equipos_directorios"; 
			var_dump($conexion->error);
			echo "<br>";echo "<br>";
			die;
		}

		$id_equipo_directorio = $equipo_directorio['id_equipo_directorio'] = $conexion->insert_id;
		$log[] = array(
			'id' => $id_equipo_directorio,
			'tabla' => 'equipos_directorios',
			'tipo' => 'Insert',
		);
		$fields_pdo = "`".implode('`,`', array_keys($equipo_directorio))."`";
		$values_pdo = "'".implode("','", $equipo_directorio)."'";
		$equipos_directorios_historicos= "INSERT INTO equipos_directorios_historicos ($fields_pdo) VALUES ($values_pdo);";

		$equipos_directorios_historicos=$conexion->query($equipos_directorios_historicos);
		$num=$conexion->affected_rows;
		if(!$equipos_directorios_historicos || $num=0){
			$success=false;
			echo "ERROR equipos_directorios_historicos"; 
			var_dump($conexion->error);
			echo "<br>";echo "<br>";
			die;
		}
		foreach ($_POST['usuarios'] as $key => $value) {
			$value['id_equipo'] = $id;
			unset($value['id']);
			$value['fechaR'] = $fechaH;
			$value['status'] = 1;
			$value['codigo_plataforma'] = $codigo_plataforma;
			
			$fields_pdo = "`".implode('`,`', array_keys($value))."`";
			$values_pdo = "'".implode("','", $value)."'";
			$insert_equipo_usuario= "INSERT INTO equipos_usuarios ($fields_pdo) VALUES ($values_pdo);";
			$insert_equipo_usuario=$conexion->query($insert_equipo_usuario);
			$num=$conexion->affected_rows;
			if(!$insert_equipo_usuario || $num=0){
				$success=false;
				echo "ERROR insert_equipo_usuario"; 
				var_dump($conexion->error);
				echo "<br>";echo "<br>";
				die;
			}
			$usuarios_ids[] = $conexion->insert_id;
			$log[] = array(
				'id' => $conexion->insert_id,
				'tabla' => 'equipos_usuarios',
				'tipo' => 'Insert',
			);
			$value['id_equipo_usuario'] = $conexion->insert_id;
			$fields_pdo = "`".implode('`,`', array_keys($value))."`";
			$values_pdo = "'".implode("','", $value)."'";
			$insert_equipo_usuario= "INSERT INTO equipos_usuarios_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_equipo_usuario=$conexion->query($insert_equipo_usuario);
			$num=$conexion->affected_rows;
			if(!$insert_equipo_usuario || $num=0){
				$success=false;
				echo "ERROR insert_equipo_usuario_historico"; 
				var_dump($conexion->error);
				echo "<br>";echo "<br>";
				die;
			}
		}
		foreach ($_POST['equipo_sistemas_operativos'] as $key => $value) {
			$value['id_equipo'] = $id;
			unset($value['id_equipo_sistema_operativo_licencia']);
			$value['fechaR'] = $fechaH;
			$value['status'] = 1;
			$value['codigo_plataforma'] = $codigo_plataforma;
			
			$fields_pdo = "`".implode('`,`', array_keys($value))."`";
			$values_pdo = "'".implode("','", $value)."'";
			$insert_equipo_sistema_operativo_licencia= "INSERT INTO equipos_sistemas_operativos_licencias ($fields_pdo) VALUES ($values_pdo);";
			$insert_equipo_sistema_operativo_licencia=$conexion->query($insert_equipo_sistema_operativo_licencia);
			$num=$conexion->affected_rows;
			if(!$insert_equipo_sistema_operativo_licencia || $num=0){
				$success=false;
				echo "ERROR insert_equipo_sistema_operativo_licencia"; 
				var_dump($conexion->error);
				echo "<br>";echo "<br>";
				die;
			}
			$sistemas_operativos_ids[] = $conexion->insert_id;
			$log[] = array(
				'id' => $conexion->insert_id,
				'tabla' => 'equipos_sistemas_operativos_licencias',
				'tipo' => 'Insert',
			);
			$value['id_equipo_sistema_operativo_licencia'] = $conexion->insert_id;
			$fields_pdo = "`".implode('`,`', array_keys($value))."`";
			$values_pdo = "'".implode("','", $value)."'";
			$insert_equipo_sistema_operativo_licencia= "INSERT INTO equipos_sistemas_operativos_licencias_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_equipo_sistema_operativo_licencia=$conexion->query($insert_equipo_sistema_operativo_licencia);
			$num=$conexion->affected_rows;
			if(!$insert_equipo_sistema_operativo_licencia || $num=0){
				$success=false;
				echo "ERROR insert_equipo_sistema_operativo_licencia_historico"; 
				var_dump($conexion->error);
				echo "<br>";echo "<br>";
				die;
			}
		}
		foreach ($_POST['equipo_softwares'] as $key => $value) {
			$value['id_equipo'] = $id;
			unset($value['id_equipo_software_licencia']);
			$value['fechaR'] = $fechaH;
			$value['status'] = 1;
			$value['codigo_plataforma'] = $codigo_plataforma;
			
			$fields_pdo = "`".implode('`,`', array_keys($value))."`";
			$values_pdo = "'".implode("','", $value)."'";
			$insert_equipo_software_licencia= "INSERT INTO equipos_softwares_licencias ($fields_pdo) VALUES ($values_pdo);";
			$insert_equipo_software_licencia=$conexion->query($insert_equipo_software_licencia);
			$num=$conexion->affected_rows;
			if(!$insert_equipo_software_licencia || $num=0){
				$success=false;
				echo "ERROR insert_equipo_software_licencia"; 
				var_dump($conexion->error);
				echo "<br>";echo "<br>";
				die;
			}
			$softwares_ids[] = $conexion->insert_id;
			$log[] = array(
				'id' => $conexion->insert_id,
				'tabla' => 'equipos_softwares_licencias',
				'tipo' => 'Insert',
			);
			$value['id_equipo_software_licencia'] = $conexion->insert_id;
			$fields_pdo = "`".implode('`,`', array_keys($value))."`";
			$values_pdo = "'".implode("','", $value)."'";
			$insert_equipo_software_licencia= "INSERT INTO equipos_softwares_licencias_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_equipo_software_licencia=$conexion->query($insert_equipo_software_licencia);
			$num=$conexion->affected_rows;
			if(!$insert_equipo_software_licencia || $num=0){
				$success=false;
				echo "ERROR insert_equipo_software_licencia"; 
				var_dump($conexion->error);
				echo "<br>";echo "<br>";
				die;
			}
		}

		if($success){
			$log = logUsuarioArray($log,$fechaH,$_COOKIE["id_usuario"]);
			if($log==true){
				echo "SI";
				$conexion->commit();
				$conexion->close();
			}else{
				echo "NO2";
				$conexion->rollback();
				$conexion->close();
			}
		}else{
			echo "NO";
			$conexion->rollback();
			$conexion->close();
		}
	}