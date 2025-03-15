<?php
		function paquetesSistema($paquete=null) { 
			include 'db.php'; 
			//include '../functions/db.php'; 
			$sql=("SELECT {$paquete} cantidadPaquete FROM configuracion_paquete WHERE 1 = 1  ");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			


			if($paquete=="usuarios_administradores"){
				$conteo=1;
				$cantidadPaquete=$row['cantidadPaquete'];
				$sql=("SELECT COUNT(*) cantidad FROM usuarios WHERE id_perfil_usuario=2 ");
				$resultado = $conexion->query($sql);
				$row=$resultado->fetch_assoc();
			}
			if($paquete=="usuarios_generales"){
				$conteo=1;
				$cantidadPaquete=$row['cantidadPaquete'];
				$sql=("SELECT COUNT(*) cantidad FROM usuarios WHERE id_perfil_usuario=3 ");
				$resultado = $conexion->query($sql);
				$row=$resultado->fetch_assoc();
			}

			if($paquete=="sucursales"){
				$conteo=1;
				$cantidadPaquete=$row['cantidadPaquete'];
				$sql=("SELECT COUNT(*) cantidad FROM sucursales WHERE 1 = 1 ");
				$resultado = $conexion->query($sql);
				$row=$resultado->fetch_assoc();
			}
			if($paquete=="empleados"){
				$conteo=1;
				$cantidadPaquete=$row['cantidadPaquete'];
				$sql=("SELECT COUNT(*) cantidad FROM empleados WHERE tipo=1 ");
				$resultado = $conexion->query($sql);
				$row=$resultado->fetch_assoc();
			}
			$conexion->close();
			if($conteo==1){
				if($cantidadPaquete=="x"){
					$return['permiso']=true;
					$return['paquete']="";
					$return['mensaje']="";
					return $return;
				}else{
					if($cantidadPaquete>$row['cantidad']){
						$return['permiso']=true;
						$return['paquete']="";
						$return['mensaje']="";
						return $return;
					}else{
						$return['permiso']=false;
						$return['paquete']=$paquete;
						if($paquete=="usuarios_administrador"){
							$paquete="Usuario Administrador Sistema";
						}
						if($paquete=="usuario_general"){
							$paquete="Usuario Administrador General";
						}
						$return['mensaje']="Ya excedio su limite de ".$paquete." si desea mas registros favor de comunicarse con el area de ventas.";
						return $return;
					}
				}
			}

			if($paquete=='notificaciones_sistema'){
				if($row['cantidadPaquete']=='SI'){
					return true;
				}else{
					return false;
				}
			}

			if($paquete=='whatsapp'){
				if($row['cantidadPaquete']=='SI'){
					return true;
				}else{
					return false;
				}
			}

			if($paquete=='megas'){
				return $row['cantidadPaquete'];
			}

			if($paquete=='web'){
				if($row['cantidadPaquete']=='SI'){
					return true;
				}else{
					return false;
				}
			}

			//return $paquete;
		}

		function paqueteSistemaDatos(){ 
			include 'db.php'; 
			$sql="SELECT * FROM configuracion_paquete WHERE 1 = 1  ";
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			$conexion->close();
			return $datos;
		}
?>