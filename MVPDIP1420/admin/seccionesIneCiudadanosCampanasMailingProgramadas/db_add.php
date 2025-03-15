<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos_campanas_mailing_programadas.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_campanas_mailing_programadas',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["seccion_ine_ciudadano_campana_mailing_programada"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["seccion_ine_ciudadano_campana_mailing_programada"][0] as $keyPrincipal => $atributo) {
			$_POST["seccion_ine_ciudadano_campana_mailing_programada"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$seccion_ine_ciudadano_campana_mailing_programadaClaveVerificacion=seccion_ine_ciudadano_campana_mailing_programadaClaveVerificacion($_POST["seccion_ine_ciudadano_campana_mailing_programada"][0]['clave'],'',1);
		if($seccion_ine_ciudadano_campana_mailing_programadaClaveVerificacion){
			$claveF= clave('secciones_ine_ciudadanos_campanas_mailing_programadas');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["seccion_ine_ciudadano_campana_mailing_programada"][0]['clave'] = $claveF['clave'];
			}
		}

		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["seccion_ine_ciudadano_campana_mailing_programada"][0]['fechaR']=$fechaH; 
		$_POST["seccion_ine_ciudadano_campana_mailing_programada"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['seccion_ine_ciudadano_campana_mailing_programada'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['seccion_ine_ciudadano_campana_mailing_programada'][0])."'";
		$inset_secciones_ine_ciudadanos_campanas_mailing_programadas= "INSERT INTO secciones_ine_ciudadanos_campanas_mailing_programadas ($fields_pdo) VALUES ($values_pdo);";

		$inset_secciones_ine_ciudadanos_campanas_mailing_programadas=$conexion->query($inset_secciones_ine_ciudadanos_campanas_mailing_programadas);
		$num=$conexion->affected_rows;
		if(!$inset_secciones_ine_ciudadanos_campanas_mailing_programadas || $num=0){
			$success=false;
			echo "ERROR inset_secciones_ine_ciudadanos_campanas_mailing_programadas"; 
			var_dump($conexion->error);
		}

		$id=$_POST['seccion_ine_ciudadano_campana_mailing_programada'][0]['id_seccion_ine_ciudadano_campana_mailing_programada']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['seccion_ine_ciudadano_campana_mailing_programada'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['seccion_ine_ciudadano_campana_mailing_programada'][0])."'";
		$inset_secciones_ine_ciudadanos_campanas_mailing_programadas_historicos= "INSERT INTO secciones_ine_ciudadanos_campanas_mailing_programadas_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_secciones_ine_ciudadanos_campanas_mailing_programadas_historicos=$conexion->query($inset_secciones_ine_ciudadanos_campanas_mailing_programadas_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_secciones_ine_ciudadanos_campanas_mailing_programadas_historicos || $num=0){
			$success=false;
			echo "ERROR inset_secciones_ine_ciudadanos_campanas_mailing_programadas_historicos"; 
			var_dump($conexion->error);
		}
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_ciudadanos_campanas_mailing_programadas',$id,'Insert','',$fechaH);
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