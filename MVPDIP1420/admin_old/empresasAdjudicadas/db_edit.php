<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/empresas_adjudicadas.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"empresas_adjudicadas",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	$_POST['empresa_adjudicada'][0]['representante_nombre_completo'] = $_POST['empresa_adjudicada'][0]['representante_nombre'].' '.$_POST['empresa_adjudicada'][0]['representante_apellido_paterno'].' '.$_POST['empresa_adjudicada'][0]['representante_apellido_materno'];

	//metemos los valores para que se no tengamos error
	foreach($_POST["empresa_adjudicada"][0] as $keyPrincipal => $atributo) {
		$_POST["empresa_adjudicada"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$empresa_adjudicadaClaveVerificacion=empresa_adjudicadaClaveVerificacion($_POST["empresa_adjudicada"][0]["clave"],$_POST["empresa_adjudicada"][0]['id'],1);
	if($empresa_adjudicadaClaveVerificacion){
		$claveF= clave("empresas_adjudicadas");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["empresa_adjudicada"][0]["clave"] = $claveF["clave"];
		}
	}

	if( registrosCompara("empresas_adjudicadas",$_POST["empresa_adjudicada"][0],1)){
		if(!empty($_POST)){ 
			$_POST["empresa_adjudicada"][0]["fechaR"]=$fechaH;
			$_POST["empresa_adjudicada"][0]["codigo_plataforma"]=$codigo_plataforma;
			$success=true;
			foreach($_POST["empresa_adjudicada"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_empresas_adjudicadas = "UPDATE empresas_adjudicadas SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_empresas_adjudicadas=$conexion->query($update_empresas_adjudicadas);
			$num=$conexion->affected_rows;
			if(!$update_empresas_adjudicadas || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_empresas_adjudicadas"; 
				var_dump($conexion->error);
			}

			unset($_POST["empresa_adjudicada"][0]['id']); 
			$id_empresa_adjudicada=$_POST["empresa_adjudicada"][0]["id_empresa_adjudicada"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["empresa_adjudicada"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["empresa_adjudicada"][0])."'";
			$insert_empresas_adjudicadas_historicos= "INSERT INTO empresas_adjudicadas_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_empresas_adjudicadas_historicos=$conexion->query($insert_empresas_adjudicadas_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_empresas_adjudicadas_historicos || $num=0){
				$success=false;
				echo "ERROR insert_empresas_adjudicadas_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],"empresas_adjudicadas",$id_empresa_adjudicada,'Update','',$fechaH);
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
