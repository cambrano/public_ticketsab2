<?php
	@session_start();
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/secciones_ine_giras.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"secciones_ine_giras",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}
	$latitud = null;
	foreach ($_SESSION['puntos_line'] as $key => $value){
		$compara = false;
		if($value['status']==1 && empty($latitud) ){
			$latitud = true;
			$_POST["seccion_ine_gira"][0]['latitud'] = $value['latitud'];
			$_POST["seccion_ine_gira"][0]['longitud'] = $value['longitud'];
		}
		
		if(!empty($value['id']) && $value['status']== 0 ){
			//eliminar secciones_ine_giras_puntos
			$lineas_array['delete'][] = $value;
			$compara = true;
		}
		if(empty($value['id']) && $value['status'] == 1 ){
			//agregar secciones_ine_giras_puntos
			$lineas_array['add'][] = $value;
			$compara = true;
		}
		if(!empty($value['id'] && $value['status']==1 )){
			unset($value['status']);
			if(registrosCompara ('secciones_ine_giras_puntos',$value,1)){
				//editar secciones_ine_giras_puntos
				$lineas_array['edit'][] = $value;
				$compara = true;
			}
		}
	}
	//metemos los valores para que se no tengamos error
	foreach($_POST["seccion_ine_gira"][0] as $keyPrincipal => $atributo) {
		$_POST["seccion_ine_gira"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$seccion_ine_giraClaveVerificacion=seccion_ine_giraClaveVerificacion($_POST["seccion_ine_gira"][0]["clave"],$_POST["seccion_ine_gira"][0]['id'],1);
	if($seccion_ine_giraClaveVerificacion){
		$claveF= clave("secciones_ine_giras");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["seccion_ine_gira"][0]["clave"] = $claveF["clave"];
		}
	}

	if( registrosCompara("secciones_ine_giras",$_POST['seccion_ine_gira'][0],1)  || $compara ){
		if(!empty($_POST)){
			$seccion_ine_giraDatos=seccion_ine_giraDatos($_POST['seccion_ine_gira'][0]['id']);


			//$_POST['registro']=$fechaH;
			$_POST["seccion_ine_gira"][0]['fechaR']=$fechaH;
			//$_POST["seccion_ine_gira"][0]['fecha_hora']=$_POST["seccion_ine_gira"][0]['fecha']." ".$_POST["seccion_ine_gira"][0]['hora'];

			$_POST["seccion_ine_gira"][0]['codigo_plataforma']=$codigo_plataforma;
			$_POST["seccion_ine_gira"][0]["referencia_importacion"]=$seccion_ine_giraDatos['referencia_importacion'];


			$success=true;
			foreach($_POST['seccion_ine_gira'] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}

			$update_secciones_ine_giras = "UPDATE secciones_ine_giras SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_secciones_ine_giras=$conexion->query($update_secciones_ine_giras);
			$num=$conexion->affected_rows;
			if(!$update_secciones_ine_giras || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_secciones_ine_giras"; 
				var_dump($conexion->error);
			}

			unset($_POST["seccion_ine_gira"][0]['id']); 
			$id_seccion_ine_gira=$_POST['seccion_ine_gira'][0]['id_seccion_ine_gira']=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["seccion_ine_gira"][0]))."`";
			$values_pdo = "'".implode("','", $_POST['seccion_ine_gira'][0])."'";
			$inset_secciones_ine_giras_historicos= "INSERT INTO secciones_ine_giras_historicos ($fields_pdo) VALUES ($values_pdo);";
			$inset_secciones_ine_giras_historicos=$conexion->query($inset_secciones_ine_giras_historicos);
			$num=$conexion->affected_rows;
			if(!$inset_secciones_ine_giras_historicos || $num=0){
				$success=false;
				echo "ERROR inset_secciones_ine_giras_historicos"; 
				var_dump($conexion->error);
			}

			//! add
			foreach ($lineas_array['add'] as $key => $value) {
				if($value['status']==1){
					$value['id_seccion_ine_gira'] = $id_seccion_ine_gira;
					$value['id_seccion_ine'] = $_POST["seccion_ine_gira"][0]['id_seccion_ine'];
					$value['id_municipio'] = $_POST["seccion_ine_gira"][0]['id_municipio'];
					$value['id_localidad'] = $_POST["seccion_ine_gira"][0]['id_localidad'];
					$value['fechaR']  = $fechaH;
					$value['codigo_plataforma']=$codigo_plataforma; 
					unset($value['status']);
					$fields_pdo = "`".implode('`,`', array_keys($value))."`";
					$values_pdo = "'".implode("','", $value)."'";
					$inset_secciones_ine_giras_puntos= "INSERT INTO secciones_ine_giras_puntos ($fields_pdo) VALUES ($values_pdo);";
					$inset_secciones_ine_giras_puntos=$conexion->query($inset_secciones_ine_giras_puntos);
					$num=$conexion->affected_rows;
					if(!$inset_secciones_ine_giras_puntos || $num=0){
						$success=false;
						echo "ERROR inset_secciones_ine_giras_puntos"; 
						var_dump($conexion->error);
					}
					$value['id_seccion_ine_gira_punto']=$conexion->insert_id;
					$fields_pdo = "`".implode('`,`', array_keys($value))."`";
					$values_pdo = "'".implode("','", $value)."'";
					$inset_secciones_ine_giras_puntos_historicos= "INSERT INTO secciones_ine_giras_puntos_historicos ($fields_pdo) VALUES ($values_pdo);";
					$inset_secciones_ine_giras_puntos_historicos=$conexion->query($inset_secciones_ine_giras_puntos_historicos);
					$num=$conexion->affected_rows;
					if(!$inset_secciones_ine_giras_puntos_historicos || $num=0){
						$success=false;
						echo "ERROR inset_secciones_ine_giras_puntos_historicos"; 
						var_dump($conexion->error);
					}
				}
			}

			//! delete
			foreach ($lineas_array['delete'] as $key => $value) {
				$id_seccion_ine_gira_punto = $value['id'];
				$delete_secciones_ine_giras_punto = "DELETE FROM secciones_ine_giras_puntos  WHERE  id='$id_seccion_ine_gira_punto' ";
				$delete_secciones_ine_giras_punto=$conexion->query($delete_secciones_ine_giras_punto);
				$num=$conexion->affected_rows;
				if(!$delete_secciones_ine_giras_punto || $num=0){
					$success=false;
					echo "ERROR delete delete_secciones_ine_giras_punto"; 
					echo "<br>";
					echo("Errorcode: " . mysqli_errno($conexion));
					echo "<br>";
				}
			}

			//! edit
			foreach ($lineas_array['edit'] as $key => $value) {
				unset($valueSets);
				unset($value['status']);
				$value['fechaR']  = $fechaH;
				$value['codigo_plataforma']=$codigo_plataforma; 

				foreach($value as $keyPrincipal => $atributos) {
					if($keyPrincipal !='id'){
						$valueSets[] = $keyPrincipal . " = '" . $atributos . "'";
					}else{
						$id_seccion_ine_gira_punto=$atributos;
					}
				}
				$update_secciones_ine_giras_punto = "UPDATE secciones_ine_giras_puntos SET ". join(",",$valueSets) . " WHERE id=".$id_seccion_ine_gira_punto;
				$update_secciones_ine_giras_punto=$conexion->query($update_secciones_ine_giras_punto);
				$num=$conexion->affected_rows;
				if(!$update_secciones_ine_giras_punto || $num=0){
					$success=false;
					echo "<br>";
					echo "ERROR update_secciones_ine_giras_punto"; 
					var_dump($conexion->error);
				}

				unset($value['id']);
				$value['id_seccion_ine_gira_punto'] = $id_seccion_ine_gira_punto;
				$fields_pdo = "`".implode('`,`', array_keys($value))."`";
				$values_pdo = "'".implode("','", $value)."'";
				$inset_secciones_ine_giras_historicos= "INSERT INTO secciones_ine_giras_puntos_historicos ($fields_pdo) VALUES ($values_pdo);";
				$inset_secciones_ine_giras_historicos=$conexion->query($inset_secciones_ine_giras_historicos);
				$num=$conexion->affected_rows;
				if(!$inset_secciones_ine_giras_historicos || $num=0){
					$success=false;
					echo "ERROR inset_secciones_ine_giras_historicos"; 
					var_dump($conexion->error);
				}
			}



			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_giras',$id_seccion_ine_gira,'Update','',$fechaH);
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
		}
	}
