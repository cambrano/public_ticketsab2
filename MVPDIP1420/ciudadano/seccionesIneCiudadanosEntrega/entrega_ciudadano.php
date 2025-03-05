<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	if(!empty($_POST)){
		include __DIR__."/../functions/switch_operaciones.php";
		include __DIR__."/../functions/secciones_ine_ciudadanos_permisos.php";
		$seccion_ine_ciudadano_permisosDatos = seccion_ine_ciudadano_permisosDatos('','',$_COOKIE["id_usuario"]);
		$switch_operacionesPermisos = switch_operacionesPermisos();
		if($switch_operacionesPermisos['entrega'] && $seccion_ine_ciudadano_permisosDatos['entrega'] == "1"){
		}else{
			echo "No tiene permiso";
			die;
		}
		include __DIR__."/../functions/log_usuarios.php";
		include __DIR__."/../functions/usuarios.php";
		include __DIR__."/../functions/secciones_ine_ciudadanos_check_2024.php";
		include __DIR__."/../functions/secciones_ine.php";

		$usuarioDatos = usuarioDatos($_COOKIE["id_usuario"]);
		$id_seccion_ine = $usuarioDatos['id_seccion_ine'];
		$id_casilla_voto_2024 = $_COOKIE["id_casilla_voto"];
		$id_usuario = $_COOKIE["id_usuario"];
		$id_seccion_ine_ciudadano = $_POST['casilla_voto'][0]['id_seccion_ine_ciudadano'];
		$id_seccion_ine_ciudadano_relacionado = $usuarioDatos['id_seccion_ine_ciudadano'];

		$seccion_ineDatos = seccion_ineDatos($id_seccion_ine);
		$id_municipio = $seccion_ineDatos['id_municipio'];
		$id_distrito_local = $seccion_ineDatos['id_distrito_local'];
		$id_distrito_federal = $seccion_ineDatos['id_distrito_federal'];

		$seccion_ine_ciudadano_check_2024Datos = seccion_ine_ciudadano_check_2024Datos('',$id_seccion_ine,'',$id_casilla_voto_2024,$id_usuario,'',$id_seccion_ine_ciudadano,$id_seccion_ine_ciudadano_relacionado);

		if($seccion_ine_ciudadano_check_2024Datos['id']==""){
			/// insert
			$success=true;
			$conexion->autocommit(FALSE);
			$_POST["seccion_ine_ciudadano_check_2024"][0]['fechaR']=$fechaH; 
			$_POST["seccion_ine_ciudadano_check_2024"][0]['codigo_plataforma']=$codigo_plataforma;


			$_POST["seccion_ine_ciudadano_check_2024"][0]['id_seccion_ine']=$id_seccion_ine; 
			$_POST["seccion_ine_ciudadano_check_2024"][0]['id_municipio']=$id_municipio;
			$_POST["seccion_ine_ciudadano_check_2024"][0]['id_distrito_local']=$id_distrito_local; 
			$_POST["seccion_ine_ciudadano_check_2024"][0]['id_distrito_federal']=$id_distrito_federal; 
			$_POST["seccion_ine_ciudadano_check_2024"][0]['id_casilla_voto_2024']=$id_casilla_voto_2024;
			$_POST["seccion_ine_ciudadano_check_2024"][0]['id_usuario']=$id_usuario;

			$_POST["seccion_ine_ciudadano_check_2024"][0]['id_seccion_ine_ciudadano']=$id_seccion_ine_ciudadano;
			$_POST["seccion_ine_ciudadano_check_2024"][0]['id_seccion_ine_ciudadano_relacionado']=$id_seccion_ine_ciudadano_relacionado;


			$_POST["seccion_ine_ciudadano_check_2024"][0]['check_in']=1;
			$_POST["seccion_ine_ciudadano_check_2024"][0]['check_in_fecha']=$fechaSF;
			$_POST["seccion_ine_ciudadano_check_2024"][0]['check_in_hora']=$fechaSH; 
			$_POST["seccion_ine_ciudadano_check_2024"][0]['check_in_fecha_hora']=$fechaH; 


			$fields_pdo = "`".implode('`,`', array_keys($_POST['seccion_ine_ciudadano_check_2024'][0]))."`";
			$values_pdo = "'".implode("','", $_POST['seccion_ine_ciudadano_check_2024'][0])."'";
			$insert_seccion_ine_ciudadano_check_2024= "INSERT INTO secciones_ine_ciudadanos_check_2024 ($fields_pdo) VALUES ($values_pdo);";

			$insert_seccion_ine_ciudadano_check_2024=$conexion->query($insert_seccion_ine_ciudadano_check_2024);
			$num=$conexion->affected_rows;
			if(!$insert_seccion_ine_ciudadano_check_2024 || $num=0){
				$success=false;
				echo "ERROR insert_seccion_ine_ciudadano_check_2024"; 
				var_dump($conexion->error);
			}

			$id=$_POST['seccion_ine_ciudadano_check_2024'][0]['id_seccion_ine_ciudadano_check_2024']=$conexion->insert_id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST['seccion_ine_ciudadano_check_2024'][0]))."`";
			$values_pdo = "'".implode("','", $_POST['seccion_ine_ciudadano_check_2024'][0])."'";
			$insert_seccion_ine_ciudadano_check_2024_historicos= "INSERT INTO secciones_ine_ciudadanos_check_2024_historicos ($fields_pdo) VALUES ($values_pdo);";

			$insert_seccion_ine_ciudadano_check_2024_historicos=$conexion->query($insert_seccion_ine_ciudadano_check_2024_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_seccion_ine_ciudadano_check_2024_historicos || $num=0){
				$success=false;
				echo "ERROR insert_seccion_ine_ciudadano_check_2024_historicos"; 
				var_dump($conexion->error);
			}
			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_ciudadanos_check_2024',$id,'Insert','',$fechaH);
				if($log==true){
					echo "SI";
					echo $fechaSH;
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
			// update
			//echo "NO";
			if($seccion_ine_ciudadano_check_2024Datos['check_in'] != 1){
				//editamos
				$_POST["seccion_ine_ciudadano_check_2024"][0]['id'] = $seccion_ine_ciudadano_check_2024Datos['id']; 
				$_POST["seccion_ine_ciudadano_check_2024"][0]['id_seccion_ine'] = $id_seccion_ine;
				$_POST["seccion_ine_ciudadano_check_2024"][0]['id_municipio'] = $id_municipio;
				$_POST["seccion_ine_ciudadano_check_2024"][0]['id_distrito_local']=$id_distrito_local;
				$_POST["seccion_ine_ciudadano_check_2024"][0]['id_distrito_federal']=$id_distrito_federal; 
				$_POST["seccion_ine_ciudadano_check_2024"][0]['id_casilla_voto_2024'] = $id_casilla_voto_2024;
				$_POST["seccion_ine_ciudadano_check_2024"][0]['id_usuario'] = $id_usuario;
				$_POST["seccion_ine_ciudadano_check_2024"][0]['id_seccion_ine_ciudadano'] = $id_seccion_ine_ciudadano;
				$_POST["seccion_ine_ciudadano_check_2024"][0]['id_seccion_ine_ciudadano_relacionado'] = $id_seccion_ine_ciudadano_relacionado;
				$_POST["seccion_ine_ciudadano_check_2024"][0]['check_in'] = 1;
				$_POST["seccion_ine_ciudadano_check_2024"][0]['fechaR']=$fechaH; 
				$_POST["seccion_ine_ciudadano_check_2024"][0]['fechaR']=$fechaH; 
				$_POST["seccion_ine_ciudadano_check_2024"][0]['codigo_plataforma']=$codigo_plataforma;
				$_POST["seccion_ine_ciudadano_check_2024"][0]['check_in_fecha']=$fechaSF;
				$_POST["seccion_ine_ciudadano_check_2024"][0]['check_in_hora']=$fechaSH; 
				$_POST["seccion_ine_ciudadano_check_2024"][0]['check_in_fecha_hora']=$fechaH;

				$success=true;
				foreach($_POST['seccion_ine_ciudadano_check_2024'] as $keyPrincipal => $atributos) {
					foreach ($atributos as $key => $value) {
						if($key !='id'){
							$valueSets[] = $key . " = '" . $value . "'";
						}else{
							$id=$value;
						}
					}
				}
				$update_secciones_ine_ciudadanos_check_2024 = "UPDATE secciones_ine_ciudadanos_check_2024 SET ". join(",",$valueSets) . " WHERE id=".$id;
				$conexion->autocommit(FALSE);
				$update_secciones_ine_ciudadanos_check_2024=$conexion->query($update_secciones_ine_ciudadanos_check_2024);
				$num=$conexion->affected_rows;
				if(!$update_secciones_ine_ciudadanos_check_2024 || $num=0){
					$success=false;
					echo "<br>";
					echo "ERROR update_secciones_ine_ciudadanos_check_2024";
					var_dump($conexion->error);
				}

				unset($_POST["seccion_ine_ciudadano_check_2024"][0]['id']);
				$id_seccion_ine_ciudadano_check_2024=$_POST['seccion_ine_ciudadano_check_2024'][0]['id_seccion_ine_ciudadano_check_2024']=$id;
				$fields_pdo = "`".implode('`,`', array_keys($_POST['seccion_ine_ciudadano_check_2024'][0]))."`";
				$values_pdo = "'".implode("','", $_POST['seccion_ine_ciudadano_check_2024'][0])."'";
				$insert_secciones_ine_ciudadanos_check_2024_historicos= "INSERT INTO secciones_ine_ciudadanos_check_2024_historicos ($fields_pdo) VALUES ($values_pdo);";
				$insert_secciones_ine_ciudadanos_check_2024_historicos=$conexion->query($insert_secciones_ine_ciudadanos_check_2024_historicos);
				$num=$conexion->affected_rows;
				if(!$insert_secciones_ine_ciudadanos_check_2024_historicos || $num=0){
					$success=false;
					echo "ERROR insert_secciones_ine_ciudadanos_check_2024_historicos"; 
					var_dump($conexion->error);
				}

				if($success){
					$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_ciudadanos_check_2024',$id_seccion_ine_ciudadano_check_2024,'Update','',$fechaH);
					if($log==true){
						echo "SI";
						echo $fechaSH;
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