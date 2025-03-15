<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','tipos_categorias_ciudadanos',$_COOKIE["id_usuario"]);
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



		$delete_tipos_categorias_ciudadanos = "DELETE FROM tipos_categorias_ciudadanos  WHERE  id='{$id}' ";
		$delete_tipos_categorias_ciudadanos=$conexion->query($delete_tipos_categorias_ciudadanos);
		$num=$conexion->affected_rows;
		if(!$delete_tipos_categorias_ciudadanos || $num=0){
			$success=false;
			echo "ERROR delete tipo categoría ciudadano"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}
		


		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'tipos_categorias_ciudadanos',$id,'Delete','',$fechaH);
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
