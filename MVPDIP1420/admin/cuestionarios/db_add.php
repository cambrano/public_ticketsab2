<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/cuestionarios.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/cuestionarios_respuestas.php";
	include __DIR__."/../functions/tool_xhpzab.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','encuestas',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}
	@session_start();
	$id_encuesta = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);

	if(!empty($_POST)){
		$success=true;
		//metemos los valores para que se no tengamos error
		foreach($_POST["pregunta"][0] as $keyPrincipal => $atributo) {
			$_POST["pregunta"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$_POST["pregunta"][0]['fechaR']=$fechaH;  
		$_POST["pregunta"][0]['codigo_plataforma']=$codigo_plataforma;
		$_POST["pregunta"][0]['status'] = 1;

		//metemos los valores para que se no tengamos error

		$cuestionarioClaveVerificacion=cuestionarioClaveVerificacion($_POST["pregunta"][0]['clave'],'',1);
		if($cuestionarioClaveVerificacion){
			$_POST["pregunta"][0]['clave'] = "PRE-".$cod16M;
		}

		$_POST["pregunta"][0]['id_encuesta'] = $id_encuesta;

		$fields_pdo = "`".implode('`,`', array_keys($_POST["pregunta"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["pregunta"][0])."'";
		$insert_cuestionarios= "INSERT INTO cuestionarios ($fields_pdo) VALUES ($values_pdo);";
		$conexion->autocommit(FALSE);

		$insert_cuestionarios=$conexion->query($insert_cuestionarios);
		$num=$conexion->affected_rows;
		if(!$insert_cuestionarios || $num=0){
			$success=false;
			echo "ERROR insert_cuestionarios"; 
			var_dump($conexion->error);
		}

		$id_cuestionario=$_POST["pregunta"][0]['id_cuestionario']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST["pregunta"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["pregunta"][0])."'";
		$insert_cuestionarios_historicos= "INSERT INTO cuestionarios_historicos ($fields_pdo) VALUES ($values_pdo);";

		$insert_cuestionarios_historicos=$conexion->query($insert_cuestionarios_historicos);
		$num=$conexion->affected_rows;
		if(!$insert_cuestionarios_historicos || $num=0){
			$success=false;
			echo "ERROR insert_cuestionarios_historicos"; 
			var_dump($conexion->error);
		}
		////////
		////Preguntas
		
		foreach ($_POST['respuestas'] as $key => $value) {
			include __DIR__."/../functions/genid.php";
			$cuestionarioClaveVerificacion=cuestionario_respuestaClaveVerificacion($value['clave'],'',1);
			if($cuestionarioClaveVerificacion){
				$value['clave'] = "PRE-".$cod16M;
			}
			unset($respuesta);
			unset($value['id']);
			$respuesta=$value;
			$respuesta['codigo_plataforma'] = $codigo_plataforma;
			$respuesta['fechaR'] = $fechaH; 
			$respuesta['id_cuestionario'] = $id_cuestionario; 
			$respuesta['id_encuesta'] = $id_encuesta; 
			unset($respuesta['id_respuesta']);


			$fields_pdo = "`".implode('`,`', array_keys($respuesta))."`";
			$values_pdo = "'".implode("','", $respuesta)."'";
			$insert_cuestionarios_respuestas= "INSERT INTO cuestionarios_respuestas ($fields_pdo) VALUES ($values_pdo);";
			$conexion->autocommit(FALSE);

			$insert_cuestionarios_respuestas=$conexion->query($insert_cuestionarios_respuestas);
			$num=$conexion->affected_rows;
			if(!$insert_cuestionarios_respuestas || $num=0){
				$success=false;
				echo "ERROR insert_cuestionarios_respuestas"; 
				var_dump($conexion->error);
			}

			$respuesta['id_cuestionario_respuesta']=$conexion->insert_id;
			$fields_pdo = "`".implode('`,`', array_keys($respuesta))."`";
			$values_pdo = "'".implode("','", $respuesta)."'";
			$insert_cuestionarios_respuestas_historicos= "INSERT INTO cuestionarios_respuestas_historicos ($fields_pdo) VALUES ($values_pdo);";

			$insert_cuestionarios_respuestas_historicos=$conexion->query($insert_cuestionarios_respuestas_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_cuestionarios_respuestas_historicos || $num=0){
				$success=false;
				echo "ERROR insert_cuestionarios_respuestas_historicos"; 
				var_dump($conexion->error);
			}
		}

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'cuestionarios',$id_cuestionario,'Insert','',$fechaH);
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