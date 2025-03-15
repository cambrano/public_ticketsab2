<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/secciones_ine_grupos.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"secciones_ine_grupos",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["seccion_ine_grupo"][0] as $keyPrincipal => $atributo) {
		$_POST["seccion_ine_grupo"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$seccion_ine_grupoClaveVerificacion=seccion_ine_grupoClaveVerificacion($_POST["seccion_ine_grupo"][0]["clave"],$_POST["seccion_ine_grupo"][0]['id'],1);
	if($seccion_ine_grupoClaveVerificacion){
		$claveF= clave("secciones_ine_grupos");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["seccion_ine_grupo"][0]["clave"] = $claveF["clave"];
		}
	}


	if( registrosCompara("secciones_ine_grupos",$_POST['seccion_ine_grupo'][0],1) ){
		if(!empty($_POST)){
			$seccion_ine_grupoDatos=seccion_ine_grupoDatos($_POST['seccion_ine_grupo'][0]['id']);


			//$_POST['registro']=$fechaH;
			$_POST["seccion_ine_grupo"][0]['fechaR']=$fechaH;
			$_POST["seccion_ine_grupo"][0]['fecha_hora']=$_POST["seccion_ine_grupo"][0]['fecha']." ".$_POST["seccion_ine_grupo"][0]['hora'];

			$_POST["seccion_ine_grupo"][0]['codigo_plataforma']=$codigo_plataforma;
			$_POST["seccion_ine_grupo"][0]["referencia_importacion"]=$seccion_ine_grupoDatos['referencia_importacion'];


			$success=true;
			foreach($_POST['seccion_ine_grupo'] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}

			$update_secciones_ine_grupos = "UPDATE secciones_ine_grupos SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_secciones_ine_grupos=$conexion->query($update_secciones_ine_grupos);
			$num=$conexion->affected_rows;
			if(!$update_secciones_ine_grupos || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_secciones_ine_grupos"; 
				var_dump($conexion->error);
			}

			unset($_POST["seccion_ine_grupo"][0]['id']); 
			$id_seccion_ine_grupo=$_POST['seccion_ine_grupo'][0]['id_seccion_ine_grupo']=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["seccion_ine_grupo"][0]))."`";
			$values_pdo = "'".implode("','", $_POST['seccion_ine_grupo'][0])."'";
			$inset_secciones_ine_grupos_historicos= "INSERT INTO secciones_ine_grupos_historicos ($fields_pdo) VALUES ($values_pdo);";
			$inset_secciones_ine_grupos_historicos=$conexion->query($inset_secciones_ine_grupos_historicos);
			$num=$conexion->affected_rows;
			if(!$inset_secciones_ine_grupos_historicos || $num=0){
				$success=false;
				echo "ERROR inset_secciones_ine_grupos_historicos"; 
				var_dump($conexion->error);
			}
			if($fkck){
				$conexion->query("SET FOREIGN_KEY_CHECKS=1;");
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_grupos',$id_seccion_ine_grupo,'Update','',$fechaH);
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
