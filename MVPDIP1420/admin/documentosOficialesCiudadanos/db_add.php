<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/files_size.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/efs.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','documentos_oficiales',$_COOKIE["id_usuario"]);

	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}
	@session_start();
	if(!empty($_POST) && !empty($_FILES) ){
		//include __DIR__."/../functions/tool_xhpzab.php";
		//$id_seccion_ine_ciudadano = decrypt_ab_checkFinal($_COOKIE['paguinaId_2']);
		$success=true;
		//metemos los valores para que se no tengamos error
		$_POST["documento_oficial"][0]['fechaR']=$fechaH;  
		$_POST["documento_oficial"][0]['status']=1;  
		$_POST["documento_oficial"][0]['codigo_plataforma']=$codigo_plataforma;
		/*$_POST["documento_oficial"][0]['codigo_plataforma']=$id_seccion_ine_ciudadano;*/
		$_POST["documento_oficial"][0]['fecha_emision']=$_POST['fecha_emision'];
		$_POST["documento_oficial"][0]['fecha_vigencia']=$_POST['fecha_vigencia'];
		$_POST["documento_oficial"][0]['tipo']=$_POST['tipo'];
		$id_seccion_ine_ciudadano = $_POST["documento_oficial"][0]['id_seccion_ine_ciudadano']=$_POST['id_seccion_ine_ciudadano'];
		foreach($_POST["documento_oficial"][0] as $keyPrincipal => $atributo) {
			$_POST["documento_oficial"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		//$_POST["documento_oficial"][0]['detalle']=mysqli_real_escape_string($conexion,$_POST["documento_oficial"][0]['detalle']);

		$fields_pdo = "`".implode('`,`', array_keys($_POST["documento_oficial"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["documento_oficial"][0])."'";
		$inset_documentos_oficiales= "INSERT INTO documentos_oficiales ($fields_pdo) VALUES ($values_pdo);";
		$conexion->autocommit(FALSE);

		$inset_documentos_oficiales=$conexion->query($inset_documentos_oficiales);
		$num=$conexion->affected_rows;
		if(!$inset_documentos_oficiales || $num=0){
			$success=false;
			echo "ERROR inset_documentos_oficiales"; 
			var_dump($conexion->error);
		}

		$id_documento_oficial=$_POST["documento_oficial"][0]['id_documento_oficial']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST["documento_oficial"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["documento_oficial"][0])."'";
		$inset_documentos_oficiales_historicos= "INSERT INTO documentos_oficiales_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_documentos_oficiales_historicos=$conexion->query($inset_documentos_oficiales_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_documentos_oficiales_historicos || $num=0){
			$success=false;
			echo "ERROR inset_documentos_oficiales_historicos"; 
			var_dump($conexion->error);
		}
		if($success){
			$filesizeData=filesizeData();
			$capacidad_sistema_restante=$filesizeData['capacidad_sistema_file_restante'];
			//var_dump($filesizeData['capacidad_sistema_restante']);
			//die;
			$file_size_up=0;
			$numberFile=0;
			$rutaEfs = rutaEfs();
			foreach ($_FILES as $key => $value) {
				if($key == 'imagen_otros'){
					$img_tipo = 'otros';
				}
				if($key == 'imagen_frente'){
					$img_tipo = 'frente';
				}
				if($key == 'imagen_atras'){
					$img_tipo = 'atras';
				}
				$capacidad_sistema_restante=$capacidad_sistema_restante-$value['size']; 
				$file_size_up=$file_size_up+$value['size'];
				include "../functions/genid.php";
				$extension = pathinfo($value['name']);
				$image_file['id_documento_oficial']=$id_documento_oficial;
				$image_file['id_seccion_ine_ciudadano']=$id_seccion_ine_ciudadano;
				$image_file['name']= 'documentos_oficiales_'.$id_documento_oficial.'_'.$mk_id."_".$key.".".$extension['extension'];
				//$filepath = '../ftpFiles/files/'.$image_file['name'];
				$filepath = $rutaEfs.$image_file['name'];
				$image_file['file']=$filepath;
				$image_file['type']=$value['type'];
				$image_file['tipo_imagen']=$img_tipo;
				$image_file['codigo_plataforma']=$codigo_plataforma;
				$image_file['file_size']=$value['size']; 
				$image_file['fechaR']=$fechaH;

				$array_files[] = array(
					'tmp' => $value['tmp_name'],
					'filepath' => $filepath,
				);

				foreach($image_file as $keyPrincipal => $atributo) {
					$image_file[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
				}

				$fields_pdo = "`".implode('`,`', array_keys($image_file))."`";
				$values_pdo = "'".implode("','", $image_file)."'";
				$insert_image= "INSERT INTO documentos_oficiales_images ($fields_pdo) VALUES ($values_pdo);";
				$conexion->autocommit(FALSE);
				$insert_image=$conexion->query($insert_image);
				$num=$conexion->affected_rows;
				if(!$insert_image || $num=0){
					$success=false;
					echo "ERROR insert_image"; 
					var_dump($conexion->error);
				}
			}
		}
		if($capacidad_sistema_restante<0 && $file_size_up>0){
			$file_size_up_mb=$file_size_up/1000000;
			$file_size_up_mb=number_format($file_size_up_mb,4);
			$capacidad_sistema_restante=$capacidad_sistema_restante/1000000;
			echo "Ya no cuenta con capacidad de almacenamiento si desea mas favor de comunicarte con nosotros gracias. Su capacidad de almacenamiento es ".number_format($filesizeData['file_size_capacidad_print'],0)." ".$filesizeData['file_size_capacidad_tipo_print']." , le resta ".number_format($filesizeData['file_size_restante_print'],4)." ".$filesizeData['file_size_restante_tipo_print']." y Usted quiere subir ".$file_size_up_mb." MB.";
			$conexion->rollback();
			$conexion->close();
			die;
		}


		foreach ($array_files as $key => $value) {
			if(!move_uploaded_file($value['tmp'],$value['filepath'])){
				$success=false;
				echo "ERROR, Imagen Intente Subir otra vez";
			}
		}

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'documentos_oficiales_ciudadanos',$id_documento_oficial,'Insert','',$fechaH);
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