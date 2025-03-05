<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/responsables_equipos.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','responsables_equipos',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["responsable_equipo"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["responsable_equipo"][0] as $keyPrincipal => $atributo) {
			$_POST["responsable_equipo"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$responsable_equipoClaveVerificacion=responsable_equipoClaveVerificacion($_POST["responsable_equipo"][0]['clave'],'',1);
		if($responsable_equipoClaveVerificacion){
			$claveF= clave('responsables_equipos');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["responsable_equipo"][0]['clave'] = $claveF['clave'];
			}
		}

		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["responsable_equipo"][0]['fechaR']=$fechaH; 
		$_POST["responsable_equipo"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['responsable_equipo'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['responsable_equipo'][0])."'";
		$inset_responsables_equipos= "INSERT INTO responsables_equipos ($fields_pdo) VALUES ($values_pdo);";

		$inset_responsables_equipos=$conexion->query($inset_responsables_equipos);
		$num=$conexion->affected_rows;
		if(!$inset_responsables_equipos || $num=0){
			$success=false;
			echo "ERROR inset_responsables_equipos"; 
			var_dump($conexion->error);
		}

		$id=$_POST['responsable_equipo'][0]['id_responsable_equipo']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['responsable_equipo'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['responsable_equipo'][0])."'";
		$inset_responsables_equipos_historicos= "INSERT INTO responsables_equipos_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_responsables_equipos_historicos=$conexion->query($inset_responsables_equipos_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_responsables_equipos_historicos || $num=0){
			$success=false;
			echo "ERROR inset_responsables_equipos_historicos"; 
			var_dump($conexion->error);
		}
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'responsables_equipos',$id,'Insert','',$fechaH);
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