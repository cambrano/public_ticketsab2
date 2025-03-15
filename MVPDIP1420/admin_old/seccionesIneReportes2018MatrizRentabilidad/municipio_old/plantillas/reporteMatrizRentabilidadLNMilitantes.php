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
			.labelInfoEmpresaZipcode{
				clear: both;
				color: black; 
				letter-spacing: 2px;
				font-weight: 10px;
				font-size: 5pt; 
				font-family: "Helvetica Neue,Helvetica,Arial,sans-serif"; 
				vertical-align:top;
				padding: 0px;
			}
			.labelInfoEmpresaFecha{
				color: black;
				[_Uppercase_]
				letter-spacing: 2px;
				font-weight: 10px;
				font-size: 5pt; 
				font-family: "Helvetica Neue,Helvetica,Arial,sans-serif"; 
				vertical-align:top; 
				width: 255px;
				vertical-align:top;  

			}
			.page_break {
				page-break-before: always;
			}
			.dotGreen {
				height: 30px;
				width: 30px;
				background-color: green;
				border-radius: 50%;
				display: inline-block;
			}
			.dotYellow {
				height: 30px;
				width: 30px;
				background-color: yellow;
				border-radius: 50%;
				display: inline-block;
			}
			.dotRed {
				height: 30px;
				width: 30px;
				background-color: red;
				border-radius: 50%;
				display: inline-block;
			}
			.dotGray {
				height: 30px;
				width: 30px;
				background-color: gray;
				border-radius: 50%;
				display: inline-block;
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
							[__Empresa_Logo__]
						</td>
						<td>
							<div class="labelInfoEmpresa">
								<b>[__Empresa_Nombre__]</b><br>
							</div>
							<div class="labelInfoEmpresaSub">
								[__Empresa_Slogan__]<br>
								Fecha Impresión:<br>
								<!-- {DATE j-m-Y} -->[__Impresion_Fecha_Hora__] hrs
							</div>
						</td>
					</tr>
				</table>
			</div>
			<div style="width: 50%;float: right;text-align: center;">
				<br>
				<h2 style="padding: 0px;margin: 0px">[__Documento_Titulo__]</h2>
				
				<div class="divTitulo" style="text-align: center;">
					[__Documento_Tipo_Territorio__]: <b class="info">[__Documento_Territorio_Nombre__]</b>
				</div>
			</div>
		</htmlpageheader>
		<htmlpageheader name="MyHeader2">
			<div style="border-bottom: 1px solid #000000;">
				<table style="padding:2px;" cellspacing="0" cellpadding="0">
					<tr>
						<td style="padding:2px">
							[__Empresa_Logo_Page__]
						</td>
						<td style="font-size:7pt;padding:2px">
							<span style="font-size:9pt">
								[__Documento_Tipo_Territorio__]: <b class="info">[__Documento_Territorio_Nombre__]</b>
							</span>
						</td>
					</tr>
				</table>
			</div>
		</htmlpageheader>
		<htmlpagefooter name="MyFooter1">
			<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
				<tr>
					<td width="66%" align="left"><span style="font-weight: bold; font-style: italic;">[__Empresa_Nombre__]</span></td>
					<td width="33%" align="right" style="font-weight: bold; font-style: italic;">Pág {PAGENO} de {nbpg}</td> 
				</tr>
			</table>
		</htmlpagefooter>
		<htmlpagefooter name="MyFooter2">
			<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
				<tr>
					<td width="66%" align="left"><span style="font-weight: bold; font-style: italic;">[__Empresa_Nombre__]</span></td>
					<td width="33%" align="right" style="font-weight: bold; font-style: italic;">Pág {PAGENO} de {nbpg}</td> 
				</tr> 
			</table>
		</htmlpagefooter>
		<!--body-->
		<div style="width: 49%; float: left;padding: 0px;display: block;">
			<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<td
							style="text-align: center;padding: 5px;background-color: #191919;color: white"
							colspan="2">Totales Votaciones</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Lista Nominal:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" style="font-size: 12px">
								[__Lista_Nominal__]
							</font>
						</td>
					</tr>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Secciones:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" style="font-size: 12px">
								[__Secciones__]
							</font>
						</td>
					</tr>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Casillas:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" style="font-size: 12px">
								[__Casillas__]
							</font>
						</td>
					</tr>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Votos Válidos:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" style="font-size: 12px">
								[__Votos_Validos__]
							</font>
						</td>
					</tr>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Votos Nulos:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" style="font-size: 12px">
								[__Votos_Nulos__]
							</font>
						</td>
					</tr>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Votos CAN NREG:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" style="font-size: 12px">
								[__Votos_Can_Nreg__]
							</font>
						</td>
					</tr>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Votos Totales:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" style="font-size: 12px">
								[__Votos_Totales__]
							</font>
						</td>
					</tr>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Participación Ciudadana:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" style="font-size: 12px">
								[__Participacion_Ciudadano__]%
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
						<td
							style="text-align: center;padding: 5px;background-color: #191919;color: white"
							colspan="2">Cartografía</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Programas de Gobierno:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" style="font-size: 12px">
								[__Apoyo_Programas__]
							</font>
						</td>
					</tr>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Programas de Inversión:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" style="font-size: 12px">
								[__Acciones_Obras__]
							</font>
						</td>
					</tr>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Ciudadanos:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" style="font-size: 12px">
								[__Ciudadanos_Totales__]
							</font>
						</td>
					</tr>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Funcionarios:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" style="font-size: 12px">
								[__Funcionarios__]
							</font>
						</td>
					</tr>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Militantes:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" style="font-size: 12px">
								[__Militantes__]
							</font>
						</td>
					</tr>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Grupos de Interes:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" style="font-size: 12px">
								[__Militantes__]
							</font>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<br><br>
		<div style="margin-top: 10px;text-align:center;width:100%;display: table;">
			[__Partido_Primera_Fuerza__]
			[__Partido_Segunda_Fuerza__]
			[__Partido_Sistema__]
		</div>
		<br><br>
		<div style="margin-top: 2px;text-align:center;width:50%;display: table;float: left;">
			[__Grafica_Partidos_Barras__]
		</div>
		<div style="margin-top: 2px;text-align:center;width:50%;display: table;float: right;">
			[__Grafica_Partidos_Pie__]
		</div>

		[__Paginas_Grupos_Secciones__]





	</body>
	</html>