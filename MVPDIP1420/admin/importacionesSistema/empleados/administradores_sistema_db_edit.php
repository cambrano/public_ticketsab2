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
	include "../../functions/claves.php"; 
	include "../../functions/camparaRegistros.php";
	include "../../functions/importacion.php";



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
		$countData=0;
		$success=true;
		$conexion->autocommit(FALSE);
		$data=$_SESSION['data_import'];
		foreach ($data as $key => $value) {
			$status_comparacion="";
			unset($value[$primaria]);
			//cambios de columnas
			foreach($value as $clave=>$valor){
				if(empty($valor)) {
					unset($value[$clave]);
				}
			}
			if($tabla=="administradores_sistema" || $tabla=="empleados"){
				$tabla_tipo=$tabla;
				$tabla="empleados";
				$value['clave'];
				$empleados['id']=claveId($value['clave'],'empleados');
				$empleados['nombre']=$value['nombre'];
				$empleados['apellido_paterno']=$value['apellido_paterno'];
				$empleados['apellido_materno']=$value['apellido_materno'];
				$empleados['correo_electronico']=$value['correo_electronico'];
				$empleados['clave']=$value['clave'];
				$empleados['status']=$value['status'];
				foreach ($empleados as $key => $valuex) {
					if($valuex==""){
						unset($empleados[$key]);
					}
				}
				unset($usuarios);
				$usuarios['clave']=$value['clave'];
				$usuarios['id']=claveId($usuarios['clave'],'usuarios');
				$usuarios['usuario']=$value['usuario'];
				$usuarios['password']=$value['password'];
				$usuarios['status']=$value['status'];
				$usuarios['tabla'] = 'empleados';
				foreach ($usuarios as $key => $valuex) {
					if($valuex==""){
						unset($usuarios[$key]);
					}
				}
				//$importDataUsuarios=importData($usuarios['id'],'usuarios');
				//$registrosUsuarios=array_replace($importDataUsuarios, $usuarios);
				//$importData=importData($empleados['id'],'empleados');
				//$registros=array_replace($importData, $empleados);
				//registrosCompara('empleados',$empleados,1);
				//registrosCompara('usuarios',$usuarios,1);
				if($status_comparacion=registrosCompara('empleados',$empleados,1)==1 || $status_comparacion=registrosCompara('usuarios',$usuarios,1)==1 ){
					$status_comparacion=1;
				}

			}

			$value['id']=claveId($value['clave'],$tabla);
			if($value['id']!=""){  
				if($status_comparacion){
					if($tabla=="empleados"){
						unset($valueSets);
						unset($registrosEmpleados);
						unset($registrosUsuarios);
						unset($importDataEmpleados);
						unset($importDataUsuarios);
						$empleados['fechaR']=$fechaH;
						$empleados['referencia_importacion']=$cod32;
						if($empleados['status']=='x'){
							$empleados['status']=0;
						}
						$usuarios['fechaR']=$fechaH;
						$usuarios['referencia_importacion']=$cod32;
						if($usuarios['status']=='x'){
							$usuarios['status']=0;
						}

						$importDataEmpleados=importData($empleados['id'],'empleados');
						$registrosEmpleados=array_replace($importDataEmpleados, $empleados);

						$importDataUsuarios=importData($usuarios['id'],'usuarios');
						$registrosUsuarios=array_replace($importDataUsuarios, $usuarios);
						unset($valueSets);
						$tabla="empleados";
						foreach($registrosEmpleados as $keyPrincipal => $atributo) {
							$registrosEmpleados[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
						}
						foreach ($registrosEmpleados as $key => $value) {
							if($key !='id'){
								if($key !='id_empleado_grupo'){
									$valueSets[] = $key . " = '" . $value . "'";
								}
							}else{
								$id=$value;
							}
						}

						$update = "UPDATE {$tabla} SET ". join(",",$valueSets) . " WHERE id=".$id;
						$update=$conexion->query($update);
						$num=$conexion->affected_rows;
						if(!$update || $num=0){
							$success=false;
							echo "<br>";
							echo "error update_{$tabla}"; 
							var_dump($conexion->error);
						}
						$id_empleado=$registrosEmpleados['id_empleado']=$id;
						unset($registrosEmpleados['id']);
						unset($registrosEmpleados['id_empleado_grupo']);
						$fields_pdo = "`".implode('`,`', array_keys($registrosEmpleados))."`";
						$values_pdo = "'".implode("','", $registrosEmpleados)."'";
						$insert= "INSERT INTO {$tabla}_historicos ($fields_pdo) VALUES ($values_pdo);";
						$insert=$conexion->query($insert);
						$num=$conexion->affected_rows;
						if(!$insert || $num=0){
							$success=false;
							echo "<br>";
							echo "error update_{$tabla}_historicos"; 
							var_dump($conexion->error);
						}
						unset($valueSets);
						$tabla="usuarios";
						foreach($registrosUsuarios as $keyPrincipal => $atributo) {
							$registrosUsuarios[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
						}
						foreach ($registrosUsuarios as $key => $value) {
							if($key !='id'){
								if($key !='id_seccion_ine_ciudadano'){
									$valueSets[] = $key . " = '" . $value . "'";
								}
							}else{
								$id=$value;
							}
						}
						$update = "UPDATE {$tabla} SET ". join(",",$valueSets) . " WHERE id=".$id;
						$update=$conexion->query($update);
						$num=$conexion->affected_rows;
						if(!$update || $num=0){
							$success=false;
							echo "<br>";
							echo "error update_{$tabla}"; 
							var_dump($conexion->error);
						}
						$registrosUsuarios['id_usuario']=$id;
						unset($registrosUsuarios['id']);
						unset($registrosUsuarios['id_seccion_ine_ciudadano']);
						$fields_pdo = "`".implode('`,`', array_keys($registrosUsuarios))."`";
						$values_pdo = "'".implode("','", $registrosUsuarios)."'";
						$insert= "INSERT INTO {$tabla}_historicos ($fields_pdo) VALUES ($values_pdo);";
						$insert=$conexion->query($insert);
						$num=$conexion->affected_rows;
						if(!$insert || $num=0){
							$success=false;
							echo "<br>";
							echo "error update_{$tabla}_historicos"; 
							var_dump($conexion->error);
						}
						if($tabla_tipo=="empleados"){
							$tabla_tipo="empleados";
						}else{
							$tabla_tipo="administradores_sistema";
						}
						$insertArray[$numarray]['id']=$id_empleado;
						$insertArray[$numarray]['tabla']='administradores_sistema';
						$insertArray[$numarray]['tipo']="Update";
						$numarray=$numarray+1;
					}
				}
			}
			
		}
		if($numarray>0){
			$tabla="administradores_sistema";
			$log_importacion['id_usuario']=$usuario['id'];
			$log_importacion['tabla']=$tabla;
			$log_importacion['tipo']='Update';
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
				$insertArray[$numarray]['tipo']="Update";
				$numarray=$numarray+1;
				if($_FILES['file']['name'] != ""){
				$rutaTemporal=$_FILES['file']['tmp_name'];
				$rutaDestino="../files/update_".$_COOKIE["id_usuario"]."_".$tabla."_".$cod32.".csv";
				if(!move_uploaded_file($rutaTemporal,$rutaDestino)){
					$success=false;
					echo "ERROR,No se guardo el csv";
				}
			}
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
			echo "No se modifico ningun registro";
		}
		
	}
?>