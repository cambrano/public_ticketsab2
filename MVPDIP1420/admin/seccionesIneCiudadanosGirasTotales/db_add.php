<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos_giras.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_giras',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}


	//var_dump($_POST["seccion_ine_ciudadano_gira"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["seccion_ine_ciudadano_gira"][0] as $keyPrincipal => $atributo) {
			$_POST["seccion_ine_ciudadano_gira"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$seccion_ine_ciudadano_giraClaveVerificacion=seccion_ine_ciudadano_giraClaveVerificacion($_POST["seccion_ine_ciudadano_gira"][0]['clave'],'',1);
		if($seccion_ine_ciudadano_giraClaveVerificacion){
			$claveF= clave2('secciones_ine_ciudadanos_giras');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["seccion_ine_ciudadano_gira"][0]['clave'] = $claveF['clave'];
			}
		}

		$_POST["seccion_ine_ciudadano_gira"][0]['id_seccion_ine_ciudadano'] = seccion_ine_ciudadanoClaveElectorVerificacion($_POST["seccion_ine_ciudadano_gira"][0]['clave_elector']);
		unset($_POST["seccion_ine_ciudadano_gira"][0]['clave_elector']);
		if($_POST["seccion_ine_ciudadano_gira"][0]['id_seccion_ine_ciudadano']==''){
			echo "Este ciudadano no esta registrado en la plataforma.";
			die;
		}

		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["seccion_ine_ciudadano_gira"][0]['fechaR']=$fechaH; 
		$_POST["seccion_ine_ciudadano_gira"][0]['fecha_hora']=$_POST["seccion_ine_ciudadano_gira"][0]['fecha']." ".$_POST["seccion_ine_ciudadano_gira"][0]['hora'];
		$_POST["seccion_ine_ciudadano_gira"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['seccion_ine_ciudadano_gira'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['seccion_ine_ciudadano_gira'][0])."'";
		$inset_secciones_ine_ciudadanos_giras= "INSERT INTO secciones_ine_ciudadanos_giras ($fields_pdo) VALUES ($values_pdo);";

		$inset_secciones_ine_ciudadanos_giras=$conexion->query($inset_secciones_ine_ciudadanos_giras);
		$num=$conexion->affected_rows;
		if(!$inset_secciones_ine_ciudadanos_giras || $num=0){
			$success=false;
			echo "ERROR inset_secciones_ine_ciudadanos_giras"; 
			var_dump($conexion->error);
		}

		$id=$_POST['seccion_ine_ciudadano_gira'][0]['id_seccion_ine_ciudadano_gira']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['seccion_ine_ciudadano_gira'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['seccion_ine_ciudadano_gira'][0])."'";
		$inset_secciones_ine_ciudadanos_giras_historicos= "INSERT INTO secciones_ine_ciudadanos_giras_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_secciones_ine_ciudadanos_giras_historicos=$conexion->query($inset_secciones_ine_ciudadanos_giras_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_secciones_ine_ciudadanos_giras_historicos || $num=0){
			$success=false;
			echo "ERROR inset_secciones_ine_ciudadanos_giras_historicos"; 
			var_dump($conexion->error);
		}
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_ciudadanos_giras',$id,'Insert','',$fechaH);
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