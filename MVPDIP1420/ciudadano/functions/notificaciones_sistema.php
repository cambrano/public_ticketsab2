<?php
		function notificaciones_sistema(){
			include 'db.php'; 
			$sql=("SELECT * FROM notificaciones_sistema WHERE codigo_plataforma='$codigo_plataforma'");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row;
			$conexion->close();
			return $datos;
		}


		function notificaciones_sistemaDatos(){
			date_default_timezone_set('America/Mazatlan');//!cambio de zona horaria
			setlocale(LC_ALL,"es_ES"); 
			$fechaSF=date('Y-m-d');
			$fechaSFMD=date('m-d');
			$fecha = $fechaSF;
			include 'db.php';
			$sql=("SELECT * FROM notificaciones_sistema WHERE codigo_plataforma='$codigo_plataforma'");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row;
			if($datos!=null){
				$fecha_nacimiento_dias=$row['fecha_nacimiento_dias'];
				$fecha_nacimiento_intervalo=$row['fecha_nacimiento_intervalo'];
				$fecha_nacimiento_status=$row['fecha_nacimiento_status'];
				if($fecha_nacimiento_intervalo=="despues"){
					$intervalo="-";
					$nuevafecha = strtotime ( $intervalo.$fecha_nacimiento_dias.' day' , strtotime ( $fecha ) ) ;
					$array['fecha_nacimiento_inicio'] = date ( 'm-d' , $nuevafecha );
					$array['fecha_nacimiento_final'] = $fechaSFMD;
					$array['fecha_nacimiento_status'] = $fecha_nacimiento_status;
				}else{
					$intervalo="+";
					$nuevafecha = strtotime ( $intervalo.$fecha_nacimiento_dias.' day' , strtotime ( $fecha ) ) ;
					$array['fecha_nacimiento_inicio'] = $fechaSFMD;
					$array['fecha_nacimiento_final'] = date ( 'm-d' , $nuevafecha );
					$array['fecha_nacimiento_status'] = $fecha_nacimiento_status;
				}
				if($fecha_nacimiento_status =="" && $fecha_nacimiento_dias =="" ){
					$array['fecha_nacimiento_inicio'] = "";
					$array['fecha_nacimiento_final'] = "";
					$array['cliente_retorno_status'] = "";
				}


				$cliente_retorno_dias=$row['cliente_retorno_dias'];
				$cliente_retorno_intervalo=$row['cliente_retorno_intervalo'];
				$cliente_retorno_status=$row['cliente_retorno_status'];
				if($cliente_retorno_intervalo!="despues"){
					$intervalo="-";
					$nuevafecha = strtotime ( $intervalo.$cliente_retorno_dias.' day' , strtotime ( $fecha ) ) ;
					$array['cliente_retorno_inicio'] = date ( 'Y-m-d' , $nuevafecha );
					$array['cliente_retorno_final'] = $fechaSF;
					$array['cliente_retorno_status'] = $cliente_retorno_status;
				}else{
					$intervalo="+";
					$nuevafecha = strtotime ( $intervalo.$cliente_retorno_dias.' day' , strtotime ( $fecha ) ) ;
					$array['cliente_retorno_inicio'] = $fechaSF;
					$array['cliente_retorno_final'] = date ( 'Y-m-d' , $nuevafecha );
					$array['cliente_retorno_status'] = $cliente_retorno_status;
				}
				if($cliente_retorno_status =="" && $cliente_retorno_dias =="" ){
					$array['cliente_retorno_inicio'] = "";
					$array['cliente_retorno_final'] = "";
					$array['cliente_retorno_status'] = "";
				}

				$cliente_llegada_dias=$row['cliente_llegada_dias'];
				$cliente_llegada_intervalo=$row['cliente_llegada_intervalo'];
				$cliente_llegada_status=$row['cliente_llegada_status'];
				if($cliente_llegada_intervalo!="despues"){
					$intervalo="-";
					$nuevafecha = strtotime ( $intervalo.$cliente_llegada_dias.' day' , strtotime ( $fecha ) ) ;
					$array['cliente_llegada_inicio'] = date ( 'Y-m-d' , $nuevafecha );
					$array['cliente_llegada_final'] = $fechaSF;
					$array['cliente_llegada_status'] = $cliente_llegada_status;
				}else{
					$intervalo="+";
					$nuevafecha = strtotime ( $intervalo.$cliente_llegada_dias.' day' , strtotime ( $fecha ) ) ;
					$array['cliente_llegada_inicio'] = $fechaSF;
					$array['cliente_llegada_final'] = date ( 'Y-m-d' , $nuevafecha );
					$array['cliente_llegada_status'] = $cliente_llegada_status;
				}
				if($cliente_llegada_status =="" && $cliente_llegada_dias =="" ){
					$array['cliente_llegada_inicio'] = "";
					$array['cliente_llegada_final'] = "";
					$array['cliente_llegada_status'] = "";
				}


				$pago_reserva_dias=$row['pago_reserva_dias'];
				$pago_reserva_intervalo=$row['pago_reserva_intervalo'];
				$pago_reserva_status=$row['pago_reserva_status'];
				if($pago_reserva_intervalo=="despues"){
					$intervalo="-";
					$nuevafecha = strtotime ( $intervalo.$pago_reserva_dias.' day' , strtotime ( $fecha ) ) ;
					$array['pago_reserva_inicio'] = date ( 'Y-m-d' , $nuevafecha );
					$array['pago_reserva_final'] = $fechaSF;
					$array['pago_reserva_status'] = $pago_reserva_status;
				}else{
					$intervalo="+";
					$nuevafecha = strtotime ( $intervalo.$pago_reserva_dias.' day' , strtotime ( $fecha ) ) ;
					$array['pago_reserva_inicio'] = $fechaSF;
					$array['pago_reserva_final'] = date ( 'Y-m-d' , $nuevafecha );
					$array['pago_reserva_status'] = $pago_reserva_status;
				}
				if($pago_reserva_status =="" && $pago_reserva_dias =="" ){
					$array['pago_reserva_inicio'] = "";
					$array['pago_reserva_final'] = "";
					$array['pago_reserva_status'] = "";
				}
			}else{
				$array=null;
			}
			$conexion->close();
			return $array;
		}

		function notificaciones_web_mensajes($maximo=null,$status=null){
			include 'db.php';
			$sql="SELECT count(*) alertas FROM website_mensajes WHERE codigo_plataforma='$codigo_plataforma'";
			if($status!=""){
				$sql .= " AND status='{$status}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row['alertas'];
			if($maximo < $datos ){
				$datos = $maximo."+";
			}
			$conexion->close();
			return $datos;
		}
		function notificaciones_productos_comentarios($maximo=null,$status=null){
			include 'db.php';
			$sql="SELECT count(*) alertas FROM productos_comentarios WHERE codigo_plataforma='$codigo_plataforma'";
			if($status!=""){
				$sql .= " AND status='{$status}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row['alertas'];
			if($maximo < $datos ){
				$datos = $maximo."+";
			}
			$conexion->close();
			return $datos;
		}

		function notificaciones_hoteles_comentarios($maximo=null,$status=null){
			include 'db.php';
			$sql="SELECT count(*) alertas FROM hoteles_comentarios WHERE codigo_plataforma='$codigo_plataforma'";
			if($status!=""){
				$sql .= " AND status='{$status}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row['alertas'];
			if($maximo < $datos ){
				$datos = $maximo."+";
			}
			$conexion->close();
			return $datos;
		}
		function notificaciones_reservas($maximo=null,$status=null){
			include 'db.php';
			$sql="SELECT count(*) alertas FROM reservas WHERE codigo_plataforma='$codigo_plataforma'";
			if($status!=""){
				$sql .= " AND leido='{$status}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row['alertas'];
			if($maximo < $datos ){
				$datos = $maximo."+";
			}
			$conexion->close();
			return $datos;
		}
?>