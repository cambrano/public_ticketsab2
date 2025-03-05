<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/tipos_gastos_asignados.php";
	include __DIR__."/../functions/claves_2.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','tipos_gastos_asignados',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["tipo_gasto_asignado"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["tipo_gasto_asignado"][0] as $keyPrincipal => $atributo) {
			$_POST["tipo_gasto_asignado"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$tipo_gasto_asignadoClaveVerificacion=tipo_gasto_asignadoClaveVerificacion($_POST["tipo_gasto_asignado"][0]['clave'],'',1);
		if($tipo_gasto_asignadoClaveVerificacion){
			$claveF= clave('tipos_gastos_asignados');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["tipo_gasto_asignado"][0]['clave'] = $claveF['clave'];
			}
		}

		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["tipo_gasto_asignado"][0]['fechaR']=$fechaH; 
		$_POST["tipo_gasto_asignado"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['tipo_gasto_asignado'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['tipo_gasto_asignado'][0])."'";
		$inset_tipos_gastos_asignados= "INSERT INTO tipos_gastos_asignados ($fields_pdo) VALUES ($values_pdo);";

		$inset_tipos_gastos_asignados=$conexion->query($inset_tipos_gastos_asignados);
		$num=$conexion->affected_rows;
		if(!$inset_tipos_gastos_asignados || $num=0){
			$success=false;
			echo "ERROR inset_tipos_gastos_asignados"; 
			var_dump($conexion->error);
		}

		$id=$_POST['tipo_gasto_asignado'][0]['id_tipo_gasto_asignado']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['tipo_gasto_asignado'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['tipo_gasto_asignado'][0])."'";
		$inset_tipos_gastos_asignados_historicos= "INSERT INTO tipos_gastos_asignados_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_tipos_gastos_asignados_historicos=$conexion->query($inset_tipos_gastos_asignados_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_tipos_gastos_asignados_historicos || $num=0){
			$success=false;
			echo "ERROR inset_tipos_gastos_asignados_historicos"; 
			var_dump($conexion->error);
		}
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'tipos_gastos_asignados',$id,'Insert','',$fechaH);
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