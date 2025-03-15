<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/militantes_partidos.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','militantes_partidos',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["militante_partido"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["militante_partido"][0] as $keyPrincipal => $atributo) {
			$_POST["militante_partido"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$militante_partidoClaveVerificacion=militante_partidoClaveVerificacion($_POST["militante_partido"][0]['clave'],'',1);
		if($militante_partidoClaveVerificacion){
			$claveF= clave('militantes_partidos');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["militante_partido"][0]['clave'] = $claveF['clave'];
			}
		}

		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["militante_partido"][0]['fechaR']=$fechaH; 
		$_POST["militante_partido"][0]['fecha_hora']=$_POST["militante_partido"][0]['fecha']." ".$_POST["militante_partido"][0]['hora'];
		$_POST["militante_partido"][0]['codigo_plataforma']=$codigo_plataforma;
		if($_POST["militante_partido"][0]['status']==1){
			//ponemos en 0 los demas
			$id_seccion_ine_ciudadano = $_POST["militante_partido"][0]['id_seccion_ine_ciudadano'];
			$update_militantes_partidos = "UPDATE militantes_partidos SET `status` = '0' WHERE id<>0 AND id_seccion_ine_ciudadano ='{$id_seccion_ine_ciudadano}' ;";
			$update_militantes_partidos=$conexion->query($update_militantes_partidos);
			$num=$conexion->affected_rows;
			if(!$update_militantes_partidos || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_militantes_partidos"; 
				var_dump($conexion->error);
			}
		}


		$fields_pdo = "`".implode('`,`', array_keys($_POST['militante_partido'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['militante_partido'][0])."'";
		$inset_militantes_partidos= "INSERT INTO militantes_partidos ($fields_pdo) VALUES ($values_pdo);";

		$inset_militantes_partidos=$conexion->query($inset_militantes_partidos);
		$num=$conexion->affected_rows;
		if(!$inset_militantes_partidos || $num=0){
			$success=false;
			echo "ERROR inset_militantes_partidos"; 
			var_dump($conexion->error);
		}

		$id=$_POST['militante_partido'][0]['id_militante_partido']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['militante_partido'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['militante_partido'][0])."'";
		$inset_militantes_partidos_historicos= "INSERT INTO militantes_partidos_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_militantes_partidos_historicos=$conexion->query($inset_militantes_partidos_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_militantes_partidos_historicos || $num=0){
			$success=false;
			echo "ERROR inset_militantes_partidos_historicos"; 
			var_dump($conexion->error);
		}

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'militantes_partidos',$id,'Insert','',$fechaH);
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