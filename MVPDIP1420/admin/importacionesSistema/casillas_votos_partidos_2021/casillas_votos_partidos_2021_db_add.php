<?php
	if($_FILES['file']["type"]!="text/csv"){
		echo "<br><div class='mensajeError'>Archivo Incorrecto debe ser un .CSV </div>";
		die;
	}
	include "../../functions/security.php";
	include "../../functions/genid.php";
	include "../../functions/timemex.php";
	include "../../functions/usuarios.php";
	include "../../functions/log_usuarios.php";
	include "../../functions/paquetes_sistema.php";
	include "../../functions/claves.php";
	include "../../functions/casillas_votos_2021.php";

	@session_start();
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST as $keyPrincipal => $atributo) {
			$_POST[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		//var_dump($_POST);
		//var_dump($_SESSION['data_import']);
		$usuario=usuarios(); 
		$tipo=$_POST['tipo_operacion'];
		$tabla=$_POST['tabla_operacion'];
		$numarray=0;

		$success=true;
		$conexion->autocommit(FALSE);
		$data=$_SESSION['data_import'];
		$countData=0;

		$array_casillas_votos_2021 = array();
		
		$array_partidos_2021 =  array();

		$array_secciones_ine  = array();

		$primaria="id_casilla_voto_partido_2021";
		foreach ($data as $key => $value) {
			unset($value[$primaria]);
			//cambios de columnas
			

			//$value['id_casilla_voto_2021']=claveId($value['clave_casillas_votos_2021'],'casillas_votos_2021');
			//unset($value['clave_casillas_votos_2021']);

			//$value['id_seccion_ine']=claveId($value['clave_secciones_ine'],'secciones_ine');
			//unset($value['clave_secciones_ine']);

			//$value['id_partido_2021']=claveId($value['clave_partidos_2021'],'partidos_2021');
			//unset($value['clave_partidos_2021']);


			if($array_secciones_ine[$value['clave_secciones_ine']] ==""){
				$value['id_seccion_ine'] = claveId($value['clave_secciones_ine'],'secciones_ine');
				$array_secciones_ine[$value['clave_secciones_ine']] = $value['id_seccion_ine'];
				unset($value['clave_secciones_ine']);
			}else{
				$value['id_seccion_ine'] = $array_secciones_ine[$value['clave_secciones_ine']];
				unset($value['clave_secciones_ine']);

			}

			if($array_casillas_votos_2021[$value['clave_casillas_votos_2021']] ==""){
				$value['id_casilla_voto_2021'] = claveId($value['clave_casillas_votos_2021'],'casillas_votos_2021');
				$array_casillas_votos_2021[$value['clave_casillas_votos_2021']] = $value['id_casilla_voto_2021'];
				unset($value['clave_casillas_votos_2021']);
				//datos de las secciones para municipio,
				$casilla_voto_2021Datos = casilla_voto_2021Datos($value['id_casilla_voto_2021']);
				$array_secciones_ine_datos[$value['id_casilla_voto_2021']] = array(
																				'id_municipio' => $casilla_voto_2021Datos['id_municipio'],
																				'id_distrito_local' => $casilla_voto_2021Datos['id_distrito_local'],
																				'id_distrito_federal' => $casilla_voto_2021Datos['id_distrito_federal'],
																				'id_cuartel' => $casilla_voto_2021Datos['id_cuartel'],
																			);
				$value['id_municipio'] = $casilla_voto_2021Datos['id_municipio'];
				$value['id_distrito_local'] = $casilla_voto_2021Datos['id_distrito_local'];
				$value['id_distrito_federal'] = $casilla_voto_2021Datos['id_distrito_federal'];
				$value['id_cuartel'] = $casilla_voto_2021Datos['id_cuartel'];
			}else{
				$value['id_casilla_voto_2021'] = $array_casillas_votos_2021[$value['clave_casillas_votos_2021']];

				$value['id_municipio'] = $array_secciones_ine_datos[$value['id_casilla_voto_2021']]['id_municipio'];
				$value['id_distrito_local'] = $array_secciones_ine_datos[$value['id_casilla_voto_2021']]['id_distrito_local'];
				$value['id_distrito_federal'] = $array_secciones_ine_datos[$value['id_casilla_voto_2021']]['id_distrito_federal'];
				$value['id_cuartel'] = $array_secciones_ine_datos[$value['id_casilla_voto_2021']]['id_cuartel'];

				unset($value['clave_casillas_votos_2021']);

			}

			if($array_partidos_2021[$value['clave_partidos_2021']] ==""){
				$value['id_partido_2021'] = claveId($value['clave_partidos_2021'],'partidos_2021');
				$array_partidos_2021[$value['clave_partidos_2021']] = $value['id_partido_2021'];
				unset($value['clave_partidos_2021']);

			}else{
				$value['id_partido_2021'] = $array_partidos_2021[$value['clave_partidos_2021']];
				unset($value['clave_partidos_2021']);

			}


			$value['codigo_plataforma']=$codigo_plataforma;
			$value['referencia_importacion']=$cod32;
			$value['fechaR']=$fechaH;
			foreach($value as $keyPrincipal => $atributo) {
				$value[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
			}
			$fields_pdo = "`".implode('`,`', array_keys($value))."`";
			$values_pdo = "'".implode("','", $value)."'";
			//echo "<pre>";
			//echo print_r($value);
			//echo "</pre>";
			$insert= "INSERT INTO {$tabla} ($fields_pdo) VALUES ($values_pdo);";
			$insert=$conexion->query($insert);
			$num=$conexion->affected_rows;
			if(!$insert || $num=0){
				$success=false;
				echo "ERROR insert_{$tabla}"; 
				var_dump($conexion->error);
				echo "<br>";
			}
			$value[$primaria]=$conexion->insert_id;
			$fields_pdo = "`".implode('`,`', array_keys($value))."`";
			$values_pdo = "'".implode("','", $value)."'";
			$insert= "INSERT INTO {$tabla}_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert=$conexion->query($insert);
			$num=$conexion->affected_rows;
			if(!$insert || $num=0){
				$success=false;
				echo "ERROR insert_{$tabla}_historicos"; 
				var_dump($conexion->error);
				echo "<br>";
				echo "<br>";
			}
			$insertArray[$numarray]['id']=$value[$primaria];
			$insertArray[$numarray]['tabla']=$tabla;
			$insertArray[$numarray]['tipo']="Insert";
			$numarray=$numarray+1;
		}
		if($numarray>0){
			$log_importacion['id_usuario']=$usuario['id'];
			$log_importacion['tabla']=$tabla;
			$log_importacion['tipo']='Insert';
			$log_importacion['referencia_importacion']=$cod32;
			$log_importacion['fechaR']=$fechaH;
			$fields_pdo = "`".implode('`,`', array_keys($log_importacion))."`";
			$values_pdo = "'".implode("','", $log_importacion)."'";
			$insert= "INSERT INTO log_importaciones ($fields_pdo) VALUES ($values_pdo);";
			$insert=$conexion->query($insert);
			$num=$conexion->affected_rows;
			if(!$insert || $num=0){
				$success=false;
				echo "ERROR log_importaciones"; 
				var_dump($conexion->error);
			}
			$insertArray[$numarray]['id']=$conexion->insert_id;
			$insertArray[$numarray]['tabla']='log_importaciones';
			$insertArray[$numarray]['tipo']="Insert";
			$numarray=$numarray+1;
			if($_FILES['file']['name'] != ""){
				$rutaTemporal=$_FILES['file']['tmp_name'];
				$rutaDestino="../files/insert_".$_COOKIE["id_usuario"]."_".$tabla."_".$cod32.".csv";
				if(!move_uploaded_file($rutaTemporal,$rutaDestino)){
					$success=false;
					echo "ERROR,No se guardo el csv";
				}
			}
			//echo "<pre>";
			//var_dump($insertArray);
			//echo "</pre>";
			if($success){
				//var_dump($insertArray);
				$logArray=logUsuarioArray($insertArray,$fechaH,$_COOKIE["id_usuario"]);
				if($logArray==true){
					echo "SI";
					$conexion->commit();
					$conexion->close();
				}else{
					unlink($rutaDestino);
					echo "NO";
					$conexion->rollback();
					$conexion->close();
				}
			}else{
				echo "NO";
				unlink($rutaDestino);
				$conexion->rollback();
				$conexion->close();
			}
		}else{
			if($successcon!=1){
				echo "No se inserto ningun registro";
			}
		}
	}
?>