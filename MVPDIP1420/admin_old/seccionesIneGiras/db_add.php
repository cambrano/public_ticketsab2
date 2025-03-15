<?php
	@session_start();
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/secciones_ine_giras.php";
	include __DIR__."/../functions/claves_2.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_giras',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}



	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["seccion_ine_gira"][0] as $keyPrincipal => $atributo) {
			$_POST["seccion_ine_gira"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}

		$seccion_ine_giraClaveVerificacion=seccion_ine_giraClaveVerificacion($_POST["seccion_ine_gira"][0]['clave'],'',1);
		if($seccion_ine_giraClaveVerificacion){
			$claveF= clave2('secciones_ine_giras');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["seccion_ine_gira"][0]['clave'] = $claveF['clave'];
			}
		}

		$latitud = null;
		foreach ($_SESSION['puntos_line'] as $key => $value){ 
			if($value['status']==1 && empty($latitud) ){
					$latitud = true;
					$_POST["seccion_ine_gira"][0]['latitud'] = $value['latitud'];
					$_POST["seccion_ine_gira"][0]['longitud'] = $value['longitud'];
			}
		}

		$success=true;
		$_POST["seccion_ine_gira"][0]['fechaR']=$fechaH;
		//$_POST["seccion_ine_gira"][0]['fecha_hora']=$_POST["seccion_ine_gira"][0]['fecha']." ".$_POST["seccion_ine_gira"][0]['hora'];

		$_POST["seccion_ine_gira"][0]['codigo_plataforma']=$codigo_plataforma; 
		$fields_pdo = "`".implode('`,`', array_keys($_POST["seccion_ine_gira"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["seccion_ine_gira"][0])."'";
		$inset_secciones_ine_giras= "INSERT INTO secciones_ine_giras ($fields_pdo) VALUES ($values_pdo);";
		$conexion->autocommit(FALSE);
		$inset_secciones_ine_giras=$conexion->query($inset_secciones_ine_giras);
		$num=$conexion->affected_rows;
		if(!$inset_secciones_ine_giras || $num=0){
			$success=false;
			echo "ERROR inset_secciones_ine_giras"; 
			var_dump($conexion->error);
		}
		$id=$_POST["seccion_ine_gira"][0]['id_seccion_ine_gira']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST["seccion_ine_gira"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["seccion_ine_gira"][0])."'";
		$inset_secciones_ine_giras_historicos= "INSERT INTO secciones_ine_giras_historicos ($fields_pdo) VALUES ($values_pdo);";
		$inset_secciones_ine_giras_historicos=$conexion->query($inset_secciones_ine_giras_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_secciones_ine_giras_historicos || $num=0){
			$success=false;
			echo "ERROR inset_secciones_ine_giras_historicos"; 
			var_dump($conexion->error);
		}
		
		foreach ($_SESSION['puntos_line'] as $key => $value){ 
			if($value['status']==1){
				$value['id_seccion_ine_gira'] = $id;
				$value['id_seccion_ine'] = $_POST["seccion_ine_gira"][0]['id_seccion_ine'];
				$value['id_municipio'] = $_POST["seccion_ine_gira"][0]['id_municipio'];
				$value['id_localidad'] = $_POST["seccion_ine_gira"][0]['id_localidad'];
				$value['fechaR']  = $fechaH;
				$value['codigo_plataforma']=$codigo_plataforma; 
				unset($value['status']);
				$fields_pdo = "`".implode('`,`', array_keys($value))."`";
				$values_pdo = "'".implode("','", $value)."'";
				$inset_secciones_ine_giras_puntos= "INSERT INTO secciones_ine_giras_puntos ($fields_pdo) VALUES ($values_pdo);";
				$inset_secciones_ine_giras_puntos=$conexion->query($inset_secciones_ine_giras_puntos);
				$num=$conexion->affected_rows;
				if(!$inset_secciones_ine_giras_puntos || $num=0){
					$success=false;
					echo "ERROR inset_secciones_ine_giras_puntos"; 
					var_dump($conexion->error);
				}
				$value['id_seccion_ine_gira_punto']=$conexion->insert_id;
				$fields_pdo = "`".implode('`,`', array_keys($value))."`";
				$values_pdo = "'".implode("','", $value)."'";
				$inset_secciones_ine_giras_puntos_historicos= "INSERT INTO secciones_ine_giras_puntos_historicos ($fields_pdo) VALUES ($values_pdo);";
				$inset_secciones_ine_giras_puntos_historicos=$conexion->query($inset_secciones_ine_giras_puntos_historicos);
				$num=$conexion->affected_rows;
				if(!$inset_secciones_ine_giras_puntos_historicos || $num=0){
					$success=false;
					echo "ERROR inset_secciones_ine_giras_puntos_historicos"; 
					var_dump($conexion->error);
				}
			}
		}



		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_giras',$id,'Insert','',$fechaH);
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
