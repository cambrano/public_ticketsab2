<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/paquete_sistemas.php";
	if($_COOKIE["id_usuario"]!=1){
		die;
	}
	//metemos los valores para que se no tengamos error
	foreach($_POST["paquete_sistema"][0] as $keyPrincipal => $atributo) {
		$_POST["paquete_sistema"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	if( registrosCompara("configuracion_paquete",$_POST['paquete_sistema'][0],1)){
		if(!empty($_POST)){
			//$_POST['registro']=$fechaH;
			//metemos los valores para que se no tengamos error
			$_POST["paquete_sistema"][0]['fechaR']=$fechaH; 
			$_POST["paquete_sistema"][0]['codigo_plataforma']=$codigo_plataforma;
			$success=true;
			foreach($_POST['paquete_sistema'] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			

			$update_paquete_sistemas = "UPDATE configuracion_paquete SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_paquete_sistemas=$conexion->query($update_paquete_sistemas);
			$num=$conexion->affected_rows;
			if(!$update_paquete_sistemas || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_paquete_sistemas"; 
				var_dump($conexion->error);
			}

			if($success){
				echo "SI";
				$conexion->commit();
				$conexion->close();
			}else{
				echo "NO";
				$conexion->rollback();
				$conexion->close();
			}

		}
	}
