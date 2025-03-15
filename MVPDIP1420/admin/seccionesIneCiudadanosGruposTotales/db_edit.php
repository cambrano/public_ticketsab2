<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos_grupos.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"secciones_ine_ciudadanos_grupos",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	$_POST["seccion_ine_ciudadano_grupo"][0]['id_seccion_ine_ciudadano'] = seccion_ine_ciudadanoClaveElectorVerificacion($_POST["seccion_ine_ciudadano_grupo"][0]['clave_elector']);
	unset($_POST["seccion_ine_ciudadano_grupo"][0]['clave_elector']);

	//metemos los valores para que se no tengamos error
	foreach($_POST["seccion_ine_ciudadano_grupo"][0] as $keyPrincipal => $atributo) {
		$_POST["seccion_ine_ciudadano_grupo"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$seccion_ine_ciudadano_grupoClaveVerificacion=seccion_ine_ciudadano_grupoClaveVerificacion($_POST["seccion_ine_ciudadano_grupo"][0]["clave"],$_POST["seccion_ine_ciudadano_grupo"][0]['id'],1);
	if($seccion_ine_ciudadano_grupoClaveVerificacion){
		$claveF= clave("secciones_ine_ciudadanos_grupos");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["seccion_ine_ciudadano_grupo"][0]["clave"] = $claveF["clave"];
		}
	}

	if( registrosCompara("secciones_ine_ciudadanos_grupos",$_POST["seccion_ine_ciudadano_grupo"][0],1)){
		if(!empty($_POST)){ 
			$_POST["seccion_ine_ciudadano_grupo"][0]["fechaR"]=$fechaH;
			$_POST["seccion_ine_ciudadano_grupo"][0]["codigo_plataforma"]=$codigo_plataforma;
			$success=true;
			foreach($_POST["seccion_ine_ciudadano_grupo"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			$conexion->autocommit(FALSE);
			if($_POST["seccion_ine_ciudadano_grupo"][0]['status']==1){
				//ponemos en 0 los demas
				$id_seccion_ine_ciudadano = $_POST["seccion_ine_ciudadano_grupo"][0]['id_seccion_ine_ciudadano'];
				$update_secciones_ine_ciudadanos_grupos = "UPDATE secciones_ine_ciudadanos_grupos SET `status` = '0' WHERE id<>0 AND id_seccion_ine_ciudadano ='{$id_seccion_ine_ciudadano}' ;";
				$update_secciones_ine_ciudadanos_grupos=$conexion->query($update_secciones_ine_ciudadanos_grupos);
				$num=$conexion->affected_rows;
				if(!$update_secciones_ine_ciudadanos_grupos || $num=0){
					$success=false;
					echo "<br>";
					echo "ERROR update_secciones_ine_ciudadanos_grupos"; 
					var_dump($conexion->error);
				}
			}

			$update_secciones_ine_ciudadanos_grupos = "UPDATE secciones_ine_ciudadanos_grupos SET ". join(",",$valueSets) . " WHERE id=".$id;
			$update_secciones_ine_ciudadanos_grupos=$conexion->query($update_secciones_ine_ciudadanos_grupos);
			$num=$conexion->affected_rows;
			if(!$update_secciones_ine_ciudadanos_grupos || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_secciones_ine_ciudadanos_grupos"; 
				var_dump($conexion->error);
			}

			unset($_POST["seccion_ine_ciudadano_grupo"][0]['id']); 
			$id_seccion_ine_ciudadano_grupo=$_POST["seccion_ine_ciudadano_grupo"][0]["id_seccion_ine_ciudadano_grupo"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["seccion_ine_ciudadano_grupo"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["seccion_ine_ciudadano_grupo"][0])."'";
			$insert_secciones_ine_ciudadanos_grupos_historicos= "INSERT INTO secciones_ine_ciudadanos_grupos_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_secciones_ine_ciudadanos_grupos_historicos=$conexion->query($insert_secciones_ine_ciudadanos_grupos_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_secciones_ine_ciudadanos_grupos_historicos || $num=0){
				$success=false;
				echo "ERROR insert_secciones_ine_ciudadanos_grupos_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],"secciones_ine_ciudadanos_grupos",$id_seccion_ine_ciudadano_grupo,'Update','',$fechaH);
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
