<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','campañas_mailing',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['delete'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST as $keyPrincipal => $atributo) {
			$_POST[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$conexion->autocommit(FALSE);
		$id=$_POST['id']; 
		$success=true;

		$delete_campanas_mailing = "DELETE FROM campanas_mailing_cuerpos  WHERE id<>0 AND id_campana_mailing='{$id}' ";
		$delete_campanas_mailing=$conexion->query($delete_campanas_mailing);
		$num=$conexion->affected_rows;
		if(!$delete_campanas_mailing || $num=0){
			$success=false;
			echo "ERROR delete campaña mailing cuerpo"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}

		$delete_campanas_mailing = "DELETE FROM campanas_mailing_programadas  WHERE id<>0 AND id_campana_mailing='{$id}' ";
		$delete_campanas_mailing=$conexion->query($delete_campanas_mailing);
		$num=$conexion->affected_rows;
		if(!$delete_campanas_mailing || $num=0){
			$success=false;
			echo "ERROR delete campaña mailing programadas"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}

		$delete_campanas_mailing = "DELETE FROM campanas_mailing_cartografias  WHERE id<>0 AND id_campana_mailing='{$id}' ";
		$delete_campanas_mailing=$conexion->query($delete_campanas_mailing);
		$num=$conexion->affected_rows;
		if(!$delete_campanas_mailing || $num=0){
			$success=false;
			echo "ERROR delete campaña mailing cartografias"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}


		$delete_campanas_mailing = "DELETE FROM campanas_mailing_encuestas  WHERE id<>0 AND id_campana_mailing='{$id}' ";
		$delete_campanas_mailing=$conexion->query($delete_campanas_mailing);
		$num=$conexion->affected_rows;
		if(!$delete_campanas_mailing || $num=0){
			$success=false;
			echo "ERROR delete campaña mailing encuestas"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}

		$delete_campanas_mailing = "DELETE FROM campanas_mailing_tipos_ciudadanos  WHERE id<>0 AND id_campana_mailing='{$id}' ";
		$delete_campanas_mailing=$conexion->query($delete_campanas_mailing);
		$num=$conexion->affected_rows;
		if(!$delete_campanas_mailing || $num=0){
			$success=false;
			echo "ERROR delete campaña mailing tipos ciudadanos"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}

		$delete_campanas_mailing = "DELETE FROM campanas_mailing_tipos_categorias_ciudadanos  WHERE id<>0 AND id_campana_mailing='{$id}' ";
		$delete_campanas_mailing=$conexion->query($delete_campanas_mailing);
		$num=$conexion->affected_rows;
		if(!$delete_campanas_mailing || $num=0){
			$success=false;
			echo "ERROR delete campaña mailing tipos categorías ciudadanos"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}

		$delete_campanas_mailing = "DELETE FROM campanas_mailing  WHERE  id='{$id}' ";
		$delete_campanas_mailing=$conexion->query($delete_campanas_mailing);
		$num=$conexion->affected_rows;
		if(!$delete_campanas_mailing || $num=0){
			$success=false;
			echo "ERROR delete campaña mailing"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'campanas_mailing',$id,'Delete','',$fechaH);
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
			echo "";
			$conexion->rollback();
			$conexion->close();
		}
		 
	}
