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

	//include "../../functions/usuarios.php"; 
	include "../../functions/paises.php";
	include "../../functions/estados.php";
	include "../../functions/municipios.php";
	include "../../functions/localidades.php";

	include "../../functions/secciones_ine.php";
	include "../../functions/gps_distancias.php";


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
			$status_comparacion="";
			unset($value[$primaria]);
			//cambios de columnas
			foreach($value as $clave=>$valor){
				if(empty($valor)) {
					unset($value[$clave]);
				}
			}
			if($value['pais']!=""){
				$value['id_pais']=paisId($value['pais']);
				unset($value['pais']);
			}
			if($value['estado']!=""){
				if($value['estado']=="México" || $value['estado']=="méxico" ){
					$value['id_estado']=estadoId($value['estado'],1);
					unset($value['estado']);
				}else{
					$value['id_estado']=estadoId($value['estado'],"");
					unset($value['estado']);
				}
			}
			if($value['municipio']!=""){
				$value['id_municipio']=municipioId($value['municipio'],$value['id_estado']);
				unset($value['municipio']);
			}
			if($value['localidad']!=""){
				$value['id_localidad']=localidadId($value['localidad'],$value['id_estado'],$value['id_municipio']);
				unset($value['localidad']);
			}

			/*
			if($array_secciones_ine[$value['clave_secciones_ine']] ==""){
				$value['id_seccion_ine'] = claveId($value['clave_secciones_ine'],'secciones_ine');
				$array_secciones_ine[$value['clave_secciones_ine']] = $value['id_seccion_ine'];
				unset($value['clave_secciones_ine']);
			}else{
				$value['id_seccion_ine'] = $array_secciones_ine[$value['clave_secciones_ine']];
				unset($value['clave_secciones_ine']);
			}
			*/


			if($array_secciones_ine[$value['clave_secciones_ine']] ==""){
				$value['id_seccion_ine'] = claveId($value['clave_secciones_ine'],'secciones_ine');

				$seccion_ineDatos=seccion_ineDatos($value['id_seccion_ine']);
				$latitud = $value['latitud'];
				$longitud = $value['longitud'];
				$value['distancia_m'] = distanceCalculation($latitud, $longitud, $seccion_ineDatos['latitud'], $seccion_ineDatos['longitud'],'m',3);
				$value['distancia_km'] = distanceCalculation($latitud, $longitud, $seccion_ineDatos['latitud'], $seccion_ineDatos['longitud'],'km',3);

				$value['id_distrito_local'] = $seccion_ineDatos['id_distrito_local'];
				$value['id_distrito_federal'] = $seccion_ineDatos['id_distrito_federal'];
				$value['id_cuartel'] = $seccion_ineDatos['id_cuartel'];

				$array_secciones_ine[$value['clave_secciones_ine']] = $value['id_seccion_ine'];
				$array_secciones_ine_gps[$value['clave_secciones_ine']]['latitud'] = $seccion_ineDatos['latitud'];
				$array_secciones_ine_gps[$value['clave_secciones_ine']]['longitud'] = $seccion_ineDatos['longitud'];
				$array_secciones_ine_datos[$value['clave_secciones_ine']]['id_distrito_local'] = $seccion_ineDatos['id_distrito_local'];
				$array_secciones_ine_datos[$value['clave_secciones_ine']]['id_distrito_federal'] = $seccion_ineDatos['id_distrito_federal'];
				$array_secciones_ine_datos[$value['clave_secciones_ine']]['id_cuartel'] = $seccion_ineDatos['id_cuartel'];
				unset($value['clave_secciones_ine']);
			}else{
				$value['id_seccion_ine'] = $array_secciones_ine[$value['clave_secciones_ine']];

				$latitud = $value['latitud'];
				$longitud = $value['longitud'];
				$value['distancia_m'] = distanceCalculation($latitud, $longitud, $array_secciones_ine_gps[$value['clave_secciones_ine']]['latitud'], $array_secciones_ine_gps[$value['clave_secciones_ine']]['longitud'],'m',3);
				$value['distancia_km'] = distanceCalculation($latitud, $array_secciones_ine_gps[$value['clave_secciones_ine']]['latitud'], $array_secciones_ine_gps[$value['clave_secciones_ine']]['longitud'],'km',3);

				$value['id_distrito_local'] = $array_secciones_ine_datos[$value['clave_secciones_ine']]['id_distrito_local'];
				$value['id_distrito_federal'] = $array_secciones_ine_datos[$value['clave_secciones_ine']]['id_distrito_federal'];
				$value['id_cuartel'] = $array_secciones_ine_datos[$value['clave_secciones_ine']]['id_cuartel'];

				unset($value['clave_secciones_ine']);
			}


			if($array_tipos_ciudadanos[$value['clave_tipos_ciudadanos']] ==""){
				$value['id_tipo_ciudadano'] = claveId($value['clave_tipos_ciudadanos'],'tipos_ciudadanos');
				$array_tipos_ciudadanos[$value['clave_tipos_ciudadanos']] = $value['id_tipo_ciudadano'];
				unset($value['clave_tipos_ciudadanos']);
			}else{
				$value['id_tipo_ciudadano'] = $array_tipos_ciudadanos[$value['clave_tipos_ciudadanos']];
				unset($value['clave_tipos_ciudadanos']);
			}

			if($array_secciones_ine_ciudadanos[$value['clave_secciones_ine_ciudadanos']] ==""){
				$value['id_seccion_ine_ciudadano_compartido'] = claveId($value['clave_secciones_ine_ciudadanos'],'secciones_ine_ciudadanos');
				$array_secciones_ine_ciudadanos[$value['clave_secciones_ine_ciudadanos']] = $value['id_seccion_ine_ciudadano_compartido'];
				unset($value['clave_secciones_ine_ciudadanos']);
			}else{
				$value['id_seccion_ine_ciudadano_compartido'] = $array_secciones_ine_ciudadanos[$value['clave_secciones_ine_ciudadanos']];
				unset($value['clave_secciones_ine_ciudadanos']);
			}


			$value['nombre_completo']=$value['nombre'].' '.$value['apellido_paterno'].' '.$value['apellido_materno'];
			$diff = (date('Y') - date('Y',strtotime($value['fecha_nacimiento'])));
			if($diff==""){
				$diff=0;
			}
			$value['edad'] = $diff;

			//$tabla="empleados";
			unset($secciones_ine_ciudadanos);
			$value['clave'];
			$secciones_ine_ciudadanos=$value;
			$secciones_ine_ciudadanos['id']=claveId($value['clave'],'secciones_ine_ciudadanos');
			//unset($secciones_ine_ciudadanos['usuario']);
			//unset($secciones_ine_ciudadanos['password']);
			foreach ($secciones_ine_ciudadanos as $key => $valuex) {
				if($valuex==""){
					unset($secciones_ine_ciudadanos[$key]);
				}
			}

			unset($usuarios);
			$usuarios['clave']=$value['clave'];
			$usuarios['id']=claveId($usuarios['clave'],'usuarios');
			$usuarios['usuario']=$value['usuario'];
			$usuarios['password']=$value['password'];
			$usuarios['status']=$value['status'];
			$usuarios['tabla'] = 'secciones_ine_ciudadanos';
			foreach ($usuarios as $key => $valuex) {
				if($valuex==""){
					unset($usuarios[$key]);
				}
			}
			
			unset($secciones_ine_ciudadanos['usuario']);
			unset($secciones_ine_ciudadanos['password']);
			 
			//$importDataUsuarios=importData($usuarios['id'],'usuarios');
			//$registrosUsuarios=array_replace($importDataUsuarios, $usuarios);
			//$importData=importData($empleados['id'],'empleados');
			//$registros=array_replace($importData, $empleados);
			//registrosCompara('empleados',$empleados,1);
			//registrosCompara('usuarios',$usuarios,1);
			if($status_comparacion=registrosCompara('secciones_ine_ciudadanos',$secciones_ine_ciudadanos,1)==1 || $status_comparacion=registrosCompara('usuarios',$usuarios,1)==1){
				$status_comparacion=1;
			}


			$value['id']=claveId($value['clave'],$tabla);
			if($value['id']!=""){
				if($status_comparacion){
					if($tabla=="secciones_ine_ciudadanos"){
						unset($valueSets);
						unset($registrossecciones_ine_ciudadanos);
						unset($registrosUsuarios);
						unset($importDatasecciones_ine_ciudadanos);
						unset($importDataUsuarios);
						$secciones_ine_ciudadanos['fechaR']=$fechaH;
						$secciones_ine_ciudadanos['referencia_importacion']=$cod32;
						
						

						$importDatasecciones_ine_ciudadanos=importData($secciones_ine_ciudadanos['id'],'secciones_ine_ciudadanos');
						$registrossecciones_ine_ciudadanos=array_replace($importDatasecciones_ine_ciudadanos, $secciones_ine_ciudadanos);


						$tabla="secciones_ine_ciudadanos";
						foreach($registrossecciones_ine_ciudadanos as $keyPrincipal => $atributo) {
							$registrossecciones_ine_ciudadanos[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
						}
						foreach ($registrossecciones_ine_ciudadanos as $key => $value) {
							if($key !='id'){
								$valueSets[] = $key . " = '" . $value . "'";
							}else{
								$id=$value;
							}
						}
						$update = "UPDATE {$tabla} SET ". join(",",$valueSets) . " WHERE id=".$id;
						//echo "<br>";echo "<br>";
						$update=$conexion->query($update);
						$num=$conexion->affected_rows;
						if(!$update || $num=0){
							$success=false;
							echo "<br>";
							echo "error update_{$tabla}"; 
							var_dump($conexion->error);
						}
						$id_seccion_ine_ciudadano=$registrossecciones_ine_ciudadanos['id_seccion_ine_ciudadano']=$id;
						unset($registrossecciones_ine_ciudadanos['id']);
						$fields_pdo = "`".implode('`,`', array_keys($registrossecciones_ine_ciudadanos))."`";
						$values_pdo = "'".implode("','", $registrossecciones_ine_ciudadanos)."'";
						$insert= "INSERT INTO {$tabla}_historicos ($fields_pdo) VALUES ($values_pdo);";
						$insert=$conexion->query($insert);
						$num=$conexion->affected_rows;
						if(!$insert || $num=0){
							$success=false;
							echo "<br>";
							echo "error update_{$tabla}"; 
							var_dump($conexion->error);
						}

						$edad = $diff;
						if($registrossecciones_ine_ciudadanos['sexo']=="Hombre"){
							$sexo=1;
						}else{
							$sexo=2;
						}
						$update_encuesta = "UPDATE secciones_ine_ciudadanos_encuestas SET edad='{$edad}',sexo='{$sexo}' WHERE id<>0 AND id_seccion_ine_ciudadano = '{$id}' ";
						$update_encuesta=$conexion->query($update_encuesta);
						$num=$conexion->affected_rows;
						if(!$update_encuesta || $num=0){
							$success=false;
							echo "ERROR update_encuesta"; 
							var_dump($conexion->error);
						}


						//usuarios
						$usuarios['fechaR']=$fechaH;
						$usuarios['referencia_importacion']=$cod32;
						$importDataUsuarios=importData($usuarios['id'],'usuarios');
						$registrosUsuarios=array_replace($importDataUsuarios, $usuarios);

						unset($valueSets);
						$tabla="usuarios";
						foreach($registrosUsuarios as $keyPrincipal => $atributo) {
							$registrosUsuarios[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
						}
						unset($registrosUsuarios['id_empleado']);
						foreach ($registrosUsuarios as $key => $value) {
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
						$registrosUsuarios['id_usuario']=$id;
						unset($registrosUsuarios['id']);
						$fields_pdo = "`".implode('`,`', array_keys($registrosUsuarios))."`";
						$values_pdo = "'".implode("','", $registrosUsuarios)."'";
						$insert= "INSERT INTO {$tabla}_historicos ($fields_pdo) VALUES ($values_pdo);";
						$insert=$conexion->query($insert);
						$num=$conexion->affected_rows;
						if(!$insert || $num=0){
							$success=false;
							echo "<br>";
							echo "error update_{$tabla}"; 
							var_dump($conexion->error);
						}


						$insertArray[$numarray]['id']=$id_seccion_ine_ciudadano;
						$insertArray[$numarray]['tabla']=$tabla;
						$insertArray[$numarray]['tipo']="Update";
						$numarray=$numarray+1;
					}
				}
			}
			
		}
		if($numarray>0){
			$tabla="secciones_ine_ciudadanos";
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