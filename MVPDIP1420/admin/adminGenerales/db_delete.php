<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php"; 
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/tablas_relacionadas.php";
	$moduloAccionPermisos = moduloAccionPermisos('configuracion','empleados',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['delete'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST as $keyPrincipal => $atributo) {
			$_POST[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$id=$_POST['id'];
		$idUusuario=$_POST['idUusuario'];
		$success=true;

		$conexion->autocommit(FALSE);
		$conexion->query("SET FOREIGN_KEY_CHECKS=0;");
		$tablasRelacionadas = tablasRelacionadas('empleados',$id);
		foreach ($tablasRelacionadas['tablas'] as $key => $value) {
			if($value['registros']>0){
				//borramos todo
				$delete = "DELETE FROM ".$value['tabla']."  WHERE  ".$value['columna']."='{$id}' AND id<> 0;";
				$delete=$conexion->query($delete);
				$num=$conexion->affected_rows;
				if(!$delete || $num=0){
					$success=false;
					echo "ERROR delete ".$value['comentario']; 
					var_dump($conexion->error);
				}
			}
		}

		$delete = "DELETE FROM empleados  WHERE  id='{$id}'";
		$delete=$conexion->query($delete);
		$num=$conexion->affected_rows;
		if(!$delete || $num=0){
			$success=false;
			echo "ERROR delete empleados"; 
			var_dump($conexion->error);
		}

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'empleados',$id,'Delete','',$fechaH);
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

