<?php
		function configuracion(){
			include 'db.php'; 
			$sql=("SELECT * FROM configuracion WHERE 1 = 1 ");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc(); 
			$denominacion=$row['nombre']; 
			$logo=$row['logo'];
			$slogan=$row['slogan'];
			if($denominacion==""){
				$denominacion="Ingrese el Nombre";
			}
			if($logo!=""){
				$logo=1;
			}else{
				$logo="";
			}
			$arrayConfiguracion['nombre']=$denominacion;
			$arrayConfiguracion['slogan']=$slogan;
			$arrayConfiguracion['logo']=$logo;
			$conexion->close();
			return $arrayConfiguracion;
		}

		function configuracionDatos(){
			include 'db.php'; 
			$sql=("SELECT * FROM configuracion WHERE 1 = 1 ");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc(); 
			$denominacion=$row['nombre']; 
			$logo=$row['logo'];
			$arrayConfiguracion=$row;
			if($denominacion==""){
				$denominacion="Ingrese el Nombre";
			}
			$arrayConfiguracion['nombre']=$denominacion;
			$arrayConfiguracion['logo']=$logo;
			$arrayConfiguracion['id']=$row['id'];
			$conexion->close();
			return $arrayConfiguracion;
		}

		function vencimientoSistema(){
			include 'db.php';
			date_default_timezone_set('America/Mazatlan');//!cambio de zona horaria
			setlocale(LC_ALL,"es_ES");
			$fechaH=date('Y-m-d');
			$sql="SELECT * FROM configuracion_paquete WHERE 1 = 1 ";
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc(); 
			$fecha_demo=$row['fecha_demo'];
			//$fechaH="2018-12-01";
			$datetime1 = new DateTime($fecha_demo);
			$datetime2 = new DateTime($fechaH);
			$interval = $datetime1->diff($datetime2);
			$dias=$interval->format('%R%a');
			$dias_sin=$interval->format('%a');
			$datos = array(
					'mensaje' => "" ,
					'status_div' => 'hidden="hidden" ',
					'status_home' => '',
					'dias' => $dias_sin,
				);
			if($dias >=-3 || $dias ==0){
				$datos = array(
					'mensaje' => "En {$dias_sin} Días Vence el Sistema" ,
					'status_div' => '',
					'status_home' => '',
					'dias' => $dias_sin,
				);
			}
			if($dias >0){
				$datos = array(
					'mensaje' => "Estimado Cliente:<br>Ha finalizado la suscripción al sistema SADA asociada a su agencia de viajes.<br> No podrá ingresar al sistema hasta que realice el pago correspondiente.<br>Si usted ya realizo el pago correspondiente favor de comunicarse con nosotros o enviar su recibo de pago.<br><br><br> ATTE:<br> SADA. Sistema Administrador de Agencias de Viajes" ,
					'status_div' => '',
					'status_home' => 'hidden="hidden" ',
					'dias' => $dias_sin,
				);
			}
			$conexion->close();
			return $datos;
		}
?>