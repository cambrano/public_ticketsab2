<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/cuarteles.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','cuarteles',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["cuartel"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["cuartel"][0] as $keyPrincipal => $atributo) {
			$_POST["cuartel"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$cuartelClaveVerificacion=cuartelClaveVerificacion($_POST["cuartel"][0]['clave'],'',1);
		if($cuartelClaveVerificacion){
			$claveF= clave('cuarteles');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["cuartel"][0]['clave'] = $claveF['clave'];
			}
		}

		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["cuartel"][0]['fechaR']=$fechaH; 
		$_POST["cuartel"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['cuartel'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['cuartel'][0])."'";
		$inset_cuarteles= "INSERT INTO cuarteles ($fields_pdo) VALUES ($values_pdo);";

		$inset_cuarteles=$conexion->query($inset_cuarteles);
		$num=$conexion->affected_rows;
		if(!$inset_cuarteles || $num=0){
			$success=false;
			echo "ERROR inset_cuarteles"; 
			var_dump($conexion->error);
		}

		$id=$_POST['cuartel'][0]['id_cuartel']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['cuartel'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['cuartel'][0])."'";
		$inset_cuarteles_historicos= "INSERT INTO cuarteles_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_cuarteles_historicos=$conexion->query($inset_cuarteles_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_cuarteles_historicos || $num=0){
			$success=false;
			echo "ERROR inset_cuarteles_historicos"; 
			var_dump($conexion->error);
		}
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'cuarteles',$id,'Insert','',$fechaH);
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