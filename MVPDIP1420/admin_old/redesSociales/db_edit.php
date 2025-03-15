<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/redes_sociales.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('perfiles','redes_sociales',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["red_social"][0] as $keyPrincipal => $atributo) {
		$_POST["red_social"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	
	$red_socialClaveVerificacion=red_socialClaveVerificacion($_POST["red_social"][0]["clave"],$_POST["red_social"][0]['id'],1);
	if($red_socialClaveVerificacion){
		$claveF= clave("redes_sociales");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["red_social"][0]["clave"] = $claveF["clave"];
		}
	}

	if( registrosCompara("redes_sociales",$_POST['red_social'][0],1)){
		if(!empty($_POST)){ 
			$_POST["red_social"][0]['fechaR']=$fechaH;
			$_POST["red_social"][0]['codigo_plataforma']=$codigo_plataforma;

			$red_socialDatos=red_socialDatos($_POST["red_social"][0]['id']);
			$_POST["red_social"][0]["referencia_importacion"]=$red_socialDatos['referencia_importacion'];

			$success=true;
			foreach($_POST['red_social'] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_redes_sociales = "UPDATE redes_sociales SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_redes_sociales=$conexion->query($update_redes_sociales);
			$num=$conexion->affected_rows;
			if(!$update_redes_sociales || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_redes_sociales"; 
				var_dump($conexion->error);
			}

			unset($_POST["red_social"][0]['id']); 
			$id_red_social=$_POST['red_social'][0]['id_red_social']=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST['red_social'][0]))."`";
			$values_pdo = "'".implode("','", $_POST['red_social'][0])."'";
			$inset_redes_sociales_historicos= "INSERT INTO redes_sociales_historicos ($fields_pdo) VALUES ($values_pdo);";
			$inset_redes_sociales_historicos=$conexion->query($inset_redes_sociales_historicos);
			$num=$conexion->affected_rows;
			if(!$inset_redes_sociales_historicos || $num=0){
				$success=false;
				echo "ERROR inset_redes_sociales_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'redes_sociales',$id_red_social,'Update','',$fechaH);
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
