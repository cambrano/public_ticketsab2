<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/servidores_correos.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('perfiles','servidores_correos',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["servidor_correo"][0] as $keyPrincipal => $atributo) {
		$_POST["servidor_correo"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$servidor_correoClaveVerificacion=servidor_correoClaveVerificacion($_POST["servidor_correo"][0]["clave"],$_POST["servidor_correo"][0]['id'],1);
	if($servidor_correoClaveVerificacion){
		$claveF= clave("servidores_correos");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["servidor_correo"][0]["clave"] = $claveF["clave"];
		}
	}

	if(!empty($_POST)){
		if( registrosCompara("servidores_correos",$_POST['servidor_correo'][0],1)){
			$_POST["servidor_correo"][0]['fechaR']=$fechaH;
			$_POST["servidor_correo"][0]['codigo_plataforma']=$codigo_plataforma;

			$servidor_correoDatos=servidor_correoDatos($_POST["servidor_correo"][0]['id']);
			$_POST["servidor_correo"][0]["referencia_importacion"]=$servidor_correoDatos['referencia_importacion'];

			$success=true;
			foreach($_POST['servidor_correo'] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_servidores_correos = "UPDATE servidores_correos SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_servidores_correos=$conexion->query($update_servidores_correos);
			$num=$conexion->affected_rows;
			if(!$update_servidores_correos || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_servidores_correos"; 
				var_dump($conexion->error);
			}

			unset($_POST["servidor_correo"][0]['id']); 
			$id_servidor_correo=$_POST['servidor_correo'][0]['id_servidor_correo']=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST['servidor_correo'][0]))."`";
			$values_pdo = "'".implode("','", $_POST['servidor_correo'][0])."'";
			$inset_servidores_correos_historicos= "INSERT INTO servidores_correos_historicos ($fields_pdo) VALUES ($values_pdo);";
			$inset_servidores_correos_historicos=$conexion->query($inset_servidores_correos_historicos);
			$num=$conexion->affected_rows;
			if(!$inset_servidores_correos_historicos || $num=0){
				$success=false;
				echo "ERROR inset_servidores_correos_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'servidores_correos',$id_servidor_correo,'Update','',$fechaH);
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