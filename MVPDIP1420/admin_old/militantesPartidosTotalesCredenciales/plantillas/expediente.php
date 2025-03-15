<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta charset="utf-8">
		<style type="text/css">
			/*
			@page  {
				odd-header-name: html_MyHeader1;
				odd-footer-name: html_MyFooter1;
				margin-top: 110px; 
			}
			*/
			@page {  
				header: html_MyHeader2;
				footer: html_MyFooter2;
				margin-top: 90px; 
			}

			@page :first {    
				header: html_MyHeader1;
				footer: html_MyFooter1;
				margin-top: 140px;
			}


			@page chart2 {
				odd-header-name: html_MyHeader2;
				odd-footer-name: html_MyFooter2;
				margin-top: 60px; 
			}

			div.chapter2 {
				page-break-before: always;
				page: chapter2;
				
			}

			h3{
				font-family: "National-Regular,HelveticaNeueRegular,HelveticaNeue-Regular,"Helvetica Neue Regular",HelveticaNeue,"Helvetica Neue",TeXGyreHerosRegular,Helvetica,Tahoma,Geneva,Arial,sans-serif"; 
			}

			div.noheader {
				page-break-before: always;
				page: noheader;
			}

			.labelInfoEmpresa{
				color: black; 
				letter-spacing: 2px;
				font-weight: 10px;
				font-size: 11pt; 
				font-family: "Helvetica Neue,Helvetica,Arial,sans-serif"; 
				vertical-align:top;
				padding: 0px;
			} 
			.labelInfoEmpresaSub{
				color: black; 
				letter-spacing: 2px;
				font-weight: 10px;
				font-size: 8pt; 
				font-family: "Helvetica Neue,Helvetica,Arial,sans-serif"; 
				vertical-align:top;
				padding: 0px;
			} 
			.page_break {
				page-break-before: always;
			}
			.fontLabelReporte,.fontDataReporte{
				font-size:10px;
				padding: 10px;
			}
			.titulos_cuadros{
				text-align: center;
				padding: 5px;
				background-color: [__Partido_Color_background__];
				color: [__Partido_Color_Font__];
				font-size:10px;
			}
			.titulos_secciones{
				text-align: left;
				padding: 5px;
				background-color: #191919;
				color: white;
				font-size:10px;
				width: 45%;
				margin-top: 20px;
			}
			.table_datos{
				border-collapse: collapse;
				width: 100%;
				table-layout: auto;
				border-collapse: collapse;
			}
			.table_datos th {  
				border: 1px solid #ddd;
				text-align: left;
				padding: 5px;
				font-size:10px;
				background-color:#a6a6a6;
			}
			.table_datos td{
				border: 1px solid #ddd;
				text-align: left;
				padding: 2px;
				font-size:8px;
			}
			.table_datos tr{
				page-break-inside: avoid;
			}
			table {page-break-inside: avoid;}
			/*li { list-style-image: url("https://www.softwaresada.dev/vw/apapachoviajes/ops/imagen.php?id_img=list_icon24.png"); } */
		</style> 
	</head>
	<body>
		<htmlpageheader name="MyHeader1">
			<div style="width: 50%;float: left;">
				<table width="100%">
					<tr>
						<td style="width: 80px; padding: 0px;text-align: center;padding: 0px 5px 0px 5px;vertical-align:top;">
							[__Partido_Logo__]
						</td>
						<td>
							<div class="labelInfoEmpresa">
								<b>[__Partido_Nombre__]</b><br>
							</div>
							<div class="labelInfoEmpresaSub">
								<b>[__Partido_Nombre_Corto__]</b><br>
							</div>
						</td>
					</tr>
				</table>
			</div>
			<div style="width: 50%;float: right;text-align: center;">
				<br>
				<h2 style="padding: 0px;margin: 0px">[__Documento_Titulo__]</h2>
				<br>
				<div class="divTitulo" style="text-align: left;">
					<div style="width:47%;text-align:left; float:left">
						Clave: <b class="info">[__Militante_Partido_Clave__]</b>
					</div>
					<div style="width:47%;text-align:left; float:right">
						Folio: <b class="info">[__Militante_Partido_Folio__]</b>
					</div>
					<div style="width:100%;text-align:left; float:right;font-size:10px">
						Fecha Impresión: 
								<!-- {DATE j-m-Y} -->[__Impresion_Fecha_Hora__] hrs
					</div>
				</div>
			</div>
		</htmlpageheader>
		<htmlpageheader name="MyHeader2">
			<div style="border-bottom: 1px solid #000000;">
				<table style="padding:2px;" cellspacing="0" cellpadding="0">
					<tr>
						<td style="padding:2px">
							[__Partido_Logo_Page__]
						</td>
						<td style="font-size:7pt;padding:2px">
							<b>[__Documento_Titulo__]</b><br>
							<span style="font-size:9pt">
								Clave: <b class="info">[__Militante_Partido_Clave__]</b>
							</span>
						</td>
					</tr>
				</table>
			</div>
		</htmlpageheader>
		<htmlpagefooter name="MyFooter1">
			<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
				<tr>
					<td width="66%" align="left"><span style="font-weight: bold; font-style: italic;">[__Partido_Nombre__]</span></td>
					<td width="33%" align="right" style="font-weight: bold; font-style: italic;">Pág {PAGENO} de {nbpg}</td> 
				</tr>
			</table>
		</htmlpagefooter>
		<htmlpagefooter name="MyFooter2">
			<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
				<tr>
					<td width="66%" align="left"><span style="font-weight: bold; font-style: italic;">[__Partido_Nombre__]</span></td>
					<td width="33%" align="right" style="font-weight: bold; font-style: italic;">Pág {PAGENO} de {nbpg}</td> 
				</tr> 
			</table>
		</htmlpagefooter>
		<!--body-->
		<div style="width: 49%; float: left;padding: 0px;display: block;">
			<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<td colspan="2" class="titulos_cuadros">Identificación</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">C.U.R.P:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" >
								[__Militante_Partido_CURP__]
							</font>
						</td>
					</tr>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Clave de Elector:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" >
								[__Militante_Partido_Clave_Elector__]
							</font>
						</td>
					</tr>
				</tbody>
			</table>
			<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<td colspan="2" class="titulos_cuadros">Datos Ciudadano</td>
					</tr>
				</thead>
				<tbody>
					
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Fecha Nacimiento:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" >
								[__Militante_Partido_Fecha_Nacimiento__]
							</font>
						</td>
					</tr>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Nombre:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" >
								[__Militante_Partido_Nombre__]
							</font>
						</td>
					</tr>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Apellido Paterno:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" >
								[__Militante_Partido_Apellido_Paterno__]
							</font>
						</td>
					</tr>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Apellido Materno:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" >
								[__Militante_Partido_Apellido_Materno__]
							</font>
						</td>
					</tr>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Sexo:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" >
								[__Militante_Partido_Sexo__]
							</font>
						</td>
					</tr>
				</tbody>
			</table>
			<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<td colspan="2" class="titulos_cuadros">Datos Contacto</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Teléfono:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" >
								[__Militante_Partido_Telefono__]
							</font>
						</td>
					</tr>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Celular:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" >
								[__Militante_Partido_Celular__]
							</font>
						</td>
					</tr>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Whatsapp:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" >
								[__Militante_Partido_Whatsapp__]
							</font>
						</td>
					</tr>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Correo Electrónico:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" >
								[__Militante_Partido_Correo_Electronico__]
							</font>
						</td>
					</tr>
				</tbody>
			</table>
			<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<td colspan="2" class="titulos_cuadros">Dirección</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Sección:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" >
								[__Militante_Partido_Seccion__]
							</font>
						</td>
					</tr>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Calle:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" >
								[__Militante_Partido_Calle__]
							</font>
						</td>
					</tr>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Num Ext:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" >
								[__Militante_Partido_Num_Ext__]
							</font>
						</td>
					</tr>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Num Int:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" >
								[__Militante_Partido_Num_Int__]
							</font>
						</td>
					</tr>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Colonia:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" >
								[__Militante_Partido_Colonia__]
							</font>
						</td>
					</tr>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Municipio:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" >
								[__Militante_Partido_Municipio__]
							</font>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div style="width: 49%; float: right;padding: 0px;display: block;">
			<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<td colspan="2" class="titulos_cuadros">QR Credencial Digital</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="2"
							style="text-align: center; width: 25%;padding: 10px 5px 10px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">
								[__Militante_Partido_QR__]
							</font>
						</td>
					</tr>
				</tbody>
			</table>
			
		</div>
	</body>
</html>