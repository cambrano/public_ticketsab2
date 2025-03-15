<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/partidos_2018.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"partidos_2018",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}
	//metemos los valores para que se no tengamos error
	foreach($_POST["partido_2018"][0] as $keyPrincipal => $atributo) {
		$_POST["partido_2018"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$partido_2018ClaveVerificacion=partido_2018ClaveVerificacion($_POST["partido_2018"][0]["clave"],$_POST["partido_2018"][0]['id'],1);
	if($partido_2018ClaveVerificacion){
		$claveF= clave("partidos_2018");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["partido_2018"][0]["clave"] = $claveF["clave"];
		}
	}

	if( registrosCompara("partidos_2018",$_POST["partido_2018"][0],1)){
		if(!empty($_POST)){ 
			$_POST["partido_2018"][0]['fechaR']=$fechaH;
			$_POST["partido_2018"][0]['codigo_plataforma']=$codigo_plataforma;
 
			$success=true;
			foreach($_POST["partido_2018"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_partidos_2018 = "UPDATE partidos_2018 SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_partidos_2018=$conexion->query($update_partidos_2018);
			$num=$conexion->affected_rows;
			if(!$update_partidos_2018 || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_partidos_2018"; 
				var_dump($conexion->error);
			}

			unset($_POST["partido_2018"][0]['id']); 
			$id_partido_2018=$_POST["partido_2018"][0]['id_partido_2018']=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["partido_2018"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["partido_2018"][0])."'";
			$inset_partidos_2018_historicos= "INSERT INTO partidos_2018_historicos ($fields_pdo) VALUES ($values_pdo);";
			$inset_partidos_2018_historicos=$conexion->query($inset_partidos_2018_historicos);
			$num=$conexion->affected_rows;
			if(!$inset_partidos_2018_historicos || $num=0){
				$success=false;
				echo "ERROR inset_partidos_2018_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'partidos_2018',$id_partido_2018,'Update','',$fechaH);
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
