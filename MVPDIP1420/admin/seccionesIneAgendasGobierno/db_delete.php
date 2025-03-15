<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_agendas_gobierno',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['delete'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST as $keyPrincipal => $atributo) {
			$_POST[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$id = $_POST['id'];
		$success = true;
		$conexion->autocommit(FALSE); // Iniciar transacción

		$queries = [
			"DELETE FROM secciones_ine_agendas_gobierno_locaciones WHERE id_seccion_ine_agenda_gobierno=? AND id<>0",
			"DELETE FROM secciones_ine_agendas_gobierno_dependencias WHERE id_seccion_ine_agenda_gobierno=? AND id<>0",
			"DELETE FROM secciones_ine_agendas_gobierno_dependencias_generales WHERE id_seccion_ine_agenda_gobierno=? AND id<>0",
			"DELETE FROM secciones_ine_agendas_gobierno WHERE id=?"
		];
		
		// Recorrer las consultas y ejecutarlas
		foreach ($queries as $sql_delete) {
			$stmt = $conexion->prepare($sql_delete);
		
			if ($stmt === false) {
				echo "ERROR al preparar consulta: " . mysqli_error($conexion) . "<br>";
				$success = false;
				break;
			}
		
			$stmt->bind_param('i', $id);
		
			if (!$stmt->execute()) {
				echo "ERROR al ejecutar consulta: " . mysqli_error($conexion) . "<br>";
				$success = false;
				$stmt->close();
				break;
			}
		
			if ($stmt->affected_rows === 0) {
				echo "ERROR: No se eliminaron registros en la consulta ejecutada.<br>";
				$success = false;
				$stmt->close();
				break;
			}
		
			$stmt->close();
		}

		if ($success) {
			$log = logUsuario($_COOKIE["id_usuario"], 'secciones_ine_agendas_gobierno', $id, 'Delete', '', $fechaH);
			if ($log == true) {
				echo "SI";
				$conexion->commit(); // Confirmar los cambios en la base de datos
				$conexion->close();
			} else {
				echo "NO";
				$conexion->rollback(); // Revertir los cambios si ocurre algún error en el log
				$conexion->close();
			}
		} else {
			echo "NO";
			$conexion->rollback(); // Revertir cambios si ha ocurrido algún error en las eliminaciones
			$conexion->close();
		}
	}
