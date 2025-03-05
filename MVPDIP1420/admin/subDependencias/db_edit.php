<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/sub_dependencias.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/tool_xhpzab.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad',"dependencias",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["sub_dependencia"][0] as $keyPrincipal => $atributo) {
		$_POST["sub_dependencia"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$sub_dependenciaClaveVerificacion=sub_dependenciaClaveVerificacion($_POST["sub_dependencia"][0]["clave"],$_POST["sub_dependencia"][0]['id'],1);
	if($sub_dependenciaClaveVerificacion){
		$claveF= clave("sub_dependencias");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["sub_dependencia"][0]["clave"] = $claveF["clave"];
		}
	}

	if( registrosCompara("sub_dependencias",$_POST["sub_dependencia"][0],1)){
		if(!empty($_POST)){ 
			$sub_dependenciaDatos = sub_dependenciaDatos($_POST["sub_dependencia"][0]["id"]);
			$_POST["sub_dependencia"][0]["fechaR"]=$fechaH;
			$_POST["sub_dependencia"][0]['id_dependencia']=$sub_dependenciaDatos['id_dependencia'];
			$_POST["sub_dependencia"][0]["codigo_plataforma"]=$codigo_plataforma;
			$success=true;
			foreach($_POST["sub_dependencia"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_sub_dependencias = "UPDATE sub_dependencias SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_sub_dependencias=$conexion->query($update_sub_dependencias);
			$num=$conexion->affected_rows;
			if(!$update_sub_dependencias || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_sub_dependencias"; 
				var_dump($conexion->error);
			}

			unset($_POST["sub_dependencia"][0]['id']); 
			$id_sub_dependencia=$_POST["sub_dependencia"][0]["id_sub_dependencia"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["sub_dependencia"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["sub_dependencia"][0])."'";
			$insert_sub_dependencias_historicos= "INSERT INTO sub_dependencias_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_sub_dependencias_historicos=$conexion->query($insert_sub_dependencias_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_sub_dependencias_historicos || $num=0){
				$success=false;
				echo "ERROR insert_sub_dependencias_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],"sub_dependencias",$id_sub_dependencia,'Update','',$fechaH);
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
