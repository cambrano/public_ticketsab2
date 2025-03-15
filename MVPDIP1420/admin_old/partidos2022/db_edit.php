<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/partidos_.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"partidos_",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}
	//metemos los valores para que se no tengamos error
	foreach($_POST["partido_"][0] as $keyPrincipal => $atributo) {
		$_POST["partido_"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$partido_ClaveVerificacion=partido_ClaveVerificacion($_POST["partido_"][0]["clave"],$_POST["partido_"][0]['id'],1);
	if($partido_ClaveVerificacion){
		$claveF= clave("partidos_");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["partido_"][0]["clave"] = $claveF["clave"];
		}
	}

	if( registrosCompara("partidos_",$_POST["partido_"][0],1)){
		if(!empty($_POST)){ 
			$_POST["partido_"][0]['fechaR']=$fechaH;
			$_POST["partido_"][0]['codigo_plataforma']=$codigo_plataforma;
 
			$success=true;
			foreach($_POST["partido_"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_partidos_ = "UPDATE partidos_ SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_partidos_=$conexion->query($update_partidos_);
			$num=$conexion->affected_rows;
			if(!$update_partidos_ || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_partidos_"; 
				var_dump($conexion->error);
			}

			unset($_POST["partido_"][0]['id']); 
			$id_partido_=$_POST["partido_"][0]['id_partido_']=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["partido_"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["partido_"][0])."'";
			$inset_partidos__historicos= "INSERT INTO partidos__historicos ($fields_pdo) VALUES ($values_pdo);";
			$inset_partidos__historicos=$conexion->query($inset_partidos__historicos);
			$num=$conexion->affected_rows;
			if(!$inset_partidos__historicos || $num=0){
				$success=false;
				echo "ERROR inset_partidos__historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'partidos_',$id_partido_,'Update','',$fechaH);
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
