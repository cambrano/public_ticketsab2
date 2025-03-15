<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/militantes_partidos.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/files_size.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','militantes_partidos',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}


	$imagen = $_POST['imagen'];
	unset($_POST['imagen']);
	unset($_POST['permiso']);
	$dataPost["militante_partido"][0] = $_POST;


	//var_dump($dataPost["militante_partido"][0]);
	if(!empty($dataPost)){
		//metemos los valores para que se no tengamos error
		foreach($dataPost["militante_partido"][0] as $keyPrincipal => $atributo) {
			$dataPost["militante_partido"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$militante_partidoClaveVerificacion=militante_partidoClaveVerificacion($dataPost["militante_partido"][0]['clave'],'',1);
		if($militante_partidoClaveVerificacion){
			$claveF= clave('militantes_partidos');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$dataPost["militante_partido"][0]['clave'] = $claveF['clave'];
			}
		}

		$success=true;
		$conexion->autocommit(FALSE);
		////! validamos si tiene imagen
		if($imagen!=""){
			if($_FILES['imagen']['name'] != ""){
				if($_FILES['imagen']['error']==UPLOAD_ERR_OK) {
					if(is_uploaded_file($_FILES['imagen']['tmp_name'])){
						if($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES["imagen"]["type"] == "image/png" ){
							$extension = pathinfo($_FILES['imagen']['name']);
							include "../functions/genid.php";
							$dataPost["militante_partido"][0]['name']='mp_image-'.$mk_id.'_'.$cod32.'.'.$extension['extension'];
							$filepath = '../ftpFiles/files/'.$dataPost["militante_partido"][0]['name'];
							$dataPost["militante_partido"][0]['file']=$filepath;
							$dataPost["militante_partido"][0]['type']=$_FILES['imagen']['type'];
							$dataPost["militante_partido"][0]['file_size']=$_FILES['imagen']['size'];
						}else{
							echo "Error no puede subir archivos con la extension ".pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION).', Solo puedes subir archivos PNG o JPG';
						}
					}else{
						echo "Error Subir Archivo";
						echo "<br>";
					}
				}else{
					echo "Error Subir Archivo";
					echo "<br>";
				}
			}
		}

		$dataPost["militante_partido"][0]['fechaR']=$fechaH; 
		$dataPost["militante_partido"][0]['fecha_hora']=$dataPost["militante_partido"][0]['fecha']." ".$dataPost["militante_partido"][0]['hora'];
		$dataPost["militante_partido"][0]['codigo_plataforma']=$codigo_plataforma;


		if($dataPost["militante_partido"][0]['status']==1){
			//ponemos en 0 los demas
			$id_seccion_ine_ciudadano = $dataPost["militante_partido"][0]['id_seccion_ine_ciudadano'];
			$update_militantes_partidos = "UPDATE militantes_partidos SET `status` = '0' WHERE id<>0 AND id_seccion_ine_ciudadano ='{$id_seccion_ine_ciudadano}' ;";
			$update_militantes_partidos=$conexion->query($update_militantes_partidos);
			$num=$conexion->affected_rows;
			if(!$update_militantes_partidos || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_militantes_partidos"; 
				var_dump($conexion->error);
			}
		}


		$fields_pdo = "`".implode('`,`', array_keys($dataPost['militante_partido'][0]))."`";
		$values_pdo = "'".implode("','", $dataPost['militante_partido'][0])."'";
		$inset_militantes_partidos= "INSERT INTO militantes_partidos ($fields_pdo) VALUES ($values_pdo);";


		$inset_militantes_partidos=$conexion->query($inset_militantes_partidos);
		$num=$conexion->affected_rows;
		if(!$inset_militantes_partidos || $num=0){
			$success=false;
			echo "ERROR inset_militantes_partidos"; 
			var_dump($conexion->error);
		}

		$id=$dataPost['militante_partido'][0]['id_militante_partido']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($dataPost['militante_partido'][0]))."`";
		$values_pdo = "'".implode("','", $dataPost['militante_partido'][0])."'";
		$inset_militantes_partidos_historicos= "INSERT INTO militantes_partidos_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_militantes_partidos_historicos=$conexion->query($inset_militantes_partidos_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_militantes_partidos_historicos || $num=0){
			$success=false;
			echo "ERROR inset_militantes_partidos_historicos"; 
			var_dump($conexion->error);
		}


		if($success && $imagen!="" ){
			$filesizeData=filesizeData();
			$capacidad_sistema_restante=$filesizeData['capacidad_sistema_file_restante'];
			$file_size_up=0;
			$capacidad_sistema_restante=$capacidad_sistema_restante-$_FILES['imagen']['size'];
			$file_size_up=$file_size_up+$_FILES['imagen']['size']-$dataPost['militante_partido'][0]['file_size'];

			if($filesizeData['file_size_restante_tipo_print']=="MB"){
				$dataPost['militante_partido'][0]['file_size']=$dataPost['militante_partido'][0]['file_size']/1000000;
			}

			if($filesizeData['file_size_restante_tipo_print']=="GB"){
				$dataPost['militante_partido'][0]['file_size']=$dataPost['militante_partido'][0]['file_size']/1000000000;
			}

			if($capacidad_sistema_restante<0 && $file_size_up>0){
				$file_size_up_mb=$file_size_up/1000000;
				$file_size_up_mb=number_format($file_size_up_mb,4);
				//$capacidad_sistema_restante=$capacidad_sistema_restante/1000000;
				echo "Ya no cuenta con capacidad de almacenamiento si desea mas favor de comunicarte con productos gracias. Su capacidad de almacenamiento es ".number_format($filesizeData['file_size_capacidad_print'],0)." ".$filesizeData['file_size_capacidad_tipo_print']." , le resta ".number_format($filesizeData['file_size_restante_print']+$productoDatos['file_size'],4)." ".$filesizeData['file_size_restante_tipo_print']." y Usted quiere subir ".$file_size_up_mb." MB.";
				$conexion->rollback();
				$conexion->close();
				die;
			}

			$rutaTemporal=$_FILES['imagen']['tmp_name'];
			if(!move_uploaded_file($rutaTemporal,$filepath)){
				$success=false;
				echo "ERROR, Imagen Intente Subir otra vez";
				echo "<br>";
			}
			if (!file_exists($filepath)) {
				$success=false;
				echo "ERROR, Imagen Intente Subir otra vez";
				echo "<br>";
			}
		}

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'militantes_partidos',$id,'Insert','',$fechaH);
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