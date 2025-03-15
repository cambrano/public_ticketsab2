<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/supervisores.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','supervisores',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["supervisor"][0]);
	if(!empty($_POST)){

		$_POST['supervisor'][0]['nombre_completo'] = $_POST['supervisor'][0]['nombre'].' '.$_POST['supervisor'][0]['apellido_paterno'].' '.$_POST['supervisor'][0]['apellido_materno'];

		//metemos los valores para que se no tengamos error
		foreach($_POST["supervisor"][0] as $keyPrincipal => $atributo) {
			$_POST["supervisor"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$supervisorClaveVerificacion=supervisorClaveVerificacion($_POST["supervisor"][0]['clave'],'',1);
		if($supervisorClaveVerificacion){
			$claveF= clave('supervisores');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["supervisor"][0]['clave'] = $claveF['clave'];
			}
		}

		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["supervisor"][0]['fechaR']=$fechaH; 
		$_POST["supervisor"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['supervisor'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['supervisor'][0])."'";
		$inset_supervisores= "INSERT INTO supervisores ($fields_pdo) VALUES ($values_pdo);";

		$inset_supervisores=$conexion->query($inset_supervisores);
		$num=$conexion->affected_rows;
		if(!$inset_supervisores || $num=0){
			$success=false;
			echo "ERROR inset_supervisores"; 
			var_dump($conexion->error);
		}

		$id=$_POST['supervisor'][0]['id_supervisor']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['supervisor'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['supervisor'][0])."'";
		$inset_supervisores_historicos= "INSERT INTO supervisores_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_supervisores_historicos=$conexion->query($inset_supervisores_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_supervisores_historicos || $num=0){
			$success=false;
			echo "ERROR inset_supervisores_historicos"; 
			var_dump($conexion->error);
		}
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'supervisores',$id,'Insert','',$fechaH);
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