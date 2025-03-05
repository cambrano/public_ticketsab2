<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/tipos_gastos.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad',"tipos_gastos",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["tipo_gasto"][0] as $keyPrincipal => $atributo) {
		$_POST["tipo_gasto"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$tipo_gastoClaveVerificacion=tipo_gastoClaveVerificacion($_POST["tipo_gasto"][0]["clave"],$_POST["tipo_gasto"][0]['id'],1);
	if($tipo_gastoClaveVerificacion){
		$claveF= clave("tipos_gastos");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["tipo_gasto"][0]["clave"] = $claveF["clave"];
		}
	}

	if( registrosCompara("tipos_gastos",$_POST["tipo_gasto"][0],1)){
		if(!empty($_POST)){ 
			$_POST["tipo_gasto"][0]["fechaR"]=$fechaH;
			$_POST["tipo_gasto"][0]["codigo_plataforma"]=$codigo_plataforma;
			$success=true;
			foreach($_POST["tipo_gasto"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_tipos_gastos = "UPDATE tipos_gastos SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_tipos_gastos=$conexion->query($update_tipos_gastos);
			$num=$conexion->affected_rows;
			if(!$update_tipos_gastos || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_tipos_gastos"; 
				var_dump($conexion->error);
			}

			unset($_POST["tipo_gasto"][0]['id']); 
			$id_tipo_gasto=$_POST["tipo_gasto"][0]["id_tipo_gasto"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["tipo_gasto"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["tipo_gasto"][0])."'";
			$insert_tipos_gastos_historicos= "INSERT INTO tipos_gastos_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_tipos_gastos_historicos=$conexion->query($insert_tipos_gastos_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_tipos_gastos_historicos || $num=0){
				$success=false;
				echo "ERROR insert_tipos_gastos_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],"tipos_gastos",$id_tipo_gasto,'Update','',$fechaH);
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
