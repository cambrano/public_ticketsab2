<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/switch_operaciones.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos_encuestas.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/api_mailing.php";
	include __DIR__."/../functions/api_sms.php";


	$switch_operacionesPermisos = switch_operacionesPermisos();
	if($switch_operacionesPermisos['evaluacion']==false){
		echo "No tiene permiso.";
		die;
	}

	if(!empty($_POST)){
		foreach($_POST["seccion_ine_ciudadano_encuesta"][0] as $keyPrincipal => $atributo) {
		$_POST["seccion_ine_ciudadano_encuesta"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}

		$seccion_ine_ciudadano_encuestaClaveVerificacion=seccion_ine_ciudadano_encuestaClaveVerificacion($_POST["seccion_ine_ciudadano_encuesta"][0]['clave'],$_POST["seccion_ine_ciudadano_encuesta"][0]['id'],1);
		if($seccion_ine_ciudadano_encuestaClaveVerificacion){
			$_POST["seccion_ine_ciudadano_encuesta"][0]['clave'] = $cod16M;
		}

		if($_POST["seccion_ine_ciudadano_encuesta"][0]['id']==""){
			//agregar
			$success=true;
			unset($_POST["seccion_ine_ciudadano_encuesta"][0]['id']);
			$_POST["seccion_ine_ciudadano_encuesta"][0]['fecha_hora'] = $_POST["seccion_ine_ciudadano_encuesta"][0]['fecha']." ".$_POST["seccion_ine_ciudadano_encuesta"][0]['hora'];
			$_POST["seccion_ine_ciudadano_encuesta"][0]['fechaR'] = $fechaH;
			$_POST["seccion_ine_ciudadano_encuesta"][0]['codigo_plataforma']=$codigo_plataforma;

			$id_seccion_ine_ciudadano = $_POST["seccion_ine_ciudadano_encuesta"][0]['id_seccion_ine_ciudadano'];
			$seccion_ine_ciudadanoDatos = seccion_ine_ciudadanoDatos($id_seccion_ine_ciudadano);

			$_POST["seccion_ine_ciudadano_encuesta"][0]['id_seccion_ine'] = $seccion_ine_ciudadanoDatos['id_seccion_ine'];
			$_POST["seccion_ine_ciudadano_encuesta"][0]['id_distrito_local'] = $seccion_ine_ciudadanoDatos['id_distrito_local'];
			$_POST["seccion_ine_ciudadano_encuesta"][0]['id_distrito_federal'] = $seccion_ine_ciudadanoDatos['id_distrito_federal'];
			$_POST["seccion_ine_ciudadano_encuesta"][0]['id_municipio'] = $seccion_ine_ciudadanoDatos['id_municipio'];
			$_POST["seccion_ine_ciudadano_encuesta"][0]['edad'] = $seccion_ine_ciudadanoDatos['edad'];
			if($seccion_ine_ciudadanoDatos['sexo']=='Hombre'){
				$_POST["seccion_ine_ciudadano_encuesta"][0]['sexo']=1;
			}else{
				$_POST["seccion_ine_ciudadano_encuesta"][0]['sexo']=2;
			}

			$fields_pdo = "`".implode('`,`', array_keys($_POST["seccion_ine_ciudadano_encuesta"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["seccion_ine_ciudadano_encuesta"][0])."'";
			$insert_seccion_ine_ciudadano_encuesta= "INSERT INTO secciones_ine_ciudadanos_encuestas ($fields_pdo) VALUES ($values_pdo);";
			$conexion->autocommit(FALSE);
			$insert_seccion_ine_ciudadano_encuesta=$conexion->query($insert_seccion_ine_ciudadano_encuesta);
			$num=$conexion->affected_rows;
			if(!$insert_seccion_ine_ciudadano_encuesta || $num=0){
				$success=false;
				echo "ERROR insert_seccion_ine_ciudadano_encuesta"; 
				var_dump($conexion->error);
			}
			$id=$_POST["seccion_ine_ciudadano_encuesta"][0]['id_seccion_ine_ciudadano_encuesta']=$conexion->insert_id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["seccion_ine_ciudadano_encuesta"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["seccion_ine_ciudadano_encuesta"][0])."'";
			$insert_seccion_ine_ciudadano_encuesta_historico= "INSERT INTO secciones_ine_ciudadanos_encuestas_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_seccion_ine_ciudadano_encuesta_historico=$conexion->query($insert_seccion_ine_ciudadano_encuesta_historico);
			$num=$conexion->affected_rows;
			if(!$insert_seccion_ine_ciudadano_encuesta_historico || $num=0){
				$success=false;
				echo "ERROR insert_seccion_ine_ciudadano_encuesta_historico"; 
				var_dump($conexion->error);
			}

			foreach ($_POST["cuestionario"] as $key => $value) {
				unset($_POST["cuestionario"][$key]['id']);
				$_POST["cuestionario"][$key]['id_seccion_ine_ciudadano'] = $_POST["seccion_ine_ciudadano_encuesta"][0]['id_seccion_ine_ciudadano'];
				$_POST["cuestionario"][$key]['fecha'] = $_POST["seccion_ine_ciudadano_encuesta"][0]['fecha'];
				$_POST["cuestionario"][$key]['hora'] = $_POST["seccion_ine_ciudadano_encuesta"][0]['hora'];
				$_POST["cuestionario"][$key]['fecha_hora'] = $_POST["seccion_ine_ciudadano_encuesta"][0]['fecha_hora'];
				$_POST["cuestionario"][$key]['fechaR'] = $fechaH;
				$_POST["cuestionario"][$key]['codigo_plataforma']=$codigo_plataforma;
				$_POST["cuestionario"][$key]['id_seccion_ine'] = $seccion_ine_ciudadanoDatos['id_seccion_ine'];
				$_POST["cuestionario"][$key]['id_distrito_local'] = $seccion_ine_ciudadanoDatos['id_distrito_local'];
				$_POST["cuestionario"][$key]['id_distrito_federal'] = $seccion_ine_ciudadanoDatos['id_distrito_federal'];
				$_POST["cuestionario"][$key]['id_municipio'] = $seccion_ine_ciudadanoDatos['id_municipio'];
				$_POST["cuestionario"][$key]['id_seccion_ine_ciudadano_encuesta'] = $id;

				unset($_POST["cuestionario"][$key]['tipo']);

				$fields_pdo = "`".implode('`,`', array_keys($_POST["cuestionario"][$key]))."`";
				$values_pdo = "'".implode("','", $_POST["cuestionario"][$key])."'";
				$insert_cuestionario= "INSERT INTO secciones_ine_ciudadanos_encuestas_respuestas ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$insert_cuestionario=$conexion->query($insert_cuestionario);
				$num=$conexion->affected_rows;
				if(!$insert_cuestionario || $num=0){
					$success=false;
					echo "ERROR insert_cuestionario"; 
					var_dump($conexion->error);
				}
				$_POST["cuestionario"][$key]['id_seccion_ine_ciudadano_encuesta_respuesta']=$conexion->insert_id;
				$fields_pdo = "`".implode('`,`', array_keys($_POST["cuestionario"][$key]))."`";
				$values_pdo = "'".implode("','", $_POST["cuestionario"][$key])."'";
				$insert_cuestionario_historico= "INSERT INTO secciones_ine_ciudadanos_encuestas_respuestas_historicos ($fields_pdo) VALUES ($values_pdo);";
				$insert_cuestionario_historico=$conexion->query($insert_cuestionario_historico);
				$num=$conexion->affected_rows;
				if(!$insert_cuestionario_historico || $num=0){
					$success=false;
					echo "ERROR insert_cuestionario_historico"; 
					var_dump($conexion->error);
				}
			}

			///verificamos si esta autorizado los correos desde la api
			$api_mailingDatos = api_mailingDatos();
			if($api_mailingDatos['status'] == 1){
				include __DIR__."/../functions/campanas_mailing_cartografias.php";
				include __DIR__."/../functions/campanas_mailing_tipos_ciudadanos.php";
				include __DIR__."/../functions/campanas_mailing_tipos_categorias_ciudadanos.php";
				// todos los correos activos
				$id_encuesta = $_POST["seccion_ine_ciudadano_encuesta"][0]['id_encuesta'];
				$id_seccion_ine_ciudadano = $_POST["seccion_ine_ciudadano_encuesta"][0]['id_seccion_ine_ciudadano'];
				$sqlCamMailing = '
				SELECT 
					cm.id,
					cm.tipo,
					cm.nombre,
					scm.status correo_status,
					cm.status campana_status,
					cm.envio envio_status,
				    cme.id_encuesta
				FROM campanas_mailing cm 
				LEFT JOIN correos_mailing scm
				ON cm.id_correo_mailing = scm.id
				LEFT JOIN campanas_mailing_encuestas cme
				ON cm.id = cme.id_campana_mailing
				WHERE cm.status = 1 AND cm.tipo=3 AND  scm.status =1  AND cme.id_encuesta = "'.$id_encuesta.'";';
				$resultCamMailing = $conexion->query($sqlCamMailing);  
				while($rowCampMailing=$resultCamMailing->fetch_assoc()){
					$id_campana_mailing = $rowCampMailing['id'];
					$tipo = $rowCampMailing['tipo'];
					$campana_mailing_cartografiaDatos=campana_mailing_cartografiaDatos('',$id_campana_mailing);
					$campanas_mailing_tipos_ciudadanosIdDatos=campanas_mailing_tipos_ciudadanosIdDatos('',$id_campana_mailing); 
					$campanas_mailing_tipos_categorias_ciudadanosIdDatos=campanas_mailing_tipos_categorias_ciudadanosIdDatos('',$id_campana_mailing);

					$sql="
						INSERT INTO secciones_ine_ciudadanos_campanas_mailing_programadas
						(id_seccion_ine_ciudadano, id_seccion_ine, id_distrito_local, id_distrito_federal, id_estado, id_municipio, id_campana_mailing, id_campana_mailing_cuerpo, id_campana_mailing_programada, status, fechaR, codigo_plataforma, codigo_seccion_ine_ciudadano, identificador, asunto, cuerpo, fecha_registro, hora_registro, fecha_hora_registro,tipo)
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
						'{$tipo}' tipo 
						FROM secciones_ine_ciudadanos sic
						WHERE 1 
						AND sic.id = '".$id_seccion_ine_ciudadano."'
					";

					if($campana_mailing_cartografiaDatos['tipo_cartografia']=='municipios'){
						$sql .= ' AND sic.id_municipio =  '.$campana_mailing_cartografiaDatos['id_tipo_cartografia'];
					}elseif ($campana_mailing_cartografiaDatos['tipo_cartografia']=='distritos_locales') {
						$sql .= ' AND sic.id_distrito_local =  '.$campana_mailing_cartografiaDatos['id_tipo_cartografia'];
					}elseif ($campana_mailing_cartografiaDatos['tipo_cartografia']=='distritos_federales') {
						$sql .= ' AND sic.id_distrito_federal =  '.$campana_mailing_cartografiaDatos['id_tipo_cartografia'];
					}elseif ($campana_mailing_cartografiaDatos['tipo_cartografia']=='secciones_ine') {
						$sql .= ' AND sic.id_seccion_ine =  '.$campana_mailing_cartografiaDatos['id_tipo_cartografia'];
					}else{}

					if(!empty($campanas_mailing_tipos_ciudadanosIdDatos)){
						foreach ($campanas_mailing_tipos_ciudadanosIdDatos as $key => $value) {
							$tipos_ciudadanos[]=$value['id_tipo_ciudadano'];
						}
						if(!empty($tipos_ciudadanos)){
							$id_tipos_ciudadanos = "'".implode("','", $tipos_ciudadanos)."'";
							$sql .=" AND sic.id_tipo_ciudadano IN ({$id_tipos_ciudadanos}) ";
						}
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
				}
			}

			///verificamos si esta autorizado los correos desde la api
			$api_smsDatos = api_smsDatos();
			if($api_smsDatos['status'] == 1){
				include __DIR__."/../functions/campanas_sms_cartografias.php";
				include __DIR__."/../functions/campanas_sms_tipos_ciudadanos.php";
				include __DIR__."/../functions/campanas_sms_tipos_categorias_ciudadanos.php";
				// todos los correos activos
				$id_encuesta = $_POST["seccion_ine_ciudadano_encuesta"][0]['id_encuesta'];
				$id_seccion_ine_ciudadano = $_POST["seccion_ine_ciudadano_encuesta"][0]['id_seccion_ine_ciudadano'];
				$sqlCamSMS = '
					SELECT 
						cm.id,
						cm.tipo,
						cm.nombre,
						scm.status api_status,
						cm.status campana_status,
						cm.envio envio_status,
					    cme.id_encuesta
					FROM campanas_sms cm 
					LEFT JOIN api_sms scm
					ON cm.id_api_sms = scm.id
					LEFT JOIN campanas_sms_encuestas cme
					ON cm.id = cme.id_campana_sms
					WHERE cm.status = 1 AND cm.tipo=3 AND  scm.status =1  AND cme.id_encuesta = "'.$id_encuesta.'";';

				$resultCamSMS = $conexion->query($sqlCamSMS);  
				while($rowCampSMS=$resultCamSMS->fetch_assoc()){
					$id_campana_sms = $rowCampSMS['id'];
					$tipo = $rowCampSMS['tipo'];
					$campana_sms_cartografiaDatos=campana_sms_cartografiaDatos('',$id_campana_sms);
					$campanas_sms_tipos_ciudadanosIdDatos=campanas_sms_tipos_ciudadanosIdDatos('',$id_campana_sms); 
					$campanas_sms_tipos_categorias_ciudadanosIdDatos=campanas_sms_tipos_categorias_ciudadanosIdDatos('',$id_campana_sms);

					$sql="
						INSERT INTO secciones_ine_ciudadanos_campanas_sms_programadas
						(id_seccion_ine_ciudadano, id_seccion_ine, id_distrito_local, id_distrito_federal, id_estado, id_municipio, id_campana_sms, id_campana_sms_cuerpo, id_campana_sms_programada, status, fechaR, codigo_plataforma, codigo_seccion_ine_ciudadano, identificador, asunto, cuerpo, fecha_registro, hora_registro, fecha_hora_registro,tipo)
						SELECT 
						sic.id id_seccion_ine_ciudadano,
						sic.id_seccion_ine,
						sic.id_distrito_local,
						sic.id_distrito_federal,
						sic.id_estado,
						sic.id_municipio,
						/*sic.id_campana_sms,*/
						(SELECT cm.id from campanas_sms cm limit 1) id_campana_sms,
						/*sic.id_campana_sms_cuerpo,*/
						(SELECT cmp.id from campanas_sms_cuerpos cmp limit 1) id_campana_sms_cuerpo,
						NULL id_campana_sms_programada,
						'0' status,
						'{$fechaH}' fechaR,
						sic.codigo_plataforma,
						sic.codigo_seccion_ine_ciudadano,
						'1' identificador,
						/*sic.asunto,*/
						/*(SELECT cmp.asunto from campanas_sms_cuerpos cmp limit 1) asunto,*/
						NULL asunto,
						/*sic.cuerpo,*/
						/*(SELECT cmp.cuerpo from campanas_sms_cuerpos cmp limit 1) cuerpo,*/
						NULL cuerpo,
						'{$fechaSF}' fecha_registro,
						'{$fechaSH}' hora_registro,
						'{$fechaH}' fecha_hora_registro,
						'{$tipo}' tipo 
						FROM secciones_ine_ciudadanos sic
						WHERE 1 
						AND sic.id = '".$id_seccion_ine_ciudadano."'
					";

					if($campana_sms_cartografiaDatos['tipo_cartografia']=='municipios'){
						$sql .= ' AND sic.id_municipio =  '.$campana_sms_cartografiaDatos['id_tipo_cartografia'];
					}elseif ($campana_sms_cartografiaDatos['tipo_cartografia']=='distritos_locales') {
						$sql .= ' AND sic.id_distrito_local =  '.$campana_sms_cartografiaDatos['id_tipo_cartografia'];
					}elseif ($campana_sms_cartografiaDatos['tipo_cartografia']=='distritos_federales') {
						$sql .= ' AND sic.id_distrito_federal =  '.$campana_sms_cartografiaDatos['id_tipo_cartografia'];
					}elseif ($campana_sms_cartografiaDatos['tipo_cartografia']=='secciones_ine') {
						$sql .= ' AND sic.id_seccion_ine =  '.$campana_sms_cartografiaDatos['id_tipo_cartografia'];
					}else{}

					if(!empty($campanas_sms_tipos_ciudadanosIdDatos)){
						foreach ($campanas_sms_tipos_ciudadanosIdDatos as $key => $value) {
							$tipos_ciudadanos[]=$value['id_tipo_ciudadano'];
						}
						if(!empty($tipos_ciudadanos)){
							$id_tipos_ciudadanos = "'".implode("','", $tipos_ciudadanos)."'";
							$sql .=" AND sic.id_tipo_ciudadano IN ({$id_tipos_ciudadanos}) ";
						}
					}
					$sql .=";";
					$conexion->autocommit(FALSE);
					$update_secciones_ine_ciudadanos_campanas_sms_programadas=$conexion->query($sql);
					$num=$conexion->affected_rows;
					if(!$update_secciones_ine_ciudadanos_campanas_sms_programadas || $num=0){
						$success=false;
						echo "<br>";
						echo "ERROR update_secciones_ine_ciudadanos_campanas_sms_programadas"; 
						var_dump($conexion->error);
					}
				}
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_ciudadanos_encuestas',$id,'Insert','',$fechaH);
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
		}else{
			//update
			$id = $_POST['seccion_ine_ciudadano_encuesta'][0]['id'];
			$success = true;
			if( registrosCompara("secciones_ine_ciudadanos_encuestas",$_POST['seccion_ine_ciudadano_encuesta'][0],1) ){
				$success_count = $success_count +1;
				$_POST["seccion_ine_ciudadano_encuesta"][0]['fechaR'] = $fechaH;
				$_POST["seccion_ine_ciudadano_encuesta"][0]['codigo_plataforma']=$codigo_plataforma;
				foreach($_POST['seccion_ine_ciudadano_encuesta'] as $keyPrincipal => $atributos) {
					foreach ($atributos as $key => $value) {
						if($key !='id'){
							$valueSets[] = $key . " = '" . $value . "'";
						}else{
							$id=$value;
						}
					}
				}

				$update_seccione_ine_ciudadano_encuesta = "UPDATE secciones_ine_ciudadanos_encuestas SET ". join(",",$valueSets) . " WHERE id=".$id;
				$conexion->autocommit(FALSE);
				$update_seccione_ine_ciudadano_encuesta=$conexion->query($update_seccione_ine_ciudadano_encuesta);
				$num=$conexion->affected_rows;
				if(!$update_seccione_ine_ciudadano_encuesta || $num=0){
					$success=false;
					echo "<br>";
					echo "ERROR update_seccione_ine_ciudadano_encuesta"; 
					var_dump($conexion->error);
				}

				$_POST["seccion_ine_ciudadano_encuesta"][0]['id_seccion_ine_ciudadano_encuesta'] = $id;
				unset($_POST["seccion_ine_ciudadano_encuesta"][0]['id']); 
				$fields_pdo = "`".implode('`,`', array_keys($_POST["seccion_ine_ciudadano_encuesta"][0]))."`";
				$values_pdo = "'".implode("','", $_POST["seccion_ine_ciudadano_encuesta"][0])."'";
				$insert_distritos_locales_parametros_historicos= "INSERT INTO secciones_ine_ciudadanos_encuestas_historicos ($fields_pdo) VALUES ($values_pdo);";
				$insert_distritos_locales_parametros_historicos=$conexion->query($insert_distritos_locales_parametros_historicos);
				$num=$conexion->affected_rows;
				if(!$insert_distritos_locales_parametros_historicos || $num=0){
					$success=false;
					echo "ERROR insert_distritos_locales_parametros_historicos"; 
					var_dump($conexion->error);
				}
			}

			$id_seccion_ine_ciudadano = $_POST["seccion_ine_ciudadano_encuesta"][0]['id_seccion_ine_ciudadano'];
			$seccion_ine_ciudadanoDatos = seccion_ine_ciudadanoDatos($id_seccion_ine_ciudadano);
			$success_count = '';
			foreach ($_POST["cuestionario"] as $key => $value) {
				if($value['id']==''){
					//echo "Insertar";
					//inserta
					$success_count = $success_count +1;
					unset($value['id']);
					$value['id_seccion_ine_ciudadano'] = $_POST["seccion_ine_ciudadano_encuesta"][0]['id_seccion_ine_ciudadano'];
					$value['fecha'] = $_POST["seccion_ine_ciudadano_encuesta"][0]['fecha'];
					$value['hora'] = $_POST["seccion_ine_ciudadano_encuesta"][0]['hora'];
					$value['fecha_hora'] = $_POST["seccion_ine_ciudadano_encuesta"][0]['fecha'].' '.$_POST["seccion_ine_ciudadano_encuesta"][0]['hora'];
					$value['fechaR'] = $fechaH;
					$value['codigo_plataforma']=$codigo_plataforma;
					$value['id_seccion_ine'] = $seccion_ine_ciudadanoDatos['id_seccion_ine'];
					$value['id_distrito_local'] = $seccion_ine_ciudadanoDatos['id_distrito_local'];
					$value['id_distrito_federal'] = $seccion_ine_ciudadanoDatos['id_distrito_federal'];
					$value['id_municipio'] = $seccion_ine_ciudadanoDatos['id_municipio'];
					$value['id_seccion_ine_ciudadano_encuesta'] = $id;

					$fields_pdo = "`".implode('`,`', array_keys($value))."`";
					$values_pdo = "'".implode("','", $value)."'";
					$insert_cuestionario= "INSERT INTO secciones_ine_ciudadanos_encuestas_respuestas ($fields_pdo) VALUES ($values_pdo);";
					$conexion->autocommit(FALSE);
					$insert_cuestionario=$conexion->query($insert_cuestionario);
					$num=$conexion->affected_rows;
					if(!$insert_cuestionario || $num=0){
						$success=false;
						echo "ERROR insert_cuestionario"; 
						var_dump($conexion->error);
					}
					$value['id_seccion_ine_ciudadano_encuesta_respuesta']=$conexion->insert_id;
					$fields_pdo = "`".implode('`,`', array_keys($value))."`";
					$values_pdo = "'".implode("','", $value)."'";
					$insert_cuestionario_historico= "INSERT INTO secciones_ine_ciudadanos_encuestas_respuestas_historicos ($fields_pdo) VALUES ($values_pdo);";
					$insert_cuestionario_historico=$conexion->query($insert_cuestionario_historico);
					$num=$conexion->affected_rows;
					if(!$insert_cuestionario_historico || $num=0){
						$success=false;
						echo "ERROR insert_cuestionario_historico"; 
						var_dump($conexion->error);
					}
				}
				if($value['id']!='' && $value['respuesta']=='0'){
					//eliminamos
					//echo "Eliminado";
					$success_count = $success_count +1;
					$seccione_ine_ciudadano_encuesta_respuesta = $value['id'];
					$delete_cuestionario_historico = "DELETE FROM secciones_ine_ciudadanos_encuestas_respuestas  WHERE  id='$seccione_ine_ciudadano_encuesta_respuesta' ";
					$conexion->autocommit(FALSE);
					$delete_cuestionario_historico=$conexion->query($delete_cuestionario_historico);
					$num=$conexion->affected_rows;
					if(!$delete_cuestionario_historico || $num=0){
						$success=false;
						echo "ERROR delete_cuestionario_historico"; 
						var_dump($conexion->error);
					}

				}
				if($value['id']!='' && $value['tipo']=='text'){
					//eliminamos
					//echo "Eliminado";
					unset($value['tipo']);
					if( registrosCompara("secciones_ine_ciudadanos_encuestas_respuestas",$value,1) ){
						unset($valueSets);

						$success_count = $success_count +1;
						$value['id_seccion_ine_ciudadano'] = $_POST["seccion_ine_ciudadano_encuesta"][0]['id_seccion_ine_ciudadano'];
						$value['fecha'] = $_POST["seccion_ine_ciudadano_encuesta"][0]['fecha'];
						$value['hora'] = $_POST["seccion_ine_ciudadano_encuesta"][0]['hora'];
						$value['fecha_hora'] = $_POST["seccion_ine_ciudadano_encuesta"][0]['fecha'].' '.$_POST["seccion_ine_ciudadano_encuesta"][0]['hora'];
						$value['fechaR'] = $fechaH;
						$value['codigo_plataforma']=$codigo_plataforma;
						$value['id_seccion_ine'] = $seccion_ine_ciudadanoDatos['id_seccion_ine'];
						$value['id_distrito_local'] = $seccion_ine_ciudadanoDatos['id_distrito_local'];
						$value['id_distrito_federal'] = $seccion_ine_ciudadanoDatos['id_distrito_federal'];
						$value['id_municipio'] = $seccion_ine_ciudadanoDatos['id_municipio'];
						$value['id_seccion_ine_ciudadano_encuesta'] = $_POST["seccion_ine_ciudadano_encuesta"][0]['id'];


						foreach ($value as $keyT => $valueT) {
							if($keyT !='id'){
								$valueSets[] = $keyT . " = '" . $valueT . "'";
							}else{
								$id=$valueT;
							}
						}

						$update_cuestionario = "UPDATE secciones_ine_ciudadanos_encuestas_respuestas SET ". join(",",$valueSets) . " WHERE id=".$id;
						$conexion->autocommit(FALSE);
						$update_cuestionario=$conexion->query($update_cuestionario);
						$num=$conexion->affected_rows;
						if(!$update_cuestionario || $num=0){
							$success=false;
							echo "<br>";
							echo "ERROR update_cuestionario"; 
							var_dump($conexion->error);
						}

						$value['id_seccion_ine_ciudadano_encuesta_respuesta']=$id;
						unset($value['id']);
						$fields_pdo = "`".implode('`,`', array_keys($value))."`";
						$values_pdo = "'".implode("','", $value)."'";
						$insert_cuestionario_historico= "INSERT INTO secciones_ine_ciudadanos_encuestas_respuestas_historicos ($fields_pdo) VALUES ($values_pdo);";
						$insert_cuestionario_historico=$conexion->query($insert_cuestionario_historico);
						$num=$conexion->affected_rows;
						if(!$insert_cuestionario_historico || $num=0){
							$success=false;
							echo "ERROR insert_cuestionario_historico"; 
							var_dump($conexion->error);
						}
					} 
				}
			}


			if($success_count>0){
				if($success==true){
					$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_ciudadanos_encuestas',$_POST["seccion_ine_ciudadano_encuesta"][0]['id'],'Insert','',$fechaH);
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
			}else{
				echo "SI";
			} 
		}
	}

 