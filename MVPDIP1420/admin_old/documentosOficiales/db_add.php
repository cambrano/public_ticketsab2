<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/files_size.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('perfiles','documentos_oficiales',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}
	@session_start();
	$id_identidad = $_SESSION['id_identidad']; 

	if(!empty($_POST)){
		$success=true;
		//metemos los valores para que se no tengamos error
		foreach($_POST["documento_oficial"][0] as $keyPrincipal => $atributo) {
			$_POST["documento_oficial"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$_POST["documento_oficial"][0]['fechaR']=$fechaH;  
		$_POST["documento_oficial"][0]['codigo_plataforma']=$codigo_plataforma;
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

		$id=$_POST["documento_oficial"][0]['id_documento_oficial']=$conexion->insert_id;
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
			foreach ($_SESSION['image'] as $key => $value) {
				$value['id'];
				if($value['status']==1 && $value['id']==""){
					$capacidad_sistema_restante=$capacidad_sistema_restante-$value['file_size']; 
					$file_size_up=$file_size_up+$value['file_size'];
					include "../functions/genid.php";
					$baseFromJavascript = "data:".$value['type'].";base64,".base64_encode( $value['imagePrint'] );
					$base_to_php = explode(',', $baseFromJavascript);
					$data = base64_decode($base_to_php[1]);
					$filepath = '../ftpFiles/files/documentos_oficiales_'.$id.'_'.$mk_id.$value['file_name'];
					//file_put_contents($filepath, $data);
					//file_put_contents($filepath, $data);
					$file_data[$numberFile]['filepath']=$filepath;
					$file_data[$numberFile]['data']=$data;
					$file_data[$numberFile]['tipo']='add';
					$numberFile=$numberFile+1;

					$image_file['id_documento_oficial']=$id;
					$image_file['id_identidad']=$id_identidad;
					$image_file['name']='documentos_oficiales_'.$id.'_'.$mk_id.$value['file_name'];
					$image_file['file']=$filepath;
					$image_file['type']='image/png';
					$image_file['tipo_imagen']=$value['tipo_imagen'];
					$image_file['codigo_plataforma']=$codigo_plataforma;
					$image_file['file_size']=$value['file_size']; 
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
					//var_dump($image_file);
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

		foreach ($file_data as $key => $value) {
			if($value['tipo']=='add'){
				$data = $value['data'];
				$filepath = $value['filepath'];
				file_put_contents($filepath, $data);
				if (!file_exists($filepath)) {
					$success=false;
					echo "Error,No se creo el archivo";
				}
				//verificar si existe
			}
			if($value['tipo']=='delete'){
				$filepath = $value['filepath'];
				unlink($filepath);
				if (file_exists($filepath)) {
					$success=false;
					echo "Error,No se borro el archivo";
				}
			}
		}

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'documentos_oficiales',$id,'Insert','',$fechaH);
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