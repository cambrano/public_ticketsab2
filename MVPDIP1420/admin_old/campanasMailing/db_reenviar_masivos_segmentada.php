<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/campanas_mailing_cartografias.php";
	include __DIR__."/../functions/campanas_mailing.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','campañas_mailing',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['delete'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["campana_mailing"][0] as $keyPrincipal => $atributo) {
			$_POST["campana_mailing"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		foreach($_POST["campana_mailing_encuesta"][0] as $keyPrincipal => $atributo) {
			$_POST["campana_mailing_encuesta"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		foreach($_POST["campana_mailing_cartografia"][0] as $keyPrincipal => $atributo) {
			$_POST["campana_mailing_cartografia"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}

		foreach($_POST["campana_mailing_tipo_ciudadano"] as $keyPrincipal => $atributos) {
			if($atributos['check']==1 ){
				$_POST["campana_mailing_tipo_ciudadano"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
				$tipos_ciudadanos[]=$atributos['id_tipo_ciudadano'];
			}
		}

		foreach($_POST["campana_mailing_tipo_categoria_ciudadano"] as $keyPrincipal => $atributos) {
			if($atributos['check']==1 ){
				$_POST["campana_mailing_tipo_categoria_ciudadano"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
				$tipos_categorias_ciudadanos[]=$atributos['id_tipo_categoria_ciudadano'];
			}
		}

		$id_campana_mailing = $_POST["campana_mailing"][0]['id'];

		$conexion->autocommit(FALSE);
		$success=true;

		$campana_mailingDatos=campana_mailingDatos($id_campana_mailing);
		$tipo = $campana_mailingDatos['tipo'];

		if($campana_mailingDatos['status']==0 || $campana_mailingDatos['status']==''){
			echo "La campaña <b>{$campana_mailingDatos['nombre']}</b> no esta activa debe activarla para el reenvio masivos de correos electrónicos, gracias ";
			die;
		}




		$campana_mailing_cartografiaDatos=campana_mailing_cartografiaDatos('',$id);

		/////sql para insertar de otra tabla masivamente
		$sql="INSERT INTO secciones_ine_ciudadanos_campanas_mailing_programadas
			(id_seccion_ine_ciudadano, id_seccion_ine, id_distrito_local, id_distrito_federal, id_estado, id_municipio, id_campana_mailing, id_campana_mailing_cuerpo, id_campana_mailing_programada, status, fechaR, codigo_plataforma, codigo_seccion_ine_ciudadano, identificador, asunto, cuerpo, fecha_registro, hora_registro, fecha_hora_registro,tipo,id_usuario)
			SELECT 
			sic.id id_seccion_ine_ciudadano,
			sic.id_seccion_ine,
			sic.id_distrito_local,
			sic.id_distrito_federal,
			sic.id_estado,
			sic.id_municipio,
			/*sic.id_campana_mailing,*/
			(SELECT cm.id from campanas_mailing cm limit 1) id_campana_mailing,
			/*sic.id_campana_mailing_cuerpo,*/
			(SELECT cmp.id from campanas_mailing_cuerpos cmp limit 1) id_campana_mailing_cuerpo,

			NULL id_campana_mailing_programada,
			'0' status,
			'{$fechaH}' fechaR,
			sic.codigo_plataforma,
			sic.codigo_seccion_ine_ciudadano,
			'1' identificador,
			/*sic.asunto,*/
			/*(SELECT cmp.asunto from campanas_mailing_cuerpos cmp limit 1) asunto,*/
			NULL asunto,
			/*sic.cuerpo,*/
			/*(SELECT cmp.cuerpo from campanas_mailing_cuerpos cmp limit 1) cuerpo,*/
			NULL cuerpo,
			'{$fechaSF}' fecha_registro,
			'{$fechaSH}' hora_registro,
			'{$fechaH}' fecha_hora_registro,
			'{$tipo}' tipo,
			{$_COOKIE["id_usuario"]}
			FROM secciones_ine_ciudadanos sic
			WHERE 1 
		";

		$id_encuesta = $_POST["campana_mailing_encuesta"][0]['id_encuesta'];
		if(!empty($id_encuesta)){
			$sql .=" AND EXISTS (SELECT * FROM secciones_ine_ciudadanos_encuestas sice WHERE sice.id_encuesta = '{$id_encuesta}' AND sice.id_seccion_ine_ciudadano = sic.id ) ";
		}

		$tipo_cartografia = $_POST["campana_mailing_cartografia"][0]['tipo_cartografia'];
		$id_tipo_cartografia = $_POST["campana_mailing_cartografia"][0]['id_tipo_cartografia'];
		if(!empty($_POST["campana_mailing_cartografia"][0]['tipo_cartografia']) && !empty($_POST["campana_mailing_cartografia"][0]['id_tipo_cartografia']) ){

			if($tipo_cartografia=='municipios'){
				$sql .= ' AND sic.id_municipio =  "'.$id_tipo_cartografia.'"';
			}elseif ($tipo_cartografia=='distritos_locales') {
				$sql .= ' AND sic.id_distrito_local =  "'.$id_tipo_cartografia.'" ';
			}elseif ($tipo_cartografia=='distritos_federales') {
				$sql .= ' AND sic.id_distrito_federal =  "'.$id_tipo_cartografia.'" ';
			}elseif ($tipo_cartografia=='secciones_ine') {
				$sql .= ' AND sic.id_seccion_ine =  "'.$id_tipo_cartografia.'" ';
			}else{}
		}

		if(!empty($tipos_ciudadanos)){
			$id_tipos_ciudadanos = "'".implode("','", $tipos_ciudadanos)."'";
			$sql .=" AND sic.id_tipo_ciudadano IN ({$id_tipos_ciudadanos}) ";
		}


		if(!empty($tipos_categorias_ciudadanos)){
			$id_tipos_categorias_ciudadanos = "'".implode("','", $tipos_categorias_ciudadanos)."'";
			$sql .=" AND EXISTS (SELECT * FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id IN ({$id_tipos_categorias_ciudadanos})  AND sic.id_tipo_ciudadano = sicc.id ) ";
		}

		$sql .=";";


		$conexion->autocommit(FALSE);
		$update_secciones_ine_ciudadanos_campanas_mailing_programadas=$conexion->query($sql);
		$num=$conexion->affected_rows;
		if(!$update_secciones_ine_ciudadanos_campanas_mailing_programadas || $num=0){
			$success=false;
			echo "<br>";
			echo "ERROR update_secciones_ine_ciudadanos_campanas_mailing_programadas"; 
			var_dump($conexion->error);
		}


		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],"secciones_ine_ciudadanos_campanas_mailing_programadas",$id_campana_mailing,'reenviar_masivos_segmentada','',$fechaH);
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
