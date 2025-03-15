<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/partidos_2021.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"partidos_2021",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}
	//metemos los valores para que se no tengamos error
	foreach($_POST["partido_2021"][0] as $keyPrincipal => $atributo) {
		$_POST["partido_2021"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$partido_2021ClaveVerificacion=partido_2021ClaveVerificacion($_POST["partido_2021"][0]["clave"],$_POST["partido_2021"][0]['id'],1);
	if($partido_2021ClaveVerificacion){
		$claveF= clave("partidos_2021");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["partido_2021"][0]["clave"] = $claveF["clave"];
		}
	}

	if( registrosCompara("partidos_2021",$_POST["partido_2021"][0],1)){
		if(!empty($_POST)){ 
			$_POST["partido_2021"][0]['fechaR']=$fechaH;
			$_POST["partido_2021"][0]['codigo_plataforma']=$codigo_plataforma;
 
			$success=true;
			foreach($_POST["partido_2021"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_partidos_2021 = "UPDATE partidos_2021 SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_partidos_2021=$conexion->query($update_partidos_2021);
			$num=$conexion->affected_rows;
			if(!$update_partidos_2021 || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_partidos_2021"; 
				var_dump($conexion->error);
			}

			unset($_POST["partido_2021"][0]['id']); 
			$id_partido_2021=$_POST["partido_2021"][0]['id_partido_2021']=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["partido_2021"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["partido_2021"][0])."'";
			$inset_partidos_2021_historicos= "INSERT INTO partidos_2021_historicos ($fields_pdo) VALUES ($values_pdo);";
			$inset_partidos_2021_historicos=$conexion->query($inset_partidos_2021_historicos);
			$num=$conexion->affected_rows;
			if(!$inset_partidos_2021_historicos || $num=0){
				$success=false;
				echo "ERROR inset_partidos_2021_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'partidos_2021',$id_partido_2021,'Update','',$fechaH);
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
