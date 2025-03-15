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

		$success=true;
		$conexion->autocommit(FALSE);
		$data=$_SESSION['data_import'];
		$countData=0;
		foreach ($data as $key => $value) {
			//cambios de columnas
			if($tabla=='identidades'){
				$otra_tabla=1;
				//tablas que se hacen insertan en multiples tablas
				//primero identidades
				$identidades=$value;
				unset($identidades['usuario']);
				unset($identidades['password']);
				$identidades['fechaR']=$fechaH; 
				$identidades['fechaU']=$fechaH; 
				$identidades['codigo_plataforma']=$codigo_plataforma;
				$identidades['referencia_importacion']=$cod32;
				if($value['pais']!=""){
				$identidades['id_pais']=paisId($identidades['pais']);
					unset($identidades['pais']);
				}
				if($identidades['estado']!=""){
					if($identidades['estado']=="México" || $identidades['estado']=="méxico"){
						$identidades['id_estado']=estadoId($identidades['estado'],1);
						unset($identidades['estado']);
					}else{
						$identidades['id_estado']=estadoId($identidades['estado'],"");
						unset($identidades['estado']);
					}
				}

				if($identidades['municipio']!=""){
					$identidades['id_municipio']=municipioId($identidades['municipio'],$identidades['id_estado']);
					unset($identidades['municipio']);
				}
				if($identidades['localidad']!=""){
					$identidades['id_localidad']=localidadId($identidades['localidad'],$identidades['id_estado'],$identidades['id_municipio']);
					unset($identidades['localidad']);
				}
				$length=6; 
				$mk_id=time();
				$gen_id = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); 
				$gen_id1=$gen_id.$mk_id; 

				$length=6; 
				$mk_id=time()*2*36*12/3;
				$gen_id = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz"), 0, $length); 
				$gen_id2=$gen_id.$mk_id; 
				$identidades['codigo_identidad']=$gen_id1.$gen_id2."_".$codigo_plataforma; 


				foreach($identidades as $keyPrincipal => $atributo) {
					$identidades[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
				} 

				$fields_pdo = "`".implode('`,`', array_keys($identidades))."`";
				$values_pdo = "'".implode("','", $identidades)."'";
				$insert= "INSERT INTO identidades ($fields_pdo) VALUES ($values_pdo);";
				$insert=$conexion->query($insert);
				$num=$conexion->affected_rows;
				if(!$insert || $num=0){
					$success=false;
					echo "ERROR insert_identidades"; 
					var_dump($conexion->error);
				}

				$id_identidad=$identidades['id_identidad']=$conexion->insert_id;
				$fields_pdo = "`".implode('`,`', array_keys($identidades))."`";
				$values_pdo = "'".implode("','", $identidades)."'";
				$insert_historicos= "INSERT INTO identidades_historicos ($fields_pdo) VALUES ($values_pdo);";
				$insert_historicos=$conexion->query($insert_historicos);
				$num=$conexion->affected_rows;
				if(!$insert_historicos || $num=0){
					$success=false;
					echo "ERROR insert_identidades_historicos"; 
					var_dump($conexion->error);
				}

				$insertArray[$numarray]['id']=$id_identidad;
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