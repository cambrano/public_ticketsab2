<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/switch_operaciones.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos_categorias.php";
	$switch_operacionesPermisos = switch_operacionesPermisos();
	if( $switch_operacionesPermisos['evaluacion'] == false){
		echo "No tiene permiso.";
		die;
	}
	$id_seccion_ine_ciudadano = $_POST['tipo_categoria_ciudadano'][0]['id_seccion_ine_ciudadano'];
	$secciones_ine_ciudadanos_categoriasDatos = secciones_ine_ciudadanos_categoriasDatos('',$id_seccion_ine_ciudadano);
	$seccion_ine_ciudadanoDatos = seccion_ine_ciudadanoDatos($id_seccion_ine_ciudadano);

	$id_seccion_ine = $seccion_ine_ciudadanoDatos['id_seccion_ine'];
	$id_distrito_local = $seccion_ine_ciudadanoDatos['id_distrito_local'];
	$id_distrito_federal = $seccion_ine_ciudadanoDatos['id_distrito_federal'];
	$id_municipio = $seccion_ine_ciudadanoDatos['id_municipio'];

	$success = true;

	if(empty($secciones_ine_ciudadanos_categoriasDatos)){
		////insert
		foreach ($_POST['tipo_categoria_ciudadano'] as $key => $value) {
			if($value['valor'] == 1){
				$entra=true;
				$_POST['tipo_categoria_ciudadano'][$key]['id_seccion_ine'] = $id_seccion_ine;
				$_POST['tipo_categoria_ciudadano'][$key]['id_distrito_local'] = $id_distrito_local;
				$_POST['tipo_categoria_ciudadano'][$key]['id_distrito_federal'] = $id_distrito_federal;
				$_POST['tipo_categoria_ciudadano'][$key]['id_municipio'] = $id_municipio;

				$_POST['tipo_categoria_ciudadano'][$key]['codigo_plataforma'] = $codigo_plataforma; 
				$_POST['tipo_categoria_ciudadano'][$key]['fechaR'] = $fechaH;
				$_POST['tipo_categoria_ciudadano'][$key]['hora'] = $fechaSH;
				$_POST['tipo_categoria_ciudadano'][$key]['fecha'] = $fechaSF;
				$_POST['tipo_categoria_ciudadano'][$key]['fecha_hora'] = $fechaH;

				$fields_pdo = "`".implode('`,`', array_keys($_POST['tipo_categoria_ciudadano'][$key]))."`";
				$values_pdo = "'".implode("','", $_POST['tipo_categoria_ciudadano'][$key])."'";
				$insert_seccion_ine_ciudadano_categoria= "INSERT INTO secciones_ine_ciudadanos_categorias ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$insert_seccion_ine_ciudadano_categoria=$conexion->query($insert_seccion_ine_ciudadano_categoria);
				$num=$conexion->affected_rows;
				if(!$insert_seccion_ine_ciudadano_categoria || $num=0){
					$success=false;
					echo "ERROR insert_seccion_ine_ciudadano_categoria"; 
					var_dump($conexion->error);
				}

				$id = $_POST['tipo_categoria_ciudadano'][$key]['id_seccion_ine_ciudadano_categoria']=$conexion->insert_id;
				$fields_pdo = "`".implode('`,`', array_keys($_POST['tipo_categoria_ciudadano'][$key]))."`";
				$values_pdo = "'".implode("','", $_POST['tipo_categoria_ciudadano'][$key])."'";
				$insert_seccion_ine_ciudadano_categoria_historico= "INSERT INTO secciones_ine_ciudadanos_categorias_historicos ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);

				$insert_seccion_ine_ciudadano_categoria_historico=$conexion->query($insert_seccion_ine_ciudadano_categoria_historico);
				$num=$conexion->affected_rows;
				if(!$insert_seccion_ine_ciudadano_categoria_historico || $num=0){
					$success=false;
					echo "ERROR insert_seccion_ine_ciudadano_categoria_historico"; 
					var_dump($conexion->error);
				}
			}
		}
	}else{
		///update
		/*
		foreach ($_POST['tipo_categoria_ciudadano'] as $key => $value) {
			$categorias_ciudadanos[$value['id_tipo_categoria_ciudadano']]=$value;
		}
		*/

		foreach ($secciones_ine_ciudadanos_categoriasDatos as $key => $value) {
			$categorias_ciudadanos[$value['id_tipo_categoria_ciudadano']]=$value;
		}

		foreach ($_POST['tipo_categoria_ciudadano'] as $key => $value) {
			if($categorias_ciudadanos[$value['id_tipo_categoria_ciudadano']]['valor'] != $value['valor']){
				//diferente

				if($categorias_ciudadanos[$value['id_tipo_categoria_ciudadano']]['id']!="" && $value['valor']==0 ){
					//elimina
					$entra = true;
					$id = $categorias_ciudadanos[$value['id_tipo_categoria_ciudadano']]['id'];
					$delete_seccion_ine_ciudadano_categoria_historico = "DELETE FROM secciones_ine_ciudadanos_categorias  WHERE  id='$id' ";
					$conexion->autocommit(FALSE);
					$delete_seccion_ine_ciudadano_categoria_historico=$conexion->query($delete_seccion_ine_ciudadano_categoria_historico);
					$num=$conexion->affected_rows;
					if(!$delete_seccion_ine_ciudadano_categoria_historico || $num=0){
						$success=false;
						echo "ERROR delete_seccion_ine_ciudadano_categoria_historico"; 
						var_dump($conexion->error);
					}

				}

				if($categorias_ciudadanos[$value['id_tipo_categoria_ciudadano']]['id']!="" && $value['valor']==1 ){
					//no hace nada
				}

				if($categorias_ciudadanos[$value['id_tipo_categoria_ciudadano']]['id']=="" && $value['valor']==1 ){
					//inserta
					$entra = true;
					$categorias_ciudadanos[$value['id_tipo_categoria_ciudadano']]['id_seccion_ine_ciudadano'] = $id_seccion_ine_ciudadano;
					$categorias_ciudadanos[$value['id_tipo_categoria_ciudadano']]['id_tipo_categoria_ciudadano']=$value['id_tipo_categoria_ciudadano'];
					$categorias_ciudadanos[$value['id_tipo_categoria_ciudadano']]['id_seccion_ine'] = $id_seccion_ine;
					$categorias_ciudadanos[$value['id_tipo_categoria_ciudadano']]['id_municipio'] = $id_municipio;
					$categorias_ciudadanos[$value['id_tipo_categoria_ciudadano']]['id_distrito_local'] = $id_distrito_local;
					$categorias_ciudadanos[$value['id_tipo_categoria_ciudadano']]['id_distrito_federal'] = $id_distrito_federal;
					$categorias_ciudadanos[$value['id_tipo_categoria_ciudadano']]['valor']=$value['valor'];
					$categorias_ciudadanos[$value['id_tipo_categoria_ciudadano']]['fecha'] = $fechaSF;
					$categorias_ciudadanos[$value['id_tipo_categoria_ciudadano']]['hora'] = $fechaSH;
					$categorias_ciudadanos[$value['id_tipo_categoria_ciudadano']]['fecha_hora'] = $fechaH;
					$categorias_ciudadanos[$value['id_tipo_categoria_ciudadano']]['codigo_plataforma'] = $codigo_plataforma;
					$categorias_ciudadanos[$value['id_tipo_categoria_ciudadano']]['fechaR'] = $fechaH;
					$categorias_ciudadanos[$value['id_tipo_categoria_ciudadano']]['clave']=$value['clave'];

					$fields_pdo = "`".implode('`,`', array_keys($categorias_ciudadanos[$value['id_tipo_categoria_ciudadano']]))."`";
					$values_pdo = "'".implode("','", $categorias_ciudadanos[$value['id_tipo_categoria_ciudadano']])."'";
					$insert_seccion_ine_ciudadano_categoria= "INSERT INTO secciones_ine_ciudadanos_categorias ($fields_pdo) VALUES ($values_pdo);";
					$conexion->autocommit(FALSE);
					$insert_seccion_ine_ciudadano_categoria=$conexion->query($insert_seccion_ine_ciudadano_categoria);
					$num=$conexion->affected_rows;
					if(!$insert_seccion_ine_ciudadano_categoria || $num=0){
						$success=false;
						echo "ERROR insert_seccion_ine_ciudadano_categoria"; 
						var_dump($conexion->error);
					}

					$id = $categorias_ciudadanos[$value['id_tipo_categoria_ciudadano']]['id_seccion_ine_ciudadano_categoria']=$conexion->insert_id;
					$fields_pdo = "`".implode('`,`', array_keys($_POST['tipo_categoria_ciudadano'][$key]))."`";
					$values_pdo = "'".implode("','", $_POST['tipo_categoria_ciudadano'][$key])."'";
					$insert_seccion_ine_ciudadano_categoria_historico= "INSERT INTO secciones_ine_ciudadanos_categorias_historicos ($fields_pdo) VALUES ($values_pdo);";
					$conexion->autocommit(FALSE);

					$insert_seccion_ine_ciudadano_categoria_historico=$conexion->query($insert_seccion_ine_ciudadano_categoria_historico);
					$num=$conexion->affected_rows;
					if(!$insert_seccion_ine_ciudadano_categoria_historico || $num=0){
						$success=false;
						echo "ERROR insert_seccion_ine_ciudadano_categoria_historico"; 
						var_dump($conexion->error);
					}
				}
			}
		}
	}

	if(!$entra){
		echo "SI";
		die;
	}
	if($success){
		$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_ciudadanos_categorias',$id_seccion_ine_ciudadano,'Update','',$fechaH);
		if($log==true){
			echo "SI";
			$conexion->commit();
			$conexion->close();
		}else{
			echo "NO";
			$conexion->rollback();
			$conexion->close();
		}
	}else{
		echo "NO";
		$conexion->rollback();
		$conexion->close();
	}