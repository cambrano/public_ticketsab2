<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/directorios.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','directorios',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["directorio"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["directorio"][0] as $keyPrincipal => $atributo) {
			$_POST["directorio"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$directorioClaveVerificacion=directorioClaveVerificacion($_POST["directorio"][0]['clave'],'',1);
		if($directorioClaveVerificacion){
			$claveF= clave('directorios');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["directorio"][0]['clave'] = $claveF['clave'];
			}
		}

		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["directorio"][0]['fechaR']=$fechaH; 
		$_POST["directorio"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['directorio'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['directorio'][0])."'";
		$inset_directorios= "INSERT INTO directorios ($fields_pdo) VALUES ($values_pdo);";

		$inset_directorios=$conexion->query($inset_directorios);
		$num=$conexion->affected_rows;
		if(!$inset_directorios || $num=0){
			$success=false;
			echo "ERROR inset_directorios"; 
			var_dump($conexion->error);
		}

		$id=$_POST['directorio'][0]['id_directorio']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['directorio'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['directorio'][0])."'";
		$inset_directorios_historicos= "INSERT INTO directorios_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_directorios_historicos=$conexion->query($inset_directorios_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_directorios_historicos || $num=0){
			$success=false;
			echo "ERROR inset_directorios_historicos"; 
			var_dump($conexion->error);
		}
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'directorios',$id,'Insert','',$fechaH);
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