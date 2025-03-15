<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/encuestas.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','encuestas',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["encuesta"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["encuesta"][0] as $keyPrincipal => $atributo) {
			$_POST["encuesta"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$encuestaClaveVerificacion=encuestaClaveVerificacion($_POST["encuesta"][0]['clave'],'',1);
		if($encuestaClaveVerificacion){
			$_POST["encuesta"][0]['clave'] = $cod16M;
		}

		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["encuesta"][0]['fechaR']=$fechaH; 
		//$_POST["encuesta"][0]['status']=1; 
		$_POST["encuesta"][0]['codigo_plataforma']=$codigo_plataforma;

		$_POST["encuesta"][0]['fecha']=$fechaSF; 
		$_POST["encuesta"][0]['hora']=$fechaSH; 
		$_POST["encuesta"][0]['fecha_hora']=$fechaH; 


		$fields_pdo = "`".implode('`,`', array_keys($_POST['encuesta'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['encuesta'][0])."'";
		$inset_encuestas= "INSERT INTO encuestas ($fields_pdo) VALUES ($values_pdo);";

		$inset_encuestas=$conexion->query($inset_encuestas);
		$num=$conexion->affected_rows;
		if(!$inset_encuestas || $num=0){
			$success=false;
			echo "ERROR inset_encuestas"; 
			var_dump($conexion->error);
		}

		$id=$_POST['encuesta'][0]['id_encuesta']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['encuesta'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['encuesta'][0])."'";
		$inset_encuestas_historicos= "INSERT INTO encuestas_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_encuestas_historicos=$conexion->query($inset_encuestas_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_encuestas_historicos || $num=0){
			$success=false;
			echo "ERROR inset_encuestas_historicos"; 
			var_dump($conexion->error);
		}
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'encuestas',$id,'Insert','',$fechaH);
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