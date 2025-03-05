<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/softwares.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','softwares',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["software"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["software"][0] as $keyPrincipal => $atributo) {
			$_POST["software"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$softwareClaveVerificacion=softwareClaveVerificacion($_POST["software"][0]['clave'],'',1);
		if($softwareClaveVerificacion){
			$claveF= clave('softwares');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["software"][0]['clave'] = $claveF['clave'];
			}
		}

		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["software"][0]['fechaR']=$fechaH; 
		$_POST["software"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['software'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['software'][0])."'";
		$inset_softwares= "INSERT INTO softwares ($fields_pdo) VALUES ($values_pdo);";

		$inset_softwares=$conexion->query($inset_softwares);
		$num=$conexion->affected_rows;
		if(!$inset_softwares || $num=0){
			$success=false;
			echo "ERROR inset_softwares"; 
			var_dump($conexion->error);
		}

		$id=$_POST['software'][0]['id_software']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['software'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['software'][0])."'";
		$inset_softwares_historicos= "INSERT INTO softwares_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_softwares_historicos=$conexion->query($inset_softwares_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_softwares_historicos || $num=0){
			$success=false;
			echo "ERROR inset_softwares_historicos"; 
			var_dump($conexion->error);
		}
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'softwares',$id,'Insert','',$fechaH);
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