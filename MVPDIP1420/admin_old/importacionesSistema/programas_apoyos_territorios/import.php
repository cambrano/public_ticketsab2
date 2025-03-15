<?php

	include "../../functions/security.php";
	include "../../functions/genid.php";
	include "../../functions/timemex.php";
	include "../../functions/usuarios.php";
	include "../../functions/log_usuarios.php";
	include "../../functions/paquetes_sistema.php";
	include "../../functions/claves.php";
	include "../../functions/secciones_ine_ciudadanos.php";
	include "../../functions/programas_apoyos.php";


	$memory_size = memory_get_usage();
	$memory_unit = array('Bytes','KB','MB','GB','TB','PB');
	// Display memory size into kb, mb etc.
	echo 'Used Memory Inicial: '.round($memory_size/pow(1024,($x=floor(log($memory_size,1024)))),2).' '.$memory_unit[$x]."\n";
	echo "<br>";
	$hora = date("H:i:s");
	echo "Inicio:".$hora;
	echo "<br>";
	echo "Archivo:";
	echo $xxx_numero = file_get_contents('numero.txt');
	echo "<br>";
	//debemes poner + 1 de lo que hay
	$fin_importacion=396;
	//debemes poner + 1 de lo que hay
	if($xxx_numero > $fin_importacion){
		echo "Fin de toda la importación";
		die;
	}
	$file_doc = "import/27_ciudadanos_encuestas_respuestas_".$xxx_numero.".csv";
	$handle = fopen($file_doc, "r");
	$columnData = array(
		'clave_programas_apoyos'=>array('alfanumerico','requerido','buscar_clave','mayuscula'),
		'clave_encuestas'=>array('alfanumerico','requerido','buscar_clave','mayuscula'),
		'clave_cuestionarios'=>array('alfanumerico','requerido','buscar_clave','mayuscula'),
		'clave_cuestionarios_respuestas'=>array('alfanumerico','requerido','buscar_clave','mayuscula'),
		'respuesta'=>array('alfanumerico','requerido'),
	);

	// Optionally, you can keep the number of the line where
	// the loop its currently iterating over
	$lineNumber = 1;
	$num=0;
	// Iterate over every line of the file
	while (($raw_string = fgets($handle)) !== false) {
		$countdraw = $countdraw +1;
		if($countdraw > 2){
			// Parse the raw csv string: "1, a, b, c"
			$line = str_getcsv($raw_string);
			// into an array: ['1', 'a', 'b', 'c']
			// And do what you need to do with every line
			$numx=0;
			foreach ($columnData as $key => $value) {
				//esto es el valor del csv
				$line_valor=$line[$numx];
				//$line_valor= mysqli_real_escape_string($conexion,$line_valor);
				//esto es el nombre de la dato
				//metemos el valor para el data
				$data[$num][$key]=$line_valor;
				//$data[$num][$key]=$line_valor;
				$numx=$numx+1;
			}
			// Increase the current line
			$num=$num+1;
		}
		$lineNumber++;
		
	}
	fclose($handle);


	$usuario=usuarios(); 
	$tipo=1;
	$tabla="programas_apoyos_territorios";
	$numarray=0;

	$success=true;
	$conexion->autocommit(FALSE);
	//$data=$_SESSION['data_import'];
	$countData=0;

	$primaria="id_programa_apoyo_territorio";
	foreach ($data as $key => $value) {
		foreach($value as $keyPrincipal => $atributo) {
			$value[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		unset($value[$primaria]);
		unset($value['error']);


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

		if($array_programas_apoyos_datos[$value['clave_programas_apoyos']] ==""){
			unset($array_programas_apoyos_datos);
			$claveIdDatos = claveIdDatos($value['clave_programas_apoyos'],'programas_apoyos');

			$value['id_seccion_ine_ciudadano_encuesta'] = $claveIdDatos['id'];
			$value['id_seccion_ine_ciudadano'] = $claveIdDatos['id_seccion_ine_ciudadano'];
			$value['id_seccion_ine'] = $claveIdDatos['id_seccion_ine'];
			$value['id_municipio'] = $claveIdDatos['id_municipio'];
			$value['id_distrito_local'] = $claveIdDatos['id_distrito_local'];
			$value['id_distrito_federal'] = $claveIdDatos['id_distrito_federal'];
			$value['fecha'] = $claveIdDatos['fecha'];
			$value['hora'] = $claveIdDatos['hora'];

			$array_programas_apoyos_datos[$value['clave_programas_apoyos']]['id_seccion_ine_ciudadano_encuesta'] = $value['id_seccion_ine_ciudadano_encuesta'];
			$array_programas_apoyos_datos[$value['clave_programas_apoyos']]['id_seccion_ine'] = $claveIdDatos['id_seccion_ine'];
			$array_programas_apoyos_datos[$value['clave_programas_apoyos']]['id_seccion_ine_ciudadano'] = $claveIdDatos['id_seccion_ine_ciudadano'];
			$array_programas_apoyos_datos[$value['clave_programas_apoyos']]['id_municipio'] = $claveIdDatos['id_municipio'];
			$array_programas_apoyos_datos[$value['clave_programas_apoyos']]['id_distrito_local'] = $claveIdDatos['id_distrito_local'];
			$array_programas_apoyos_datos[$value['clave_programas_apoyos']]['id_distrito_federal'] = $claveIdDatos['id_distrito_federal'];
			$array_programas_apoyos_datos[$value['clave_programas_apoyos']]['fecha'] = $claveIdDatos['fecha'];
			$array_programas_apoyos_datos[$value['clave_programas_apoyos']]['hora'] = $claveIdDatos['hora'];
			unset($claveIdDatos);
			unset($value['clave_programas_apoyos']);
		}else{

			$value['id_seccion_ine_ciudadano_encuesta'] = $array_programas_apoyos_datos[$value['clave_programas_apoyos']]['id_seccion_ine_ciudadano_encuesta'];
			$value['id_seccion_ine_ciudadano'] = $array_programas_apoyos_datos[$value['clave_programas_apoyos']]['id_seccion_ine_ciudadano'];
			$value['id_seccion_ine'] = $array_programas_apoyos_datos[$value['clave_programas_apoyos']]['id_seccion_ine'];
			$value['id_municipio'] = $array_programas_apoyos_datos[$value['clave_programas_apoyos']]['id_municipio'];
			$value['id_distrito_local'] = $array_programas_apoyos_datos[$value['clave_programas_apoyos']]['id_distrito_local'];
			$value['id_distrito_federal'] = $array_programas_apoyos_datos[$value['clave_programas_apoyos']]['id_distrito_federal'];
			$value['fecha'] = $array_programas_apoyos_datos[$value['clave_programas_apoyos']]['fecha'];
			$value['hora'] = $array_programas_apoyos_datos[$value['clave_programas_apoyos']]['hora'];
			unset($value['clave_programas_apoyos']);
		}




		$value['codigo_plataforma']=$codigo_plataforma;
		$value['referencia_importacion']=$cod32;
		$value['fechaR']=$fechaH;
		$value['fecha_hora']=$value['fecha']." ".$value['hora'];
		//foreach($value as $keyPrincipal => $atributo) {
		//	$value[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		//}
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

		/*
		$value[$primaria]=$conexion->insert_id;
		$array_secciones_ine_ciudadanos[$value['clave']] = $conexion->insert_id;
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
		*/
		$numarray=$numarray+1;

	}
	if($numarray>0){
		/*
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
		$rutaTemporal=$file_doc;
		$rutaDestino="../files/insert_".$_COOKIE["id_usuario"]."_".$tabla."_".$cod32.".csv";
		//if(!move_uploaded_file($rutaTemporal,$rutaDestino)){
		//	$success=false;
		//	echo "ERROR,No se guardo el csv";
		//}
		//echo "<pre>";
		//var_dump($insertArray);
		//echo "</pre>";
		*/
		if($success){
			//var_dump($insertArray);
			//$logArray=logUsuarioArray($insertArray,$fechaH,$_COOKIE["id_usuario"]);
			$logArray = true;
			if($logArray==true){
				echo "SI";
				$conexion->commit();
				$conexion->close();
				$fichero = 'numero.txt';
				$xxx_nuevo_numero = $xxx_numero + 1;
				$actual = $xxx_nuevo_numero;
				// Escribe el contenido al fichero
				file_put_contents($fichero, $actual);
				

				//if (copy($rutaTemporal, $rutaDestino)) {
				if ($logArray) {
					$hora = date("H:i:s");
					echo "<br>";
					echo "Final: ".$hora;
					echo "<br>";
					$memory_size = memory_get_usage();
					// Display memory size into kb, mb etc.
					echo 'Used Memory Final: '.round($memory_size/pow(1024,($x=floor(log($memory_size,1024)))),2).' '.$memory_unit[$x]."\n";
					echo "<br>";
					if($xxx_nuevo_numero > $fin_importacion){
						echo "Fin de toda la importación";
						die;
					}
					?>
					<script type="text/javascript">
						location.reload();
					</script>
					<?php
				} else {
					$success=false;
					echo "ERROR,No se guardo el csv";
					die;
				}
			}else{
				//unlink($rutaDestino);
				echo "NO";
				$conexion->rollback();
				$conexion->close();
			}
		}else{
			echo "NO";
			//unlink($rutaDestino);
			$conexion->rollback();
			$conexion->close();
		}
	}else{
		if($successcon!=1){
			echo "No se inserto ningun registro";
		}
	}
?>