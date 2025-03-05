<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/ubicaciones.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','ubicaciones',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["ubicacion"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["ubicacion"][0] as $keyPrincipal => $atributo) {
			$_POST["ubicacion"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$ubicacionClaveVerificacion=ubicacionClaveVerificacion($_POST["ubicacion"][0]['clave'],'',1);
		if($ubicacionClaveVerificacion){
			$claveF= clave('ubicaciones');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["ubicacion"][0]['clave'] = $claveF['clave'];
			}
		}

		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["ubicacion"][0]['fechaR']=$fechaH; 
		$_POST["ubicacion"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['ubicacion'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['ubicacion'][0])."'";
		$inset_ubicaciones= "INSERT INTO ubicaciones ($fields_pdo) VALUES ($values_pdo);";

		$inset_ubicaciones=$conexion->query($inset_ubicaciones);
		$num=$conexion->affected_rows;
		if(!$inset_ubicaciones || $num=0){
			$success=false;
			echo "ERROR inset_ubicaciones"; 
			var_dump($conexion->error);
		}

		$id=$_POST['ubicacion'][0]['id_ubicacion']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['ubicacion'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['ubicacion'][0])."'";
		$inset_ubicaciones_historicos= "INSERT INTO ubicaciones_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_ubicaciones_historicos=$conexion->query($inset_ubicaciones_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_ubicaciones_historicos || $num=0){
			$success=false;
			echo "ERROR inset_ubicaciones_historicos"; 
			var_dump($conexion->error);
		}
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'ubicaciones',$id,'Insert','',$fechaH);
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