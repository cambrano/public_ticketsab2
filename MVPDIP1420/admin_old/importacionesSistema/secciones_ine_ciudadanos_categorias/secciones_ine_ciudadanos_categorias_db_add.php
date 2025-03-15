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

		$success=true;
		$conexion->autocommit(FALSE);
		$data=$_SESSION['data_import'];
		$countData=0;

		$primaria="id_seccion_ine_ciudadano_categoria";
		foreach ($data as $key => $value) {
			unset($value[$primaria]);

			//cambios de columnas
			//$value['id_seccion_ine']=claveId($value['clave_secciones_ine'],'secciones_ine');
			//unset($value['clave_secciones_ine']); 
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

			if($array_tipos_categorias_ciudadanos[$value['clave_tipos_categorias_ciudadanos']] ==""){
				$value['id_tipo_categoria_ciudadano'] = claveId($value['clave_tipos_categorias_ciudadanos'],'tipos_categorias_ciudadanos');
				$array_tipos_categorias_ciudadanos[$value['clave_tipos_categorias_ciudadanos']] = $value['id_tipo_categoria_ciudadano'];
				unset($value['clave_tipos_categorias_ciudadanos']);

			}else{
				$value['id_tipo_categoria_ciudadano'] = $array_tipos_categorias_ciudadanos[$value['clave_tipos_categorias_ciudadanos']];
				unset($value['clave_tipos_categorias_ciudadanos']);

			}


			$value['codigo_plataforma']=$codigo_plataforma;
			$value['referencia_importacion']=$cod32;
			$value['fechaR']=$fechaH;


			$value['hora'] = $fechaSH;
			$value['fecha'] = $fechaSF;
			$value['fecha_hora'] = $fechaH;


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