<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/usuarios.php";
	include __DIR__."/../functions/switch_operaciones.php";
	include __DIR__."/../functions/gps_distancias.php";
	$switch_operacionesPermisos = switch_operacionesPermisos();
	if($switch_operacionesPermisos['registro']!=true){
		echo "No tiene permiso.";
		die;
	}


	//metemos los valores para que se no tengamos error
	foreach($_POST["seccion_ine_ciudadano"][0] as $keyPrincipal => $atributo) {
		$_POST["seccion_ine_ciudadano"][0][$keyPrincipal]= rtrim(ltrim(mysqli_real_escape_string($conexion,$atributo)));
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["usuarios"][0] as $keyPrincipal => $atributo) {
		$_POST["usuarios"][0][$keyPrincipal]= rtrim(ltrim(mysqli_real_escape_string($conexion,$atributo)));
	}

	$usuarioValidadorSistema=usuarioValidadorSistema($_POST["usuarios"][0]['usuario'],3,'',$_POST["seccion_ine_ciudadano"][0]['id']);
	if($usuarioValidadorSistema){
		echo "Favor de Ingresar un Usuario Válido o que no exista en sistema.";
		die;
	}

	$usuarioDatos = usuarioDatos($_COOKIE["id_usuario"]);
	$id_seccion_ine_ciudadano_compartido = $usuarioDatos['id_seccion_ine_ciudadano'];
	//$_POST["seccion_ine_ciudadano"][0]['id_seccion_ine_ciudadano_compartido'] = $id_seccion_ine_ciudadano_compartido;


	if( registrosCompara("secciones_ine_ciudadanos",$_POST['seccion_ine_ciudadano'][0],1) || registrosCompara("usuarios",$_POST['usuarios'][0],1)){


		if(!empty($_POST)){
			$seccion_ine_ciudadanoDatos=seccion_ine_ciudadanoDatos($_POST['seccion_ine_ciudadano'][0]['id']);

			$seccion_ineDatos=seccion_ineDatos($_POST['seccion_ine_ciudadano'][0]['id_seccion_ine']);
			$latitud = $_POST['seccion_ine_ciudadano'][0]['latitud'];
			$longitud = $_POST['seccion_ine_ciudadano'][0]['longitud'];
			$_POST["seccion_ine_ciudadano"][0]['distancia_m'] = distanceCalculation($latitud, $longitud, $seccion_ineDatos['latitud'], $seccion_ineDatos['longitud'],'m',3);
			$_POST["seccion_ine_ciudadano"][0]['distancia_km'] = distanceCalculation($latitud, $longitud, $seccion_ineDatos['latitud'], $seccion_ineDatos['longitud'],'km',3);

			$_POST["seccion_ine_ciudadano"][0]['id_distrito_local'] = $seccion_ineDatos['id_distrito_local'];
			$_POST["seccion_ine_ciudadano"][0]['id_distrito_federal'] = $seccion_ineDatos['id_distrito_federal'];
			$_POST["seccion_ine_ciudadano"][0]['id_cuartel'] = $seccion_ineDatos['id_cuartel'];

			//$_POST['registro']=$fechaH;
			$_POST["seccion_ine_ciudadano"][0]['fechaR']=$fechaH;
			$_POST["seccion_ine_ciudadano"][0]['fechaU']=$fechaH;
			$_POST["seccion_ine_ciudadano"][0]['status']=1;

			$_POST["seccion_ine_ciudadano"][0]['codigo_plataforma']=$codigo_plataforma;
			$_POST["seccion_ine_ciudadano"][0]["codigo_seccion_ine_ciudadano"]=$seccion_ine_ciudadanoDatos['codigo_seccion_ine_ciudadano'];
			$_POST["seccion_ine_ciudadano"][0]["referencia_importacion"]=$seccion_ine_ciudadanoDatos['referencia_importacion'];
			$_POST["seccion_ine_ciudadano"][0]['status']= $seccion_ine_ciudadanoDatos['status'];
			$_POST["seccion_ine_ciudadano"][0]['folio'] = $seccion_ine_ciudadanoDatos['folio'];

			$diff = (date('Y') - date('Y',strtotime($_POST["seccion_ine_ciudadano"][0]['fecha_nacimiento'])));
			if($diff==""){
				$diff=0;
			}
			$_POST["seccion_ine_ciudadano"][0]['edad']=$diff; 
			if($_POST["seccion_ine_ciudadano"][0]['id_seccion_ine_ciudadano_compartido']==""){
				if($seccion_ine_ciudadanoDatos['id_seccion_ine_ciudadano_compartido']!=""){
					$_POST["seccion_ine_ciudadano"][0]['id_seccion_ine_ciudadano_compartido']=NULL;
					$conexion->query("SET FOREIGN_KEY_CHECKS=0;");
					$fkck=true;
				}else{
					unset($_POST["seccion_ine_ciudadano"][0]['id_seccion_ine_ciudadano_compartido']);
				}
			}

			$_POST['seccion_ine_ciudadano'][0]['latitud_r'] = $seccion_ine_ciudadanoDatos['latitud_r'];
			$_POST['seccion_ine_ciudadano'][0]['longitud_r'] = $seccion_ine_ciudadanoDatos['longitud_r'];
			$_POST['seccion_ine_ciudadano'][0]['medio_registro'] = $seccion_ine_ciudadanoDatos['medio_registro'];
			if($_POST['seccion_ine_ciudadano'][0]['latitud_r']!=""){
				$latitud_r = $_POST['seccion_ine_ciudadano'][0]['latitud_r'];
				$longitud_r = $_POST['seccion_ine_ciudadano'][0]['longitud_r'];
				$_POST["seccion_ine_ciudadano"][0]['distancia_m_r'] = distanceCalculation($latitud, $longitud, $latitud_r, $longitud_r,'m',3);
				$_POST["seccion_ine_ciudadano"][0]['distancia_km_r'] = distanceCalculation($latitud, $longitud, $latitud_r, $longitud_r,'km',3);
				
				if($_POST['seccion_ine_ciudadano'][0]['medio_registro']==1){
					if($_POST["seccion_ine_ciudadano"][0]['distancia_m_r'] > 100){
						$_POST["seccion_ine_ciudadano"][0]['distancia_alert'] = 1;
					}else{
						$_POST["seccion_ine_ciudadano"][0]['distancia_alert'] = 0;
					}
				}else{
					$_POST["seccion_ine_ciudadano"][0]['distancia_alert'] = 0;
				}
			}else{
				$_POST["seccion_ine_ciudadano"][0]['distancia_alert'] = 0;
			}


			$success=true;
			foreach($_POST['seccion_ine_ciudadano'] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						if($value == NULL){
							$valueSets[] = $key . " = NULL ";
						}else{
							$valueSets[] = $key . " = '" . $value . "'";
						}
					}else{
						$id=$value;
					}
				}
			}

			$update_secciones_ine_ciudadanos = "UPDATE secciones_ine_ciudadanos SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_secciones_ine_ciudadanos=$conexion->query($update_secciones_ine_ciudadanos);
			$num=$conexion->affected_rows;
			if(!$update_secciones_ine_ciudadanos || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_secciones_ine_ciudadanos"; 
				var_dump($conexion->error);
			}

			unset($_POST["seccion_ine_ciudadano"][0]['id']); 
			$id_seccion_ine_ciudadano=$_POST['seccion_ine_ciudadano'][0]['id_seccion_ine_ciudadano']=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["seccion_ine_ciudadano"][0]))."`";
			$values_pdo = "'".implode("','", $_POST['seccion_ine_ciudadano'][0])."'";
			$inset_secciones_ine_ciudadanos_historicos= "INSERT INTO secciones_ine_ciudadanos_historicos ($fields_pdo) VALUES ($values_pdo);";
			$inset_secciones_ine_ciudadanos_historicos=$conexion->query($inset_secciones_ine_ciudadanos_historicos);
			$num=$conexion->affected_rows;
			if(!$inset_secciones_ine_ciudadanos_historicos || $num=0){
				$success=false;
				echo "ERROR inset_secciones_ine_ciudadanos_historicos"; 
				var_dump($conexion->error);
			}
			if($fkck){
				$conexion->query("SET FOREIGN_KEY_CHECKS=1;");
			}

			//usuarios
			unset($valueSets);
			$valueSets=array();
			$_POST['usuarios'][0]['codigo_plataforma']=$codigo_plataforma;
			$_POST['usuarios'][0]['id_perfil_usuario']=4;
			$_POST['usuarios'][0]['tabla'] = 'secciones_ine_ciudadanos';
			$_POST['usuarios'][0]['referencia_importacion'] = $seccion_ine_ciudadanoDatos['referencia_importacion'];
			foreach($_POST['usuarios'] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			$update_usuarios = "UPDATE usuarios SET ". join(",",$valueSets) . " WHERE id=".$id;
			$update_usuarios=$conexion->query($update_usuarios);
			$num=$conexion->affected_rows;
			if(!$update_usuarios || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_usuarios"; 
				var_dump($conexion->error);
			}
			unset($_POST['usuarios'][0]['id']);
			$_POST['usuarios'][0]['id_seccion_ine_ciudadano']=$id_seccion_ine_ciudadano;
			$_POST['usuarios'][0]['id_usuario']=$id_usuario=$id;
			$_POST['usuarios'][0]['fechaR']=$fechaH;
			$fields_pdo = "`".implode('`,`', array_keys($_POST['usuarios'][0]))."`";
			$values_pdo = "'".implode("','", $_POST['usuarios'][0])."'";
			$inset_usuarios_historicos= "INSERT INTO usuarios_historicos ($fields_pdo) VALUES ($values_pdo);";
			$conexion->autocommit(FALSE);

			$inset_usuarios_historicos=$conexion->query($inset_usuarios_historicos);
			$num=$conexion->affected_rows;
			if(!$inset_usuarios_historicos || $num=0){
				$success=false;
				echo "ERROR inset_usuarios_historicos"; 
				var_dump($conexion->error);
			}


			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_ciudadanos',$id_seccion_ine_ciudadano,'Update','',$fechaH);
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
