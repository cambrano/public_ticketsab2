<?php
		$raiz= "/home/cambrano/public_html/".$valueProduccion['folderProducto']."/".$valueProduccion['folder']."/admin/functions/";
		$raizMail= "/home/cambrano/public_html/".$valueProduccion['folderProducto']."/".$valueProduccion['folder']."/admin/commands/";
		$folderProduccion=$valueProduccion['folder'];
		$folderProducto=$valueProduccion['folderProducto'];
		$codigo_produccion=$valueProduccion['codigo_produccion'];


		unset($datos);
		unset($array);
		unset($notificaciones_sistemaDatos);
		unset($datosClientes);
		unset($entasDatos);
		unset($ventasDatosRetornos);
		unset($configuracionDatos);
		unset($usuariosNotificaciones);

		//include $raiz."error.php"; 
		//include $raiz."timemex.php";
		include $raiz."db.php";
		$sql=("SELECT notificaciones_sistema cantidadPaquete FROM configuracion_paquete WHERE codigo_plataforma='{$codigo_plataforma}'  ");
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_array();

		if($row['cantidadPaquete']=='SI'){
			$paquetesSistema = true;
		}else{
			$paquetesSistema = false;
		}

		if($paquetesSistema==true){
			//checamos las notificaciones
			//$notificaciones_sistemaDatos=notificaciones_sistemaDatos();
			date_default_timezone_set('America/Mazatlan');//!cambio de zona horaria
			setlocale(LC_ALL,"es_ES"); 
			$fechaSF=date('Y-m-d');
			$fechaSFMD=date('m-d');
			$fecha = $fechaSF;

			$sql=("SELECT * FROM notificaciones_sistema WHERE codigo_plataforma='$codigo_plataforma'");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_array();
			foreach($row as $key => $value){
				if(is_numeric($key)) unset($row[$key]);
			}
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
			$notificaciones_sistemaDatos=$array;
			if($notificaciones_sistemaDatos != null){
				$fecha_inicial=$notificaciones_sistemaDatos['fecha_nacimiento_inicio'];
				$fecha_final=$notificaciones_sistemaDatos['fecha_nacimiento_final'];
				$fecha_nacimiento_status=$notificaciones_sistemaDatos['fecha_nacimiento_status'];
				$table_nacimiento="";
				//$clientesDatosNacimientos=clientesDatosNacimientos('',$fecha_inicial,$fecha_final);
				$sql="SELECT * FROM clientes WHERE  codigo_plataforma='{$codigo_plataforma}'  ";
				if($id_cliente!=""){
					$sql.=" AND id='{$id_cliente}'";
				}
				if($fecha_inicial!="" && $fecha_final=="" ){
					$sql.=" AND  DATE_FORMAT(fecha_nacimiento, '%m-%d') >= '{$fecha_inicial}'";
				}
				if($fecha_inicial=="" && $fecha_final!="" ){
					$sql.=" AND  DATE_FORMAT(fecha_nacimiento, '%m-%d') <= '{$fecha_final}'";
				}
				if($fecha_inicial!="" && $fecha_final!="" ){
					$sql.=" AND  DATE_FORMAT(fecha_nacimiento, '%m-%d') BETWEEN '{$fecha_inicial}' AND '{$fecha_final}' ";
				}
				$resultado = $conexion->query($sql);
				$result = $conexion->query($sql); 
				$num=0; 
				while($row=$result->fetch_array()){
					foreach($row as $key => $value){
						if(is_numeric($key)) unset($row[$key]);
					}
					$datosClientes[$num]=$row;
					$datosClientes[$num]['nombre_cliente']=$row['nombre']." ".$row['apellido_paterno']." ".$row['apellido_materno'];
					$num=$num+1;
				}
				if($num==0){
					$datosClientes=null;
				}

				if($fecha_nacimiento_status==1 && !empty($datosClientes)){
					$sendNacimientosClientes=1;
					$table_nacimientos='
						<table border="1" style="width: 100%; font-size: 8px" cellpadding="0" cellspacing="0" >
							<thead>
								<tr>
									<th style="font-size: 8px; background-color:#578EBE; color: white;border: 0.8px solid black;padding: 8px" colspan="5">Cumpleaños Proximos</th> 
								</tr>
								<tr>
									<th style="font-size: 8px; background-color:#578EBE; color: white;border: 0.8px solid black;padding: 8px">Clave</th>
									<th style="font-size: 8px; background-color:#578EBE; color: white;border: 0.8px solid black;padding: 8px">Cliente</th>
									<th style="font-size: 8px; background-color:#578EBE; color: white;border: 0.8px solid black;padding: 8px">Celular</th>
									<th style="font-size: 8px; background-color:#578EBE; color: white;border: 0.8px solid black;padding: 8px">Whatsapp</th>
									<th style="font-size: 8px; background-color:#578EBE; color: white;border: 0.8px solid black;padding: 8px">Fecha</th>
								</tr>
							</thead>
					';
					foreach ($datosClientes as $key => $value) {
						$table_nacimientos.= "<tr>";
						$table_nacimientos.= '<td style="font-size: 8px; background-color:white; color: black;border: 0.8px solid black;padding: 3px 8px 3px 8px">'.$value['clave'].'</td>';
						$table_nacimientos.= '<td style="font-size: 8px; background-color:white; color: black;border: 0.8px solid black;padding: 3px 8px 3px 8px">'.$value['nombre_cliente'].'</td>';
						if($value["celular"]!=""){
							$celular='<a href="tel:'.$value["celular"].'">'.$value["celular"].'</a>';
						}else{
							$celular ="No tiene";
						}
						$table_nacimientos.= '<td style="font-size: 8px; background-color:white; color: black;border: 0.8px solid black;padding: 3px 8px 3px 8px">'.$celular.'</td>';
						if($value["whatsapp"]!=""){
							$whatsapp ="<a href='https://wa.me/521".$value["whatsapp"]."' target='_blank'>".$value["whatsapp"]."</a>";
						}else{
							$whatsapp ="No tiene";
						}
						$table_nacimientos.= '<td style="font-size: 8px; background-color:white; color: black;border: 0.8px solid black;padding: 3px 8px 3px 8px">'.$whatsapp.'</td>';
						$table_nacimientos.= '<td style="font-size: 8px; background-color:white; color: black;border: 0.8px solid black;padding: 3px 8px 3px 8px">'.$value['fecha_nacimiento'].'</td>';
						$table_nacimientos.= "</tr>";
					}
					$table_nacimientos.= "</table>";
				}

				$fecha_inicial=$notificaciones_sistemaDatos['pago_reserva_inicio'];
				$fecha_final=$notificaciones_sistemaDatos['pago_reserva_final'];
				$pago_reserva_status=$notificaciones_sistemaDatos['pago_reserva_status'];
				//$ventasDatos=ventasDatos('','','',$fecha_inicial,$fecha_final,1,'');
				$id_venta=null;
				$id_cliente=null;
				$id_sucursal=null;
				$fecha1=$fecha_inicial;
				$fecha2=$fecha_final;
				$tipo=1;
				$id_grupo=null;
				if($fecha2==""){
					date_default_timezone_set('America/Mazatlan');//!cambio de zona horaria
					setlocale(LC_ALL,"es_ES");
					$fecha2 = date('Y-m-d');
				}

				$sql="SELECT 
				*,
				(SELECT g.clave FROM grupos g WHERE g.id=vt.id_grupo) clave_grupo,
				(SELECT CONCAT_WS(' ',c.nombre,c.apellido_paterno,c.apellido_materno)  FROM clientes c  WHERE c.id=vt.id_cliente) nombre_cliente,
				(SELECT c.celular  FROM clientes c  WHERE c.id=vt.id_cliente) celular,
				(SELECT c.whatsapp  FROM clientes c  WHERE c.id=vt.id_cliente) whatsapp,
				(SELECT IFNULL(SUM(pc.monto),0) FROM pagos_clientes pc WHERE pc.id_venta=vt.id) sumPagoCliente,
				vt.monto_publico_total-(SELECT IFNULL(SUM(pc.monto),0) FROM pagos_clientes pc WHERE pc.id_venta=vt.id) sumRestanteCliente,
				DATEDIFF(vt.fecha_llegada,'{$fecha1}') diasReserva,
				DATEDIFF(vt.fecha_salida,'{$fecha1}') diasRetorno
				FROM ventas vt WHERE 1 AND codigo_plataforma='{$codigo_plataforma}'";
				if($id_venta !=""){
					$sql .=" AND vt.id = '{$id_venta}' ";
				}
				if($id_cliente !=""){
					$sql .=" AND vt.id_cliente = '{$id_cliente}' ";
				}
				if($id_sucursal !=""){
					$sql .=" AND vt.id_sucursal = '{$id_sucursal}' ";
				}

				if($tipo ==1){
					$sql .=" AND vt.fecha_llegada BETWEEN '{$fecha1}' AND '{$fecha2}'  ORDER BY vt.fecha_llegada ASC ";
				}

				if($tipo ==2){
					$sql .=" AND vt.fecha_salida BETWEEN '{$fecha1}' AND '{$fecha2}'  ORDER BY vt.fecha_salida ASC ";
				}

				if($id_grupo!=""){
					$sql .=" AND vt.id_grupo = '{$id_grupo}' ";
				}

				//id_venta='{$id_venta}' AND fecha_hora <='{$fecha_hora}'";
				$result = $conexion->query($sql); 
				$num=0; 
				while($row=$result->fetch_array()){
					foreach($row as $key => $value){
						if(is_numeric($key)) unset($row[$key]);
					}
					$ventasDatos[$num]=$row;
					$num=$num+1;
				}
				if($num==0){
					$ventasDatos=null;
				}
				if($pago_reserva_status==1 && !empty($ventasDatos)){
					$sendProxViajesClientes=1;
					$table_reservas='
						<table border="1" style="width: 100%; font-size: 8px" cellpadding="0" cellspacing="0" >
							<thead>
								<tr>
									<th style="font-size: 8px; background-color:#578EBE; color: white;border: 0.8px solid black;padding: 8px" colspan="8">Reservas Proximas</th> 
								</tr>
								<tr>
									<th style="font-size: 8px; background-color:#578EBE; color: white;border: 0.8px solid black;padding: 8px">Venta</th>
									<th style="font-size: 8px; background-color:#578EBE; color: white;border: 0.8px solid black;padding: 8px">Grupo</th>
									<th style="font-size: 8px; background-color:#578EBE; color: white;border: 0.8px solid black;padding: 8px">Cliente</th>
									<th style="font-size: 8px; background-color:#578EBE; color: white;border: 0.8px solid black;padding: 8px">Celular</th>
									<th style="font-size: 8px; background-color:#578EBE; color: white;border: 0.8px solid black;padding: 8px">Whatsapp</th>
									<th style="font-size: 8px; background-color:#578EBE; color: white;border: 0.8px solid black;padding: 8px">Deuda</th>
									<th style="font-size: 8px; background-color:#578EBE; color: white;border: 0.8px solid black;padding: 8px">Dias</th>
									<th style="font-size: 8px; background-color:#578EBE; color: white;border: 0.8px solid black;padding: 8px">Fecha Reserva</th>
								</tr>
							</thead>
					';
					foreach ($ventasDatos as $key => $value) {
						$table_reservas.= "<tr>";
						$table_reservas.= '<td style="font-size: 8px; background-color:white; color: black;border: 0.8px solid black;padding: 3px 8px 3px 8px">'.$value['clave'].'</td>';
						$table_reservas.= '<td style="font-size: 8px; background-color:white; color: black;border: 0.8px solid black;padding: 3px 8px 3px 8px">'.$value['clave_grupo'].'</td>';
						$table_reservas.= '<td style="font-size: 8px; background-color:white; color: black;border: 0.8px solid black;padding: 3px 8px 3px 8px">'.$value['nombre_cliente'].'</td>';
						if($value["celular"]!=""){
							$celular='<a href="tel:'.$value["celular"].'">'.$value["celular"].'</a>';
						}else{
							$celular ="No tiene";
						}
						$table_reservas.= '<td style="font-size: 8px; background-color:white; color: black;border: 0.8px solid black;padding: 3px 8px 3px 8px">'.$celular.'</td>';
						if($value["whatsapp"]!=""){
							$whatsapp ="<a href='https://wa.me/521".$value["whatsapp"]."' target='_blank'>".$value["whatsapp"]."</a>";
						}else{
							$whatsapp ="No tiene";
						}
						$table_reservas.= '<td style="font-size: 8px; background-color:white; color: black;border: 0.8px solid black;padding: 3px 8px 3px 8px">'.$whatsapp.'</td>';
						$table_reservas.= '<td style="font-size: 8px; background-color:white; color: black;border: 0.8px solid black;padding: 3px 8px 3px 8px">$ '.number_format($value['sumRestanteCliente'], 2, '.', ',').'</td>';
						$table_reservas.= '<td style="font-size: 8px; background-color:white; color: black;border: 0.8px solid black;padding: 3px 8px 3px 8px">'.$value['diasReserva'].'</td>';
						$table_reservas.= '<td style="font-size: 8px; background-color:white; color: black;border: 0.8px solid black;padding: 3px 8px 3px 8px">'.$value['fecha_llegada'].'</td>';
						$table_reservas.= "</tr>";
					}
					$table_reservas.= "</table>";
				}


				$fecha_inicial=$notificaciones_sistemaDatos['cliente_retorno_inicio'];
				$fecha_final=$notificaciones_sistemaDatos['cliente_retorno_final'];
				$cliente_retorno_status=$notificaciones_sistemaDatos['cliente_retorno_status'];
				//$ventasDatosRetornos=ventasDatos('','','',$fecha_inicial,$fecha_final,2,'');
				$id_venta=null;
				$id_cliente=null;
				$id_sucursal=null;
				$fecha1=$fecha_inicial;
				$fecha2=$fecha_final;
				$tipo=2;
				$id_grupo=null;
				if($fecha2==""){
					date_default_timezone_set('America/Mazatlan');//!cambio de zona horaria
					setlocale(LC_ALL,"es_ES");
					$fecha2 = date('Y-m-d');
				}
				$sql="SELECT 
				*,
				(SELECT g.clave FROM grupos g WHERE g.id=vt.id_grupo) clave_grupo,
				(SELECT CONCAT_WS(' ',c.nombre,c.apellido_paterno,c.apellido_materno)  FROM clientes c  WHERE c.id=vt.id_cliente) nombre_cliente,
				(SELECT c.celular  FROM clientes c  WHERE c.id=vt.id_cliente) celular,
				(SELECT c.whatsapp  FROM clientes c  WHERE c.id=vt.id_cliente) whatsapp,
				(SELECT IFNULL(SUM(pc.monto),0) FROM pagos_clientes pc WHERE pc.id_venta=vt.id) sumPagoCliente,
				vt.monto_publico_total-(SELECT IFNULL(SUM(pc.monto),0) FROM pagos_clientes pc WHERE pc.id_venta=vt.id) sumRestanteCliente,
				DATEDIFF(vt.fecha_llegada,'{$fecha1}') diasReserva,
				DATEDIFF(vt.fecha_salida,'{$fecha1}') diasRetorno
				FROM ventas vt WHERE 1 AND codigo_plataforma='{$codigo_plataforma}'";
				if($id_venta !=""){
					$sql .=" AND vt.id = '{$id_venta}' ";
				}
				if($id_cliente !=""){
					$sql .=" AND vt.id_cliente = '{$id_cliente}' ";
				}
				if($id_sucursal !=""){
					$sql .=" AND vt.id_sucursal = '{$id_sucursal}' ";
				}

				if($tipo ==1){
					$sql .=" AND vt.fecha_llegada BETWEEN '{$fecha1}' AND '{$fecha2}'  ORDER BY vt.fecha_llegada ASC ";
				}

				if($tipo ==2){
					$sql .=" AND vt.fecha_salida BETWEEN '{$fecha1}' AND '{$fecha2}'  ORDER BY vt.fecha_salida ASC ";
				}

				if($id_grupo!=""){
					$sql .=" AND vt.id_grupo = '{$id_grupo}' ";
				}

				$sql;
				//id_venta='{$id_venta}' AND fecha_hora <='{$fecha_hora}'";
				$result = $conexion->query($sql); 
				$num=0; 
				while($row=$result->fetch_array()){
					foreach($row as $key => $value){
						if(is_numeric($key)) unset($row[$key]);
					}
					$ventasDatosRetornos[$num]=$row;
					$num=$num+1;
				}
				if($num==0){
					$ventasDatosRetornos=null;
				}

				if($cliente_retorno_status==1 && !empty($ventasDatosRetornos)){
					$sendRetornosClientes=1;
					$table_retornos='
						<table border="1" style="width: 100%; font-size: 8px" cellpadding="0" cellspacing="0" >
							<thead>
								<tr>
									<th style="font-size: 8px; background-color:#578EBE; color: white;border: 0.8px solid black;padding: 8px" colspan="8">Retornos Reservas </th> 
								</tr>
								<tr>
									<th style="font-size: 8px; background-color:#578EBE; color: white;border: 0.8px solid black;padding: 8px">Venta</th>
									<th style="font-size: 8px; background-color:#578EBE; color: white;border: 0.8px solid black;padding: 8px">Grupo</th>
									<th style="font-size: 8px; background-color:#578EBE; color: white;border: 0.8px solid black;padding: 8px">Cliente</th>
									<th style="font-size: 8px; background-color:#578EBE; color: white;border: 0.8px solid black;padding: 8px">Celular</th>
									<th style="font-size: 8px; background-color:#578EBE; color: white;border: 0.8px solid black;padding: 8px">Whatsapp</th>
									<th style="font-size: 8px; background-color:#578EBE; color: white;border: 0.8px solid black;padding: 8px">Deuda</th>
									<th style="font-size: 8px; background-color:#578EBE; color: white;border: 0.8px solid black;padding: 8px">Dias</th>
									<th style="font-size: 8px; background-color:#578EBE; color: white;border: 0.8px solid black;padding: 8px">Fecha Retorno</th>
								</tr>
							</thead>
					';
					foreach ($ventasDatosRetornos as $key => $value) {
						$table_retornos.= "<tr>";
						$table_retornos.= '<td style="font-size: 8px; background-color:white; color: black;border: 0.8px solid black;padding: 3px 8px 3px 8px">'.$value['clave'].'</td>';
						$table_retornos.= '<td style="font-size: 8px; background-color:white; color: black;border: 0.8px solid black;padding: 3px 8px 3px 8px">'.$value['clave_grupo'].'</td>';
						$table_retornos.= '<td style="font-size: 8px; background-color:white; color: black;border: 0.8px solid black;padding: 3px 8px 3px 8px">'.$value['nombre_cliente'].'</td>';
						if($value["celular"]!=""){
							$celular='<a href="tel:'.$value["celular"].'">'.$value["celular"].'</a>';
						}else{
							$celular ="No tiene";
						}
						$table_retornos.= '<td style="font-size: 8px; background-color:white; color: black;border: 0.8px solid black;padding: 3px 8px 3px 8px">'.$celular.'</td>';
						if($value["whatsapp"]!=""){
							$whatsapp ="<a href='https://wa.me/521".$value["whatsapp"]."' target='_blank'>".$value["whatsapp"]."</a>";
						}else{
							$whatsapp ="No tiene";
						}
						$table_retornos.= '<td style="font-size: 8px; background-color:white; color: black;border: 0.8px solid black;padding: 3px 8px 3px 8px">'.$whatsapp.'</td>';
						$table_retornos.= '<td style="font-size: 8px; background-color:white; color: black;border: 0.8px solid black;padding: 3px 8px 3px 8px">$ '.number_format($value['sumRestanteCliente'], 2, '.', ',').'</td>';
						$table_retornos.= '<td style="font-size: 8px; background-color:white; color: black;border: 0.8px solid black;padding: 3px 8px 3px 8px">'.$value['diasRetorno'].'</td>';
						$table_retornos.= '<td style="font-size: 8px; background-color:white; color: black;border: 0.8px solid black;padding: 3px 8px 3px 8px">'.$value['fecha_salida'].'</td>';
						$table_retornos.= "</tr>";
					}
					$table_retornos.= "</table>";
				}

				
				 

				$sql=("SELECT * FROM configuracion WHERE codigo_plataforma='{$codigo_plataforma}'");
				$resultado = $conexion->query($sql);
				$row=$resultado->fetch_array(); 
				foreach($row as $key => $value){
					if(is_numeric($key)) unset($row[$key]);
				}
				$denominacion=$row['nombre']; 
				$logo=$row['logo'];
				$configuracionDatos=$row;
				if($denominacion==""){
					$denominacion="Ingrese el Nombre";
				}
				$configuracionDatos['nombre']=$denominacion;
				$configuracionDatos['logo']=$logo;
				$configuracionDatos['id']=$row['id'];

				$generales = array(
					"[__Fecha_Hoy__]" => $fechaSF , 
				);

				$empresa = array(
					"[__Empresa_Nombre__]" => $configuracionDatos['nombre'], 
					"[__Empresa_Logo__]" => $photo, 
					"[__Empresa_Logo_Code__]" => $kad_photo, 
				); 

				$notificaciones_sistema = array(
					"[__Tabla_Nacimientos__]" => $table_nacimientos,
					"[__Salto_Linea__]" => "<br>",
					"[__Tabla_Pagos_Reservas__]" => $table_reservas,
					"[__Tabla_Cliente_Retorno__]" => $table_retornos, 
					"[__Tabla_Dia_Reservas__]" => $table_reservas_dia, 
					
				); 

				include $raizMail."plantillas/email.php";
				$bodyHTML=strtr($mensaje, array_merge($generales,$empresa,$notificaciones_sistema));
				$sendNacimientosClientes;
				$sendProxViajesClientes;
				$sendRetornosClientes;

				$sql=" 
					SELECT * 
					FROM empleados e 
					WHERE e.notificaciones_sistema =1
					AND 
					e.codigo_plataforma='{$codigo_plataforma}'
					AND 
					EXISTS
					(SELECT * FROM usuarios u WHERE u.id_perfil_usuario IN (2,3) AND u.codigo_plataforma='{$codigo_plataforma}') ";
				$result = $conexion->query($sql);
				$num=0;
				while($row=$result->fetch_array()){
					foreach($row as $key => $value){
						if(is_numeric($key)) unset($row[$key]);
						$usuariosNotificaciones[$num]=$row;
					}
					$num=$num+1;
				}
				if($sendNacimientosClientes==1 || $sendProxViajesClientes==1 || $sendDiaViajesClientes==1 || $sendRetornosClientes==1 && !empty($usuariosNotificaciones) && $correoSistema['status']==1 ){
					//enviamos los correos
					//$correoSistema=correoSistema();
					$sql=("SELECT * FROM correo_sistema WHERE codigo_plataforma='$codigo_plataforma'");
					$resultado = $conexion->query($sql);
					$row=$resultado->fetch_array();

					$correoSistema['servidor']=$row['servidor'];
					$correoSistema['puerto']=$row['puerto'];
					$correoSistema['cifrado']=$row['cifrado'];
					$correoSistema['usuario']=$row['usuario'];
					$correoSistema['password']=$row['password']; 
					$correoSistema['status']=$row['status']; 
					$mensajeCorreo="";
					foreach ($usuariosNotificaciones as $key => $value) { 
						if(correoVerificacion($value['correo_electronico'])){
							$asunto="Notificciones del Sistema Mi Agencia de Viajes";
							$de="Mi Sistema Agencia de Viajes";
							//$value['correo_electronico']="cambrano@gmail.com";
							//$correoEnvio=correoEnvio($correoSistema,$bodyHTML,$asunto,$de,$value['correo_electronico']);
							$datos=$correoSistema;
							$mensaje=$bodyHTML;
							$asunto=$asunto;
							$de=$de;
							$para=$value['correo_electronico'];
							//$para= "cambranoy@gmail.com";
							$archivo=null;

							$fechaH=date('Y-m-d H:i:s');
							$servidor=$datos['servidor'];
							$puerto=$datos['puerto'];
							$cifrado=$datos['cifrado'];
							$usuario=$datos['usuario'];
							$password=$datos['password'];
							$mensaje=$mensaje;
							$asunto=$asunto;

							// Godaddy
							/*
							$servidor="p3plcpnl0942.prod.phx3.secureserver.net";
							$puerto=465;
							$cifrado="ssl";
							$usuario="miagenciadeviajes@creesuniendoatabasco.org.mx";
							$password="a07080444c";
							*/

							//$para="cambrano@gmail.com";


							//Librerías para el envío de mail
							//Recibir todos los parámetros del formulario
							//Este bloque es importante
							//convierte en UTF-8 el subject
							$asunto='=?UTF-8?B?'.base64_encode($asunto).'?=';
							$mail = new PHPMailer();
							$mail->IsSMTP();
							$mail->Host = $servidor;
							$mail->Port = $puerto;
							$mail->SMTPAuth = true;
							$mail->SMTPSecure = $cifrado;
							$mail->SMTPDebug = 0; 
						    $mail->SMTPAutoTLS = false; 
							
							//Nuestra cuenta
							//$usuario="miagenciadeviajes@creesuniendoatabasco.org.mx";
							$mail->Username =$usuario;
							$mail->Password = $password;
							$mail->FromName = $de;
							$mail->From = $usuario; 
							$mail->AddReplyTo($datos['usuario'], 'SADA - Sistema Adminsitrador de Agencias de viajes');
							//Agregar destinatario
							$mail->AddAddress($para);
							//$mail->AddBCC("@financialgroup.mx",'Ticket Copia');
							//$mail->AddBCC("@financialgroup.mx",'Ticket Copia');
							$mail->Subject = $asunto."-_-".$fechaH;
							$mail->Body = $mensaje;
							//Para adjuntar archivo
							///$mail->AddAttachment($archivo['tmp_name'], $archivo['name']);
							$mail->MsgHTML($mensaje); 
							$mail->CharSet = 'UTF-8';
							$mail->IsHTML(true);
							if($archivo != ""){
								$mail->addStringAttachment($archivo['file'],$archivo['nombre'], $encoding = $archivo['encoding'], $type = $archivo['type']);
							}

							//Avisar si fue enviado o no y dirigir al index
							if($mail->Send()){
								$mensajeCorreo .= "/Enviado - ".$para;
							}else{ 
								$mensajeCorreo .= "/NO Enviado - ".$para;
							}
						}
					}
				}

				$to = "logs_sistemas@softwaresada.com";
				$subject = "Log Notificaciones SADA / Fecha: ".$fechaH." / Produccion: ".$folderProduccion." / Código Produccion: ".$codigo_produccion;
				$txt ="Log Notificaciones"."\r\n"." Fecha: ".$fechaH."\r\n\r\n"."Folders"."\r\n"."Producto: ".$folderProducto."\r\n"."Produccion: ".$folderProduccion."\r\n"."Código Produccion: ".$codigo_produccion."\r\n\r\n".$mensajeCorreo;
				$headers = "From: logs_sistemas@softwaresada.com";
				mail($to,$subject,$txt,$headers);

				//se envia al correo que registraron
				//$to = $usuario;
				$to = "cambranoy@gmail.com";
				$subject = "Log Notificaciones SADA / Fecha: ".$fechaH." / Produccion: ".$folderProduccion." / Código Produccion: ".$codigo_produccion;
				$txt ="Log Notificaciones"."\r\n"." Fecha: ".$fechaH."\r\n\r\n"."Folders"."\r\n"."Producto: ".$folderProducto."\r\n"."Produccion: ".$folderProduccion."\r\n"."Código Produccion: ".$codigo_produccion."\r\n\r\n".$mensajeCorreo;
				$headers = "From: logs_sistemas@softwaresada.com";
				mail($to,$subject,$txt,$headers);

			}


		}