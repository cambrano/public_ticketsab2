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
	include "../../functions/camparaRegistros.php";
	include "../../functions/importacion.php";
	include "../../functions/configuracion.php";


	@session_start();
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
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
			$configuracionDatos=configuracionDatos();
			$value['id']=$configuracionDatos['id'];

			if( registrosCompara("configuracion",$value,1)){
				if($tabla=="configuracion" && $countData==0){
					$numarray=$numarray+1;
					$importData=importData($value['id'],$tabla);
					$value['fechaR']=$fechaH;
					$value['referencia_importacion']=$cod32;
					$registros=array_replace($importData, $value);
					unset($valueSets);
					unset($registros['logo']);
					foreach($registros as $keyPrincipal => $atributo) {
						$registros[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
					}
					foreach ($registros as $key => $value) {
						if($key !='id'){
							$valueSets[] = $key . " = '" . $value . "'";
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


					unset($registros['id']);
					$fields_pdo = "`".implode('`,`', array_keys($registros))."`";
					$values_pdo = "'".implode("','", $registros)."'";
					$insert_configuracion_historicos= "INSERT INTO configuracion_historicos ($fields_pdo) VALUES ($values_pdo);";
					$success=$insert_configuracion_historicos=$conexion->query($insert_configuracion_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_configuracion_historicos || $num=0){
						$success=false;
						echo "ERROR insert_configuracion_historicos"; 
						var_dump($conexion->error);
					}

					$insertArray[$numarray]['id']=$id;
					$insertArray[$numarray]['tabla']=$tabla;
					$insertArray[$numarray]['tipo']="Update";
					$numarray=$numarray+1;
				}
			}else{
				echo "No se modifico ningun registro, datos igual al anterior registro";
				die;
			}

			
		}
		if($numarray>0 && $success){
			$log_importacion['id_usuario']=$usuario['id'];
			$log_importacion['tabla']=$tabla;
			$log_importacion['tipo']='Update';
			$log_importacion['referencia_importacion']=$cod32;
			$log_importacion['fechaR']=$fechaH;
			$log_importacion['status']=1;
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