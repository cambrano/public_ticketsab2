<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/correos_electronicos.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('perfiles',"correos_electronicos",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}
	//metemos los valores para que se no tengamos error
	foreach($_POST["correo_electronico"][0] as $keyPrincipal => $atributo) {
		$_POST["correo_electronico"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$correo_electronicoClaveVerificacion=correo_electronicoClaveVerificacion($_POST["correo_electronico"][0]["clave"],$_POST["correo_electronico"][0]['id'],1);
	if($correo_electronicoClaveVerificacion){
		$claveF= clave("correos_electronicos");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["correo_electronico"][0]["clave"] = $claveF["clave"];
		}
	}

	if( registrosCompara("correos_electronicos",$_POST['correo_electronico'][0],1)){
		if(!empty($_POST)){
			$_POST["correo_electronico"][0]['fechaR']=$fechaH;
			$_POST["correo_electronico"][0]['codigo_plataforma']=$codigo_plataforma;
			$_POST["correo_electronico"][0]['fecha_hora_emision']=$_POST["correo_electronico"][0]['fecha_emision']." ".$_POST["correo_electronico"][0]['hora_emision'];

			$correo_electronicoDatos=correo_electronicoDatos($_POST["correo_electronico"][0]['id']);
			$_POST["correo_electronico"][0]["referencia_importacion"]=$correo_electronicoDatos['referencia_importacion'];

			$success=true;
			foreach($_POST['correo_electronico'] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_correos_electronicos = "UPDATE correos_electronicos SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_correos_electronicos=$conexion->query($update_correos_electronicos);
			$num=$conexion->affected_rows;
			if(!$update_correos_electronicos || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_correos_electronicos"; 
				var_dump($conexion->error);
			}

			unset($_POST["correo_electronico"][0]['id']); 
			$id_correo_electronico=$_POST['correo_electronico'][0]['id_correo_electronico']=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST['correo_electronico'][0]))."`";
			$values_pdo = "'".implode("','", $_POST['correo_electronico'][0])."'";
			$inset_correos_electronicos_historicos= "INSERT INTO correos_electronicos_historicos ($fields_pdo) VALUES ($values_pdo);";
			$inset_correos_electronicos_historicos=$conexion->query($inset_correos_electronicos_historicos);
			$num=$conexion->affected_rows;
			if(!$inset_correos_electronicos_historicos || $num=0){
				$success=false;
				echo "ERROR inset_correos_electronicos_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'correos_electronicos',$id_correo_electronico,'Update','',$fechaH);
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