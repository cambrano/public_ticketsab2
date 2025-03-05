<?php
	/*
	$_POST =  array(
		'EventType' => 'READ',
		'SmsSid' => 'MM596d7ff2c5bc41d59a6b7e755e909764',
		'SmsStatus' => 'read',
		'MessageStatus' => 'read',
		'ChannelToAddress' => '+521999156XXXX',
		'To' => 'whatsapp:+5219991564188',
		'ChannelPrefix' => 'whatsapp',
		'MessageSid' => 'MM596d7ff2c5bc41d59a6b7e755e909764',
		'AccountSid' => 'AC92d8f7409e060a2bd8e92fa6e4ba434c',
		'From' => 'whatsapp:+14155238886',
		'ApiVersion' => '2010-04-01',
		'ChannelInstallSid' => 'XEabc0dbf0eadce491e37a9dbeb6316f62',
	);
	*/
	/*
	$_POST['EventType'] = 'DELIVERED';
	$_POST['SmsSid'] = 'MM0594c5b7d76c571bd8908d17aa6a63e2';
	$_POST['SmsStatus'] = 'delivered';
	$_POST['MessageStatus'] = 'delivered';
	$_POST['ChannelToAddress'] = '+521999156XXXX';
	$_POST['To'] = 'whatsapp:+5219991564188';
	$_POST['ChannelPrefix'] = 'whatsapp';
	$_POST['MessageSid'] = 'MM0594c5b7d76c571bd8908d17aa6a63e2';
	$_POST['AccountSid'] = 'AC92d8f7409e060a2bd8e92fa6e4ba434c';
	$_POST['From'] = 'whatsapp:+14155238886';
	$_POST['ApiVersion'] = '2010-04-01';
	$_POST['ChannelInstallSid'] = 'XEabc0dbf0eadce491e37a9dbeb6316f62';

	Cuando envias textos/ gps /
	*/
	if(!empty($_POST)){
		include __DIR__."/../db.php";

		/*
		$dir = __DIR__.'/reply'.date('H:i_s').'.txt';
		$fp = fopen($dir, 'w');
		fwrite($fp, print_r($_POST,TRUE));
		fclose($fp);
		*/


		if($_POST['EventType']==''){
			$_POST['EventType'] = strtoupper($_POST['SmsStatus']);
		}
		$whatsapp_status = $_POST;
		$status = $whatsapp_status['SmsStatus'];
		$mensaje_proveedor = $_POST['MessageStatus'];
		$whatsapp = substr($_POST['To'], 13);
		if($status =='sent'){

			$SmsSid = $_POST['SmsSid'];
			$status = $whatsapp_status['SmsStatus'];
			$mensaje_proveedor = $whatsapp_status['MessageStatus'];
			$scriptSQL=" SELECT * FROM api_whatsapp_mensajes WHERE sid IS NULL AND tipo=1 AND whatsapp='{$whatsapp}' ";
			$resultado = $conexion->query($scriptSQL);
			$row=$resultado->fetch_assoc();
			$tipo = $row['tipo'];
			$id_whatsapp_envio = $row['id_whatsapp_envio'];
			$id_whatsapp_mensaje = $row['id'];

			$update_whatsapp_mensaje ='
				UPDATE api_whatsapp_mensajes 
					SET

						sid="'.$_POST['SmsSid'].'" ,
						fecha_envio="'.$fechaSF.'" ,
						hora_envio="'.$fechaSH.'" ,
						fecha_hora_envio="'.$fechaH.'" 
					WHERE ( id = "'.$id_whatsapp_mensaje.'" );';
			$update_whatsapp_mensaje=$conexion->query($update_whatsapp_mensaje);
			if(!$update_whatsapp_mensaje || $num=0){
				$success=false;
				echo "ERROR update_whatsapp_mensaje"; 
				var_dump($conexion->error);
			}

			$update_whatsapp_envio ='
				UPDATE api_whatsapp_envios 
					SET
						sid="'.$_POST['SmsSid'].'" 
					WHERE ( id = "'.$id_whatsapp_envio.'" );';
			$update_whatsapp_envio=$conexion->query($update_whatsapp_envio);
			if(!$update_whatsapp_envio || $num=0){
				$success=false;
				echo "ERROR update_whatsapp_envio"; 
				var_dump($conexion->error);
			}

		}elseif ($status=='delivered') {
			//// ENTREGADO
			$SmsSid = $_POST['SmsSid'];
			$status = $whatsapp_status['SmsStatus'];
			$mensaje_proveedor = $whatsapp_status['MessageStatus'];
			$scriptSQL=" SELECT * FROM api_whatsapp_mensajes WHERE tipo=1 AND whatsapp='{$whatsapp}' AND sid = '{$SmsSid}' ";
			$resultado = $conexion->query($scriptSQL);
			$row=$resultado->fetch_assoc();
			$tipo = $row['tipo'];
			$id_whatsapp_envio = $row['id_whatsapp_envio'];
			$id_whatsapp_mensaje = $row['id'];

			$update_whatsapp_mensaje ='
				UPDATE api_whatsapp_mensajes 
					SET 
						fecha_entrega="'.$fechaSF.'" ,
						hora_entrega="'.$fechaSH.'" ,
						fecha_hora_entrega="'.$fechaH.'" 
					WHERE ( id = "'.$id_whatsapp_mensaje.'" );';
			$update_whatsapp_mensaje=$conexion->query($update_whatsapp_mensaje);
			if(!$update_whatsapp_mensaje || $num=0){
				$success=false;
				echo "ERROR update_whatsapp_mensaje"; 
				var_dump($conexion->error);
			}
		}else{
			//// LEIDO
			$SmsSid = $_POST['SmsSid'];
			$status = $whatsapp_status['SmsStatus'];
			$mensaje_proveedor = $whatsapp_status['MessageStatus'];
			$scriptSQL=" SELECT * FROM api_whatsapp_mensajes WHERE tipo=1 AND whatsapp='{$whatsapp}' AND sid = '{$SmsSid}' ";
			$resultado = $conexion->query($scriptSQL);
			$row=$resultado->fetch_assoc();
			$tipo = $row['tipo'];
			$id_whatsapp_envio = $row['id_whatsapp_envio'];
			$id_whatsapp_mensaje = $row['id'];

			$update_whatsapp_mensaje ='
				UPDATE api_whatsapp_mensajes 
					SET 
						fecha_leido="'.$fechaSF.'" ,
						hora_leido="'.$fechaSH.'" ,
						fecha_hora_leido="'.$fechaH.'" 
					WHERE ( id = "'.$id_whatsapp_mensaje.'" );';
			$update_whatsapp_mensaje=$conexion->query($update_whatsapp_mensaje);
			if(!$update_whatsapp_mensaje || $num=0){
				$success=false;
				echo "ERROR update_whatsapp_mensaje"; 
				var_dump($conexion->error);
			}
		}

		$whatsapp_status['id_whatsapp_mensaje'] = $id_whatsapp_mensaje;
		$whatsapp_status['id_whatsapp_envio'] = $id_whatsapp_envio;
		$whatsapp_status['fechaR'] = $fechaH;
		$fields_pdo = "`".implode('`,`', array_keys($whatsapp_status))."`";
		$values_pdo = "'".implode("','", $whatsapp_status)."'";
		$insert_api_whatsapp_mensajes= "INSERT INTO api_whatsapp_envios_status ($fields_pdo) VALUES ($values_pdo);";
		$insert_api_whatsapp_mensajes=$conexion->query($insert_api_whatsapp_mensajes);
		$num=$conexion->affected_rows;
		if(!$insert_api_whatsapp_mensajes || $num=0){
			$success=false;
			echo "ERROR insert_api_whatsapp_mensajes"; 
			var_dump($conexion->error);
		}

	}else{
		echo 'offline';
	}