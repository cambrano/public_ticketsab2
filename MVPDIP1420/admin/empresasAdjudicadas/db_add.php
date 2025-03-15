<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/empresas_adjudicadas.php";
	include __DIR__."/../functions/claves_2.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','empresas_adjudicadas',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["empresa_adjudicada"][0]);
	if(!empty($_POST)){

		$_POST['empresa_adjudicada'][0]['representante_nombre_completo'] = $_POST['empresa_adjudicada'][0]['representante_nombre'].' '.$_POST['empresa_adjudicada'][0]['representante_apellido_paterno'].' '.$_POST['empresa_adjudicada'][0]['representante_apellido_materno'];

		//metemos los valores para que se no tengamos error
		foreach($_POST["empresa_adjudicada"][0] as $keyPrincipal => $atributo) {
			$_POST["empresa_adjudicada"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$empresa_adjudicadaClaveVerificacion=empresa_adjudicadaClaveVerificacion($_POST["empresa_adjudicada"][0]['clave'],'',1);
		if($empresa_adjudicadaClaveVerificacion){
			$claveF= clave2('empresas_adjudicadas');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["empresa_adjudicada"][0]['clave'] = $claveF['clave'];
			}
		}

		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["empresa_adjudicada"][0]['fechaR']=$fechaH; 
		$_POST["empresa_adjudicada"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['empresa_adjudicada'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['empresa_adjudicada'][0])."'";
		$inset_empresas_adjudicadas= "INSERT INTO empresas_adjudicadas ($fields_pdo) VALUES ($values_pdo);";

		$inset_empresas_adjudicadas=$conexion->query($inset_empresas_adjudicadas);
		$num=$conexion->affected_rows;
		if(!$inset_empresas_adjudicadas || $num=0){
			$success=false;
			echo "ERROR inset_empresas_adjudicadas"; 
			var_dump($conexion->error);
		}

		$id=$_POST['empresa_adjudicada'][0]['id_empresa_adjudicada']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['empresa_adjudicada'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['empresa_adjudicada'][0])."'";
		$inset_empresas_adjudicadas_historicos= "INSERT INTO empresas_adjudicadas_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_empresas_adjudicadas_historicos=$conexion->query($inset_empresas_adjudicadas_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_empresas_adjudicadas_historicos || $num=0){
			$success=false;
			echo "ERROR inset_empresas_adjudicadas_historicos"; 
			var_dump($conexion->error);
		}
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'empresas_adjudicadas',$id,'Insert','',$fechaH);
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