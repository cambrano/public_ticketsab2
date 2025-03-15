<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/correos_mailing.php";
	include __DIR__."/../functions/configuracion.php";
	include __DIR__."/../functions/soporte.php";
	include __DIR__."/../functions/dir.php";

	
	//metemos los valores para que se no tengamos error
	foreach($_POST as $keyPrincipal => $atributo) {
		$_POST[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}


	if(!empty($_POST)){
		$config=configuracion();
		$soporteDatos=soporteDatos();
		//include "mensajeprueba.php"; 
		$mensaje = file_get_contents('mensajeprueba.php');
		$mensaje = str_replace("[*_nombre_*]", $config['nombre'], $mensaje);
		$mensaje = str_replace("[*fecha_hora_letras*]", fechaNormalSimpleDDMMAA_ES($fechaSF).' - '.$fechaSH, $mensaje);
		$mensaje = str_replace("[*soporte_telefono*]", $soporteDatos[0]['telefono'], $mensaje);
		$mensaje = str_replace("[*soporte_telefono_sp*]", $soporteDatos[0]['telefono_sp'], $mensaje);
		$mensaje = str_replace("[*soporte_nombre*]", $soporteDatos[0]['nombre'], $mensaje);
		$mensaje = str_replace("[*soporte_whatsapp*]", $soporteDatos[0]['whatsapp'], $mensaje);
		$mensaje = str_replace("[*soporte_whatsapp_sp*]", $soporteDatos[0]['whatsapp_sp'], $mensaje);
		$mensaje = str_replace("[*soporte_correo_electronico*]", $soporteDatos[0]['correo_electronico'], $mensaje);
		$mensaje = str_replace("[*__url__*]", 'https://'.$_SERVER['SERVER_NAME'].'/'.$dir_base.'/'.$dir_produccion.'', $mensaje);
		$mensaje;



		$posicion_coincidencia = strpos($_POST['correo_electronico'], ',');
		if($posicion_coincidencia==true){
			$porciones = explode(",", $_POST['correo_electronico']);
			foreach ($porciones as $key => $value) {
				$para[$key]['correo_electronico']=$value;
				
			}
			$_POST['status']=correoPrueba($_POST,$mensaje,$para,1);
			$_POST['varios_correos_electronicos']=1;
		}else{
			$_POST['status']=correoPrueba($_POST,$mensaje,$_POST['correo_electronico']);
			$_POST['varios_correos_electronicos']=0;
		}

		$_POST['codigo_plataforma']=$codigo_plataforma;
		//checamos si tiene un registro si no es update
		$sql ="SELECT * FROM correos_mailing WHERE codigo_plataforma='{$codigo_plataforma}' ";
		$result = $conexion->query($sql);
		$row=$result->fetch_array();
		$id=$row['id'];
		$success=true;
		$_POST['fechaR']=$fechaH;
		$tipo="Insert";
		$fields_pdo = "`".implode('`,`', array_keys($_POST))."`";
		$values_pdo = "'".implode("','", $_POST)."'";
		$inset_correos_mailing= "INSERT INTO correos_mailing ($fields_pdo) VALUES ($values_pdo);";
		$conexion->autocommit(FALSE);
		$inset_correos_mailing=$conexion->query($inset_correos_mailing);
		$num=$conexion->affected_rows;
		if(!$inset_correos_mailing || $num=0){
			$success=false;
			echo "ERROR inset_correos_mailing"; 
			var_dump($conexion->error);
		}
		$id=$_POST['id_correo_mailing']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST))."`";
		$values_pdo = "'".implode("','", $_POST)."'";
		$inset_correos_mailing_historicos= "INSERT INTO correos_mailing_historicos ($fields_pdo) VALUES ($values_pdo);";
		$conexion->autocommit(FALSE);
		$success=$inset_correos_mailing_historicos=$conexion->query($inset_correos_mailing_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_correos_mailing_historicos || $num=0){
			$success=false;
			echo "ERROR inset_correos_mailing_historicos"; 
			var_dump($conexion->error);
		}

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'correos_mailing',$id,$tipo,'',$fechaH);
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


?>