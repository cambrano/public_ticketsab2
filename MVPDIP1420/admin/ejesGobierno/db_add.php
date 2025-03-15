<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/ejes_gobierno.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','ejes_gobierno',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["eje_gobierno"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["eje_gobierno"][0] as $keyPrincipal => $atributo) {
			$_POST["eje_gobierno"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}

		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["eje_gobierno"][0]['fechaR']=$fechaH; 
		$_POST["eje_gobierno"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['eje_gobierno'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['eje_gobierno'][0])."'";
		$inset_ejes_gobierno= "INSERT INTO ejes_gobierno ($fields_pdo) VALUES ($values_pdo);";

		$inset_ejes_gobierno=$conexion->query($inset_ejes_gobierno);
		$num=$conexion->affected_rows;
		if(!$inset_ejes_gobierno || $num=0){
			$success=false;
			echo "ERROR inset_ejes_gobierno"; 
			var_dump($conexion->error);
		}

		$id=$_POST['eje_gobierno'][0]['id_eje_gobierno']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['eje_gobierno'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['eje_gobierno'][0])."'";
		$inset_ejes_gobierno_historicos= "INSERT INTO ejes_gobierno_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_ejes_gobierno_historicos=$conexion->query($inset_ejes_gobierno_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_ejes_gobierno_historicos || $num=0){
			$success=false;
			echo "ERROR inset_ejes_gobierno_historicos"; 
			var_dump($conexion->error);
		}
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'ejes_gobierno',$id,'Insert','',$fechaH);
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