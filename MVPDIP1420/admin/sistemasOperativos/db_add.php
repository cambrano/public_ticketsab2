<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/sistemas_operativos.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','sistemas_operativos',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["sistema_operativo"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["sistema_operativo"][0] as $keyPrincipal => $atributo) {
			$_POST["sistema_operativo"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$sistema_operativoClaveVerificacion=sistema_operativoClaveVerificacion($_POST["sistema_operativo"][0]['clave'],'',1);
		if($sistema_operativoClaveVerificacion){
			$claveF= clave('sistemas_operativos');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["sistema_operativo"][0]['clave'] = $claveF['clave'];
			}
		}

		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["sistema_operativo"][0]['fechaR']=$fechaH; 
		$_POST["sistema_operativo"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['sistema_operativo'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['sistema_operativo'][0])."'";
		$inset_sistemas_operativos= "INSERT INTO sistemas_operativos ($fields_pdo) VALUES ($values_pdo);";

		$inset_sistemas_operativos=$conexion->query($inset_sistemas_operativos);
		$num=$conexion->affected_rows;
		if(!$inset_sistemas_operativos || $num=0){
			$success=false;
			echo "ERROR inset_sistemas_operativos"; 
			var_dump($conexion->error);
		}

		$id=$_POST['sistema_operativo'][0]['id_sistema_operativo']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['sistema_operativo'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['sistema_operativo'][0])."'";
		$inset_sistemas_operativos_historicos= "INSERT INTO sistemas_operativos_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_sistemas_operativos_historicos=$conexion->query($inset_sistemas_operativos_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_sistemas_operativos_historicos || $num=0){
			$success=false;
			echo "ERROR inset_sistemas_operativos_historicos"; 
			var_dump($conexion->error);
		}
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'sistemas_operativos',$id,'Insert','',$fechaH);
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