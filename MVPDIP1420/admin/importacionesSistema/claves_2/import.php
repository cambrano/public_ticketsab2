<?php

	include "../../functions/security.php";
	include "../../functions/genid.php";
	include "../../functions/timemex.php";
	include "../../functions/log_usuarios.php";
	include "../../functions/usuarios.php";
	include "../../functions/claves_2.php";

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
	$file_doc = "import/2-clave.csv";
	$handle = fopen($file_doc, "r");
	$columnData = array(
		'empresa_adjudicada'=>array('alfanumerico','requerido','mayuscula'),
		'forma_empresa_adjudicada'=>array('alfanumerico','requerido','mayuscula'),
		'supervisor'=>array('alfanumerico','requerido','mayuscula'),
		'forma_supervisor'=>array('alfanumerico','requerido','mayuscula'),
		'seccion_ine_gira'=>array('alfanumerico','requerido','mayuscula'),
		'forma_seccion_ine_gira'=>array('alfanumerico','requerido','mayuscula'),
		'partido_2016'=>array('alfanumerico','requerido','mayuscula'),
		'forma_partido_2016'=>array('alfanumerico','requerido','mayuscula'),
		'casilla_voto_2016'=>array('alfanumerico','requerido','mayuscula'),
		'forma_casilla_voto_2016'=>array('alfanumerico','requerido','mayuscula'),
		'partido_2024'=>array('alfanumerico','requerido','mayuscula'),
		'forma_partido_2024'=>array('alfanumerico','requerido','mayuscula'),
		'casilla_voto_2024'=>array('alfanumerico','requerido','mayuscula'),
		'forma_casilla_voto_2024'=>array('alfanumerico','requerido','mayuscula'),
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
	$tabla="claves_2";
	$numarray=0;

	$success=true;
	$conexion->autocommit(FALSE);
	//$data=$_SESSION['data_import'];
	$countData=0;

	$primaria="id_clave";
	foreach ($data as $key => $value) {
		foreach($value as $keyPrincipal => $atributo) {
			$value[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		
		$numarray=$numarray+1;
		$errorvalor[]=4;
		$value['codigo_plataforma']=$codigo_plataforma;
		$value['referencia_importacion']=$cod32;
		$value['fechaR']=$fechaH;
		foreach($value as $keyPrincipal => $atributo) {
			$value[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$fields_pdo = "`".implode('`,`', array_keys($value))."`";
		$values_pdo = "'".implode("','", $value)."'";
		$insert= "INSERT INTO {$tabla} ($fields_pdo) VALUES ($values_pdo);";
		$insert=$conexion->query($insert);
		$num=$conexion->affected_rows;
		if(!$insert || $num=0){
			$success=false;
			$mensaje .= "ERROR insert_{$tabla} ".$conexion->error; 
			//var_dump($conexion->error);
		}

		$id=$conexion->insert_id;

		$insert_claves_2_historicos= "INSERT INTO claves_2_historicos ($fields_pdo) VALUES ($values_pdo);";
		$success=$insert_claves_2_historicos=$conexion->query($insert_claves_2_historicos);
		$num=$conexion->affected_rows;
		if(!$insert_claves_2_historicos || $num=0){
			$success=false;
			echo "ERROR insert_claves_2_historicos"; 
			var_dump($conexion->error);
		}

		$insertArray[$numarray]['id']=$id;
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
		$rutaDestino="../files/insert_".$_COOKIE["id_usuario"]."_".$tabla."_".$cod32.".csv";
		//if(!move_uploaded_file($rutaTemporal,$rutaDestino)){
		//	$success=false;
		//	echo "ERROR,No se guardo el csv";
		//}
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
				
				$hora = date("H:i:s");
				echo "<br>";
				echo "Final: ".$hora;
				echo "<br>";
				$memory_size = memory_get_usage();
				// Display memory size into kb, mb etc.
				echo 'Used Memory Final: '.round($memory_size/pow(1024,($x=floor(log($memory_size,1024)))),2).' '.$memory_unit[$x]."\n";
				echo "<br>";
				sleep(1);
				?>
				<script type="text/javascript">
					window.location.href = "../../importacionesSistema/empleados/import.php";
				</script>
				<?php
				die;

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