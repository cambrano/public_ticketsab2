<?php
	if($_FILES['file']["type"]!="text/csv"){
		echo "<br><div class='mensajeError'>Archivo Incorrecto debe ser un .CSV </div>";
		die;
	}
	include "../../functions/security.php";
	include "../../functions/genid.php";
	include "../../functions/timemex.php";
	include "../../functions/log_usuarios.php";
	include "../../functions/usuarios.php";
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

		$success=true;
		$conexion->autocommit(FALSE);
		$mensaje="";
		$data=$_SESSION['data_import'];
		$countData=0;

		$errorvalor[]=1;
		foreach ($data as $key => $value) {
			unset($value[$primaria]);
			$errorvalor[]=2;
			//cambios de columnas
			if($tabla=="configuracion" ){
				$errorvalor[]=3;
				$configuracionDatos=configuracionDatos();
				//var_dump($configuracionDatos);
				$id=$configuracionDatos['id']; 
				// tabalas que no tienen historicos
				if($countData==0 && $id==""){
					$numarray=$numarray+1;
					$errorvalor[]=4;
					$value['codigo_plataforma']=$codigo_plataforma;
					$value['referencia_importacion']=$cod32;
					$value['fechaR']=$fechaH;
					foreach($value as $keyPrincipal => $atributo) {
						$value[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
					}
					$fields_pdo = "`".implode('`,`', array_keys($value))."`";
					$values_pdo = "'".implode("','", $value)."'";
					$insert= "INSERT INTO {$tabla} ($fields_pdo) VALUES ($values_pdo);";
					$insert=$conexion->query($insert);
					$num=$conexion->affected_rows;
					if(!$insert || $num=0){
						$success=false;
						$mensaje .= "ERROR insert_{$tabla} ".$conexion->error; 
						//var_dump($conexion->error);
					}

					$id=$conexion->insert_id;

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
					$insertArray[$numarray]['tipo']="Insert";
					$numarray=$numarray+1;
				}else{
					echo "No se inserto ningun registro, ya existe.";
					die;
				}
			}
			 
		}
		//var_dump($errorvalor);
		//validador si esta bien
		if($numarray>0 && $success){
			$log_importacion['id_usuario']=$usuario['id'];
			$log_importacion['tabla']=$tabla;
			$log_importacion['tipo']='Insert';
			$log_importacion['referencia_importacion']=$cod32;
			$log_importacion['fechaR']=$fechaH;
			$log_importacion['status']=1;
			$fields_pdo = "`".implode('`,`', array_keys($log_importacion))."`";
			$values_pdo = "'".implode("','", $log_importacion)."'";
			$insert_importacion= "INSERT INTO log_importaciones ($fields_pdo) VALUES ($values_pdo);";
			$insert_importacion=$conexion->query($insert_importacion);
			$num=$conexion->affected_rows;
			if(!$insert_importacion || $num=0){
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
				echo "No se inserto ningun registro".$mensaje;
			}
		}

	}
?>