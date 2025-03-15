<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/configuracion.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_avance_semaforo',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	

	//metemos los valores para que se no tengamos error
	foreach($_POST["secciones_ine_ciudadanos_avance_semaforo"][0] as $keyPrincipal => $atributo) {
		$_POST["secciones_ine_ciudadanos_avance_semaforo"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	$valores = $_POST["secciones_ine_ciudadanos_avance_semaforo"][0];
	unset($_POST["secciones_ine_ciudadanos_avance_semaforo"][0]);

	$_POST["secciones_ine_ciudadanos_avance_semaforo"][0]['verde_rango_inicial'] = $valores['verde_rango_inicial_unidad'].'.'.$valores['verde_rango_inicial_decimal'];
	$_POST["secciones_ine_ciudadanos_avance_semaforo"][0]['verde_rango_final'] = $valores['verde_rango_final_unidad'].'.'.$valores['verde_rango_final_decimal'];


	$_POST["secciones_ine_ciudadanos_avance_semaforo"][0]['amarillo_rango_inicial'] = $valores['amarillo_rango_inicial_unidad'].'.'.$valores['amarillo_rango_inicial_decimal'];
	$_POST["secciones_ine_ciudadanos_avance_semaforo"][0]['amarillo_rango_final'] = $valores['amarillo_rango_final_unidad'].'.'.$valores['amarillo_rango_final_decimal'];


	$_POST["secciones_ine_ciudadanos_avance_semaforo"][0]['rojo_rango_inicial'] = $valores['rojo_rango_inicial_unidad'].'.'.$valores['rojo_rango_inicial_decimal'];
	$_POST["secciones_ine_ciudadanos_avance_semaforo"][0]['rojo_rango_final'] = $valores['rojo_rango_final_unidad'].'.'.$valores['rojo_rango_final_decimal'];

	$_POST["secciones_ine_ciudadanos_avance_semaforo"][0]['status'] = $valores['status'];

	$sql ="SELECT * FROM secciones_ine_ciudadanos_avance_semaforo WHERE 1 = 1 ";
	$result = $conexion->query($sql);
	$row=$result->fetch_assoc();
	$id=$row['id'];
	if(!empty($id)){ 
		$_POST["secciones_ine_ciudadanos_avance_semaforo"][0]['id']=$id;
		$registroCompara= registrosCompara("secciones_ine_ciudadanos_avance_semaforo",$_POST["secciones_ine_ciudadanos_avance_semaforo"][0],1);
		unset($_POST["secciones_ine_ciudadanos_avance_semaforo"][0]['id']);
	}else{
		$registroCompara=true;
	}

	
	
	if($registroCompara){
		if(!empty($_POST["secciones_ine_ciudadanos_avance_semaforo"][0])){
			$_POST["secciones_ine_ciudadanos_avance_semaforo"][0]['codigo_plataforma']=$codigo_plataforma;
			//checamos si tiene un registro si no es update
			$sql ="SELECT * FROM secciones_ine_ciudadanos_avance_semaforo WHERE 1 = 1 ";
			$result = $conexion->query($sql);
			$row=$result->fetch_assoc();
			$id=$row['id'];
			$success=true;
			$_POST["secciones_ine_ciudadanos_avance_semaforo"][0]['fechaR']=$fechaH;
			if(empty($id)){ 
				//agrega
				$tipo="Insert";
				$fields_pdo = "`".implode('`,`', array_keys($_POST["secciones_ine_ciudadanos_avance_semaforo"][0]))."`";
				$values_pdo = "'".implode("','", $_POST["secciones_ine_ciudadanos_avance_semaforo"][0])."'";
				$inset_secciones_ine_ciudadanos_avance_semaforo= "INSERT INTO secciones_ine_ciudadanos_avance_semaforo ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$inset_secciones_ine_ciudadanos_avance_semaforo=$conexion->query($inset_secciones_ine_ciudadanos_avance_semaforo);
				$num=$conexion->affected_rows;
				if(!$inset_secciones_ine_ciudadanos_avance_semaforo || $num=0){
					$success=false;
					echo "ERROR inset_secciones_ine_ciudadanos_avance_semaforo"; 
					var_dump($conexion->error);
				}
				$id=$_POST["secciones_ine_ciudadanos_avance_semaforo"][0]['id_seccion_ine_ciudadano_avance_semaforo']=$conexion->insert_id;
				$fields_pdo = "`".implode('`,`', array_keys($_POST["secciones_ine_ciudadanos_avance_semaforo"][0]))."`";
				$values_pdo = "'".implode("','", $_POST["secciones_ine_ciudadanos_avance_semaforo"][0])."'";
				$inset_secciones_ine_ciudadanos_avance_semaforo_historicos= "INSERT INTO secciones_ine_ciudadanos_avance_semaforo_historicos ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$success=$inset_secciones_ine_ciudadanos_avance_semaforo_historicos=$conexion->query($inset_secciones_ine_ciudadanos_avance_semaforo_historicos);
				$num=$conexion->affected_rows;
				if(!$inset_secciones_ine_ciudadanos_avance_semaforo_historicos || $num=0){
					$success=false;
					echo "ERROR inset_secciones_ine_ciudadanos_avance_semaforo_historicos"; 
					var_dump($conexion->error);
				}
			}else{
				
				//edita
				$tipo="Update";
				$_POST["secciones_ine_ciudadanos_avance_semaforo"][0]['fechaR']=$fechaH;
				foreach($_POST["secciones_ine_ciudadanos_avance_semaforo"][0] as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}


				$conexion->autocommit(FALSE);
				$update_secciones_ine_ciudadanos_avance_semaforo = "UPDATE secciones_ine_ciudadanos_avance_semaforo SET ". join(",",$valueSets) . " WHERE id=".$id;
				$update_secciones_ine_ciudadanos_avance_semaforo=$conexion->query($update_secciones_ine_ciudadanos_avance_semaforo);
				$num=$conexion->affected_rows;
				if(!$update_secciones_ine_ciudadanos_avance_semaforo || $num=0){
					$success=false;
					echo "ERROR update_secciones_ine_ciudadanos_avance_semaforo"; 
					var_dump($conexion->error);
				}

				$_POST["secciones_ine_ciudadanos_avance_semaforo"][0]['id_seccion_ine_ciudadano_avance_semaforo']=$id;
				unset($_POST["secciones_ine_ciudadanos_avance_semaforo"][0]['id']);
				$fields_pdo = "`".implode('`,`', array_keys($_POST["secciones_ine_ciudadanos_avance_semaforo"][0]))."`";
				$values_pdo = "'".implode("','", $_POST["secciones_ine_ciudadanos_avance_semaforo"][0])."'";
				$inset_secciones_ine_ciudadanos_avance_semaforo_historicos= "INSERT INTO secciones_ine_ciudadanos_avance_semaforo_historicos ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$success=$inset_secciones_ine_ciudadanos_avance_semaforo_historicos=$conexion->query($inset_secciones_ine_ciudadanos_avance_semaforo_historicos);
				$num=$conexion->affected_rows;
				if(!$inset_secciones_ine_ciudadanos_avance_semaforo_historicos || $num=0){
					$success=false;
					echo "ERROR inset_secciones_ine_ciudadanos_avance_semaforo_historicos"; 
					var_dump($conexion->error);
				}
			}

			if($success){
				
				$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_ciudadanos_avance_semaforo',$id,$tipo,'',$fechaH);
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

	}else{
		echo "SINCAMBIOS";
	}