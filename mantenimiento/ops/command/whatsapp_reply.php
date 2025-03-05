<?php
	if(!empty($_POST)){
		header("content-type: text/xml");
		echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		
		include __DIR__."/../db.php";




		$body = $_POST['Body'];
		$numero = $_POST['WaId'];
		$whatsapp = substr($numero, 3);

		/*
		$dir = __DIR__.'/reply_'.date('H:i_s').'.txt';
		$fp = fopen($dir, 'w');
		fwrite($fp, print_r($_POST,TRUE));
		fclose($fp);
		*/

		$scriptSQL=" SELECT * FROM api_whatsapp LIMIT 1";
		$resultado = $conexion->query($scriptSQL);
		$row=$resultado->fetch_assoc();
		// 30 * 30
		if($row['status'] == 0){
			echo "offline :( api_whatsapp";
			die;
		}

		$url = $row['url'];
		$id_api_whatsapp = $row['id'];
		
		$tiempo_espera_segundos = $row['tiempo_espera_segundos'];
		$mensajes_a_enviar = $row['mensajes_a_enviar'];
		
		$account_sid = $row['account_sid'];
		$auth_token = $row['auth_token'];
		$de = $row['de'];
		$statusCallback = $row['statusCallback_replys'];

		//verificar si esta activo el envio de correos
		$scriptSQL=" SELECT whatsapp FROM switch_operaciones LIMIT 1";
		$resultado = $conexion->query($scriptSQL);
		$row=$resultado->fetch_assoc();
		if($row['whatsapp'] ==0){
			echo "offline switch_operaciones";
			die;
		}


		$sql="SELECT * FROM a_chat_ciudadano WHERE 1 = 1 ";
		$result = $conexion->query($sql);  
		while($row=$result->fetch_assoc()){
			$preguntas[]=$row;
			//$palabras[$row['id']]=$row['frase'];
		}



		$body= strtolower($body);

		//$clave = array_search($body, $palabras);
		foreach ($preguntas as $key => $value) {
			$findme = $value['frase'];
			//var_dump($findme);
			//echo "------";
			$pos = strpos($body, $findme);
			//var_dump($pos);
			//echo "<br>";
			if ($pos === false) { 
			}else{
				$findme.'---';
				//echo $id_respuestas = $value['id_respuestas'];
				$id_respuestas = $value['id_respuestas'];
				//echo "<br>";
			}
		}

		$id_respuesta_array = explode( ',', $id_respuestas);
		$clave_respuesta = array_rand($id_respuesta_array, 1);
		$id_respuesta = $id_respuesta_array[$clave_respuesta];

		$sql="SELECT * FROM a_chat_sistema WHERE 1 = 1 AND id='{$id_respuesta}' ";
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$datos=$row;


		$sql="SELECT c.nombre,(SELECT s.numero FROM secciones_ine s WHERE s.id= c.id_seccion_ine) seccion,c.sexo,c.id  FROM secciones_ine_ciudadanos c WHERE  whatsapp='{$whatsapp}'  LIMIT 1 ";
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$ciudadano=$row;
		$id_seccion_ine_ciudadano = $row['id'];

		$timeOfDay = date('H');
		if($timeOfDay >= '00' && $timeOfDay <= '11' ){
			$tipo_saludo ='buenos días';
		}elseif($timeOfDay >= '12' && $timeOfDay <= '16' ){
			$tipo_saludo ='buenos tardes';
		}else{
			$tipo_saludo ='buenos noches';
		}

		if($ciudadano['sexo']=='Hombre'){
			$tipo_tranquilo_sexo ='tranquilo';
		}else{
			$tipo_tranquilo_sexo ='tranquila';
		}

		$acciones = $datos['acciones'];

		$acciones_array = explode( ',', $acciones);



		$ciudadano_data = array(
			"XX__NOMBRE__XX" => $ciudadano['nombre'], 
			"XX__SECCION__XX" => $ciudadano['seccion'], 
			"XX__TIPO_SALUDO__XX" => $tipo_saludo,
			"XX__TIPO_TRANQUILO_SEXO__XX" => $tipo_tranquilo_sexo,
		);

		$respuestas  = $datos['respuesta'];
		$bodyHTML = strtr($respuestas, array_merge($ciudadano_data));

		foreach ($acciones_array as $key => $value) {
			if($value=='buscar_nombre_invitar'){
				if($ciudadano['nombre']==''){
					$bodyHTML .=' Me guisaría invitarte a ser parte de mi campaña, manda un mensaje a este numero 9991828212 para que recibas mas información sobre mi y las secciones que estere visitando';
				}
			}
			if($value=='accion_verificar_seccion'){
				if($ciudadano['nombre']==''){
					$bodyHTML = '';
					$bodyHTML .=' No tenemos información de quien seas ya que este whatsapp no esta registrado con nosotros, por favor envia un mensaje a 9991828212 para poder registrarte congusto.';
				}
			}
		}

		



		


		if(empty($datos)){
			$bodyHTML = 'no entendí lo que me quisiste decir, pero en que te puedo ayudar';
		}


		//// lo que mando el ciudadano
		//// api_whatsapp_mensajes
		$api_whatsapp_mensaje = $_POST;
		if($id_seccion_ine_ciudadano !='' ){
			$api_whatsapp_mensaje['id_seccion_ine_ciudadano'] = $id_seccion_ine_ciudadano;
		}
		$api_whatsapp_mensaje['tipo'] = 2;
		$api_whatsapp_mensaje['fechaR'] = $fechaH;
		//$api_whatsapp_mensaje['SmsMessageSid'] = 'SMdc792ad2e79774bd779d5a4ee20b678b';
		$api_whatsapp_mensaje['sid'] = $api_whatsapp_mensaje['SmsMessageSid'];
		//$api_whatsapp_mensaje['NumMedia'] = '0';
		$api_whatsapp_mensaje['whatsapp'] = $whatsapp;
		//$api_whatsapp_mensaje['ProfileName'] = 'Ideas AB';
		//$api_whatsapp_mensaje['SmsSid'] = 'SMdc792ad2e79774bd779d5a4ee20b678b';
		//$api_whatsapp_mensaje['WaId'] = '5219991564188';
		//$api_whatsapp_mensaje['SmsStatus'] = 'received';
		//$api_whatsapp_mensaje['Body'] = 'Hola';
		//$api_whatsapp_mensaje['To'] = 'whatsapp:+14155238886';
		//$api_whatsapp_mensaje['NumSegments'] = '1';
		//$api_whatsapp_mensaje['MessageSid'] = 'SMdc792ad2e79774bd779d5a4ee20b678b';
		//$api_whatsapp_mensaje['AccountSid'] = 'AC92d8f7409e060a2bd8e92fa6e4ba434c';
		//$api_whatsapp_mensaje['From'] = 'whatsapp:+5219991564188';
		//$api_whatsapp_mensaje['ApiVersion'] = '2010-04-01';

		$api_whatsapp_mensaje['fecha_hora_envio'] = $fechaH;
		$api_whatsapp_mensaje['fecha_envio'] = $fechaSF;
		$api_whatsapp_mensaje['hora_envio'] = $fechaSH;
		$api_whatsapp_mensaje['fecha_hora_entrega'] = $fechaH;
		$api_whatsapp_mensaje['fecha_entrega'] = $fechaSF;
		$api_whatsapp_mensaje['hora_entrega'] = $fechaSH;
		$api_whatsapp_mensaje['fecha_hora_leido'] = $fechaH;
		$api_whatsapp_mensaje['fecha_leido'] = $fechaSF;
		$api_whatsapp_mensaje['hora_leido'] = $fechaSH;

		unset($api_whatsapp_mensaje['WaId']);
		unset($api_whatsapp_mensaje['SmsMessageSid']);
		unset($api_whatsapp_mensaje['NumMedia']);
		unset($api_whatsapp_mensaje['SmsSid']);
		unset($api_whatsapp_mensaje['SmsStatus']);
		unset($api_whatsapp_mensaje['To']);
		unset($api_whatsapp_mensaje['NumSegments']);
		unset($api_whatsapp_mensaje['MessageSid']);
		unset($api_whatsapp_mensaje['AccountSid']);
		unset($api_whatsapp_mensaje['From']);
		unset($api_whatsapp_mensaje['ApiVersion']);

		$fields_pdo = "`".implode('`,`', array_keys($api_whatsapp_mensaje))."`";
		$values_pdo = "'".implode("','", $api_whatsapp_mensaje)."'";
		$insert_api_whatsapp_mensaje= "INSERT INTO api_whatsapp_mensajes ($fields_pdo) VALUES ($values_pdo);";
		$insert_api_whatsapp_mensaje=$conexion->query($insert_api_whatsapp_mensaje);
		$num=$conexion->affected_rows;
		if(!$insert_api_whatsapp_mensaje || $num=0){
			$success=false;
			echo "ERROR insert_api_whatsapp_mensaje"; 
			var_dump($conexion->error);
		}

		//$dir = __DIR__.'/reply_'.date('H:i_s').'.txt';
		//$fp = fopen($dir, 'w');
		//fwrite($fp, print_r($conexion->error,TRUE));
		//fclose($fp);
		
		//echo "<pre>";
		//print_r($api_whatsapp_mensaje);
		//echo "</pre>";
		unset($api_whatsapp_mensaje);

		////////
		////////envio reply
		$api_whatsapp_envios['id_api_whatsapp'] = $id_api_whatsapp;
		if($id_seccion_ine_ciudadano != ''){
			$api_whatsapp_envios['id_seccion_ine_ciudadano'] = $id_seccion_ine_ciudadano;
		}
		$api_whatsapp_envios['fechaR'] = $fechaH;
		$api_whatsapp_envios['date_created'] = date('D, d M Y H:i:s O');
		$api_whatsapp_envios['date_updated'] = date('D, d M Y H:i:s O');
		$api_whatsapp_envios['date_sent'];
		$api_whatsapp_envios['account_sid'] = $account_sid;
		$api_whatsapp_envios['to'] = 'whatsapp:'.$de;
		$api_whatsapp_envios['from'] = 'whatsapp:+521'.$whatsapp;
		$api_whatsapp_envios['messaging_service_sid'];
		$api_whatsapp_envios['body'] = $bodyHTML;
		$api_whatsapp_envios['status'] = 'queued';
		$api_whatsapp_envios['num_segments'] = 1;
		$api_whatsapp_envios['num_media'];
		$api_whatsapp_envios['direction'] = 'Reply';
		$api_whatsapp_envios['api_version'] = '2010-04-01';
		$api_whatsapp_envios['price'];
		$api_whatsapp_envios['price_unit'];
		$api_whatsapp_envios['error_code'];
		$api_whatsapp_envios['error_message'];
		$api_whatsapp_envios['uri'];
		$api_whatsapp_envios['subresource_uris'];
		$api_whatsapp_envios['tipo'] = '1';
		// 0 plantilla // 1 reply /// 2 ciudadano
		$api_whatsapp_envios['whatsapp'] = $whatsapp;
		$mensaje_proveedor  ='sent';

		$fields_pdo = "`".implode('`,`', array_keys($api_whatsapp_envios))."`";
		$values_pdo = "'".implode("','", $api_whatsapp_envios)."'";
		$insert_api_whatsapp_envios= "INSERT INTO api_whatsapp_envios ($fields_pdo) VALUES ($values_pdo);";
		$insert_api_whatsapp_envios=$conexion->query($insert_api_whatsapp_envios);
		$num=$conexion->affected_rows;
		if(!$insert_api_whatsapp_envios || $num=0){
			$success=false;
			echo "ERROR insert_api_whatsapp_envios"; 
			var_dump($conexion->error);
		}
		$id_whatsapp_envio = $conexion->insert_id;

		//echo "<pre>";
		//print_r($api_whatsapp_envios);
		//echo "</pre>";

		$api_whatsapp_mensaje = $_POST;
		if($id_seccion_ine_ciudadano!=''){
			$api_whatsapp_mensaje['id_seccion_ine_ciudadano'] = $id_seccion_ine_ciudadano;
		}
		$api_whatsapp_mensaje['tipo'] = 1;
		$api_whatsapp_mensaje['fechaR'] = $fechaH;
		$api_whatsapp_mensaje['whatsapp'] = $whatsapp;
		$api_whatsapp_mensaje['ProfileName'] = 'Sistema';
		$api_whatsapp_mensaje['Body'] = $bodyHTML;

		$api_whatsapp_mensaje['fecha_hora_envio'] = $fechaH;
		$api_whatsapp_mensaje['fecha_envio'] = $fechaSF;
		$api_whatsapp_mensaje['hora_envio'] = $fechaSH;
		$api_whatsapp_mensaje['id_whatsapp_envio'] = $id_whatsapp_envio;
		unset($api_whatsapp_mensaje['WaId']);
		unset($api_whatsapp_mensaje['SmsMessageSid']);
		unset($api_whatsapp_mensaje['NumMedia']);
		unset($api_whatsapp_mensaje['SmsSid']);
		unset($api_whatsapp_mensaje['SmsStatus']);
		unset($api_whatsapp_mensaje['To']);
		unset($api_whatsapp_mensaje['NumSegments']);
		unset($api_whatsapp_mensaje['MessageSid']);
		unset($api_whatsapp_mensaje['AccountSid']);
		unset($api_whatsapp_mensaje['From']);
		unset($api_whatsapp_mensaje['ApiVersion']);


		$fields_pdo = "`".implode('`,`', array_keys($api_whatsapp_mensaje))."`";
		$values_pdo = "'".implode("','", $api_whatsapp_mensaje)."'";
		$insert_api_whatsapp_mensaje= "INSERT INTO api_whatsapp_mensajes ($fields_pdo) VALUES ($values_pdo);";
		$insert_api_whatsapp_mensaje=$conexion->query($insert_api_whatsapp_mensaje);
		$num=$conexion->affected_rows;
		if(!$insert_api_whatsapp_mensaje || $num=0){
			$success=false;
			echo "ERROR insert_api_whatsapp_mensaje"; 
			var_dump($conexion->error);
		}
		//unset($api_whatsapp_mensaje);
		//echo "<pre>";
		//print_r($api_whatsapp_mensaje);
		//echo "</pre>";
		?>
		<Response>
			<Message action="<?= $statusCallback ?>" method="POST">
				<?= $bodyHTML ?>
			</Message>
		</Response>
		<!-- envio con media
		<Response>
			<Message>
				<Body>Store Location: 123 Easy St.</Body>
				<Media>https://demo.twilio.com/owl.png</Media>
			</Message>
		</Response>
		-->
		<?php
	}else{
		echo 'offline';
	}
?>