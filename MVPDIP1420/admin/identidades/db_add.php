<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/identidades.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('perfiles','identidades',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["identidad"][0] as $keyPrincipal => $atributo) {
			$_POST["identidad"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}

		$identidadClaveVerificacion=identidadClaveVerificacion($_POST["identidad"][0]['clave'],'',1);
		if($identidadClaveVerificacion){
			$claveF= clave('identidades');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["identidad"][0]['clave'] = $claveF['clave'];
			}
		}

		$success=true;
		$_POST["identidad"][0]['fechaR']=$fechaH;
		$_POST["identidad"][0]['fechaU']=$fechaH;
		$_POST["identidad"][0]['status']=1;


		$_POST["identidad"][0]['codigo_plataforma']=$codigo_plataforma; 
		$_POST["identidad"][0]['codigo_identidad']=$cod32."_".$codigo_plataforma; 
		$fields_pdo = "`".implode('`,`', array_keys($_POST["identidad"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["identidad"][0])."'";
		$inset_identidades= "INSERT INTO identidades ($fields_pdo) VALUES ($values_pdo);";
		$conexion->autocommit(FALSE);
		$inset_identidades=$conexion->query($inset_identidades);
		$num=$conexion->affected_rows;
		if(!$inset_identidades || $num=0){
			$success=false;
			echo "ERROR inset_identidades"; 
			var_dump($conexion->error);
		}
		$id=$_POST["identidad"][0]['id_identidad']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST["identidad"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["identidad"][0])."'";
		$inset_identidades_historicos= "INSERT INTO identidades_historicos ($fields_pdo) VALUES ($values_pdo);";
		$inset_identidades_historicos=$conexion->query($inset_identidades_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_identidades_historicos || $num=0){
			$success=false;
			echo "ERROR inset_identidades_historicos"; 
			var_dump($conexion->error);
		}

		 


		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'identidades',$id,'Insert','',$fechaH);
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
