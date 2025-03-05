<?php

	include "../../functions/security.php";
	include "../../functions/genid.php";
	include "../../functions/timemex.php";
	include "../../functions/usuarios.php";
	include "../../functions/log_usuarios.php";
	include "../../functions/paquetes_sistema.php";
	include "../../functions/claves.php";
	include "../../functions/secciones_ine.php";
	include "../../functions/gps_distancias.php";

	include "../../functions/paises.php";
	include "../../functions/estados.php";
	include "../../functions/municipios.php";
	include "../../functions/localidades.php";
	include "../../functions/casillas_votos_2018.php";
	//$file_doc = "import/27_ciudadanos_8.csv";
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
	//coloca el ultimo numero que quieres importar
	$fin_importacion=11;
	
	if($xxx_numero > $fin_importacion){
		echo "Fin de toda la importación";
		die;
	}
	$file_doc = "import/25_Votos_Actuales_".$xxx_numero.".csv";
	//$file_doc = "import/".$xxx_numero.".csv";
	$handle = fopen($file_doc, "r");
	$columnData = array(
		'clave'=>array('alfanumerico','requerido','unique','unique_db','mayuscula'),
		'clave_secciones_ine'=>array('alfanumerico','requerido','buscar_clave','mayuscula'),
		'clave_casillas_votos_2018'=>array('alfanumerico','requerido','buscar_clave','mayuscula'),
		'clave_partidos_2018'=>array('alfanumerico','requerido','buscar_clave','mayuscula'),
		'votos'=>array('alfanumerico','requerido'),
		'tipo'=>array('alfanumerico','requerido'),
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
	$tabla="casillas_votos_partidos_2018";
	$numarray=0;

	$success=true;
	$conexion->autocommit(FALSE);
	//$data=$_SESSION['data_import'];
	$countData=0;

	$primaria="id_casilla_voto_partido_2018";
	foreach ($data as $key => $value) {
		foreach($value as $keyPrincipal => $atributo) {
			$value[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		unset($value[$primaria]);
		unset($value['error']);
		$valueH = $value;

		if($array_secciones_ine[$value['clave_secciones_ine']] ==""){
			$value['id_seccion_ine'] = claveId($value['clave_secciones_ine'],'secciones_ine');
			if($value['id_seccion_ine']==''){
				echo "<pre>";
				var_dump($value);
				echo "</pre>";
				echo $value['clave_secciones_ine'];
				die;
			}
			$array_secciones_ine[$value['clave_secciones_ine']] = $value['id_seccion_ine'];
			$value['clave_seccion_ine'] = $value['clave_secciones_ine'];
			unset($value['clave_secciones_ine']);
		}else{
			$value['id_seccion_ine'] = $array_secciones_ine[$value['clave_secciones_ine']];
			$value['clave_seccion_ine'] = $value['clave_secciones_ine'];
			unset($value['clave_secciones_ine']);

		}

		if($array_casillas_votos_2018[$value['clave_casillas_votos_2018']] ==""){
			$value['id_casilla_voto_2018'] = claveId($value['clave_casillas_votos_2018'],'casillas_votos_2018');
			
			//datos de las secciones para municipio,
			$casilla_voto_2018Datos = casilla_voto_2018Datos($value['id_casilla_voto_2018']);
			
			$value['id_municipio'] = $casilla_voto_2018Datos['id_municipio'];
			$value['clave_municipio'] = $casilla_voto_2018Datos['clave_municipio'];
			$value['id_distrito_local'] = $casilla_voto_2018Datos['id_distrito_local'];
			$value['clave_distrito_local'] = $casilla_voto_2018Datos['clave_distrito_local'];
			$value['id_distrito_federal'] = $casilla_voto_2018Datos['id_distrito_federal'];
			$value['clave_distrito_federal'] = $casilla_voto_2018Datos['clave_distrito_federal'];
			$value['id_cuartel'] = $casilla_voto_2018Datos['id_cuartel'];
			$array_casillas_votos_2018[$value['clave_casillas_votos_2018']] = array(
																			'id_municipio' => $casilla_voto_2018Datos['id_municipio'],
																			'clave_municipio' => $casilla_voto_2018Datos['clave_municipio'],
																			'id_distrito_local' => $casilla_voto_2018Datos['id_distrito_local'],
																			'clave_distrito_local' => $casilla_voto_2018Datos['clave_distrito_local'],
																			'id_distrito_federal' => $casilla_voto_2018Datos['id_distrito_federal'],
																			'clave_distrito_federal' => $casilla_voto_2018Datos['clave_distrito_federal'],
																			'id_cuartel' => $casilla_voto_2018Datos['id_cuartel'],
																			'id_casilla_voto_2018' => $casilla_voto_2018Datos['id'],
																		);
			unset($value['clave_casillas_votos_2018']);
		}else{

			$value['id_casilla_voto_2018'] = $array_casillas_votos_2018[$value['clave_casillas_votos_2018']]['id_casilla_voto_2018'];
			$value['id_municipio'] = $array_casillas_votos_2018[$value['clave_casillas_votos_2018']]['id_municipio'];
			$value['clave_municipio'] = $array_casillas_votos_2018[$value['clave_casillas_votos_2018']]['clave_municipio'];
			$value['id_distrito_local'] = $array_casillas_votos_2018[$value['clave_casillas_votos_2018']]['id_distrito_local'];
			$value['clave_distrito_local'] = $array_casillas_votos_2018[$value['clave_casillas_votos_2018']]['clave_distrito_local'];
			$value['id_distrito_federal'] = $array_casillas_votos_2018[$value['clave_casillas_votos_2018']]['id_distrito_federal'];
			$value['clave_distrito_federal'] = $array_casillas_votos_2018[$value['clave_casillas_votos_2018']]['clave_distrito_federal'];
			$value['id_cuartel'] = $array_casillas_votos_2018[$value['clave_casillas_votos_2018']]['id_cuartel'];
			unset($value['clave_casillas_votos_2018']);

		}

		if($array_partidos_2018[$value['clave_partidos_2018']] ==""){
			$value['id_partido_2018'] = claveId($value['clave_partidos_2018'],'partidos_2018');
			$array_partidos_2018[$value['clave_partidos_2018']] = $value['id_partido_2018'];
			unset($value['clave_partidos_2018']);

		}else{
			$value['id_partido_2018'] = $array_partidos_2018[$value['clave_partidos_2018']];
			unset($value['clave_partidos_2018']);

		}


		$value['codigo_plataforma']=$codigo_plataforma;
		$value['referencia_importacion']=$cod32;
		$value['fechaR']=$fechaH;

		//foreach($value as $keyPrincipal => $atributo) {
		//	$value[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		//}
		$fields_pdo = "`".implode('`,`', array_keys($value))."`";
		$values_pdo = "'".implode("','", $value)."'";
		//echo "<pre>";
		//echo print_r($value);
		//echo "</pre>";
		$ck_men = $insert= "INSERT INTO {$tabla} ($fields_pdo) VALUES ($values_pdo);";
		$insert=$conexion->query($insert);
		$num=$conexion->affected_rows;
		if(!$insert || $num=0){
			echo "<pre>";
			var_dump($valueH);
			echo "</pre>";
			$success=false;
			echo $ck_men;
			echo "ERROR insert_{$tabla}"; 
			var_dump($conexion->error);
			echo "<br>";
			die;
		}
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
		$rutaTemporal=$file_doc;
		//if(!move_uploaded_file($rutaTemporal,$rutaDestino)){
		//	$success=false;
		//	echo "ERROR,No se guardo el csv";
		//}
		$rutaDestino="../files/insert_".$_COOKIE["id_usuario"]."_".$tabla."_".$cod32.".csv";
		if (!copy($rutaTemporal, $rutaDestino)) {
			$success=false;
			echo "ERROR,No se guardo el csv";
		}
		//echo "<pre>";
		//var_dump($insertArray);
		//echo "</pre>";
		if($success){
			//var_dump($insertArray);
			$logArray=logUsuarioArray($insertArray,$fechaH,$_COOKIE["id_usuario"]);
			if($logArray==true){
				echo "status: SI";
				$conexion->commit();
				$conexion->close();
				$fichero = 'numero.txt';
				$xxx_nuevo_numero = $xxx_numero + 1;
				$actual = $xxx_nuevo_numero;
				// Escribe el contenido al fichero
				file_put_contents($fichero, $actual);
				if (copy($rutaTemporal, $rutaDestino)) {
					$hora = date("H:i:s");
					echo "<br>";
					echo "Final: ".$hora;
					echo "<br>";
					$memory_size = memory_get_usage();
					// Display memory size into kb, mb etc.
					echo 'Used Memory Final: '.round($memory_size/pow(1024,($x=floor(log($memory_size,1024)))),2).' '.$memory_unit[$x]."\n";
					echo "<br>";
					sleep(1);
					if($xxx_nuevo_numero > $fin_importacion){
						echo "Fin de toda la importación";
						echo '<center><img src="../bye.gif" style="width: 40%" ></center>';
						die;
					}
					?>
					<center>
						<img src="../run.gif" style="width: 40%" >
					</center>
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