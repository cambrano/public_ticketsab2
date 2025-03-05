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
	include "../../functions/tipos_casillas.php";
	include "../../functions/secciones_ine.php";

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

		$primaria="id_seccion_ine_actividad";
		foreach ($data as $key => $value) {
			unset($value[$primaria]);

			foreach ($value as $keyT => $valueT) {
				$value[$keyT]= mysqli_real_escape_string($conexion,$valueT);
			}

			//cambios de columnas
			//$value['id_tipo_casilla']=claveId($value['clave_tipos_casillas'],'tipos_casillas');
			//unset($value['clave_tipos_casillas']);
			//$value['id_seccion_ine']=claveId($value['clave_secciones_ine'],'secciones_ine');
			//unset($value['clave_secciones_ine']);
			if($array_secciones_ine[$value['clave_secciones_ine']] ==""){
				$value['id_seccion_ine'] = claveId($value['clave_secciones_ine'],'secciones_ine');
				$array_secciones_ine[$value['clave_secciones_ine']] = $value['id_seccion_ine'];
				unset($value['clave_secciones_ine']);
			}else{
				$value['id_seccion_ine'] = $array_secciones_ine[$value['clave_secciones_ine']];
				unset($value['clave_secciones_ine']);
			}

			if($array_empresas_adjudicadas[$value['clave_empresas_adjudicadas']] ==""){
				$value['id_empresa_adjudicada'] = claveId($value['clave_empresas_adjudicadas'],'empresas_adjudicadas');
				$array_empresas_adjudicadas[$value['clave_empresas_adjudicadas']] = $value['id_empresa_adjudicada'];
				unset($value['clave_empresas_adjudicadas']);
			}else{
				$value['id_empresa_adjudicada'] = $array_empresas_adjudicadas[$value['clave_empresas_adjudicadas']];
				unset($value['clave_empresas_adjudicadas']);
			}

			if($array_supervisores[$value['clave_supervisores']] ==""){
				$value['id_supervisor'] = claveId($value['clave_supervisores'],'supervisores');
				$array_supervisores[$value['clave_supervisores']] = $value['id_supervisor'];
				unset($value['clave_supervisores']);
			}else{
				$value['id_supervisor'] = $array_supervisores[$value['clave_supervisores']];
				unset($value['clave_supervisores']);
			}

			/*
			if($array_pais[$value['pais']] ==""){
				$value['id_pais'] = paisId($value['pais']);
				$array_pais[$value['pais']] = $value['id_pais'];
				unset($value['pais']);
			}else{
				$value['id_pais'] = $array_pais[$value['pais']];
				unset($value['pais']);
			}
			*/

			/*
			if($array_pais[$value['estado']] ==""){
				$value['id_pais'] = paisId($value['estado']);
				$array_pais[$value['estado']] = $value['estado'];
				unset($value['estado']);
			}else{
				$value['id_pais'] = $array_pais[$value['estado']];
				unset($value['estado']);
			}

			if($value['estado']!=""){
				if($value['estado']=="México" || $value['estado']=="méxico"){
					$value['id_estado']=estadoId($value['estado'],1);
					unset($value['estado']);
				}else{
					$value['id_estado']=estadoId($value['estado'],"");
					unset($value['estado']);
				}
			}
			*/
			$value['id_pais']=141;
			unset($value['pais']);
			$value['id_estado']=$id_estado;
			unset($value['estado']);
			if($value['municipio']!=""){
				$value['id_municipio']=municipioIdClave($value['municipio'],$value['id_estado']);
				$value['clave_municipio']=$value['municipio'];
				unset($value['municipio']);
			}

			unset($value['municipio']);

			if($value['localidad']!=""){
				$value['id_localidad']=localidadIdClave($value['localidad'],$value['id_estado'],$value['id_municipio']);
				$value['clave_localidad']=$value['localidad'];
				unset($value['localidad']);
			}


			$value['codigo_plataforma']=$codigo_plataforma;
			$value['referencia_importacion']=$cod32;
			$value['fechaR']=$fechaH;
			//$value['fecha_hora'] = $value['fecha'].' '.$value['hora'];
			foreach($value as $keyPrincipal => $atributo) {
				$value[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
			}

			$fields_pdo = "`".implode('`,`', array_keys($value))."`";
			$values_pdo = "'".implode("','", $value)."'";
			//echo "<pre>";
			//echo print_r($value);
			//echo "</pre>";
			$insert= "INSERT INTO {$tabla} ($fields_pdo) VALUES ($values_pdo);";
			$insert=$conexion->query($insert);
			$num=$conexion->affected_rows;
			if(!$insert || $num=0){
				$success=false;
				echo "ERROR insert_{$tabla}"; 
				var_dump($conexion->error);
				echo "<br>";
			}
			$value[$primaria]=$conexion->insert_id;
			$fields_pdo = "`".implode('`,`', array_keys($value))."`";
			$values_pdo = "'".implode("','", $value)."'";
			$insert= "INSERT INTO {$tabla}_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert=$conexion->query($insert);
			$num=$conexion->affected_rows;
			if(!$insert || $num=0){
				$success=false;
				echo "ERROR insert_{$tabla}_historicos"; 
				var_dump($conexion->error);
				echo "<br>";
				echo "<br>";
			}
			$insertArray[$numarray]['id']=$value[$primaria];
			$insertArray[$numarray]['tabla']=$tabla;
			$insertArray[$numarray]['tipo']="Insert";
			$numarray=$numarray+1;
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