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
	include "../../functions/secciones_ine_ciudadanos_encuestas.php";

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

		$primaria="id_seccion_ine_ciudadano_encuesta_respuesta";
		foreach ($data as $key => $value) {
			unset($value[$primaria]);
			//cambios de columnas
			//$value['id_encuesta']=claveId($value['clave_encuestas'],'encuestas');
			//unset($value['clave_encuestas']);
			//$value['id_red_social']=claveId($value['clave_redes_sociales'],'redes_sociales');
			//unset($value['clave_redes_sociales']);
			if($array_encuestas[$value['clave_encuestas']] ==""){
				$value['id_encuesta'] = claveId($value['clave_encuestas'],'encuestas');
				$array_encuestas[$value['clave_encuestas']] = $value['id_encuesta'];
				unset($value['clave_encuestas']);
			}else{
				$value['id_encuesta'] = $array_encuestas[$value['clave_encuestas']];
				unset($value['clave_encuestas']);
			}

			if($array_cuestionarios[$value['clave_cuestionarios']] ==""){
				$value['id_cuestionario'] = claveId($value['clave_cuestionarios'],'cuestionarios');
				$array_cuestionarios[$value['clave_cuestionarios']] = $value['id_cuestionario'];
				unset($value['clave_cuestionarios']);
			}else{
				$value['id_cuestionario'] = $array_cuestionarios[$value['clave_cuestionarios']];
				unset($value['clave_cuestionarios']);
			}

			if($value['clave_cuestionarios_respuestas']!="NOTIENE"){
				if($array_cuestionarios_respuestas[$value['clave_cuestionarios_respuestas']] ==""){
					$value['id_cuestionario_respuesta'] = claveId($value['clave_cuestionarios_respuestas'],'cuestionarios_respuestas');
					$array_cuestionarios_respuestas[$value['clave_cuestionarios_respuestas']] = $value['id_cuestionario_respuesta'];
					unset($value['clave_cuestionarios_respuestas']);
				}else{
					$value['id_cuestionario_respuesta'] = $array_cuestionarios_respuestas[$value['clave_cuestionarios_respuestas']];
					unset($value['clave_cuestionarios_respuestas']);
				}
			}else{
				unset($value['clave_cuestionarios_respuestas']);
			}

			if($array_secciones_ine_ciudadanos_encuestas[$value['clave_secciones_ine_ciudadanos_encuestas']] ==""){
				$value['id_seccion_ine_ciudadano_encuesta'] = claveId($value['clave_secciones_ine_ciudadanos_encuestas'],'secciones_ine_ciudadanos_encuestas');
				$seccion_ine_ciudadano_encuestaDatos=seccion_ine_ciudadano_encuestaDatos($value['id_seccion_ine_ciudadano_encuesta']);
				$value['id_seccion_ine_ciudadano'] = $seccion_ine_ciudadano_encuestaDatos['id_seccion_ine_ciudadano'];
				$value['id_seccion_ine'] = $seccion_ine_ciudadano_encuestaDatos['id_seccion_ine'];
				$value['id_municipio'] = $seccion_ine_ciudadano_encuestaDatos['id_municipio'];
				$value['id_distrito_local'] = $seccion_ine_ciudadano_encuestaDatos['id_distrito_local'];
				$value['id_distrito_federal'] = $seccion_ine_ciudadano_encuestaDatos['id_distrito_federal'];
				$value['fecha'] = $seccion_ine_ciudadano_encuestaDatos['fecha'];
				$value['hora'] = $seccion_ine_ciudadano_encuestaDatos['hora'];
				$array_secciones_ine_ciudadanos_encuestas[$value['clave_secciones_ine_ciudadanos_encuestas']] = $value['id_seccion_ine_ciudadano_encuesta'];
				$array_secciones_ine_ciudadanos_encuestas_datos[$value['clave_secciones_ine_ciudadanos_encuestas']]['id_seccion_ine'] = $seccion_ine_ciudadano_encuestaDatos['id_seccion_ine'];
				$array_secciones_ine_ciudadanos_encuestas_datos[$value['clave_secciones_ine_ciudadanos_encuestas']]['id_seccion_ine_ciudadano'] = $seccion_ine_ciudadano_encuestaDatos['id_seccion_ine_ciudadano'];
				$array_secciones_ine_ciudadanos_encuestas_datos[$value['clave_secciones_ine_ciudadanos_encuestas']]['id_municipio'] = $seccion_ine_ciudadano_encuestaDatos['id_municipio'];
				$array_secciones_ine_ciudadanos_encuestas_datos[$value['clave_secciones_ine_ciudadanos_encuestas']]['id_distrito_local'] = $seccion_ine_ciudadano_encuestaDatos['id_distrito_local'];
				$array_secciones_ine_ciudadanos_encuestas_datos[$value['clave_secciones_ine_ciudadanos_encuestas']]['id_distrito_federal'] = $seccion_ine_ciudadano_encuestaDatos['id_distrito_federal'];
				$array_secciones_ine_ciudadanos_encuestas_datos[$value['clave_secciones_ine_ciudadanos_encuestas']]['fecha'] = $seccion_ine_ciudadano_encuestaDatos['fecha'];
				$array_secciones_ine_ciudadanos_encuestas_datos[$value['clave_secciones_ine_ciudadanos_encuestas']]['hora'] = $seccion_ine_ciudadano_encuestaDatos['hora'];
				unset($value['clave_secciones_ine_ciudadanos_encuestas']);
			}else{
				$value['id_seccion_ine_ciudadano_encuesta'] = $array_secciones_ine_ciudadanos_encuestas[$value['clave_secciones_ine_ciudadanos_encuestas']]['id_seccion_ine_ciudadano_encuesta'];
				$value['id_seccion_ine_ciudadano'] = $array_secciones_ine_ciudadanos_encuestas_datos[$value['clave_secciones_ine_ciudadanos_encuestas']]['id_seccion_ine_ciudadano'];
				$value['id_seccion_ine'] = $array_secciones_ine_ciudadanos_encuestas_datos[$value['clave_secciones_ine_ciudadanos_encuestas']]['id_seccion_ine'];
				$value['id_municipio'] = $array_secciones_ine_ciudadanos_encuestas_datos[$value['clave_secciones_ine_ciudadanos_encuestas']]['id_municipio'];
				$value['id_distrito_local'] = $array_secciones_ine_ciudadanos_encuestas_datos[$value['clave_secciones_ine_ciudadanos_encuestas']]['id_distrito_local'];
				$value['id_distrito_federal'] = $array_secciones_ine_ciudadanos_encuestas_datos[$value['clave_secciones_ine_ciudadanos_encuestas']]['id_distrito_federal'];
				$value['fecha'] = $array_secciones_ine_ciudadanos_encuestas_datos[$value['clave_secciones_ine_ciudadanos_encuestas']]['fecha'];
				$value['hora'] = $array_secciones_ine_ciudadanos_encuestas_datos[$value['clave_secciones_ine_ciudadanos_encuestas']]['hora'];
				unset($value['clave_secciones_ine_ciudadanos_encuestas']);
			}


			$value['codigo_plataforma']=$codigo_plataforma;
			$value['referencia_importacion']=$cod32;
			$value['fechaR']=$fechaH;
			$value['fecha_hora']=$value['fecha'].' '.$value['hora'];
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