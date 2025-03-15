<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/preguntas_2022_revocacion_mandato.php";
	/*include __DIR__."/../functions/claves.php";*/
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','preguntas_2022_revocacion_mandato',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}
	//var_dump($_POST["partido"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["pregunta_2022_revocacion_mandato"][0] as $keyPrincipal => $atributo) {
			$_POST["pregunta_2022_revocacion_mandato"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}

		$pregunta_2022_revocacion_mandatoClaveVerificacion=pregunta_2022_revocacion_mandatoClaveVerificacion($_POST["pregunta_2022_revocacion_mandato"][0]['clave'],'',1);
		if($pregunta_2022_revocacion_mandatoClaveVerificacion){
			/*$claveF= clave('preguntas_2022_revocacion_mandato');*/
			
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["pregunta_2022_revocacion_mandato"][0]['clave'] = $claveF['clave'];
			}
			
		}


		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["pregunta_2022_revocacion_mandato"][0]['fechaR']=$fechaH; 
		$_POST["pregunta_2022_revocacion_mandato"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['pregunta_2022_revocacion_mandato'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['pregunta_2022_revocacion_mandato'][0])."'";
		$inset_preguntas_2022_revocacion_mandato= "INSERT INTO preguntas_2022_revocacion_mandato ($fields_pdo) VALUES ($values_pdo);";

		$inset_preguntas_2022_revocacion_mandato=$conexion->query($inset_preguntas_2022_revocacion_mandato);
		$num=$conexion->affected_rows;
		if(!$inset_preguntas_2022_revocacion_mandato || $num=0){
			$success=false;
			echo "ERROR inset_preguntas_2022_revocacion_mandato"; 
			var_dump($conexion->error);
		}

		$id=$_POST['pregunta_2022_revocacion_mandato'][0]['id_pregunta_2022_revocacion_mandato']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['pregunta_2022_revocacion_mandato'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['pregunta_2022_revocacion_mandato'][0])."'";
		$inset_preguntas_2022_revocacion_mandato_historicos= "INSERT INTO preguntas_2022_revocacion_mandato_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_preguntas_2022_revocacion_mandato_historicos=$conexion->query($inset_preguntas_2022_revocacion_mandato_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_preguntas_2022_revocacion_mandato_historicos || $num=0){
			$success=false;
			echo "ERROR inset_preguntas_2022_revocacion_mandato_historicos"; 
			var_dump($conexion->error);
		}
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'preguntas_2022_revocacion_mandato',$id,'Insert','',$fechaH);
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