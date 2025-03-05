<?php
	if($_FILES['file']["type"]!="text/csv"){
		echo "<br><div class='mensajeError'>Archivo Incorrecto debe ser un .CSV </div>";
		die;
	}
	include "../../functions/security.php";
	include "../../functions/genid.php";
	include "../../functions/timemex.php";
	include "../../functions/usuarios.php";
	include "../../functions/log_usuarios.php";
	include "../../functions/paquetes_sistema.php";



	@session_start();
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST as $keyPrincipal => $atributo) {
			$_POST[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		//var_dump($_POST);
		//var_dump($_SESSION['data_import']);
		$usuario=usuarios(); 
		$tipo=$_POST['tipo_operacion'];
		$tabla=$_POST['tabla_operacion'];
		$numarray=0;

		$success=true;
		$conexion->autocommit(FALSE);
		$data=$_SESSION['data_import'];
		$countData=0;
		foreach ($data as $key => $value) {
			//cambios de columnas
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
				$usuarios['identificador'] = $gen_id3.'_'.$cod32;

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
			if($_FILES['file']['name'] != ""){
				$rutaTemporal=$_FILES['file']['tmp_name'];
				$rutaDestino="../files/insert_".$_COOKIE["id_usuario"]."_".$tabla."_".$cod32.".csv";
				if(!move_uploaded_file($rutaTemporal,$rutaDestino)){
					$success=false;
					echo "ERROR,No se guardo el csv";
				}
			}
			//echo "<pre>";
			//var_dump($insertArray);
			//echo "</pre>";
			if($success){
				//var_dump($insertArray);
				$logArray=logUsuarioArray($insertArray,$fechaH,$_COOKIE["id_usuario"]);
				if($logArray==true){
					echo "SI";
					$conexion->commit();
					$conexion->close();
				}else{
					unlink($rutaDestino);
					echo "NO";
					$conexion->rollback();
					$conexion->close();
				}
			}else{
				echo "NO";
				unlink($rutaDestino);
				$conexion->rollback();
				$conexion->close();
			}
		}else{
			if($successcon!=1){
				echo "No se inserto ningun registro";
			}
		}

		

		

	}
?>