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

	//include "../../functions/usuarios.php"; 
	include "../../functions/paises.php";
	include "../../functions/estados.php";
	include "../../functions/municipios.php";
	include "../../functions/localidades.php";


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
			if($value['pais']!=""){
				$value['id_pais']=paisId($value['pais']);
				unset($value['pais']);
			}
			if($value['estado']!=""){
				if($value['estado']=="México" || $value['estado']=="méxico" ){
					$value['id_estado']=estadoId($value['estado'],1);
					unset($value['estado']);
				}else{
					$value['id_estado']=estadoId($value['estado'],"");
					unset($value['estado']);
				}
			}
			if($value['municipio']!=""){
				$value['id_municipio']=municipioId($value['municipio'],$value['id_estado']);
				unset($value['municipio']);
			}
			if($value['localidad']!=""){
				$value['id_localidad']=localidadId($value['localidad'],$value['id_estado'],$value['id_municipio']);
				unset($value['localidad']);
			}
			//$tabla="empleados";
			unset($identidades);
			$value['clave'];
			$identidades=$value;
			$identidades['id']=claveId($value['clave'],'identidades');
			unset($identidades['usuario']);
			unset($identidades['password']);
			foreach ($identidades as $key => $valuex) {
				if($valuex==""){
					unset($identidades[$key]);
				}
			}
			 
			//$importDataUsuarios=importData($usuarios['id'],'usuarios');
			//$registrosUsuarios=array_replace($importDataUsuarios, $usuarios);
			//$importData=importData($empleados['id'],'empleados');
			//$registros=array_replace($importData, $empleados);
			//registrosCompara('empleados',$empleados,1);
			//registrosCompara('usuarios',$usuarios,1);
			if($status_comparacion=registrosCompara('identidades',$identidades,1)==1 ){
				$status_comparacion=1;
			}


			$value['id']=claveId($value['clave'],$tabla);
			if($value['id']!=""){  
				if($status_comparacion){
					if($tabla=="identidades"){
						unset($valueSets);
						unset($registrosidentidades);
						unset($registrosUsuarios);
						unset($importDataidentidades);
						unset($importDataUsuarios);
						$identidades['fechaR']=$fechaH;
						$identidades['referencia_importacion']=$cod32;
						if($identidades['status']=='x'){
							$identidades['status']=0;
						}
						 

						$importDataidentidades=importData($identidades['id'],'identidades');
						$registrosidentidades=array_replace($importDataidentidades, $identidades);


						$tabla="identidades";
						foreach($registrosidentidades as $keyPrincipal => $atributo) {
							$registrosidentidades[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
						}
						foreach ($registrosidentidades as $key => $value) {
							if($key !='id'){
								$valueSets[] = $key . " = '" . $value . "'";
							}else{
								$id=$value;
							}
						}
						$update = "UPDATE {$tabla} SET ". join(",",$valueSets) . " WHERE id=".$id;
						//echo "<br>";echo "<br>";
						$update=$conexion->query($update);
						$num=$conexion->affected_rows;
						if(!$update || $num=0){
							$success=false;
							echo "<br>";
							echo "error update_{$tabla}"; 
							var_dump($conexion->error);
						}
						$id_identidad=$registrosidentidades['id_identidad']=$id;
						unset($registrosidentidades['id']);
						$fields_pdo = "`".implode('`,`', array_keys($registrosidentidades))."`";
						$values_pdo = "'".implode("','", $registrosidentidades)."'";
						$insert= "INSERT INTO {$tabla}_historicos ($fields_pdo) VALUES ($values_pdo);";
						$insert=$conexion->query($insert);
						$num=$conexion->affected_rows;
						if(!$insert || $num=0){
							$success=false;
							echo "<br>";
							echo "error update_{$tabla}"; 
							var_dump($conexion->error);
						}

						$insertArray[$numarray]['id']=$id_identidad;
						$insertArray[$numarray]['tabla']=$tabla;
						$insertArray[$numarray]['tipo']="Update";
						$numarray=$numarray+1;
					}
				}
			}
			
		}
		if($numarray>0){
			$tabla="identidades";
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