<?php
	include "../../functions/security.php";
	include "../../functions/genid.php";
	include "../../functions/timemex.php";
	include "../../functions/usuarios.php";
	include "../../functions/log_usuarios.php";
	include "../../functions/paquetes_sistema.php";

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

	$xxx_numero = file_get_contents('numero.txt');
	if($xxx_numero>1){
		echo "Fin de toda la importación";
		die;
	}
	$file_doc = "import/3-usuarios_administradores_".$xxx_numero.".csv";
	$handle = fopen($file_doc, "r");
	$columnData = array(
		'clave'=>array('alfanumerico','requerido','unique','unique_db','mayuscula'),
		'nombre'=>array('alfanumerico','requerido'),
		'apellido_paterno'=>array('alfanumerico','requerido'),
		'apellido_materno'=>array('alfanumerico','requerido'),
		'correo_electronico'=>array('correo_electronico','requerido'),
		'usuario'=>array('alfanumerico','requerido','unique','unique_db_usuario',''),
		'password'=>array('alfanumerico','requerido'),
		'status'=>array('status','requerido'),
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
	$tabla="administradores_sistema";
	$numarray=0;

	$success=true;
	$conexion->autocommit(FALSE);
	//$data=$_SESSION['data_import'];
	$countData=0;

	$primaria="id_empleado";
	foreach ($data as $key => $value) {
		foreach($value as $keyPrincipal => $atributo) {
			$value[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		if($tabla=='administradores_sistema' || $tabla=='empleados'){
			$otra_tabla=1;
			//tablas que se hacen insertan en multiples tablas
			//primero empleados
			$empleados=$value;
			unset($empleados['usuario']);
			unset($empleados['password']);
			$empleados['fechaR']=$fechaH; 
			$empleados['codigo_plataforma']=$codigo_plataforma;
			$empleados['referencia_importacion']=$cod32;
			
			$usuarios=$value;
			unset($usuarios['nombre']);
			unset($usuarios['apellido_paterno']);
			unset($usuarios['apellido_materno']);
			unset($usuarios['correo_electronico']);
			if($tabla=="administradores_sistema"){
				$tabla="administradores_sistema";
				$usuarios['id_perfil_usuario']=2;
				$empleados['notificaciones_sistema']=1;
			}
			if($tabla=="empleados"){
				$tabla="empleados";
				$usuarios['id_perfil_usuario']=3;
				//$usuarios['notificaciones_sistema']=1;
			}
			$usuarios['fechaR']=$fechaH;
			$usuarios['codigo_plataforma']=$codigo_plataforma;
			$usuarios['referencia_importacion']=$cod32;

			foreach($empleados as $keyPrincipal => $atributo) {
				$empleados[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
			}
			foreach($usuarios as $keyPrincipal => $atributo) {
				$usuarios[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
			}

			$fields_pdo = "`".implode('`,`', array_keys($empleados))."`";
			$values_pdo = "'".implode("','", $empleados)."'";
			$insert= "INSERT INTO empleados ($fields_pdo) VALUES ($values_pdo);";
			$insert=$conexion->query($insert);
			$num=$conexion->affected_rows;
			if(!$insert || $num=0){
				$success=false;
				echo "ERROR insert_empleados"; 
				var_dump($conexion->error);
			}

			$id_empleado=$empleados['id_empleado']=$conexion->insert_id;
			$fields_pdo = "`".implode('`,`', array_keys($empleados))."`";
			$values_pdo = "'".implode("','", $empleados)."'";
			$insert_historicos= "INSERT INTO empleados_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_historicos=$conexion->query($insert_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_historicos || $num=0){
				$success=false;
				echo "ERROR insert_empleados_historicos"; 
				var_dump($conexion->error);
			}

			$usuarios['id_empleado']=$id_empleado;
			$usuarios['tabla'] = 'empleados';
			$fields_pdo = "`".implode('`,`', array_keys($usuarios))."`";
			$values_pdo = "'".implode("','", $usuarios)."'";
			$insert= "INSERT INTO usuarios ($fields_pdo) VALUES ($values_pdo);";
			$insert=$conexion->query($insert);
			$num=$conexion->affected_rows;
			if(!$insert || $num=0){
				$success=false;
				echo "ERROR inset_usuarios"; 
				var_dump($conexion->error);
			}

			$usuarios['id_usuario']=$conexion->insert_id;
			$fields_pdo = "`".implode('`,`', array_keys($usuarios))."`";
			$values_pdo = "'".implode("','", $usuarios)."'";
			$insert_historicos= "INSERT INTO usuarios_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_historicos=$conexion->query($insert_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_historicos || $num=0){
				$success=false;
				echo "ERROR insert_usuarios_historicos"; 
				var_dump($conexion->error);
			}
			$insertArray[$numarray]['id']=$id_empleado;
			$insertArray[$numarray]['tabla']=$tabla;
			$insertArray[$numarray]['tipo']="Insert";
			$numarray=$numarray+1;
		}

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
					window.location.href = "../../importacionesSistema/tipos_actividades/import.php";
				</script>
				<?php
				die;
				

				if (copy($rutaTemporal, $rutaDestino)) {
					echo "-";
					$hora = date("H:i:s");
					echo $hora;
					sleep(1);
					?>
					<script type="text/javascript">
						location.reload();
					</script>
					<?php
					die;
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