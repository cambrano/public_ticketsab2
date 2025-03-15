<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/identidades.php";
	include __DIR__."/../functions/usuario_permisos.php";

	$moduloAccionPermisos = moduloAccionPermisos('perfiles','identidades',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["identidad"][0] as $keyPrincipal => $atributo) {
		$_POST["identidad"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$identidadClaveVerificacion=identidadClaveVerificacion($_POST["identidad"][0]["clave"],$_POST["identidad"][0]['id'],1);
	if($identidadClaveVerificacion){
		$claveF= clave("identidades");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["identidad"][0]["clave"] = $claveF["clave"];
		}
	}


	if( registrosCompara("identidades",$_POST['identidad'][0],1) ){
		if(!empty($_POST)){
			$identidadDatos=identidadDatos($_POST['identidad'][0]['id']);
			
			//$_POST['registro']=$fechaH;
			$_POST["identidad"][0]['fechaR']=$fechaH;
			$_POST["identidad"][0]['fechaU']=$fechaH;

			$_POST["identidad"][0]['codigo_plataforma']=$codigo_plataforma;
			$_POST["identidad"][0]["codigo_identidad"]=$identidadDatos['codigo_identidad'];
			$_POST["identidad"][0]["referencia_importacion"]=$identidadDatos['referencia_importacion'];
			$_POST["identidad"][0]['status']=1;

			if($_POST["identidad"][0]['id_identidad_compartido']==""){
				if($identidadDatos['id_identidad_compartido']!=""){
					$_POST["identidad"][0]['id_identidad_compartido']=NULL;
					$conexion->query("SET FOREIGN_KEY_CHECKS=0;");
					$fkck=true;
				}else{
					unset($_POST["identidad"][0]['id_identidad_compartido']);
				}
			}



			$success=true;
			foreach($_POST['identidad'] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			if($_POST["identidad"][0]['id_identidad_compartido']==""){
				unset($_POST["identidad"][0]['id_identidad_compartido']);
			}
			$update_identidades = "UPDATE identidades SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_identidades=$conexion->query($update_identidades);
			$num=$conexion->affected_rows;
			if(!$update_identidades || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_identidades"; 
				var_dump($conexion->error);
			}

			unset($_POST["identidad"][0]['id']); 
			$id_identidad=$_POST['identidad'][0]['id_identidad']=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["identidad"][0]))."`";
			$values_pdo = "'".implode("','", $_POST['identidad'][0])."'";
			$inset_identidades_historicos= "INSERT INTO identidades_historicos ($fields_pdo) VALUES ($values_pdo);";
			$inset_identidades_historicos=$conexion->query($inset_identidades_historicos);
			$num=$conexion->affected_rows;
			if(!$inset_identidades_historicos || $num=0){
				$success=false;
				echo "ERROR inset_identidades_historicos"; 
				var_dump($conexion->error);
			}
			if($fkck){
				$conexion->query("SET FOREIGN_KEY_CHECKS=1;");
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'identidades',$id_identidad,'Update','',$fechaH);
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
	}
