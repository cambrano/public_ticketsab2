<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/equipos.php";
	include __DIR__."/../functions/equipos_directorios.php";
	include __DIR__."/../functions/equipos_sistemas_operativos_licencias.php";
	include __DIR__."/../functions/equipos_softwares_licencias.php";
	include __DIR__."/../functions/equipos_usuarios.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad',"equipos",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["equipo"][0] as $keyPrincipal => $atributo) {
		$_POST["equipo"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$id_directorio = $_POST["equipo"][0]['id_directorio'];
	$id_equipo = $_POST["equipo"][0]['id'];
	unset($_POST["equipo"][0]['id_directorio']);

	$equipoClaveVerificacion=equipoClaveVerificacion($_POST["equipo"][0]["clave"],$_POST["equipo"][0]['id'],1);
	if($equipoClaveVerificacion){
		$claveF= clave("equipos");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["equipo"][0]["clave"] = $claveF["clave"];
		}
	}
	
	$success=true;
	$conexion->autocommit(FALSE);
	if( registrosCompara("equipos",$_POST["equipo"][0],1)){
		if(!empty($_POST)){ 
			$chk = true;
			$_POST["equipo"][0]["fechaR"]=$fechaH;
			$_POST["equipo"][0]["codigo_plataforma"]=$codigo_plataforma;
			$equipoDatos = equipoDatos($_POST["equipo"][0]["id"]);
			$_POST["equipo"][0]["identificador"] = $equipoDatos['identificador'];
			foreach($_POST["equipo"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			$update_equipos = "UPDATE equipos SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_equipos=$conexion->query($update_equipos);
			$num=$conexion->affected_rows;
			if(!$update_equipos || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_equipos"; 
				var_dump($conexion->error);
				echo "<br>";echo "<br>";
			}

			unset($_POST["equipo"][0]['id']); 
			$id_equipo=$_POST["equipo"][0]["id_equipo"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["equipo"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["equipo"][0])."'";
			$insert_equipos_historicos= "INSERT INTO equipos_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_equipos_historicos=$conexion->query($insert_equipos_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_equipos_historicos || $num=0){
				$success=false;
				echo "ERROR insert_equipos_historicos"; 
				var_dump($conexion->error);
				echo "<br>";echo "<br>";
			}
			$log[0] = array(
				'id' => $id_equipo,
				'tabla' => 'equipos',
				'tipo' => 'Update',
			);
		}
	}

	//! aqui agregamos los aditamentos de softwares y sistemas operativos
	
	foreach ($_POST["equipo_sistemas_operativos"] as $key => $value) {
		if($value['id']!=""){
			$ids_validador[]=$value['id'];
			if( registrosCompara("equipos_sistemas_operativos_licencias",$value,1)){
				$chk = true;
				//modificamos el sistema
				unset($valueSets);
				foreach ($value as $key => $valueT) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $valueT . "'";
					}else{
						$id=$valueT;
					}
				}
				$update_equipos_sistemas_operativos_licencias = "UPDATE equipos_sistemas_operativos_licencias SET ". join(",",$valueSets) . " WHERE id=".$id;
				$conexion->autocommit(FALSE);
				$update_equipos_sistemas_operativos_licencias=$conexion->query($update_equipos_sistemas_operativos_licencias);
				$num=$conexion->affected_rows;
				if(!$update_equipos_sistemas_operativos_licencias || $num=0){
					$success=false;
					echo "<br>";
					echo "ERROR update_equipos_sistemas_operativos_licencias"; 
					var_dump($conexion->error);
					echo "<br>";echo "<br>";
					die;
				}
	
				unset($value['id']); 
				$value["id_equipo_sistema_operativo_licencia"]=$id;
				$fields_pdo = "`".implode('`,`', array_keys($value))."`";
				$values_pdo = "'".implode("','", $value)."'";
				$insert_equipos_sistemas_operativos_licencias_historicos= "INSERT INTO equipos_sistemas_operativos_licencias_historicos ($fields_pdo) VALUES ($values_pdo);";
				$insert_equipos_sistemas_operativos_licencias_historicos=$conexion->query($insert_equipos_sistemas_operativos_licencias_historicos);
				$num=$conexion->affected_rows;
				if(!$insert_equipos_sistemas_operativos_licencias_historicos || $num=0){
					$success=false;
					echo "ERROR insert_equipos_sistemas_operativos_licencias_historicos"; 
					var_dump($conexion->error);
					echo "<br>";echo "<br>";
					die;
				}
				$log[] = array(
					'id' => $id,
					'tabla' => 'equipos_sistemas_operativos_licencias',
					'tipo' => 'Update',
				);
	
			}
		}else{
			//insertamos los nuevos
			$id_equipo = $_POST["equipo"][0]["id"];
			unset($value['id']);
			$chk = true;
			$value['id_equipo'] = $id_equipo;
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
			$log[] = array(
				'id' => $value['id_equipo_sistema_operativo_licencia'],
				'tabla' => 'equipos_sistemas_operativos_licencias',
				'tipo' => 'Insert',
			);
		}
	}
	//!checamos para eliminar
	$equipos_sistemas_operativos_licenciasDatos = equipos_sistemas_operativos_licenciasDatos('',$_POST["equipo"][0]['id']);
	foreach ($equipos_sistemas_operativos_licenciasDatos as $key => $value) {
		$id = $value['id'];
		$chk = true;
		// Verificar si el ID NO está en el array de validador
		if (!in_array($id, $ids_validador)) {
			//! Eliminamos el id
			$delete_equipos = "DELETE FROM equipos_sistemas_operativos_licencias  WHERE  id='{$id}' ";
			$delete_equipos=$conexion->query($delete_equipos);
			$num=$conexion->affected_rows;
			if(!$delete_equipos || $num=0){
				$success=false;
				echo "ERROR delete equipos_sistemas_operativos_licencias"; 
				echo "<br>";
				echo("Errorcode: " . mysqli_errno($conexion));
				echo "<br>";
				die;
			}
			$log[] = array(
				'id' => $id,
				'tabla' => 'equipos_sistemas_operativos_licencias',
				'tipo' => 'Delete',
			);
		}
	}
	unset($ids_validador);
	foreach ($_POST["equipo_softwares"] as $key => $value) {
		if($value['id']!=""){
			$ids_validador[]=$value['id'];
			if( registrosCompara("equipos_softwares_licencias",$value,1)){
				$chk = true;
				//modificamos el sistema
				unset($valueSets);
				foreach ($value as $key => $valueT) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $valueT . "'";
					}else{
						$id=$valueT;
					}
				}
				$update_equipos_softwares_licencias = "UPDATE equipos_softwares_licencias SET ". join(",",$valueSets) . " WHERE id=".$id;
				$conexion->autocommit(FALSE);
				$update_equipos_softwares_licencias=$conexion->query($update_equipos_softwares_licencias);
				$num=$conexion->affected_rows;
				if(!$update_equipos_softwares_licencias || $num=0){
					$success=false;
					echo "<br>";
					echo "ERROR update_equipos_softwares_licencias"; 
					var_dump($conexion->error);
					echo "<br>";echo "<br>";
				}
	
				unset($value['id']); 
				$value["id_equipo_software_licencia"]=$id;
				$fields_pdo = "`".implode('`,`', array_keys($value))."`";
				$values_pdo = "'".implode("','", $value)."'";
				$insert_equipos_softwares_licencias_historicos= "INSERT INTO equipos_softwares_licencias_historicos ($fields_pdo) VALUES ($values_pdo);";
				$insert_equipos_softwares_licencias_historicos=$conexion->query($insert_equipos_softwares_licencias_historicos);
				$num=$conexion->affected_rows;
				if(!$insert_equipos_softwares_licencias_historicos || $num=0){
					$success=false;
					echo "ERROR insert_equipos_softwares_licencias_historicos"; 
					var_dump($conexion->error);
					echo "<br>";echo "<br>";
				}
				$log[] = array(
					'id' => $id,
					'tabla' => 'equipos_softwares_licencias',
					'tipo' => 'Update',
				);
	
			}
		}else{
			//insertamos los nuevos
			unset($value['id']);
			$chk = true;
			$value['id_equipo'] = $id_equipo;
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
			$value['id_equipo_software_licencia'] = $conexion->insert_id;
			$fields_pdo = "`".implode('`,`', array_keys($value))."`";
			$values_pdo = "'".implode("','", $value)."'";
			$insert_equipo_software_licencia= "INSERT INTO equipos_softwares_licencias_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_equipo_software_licencia=$conexion->query($insert_equipo_software_licencia);
			$num=$conexion->affected_rows;
			if(!$insert_equipo_software_licencia || $num=0){
				$success=false;
				echo "ERROR insert_equipo_software_licencia_historico"; 
				var_dump($conexion->error);
				echo "<br>";echo "<br>";
				die;
			}
			$log[] = array(
				'id' => $value['id_equipo_software_licencia'],
				'tabla' => 'equipos_softwares_licencias',
				'tipo' => 'Insert',
			);
		}
	}
	//!checamos para eliminar
	$equipos_softwares_licenciasDatos = equipos_softwares_licenciasDatos('',$_POST["equipo"][0]['id']);
	foreach ($equipos_softwares_licenciasDatos as $key => $value) {
		$id = $value['id'];
		$chk = true;
		// Verificar si el ID NO está en el array de validador
		if (!in_array($id, $ids_validador)) {
			//! Eliminamos el id
			$delete_equipos = "DELETE FROM equipos_softwares_licencias  WHERE  id='{$id}' ";
			$delete_equipos=$conexion->query($delete_equipos);
			$num=$conexion->affected_rows;
			if(!$delete_equipos || $num=0){
				$success=false;
				echo "ERROR delete equipos_softwares_licencias"; 
				echo "<br>";
				echo("Errorcode: " . mysqli_errno($conexion));
				echo "<br>";
				die;
			}
			$log[] = array(
				'id' => $id,
				'tabla' => 'equipos_softwares_licencias',
				'tipo' => 'Delete',
			);
		}
	}

	//!checamos los usuarios
	unset($ids_validador);
	foreach ($_POST["usuarios"] as $key => $value) {
		if($value['id']!=""){
			$ids_validador[]=$value['id'];
			if( registrosCompara("equipos_usuarios",$value,1)){
				$chk = true;
				//modificamos el sistema
				unset($valueSets);
				foreach ($value as $key => $valueT) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $valueT . "'";
					}else{
						$id=$valueT;
					}
				}
				$update_equipos_usuarios = "UPDATE equipos_usuarios SET ". join(",",$valueSets) . " WHERE id=".$id;
				$conexion->autocommit(FALSE);
				$update_equipos_usuarios=$conexion->query($update_equipos_usuarios);
				$num=$conexion->affected_rows;
				if(!$update_equipos_usuarios || $num=0){
					$success=false;
					echo "<br>";
					echo "ERROR update_equipo_usuario"; 
					var_dump($conexion->error);
					echo "<br>";echo "<br>";
				}
	
				unset($value['id']); 
				$value["id_equipo_usuario"]=$id;
				$fields_pdo = "`".implode('`,`', array_keys($value))."`";
				$values_pdo = "'".implode("','", $value)."'";
				$insert_equipo_usuario_historico= "INSERT INTO equipos_usuarios_historicos ($fields_pdo) VALUES ($values_pdo);";
				$insert_equipo_usuario_historico=$conexion->query($insert_equipo_usuario_historico);
				$num=$conexion->affected_rows;
				if(!$insert_equipo_usuario_historico || $num=0){
					$success=false;
					echo "ERROR insert_equipo_usuario_historico"; 
					var_dump($conexion->error);
					echo "<br>";echo "<br>";
				}
				$log[] = array(
					'id' => $id,
					'tabla' => 'equipos_usuarios',
					'tipo' => 'Update',
				);
	
			}
		}else{
			//insertamos los nuevos
			unset($value['id']);
			$chk = true;
			$value['id_equipo'] = $id_equipo;
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
			$value['id_equipo_usuario'] = $conexion->insert_id;
			$fields_pdo = "`".implode('`,`', array_keys($value))."`";
			$values_pdo = "'".implode("','", $value)."'";
			$insert_equipo_usuario= "INSERT INTO equipos_usuarios_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_equipo_usuario=$conexion->query($insert_equipo_usuario);
			$num=$conexion->affected_rows;
			if(!$insert_equipo_usuario || $num=0){
				$success=false;
				echo "ERROR insert_equipo_usuario_historicos"; 
				var_dump($conexion->error);
				echo "<br>";echo "<br>";
				die;
			}
			$log[] = array(
				'id' => $value['id_equipo_usuario'],
				'tabla' => 'equipos_usuarios',
				'tipo' => 'Insert',
			);
		}
	}
	$equipos_usuariosDatos = equipos_usuariosDatos('',$_POST["equipo"][0]['id']);
	foreach ($equipos_usuariosDatos as $key => $value) {
		$id = $value['id'];
		$chk = true;
		// Verificar si el ID NO está en el array de validador
		if (!in_array($id, $ids_validador)) {
			//! Eliminamos el id
			$delete_equipos = "DELETE FROM equipos_usuarios  WHERE  id='{$id}' ";
			$delete_equipos=$conexion->query($delete_equipos);
			$num=$conexion->affected_rows;
			if(!$delete_equipos || $num=0){
				$success=false;
				echo "ERROR delete equipos_usuarios"; 
				echo "<br>";
				echo("Errorcode: " . mysqli_errno($conexion));
				echo "<br>";
				die;
			}
			$log[] = array(
				'id' => $id,
				'tabla' => 'equipos_usuarios',
				'tipo' => 'Delete',
			);
		}
	}
	if($success == true && $chk == true){
		$log= logUsuarioArray($log,$fechaH,$_COOKIE["id_usuario"]);
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
		echo "NO1";
		$conexion->rollback();
		$conexion->close();
	}