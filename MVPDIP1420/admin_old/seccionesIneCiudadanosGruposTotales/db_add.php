<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos_grupos.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_grupos',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["seccion_ine_ciudadano_grupo"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["seccion_ine_ciudadano_grupo"][0] as $keyPrincipal => $atributo) {
			$_POST["seccion_ine_ciudadano_grupo"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$seccion_ine_ciudadano_grupoClaveVerificacion=seccion_ine_ciudadano_grupoClaveVerificacion($_POST["seccion_ine_ciudadano_grupo"][0]['clave'],'',1);
		if($seccion_ine_ciudadano_grupoClaveVerificacion){
			$claveF= clave('secciones_ine_ciudadanos_grupos');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["seccion_ine_ciudadano_grupo"][0]['clave'] = $claveF['clave'];
			}
		}
		$_POST["seccion_ine_ciudadano_grupo"][0]['id_seccion_ine_ciudadano'] = seccion_ine_ciudadanoClaveElectorVerificacion($_POST["seccion_ine_ciudadano_grupo"][0]['clave_elector']);
		unset($_POST["seccion_ine_ciudadano_grupo"][0]['clave_elector']);

		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["seccion_ine_ciudadano_grupo"][0]['fechaR']=$fechaH; 
		$_POST["seccion_ine_ciudadano_grupo"][0]['fecha_hora']=$_POST["seccion_ine_ciudadano_grupo"][0]['fecha']." ".$_POST["seccion_ine_ciudadano_grupo"][0]['hora'];
		$_POST["seccion_ine_ciudadano_grupo"][0]['codigo_plataforma']=$codigo_plataforma;
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


		$fields_pdo = "`".implode('`,`', array_keys($_POST['seccion_ine_ciudadano_grupo'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['seccion_ine_ciudadano_grupo'][0])."'";
		$inset_secciones_ine_ciudadanos_grupos= "INSERT INTO secciones_ine_ciudadanos_grupos ($fields_pdo) VALUES ($values_pdo);";

		$inset_secciones_ine_ciudadanos_grupos=$conexion->query($inset_secciones_ine_ciudadanos_grupos);
		$num=$conexion->affected_rows;
		if(!$inset_secciones_ine_ciudadanos_grupos || $num=0){
			$success=false;
			echo "ERROR inset_secciones_ine_ciudadanos_grupos"; 
			var_dump($conexion->error);
		}

		$id=$_POST['seccion_ine_ciudadano_grupo'][0]['id_seccion_ine_ciudadano_grupo']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['seccion_ine_ciudadano_grupo'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['seccion_ine_ciudadano_grupo'][0])."'";
		$inset_secciones_ine_ciudadanos_grupos_historicos= "INSERT INTO secciones_ine_ciudadanos_grupos_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_secciones_ine_ciudadanos_grupos_historicos=$conexion->query($inset_secciones_ine_ciudadanos_grupos_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_secciones_ine_ciudadanos_grupos_historicos || $num=0){
			$success=false;
			echo "ERROR inset_secciones_ine_ciudadanos_grupos_historicos"; 
			var_dump($conexion->error);
		}
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_ciudadanos_grupos',$id,'Insert','',$fechaH);
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