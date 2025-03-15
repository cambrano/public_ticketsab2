<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos_giras.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"secciones_ine_ciudadanos_giras",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["seccion_ine_ciudadano_gira"][0] as $keyPrincipal => $atributo) {
		$_POST["seccion_ine_ciudadano_gira"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$seccion_ine_ciudadano_giraClaveVerificacion=seccion_ine_ciudadano_giraClaveVerificacion($_POST["seccion_ine_ciudadano_gira"][0]["clave"],$_POST["seccion_ine_ciudadano_gira"][0]['id'],1);
	if($seccion_ine_ciudadano_giraClaveVerificacion){
		$claveF= clave("secciones_ine_ciudadanos_giras");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["seccion_ine_ciudadano_gira"][0]["clave"] = $claveF["clave"];
		}
	}

	if( registrosCompara("secciones_ine_ciudadanos_giras",$_POST["seccion_ine_ciudadano_gira"][0],1)){
		if(!empty($_POST)){ 
			$_POST["seccion_ine_ciudadano_gira"][0]["fechaR"]=$fechaH;
			$_POST["seccion_ine_ciudadano_gira"][0]['fecha_hora']=$_POST["seccion_ine_ciudadano_gira"][0]['fecha']." ".$_POST["seccion_ine_ciudadano_gira"][0]['hora'];
			$_POST["seccion_ine_ciudadano_gira"][0]["codigo_plataforma"]=$codigo_plataforma;
			$success=true;
			foreach($_POST["seccion_ine_ciudadano_gira"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_secciones_ine_ciudadanos_giras = "UPDATE secciones_ine_ciudadanos_giras SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_secciones_ine_ciudadanos_giras=$conexion->query($update_secciones_ine_ciudadanos_giras);
			$num=$conexion->affected_rows;
			if(!$update_secciones_ine_ciudadanos_giras || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_secciones_ine_ciudadanos_giras"; 
				var_dump($conexion->error);
			}

			unset($_POST["seccion_ine_ciudadano_gira"][0]['id']); 
			$id_seccion_ine_ciudadano_gira=$_POST["seccion_ine_ciudadano_gira"][0]["id_seccion_ine_ciudadano_gira"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["seccion_ine_ciudadano_gira"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["seccion_ine_ciudadano_gira"][0])."'";
			$insert_secciones_ine_ciudadanos_giras_historicos= "INSERT INTO secciones_ine_ciudadanos_giras_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_secciones_ine_ciudadanos_giras_historicos=$conexion->query($insert_secciones_ine_ciudadanos_giras_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_secciones_ine_ciudadanos_giras_historicos || $num=0){
				$success=false;
				echo "ERROR insert_secciones_ine_ciudadanos_giras_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],"secciones_ine_ciudadanos_giras",$id_seccion_ine_ciudadano_gira,'Update','',$fechaH);
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
