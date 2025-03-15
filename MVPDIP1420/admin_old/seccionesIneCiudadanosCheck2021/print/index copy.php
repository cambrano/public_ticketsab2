<?php
	@session_start(); 
	include "../../functions/security.php";
	include "../../functions/sucursales.php";
	include "../../functions/localidades.php";
	include "../../functions/estados.php";
	include "../../functions/ventas.php";
	include "../../functions/configuracion.php";
	include "../../functions/timemex.php";
	include "../../functions/clientes.php";
	include "../../functions/ventas_pasajeros.php";
	include "../../functions/ventas_productos.php";
	include "../../functions/bancos.php";
	include "../../functions/avisos_privacidad.php";
	
	 

	$pagina_inicio = file_get_contents('../plantillas/datosVenta.php');
	
	$id_venta=$_GET['cot'];
	$pageService=$_GET['cot2'];
	if($pageService==""){
		?>
		<script type="text/javascript">
			window.close();
		</script>
		<?php
	}else{
		$_SESSION['pageService'];
	}
	
	//$id_venta=115;

	$configuracionDatos=configuracionDatos();
	$ventaDatos=ventaDatos($id_venta);
	$sucursalDatos=sucursalDatos($ventaDatos['id_sucursal']);
	$clienteDatos=clienteDatos($ventaDatos['id_cliente']);
	$venta_pasajerosDatosArray=venta_pasajerosDatosArray($id_venta);
	$venta_productosDatosArray=venta_productosDatosArray($id_venta);
	
	if($_SESSION['pageService'] != $pageService){
		?>
		<script type="text/javascript">
			window.close();
		</script>
		<?php
	}


	$css = array(
		"[_Uppercase_]" => "text-transform: uppercase;",
		//"[_Uppercase_]" => "",
	);

	$impresion = array(
		"[__Impresion_Fecha_Hora__]" => $fechaH , 
	);


	if($configuracionDatos['logo']==""){
		$img_logo='<img src="../../configuracion/img/logo.png" height="90px" >';
	}else{
		$img_logo='<img src="../../configuracion/img/logo_over.png" height="90px" >';
	}
	$empresa = array(
		"[__Empresa_Nombre__]" => $configuracionDatos['nombre'], 
		"[__Empresa_Slogan__]" => $configuracionDatos['slogan']."<br><br>",
		"[__Empresa_Registro_Nacional_Turismo__]" => "<b>Registro Nacional De Turismo:</b><br>".$configuracionDatos['registro_nacional_turismo']."<br>",
		"[__Empresa_Logo__]" => $img_logo,
	);
	$sucursales = array(
		"[__Sucursal_Nombre__]" => $sucursalDatos['nombre'],
		"[__Sucursal_Direccion__]" => $sucursalDatos['direccion'],
		"[__Sucursal_Codigo_Postal__]" => $sucursalDatos['codigo_postal'],
		"[__Sucursal_Localidad__]" => localidadNombre($sucursalDatos['id_localidad']),
		"[__Sucursal_Estado__]" => estadoNombre($sucursalDatos['id_estado']),
		"[__Sucursal_Telefono__]" => $sucursalDatos['telefono'],
		"[__Sucursal_Celular__]" => $sucursalDatos['celular'],
		"[__Sucursal_Correo_Electronico__]" => $sucursalDatos['correo_electronico'],
		"[__Sucursal_RFC__]" => $sucursalDatos['rfc'],
	);
	$ventas = array(
		"[__Venta_Clave__]" => $ventaDatos['clave'],
		"[__Venta_Fecha__]" => $ventaDatos['fecha'],
		"[__Venta_Hora__]" => $ventaDatos['hora'], 
		"[__Venta_Fecha_FormatoNormal__]" => fechaNormal($ventaDatos['fecha']), 
		"[__Venta_Monto_Publico_Total__]" => "$".number_format($ventaDatos['monto_publico_total'], 2, '.', ','),
		"[__Venta_Fecha_Llegada__]" => $ventaDatos['fecha_llegada'],
		"[__Venta_Fecha_Llegada_FormatoNormal__]" => fechaNormal($ventaDatos['fecha_llegada']), 
		"[__Venta_Fecha_Salida__]" => $ventaDatos['fecha_salida'],
		"[__Venta_Fecha_Salida_FormatoNormal__]" => fechaNormal($ventaDatos['fecha_salida']), 
		"[__Venta_Pax_Totales__]" =>$ventaDatos['pax_totales'], 
		"[__Venta_Adultos__]" =>$ventaDatos['adultos'],
		"[__Venta_Juniors__]" =>$ventaDatos['juniors'],
		"[__Venta_Menores__]" =>$ventaDatos['menores'],
		"[__Venta_Detalle__]" =>$ventaDatos['detalle'],
	);

	$clientes = array(
		"[__Cliente_Nombre_Completo__]" => $clienteDatos['nombre_completo']!="" ? $clienteDatos['nombre_completo'] : "", 
		"[__Cliente_Telefono__]" => $clienteDatos['telefono']!="" ? $clienteDatos['telefono'] : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
		"[__Cliente_Celular__]" => $clienteDatos['celular']!="" ? $clienteDatos['celular'] : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", 
		"[__Cliente_Correo_Electronico__]" => $clienteDatos['correo_electronico']!="" ? $clienteDatos['correo_electronico'] : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
		"[__Cliente_Whatsapp__]" => $clienteDatos['whatsapp']!="" ? $clienteDatos['whatsapp'] : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
		"[__Cliente_Clave__]" => $clienteDatos['clave']!="" ? $clienteDatos['clave'] : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
	);

	$venta_pasajeros_datos="";
	foreach ($venta_pasajerosDatosArray as $key => $value) {
		$value['nombre_completo'];
		$value['telefono'];
		$value['titular'];
		if($value['titular']==1){
			$titular="Titular";
		}else{
			$titular="";
		}
		$venta_pasajeros_datos.= '
			<tr>
				<td class="RegistroLabel" colspan="2">
					<div style="width:120px;background-color:#696969;color:white;display:block;float:left;padding:15px">'.$titular.'</div>
					<font style="padding:5px">'.$value['nombre_completo'].'</font><br>
					<small style="padding:4px">Telefono: '.$value['telefono'].'</small>
				</td>
			</tr>';
	}

	$venta_pasajeros = array(
		"[__Venta_Pasajeros_Datos__]" => $venta_pasajeros_datos, 
	);

	$venta_productos_datos="";
	foreach ($venta_productosDatosArray as $key => $value) {
		$value['tipo_pedido'];
		if($value['tipo_pedido']=="hoteles"){
			$venta_productos_datos.= '
			<table class="divSeccion100"  >
				<thead>
					<tr>
						<td class="InfoLabel" colspan="2" >Codigo de Reserva</td>
						<td class="RegistroLabel" colspan="2" >
							'.$value['codigo_reserva'].'
						</td>
						<td style="width: 55%; padding: 0px" ></td> 
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="InfoLabel">Cantidad</td>
						<td class="RegistroLabel">
							'.$value['cantidad'].'
						</td>
						<td colspan="3"></td>
					</tr>
					<tr>
						<td class="InfoLabel" colspan="5" >Servicio</td> 
					</tr>
					<tr>
						<td class="RegistroLabel" colspan="5" >
						'.$value['nombre_hotel'].' -  Tipo de Hab. '.$value['nombre_tipo_habitacion'].'
						</td> 
					</tr>
					<tr>
						<td class="InfoLabel">Precio</td>
						<td class="RegistroLabel">
							$'.number_format($value['monto_publico'], 2, '.', ',').'
						</td>
						<td colspan="3"></td>
					</tr>
				</tbody>
			</table><hr style="width: 100%;text-align: center;">';
		}else{
			$venta_productos_datos.= '
			<table class="divSeccion100"  >
				<thead>
					<tr>
						<td class="InfoLabel" colspan="2" >Codigo de Reserva</td>
						<td class="RegistroLabel" colspan="2" >
							'.$value['codigo_reserva'].'
						</td>
						<td style="width: 55%; padding: 0px" ></td> 
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="InfoLabel">Cantidad</td>
						<td class="RegistroLabel">
							'.$value['cantidad'].'
						</td>
						<td colspan="3"></td>
					</tr>
					<tr>
						<td class="InfoLabel" colspan="5" >Servicio</td> 
					</tr>
					<tr>
						<td class="RegistroLabel" colspan="5" >
						'.$value['nombre_producto'].' -  Tipo de Producto. '.$value['nombre_tipo_producto'].'
						</td> 
					</tr>
					<tr>
						<td class="InfoLabel">Precio</td>
						<td class="RegistroLabel">
							$'.number_format($value['monto_publico'], 2, '.', ',').'
						</td>
						<td colspan="3"></td>
					</tr>
				</tbody>
			</table><hr style="width: 100%;text-align: center;">';
		}
	}
	$venta_productos_detalles="";
	foreach ($venta_productosDatosArray as $key => $value) {
		$value['tipo_pedido'];
		if($value['tipo_pedido']=="hoteles"){
			$venta_productos_detalles.= '
				<table style="width:100%">
					<tr>
						<td>
							<table class="divSeccion100">
								<thead>
									<tr>
										<td class="InfoLabel" >Servicio</td> 
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="RegistroLabel">
										'.$value['nombre_hotel'].' -  Tipo de Hab. '.$value['nombre_tipo_habitacion'].'
										</td>
									</tr>
									<tr>
										<td class="InfoLabel" >Detalle</td> 
									</tr>
									<tr>
										<td class="RegistroLabel">
										'.$value['detalle'].'
										</td>
									</tr>
								</tbody>
							</table><hr style="width: 100%;text-align: center;">
						</td>
					</tr>
				</table>

				';
		}else{
			$venta_productos_detalles.= '
				<table style="width:100%">
					<tr>
						<td>
							<table class="divSeccion100">
								<thead>
									<tr>
										<td class="InfoLabel" >Servicio</td> 
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="RegistroLabel">
										'.$value['nombre_producto'].' -  Tipo de Producto. '.$value['nombre_tipo_producto'].'
										</td>
									</tr>
									<tr>
										<td class="InfoLabel" >Detalle</td> 
									</tr>
									<tr>
										<td class="RegistroLabel">
										'.$value['detalle'].'
										</td>
									</tr>
								</tbody>
							</table><hr style="width: 100%;text-align: center;">
						</td>
					</tr>
				</table>
				';
		}
	}

	$venta_productos = array(
		"[__Venta_Pedidos__]" => $venta_productos_datos,
		"[__Venta_Pedidos_Detalles__]" => $venta_productos_detalles,
	);
	$bancos="";
	$bancosMostrar="";
	$bancosDatosMostrar=bancosDatosSentence('mostrar=1 ORDER BY nombre DESC');
	if($bancosDatosMostrar){
		$bancosMostrar="<tr>
						<td class='InfoLabel' colspan='2'>
							EFECTIVO <br>
						</td></tr><tr>
					<td class='RegistroLabel' colspan='2'>EN AGENCIA </td>
					</tr>";
		foreach ($bancosDatosMostrar as $key => $value) {
			$value['nombre'];
			$value['clabe'];
			$value['numero_cuenta'];
			$bancosMostrar.="<tr>
						<td class='InfoLabel' colspan='2'>
							Banco: ".$value['nombre']."
						</td>
					</tr>
					<tr>
					<td class='RegistroLabel' colspan='2'>CLABE: ".$value['clabe']." </td>
					</tr>
					<tr>
					<td class='RegistroLabel' colspan='2'>Numero de Cuenta: ".$value['numero_cuenta']." </td>
					</tr>";
		}

		$bancos="<div >
		<div class='divTitulo'>
		<b class='info'>Metodos de Pago</b>
		</div>
		<div class='divBody'>
			<table class='divSeccion100' >
			{$bancosMostrar}
			</table>
			
		</div>
		<div class='divTitulo'></div>
	</div>";
	}
	$bancos = array(
		"[__Bancos_Mostrar__]" => $bancos!="" ? $bancos : "", 
	);


	$avisos_privacidadDatos=avisos_privacidadDatos();
	$avisos_mostrar=$avisos_privacidadDatos['aviso_privacidad']."<br><br>".$avisos_privacidadDatos['terminos_condiciones_cupon']."<br><br>".$avisos_privacidadDatos['terminos_condiciones_pago'];
	$avisos = array(
		"[__Avisos_Privacidad__]" => $avisos_mostrar!="" ? $avisos_mostrar : "", 
	);

	$bodyHTML=strtr($pagina_inicio, array_merge($css,$impresion,$sucursales, $empresa,$clientes,$ventas,$venta_pasajeros,$venta_productos,$bancos,$avisos));

	require_once('../../librerias/pdf/mpdf.php');
	//$mpdf = new mPDF(); 
	$mpdf->showImageErrors = true;
	//$mpdf -> writeHTML($bodyHTML);
	//$mpdf -> Output('reporte.pdf','I');
	$mpdf = new mPDF('c','A4'); 
	$mpdf->SetProtection(array('print'));
	$mpdf->SetTitle("Folio - ".$ventaDatos['clave']);
	$mpdf->SetAuthor("Mi Agencia de Viajes");
	//$mpdf->SetWatermarkText("Reporte");
	//$mpdf->showWatermarkText = true;
	//$mpdf->SetWatermarkImage("data:image/jpg;base64, ".$kad_photo);
	$mpdf->showWatermarkImage = true; 
	$mpdf->watermark_font = 'Helvetica Neue,Helvetica,Arial,sans-serif';
	$mpdf->watermarkTextAlpha = 0.001;
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->WriteHTML($bodyHTML);
	$mpdf->Output('Venta_-_'.$ventaDatos['clave'].'.pdf', 'I');

	/*
	'D': download the PDF file
	'I': serves in-line to the browser
	'S': returns the PDF document as a string
	'F': save as file $file_out
	
	<barcode code="MECARD:N:Norfi, Carrodeguas;TEL:5358167785;EMAIL:info@norfipc.com;URL:http://norfipc.com;" type="QR" class="barcode" size="1" error="Q" />


	*/













?>