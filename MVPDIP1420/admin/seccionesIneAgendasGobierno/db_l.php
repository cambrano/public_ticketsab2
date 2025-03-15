$_POST["seccion_ine_agenda_gobierno"][0]['codigo_plataforma']=$codigo_plataforma; 
		$fields_pdo = "`".implode('`,`', array_keys($_POST["seccion_ine_agenda_gobierno"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["seccion_ine_agenda_gobierno"][0])."'";
		$inset_secciones_ine_agendas_gobierno= "INSERT INTO secciones_ine_agendas_gobierno ($fields_pdo) VALUES ($values_pdo);";
		$conexion->autocommit(FALSE);
		$inset_secciones_ine_agendas_gobierno=$conexion->query($inset_secciones_ine_agendas_gobierno);
		$num=$conexion->affected_rows;
		if(!$inset_secciones_ine_agendas_gobierno || $num=0){
			$success=false;
			echo "ERROR inset_secciones_ine_agendas_gobierno"; 
			var_dump($conexion->error);
		}
		$id=$_POST["seccion_ine_agenda_gobierno"][0]['id_seccion_ine_agenda_gobierno']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST["seccion_ine_agenda_gobierno"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["seccion_ine_agenda_gobierno"][0])."'";
		$inset_secciones_ine_agendas_gobierno_historicos= "INSERT INTO secciones_ine_agendas_gobierno_historicos ($fields_pdo) VALUES ($values_pdo);";
		$inset_secciones_ine_agendas_gobierno_historicos=$conexion->query($inset_secciones_ine_agendas_gobierno_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_secciones_ine_agendas_gobierno_historicos || $num=0){
			$success=false;
			echo "ERROR inset_secciones_ine_agendas_gobierno_historicos"; 
			var_dump($conexion->error);
		}

		$ids_dependencias = explode(",", $_POST["seccion_ine_agenda_gobierno"][0]['ids_dependencias']);
		// Verificar si el primer elemento es una cadena vacía
		if (isset($ids_dependencias[0]) && $ids_dependencias[0] === "") {
			// Vaciar el array
			$ids_dependencias = array();
		}
		foreach ($ids_dependencias as $key => $value) {
			$dep['id_seccion_ine_agenda_gobierno'] = $id;
			$dep['id_dependencia'] = $value;
			$dep['fechaR']  = $fechaH;
			$dep['codigo_plataforma']=$codigo_plataforma;
			$fields_pdo = "`".implode('`,`', array_keys($dep))."`";
			$values_pdo = "'".implode("','", $dep)."'";
			$insert_secciones_ine_agendas_gobierno_dependencia= "INSERT INTO secciones_ine_agendas_gobierno_dependencias ($fields_pdo) VALUES ($values_pdo);";
			$insert_secciones_ine_agendas_gobierno_dependencia=$conexion->query($insert_secciones_ine_agendas_gobierno_dependencia);
			$num=$conexion->affected_rows;
			if(!$insert_secciones_ine_agendas_gobierno_dependencia || $num=0){
				$success=false;
				echo "ERROR insert_secciones_ine_agendas_gobierno_dependencia"; 
				var_dump($conexion->error);
			}
		}


		$ids_dependencias_generales = explode(",", $_POST["seccion_ine_agenda_gobierno"][0]['ids_dependencias']);
		if (isset($ids_dependencias_generales[0]) && $ids_dependencias_generales[0] === "") {
			// Vaciar el array
			$ids_dependencias_generales = array();
		}
		$ids_dependencias_generales[] = $_POST["seccion_ine_agenda_gobierno"][0]['id_dependencia'];
		foreach ($ids_dependencias_generales as $key => $value) {
			$dep['id_seccion_ine_agenda_gobierno'] = $id;
			$dep['id_dependencia'] = $value;
			$dep['fechaR']  = $fechaH;
			$dep['codigo_plataforma']=$codigo_plataforma;
			$fields_pdo = "`".implode('`,`', array_keys($dep))."`";
			$values_pdo = "'".implode("','", $dep)."'";
			$insert_secciones_ine_agendas_gobierno_dependencia_general= "INSERT INTO secciones_ine_agendas_gobierno_dependencias_generales ($fields_pdo) VALUES ($values_pdo);";
			$insert_secciones_ine_agendas_gobierno_dependencia_general=$conexion->query($insert_secciones_ine_agendas_gobierno_dependencia_general);
			$num=$conexion->affected_rows;
			if(!$insert_secciones_ine_agendas_gobierno_dependencia_general || $num=0){
				$success=false;
				echo "ERROR insert_secciones_ine_agendas_gobierno_dependencia_general"; 
				var_dump($conexion->error);
			}
		}
		
		foreach ($_POST['sub_eventos'] as $key => $value){ 
			$value['id_seccion_ine_agenda_gobierno'] = $id;
			$value['id_seccion_ine'] = $value['id_seccion_ine'];
			$value['id_municipio'] = $value['id_municipio'];
			$value['id_localidad'] = $value['id_localidad'];
			$value['fechaR']  = $fechaH;
			$value['codigo_plataforma']=$codigo_plataforma; 
			$fields_pdo = "`".implode('`,`', array_keys($value))."`";
			$values_pdo = "'".implode("','", $value)."'";
			$inset_secciones_ine_agendas_gobierno_puntos= "INSERT INTO secciones_ine_agendas_gobierno_locaciones ($fields_pdo) VALUES ($values_pdo);";
			$inset_secciones_ine_agendas_gobierno_puntos=$conexion->query($inset_secciones_ine_agendas_gobierno_puntos);
			$num=$conexion->affected_rows;
			if(!$inset_secciones_ine_agendas_gobierno_puntos || $num=0){
				$success=false;
				echo "ERROR insert_secciones_ine_agendas_gobierno_locaciones"; 
				var_dump($conexion->error);
			}
			$value['id_seccion_ine_agenda_gobierno_locacion']=$conexion->insert_id;
			$fields_pdo = "`".implode('`,`', array_keys($value))."`";
			$values_pdo = "'".implode("','", $value)."'";
			$inset_secciones_ine_agendas_gobierno_puntos_historicos= "INSERT INTO secciones_ine_agendas_gobierno_locaciones_historicos ($fields_pdo) VALUES ($values_pdo);";
			$inset_secciones_ine_agendas_gobierno_puntos_historicos=$conexion->query($inset_secciones_ine_agendas_gobierno_puntos_historicos);
			$num=$conexion->affected_rows;
			if(!$inset_secciones_ine_agendas_gobierno_puntos_historicos || $num=0){
				$success=false;
				echo "ERROR insert_secciones_ine_agendas_gobierno_locaciones_historicos"; 
				var_dump($conexion->error);
			}
		}