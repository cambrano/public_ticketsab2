<?php
	$paqueteSistemaDatos=paqueteSistemaDatos();
	$pagos_sistemaDatos=pagos_sistemaDatos();
	if($pagos_sistemaDatos==""){
		$tipo_pro=" - demo";
	}else{
		$tipo_pro="";
	}
	$fecha_vencimineto='2018-11-10';
?>
<style type="text/css">
	.totales{ 
		display: table;
		float: left; 
		width: 100%;
		font-family: 'Avenir Next';
		letter-spacing: 5px;
		font-weight: 10px;
		text-transform: uppercase;

	}
	.fontLabelReporteTable {
		padding: 1px;   
		border-width: 1px;
		/*border-color: #ebccd1;*/ 
		text-transform: uppercase;
		letter-spacing: 5px; 
		font-size: 14px;
		font-family: 'Avenir Next';
		vertical-align: bottom;
	}
	.fontLabelReporte {
		padding: 1px;   
		border-width: 1px;
		/*border-color: #ebccd1;*/ 
		text-transform: uppercase;
		letter-spacing: 5px; 
		font-size: 10px;
		font-family: 'Avenir Next'; 
	}
	.fontDataReporte {
		padding: 1px; 
		font-weight: bold; 
		border-width: 1px;
		/*border-color: #ebccd1;*/ 
		text-transform: uppercase;
		letter-spacing: 1px; 
		font-size: 14px;
		font-family: 'Avenir Next';
	}
	.div50Reporte{
		width: 50%; 
		padding: 5px 25px 10px 25px ; 
		float: left;
	}
	.div100Reporte{
		width: 100%; 
		padding: 5px 25px 10px 25px ; 
		float: left;
	}
	@media only screen and (max-width:820px) {
	/* For mobile phones: */
		.totales,.div50Reporte{
			width: 100%;
		}
		.div50Reporte,.div100Reporte{
			padding: 10px;
		}
	}
</style>
<div class="totales">
	<div style="width: 100%;display: table;padding: 5px 5px 5px 0px;">
		<div style="padding: 5px;display: table;">
			<div class="div50Reporte" style="background-color: white">
				<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td style="text-align: left;" colspan="2">Datos Paquete</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align: left;padding: 0px 5px 0px 5px"><font class="fontLabelReporte">Nombre:</font></td>
							<td style="text-align: left;padding: 0px 5px 0px 5px"><font class="fontDataReporte"><?= $paqueteSistemaDatos['nombre'].$tipo_pro ?></font></td>
						</tr>
						<tr>
							<td style="text-align: left;padding: 0px 5px 0px 5px"><font class="fontLabelReporte">Usuarios Admin</font></td>
							<td style="text-align: left;padding: 0px 5px 0px 5px"><font class="fontDataReporte"><?= $paqueteSistemaDatos['usuarios_administradores']=='x'?'&infin;':$paqueteSistemaDatos['usuarios_administradores'] ?></font></td>
						</tr>
						<tr>
							<td style="text-align: left;padding: 0px 5px 0px 5px"><font class="fontLabelReporte">Empledos:</font></td>
							<td style="text-align: left;padding: 0px 5px 0px 5px"><font class="fontDataReporte"><?= $paqueteSistemaDatos['usuarios_generales']=='x'?'&infin;':$paqueteSistemaDatos['usuarios_generales'] ?></font></td>

						</tr>
						<tr>
							<td style="text-align: left;padding: 0px 5px 0px 5px"><font class="fontLabelReporte">Sucursales</font></td>
							<td style="text-align: left;padding: 0px 5px 0px 5px"><font class="fontDataReporte"><?= $paqueteSistemaDatos['sucursales']=='x'?'&infin;':$paqueteSistemaDatos['sucursales'] ?></font></td>
						</tr>
						<tr>
							<td style="text-align: left;padding: 0px 5px 0px 5px"><font class="fontLabelReporte">Notificaciones</font></td>
							<td style="text-align: left;padding: 0px 5px 0px 5px"><font class="fontDataReporte"><?= $paqueteSistemaDatos['notificaciones_sistema']=='SI'?'SI':'NO' ?></font></td>
						</tr>
						<tr>
							<td style="text-align: left;padding: 0px 5px 0px 5px"><font class="fontLabelReporte">Whatsapp</font></td>
							<td style="text-align: left;padding: 0px 5px 0px 5px"><font class="fontDataReporte"><?= $paqueteSistemaDatos['whatsapp']=='SI'?'SI':'NO' ?></font></td>
						</tr>
					</tbody>
				</table>
				<hr style="width: 80%"> 
				<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td style="text-align: left;" colspan="2">Datos Pago</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align: left;padding: 0px 5px 0px 5px"><font class="fontLabelReporte">Monto Paquete:</font></td>
							<td style="text-align: left;padding: 0px 5px 0px 5px"><font class="fontDataReporte">$ <?= number_format($paqueteSistemaDatos['monto'],2,'.',',') ?></font></td>
						</tr>
						<tr>
							<td style="text-align: left;padding: 0px 5px 0px 5px"><font class="fontLabelReporte">Descuento(%):</font></td>
							<td style="text-align: left;padding: 0px 5px 0px 5px"><font class="fontDataReporte"><?= number_format($paqueteSistemaDatos['porcentaje'],2,'.','') ?> %</font></td>
						</tr>
						<tr>
							<td style="text-align: left;padding: 0px 5px 0px 5px"><font class="fontLabelReporte">Descuento($):</font></td>
							<td style="text-align: left;padding: 0px 5px 0px 5px"><font class="fontDataReporte">$ <?= number_format($paqueteSistemaDatos['descuento'],2,'.',',') ?></font></td>
						</tr>
						<tr>
							<td style="text-align: left;padding: 0px 5px 0px 5px"><font class="fontLabelReporte">Monto Pago($):</font></td>
							<td style="text-align: left;padding: 0px 5px 0px 5px"><font class="fontDataReporte">$ <?= number_format($paqueteSistemaDatos['monto_total'],2,'.',',') ?></font></td>
						</tr>
						<tr>
							<td style="text-align: left;padding: 0px 5px 0px 5px"><font class="fontLabelReporte">Vencimiento</font></td>
							<td style="text-align: left;padding: 0px 5px 0px 5px"><font class="fontDataReporte"><?= $paqueteSistemaDatos['fecha_demo'] ?></font></td>
						</tr>
						<tr>
							<td style="text-align: left;padding: 0px 5px 0px 5px" colspan="2"><font class="fontDataReporte"><?= fechaNormal($paqueteSistemaDatos['fecha_demo']) ?></font></td>
						</tr>
					</tbody>
				</table>
				<hr style="width: 80%"> 
				<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td style="text-align: left;" colspan="2">Enviar Pago</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align: left;padding: 0px 5px 0px 5px" colspan="2">
								<label class="descripcionForm">
									<font style="font-size: 8px;">Si usted cuenta con su comprobante de transaccion bancaria favor de enviarlo. Gracias</font><br><br>
								</label><br>
							</td>
							
						</tr>
						<tr>
							<td style="text-align: left;padding: 0px 5px 0px 5px" colspan="2" >
								<div class="fileupload" style="width: 100%;text-align: center;">
									Seleccionar Comprobante
									<input type="file" id="archivo" name="archivo" />
								</div>
								<br>
								<input type="button" id="sumbmit" onclick="guardar()" value="Envir">
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			
		</div>
	</div>

</div>