<?php
	@session_start();
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/correos_electronicos.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('perfiles','correos_electronicos',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["correo_electronico"][0] as $keyPrincipal => $atributo) {
			$_POST["correo_electronico"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}

		$correo_electronicoClaveVerificacion=correo_electronicoClaveVerificacion($_POST["correo_electronico"][0]['clave'],'',1);
		if($correo_electronicoClaveVerificacion){
			$claveF= clave('correos_electronicos');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["correo_electronico"][0]['clave'] = $claveF['clave'];
			}
		}

		$success=true;
		$_POST["correo_electronico"][0]['fechaR']=$fechaH;  
		$_POST["correo_electronico"][0]['fecha_hora_emision']=$_POST["correo_electronico"][0]['fecha_emision']." ".$_POST["correo_electronico"][0]['hora_emision'];
		$_POST["correo_electronico"][0]['codigo_plataforma']=$codigo_plataforma;
		//$_POST["correo_electronico"][0]['detalle']=mysqli_real_escape_string($conexion,$_POST["correo_electronico"][0]['detalle']);

		$fields_pdo = "`".implode('`,`', array_keys($_POST["correo_electronico"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["correo_electronico"][0])."'";
		$inset_correos_electronicos= "INSERT INTO correos_electronicos ($fields_pdo) VALUES ($values_pdo);";
		$conexion->autocommit(FALSE);

		$inset_correos_electronicos=$conexion->query($inset_correos_electronicos);
		$num=$conexion->affected_rows;
		if(!$inset_correos_electronicos || $num=0){
			$success=false;
			echo "ERROR inset_correos_electronicos"; 
			var_dump($conexion->error);
		}

		$id=$_POST["correo_electronico"][0]['id_correo_electronico']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST["correo_electronico"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["correo_electronico"][0])."'";
		$inset_correos_electronicos_historicos= "INSERT INTO correos_electronicos_historicos ($fields_pdo) VALUES ($values_pdo);";
		 

		$inset_correos_electronicos_historicos=$conexion->query($inset_correos_electronicos_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_correos_electronicos_historicos || $num=0){
			$success=false;
			echo "ERROR inset_correos_electronicos_historicos"; 
			var_dump($conexion->error);
		}


		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'correos_electronicos',$id,'Insert','',$fechaH);
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