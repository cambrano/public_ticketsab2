<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/tipos_ciudadanos_secciones_avance_semaforo.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','tipos_ciudadanos_secciones_avance_semaforo',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	$tipo_ciudadano_seccion_avance_semaforoDatos = tipo_ciudadano_seccion_avance_semaforoDatos('',$_POST["tipo_ciudadano_seccion_avance_semaforo"][0]['id_seccion_ine'],$_POST["tipo_ciudadano_seccion_avance_semaforo"][0]['id_tipo_ciudadano']);
	if(!empty($tipo_ciudadano_seccion_avance_semaforoDatos)){
		echo "Ya existe ese tipo de ciudadano en esta sección.";
		die;
	}

	//var_dump($_POST["tipo_ciudadano_seccion_avance_semaforo"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["tipo_ciudadano_seccion_avance_semaforo"][0] as $keyPrincipal => $atributo) {
			$_POST["tipo_ciudadano_seccion_avance_semaforo"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}

		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["tipo_ciudadano_seccion_avance_semaforo"][0]['fechaR']=$fechaH; 
		$_POST["tipo_ciudadano_seccion_avance_semaforo"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['tipo_ciudadano_seccion_avance_semaforo'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['tipo_ciudadano_seccion_avance_semaforo'][0])."'";
		$inset_tipos_ciudadanos_secciones_avance_semaforo= "INSERT INTO tipos_ciudadanos_secciones_avance_semaforo ($fields_pdo) VALUES ($values_pdo);";

		$inset_tipos_ciudadanos_secciones_avance_semaforo=$conexion->query($inset_tipos_ciudadanos_secciones_avance_semaforo);
		$num=$conexion->affected_rows;
		if(!$inset_tipos_ciudadanos_secciones_avance_semaforo || $num=0){
			$success=false;
			echo "ERROR inset_tipos_ciudadanos_secciones_avance_semaforo"; 
			var_dump($conexion->error);
		}

		$id=$_POST['tipo_ciudadano_seccion_avance_semaforo'][0]['id_tipo_ciudadano_seccion_avance_semaforo']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['tipo_ciudadano_seccion_avance_semaforo'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['tipo_ciudadano_seccion_avance_semaforo'][0])."'";
		$inset_tipos_ciudadanos_secciones_avance_semaforo_historicos = "INSERT INTO tipos_ciudadanos_secciones_avance_semaforo_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_tipos_ciudadanos_secciones_avance_semaforo_historicos=$conexion->query($inset_tipos_ciudadanos_secciones_avance_semaforo_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_tipos_ciudadanos_secciones_avance_semaforo_historicos || $num=0){
			$success=false;
			echo "ERROR inset_tipos_ciudadanos_secciones_avance_semaforo_historicos"; 
			var_dump($conexion->error);
		}
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'tipos_ciudadanos_secciones_avance_semaforo',$id,'Insert','',$fechaH);
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