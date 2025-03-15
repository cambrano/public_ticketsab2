<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/tipos_casillas.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','tipos_casillas',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["tipo_casilla"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["tipo_casilla"][0] as $keyPrincipal => $atributo) {
			$_POST["tipo_casilla"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$tipo_casillaClaveVerificacion=tipo_casillaClaveVerificacion($_POST["tipo_casilla"][0]['clave'],'',1);
		if($tipo_casillaClaveVerificacion){
			$claveF= clave('tipos_casillas');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["tipo_casilla"][0]['clave'] = $claveF['clave'];
			}
		}

		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["tipo_casilla"][0]['fechaR']=$fechaH; 
		$_POST["tipo_casilla"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['tipo_casilla'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['tipo_casilla'][0])."'";
		$inset_tipos_casillas= "INSERT INTO tipos_casillas ($fields_pdo) VALUES ($values_pdo);";

		$inset_tipos_casillas=$conexion->query($inset_tipos_casillas);
		$num=$conexion->affected_rows;
		if(!$inset_tipos_casillas || $num=0){
			$success=false;
			echo "ERROR inset_tipos_casillas"; 
			var_dump($conexion->error);
		}

		$id=$_POST['tipo_casilla'][0]['id_tipo_casilla']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['tipo_casilla'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['tipo_casilla'][0])."'";
		$inset_tipos_casillas_historicos= "INSERT INTO tipos_casillas_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_tipos_casillas_historicos=$conexion->query($inset_tipos_casillas_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_tipos_casillas_historicos || $num=0){
			$success=false;
			echo "ERROR inset_tipos_casillas_historicos"; 
			var_dump($conexion->error);
		}
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'tipos_casillas',$id,'Insert','',$fechaH);
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