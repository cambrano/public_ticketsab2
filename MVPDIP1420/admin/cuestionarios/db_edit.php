<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/cuestionarios.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/tool_xhpzab.php";
	include __DIR__."/../functions/cuestionarios_respuestas.php";
	@session_start();
	$id_encuesta = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);

	
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"encuestas",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	$cuestionarioClaveVerificacion=cuestionarioClaveVerificacion($_POST["pregunta"][0]["clave"],$_POST["pregunta"][0]['id'],1);
	if($cuestionarioClaveVerificacion){
		$claveF= clave("cuestionarios");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["pregunta"][0]["clave"] = $claveF["clave"];
		}
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["pregunta"][0] as $keyPrincipal => $atributo) {
		$_POST["pregunta"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	$id_cuestionario=$_POST["pregunta"][0]['id'];
	$success=true;
	if( registrosCompara("cuestionarios",$_POST["pregunta"][0],1)){
		if(!empty($_POST)){
			//$_POST['registro']=$fechaH;
			$_POST["pregunta"][0]["fechaR"]=$fechaH;
			$_POST["pregunta"][0]["codigo_plataforma"]=$codigo_plataforma;
			$success=true;
			foreach($_POST["pregunta"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_cuestionarios = "UPDATE cuestionarios SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_cuestionarios=$conexion->query($update_cuestionarios);
			$num=$conexion->affected_rows;
			if(!$update_cuestionarios || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_cuestionarios"; 
				var_dump($conexion->error);
			}

			unset($_POST["pregunta"][0]['id']); 
			$id_cuestionario=$_POST["pregunta"][0]["id_cuestionario"]=$id;
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
		}
	}

	$cuestionario_respuestasDatos=cuestionario_respuestasDatos('',$id_cuestionario,$id_encuesta);

	foreach ($cuestionario_respuestasDatos as $key => $value) {
		if(empty($_POST['respuestas_registradas'][$value['id']])){
			//! Borra el registro
			$delete_respuesta[] = $value['id'];
		}

	}

	$_POST['respuestas_registradas'] = array_filter($_POST['respuestas_registradas'], function($value) {
		return !empty($value);
	});
	
	foreach ($delete_respuesta as $key => $value) {
		$id = $value;
		$delete_cuestionarios_respuestas = "DELETE FROM cuestionarios_respuestas  WHERE  id='$id' ";
		$conexion->autocommit(FALSE);
		$delete_cuestionarios_respuestas=$conexion->query($delete_cuestionarios_respuestas);
		$num=$conexion->affected_rows;
		if(!$delete_cuestionarios_respuestas || $num=0){
			$success=false;
			echo "ERROR delete cuestionario respuesta, tiene registros relacionados."; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}
	}

	foreach ($_POST['respuestas_registradas'] as $key => $value) {
		$value['id_cuestionario'] = $id_cuestionario; 
		$value['id_encuesta'] = $id_encuesta;
		foreach($value as $keyPrincipal => $atributo) {
			$value[$keyPrincipal] = mysqli_real_escape_string($conexion,$atributo);
		}
		if( registrosCompara("cuestionarios_respuestas",$value,1)){
			include __DIR__."/../functions/genid.php";
			$cuestionarioClaveVerificacion=cuestionarioClaveVerificacion($value['clave'],$value['id'],1);
			if($cuestionarioClaveVerificacion){
				$value['clave'] = "PRE-".$cod16M;
			}
			///editamos
			unset($valueSets);
			$value['codigo_plataforma'] = $codigo_plataforma;
			$value['fechaR'] = $fechaH;
			foreach ($value as $key => $valueT) {
				if($key !='id'){
					$valueSets[] = $key . " = '" . $valueT . "'";
				}else{
					$id=$valueT;
				}
			}

			$update_cuestionarios = "UPDATE cuestionarios_respuestas SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_cuestionarios=$conexion->query($update_cuestionarios);
			$num=$conexion->affected_rows;
			if(!$update_cuestionarios || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_cuestionarios_respuesta"; 
				var_dump($conexion->error);
			}

			unset($_POST["pregunta"][0]['id']); 
			$value["id_cuestionario"]=$id;
			unset($value["id"]);
			$fields_pdo = "`".implode('`,`', array_keys($value))."`";
			$values_pdo = "'".implode("','", $value)."'";
			$insert_cuestionarios_historicos= "INSERT INTO cuestionarios_respuestas_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_cuestionarios_historicos=$conexion->query($insert_cuestionarios_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_cuestionarios_historicos || $num=0){
				$success=false;
				echo "ERROR insert_cuestionarios_respuesta_historicos"; 
				var_dump($conexion->error);
			}
		}
	}
	foreach ($_POST['respuestas_nuevas'] as $key => $value) {
		include __DIR__."/../functions/genid.php";
		$cuestionarioClaveVerificacion=cuestionarioClaveVerificacion($value['clave'],'',1);
		if($cuestionarioClaveVerificacion){
			$value['clave'] = "PRE-".$cod16M;
		}
		unset($respuesta);
		unset($value['id']);
		unset($value['id_respuesta']);
		$respuesta=$value;
		$respuesta['codigo_plataforma'] = $codigo_plataforma;
		$respuesta['fechaR'] = $fechaH; 
		$respuesta['id_cuestionario'] = $id_cuestionario; 
		$respuesta['id_encuesta'] = $id_encuesta; 

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
		$log= logUsuario($_COOKIE["id_usuario"],'cuestionarios',$id_cuestionario,'Update','',$fechaH);
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