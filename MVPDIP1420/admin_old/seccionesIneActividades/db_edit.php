<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/secciones_ine_actividades.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"secciones_ine_actividades",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["seccion_ine_actividad"][0] as $keyPrincipal => $atributo) {
		$_POST["seccion_ine_actividad"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$seccion_ine_actividadClaveVerificacion=seccion_ine_actividadClaveVerificacion($_POST["seccion_ine_actividad"][0]["clave"],$_POST["seccion_ine_actividad"][0]['id'],1);
	if($seccion_ine_actividadClaveVerificacion){
		$claveF= clave("secciones_ine_actividades");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["seccion_ine_actividad"][0]["clave"] = $claveF["clave"];
		}
	}


	if( registrosCompara("secciones_ine_actividades",$_POST['seccion_ine_actividad'][0],1) ){
		if(!empty($_POST)){
			$seccion_ine_actividadDatos=seccion_ine_actividadDatos($_POST['seccion_ine_actividad'][0]['id']);


			//$_POST['registro']=$fechaH;
			$_POST["seccion_ine_actividad"][0]['fechaR']=$fechaH;
			//$_POST["seccion_ine_actividad"][0]['fecha_hora']=$_POST["seccion_ine_actividad"][0]['fecha']." ".$_POST["seccion_ine_actividad"][0]['hora'];

			$_POST["seccion_ine_actividad"][0]['codigo_plataforma']=$codigo_plataforma;
			$_POST["seccion_ine_actividad"][0]["referencia_importacion"]=$seccion_ine_actividadDatos['referencia_importacion'];


			$success=true;
			foreach($_POST['seccion_ine_actividad'] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}

			$update_secciones_ine_actividades = "UPDATE secciones_ine_actividades SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_secciones_ine_actividades=$conexion->query($update_secciones_ine_actividades);
			$num=$conexion->affected_rows;
			if(!$update_secciones_ine_actividades || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_secciones_ine_actividades"; 
				var_dump($conexion->error);
			}

			unset($_POST["seccion_ine_actividad"][0]['id']); 
			$id_seccion_ine_actividad=$_POST['seccion_ine_actividad'][0]['id_seccion_ine_actividad']=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["seccion_ine_actividad"][0]))."`";
			$values_pdo = "'".implode("','", $_POST['seccion_ine_actividad'][0])."'";
			$inset_secciones_ine_actividades_historicos= "INSERT INTO secciones_ine_actividades_historicos ($fields_pdo) VALUES ($values_pdo);";
			$inset_secciones_ine_actividades_historicos=$conexion->query($inset_secciones_ine_actividades_historicos);
			$num=$conexion->affected_rows;
			if(!$inset_secciones_ine_actividades_historicos || $num=0){
				$success=false;
				echo "ERROR inset_secciones_ine_actividades_historicos"; 
				var_dump($conexion->error);
			}
			if($fkck){
				$conexion->query("SET FOREIGN_KEY_CHECKS=1;");
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_actividades',$id_seccion_ine_actividad,'Update','',$fechaH);
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
	}
