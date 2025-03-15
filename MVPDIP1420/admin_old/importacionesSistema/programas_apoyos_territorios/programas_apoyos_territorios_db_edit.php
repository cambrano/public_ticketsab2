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
	include "../../functions/camparaRegistros.php";
	include "../../functions/importacion.php";
	include "../../functions/secciones_ine_ciudadanos.php";


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
		$countData=0;
		$success=true;
		$conexion->autocommit(FALSE);
		$data=$_SESSION['data_import'];
		foreach ($data as $key => $value) {
			foreach($value as $keyPrincipal => $atributo) {
				$value[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
			}
			$status_comparacion="";
			unset($value[$primaria]);
			//cambios de columnas
			foreach($value as $clave=>$valor){
				if(empty($valor)) {
					unset($value[$clave]);
				}
			}
			$value['id']=claveId($value['clave'],$tabla);
			if($value['id']!=""){
				$primaria="id_programa_apoyo_territorio";
				//cambios de columnas
				//$value['id_identidad']=claveId($value['clave_identidades'],'identidades');
				//unset($value['clave_identidades']);
				//$value['id_red_social']=claveId($value['clave_redes_sociales'],'redes_sociales');
				//unset($value['clave_redes_sociales']);
				if($array_identidades[$value['clave_encuestas']] ==""){
					$value['id_encuesta'] = claveId($value['clave_encuestas'],'identidades');
					$array_identidades[$value['clave_encuestas']] = $value['id_encuesta'];
					unset($value['clave_encuestas']);
				}else{
					$value['id_encuesta'] = $array_identidades[$value['clave_encuestas']];
					unset($value['clave_encuestas']);
				}

				if($array_secciones_ine_ciudadanos[$value['clave_secciones_ine_ciudadanos']] ==""){
					$value['id_seccion_ine_ciudadano'] = claveId($value['clave_secciones_ine_ciudadanos'],'secciones_ine_ciudadanos');
					$seccion_ine_ciudadanoDatos=seccion_ine_ciudadanoDatos($value['id_seccion_ine_ciudadano']);
					$value['id_seccion_ine'] = $seccion_ine_ciudadanoDatos['id_seccion_ine'];
					$value['id_municipio'] = $seccion_ine_ciudadanoDatos['id_municipio'];
					$value['id_distrito_local'] = $seccion_ine_ciudadanoDatos['id_distrito_local'];
					$value['id_distrito_federal'] = $seccion_ine_ciudadanoDatos['id_distrito_federal'];
					$array_secciones_ine_ciudadanos[$value['clave_secciones_ine_ciudadanos']] = $value['id_seccion_ine_ciudadano'];
					$array_secciones_ine_ciudadanos_datos[$value['clave_secciones_ine_ciudadanos']]['id_seccion_ine'] = $seccion_ine_ciudadanoDatos['id_seccion_ine'];
					$array_secciones_ine_ciudadanos_datos[$value['clave_secciones_ine_ciudadanos']]['id_municipio'] = $seccion_ine_ciudadanoDatos['id_municipio'];
					$array_secciones_ine_ciudadanos_datos[$value['clave_secciones_ine_ciudadanos']]['id_distrito_local'] = $seccion_ine_ciudadanoDatos['id_distrito_local'];
					$array_secciones_ine_ciudadanos_datos[$value['clave_secciones_ine_ciudadanos']]['id_distrito_federal'] = $seccion_ine_ciudadanoDatos['id_distrito_federal'];
					unset($value['clave_secciones_ine_ciudadanos']);
				}else{
					$value['id_seccion_ine_ciudadano'] = $array_secciones_ine_ciudadanos[$value['clave_secciones_ine_ciudadanos']];
					$value['id_seccion_ine'] = $array_secciones_ine_ciudadanos_datos[$value['clave_secciones_ine_ciudadanos']]['id_seccion_ine'];
					$value['id_municipio'] = $array_secciones_ine_ciudadanos_datos[$value['clave_secciones_ine_ciudadanos']]['id_municipio'];
					$value['id_distrito_local'] = $array_secciones_ine_ciudadanos_datos[$value['clave_secciones_ine_ciudadanos']]['id_distrito_local'];
					$value['id_distrito_federal'] = $array_secciones_ine_ciudadanos_datos[$value['clave_secciones_ine_ciudadanos']]['id_distrito_federal'];
					unset($value['clave_secciones_ine_ciudadanos']);
				}


				//$value['fechaR']=$fechaH;
				$status_comparacion=registrosCompara($tabla,$value,1);
				if($status_comparacion){
					$value['fechaR']=$fechaH;
					$importData=importData($value['id'],$tabla);
					$value['fecha_hora']=$value['fecha'].' '.$value['hora'];
					$value['referencia_importacion']=$cod32;
					$registros=array_replace($importData, $value);
					//var_dump($registros);
					foreach($registros as $keyPrincipal => $atributo) {
						$registros[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
					}
					unset($valueSets);
					foreach ($registros as $key => $value) {
						if($key !='id'){
							$valueSets[] = $key . " = '" . $value . "'";
						}else{
							$id=$value;
						}
					}
					 
					$update = "UPDATE {$tabla} SET ". join(",",$valueSets) . " WHERE id=".$id;
					$update=$conexion->query($update);
					$num=$conexion->affected_rows;
					if(!$update || $num=0){
						$success=false;
						echo "<br>";
						echo "error update_{$tabla}"; 
						var_dump($conexion->error);
					}
					 
					$registros[$primaria]=$id;
					unset($registros['id']);
					$fields_pdo = "`".implode('`,`', array_keys($registros))."`";
					$values_pdo = "'".implode("','", $registros)."'";
					$insert= "INSERT INTO {$tabla}_historicos ($fields_pdo) VALUES ($values_pdo);";
					$insert=$conexion->query($insert);
					$num=$conexion->affected_rows;
					if(!$insert || $num=0){
						$success=false;
						echo "<br>";
						echo "error update_{$tabla}"; 
						var_dump($conexion->error);
					}
					$insertArray[$numarray]['id']=$registros[$primaria];
					$insertArray[$numarray]['tabla']=$tabla;
					$insertArray[$numarray]['tipo']="Update";
					$numarray=$numarray+1;
				}
			}
			
		}
		if($numarray>0){
			$log_importacion['id_usuario']=$usuario['id'];
			$log_importacion['tabla']=$tabla;
			$log_importacion['tipo']='Update';
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
				$insertArray[$numarray]['tipo']="Update";
				$numarray=$numarray+1;
				if($_FILES['file']['name'] != ""){
				$rutaTemporal=$_FILES['file']['tmp_name'];
				$rutaDestino="../files/update_".$_COOKIE["id_usuario"]."_".$tabla."_".$cod32.".csv";
				if(!move_uploaded_file($rutaTemporal,$rutaDestino)){
					$success=false;
					echo "ERROR,No se guardo el csv";
				}
			}
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
			echo "No se modifico ningun registro";
		}
		
	}
?>