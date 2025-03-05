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
	$fin_importacion=21;
	
	if($xxx_numero > $fin_importacion){
		echo "Fin de toda la importación";
		die;
	}
	$file_doc = "import/29_secciones_ine_ciudadanos_".$xxx_numero.".csv";
	//$file_doc = "import/".$xxx_numero.".csv";
	$handle = fopen($file_doc, "r");
	$columnData = array(
		'clave'=>array('alfanumerico','requerido','unique','unique_db','mayuscula'),
		'folio' =>array('alfanumerico','requerido'),

		'clave_elector' =>array('alfanumerico','requerido'),
		'ocr' =>array('alfanumerico',''),
		'clave_secciones_ine'=>array('alfanumerico','requerido','buscar_clave','mayuscula'),
		'manzana' =>array('alfanumerico',''),
		'vigencia' =>array('alfanumerico','requerido'),

		'curp' =>array('alfanumerico',''),
		'rfc' =>array('alfanumerico',''),

		'clave_secciones_ine_ciudadanos'=>array('alfanumerico','','','mayuscula','relacionado'),
		'clave_tipos_ciudadanos'=>array('alfanumerico','requerido','buscar_clave','mayuscula'),
		'nombre' =>array('alfanumerico','requerido'),
		'apellido_paterno' =>array('alfanumerico','requerido'),
		'apellido_materno' =>array('alfanumerico','requerido'),
		'sexo' =>array('alfanumerico','requerido'),
		'fecha_nacimiento'=>array('fecha','requerido'),
		'correo_electronico'=>array('correo_electronico','requerido'),
		'whatsapp'=>array('telefono','requerido'),
		'telefono'=>array('telefono',''),
		'celular'=>array('telefono',''),

		'usuario'=>array('alfanumerico','requerido','unique','unique_db_usuario',''),
		'password'=>array('alfanumerico','requerido'),
		'status'=>array('status','requerido'),

		'pais'=>array('alfanumerico','requerido'),
		'estado'=>array('alfanumerico','requerido'),
		'municipio'=>array('alfanumerico','requerido'),
		'localidad'=>array('alfanumerico','requerido'),
		'calle'=>array('alfanumerico','requerido'),
		'num_ext'=>array('alfanumerico',''),
		'num_int'=>array('alfanumerico',''),
		'colonia'=>array('alfanumerico','requerido'),
		'codigo_postal'=>array('numerico','requerido'),

		'latitud'=>array('alfanumerico','opcional_1'),
		'longitud'=>array('alfanumerico','opcional_2'),

		'entrega'=>array('alfanumerico','requerido'),
		'recibe'=>array('alfanumerico','requerido'),
		'casilla'=>array('alfanumerico','requerido'),
		'evaluacion'=>array('alfanumerico','requerido'),

		'observaciones' =>array('alfanumerico',''),
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
	$tabla="secciones_ine_ciudadanos";
	$numarray=0;

	$success=true;
	$conexion->autocommit(FALSE);
	//$data=$_SESSION['data_import'];
	$countData=0;

	$primaria="id_seccion_ine_ciudadano";
	foreach ($data as $key => $value) {
		foreach($value as $keyPrincipal => $atributo) {
			$value[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		unset($value[$primaria]);
		unset($value['error']);

		$value['seccion'] = $value['clave_secciones_ine'];
		$value['clave_localidad'] = $value['localidad'];
		$value['clave_municipio'] = $value['municipio'];

		//cambios de columnas
		//$value['id_seccion_ine']=claveId($value['clave_secciones_ine'],'secciones_ine');
		if($array_secciones_ine[$value['clave_secciones_ine']] ==""){
			$claveIdDatos = claveIdDatos($value['clave_secciones_ine'],'secciones_ine');
			$latitud = $value['latitud'];
			$longitud = $value['longitud'];
			$array_secciones_ine_datos[$value['clave_secciones_ine']]['id_seccion_ine'] = $value['id_seccion_ine'] = $claveIdDatos['id'];
			$array_secciones_ine_datos[$value['clave_secciones_ine']]['id_distrito_federal'] = $value['id_distrito_federal'] = $claveIdDatos['id_distrito_federal'];
			$array_secciones_ine_datos[$value['clave_secciones_ine']]['id_distrito_local'] = $value['id_distrito_local'] = $claveIdDatos['id_distrito_local'];

			$array_secciones_ine_datos[$value['clave_secciones_ine']]['id_cuartel'] = $value['id_cuartel'] = $claveIdDatos['id_cuartel'];
			
			$latitud_seccion = $array_secciones_ine_datos[$value['clave_secciones_ine']]['latitud'] = $claveIdDatos['latitud'];
			$longitud_seccion = $array_secciones_ine_datos[$value['clave_secciones_ine']]['longitud'] = $claveIdDatos['longitud'];
			unset($value['clave_secciones_ine']);
		}else{
			$latitud = $value['latitud'];
			$longitud = $value['longitud'];
			$value['id_seccion_ine'] = $array_secciones_ine_datos[$value['clave_secciones_ine']]['id_seccion_ine'];
			$value['id_distrito_federal'] = $array_secciones_ine_datos[$value['clave_secciones_ine']]['id_distrito_federal'];
			$value['id_distrito_local'] = $array_secciones_ine_datos[$value['clave_secciones_ine']]['id_distrito_local'];
			$value['id_cuartel'] = $array_secciones_ine_datos[$value['clave_secciones_ine']]['id_cuartel'];
			$latitud_seccion = $array_secciones_ine_datos[$value['clave_secciones_ine']]['latitud'];
			$longitud_seccion = $array_secciones_ine_datos[$value['clave_secciones_ine']]['longitud'];

			unset($value['clave_secciones_ine']);
		}

		$value['distancia_m'] = distanceCalculation($latitud, $longitud, $latitud_seccion, $longitud_seccion,'m',3);
		$value['distancia_km'] = distanceCalculation($latitud, $longitud, $latitud_seccion, $longitud_seccion,'km',3);

		/*
		if($value['pais']!=""){
			$value['id_pais']=paisId($value['pais']);
			unset($value['pais']);
		}
		if($value['estado']!=""){
			if($value['estado']=="México" || $value['estado']=="méxico"){
				$value['id_estado']=estadoId($value['estado'],1);
				unset($value['estado']);
			}else{
				$value['id_estado']=estadoId($value['estado'],"");
				unset($value['estado']);
			}
		}
		*/

		$value['id_pais']=141;
		unset($value['pais']);
		$value['id_estado']=$id_estado;
		unset($value['estado']);

		if($array_tipos_ciudadanos[$value['clave_tipos_ciudadanos']] ==""){
			$value['id_tipo_ciudadano'] = claveId($value['clave_tipos_ciudadanos'],'tipos_ciudadanos');
			$array_tipos_ciudadanos[$value['clave_tipos_ciudadanos']] = $value['id_tipo_ciudadano'];
			unset($value['clave_tipos_ciudadanos']);
		}else{
			$value['id_tipo_ciudadano'] = $array_tipos_ciudadanos[$value['clave_tipos_ciudadanos']];
			unset($value['clave_tipos_ciudadanos']);
		}

		

		if($value['municipio']!=""){
			$value['id_municipio']=municipioIdClave($value['municipio'],$value['id_estado']);
			$value['clave_municipio']=$value['municipio'];
			unset($value['municipio']);
		}

		unset($value['municipio']);

		if($value['localidad']!=""){
			$value['id_localidad']=localidadIdClave($value['localidad'],$value['id_estado'],$value['id_municipio']);
			$value['clave_localidad']=$value['localidad'];
			unset($value['localidad']);
		}


		if($value['clave_secciones_ine_ciudadanos']!=""){
			if($array_secciones_ine_ciudadanos[ $value['clave_secciones_ine_ciudadanos'] ] ==""){
				if( claveId($value['clave_secciones_ine_ciudadanos'],'secciones_ine_ciudadanos') =="" ){
					unset($value['clave_secciones_ine_ciudadanos']);
				}else{
					$value['id_seccion_ine_ciudadano_compartido'] = claveId($value['clave_secciones_ine_ciudadanos'],'secciones_ine_ciudadanos');
					$array_secciones_ine_ciudadanos[$value['clave_secciones_ine_ciudadanos']] = $value['id_seccion_ine_ciudadano_compartido'];
					unset($value['clave_secciones_ine_ciudadanos']);
				}
			}else{
				$value['id_seccion_ine_ciudadano_compartido'] = $array_secciones_ine_ciudadanos[$value['clave_secciones_ine_ciudadanos']];
				unset($value['clave_secciones_ine_ciudadanos']);
			}
		}else{
			unset($value['clave_secciones_ine_ciudadanos']);
		}

		unset($usuarios);
		$usuarios['clave'] = $value['clave'];
		$usuarios['usuario'] = $value['usuario'];
		$usuarios['password'] = $value['password'];
		$usuarios['status'] = $value['status'];
		$usuarios['id_perfil_usuario'] = 4;
		$usuarios['tabla'] = "secciones_ine_ciudadanos";
		$usuarios['fechaR'] = $fechaH;
		$usuarios['codigo_plataforma'] = $codigo_plataforma;
		$usuarios['referencia_importacion'] = $cod32;

		$length=6; 
		$mk_id=time();
		$gen_id = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); 
		$gen_id1=$gen_id.$mk_id; 
		$length=6; 
		$mk_id=time()*2*36*12/3;
		$gen_id3 = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz0123456789"), 0, $length);
		$length=6; 
		$mk_id=time()*2*36*12/2;
		$gen_id4 = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz0123456789"), 0, $length); 
		$usuarios['identificador']=$gen_id4.'_'.$gen_id1.$gen_id3;

		unset($value['usuario']);
		unset($value['password']);

		unset($usuarios_permisos);
		$usuarios_permisos['entrega'] = $value['entrega'];
		$usuarios_permisos['recibe'] = $value['recibe'];
		$usuarios_permisos['casilla'] = $value['casilla'];
		$usuarios_permisos['evaluacion'] = $value['evaluacion'];
		unset($value['entrega']);
		unset($value['recibe']);
		unset($value['casilla']);
		unset($value['evaluacion']);


		$length=6; 
		$mk_id=time();
		$gen_id = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); 
		$gen_id1=$gen_id.$mk_id; 

		$length=6; 
		$mk_id=time()*2*36*12/3;
		$gen_id = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz"), 0, $length); 
		$gen_id2=$gen_id.$mk_id; 
		$value['codigo_seccion_ine_ciudadano']=$gen_id1.$gen_id2."_".$codigo_plataforma; 


		 

		$value['fechaU']=$fechaH; 
		$value['codigo_plataforma']=$codigo_plataforma;
		$value['referencia_importacion']=$cod32;
		$value['fechaR']=$fechaH;
		$value['nombre_completo']=$value['nombre'].' '.$value['apellido_paterno'].' '.$value['apellido_materno'];
		$value['medio_registro'] = 2;
		$value['distancia_alert'] = 0;

		$diff = (date('Y') - date('Y',strtotime($value['fecha_nacimiento'])));
		if($diff==""){
			$diff=0;
		}
		$value['edad'] = $diff;
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

		$usuarios['referencia_importacion']=$cod32;
		$usuarios['id_seccion_ine_ciudadano'] = $value[$primaria];

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


		////permisos
		$usuarios_permisos['referencia_importacion']=$cod32;
		$usuarios_permisos['id_seccion_ine_ciudadano'] = $usuarios['id_seccion_ine_ciudadano'];
		$usuarios_permisos['id_usuario'] = $usuarios['id_usuario'];
		$usuarios_permisos['fechaR']=$fechaH;
		$usuarios_permisos['codigo_plataforma']=$codigo_plataforma;

		if(strtolower($usuarios_permisos['entrega']) =="si" ){
			$usuarios_permisos['entrega']=1;
		}else{
			$usuarios_permisos['entrega']=0;
		}


		if(strtolower($usuarios_permisos['recibe']) =="si" ){
			$usuarios_permisos['recibe']=1;
		}else{
			$usuarios_permisos['recibe']=0;
		}

		if(strtolower($usuarios_permisos['casilla']) =="si" ){
			$usuarios_permisos['casilla']=1;
		}else{
			$usuarios_permisos['casilla']=0;
		}
		if(strtolower($usuarios_permisos['evaluacion']) =="si" ){
			$usuarios_permisos['evaluacion']=1;
		}else{
			$usuarios_permisos['evaluacion']=0;
		}


		$fields_pdo = "`".implode('`,`', array_keys($usuarios_permisos))."`";
		$values_pdo = "'".implode("','", $usuarios_permisos)."'";
		$insert_usuarios_permsiso= "INSERT INTO secciones_ine_ciudadanos_permisos ($fields_pdo) VALUES ($values_pdo);";
		$conexion->autocommit(FALSE);
		$insert_usuarios_permsiso=$conexion->query($insert_usuarios_permsiso);
		$num=$conexion->affected_rows;
		if(!$insert_usuarios_permsiso || $num=0){
			$success=false;
			echo "ERROR insert_usuarios_permsiso"; 
			var_dump($conexion->error);
		}

		$usuarios_permisos['id_seccion_ine_ciudadano_permiso']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($usuarios_permisos))."`";
		$values_pdo = "'".implode("','", $usuarios_permisos)."'";
		$insert_usuarios_permiso_historicos= "INSERT INTO secciones_ine_ciudadanos_permisos_historicos ($fields_pdo) VALUES ($values_pdo);";
		$conexion->autocommit(FALSE);

		$insert_usuarios_permiso_historicos=$conexion->query($insert_usuarios_permiso_historicos);
		$num=$conexion->affected_rows;
		if(!$insert_usuarios_permiso_historicos || $num=0){
			$success=false;
			echo "ERROR insert_usuarios_permiso_historicos"; 
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