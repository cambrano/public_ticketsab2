<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/dependencias.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','dependencias',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["dependencia"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["dependencia"][0] as $keyPrincipal => $atributo) {
			$_POST["dependencia"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$dependenciaClaveVerificacion=dependenciaClaveVerificacion($_POST["dependencia"][0]['clave'],'',1);
		if($dependenciaClaveVerificacion){
			$claveF= clave('dependencias');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["dependencia"][0]['clave'] = $claveF['clave'];
			}
		}

		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["dependencia"][0]['fechaR']=$fechaH; 
		$_POST["dependencia"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['dependencia'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['dependencia'][0])."'";
		$inset_dependencias= "INSERT INTO dependencias ($fields_pdo) VALUES ($values_pdo);";

		$inset_dependencias=$conexion->query($inset_dependencias);
		$num=$conexion->affected_rows;
		if(!$inset_dependencias || $num=0){
			$success=false;
			echo "ERROR inset_dependencias"; 
			var_dump($conexion->error);
		}

		$id=$_POST['dependencia'][0]['id_dependencia']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['dependencia'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['dependencia'][0])."'";
		$inset_dependencias_historicos= "INSERT INTO dependencias_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_dependencias_historicos=$conexion->query($inset_dependencias_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_dependencias_historicos || $num=0){
			$success=false;
			echo "ERROR inset_dependencias_historicos"; 
			var_dump($conexion->error);
		}
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'dependencias',$id,'Insert','',$fechaH);
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