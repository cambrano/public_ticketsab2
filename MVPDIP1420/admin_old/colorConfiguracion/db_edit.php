<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/partidos_legados.php";
	include __DIR__."/../functions/configuracion_matriz_rentabilidad_secciones_ine_2018.php";
	include __DIR__."/../functions/configuracion_matriz_rentabilidad_secciones_ine_2021.php";
	include __DIR__."/../functions/partidos_2018.php";
	include __DIR__."/../functions/partidos_2021.php";

	include __DIR__."/../functions/estados.php";
	include __DIR__."/../functions/municipios.php";
	include __DIR__."/../functions/distritos_locales.php";
	include __DIR__."/../functions/distritos_federales.php";

	$_SESSION['Paguinasub']="colorConfiguracion/index.php";
	if($_COOKIE["id_usuario"]!=1){
		die;
	}
	//metemos los valores para que se no tengamos error
	if(!empty($_POST["color_configuracion"][0]['id_partido_legado'])){
		$id_partido_legado = $_POST["color_configuracion"][0]['id_partido_legado'];

		$partido_legadoDatos = partido_legadoDatos($id_partido_legado);
		$nombre_corto = $partido_legadoDatos['nombre_corto'];

		
		//! Militantes Partidos
		$success=true;
		$conexion->autocommit(FALSE);
		$update_militantes_partidos = "UPDATE `militantes_partidos` SET `id_partido_legado` = '{$id_partido_legado}' WHERE (`id` <> '0');";
		$update_militantes_partidos=$conexion->query($update_militantes_partidos);
		$num=$conexion->affected_rows;
		if(!$update_militantes_partidos || $num=0){
			$success=false;
			echo "<br>";
			echo "ERROR update_militantes_partidos"; 
			var_dump($conexion->error);
		}

		//! Votaciones 2018
		$update_partidos2018 = "UPDATE `partidos_2018` SET `principal` = NULL WHERE (`id` <> '0');";
		$update_partidos2018=$conexion->query($update_partidos2018);
		$num=$conexion->affected_rows;
		if(!$update_partidos2018 || $num=0){
			$success=false;
			echo "<br>";
			echo "ERROR update_partidos2018"; 
			var_dump($conexion->error);
		}

		$update_partidos2018 = "UPDATE `partidos_2018` SET `principal` = 1 WHERE (`id` <> '0' AND nombre_corto ='{$nombre_corto}' );";
		$update_partidos2018=$conexion->query($update_partidos2018);
		$num=$conexion->affected_rows;
		if(!$update_partidos2018 || $num=0){
			$success=false;
			echo "<br>";
			echo "ERROR update_partidos2018"; 
			var_dump($conexion->error);
		}
		//! Votaciones 2021
		$update_partidos2021 = "UPDATE `partidos_2021` SET `principal` = NULL WHERE (`id` <> '0');";
		$update_partidos2021=$conexion->query($update_partidos2021);
		$num=$conexion->affected_rows;
		if(!$update_partidos2021 || $num=0){
			$success=false;
			echo "<br>";
			echo "ERROR update_partidos2021"; 
			var_dump($conexion->error);
		}

		$update_partidos2021 = "UPDATE `partidos_2021` SET `principal` = 1 WHERE (`id` <> '0' AND nombre_corto ='{$nombre_corto}' );";
		$update_partidos2021=$conexion->query($update_partidos2021);
		$num=$conexion->affected_rows;
		if(!$update_partidos2021 || $num=0){
			$success=false;
			echo "<br>";
			echo "ERROR update_partidos2021"; 
			var_dump($conexion->error);
		}

		//! Matriz rentabilidad 2018
		$configuracion_matriz_rentabilidad_secciones_ine_2018Datos=configuracion_matriz_rentabilidad_secciones_ine_2018Datos();
		unset($partido);
		$partidos_2018CompletaDatos = partidos_2018CompletaDatos('',$nombre_corto);
		foreach ($partidos_2018CompletaDatos as $key => $value) {
			$partido[$value['tipo']]['id_partido'] = $value['id'] ;
		}

		unset($config_matriz_2018);
		unset($valueSets);
		$config_matriz_2018['id'] = $configuracion_matriz_rentabilidad_secciones_ine_2018Datos['id'];
		foreach ($partido as $key => $value) {
			if($key == 0){
				$config_matriz_2018['id_partido_2018_ayuntamiento'] = $value['id_partido'];
			}
			if($key == 1){
				$config_matriz_2018['id_partido_2018_distrito_local'] = $value['id_partido'];
			}
			if($key == 2){
				$config_matriz_2018['id_partido_2018_distrito_federal'] = $value['id_partido'];
			}
		}
		$config_matriz_2018['id_partido_legado'] = $id_partido_legado;

		foreach($config_matriz_2018 as $keyPrincipal => $atributos) {
			if($keyPrincipal=='id'){
				$id = $atributos;
			}else{
				$valueSets[] = $keyPrincipal . " = '" . $atributos . "'";
			}
		}
		$update_configuracion_matriz2018 = "UPDATE configuracion_matriz_rentabilidad_secciones_ine_2018 SET ". join(",",$valueSets) . " WHERE id<>0";
		$update_configuracion_matriz2018=$conexion->query($update_configuracion_matriz2018);
		$num=$conexion->affected_rows;
		if(!$update_configuracion_matriz2018 || $num=0){
			$success=false;
			echo "<br>";
			echo "ERROR update_configuracion_matriz2018"; 
			var_dump($conexion->error);
		}

		//! Matriz rentabilidad 2021
		$configuracion_matriz_rentabilidad_secciones_ine_2021Datos=configuracion_matriz_rentabilidad_secciones_ine_2021Datos();
		unset($partido);
		$partidos_2021CompletaDatos = partidos_2021CompletaDatos('',$nombre_corto);
		foreach ($partidos_2021CompletaDatos as $key => $value) {
			$partido[$value['tipo']]['id_partido'] = $value['id'] ;
		}

		unset($config_matriz_2021);
		unset($valueSets);
		$config_matriz_2021['id'] = $configuracion_matriz_rentabilidad_secciones_ine_2021Datos['id'];
		foreach ($partido as $key => $value) {
			if($key == 0){
				$config_matriz_2021['id_partido_2021_ayuntamiento'] = $value['id_partido'];
			}
			if($key == 1){
				$config_matriz_2021['id_partido_2021_distrito_local'] = $value['id_partido'];
			}
			if($key == 2){
				$config_matriz_2021['id_partido_2021_distrito_federal'] = $value['id_partido'];
			}
		}
		$config_matriz_2021['id_partido_legado'] = $id_partido_legado;

		foreach($config_matriz_2021 as $keyPrincipal => $atributos) {
			if($keyPrincipal=='id'){
				$id = $atributos;
			}else{
				$valueSets[] = $keyPrincipal . " = '" . $atributos . "'";
			}
		}
		$update_configuracion_matriz2021 = "UPDATE configuracion_matriz_rentabilidad_secciones_ine_2021 SET ". join(",",$valueSets) . " WHERE id<>0";
		$update_configuracion_matriz2021=$conexion->query($update_configuracion_matriz2021);
		$num=$conexion->affected_rows;
		if(!$update_configuracion_matriz2021 || $num=0){
			$success=false;
			echo "<br>";
			echo "ERROR update_configuracion_matriz2021"; 
			var_dump($conexion->error);
		}

		///Creamos el archivo
		$estadoDatos = estadoDatos($_POST["territorio"][0]['id_estado']);
		if($_POST["territorio"][0]['id_municipio']!=''){
			$municipioCoordenadas = municipioCoordenadas($_POST["territorio"][0]['id_municipio']);
			$texto_custom[0] = '$tipo_uso_plataforma="municipio";';

			$texto_custom[1] = '$id_estado="'.$_POST["territorio"][0]['id_estado'].'";';
			$texto_custom[2] = '$id_municipio="'.$_POST["territorio"][0]['id_municipio'].'";';
			$texto_custom[3] = '$latitud="'.$municipioCoordenadas['lat'].'";';
			$texto_custom[4] = '$longitud="'.$municipioCoordenadas['lng'].'";';
			$texto_custom[5] = '$estado_nombre="'.$estadoDatos['abreviacion'].'";';
			$texto_custom[6] = '$extranjeros_mode="false";';


		}elseif($_POST["territorio"][0]['id_distrito_local']!='' ){
			$distrito_localDatos = distrito_localDatos($_POST["territorio"][0]['id_distrito_local']);
			$texto_custom[0] = '$tipo_uso_plataforma="distrito_local";';

			$texto_custom[1] = '$id_estado="'.$_POST["territorio"][0]['id_estado'].'";';
			$texto_custom[2] = '$id_distrito_local="'.$_POST["territorio"][0]['id_distrito_local'].'";';
			$texto_custom[3] = '$latitud="'.$distrito_localDatos['latitud'].'";';
			$texto_custom[4] = '$longitud="'.$distrito_localDatos['longitud'].'";';
			$texto_custom[5] = '$estado_nombre="'.$estadoDatos['abreviacion'].'";';
			$texto_custom[6] = '$extranjeros_mode="false";';

		}elseif($_POST["territorio"][0]['id_distrito_federal']!='' ){
			$distrito_federalDatos = distrito_federalDatos($_POST["territorio"][0]['id_distrito_federal']);
			$texto_custom[0] = '$tipo_uso_plataforma="distrito_federal";';

			$texto_custom[1] = '$id_estado="'.$_POST["territorio"][0]['id_estado'].'";';
			$texto_custom[2] = '$id_distrito_local="'.$_POST["territorio"][0]['id_distrito_federal'].'";';
			$texto_custom[3] = '$latitud="'.$distrito_federalDatos['latitud'].'";';
			$texto_custom[4] = '$longitud="'.$distrito_federalDatos['longitud'].'";';
			$texto_custom[5] = '$estado_nombre="'.$estadoDatos['abreviacion'].'";';
			$texto_custom[6] = '$extranjeros_mode="false";';
		}else{
			$texto_custom[0] = '$tipo_uso_plataforma="all";';
			$texto_custom[1] = '$id_estado="'.$_POST["territorio"][0]['id_estado'].'";';
			$texto_custom[2] = '$latitud="'.$estadoDatos['latitud'].'";';
			$texto_custom[3] = '$longitud="'.$estadoDatos['longitud'].'";';
			$texto_custom[4] = '$estado_nombre="'.$estadoDatos['abreviacion'].'";';
			$texto_custom[5] = '$extranjeros_mode="false";';
		}
		$file = __DIR__."/../keySistema/nf4WUJ1540838393iaHbsU1540838393.php";
		$fp = fopen($file, "r");
		$line_inicio = 1;
		$line_fin = 30;
		while (($line = stream_get_line($fp, 1024 * 1024, "\n")) !== false) {
			$line_inicio ++;
			if($line_inicio <=$line_fin){
				$text[] = $line;
			}
		}
		//borramos el archivo
		$arch = fopen ($file, "w+");
		fwrite($arch, "");
		fclose($arch);
		//abrimos el archivo
		$fh = fopen($file,"a+");
		foreach ($text as $key => $value) {
			fwrite($fh,$value.PHP_EOL);
		}
		fwrite($fh,''.PHP_EOL);
		fwrite($fh,''.PHP_EOL);
		fwrite($fh,''.PHP_EOL);
		fwrite($fh,''.PHP_EOL);
		fwrite($fh,''.PHP_EOL);
		fwrite($fh,''.PHP_EOL);
		fwrite($fh,''.PHP_EOL);
		fwrite($fh,''.PHP_EOL);
		fwrite($fh,''.PHP_EOL);
		fwrite($fh,''.PHP_EOL);
		foreach ($texto_custom as $key => $value) {
			fwrite($fh,$value.PHP_EOL);
		}
		fwrite($fh,''.PHP_EOL);
		fwrite($fh,'?>'.PHP_EOL);
		fclose($fh);

		unset($text);
		$file = __DIR__."/../../ciudadano/keySistema/nf4WUJ1540838393iaHbsU1540838393.php";
		$fp = fopen($file, "r");
		$line_inicio = 1;
		$line_fin = 30;
		while (($line = stream_get_line($fp, 1024 * 1024, "\n")) !== false) {
			$line_inicio ++;
			if($line_inicio <=$line_fin){
				$text[] = $line;
			}
		}
		//borramos el archivo
		$arch = fopen ($file, "w+");
		fwrite($arch, "");
		fclose($arch);
		//abrimos el archivo
		$fh = fopen($file,"a+");
		foreach ($text as $key => $value) {
			fwrite($fh,$value.PHP_EOL);
		}
		fwrite($fh,''.PHP_EOL);
		fwrite($fh,''.PHP_EOL);
		fwrite($fh,''.PHP_EOL);
		fwrite($fh,''.PHP_EOL);
		fwrite($fh,''.PHP_EOL);
		fwrite($fh,''.PHP_EOL);
		fwrite($fh,''.PHP_EOL);
		fwrite($fh,''.PHP_EOL);
		fwrite($fh,''.PHP_EOL);
		fwrite($fh,''.PHP_EOL);
		foreach ($texto_custom as $key => $value) {
			fwrite($fh,$value.PHP_EOL);
		}
		fwrite($fh,''.PHP_EOL);
		fwrite($fh,'?>'.PHP_EOL);
		fclose($fh);


		if($success){
			echo "SI!";
			$conexion->commit();
			$conexion->close();
		}else{
			echo "NO";
			$conexion->rollback();
			$conexion->close();
		}


		die;
	}

