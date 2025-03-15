<?php
	@session_start();
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/usuario_permisos.php";
	
	include __DIR__."/../functions/campanas_whatsapp.php";
	include __DIR__."/../functions/campanas_whatsapp_encuestas.php";
	include __DIR__."/../functions/campanas_whatsapp_cartografias.php";
	include __DIR__."/../functions/campanas_whatsapp_tipos_ciudadanos.php";
	include __DIR__."/../functions/campanas_whatsapp_tipos_categorias_ciudadanos.php";

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','campañas_whatsapp',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['delete'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}
	if(!empty($_POST)){
		$id_seccion_ine_ciudadano=$_SESSION['id_seccion_ine_ciudadano'];
		//metemos los valores para que se no tengamos error
		foreach($_POST as $keyPrincipal => $atributo) {
			$_POST[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$conexion->autocommit(FALSE);
		$id=$_POST['id']; 
		$success=true;

		$campana_whatsappDatos=campana_whatsappDatos($id);
		$tipo = $campana_whatsappDatos['tipo'];

		if($campana_whatsappDatos['status']==0 || $campana_whatsappDatos['status']==''){
			echo "La campaña <b>{$campana_whatsappDatos['nombre']}</b> no esta activa debe activarla para el reenvio masivos de correos electrónicos, gracias ";
			die;
		}

		$campana_whatsapp_encuestaDatos=campana_whatsapp_encuestaDatos('',$id);
		$campana_whatsapp_cartografiaDatos=campana_whatsapp_cartografiaDatos('',$id);
		$campanas_whatsapp_tipos_ciudadanosIdDatos=campanas_whatsapp_tipos_ciudadanosIdDatos('',$id); 
		$campanas_whatsapp_tipos_categorias_ciudadanosIdDatos=campanas_whatsapp_tipos_categorias_ciudadanosIdDatos('',$id);

		/////sql para insertar de otra tabla masivamente
		$sql="INSERT INTO secciones_ine_ciudadanos_campanas_whatsapp_programadas
			(id_seccion_ine_ciudadano, id_seccion_ine, id_distrito_local, id_distrito_federal, id_estado, id_municipio, id_campana_whatsapp, id_campana_whatsapp_cuerpo, id_campana_whatsapp_programada, status, fechaR, codigo_plataforma, codigo_seccion_ine_ciudadano, identificador, asunto, cuerpo, fecha_registro, hora_registro, fecha_hora_registro,tipo,id_usuario)
			SELECT 
			sic.id id_seccion_ine_ciudadano,
			sic.id_seccion_ine,
			sic.id_distrito_local,
			sic.id_distrito_federal,
			sic.id_estado,
			sic.id_municipio,
			/*sic.id_campana_whatsapp,*/
			(SELECT cm.id from campanas_whatsapp cm limit 1) id_campana_whatsapp,
			/*sic.id_campana_whatsapp_cuerpo,*/
			(SELECT cmp.id from campanas_whatsapp_cuerpos cmp limit 1) id_campana_whatsapp_cuerpo,

			NULL id_campana_whatsapp_programada,
			'0' status,
			'{$fechaH}' fechaR,
			sic.codigo_plataforma,
			sic.codigo_seccion_ine_ciudadano,
			'1' identificador,
			/*sic.asunto,*/
			/*(SELECT cmp.asunto from campanas_whatsapp_cuerpos cmp limit 1) asunto,*/
			NULL asunto,
			/*sic.cuerpo,*/
			/*(SELECT cmp.cuerpo from campanas_whatsapp_cuerpos cmp limit 1) cuerpo,*/
			NULL cuerpo,
			'{$fechaSF}' fecha_registro,
			'{$fechaSH}' hora_registro,
			'{$fechaH}' fecha_hora_registro,
			'{$tipo}' tipo,
			{$_COOKIE["id_usuario"]}
			FROM secciones_ine_ciudadanos sic
			WHERE id='{$id_seccion_ine_ciudadano}'
		";

		$sql .=";";

		$conexion->autocommit(FALSE);
		$update_secciones_ine_ciudadanos_campanas_whatsapp_programadas=$conexion->query($sql);
		$num=$conexion->affected_rows;
		if(!$update_secciones_ine_ciudadanos_campanas_whatsapp_programadas || $num=0){
			$success=false;
			echo "<br>";
			echo "ERROR update_secciones_ine_ciudadanos_campanas_whatsapp_programadas"; 
			var_dump($conexion->error);
		}
	

		$id_seccion_ine_ciudadano ='';
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],"secciones_ine_ciudadanos_campanas_whatsapp_programadas",$id,'reenviar_masivos_configurada','',$fechaH);
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
