<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/programas_apoyos.php";
	include __DIR__."/../functions/programas_apoyos_dependencias.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"programas_apoyos",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["programa_apoyo"][0] as $keyPrincipal => $atributo) {
		$_POST["programa_apoyo"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$programa_apoyoClaveVerificacion=programa_apoyoClaveVerificacion($_POST["programa_apoyo"][0]["clave"],$_POST["programa_apoyo"][0]['id'],1);
	if($programa_apoyoClaveVerificacion){
		$claveF= clave("programas_apoyos");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["programa_apoyo"][0]["clave"] = $claveF["clave"];
		}
	}


	foreach($_POST["programas_apoyos_territorios"] as $keyPrincipal => $atributos) {
		if($atributos['id']=='' && $atributos['check']==1 ){
			$compara_programas_apoyos_territorios = true;
			foreach ($atributos as $key => $value) {
				$_POST["programas_apoyos_territorios"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
			}
		}else{
			if($atributos['id'] !='' && $atributos['check']==0){
				$compara_programas_apoyos_territorios = true;
				foreach ($atributos as $key => $value) {
					$_POST["programas_apoyos_territorios"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
				}
			}
		}
	}

	foreach($_POST["programas_apoyos_categorias"] as $keyPrincipal => $atributos) {
		if($atributos['id']=='' && $atributos['check']==1 ){
			$compara_programas_apoyos_categorias = true;
			foreach ($atributos as $key => $value) {
				$_POST["programas_apoyos_categorias"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
			}
		}else{
			if($atributos['id'] !='' && $atributos['check']==0){
				$compara_programas_apoyos_categorias = true;
				foreach ($atributos as $key => $value) {
					$_POST["programas_apoyos_categorias"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
				}
			}
		}
	}

	foreach($_POST["programas_apoyos_dependencias"] as $keyPrincipal => $atributos) {
		if($atributos['id']=='' && $atributos['check']==1 ){
			$compara_programas_apoyos_dependencias = true;
			foreach ($atributos as $key => $value) {
				$_POST["programas_apoyos_dependencias"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
			}
		}else{
			if($atributos['id'] !='' && $atributos['check']==0){
				$compara_programas_apoyos_dependencias = true;
				foreach ($atributos as $key => $value) {
					$_POST["programas_apoyos_dependencias"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
				}
			}
		}
	}


	if( registrosCompara("programas_apoyos",$_POST["programa_apoyo"][0],1) || $compara_programas_apoyos_territorios || $compara_programas_apoyos_categorias || $compara_programas_apoyos_dependencias){
		if(!empty($_POST)){ 
			$_POST["programa_apoyo"][0]["fechaR"]=$fechaH;
			$_POST["programa_apoyo"][0]["codigo_plataforma"]=$codigo_plataforma;
			$success=true;
			foreach($_POST["programa_apoyo"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_programas_apoyos = "UPDATE programas_apoyos SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_programas_apoyos=$conexion->query($update_programas_apoyos);
			$num=$conexion->affected_rows;
			if(!$update_programas_apoyos || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_programas_apoyos"; 
				var_dump($conexion->error);
			}

			unset($_POST["programa_apoyo"][0]['id']); 
			$id_programa_apoyo=$_POST["programa_apoyo"][0]["id_programa_apoyo"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["programa_apoyo"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["programa_apoyo"][0])."'";
			$insert_programas_apoyos_historicos= "INSERT INTO programas_apoyos_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_programas_apoyos_historicos=$conexion->query($insert_programas_apoyos_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_programas_apoyos_historicos || $num=0){
				$success=false;
				echo "ERROR insert_programas_apoyos_historicos"; 
				var_dump($conexion->error);
			}

			foreach($_POST["programas_apoyos_territorios"] as $keyPrincipal => $atributos) {
				if($atributos['id']=='' && $atributos['check']==1 ){
					///insertamos
					unset($atributos['check']);
					unset($atributos['id']);
					$atributos['codigo_plataforma']=$codigo_plataforma;
					$atributos['fechaR']=$fechaH;
					$atributos['id_programa_apoyo']=$id_programa_apoyo;

					$fields_pdo = "`".implode('`,`', array_keys($atributos))."`";
					$values_pdo = "'".implode("','", $atributos)."'";
					$insert_programas_apoyos_territorios= "INSERT INTO programas_apoyos_territorios ($fields_pdo) VALUES ($values_pdo);";
					$insert_programas_apoyos_territorios=$conexion->query($insert_programas_apoyos_territorios);
					$num=$conexion->affected_rows;
					if(!$insert_programas_apoyos_territorios || $num=0){
						$success=false;
						echo "ERROR insert_programas_apoyos_territorios"; 
						var_dump($conexion->error);
					}

					$atributos['id_programa_apoyo_territorio']=$conexion->insert_id;
					$fields_pdo = "`".implode('`,`', array_keys($atributos))."`";
					$values_pdo = "'".implode("','", $atributos)."'";
					$insert_programas_apoyos_territorios_historicos= "INSERT INTO programas_apoyos_territorios_historicos ($fields_pdo) VALUES ($values_pdo);";

					$insert_programas_apoyos_territorios_historicos=$conexion->query($insert_programas_apoyos_territorios_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_programas_apoyos_territorios_historicos || $num=0){
						$success=false;
						echo "ERROR insert_programas_apoyos_territorios_historicos"; 
						var_dump($conexion->error);
					}

				}elseif($atributos['id']!='' && $atributos['check']==0 ){
					///eliminamos
					$id = $atributos['id'];
					$delete_programas_apoyos_territorios = "DELETE FROM programas_apoyos_territorios  WHERE id = '{$id}' ";
					$delete_programas_apoyos_territorios=$conexion->query($delete_programas_apoyos_territorios);
					$num=$conexion->affected_rows;
					if(!$delete_programas_apoyos_territorios || $num=0){
						$success=false;
						echo "ERROR delete territorio programa"; 
						echo "<br>";
						echo("Errorcode: " . mysqli_errno($conexion));
						echo "<br>";
					}
				}else{}
			}

			foreach($_POST["programas_apoyos_categorias"] as $keyPrincipal => $atributos) {
				if($atributos['id']=='' && $atributos['check']==1 ){
					///insertamos
					unset($atributos['check']);
					unset($atributos['id']);
					$atributos['codigo_plataforma']=$codigo_plataforma;
					$atributos['fechaR']=$fechaH;
					$atributos['id_programa_apoyo']=$id_programa_apoyo;

					$fields_pdo = "`".implode('`,`', array_keys($atributos))."`";
					$values_pdo = "'".implode("','", $atributos)."'";
					$insert_programas_apoyos_categorias= "INSERT INTO programas_apoyos_categorias ($fields_pdo) VALUES ($values_pdo);";
					$insert_programas_apoyos_categorias=$conexion->query($insert_programas_apoyos_categorias);
					$num=$conexion->affected_rows;
					if(!$insert_programas_apoyos_categorias || $num=0){
						$success=false;
						echo "ERROR insert_programas_apoyos_categorias"; 
						var_dump($conexion->error);
					}

					$atributos['id_programa_apoyo_categoria']=$conexion->insert_id;
					$fields_pdo = "`".implode('`,`', array_keys($atributos))."`";
					$values_pdo = "'".implode("','", $atributos)."'";
					$insert_programas_apoyos_categorias_historicos= "INSERT INTO programas_apoyos_categorias_historicos ($fields_pdo) VALUES ($values_pdo);";

					$insert_programas_apoyos_categorias_historicos=$conexion->query($insert_programas_apoyos_categorias_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_programas_apoyos_categorias_historicos || $num=0){
						$success=false;
						echo "ERROR insert_programas_apoyos_categorias_historicos"; 
						var_dump($conexion->error);
					}

				}elseif($atributos['id']!='' && $atributos['check']==0 ){
					///eliminamos
					$id = $atributos['id'];
					$delete_programas_apoyos_categorias = "DELETE FROM programas_apoyos_categorias  WHERE id = '{$id}' ";
					$delete_programas_apoyos_categorias=$conexion->query($delete_programas_apoyos_categorias);
					$num=$conexion->affected_rows;
					if(!$delete_programas_apoyos_categorias || $num=0){
						$success=false;
						echo "ERROR delete categoria programa"; 
						echo "<br>";
						echo("Errorcode: " . mysqli_errno($conexion));
						echo "<br>";
					}
				}else{}
			}

			$programa_apoyo_dependenciaIdDatos = programa_apoyo_dependenciaIdDatos('',$_POST["id"]);
			//hacemos update
			

			unset($valueSets);
			$_POST["programas_apoyos_dependencias"][0]["fechaR"]=$fechaH;
			$_POST["programas_apoyos_dependencias"][0]["codigo_plataforma"]=$codigo_plataforma;
			foreach($_POST["programas_apoyos_dependencias"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_programas_apoyos = "UPDATE programas_apoyos_dependencias SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_programas_apoyos=$conexion->query($update_programas_apoyos);
			$num=$conexion->affected_rows;
			if(!$update_programas_apoyos || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_programas_apoyos"; 
				var_dump($conexion->error);
			}

			unset($_POST["programas_apoyos_dependencias"][0]['id']); 
			$id_programa_apoyo=$_POST["programas_apoyos_dependencias"][0]["id_programa_apoyo_dependencia"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["programas_apoyos_dependencias"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["programas_apoyos_dependencias"][0])."'";
			$insert_programas_apoyos_historicos= "INSERT INTO programas_apoyos_dependencias_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_programas_apoyos_historicos=$conexion->query($insert_programas_apoyos_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_programas_apoyos_historicos || $num=0){
				$success=false;
				echo "ERROR insert_programas_apoyos_historicos"; 
				var_dump($conexion->error);
			}



			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],"programas_apoyos",$id_programa_apoyo,'Update','',$fechaH);
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
