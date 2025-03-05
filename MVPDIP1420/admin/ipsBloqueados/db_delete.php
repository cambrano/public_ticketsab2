<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/plataformas.php";
	$moduloAccionPermisos = moduloAccionPermisos('security','ips_bloqueados',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['delete'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST as $keyPrincipal => $atributo) {
			$_POST[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$conexion->autocommit(FALSE);
		$id=$_POST['id']; 
		$success=true;

		$delete_ips_bloqueados = "DELETE FROM ips_bloqueados  WHERE  id='{$id}' ";
		$delete_ips_bloqueados=$conexion->query($delete_ips_bloqueados);
		$num=$conexion->affected_rows;
		if(!$delete_ips_bloqueados || $num=0){
			$success=false;
			echo "ERROR delete IP bloqueado"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'ips_bloqueados',$id,'Delete','',$fechaH);
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
				}// Ruta del archivo .htaccess
			}else{
				echo "NO";
				$conexion->rollback();
				$conexion->close();
			}
		}else{
			echo "";
			$conexion->rollback();
			$conexion->close();
		}
		 
	}
