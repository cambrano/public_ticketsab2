<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	if(!empty($_POST)){

		

		include __DIR__."/../functions/log_usuarios.php";
		include __DIR__."/../functions/usuarios.php";
		include __DIR__."/../functions/secciones_ine_ciudadanos_check_2021.php";
		include __DIR__."/../functions/secciones_ine_ciudadanos.php";



		$id_casilla_voto_2021 = null;
		$id_usuario = $_COOKIE["id_usuario"];
		$id_seccion_ine_ciudadano = $_POST['casilla_voto'][0]['id_seccion_ine_ciudadano'];
		$seccion_ine_ciudadanoDatos = seccion_ine_ciudadanoDatos($id_seccion_ine_ciudadano);
		$id_municipio = $seccion_ine_ciudadanoDatos['id_municipio'];
		$id_seccion_ine = $seccion_ine_ciudadanoDatos['id_seccion_ine'];
		$id_distrito_local = $seccion_ine_ciudadanoDatos['id_distrito_local'];
		$id_distrito_federal = $seccion_ine_ciudadanoDatos['id_distrito_federal'];
		$id_seccion_ine_ciudadano_relacionado = null;

		$seccion_ine_ciudadano_check_2021Datos = seccion_ine_ciudadano_check_2021Datos('','','','','','',$id_seccion_ine_ciudadano,'');


		if($seccion_ine_ciudadano_check_2021Datos['id']==""){
			/// insert
			$success=true;
			$conexion->autocommit(FALSE);
			$_POST["seccion_ine_ciudadano_check_2021"][0]['fechaR']=$fechaH; 
			$_POST["seccion_ine_ciudadano_check_2021"][0]['codigo_plataforma']=$codigo_plataforma;
			$_POST["seccion_ine_ciudadano_check_2021"][0]['id_seccion_ine']=$id_seccion_ine; 
			$_POST["seccion_ine_ciudadano_check_2021"][0]['id_municipio']=$id_municipio;
			$_POST["seccion_ine_ciudadano_check_2021"][0]['id_distrito_local']=$id_distrito_local;
			$_POST["seccion_ine_ciudadano_check_2021"][0]['id_distrito_federal']=$id_distrito_federal;
			$_POST["seccion_ine_ciudadano_check_2021"][0]['id_usuario']=$id_usuario;
			$_POST["seccion_ine_ciudadano_check_2021"][0]['id_seccion_ine_ciudadano']=$id_seccion_ine_ciudadano;
			$_POST["seccion_ine_ciudadano_check_2021"][0]['check_in']=1;
			$_POST["seccion_ine_ciudadano_check_2021"][0]['check_in_fecha']=$fechaSF;
			$_POST["seccion_ine_ciudadano_check_2021"][0]['check_in_hora']=$fechaSH; 
			$_POST["seccion_ine_ciudadano_check_2021"][0]['check_in_fecha_hora']=$fechaH; 

			$fields_pdo = "`".implode('`,`', array_keys($_POST['seccion_ine_ciudadano_check_2021'][0]))."`";
			$values_pdo = "'".implode("','", $_POST['seccion_ine_ciudadano_check_2021'][0])."'";
			$insert_seccion_ine_ciudadano_check_2021= "INSERT INTO secciones_ine_ciudadanos_check_2021 ($fields_pdo) VALUES ($values_pdo);";

			$insert_seccion_ine_ciudadano_check_2021=$conexion->query($insert_seccion_ine_ciudadano_check_2021);
			$num=$conexion->affected_rows;
			if(!$insert_seccion_ine_ciudadano_check_2021 || $num=0){
				$success=false;
				echo "ERROR insert_seccion_ine_ciudadano_check_2021"; 
				var_dump($conexion->error);
			}

			$id=$_POST['seccion_ine_ciudadano_check_2021'][0]['id_seccion_ine_ciudadano_check_2021']=$conexion->insert_id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST['seccion_ine_ciudadano_check_2021'][0]))."`";
			$values_pdo = "'".implode("','", $_POST['seccion_ine_ciudadano_check_2021'][0])."'";
			$insert_seccion_ine_ciudadano_check_2021_historicos= "INSERT INTO secciones_ine_ciudadanos_check_2021_historicos ($fields_pdo) VALUES ($values_pdo);";

			$insert_seccion_ine_ciudadano_check_2021_historicos=$conexion->query($insert_seccion_ine_ciudadano_check_2021_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_seccion_ine_ciudadano_check_2021_historicos || $num=0){
				$success=false;
				echo "ERROR insert_seccion_ine_ciudadano_check_2021_historicos"; 
				var_dump($conexion->error);
			}
			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_ciudadanos_check_2021',$id,'Insert','',$fechaH);
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
			if($seccion_ine_ciudadano_check_2021Datos['check_in'] != 1){
				//editamos
				$_POST["seccion_ine_ciudadano_check_2021"][0]['id'] = $seccion_ine_ciudadano_check_2021Datos['id']; 
				$_POST["seccion_ine_ciudadano_check_2021"][0]['id_seccion_ine'] = $id_seccion_ine; 
				$_POST["seccion_ine_ciudadano_check_2021"][0]['id_municipio'] = $id_municipio;
				$_POST["seccion_ine_ciudadano_check_2021"][0]['id_distrito_local']=$id_distrito_local;
				$_POST["seccion_ine_ciudadano_check_2021"][0]['id_distrito_federal']=$id_distrito_federal;
				
				$_POST["seccion_ine_ciudadano_check_2021"][0]['id_usuario'] = $id_usuario;
				$_POST["seccion_ine_ciudadano_check_2021"][0]['id_seccion_ine_ciudadano'] = $id_seccion_ine_ciudadano;

				$_POST["seccion_ine_ciudadano_check_2021"][0]['check_in'] = 1;
				$_POST["seccion_ine_ciudadano_check_2021"][0]['fechaR']=$fechaH; 
				$_POST["seccion_ine_ciudadano_check_2021"][0]['fechaR']=$fechaH; 
				$_POST["seccion_ine_ciudadano_check_2021"][0]['codigo_plataforma']=$codigo_plataforma;
				$_POST["seccion_ine_ciudadano_check_2021"][0]['check_in_fecha']=$fechaSF;
				$_POST["seccion_ine_ciudadano_check_2021"][0]['check_in_hora']=$fechaSH; 
				$_POST["seccion_ine_ciudadano_check_2021"][0]['check_in_fecha_hora']=$fechaH;

				$success=true;
				foreach($_POST['seccion_ine_ciudadano_check_2021'] as $keyPrincipal => $atributos) {
					foreach ($atributos as $key => $value) {
						if($key !='id'){
							$valueSets[] = $key . " = '" . $value . "'";
						}else{
							$id=$value;
						}
					}
				}
				echo $update_secciones_ine_ciudadanos_check_2021 = "UPDATE secciones_ine_ciudadanos_check_2021 SET ". join(",",$valueSets) . " WHERE id=".$id;
				$conexion->autocommit(FALSE);
				$update_secciones_ine_ciudadanos_check_2021=$conexion->query($update_secciones_ine_ciudadanos_check_2021);
				$num=$conexion->affected_rows;
				if(!$update_secciones_ine_ciudadanos_check_2021 || $num=0){
					$success=false;
					echo "<br>";
					echo "ERROR update_secciones_ine_ciudadanos_check_2021";
					var_dump($conexion->error);
				}

				unset($_POST["seccion_ine_ciudadano_check_2021"][0]['id']);
				$id_seccion_ine_ciudadano_check_2021=$_POST['seccion_ine_ciudadano_check_2021'][0]['id_seccion_ine_ciudadano_check_2021']=$id;
				$fields_pdo = "`".implode('`,`', array_keys($_POST['seccion_ine_ciudadano_check_2021'][0]))."`";
				$values_pdo = "'".implode("','", $_POST['seccion_ine_ciudadano_check_2021'][0])."'";
				$insert_secciones_ine_ciudadanos_check_2021_historicos= "INSERT INTO secciones_ine_ciudadanos_check_2021_historicos ($fields_pdo) VALUES ($values_pdo);";
				$insert_secciones_ine_ciudadanos_check_2021_historicos=$conexion->query($insert_secciones_ine_ciudadanos_check_2021_historicos);
				$num=$conexion->affected_rows;
				if(!$insert_secciones_ine_ciudadanos_check_2021_historicos || $num=0){
					$success=false;
					echo "ERROR insert_secciones_ine_ciudadanos_check_2021_historicos"; 
					var_dump($conexion->error);
				}

				if($success){
					$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_ciudadanos_check_2021',$id_seccion_ine_ciudadano_check_2021,'Update','',$fechaH);
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