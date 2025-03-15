<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/categorias_programas_apoyos.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','categorias_programas_apoyos',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["categoria_programa_apoyo"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["categoria_programa_apoyo"][0] as $keyPrincipal => $atributo) {
			$_POST["categoria_programa_apoyo"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$categoria_programa_apoyoClaveVerificacion=categoria_programa_apoyoClaveVerificacion($_POST["categoria_programa_apoyo"][0]['clave'],'',1);
		if($categoria_programa_apoyoClaveVerificacion){
			$claveF= clave('categorias_programas_apoyos');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["categoria_programa_apoyo"][0]['clave'] = $claveF['clave'];
			}
		}

		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["categoria_programa_apoyo"][0]['fechaR']=$fechaH; 
		$_POST["categoria_programa_apoyo"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['categoria_programa_apoyo'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['categoria_programa_apoyo'][0])."'";
		$inset_categorias_programas_apoyos= "INSERT INTO categorias_programas_apoyos ($fields_pdo) VALUES ($values_pdo);";

		$inset_categorias_programas_apoyos=$conexion->query($inset_categorias_programas_apoyos);
		$num=$conexion->affected_rows;
		if(!$inset_categorias_programas_apoyos || $num=0){
			$success=false;
			echo "ERROR inset_categorias_programas_apoyos"; 
			var_dump($conexion->error);
		}

		$id=$_POST['categoria_programa_apoyo'][0]['id_categoria_programa_apoyo']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['categoria_programa_apoyo'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['categoria_programa_apoyo'][0])."'";
		$inset_categorias_programas_apoyos_historicos= "INSERT INTO categorias_programas_apoyos_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_categorias_programas_apoyos_historicos=$conexion->query($inset_categorias_programas_apoyos_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_categorias_programas_apoyos_historicos || $num=0){
			$success=false;
			echo "ERROR inset_categorias_programas_apoyos_historicos"; 
			var_dump($conexion->error);
		}
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'categorias_programas_apoyos',$id,'Insert','',$fechaH);
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