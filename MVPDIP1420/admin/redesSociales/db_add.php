<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/redes_sociales.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('perfiles','redes_sociales',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["red_social"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["red_social"][0] as $keyPrincipal => $atributo) {
			$_POST["red_social"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}

		$red_socialClaveVerificacion=red_socialClaveVerificacion($_POST["red_social"][0]['clave'],'',1);
		if($red_socialClaveVerificacion){
			$claveF= clave('redes_sociales');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["red_social"][0]['clave'] = $claveF['clave'];
			}
		}


		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["red_social"][0]['fechaR']=$fechaH; 
		$_POST["red_social"][0]['status']=1; 
		$_POST["red_social"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['red_social'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['red_social'][0])."'";
		$inset_redes_sociales= "INSERT INTO redes_sociales ($fields_pdo) VALUES ($values_pdo);";

		$inset_redes_sociales=$conexion->query($inset_redes_sociales);
		$num=$conexion->affected_rows;
		if(!$inset_redes_sociales || $num=0){
			$success=false;
			echo "ERROR inset_redes_sociales"; 
			var_dump($conexion->error);
		}

		$id=$_POST['red_social'][0]['id_red_social']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['red_social'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['red_social'][0])."'";
		$inset_redes_sociales_historicos= "INSERT INTO redes_sociales_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_redes_sociales_historicos=$conexion->query($inset_redes_sociales_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_redes_sociales_historicos || $num=0){
			$success=false;
			echo "ERROR inset_redes_sociales_historicos"; 
			var_dump($conexion->error);
		}
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'redes_sociales',$id,'Insert','',$fechaH);
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