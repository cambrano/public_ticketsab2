<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/ips_bloqueados.php";
	include __DIR__."/../functions/plataformas.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('security','ips_bloqueados',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	$hay_coincidencia = strpos($_POST["ip_bloqueado"][0]['ip'], 'localhost') !== false;
	if ($hay_coincidencia) {
		echo "IP: localhost no se puede usar";
		die;
	}
	$hay_coincidencia = strpos($_POST["ip_bloqueado"][0]['ip'], '127.0.0.1') !== false;
	if ($hay_coincidencia) {
		echo "IP: 127.0.0.1 no se puede usar";
		die;
	}
	$hay_coincidencia = strpos($_POST["ip_bloqueado"][0]['ip'], '::1') !== false;
	if ($hay_coincidencia) {
		echo "IP: ::1 no se puede usar";
		die;
	}

	//var_dump($_POST["ip_bloqueado"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["ip_bloqueado"][0] as $keyPrincipal => $atributo) {
			$_POST["ip_bloqueado"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}

		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["ip_bloqueado"][0]['fechaR']=$fechaH; 
		$_POST["ip_bloqueado"][0]['status']=1; 
		$_POST["ip_bloqueado"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['ip_bloqueado'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['ip_bloqueado'][0])."'";
		$inset_ips_bloqueados= "INSERT INTO ips_bloqueados ($fields_pdo) VALUES ($values_pdo);";

		$inset_ips_bloqueados=$conexion->query($inset_ips_bloqueados);
		$num=$conexion->affected_rows;
		if(!$inset_ips_bloqueados || $num=0){
			$success=false;
			echo "ERROR inset_ips_bloqueados"; 
			var_dump($conexion->error);
		}

		$id=$_POST['ip_bloqueado'][0]['id_ip_bloqueado']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['ip_bloqueado'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['ip_bloqueado'][0])."'";
		$inset_ips_bloqueados_historicos= "INSERT INTO ips_bloqueados_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_ips_bloqueados_historicos=$conexion->query($inset_ips_bloqueados_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_ips_bloqueados_historicos || $num=0){
			$success=false;
			echo "ERROR inset_ips_bloqueados_historicos"; 
			var_dump($conexion->error);
		}
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'ips_bloqueados',$id,'Insert','',$fechaH);
			if($log==true){
				echo "SI";
				$conexion->commit();
				$conexion->close();
				//! hacemos un curl post para que modifique el archivo httacces de los servidores
				$plataformasDatos = plataformasDatos();
				foreach ($plataformasDatos as $key => $value) {
					$url = $value['url'];
					$codigo_dispositivo = $value['key_acceso'];
					
					// Datos que deseas enviar
					$data = array(
						'codigo_dispositivo' => $codigo_dispositivo
					);

					// Inicializar cURL
					$ch = curl_init();
					// Establecer la URL a la que se enviarán los datos
					curl_setopt($ch, CURLOPT_URL, $url);
					// Establecer que se enviarán datos mediante POST
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
					// Convertir los datos a formato de cadena
					$postData = http_build_query($data);
					// Establecer los datos que se enviarán
					curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
					// Indicar que quieres recibir la respuesta del servidor
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					// Ejecutar la solicitud y guardar la respuesta en una variable
					$response = curl_exec($ch);
					// Verificar si ocurrió algún error
					if(curl_errno($ch)){
						echo 'Error al enviar la solicitud: ' . curl_error($ch);
					}
					// Cerrar la conexión cURL
					curl_close($ch);
					// Imprimir la respuesta del servidor
					$response;
				}

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