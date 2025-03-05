<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/documentos_oficiales.php";
	include __DIR__."/../functions/documentos_oficiales_images.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/files_size.php";
	@session_start();
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad',"documentos_oficiales",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	 

	$frente_id = $_POST['frente_id'];
	$atras_id = $_POST['atras_id'];
	$otros_id = $_POST['otros_id'];
	$frente_reset = $_POST['frente_reset'];
	$atras_reset = $_POST['atras_reset'];
	$otros_reset = $_POST['otros_reset'];

	$frente_borrar = $_POST['frente_borrar'];
	$atras_borrar = $_POST['atras_borrar'];
	$otros_borrar = $_POST['otros_borrar'];
	
	if($frente_id!=""){
		if($frente_borrar==1){
			$frente_no_image = 'NOTIENE';
		}else{
			if($frente_reset==1){
				$frente_no_image = 'SITIENE';
			}else{
				if(!empty($_FILES['imagen_frente'])){
					$frente_no_image = 'SITIENE';
				}else{
					$frente_no_image = 'NOTIENE';
				}
			}
		}
	}else{
		if($frente_borrar==1){
			$frente_no_image = 'NOTIENE';
		}else{
			if(!empty($_FILES['imagen_frente'])){
				$frente_no_image = 'SITIENE';
			}else{
				$frente_no_image = 'NOTIENE';
			}
		}
	}
	if($atras_id!=""){
		if($atras_borrar==1){
			$atras_no_image = 'NOTIENE';
		}else{
			if($atras_reset==1){
				$atras_no_image = 'SITIENE';
			}else{
				if(!empty($_FILES['imagen_atras'])){
					$atras_no_image = 'SITIENE';
				}else{
					$atras_no_image = 'NOTIENE';
				}
			}
		}
	}else{
		if($atras_borrar==1){
			$atras_no_image = 'NOTIENE';
		}else{
			if(!empty($_FILES['imagen_atras'])){
				$atras_no_image = 'SITIENE';
			}else{
				$atras_no_image = 'NOTIENE';
			}
		}
	}
	if($otros_id!=""){
		if($otros_borrar==1){
			$otros_no_image = 'NOTIENE';
		}else{
			if($otros_reset==1){
				$otros_no_image = 'SITIENE';
			}else{
				if(!empty($_FILES['imagen_otros'])){
					$otros_no_image = 'SITIENE';
				}else{
					$otros_no_image = 'NOTIENE';
				}
			}
		}
	}else{
		if($otros_borrar==1){
			$otros_no_image = 'NOTIENE';
		}else{
			if(!empty($_FILES['imagen_otros'])){
				$otros_no_image = 'SITIENE';
			}else{
				$otros_no_image = 'NOTIENE';
			}
		}
	}
	
	if($frente_no_image == 'NOTIENE' && $atras_no_image == 'NOTIENE' && $otros_no_image == 'NOTIENE'){
		echo "Debe adjuntar un archivo.";
		$entra = 0;
		die;
	}else{
		$entra = 1;
	}

	

	$_POST["documento_oficial"][0]['id']=$_POST['id'];
	$_POST["documento_oficial"][0]['fechaR']=$fechaH;  
	$_POST["documento_oficial"][0]['status']=1;  
	$_POST["documento_oficial"][0]['codigo_plataforma']=$codigo_plataforma;
	/*$_POST["documento_oficial"][0]['codigo_plataforma']=$id_identidad;*/
	$_POST["documento_oficial"][0]['fecha_emision']=$_POST['fecha_emision'];
	$_POST["documento_oficial"][0]['fecha_vigencia']=$_POST['fecha_vigencia'];
	$_POST["documento_oficial"][0]['tipo']=$_POST['tipo'];
	//metemos los valores para que se no tengamos error
	foreach($_POST["documento_oficial"][0] as $keyPrincipal => $atributo) {
		$_POST["documento_oficial"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	$id_documento_oficial=$_POST["documento_oficial"][0]['id'];
	$success=false;
	if( registrosCompara("documentos_oficiales",$_POST["documento_oficial"][0],1) || $entra == 1 ){
		if(!empty($_POST)){
			//$_POST['registro']=$fechaH;
			$_POST["documento_oficial"][0]['codigo_plataforma']=$codigo_plataforma;
			//$documento_oficialDatos=documento_oficialDatos($_POST["documento_oficial"][0]['id']);


			$_POST["documento_oficial"][0]['status']=1;
			$success=true;
			foreach($_POST["documento_oficial"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}

			/*
			$_POST["documento_oficial"][0]['id_page'];
			if($_POST["documento_oficial"][0]['id_page']==""){
				$_POST["documento_oficial"][0]['id_page']=null;
				$conexion->query("SET FOREIGN_KEY_CHECKS=0;");
			}
			*/
			$update_documentos_oficiales = "UPDATE documentos_oficiales SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_documentos_oficiales=$conexion->query($update_documentos_oficiales);
			$num=$conexion->affected_rows;
			if(!$update_documentos_oficiales || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_documentos_oficiales"; 
				var_dump($conexion->error);
			}

			/*
			if($_POST["documento_oficial"][0]['id_page']==""){
				$_POST["documento_oficial"][0]['id_page']=null;
				$conexion->query("SET FOREIGN_KEY_CHECKS=1;");
			}
			*/

			$id_documento_oficial=$id=$_POST["documento_oficial"][0]['id_documento_oficial']=$id;
			$_POST["documento_oficial"][0]['fechaR']=$fechaH; 
			unset($_POST["documento_oficial"][0]['id']);
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
		}
		//! FILES
		$filesizeData=filesizeData();
		$capacidad_sistema_restante=$filesizeData['capacidad_sistema_file_restante'];
		if($frente_id!=""){
			//* SI tiene id
			$documento_oficial_imagesDatos=documento_oficial_imagesDatos($frente_id,'');
			if($frente_borrar == 1){
				//! elimina la imagen
				$img_array['delete'][] = '../ftpFiles/files/'.$documento_oficial_imagesDatos[0]['name'];
				$insert_image= "DELETE FROM documentos_oficiales_images WHERE id = '{$frente_id}' ";
				$insert_image=$conexion->query($insert_image);
			}else{
				//! Modificamos la imagen
				if(!empty($_FILES['imagen_frente'])){
					$img_array['delete'][] = '../ftpFiles/files/'.$documento_oficial_imagesDatos[0]['name'];
					$name = explode('.',$documento_oficial_imagesDatos[0]['name']);
					$extension = pathinfo($_FILES['imagen_frente']['name']);
					$name_nuevo = $name[0].'.'.$extension['extension'];


					$image_file['id_documento_oficial']=$id_documento_oficial;
					$image_file['id_identidad']=$_POST['id_identidad'];
					$image_file['name']= $name_nuevo;
					$filepath = '../ftpFiles/files/'.$image_file['name'];
					$image_file['file']=$filepath;
					$image_file['type']=$_FILES['imagen_frente']['type'];
					$image_file['tipo_imagen']='frente';
					$image_file['codigo_plataforma']=$codigo_plataforma;
					$image_file['file_size']=$_FILES['imagen_frente']['size']; 
					$image_file['fechaR']=$fechaH;

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

					//$img_array['add'][] = $name_nuevo;
					$img_array['edit'][] = array(
						'name' => $filepath,
						'tmp' => $_FILES['imagen_frente']['tmp_name'],
					);
					//! modificamos la imagen si existe
				}
			}
		}else{
			//* NO tiene id
			if($frente_borrar == 1){
				//! Elimina no hace nada
			}else{
				if(!empty($_FILES['imagen_frente'])){
					//! guarda la imagen si existe
					include "../functions/genid.php";
					$extension = pathinfo($_FILES['imagen_frente']['name']);
					$key = 'imagen_frente';
					//$img_array['add'][] = 'documentos_oficiales_'.$id_documento_oficial.'_'.$mk_id."_".$key.".".$extension['extension'];

					$image_file['id_documento_oficial']=$id_documento_oficial;
					$image_file['id_identidad']=$_POST['id_identidad'];
					$image_file['name']= 'documentos_oficiales_'.$id_documento_oficial.'_'.$mk_id."_".$key.".".$extension['extension'];
					$filepath = '../ftpFiles/files/'.$image_file['name'];
					$image_file['file']=$filepath;
					$image_file['type']=$_FILES['imagen_frente']['type'];
					$image_file['tipo_imagen']='frente';
					$image_file['codigo_plataforma']=$codigo_plataforma;
					$image_file['file_size']=$_FILES['imagen_frente']['size']; 
					$image_file['fechaR']=$fechaH;

					$img_array['add'][] = array(
						'name' => $filepath,
						'tmp' => $_FILES['imagen_frente']['tmp_name'],
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
		}
		if($atras_id!=""){
			//* SI tiene id
			$documento_oficial_imagesDatos=documento_oficial_imagesDatos($atras_id,'');
			if($atras_borrar == 1){
				//! elimina la imagen
				$img_array['delete'][] = '../ftpFiles/files/'.$documento_oficial_imagesDatos[0]['name'];
				$insert_image= "DELETE FROM documentos_oficiales_images WHERE id = '{$atras_id}' ";
				$insert_image=$conexion->query($insert_image);
				//$sql = "DELETE FROM documentos_oficiales_images WHERE id = '{$atras_id}' ";
			}else{
				//! Modificamos la imagen
				if(!empty($_FILES['imagen_atras'])){
					$img_array['delete'][] = '../ftpFiles/files/'.$documento_oficial_imagesDatos[0]['name'];
					$name = explode('.',$documento_oficial_imagesDatos[0]['name']);
					$extension = pathinfo($_FILES['imagen_atras']['name']);
					$name_nuevo = $name[0].'.'.$extension['extension'];


					$image_file['id_documento_oficial']=$id_documento_oficial;
					$image_file['id_identidad']=$_POST['id_identidad'];
					$image_file['name']= $name_nuevo;
					$filepath = '../ftpFiles/files/'.$image_file['name'];
					$image_file['file']=$filepath;
					$image_file['type']=$_FILES['imagen_atras']['type'];
					$image_file['tipo_imagen']='atras';
					$image_file['codigo_plataforma']=$codigo_plataforma;
					$image_file['file_size']=$_FILES['imagen_atras']['size']; 
					$image_file['fechaR']=$fechaH;

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

					//$img_array['add'][] = $name_nuevo;
					$img_array['edit'][] = array(
						'name' => $filepath,
						'tmp' => $_FILES['imagen_atras']['tmp_name'],
					);
					//! modificamos la imagen si existe
				}
			}
		}else{
			//* NO tiene id
			if($atras_borrar == 1){
				//! Elimina no hace nada
			}else{
				if(!empty($_FILES['imagen_atras'])){
					//! guarda la imagen si existe
					include "../functions/genid.php";
					$extension = pathinfo($_FILES['imagen_atras']['name']);
					$key = 'imagen_atras';
					//$img_array['add'][] = 'documentos_oficiales_'.$id_documento_oficial.'_'.$mk_id."_".$key.".".$extension['extension'];

					$image_file['id_documento_oficial']=$id_documento_oficial;
					$image_file['id_identidad']=$_POST['id_identidad'];
					$image_file['name']= 'documentos_oficiales_'.$id_documento_oficial.'_'.$mk_id."_".$key.".".$extension['extension'];
					$filepath = '../ftpFiles/files/'.$image_file['name'];
					$image_file['file']=$filepath;
					$image_file['type']=$_FILES['imagen_atras']['type'];
					$image_file['tipo_imagen']='atras';
					$image_file['codigo_plataforma']=$codigo_plataforma;
					$image_file['file_size']=$_FILES['imagen_atras']['size']; 
					$image_file['fechaR']=$fechaH;

					$img_array['add'][] = array(
						'name' => $filepath,
						'tmp' => $_FILES['imagen_atras']['tmp_name'],
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
		}
		if($otros_id!=""){
			//* SI tiene id
			$documento_oficial_imagesDatos=documento_oficial_imagesDatos($otros_id,'');
			if($otros_borrar == 1){
				//! elimina la imagen
				$img_array['delete'][] = '../ftpFiles/files/'.$documento_oficial_imagesDatos[0]['name'];
				//$sql = "DELETE FROM documentos_oficiales_images WHERE id = '{$otros_id}' ";
				$insert_image= "DELETE FROM documentos_oficiales_images WHERE id = '{$otros_id}' ";
				$insert_image=$conexion->query($insert_image);
			}else{
				//! Modificamos la imagen
				if(!empty($_FILES['imagen_otros'])){
					$img_array['delete'][] = '../ftpFiles/files/'.$documento_oficial_imagesDatos[0]['name'];
					$name = explode('.',$documento_oficial_imagesDatos[0]['name']);
					$extension = pathinfo($_FILES['imagen_otros']['name']);
					$name_nuevo = $name[0].'.'.$extension['extension'];


					$image_file['id_documento_oficial']=$id_documento_oficial;
					$image_file['id_identidad']=$_POST['id_identidad'];
					$image_file['name']= $name_nuevo;
					$filepath = '../ftpFiles/files/'.$image_file['name'];
					$image_file['file']=$filepath;
					$image_file['type']=$_FILES['imagen_otros']['type'];
					$image_file['tipo_imagen']='otros';
					$image_file['codigo_plataforma']=$codigo_plataforma;
					$image_file['file_size']=$_FILES['imagen_otros']['size']; 
					$image_file['fechaR']=$fechaH;

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

					//$img_array['add'][] = $name_nuevo;
					$img_array['edit'][] = array(
						'name' => $filepath,
						'tmp' => $_FILES['imagen_otros']['tmp_name'],
					);
					//! modificamos la imagen si existe
				}
			}
		}else{
			//* NO tiene id
			if($otros_borrar == 1){
				//! Elimina no hace nada
			}else{
				if(!empty($_FILES['imagen_otros'])){
					//! guarda la imagen si existe
					include "../functions/genid.php";
					$extension = pathinfo($_FILES['imagen_otros']['name']);
					$key = 'imagen_otros';
					//$img_array['add'][] = 'documentos_oficiales_'.$id_documento_oficial.'_'.$mk_id."_".$key.".".$extension['extension'];

					$image_file['id_documento_oficial']=$id_documento_oficial;
					$image_file['id_identidad']=$_POST['id_identidad'];
					$image_file['name']= 'documentos_oficiales_'.$id_documento_oficial.'_'.$mk_id."_".$key.".".$extension['extension'];
					$filepath = '../ftpFiles/files/'.$image_file['name'];
					$image_file['file']=$filepath;
					$image_file['type']=$_FILES['imagen_otros']['type'];
					$image_file['tipo_imagen']='otros';
					$image_file['codigo_plataforma']=$codigo_plataforma;
					$image_file['file_size']=$_FILES['imagen_otros']['size']; 
					$image_file['fechaR']=$fechaH;

					$img_array['add'][] = array(
						'name' => $filepath,
						'tmp' => $_FILES['imagen_otros']['tmp_name'],
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

	foreach ($img_array['add'] as $key => $value) {
		if(!move_uploaded_file($value['tmp'],$value['name'])){
			$success=false;
			echo "ERROR, Imagen Intente Subir otra vez";
		}
	}
	foreach ($img_array['edit'] as $key => $value) {
		if(!move_uploaded_file($value['tmp'],$value['name'])){
			$success=false;
			echo "ERROR, Imagen Intente Subir otra vez";
		}
	}


	foreach ($img_array['delete'] as $key => $value) {
		$filepath = $value;
		unlink($filepath);
		if (file_exists($filepath)) {
			$success=false;
			echo "Error,No se borro el archivo";
		}
	}
	

	if($success || $success_images){
		if($success_images && $success==false ){
			$log= logUsuario($_COOKIE["id_usuario"],'documentos_oficiales_ciudadanos',$id_documento_oficial,'Update','',$fechaH);
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
			
			$log= logUsuario($_COOKIE["id_usuario"],'documentos_oficiales_ciudadanos',$id_documento_oficial,'Update','',$fechaH);
			if($log==true){
				echo "SI";
				$conexion->commit();
				$conexion->close();
			}else{
				echo "NO";
				$conexion->rollback();
				$conexion->close();
			}
		}
	}else{
		echo "NO";
		$conexion->rollback();
		$conexion->close();
	}