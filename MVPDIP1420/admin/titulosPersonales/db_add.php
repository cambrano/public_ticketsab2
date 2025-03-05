<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/titulos_personales.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','titulos_personales',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["titulo_personal"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["titulo_personal"][0] as $keyPrincipal => $atributo) {
			$_POST["titulo_personal"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$titulo_personalClaveVerificacion=titulo_personalClaveVerificacion($_POST["titulo_personal"][0]['clave'],'',1);
		if($titulo_personalClaveVerificacion){
			$claveF= clave('titulos_personales');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["titulo_personal"][0]['clave'] = $claveF['clave'];
			}
		}

		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["titulo_personal"][0]['fechaR']=$fechaH; 
		$_POST["titulo_personal"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['titulo_personal'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['titulo_personal'][0])."'";
		$inset_titulos_personales= "INSERT INTO titulos_personales ($fields_pdo) VALUES ($values_pdo);";

		$inset_titulos_personales=$conexion->query($inset_titulos_personales);
		$num=$conexion->affected_rows;
		if(!$inset_titulos_personales || $num=0){
			$success=false;
			echo "ERROR inset_titulos_personales"; 
			var_dump($conexion->error);
		}

		$id=$_POST['titulo_personal'][0]['id_titulo_personal']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['titulo_personal'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['titulo_personal'][0])."'";
		$inset_titulos_personales_historicos= "INSERT INTO titulos_personales_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_titulos_personales_historicos=$conexion->query($inset_titulos_personales_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_titulos_personales_historicos || $num=0){
			$success=false;
			echo "ERROR inset_titulos_personales_historicos"; 
			var_dump($conexion->error);
		}
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'titulos_personales',$id,'Insert','',$fechaH);
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