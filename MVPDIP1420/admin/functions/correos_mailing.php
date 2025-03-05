<?php
		function correos_mailing($id=null) {
			include 'db.php'; 
			$id;
			$select[$id]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT *
			FROM correos_mailing WHERE 1 = 1 ";

			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['usuario']."</option> ";
			} 
			$conexion->close();
			return $return;
		}
		function correo_mailingDatos($id=null){

			include 'db.php';
			$sql=("SELECT * FROM correos_mailing WHERE id='{$id}'  ");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$conexion->close();
			return $row;
		}

		function correoPrueba($datos=null,$mensaje=null,$para=null,$varios=null){
			$fechaH=date('Y-m-d H:i:s');
			$servidor=$datos['servidor'];
			$puerto=$datos['puerto'];
			$cifrado=$datos['cifrado'];
			$usuario=$datos['usuario'];
			if($para==null){
				$para=$usuario=$datos['usuario'];
			}
			
			$password=$datos['password'];
			$mensaje=$mensaje;
			$asunto=" Notificciones del Sistema administrador de website Ideas Y Soluciones AB";
			$de="Pruebas Ideas y Soluciones AB";

			// Godaddy
			//$servidor="p3plcpnl0942.prod.phx3.secureserver.net";
			//$puerto=465;
			//$cifrado="ssl";
			//$usuario="miagenciadeviajes@creesuniendoatabasco.org.mx";
			//$password="a07080444c";


			//Librerías para el envío de mail
			include_once('smtpconfig/class.phpmailer.php');
			include_once('smtpconfig/class.smtp.php');
			//Recibir todos los parámetros del formulario
			//Este bloque es importante
			//convierte en UTF-8 el subject
			$asunto='=?UTF-8?B?'.base64_encode($asunto).'?=';
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->Host = $servidor;
			$mail->Port = $puerto;
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = $cifrado;
			$mail->SMTPDebug = 0; 
			$mail->SMTPAutoTLS = false; 
			
			//Nuestra cuenta
			//$usuario="miagenciadeviajes@creesuniendoatabasco.org.mx";
			$mail->Username =$usuario;
			$mail->Password = $password;
			$mail->FromName = $de;
			$mail->From = $usuario; 
			$mail->AddReplyTo($datos['usuario'], 'Ideas y Soluciones AB');
			//Agregar destinatario 
			if($varios!=1){
				$mail->AddAddress($para);
			}else{
				foreach ($para as $key => $value) {
					$mail->addCC($value['correo_electronico']);
				}
			}
			//$mail->AddBCC("@financialgroup.mx",'Ticket Copia');
			//$mail->AddBCC("@financialgroup.mx",'Ticket Copia');
			$mail->Subject = $asunto."-_-".$fechaH;
			$mail->Body = $mensaje;
			//Para adjuntar archivo
			///$mail->AddAttachment($archivo['tmp_name'], $archivo['name']);
			$mail->MsgHTML($mensaje); 
			$mail->CharSet = 'UTF-8';
			$mail->IsHTML(true);

			//Avisar si fue enviado o no y dirigir al index
			if($mail->Send()){
				return $valsmpt="1";
			}else{ 
				return $valsmpt="0";
			}
		}

		function correoEnvio($datos,$mensaje,$asunto,$de,$para,$archivo=null){
			//Librerías para el envío de mail
			include_once('smtpconfig/class.phpmailer.php');
			include_once('smtpconfig/class.smtp.php');
			//Recibir todos los parámetros del formulario
			//Este bloque es importante
			//convierte en UTF-8 el subject
			$fechaH=date('Y-m-d H:i:s');
			$servidor=$datos['servidor'];
			$puerto=$datos['puerto'];
			$cifrado=$datos['cifrado'];
			$usuario=$datos['usuario'];
			$password=$datos['password'];
			$mensaje=$mensaje;
			$asunto=$asunto;
			// Godaddy
			/*
			$servidor="p3plcpnl0942.prod.phx3.secureserver.net";
			$puerto=465;
			$cifrado="ssl";
			$usuario="miagenciadeviajes@creesuniendoatabasco.org.mx";
			$password="a07080444c";
			*/
			//$para="soporte@softwaresada.com";
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->Host = $servidor;
			$mail->Port = $puerto;
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = $cifrado;
			$mail->SMTPDebug = 0; 
			$mail->SMTPAutoTLS = true; 

			//$message_id="ASsadasda131e";
			//$mail->addCustomHeader('In-Reply-To', $message_id);
			//$mail->MessageID = $message_id;
			//$mail->addCustomHeader('References', $message_id);
			

			$mail->Username =$usuario;
			$mail->Password = $password;
			$mail->FromName = $de;
			$mail->From = $usuario; 
			$mail->AddReplyTo($datos['usuario'], 'SADA - Sistema Adminsitrador de Agencias de viajes');
			//$mail->AddCustomHeader("List-Unsubscribe: <mailto:support@rosresurs.net?subject=Unsubscribe>, <http://rosresurs.net/escript/unsubscribe.php?token=12312312>");
			$mail->AddCustomHeader("List-Unsubscribe-Post: List-Unsubscribe=One-Click");
			$mail->AddCustomHeader("List-Unsubscribe:<mailto:".$usuario."?subject=Unsubscribe>");
			$mail->AddCustomHeader("Precedence: bulk");

			//Agregar destinatario
			$mail->AddAddress($para);
			//$mail->AddBCC("@financialgroup.mx",'Ticket Copia');
			//$mail->AddBCC("@financialgroup.mx",'Ticket Copia');
			//$asunto='=?UTF-8?B?'.base64_encode($asunto).'?=';
			$mail->Subject = $asunto;
			$mail->Body = $mensaje;
			//Para adjuntar archivo
			///$mail->AddAttachment($archivo['tmp_name'], $archivo['name']);
			$mail->MsgHTML($mensaje); 
			$mail->CharSet = 'UTF-8';
			$mail->IsHTML(true);
			if($archivo != ""){
				$mail->addStringAttachment($archivo['file'],$archivo['nombre'], $encoding = $archivo['encoding'], $type = $archivo['type']);
			}

			if($mail->Send()){
				return $valsmpt="1";
			}else{ 
				return $valsmpt="0";
				echo "Mailer Error: " . $mail->ErrorInfo;

			}
		}
		function correoEnvioMailing($datos,$mensaje,$asunto,$para,$archivo=null,$unsubscribe=null){
			//Librerías para el envío de mail
			include_once('smtpconfig/class.phpmailer.php');
			include_once('smtpconfig/class.smtp.php');
			//Recibir todos los parámetros del formulario
			//Este bloque es importante
			//convierte en UTF-8 el subject
			$fechaH=date('Y-m-d H:i:s');
			$servidor=$datos['servidor'];
			$puerto=$datos['puerto'];
			$cifrado=$datos['cifrado'];
			$usuario=$datos['usuario'];
			$password=$datos['password'];
			$de=$datos['de'];
			$mensaje=$mensaje;
			$asunto=$asunto;
			
			$correo_unsubscribe=$datos['correo_electronico'];
			$codigo_unico=$unsubscribe['codigo_unico'];
			$identificador=$unsubscribe['identificador'];
			$correo_electronico_reply = $datos['correo_electronico'];
			// Godaddy
			/*
			$servidor="p3plcpnl0942.prod.phx3.secureserver.net";
			$puerto=465;
			$cifrado="ssl";
			$usuario="miagenciadeviajes@creesuniendoatabasco.org.mx";
			$password="a07080444c";
			*/
			//$para="soporte@softwaresada.com";
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->Host = $servidor;
			$mail->Port = $puerto;
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = $cifrado;
			$mail->SMTPDebug = 0; 
			$mail->SMTPAutoTLS = true; 

			//$message_id="ASsadasda131e";
			//$mail->addCustomHeader('In-Reply-To', $message_id);
			//$mail->MessageID = $message_id;
			//$mail->addCustomHeader('References', $message_id);
			

			$mail->Username =$usuario;
			$mail->Password = $password;
			$mail->FromName = $de;
			$mail->From = $usuario; 
			$mail->AddReplyTo($correo_electronico_reply, 'Responder '.$de);
			//$mail->AddCustomHeader("List-Unsubscribe: <mailto:support@rosresurs.net?subject=Unsubscribe>, <http://rosresurs.net/escript/unsubscribe.php?token=12312312>");
			$mail->AddCustomHeader("List-Unsubscribe-Post: List-Unsubscribe=One-Click");
			$mail->AddCustomHeader("List-Unsubscribe:<mailto:".$correo_unsubscribe."?subject=Unsubscribe>, <https://www.ops.softwaresada.com/cliente_unsubscribe.php?cot=".$codigo_unico."&cot1=unsubscribe&cot2=".$identificador.">");
			$mail->AddCustomHeader("Precedence: bulk");

			//Agregar destinatario
			$mail->AddAddress($para);
			//$mail->AddBCC("@financialgroup.mx",'Ticket Copia');
			//$mail->AddBCC("@financialgroup.mx",'Ticket Copia');
			//$asunto='=?UTF-8?B?'.base64_encode($asunto).'?=';
			$mail->Subject = $asunto;
			$mail->Body = $mensaje;
			//Para adjuntar archivo
			///$mail->AddAttachment($archivo['tmp_name'], $archivo['name']);
			$mail->MsgHTML($mensaje); 
			$mail->CharSet = 'UTF-8';
			$mail->IsHTML(true);
			if($archivo != ""){
				$mail->addStringAttachment($archivo['file'],$archivo['nombre'], $encoding = $archivo['encoding'], $type = $archivo['type']);
			}

			if($mail->Send()){
				return $valsmpt="1";
			}else{ 
				$valsmpt = "";
				$valsmpt .= "Mailer Error: " . $mail->ErrorInfo; 
				return $valsmpt;

			}
		}

		function correoVerificacion($correo_electronico=null){
			if (filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
				$return=true;
			}else{
				$return=false;
			}
			return $return;
		}



?>