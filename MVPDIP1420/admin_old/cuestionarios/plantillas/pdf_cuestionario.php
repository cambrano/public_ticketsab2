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
			    margin-top: 60px; 
			}

			@page :first {    
			    header: html_MyHeader1;
			    footer: html_MyFooter1;
			    margin-top: 120px;
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
				font-size: 7pt; 
				font-family: "National-Regular,HelveticaNeueRegular,HelveticaNeue-Regular,"Helvetica Neue Regular",HelveticaNeue,"Helvetica Neue",TeXGyreHerosRegular,Helvetica,Tahoma,Geneva,Arial,sans-serif"; 
				vertical-align:top;
				padding: 0px;
			}
			.labelInfoEmpresaUPPer{
				color: black;
				[_Uppercase_]
				letter-spacing: 2px;
				font-weight: 10px;
				font-size: 7pt; 
				font-family: "National-Regular,HelveticaNeueRegular,HelveticaNeue-Regular,"Helvetica Neue Regular",HelveticaNeue,"Helvetica Neue",TeXGyreHerosRegular,Helvetica,Tahoma,Geneva,Arial,sans-serif"; 
				vertical-align:top;
				padding: 0px;
			}
			.labelInfoEmpresaZipcode{
				clear: both;
				color: black; 
				letter-spacing: 2px;
				font-weight: 10px;
				font-size: 7pt; 
				font-family: "National-Regular,HelveticaNeueRegular,HelveticaNeue-Regular,"Helvetica Neue Regular",HelveticaNeue,"Helvetica Neue",TeXGyreHerosRegular,Helvetica,Tahoma,Geneva,Arial,sans-serif"; 
				vertical-align:top;
				padding: 0px;
			}
			.labelInfoEmpresaFecha{
				color: black;
				[_Uppercase_]
				letter-spacing: 2px;
				font-weight: 10px;
				font-size: 5pt; 
				font-family: "National-Regular,HelveticaNeueRegular,HelveticaNeue-Regular,"Helvetica Neue Regular",HelveticaNeue,"Helvetica Neue",TeXGyreHerosRegular,Helvetica,Tahoma,Geneva,Arial,sans-serif"; 
				vertical-align:top; 
				width: 255px;
				vertical-align:top;  

			}
			.info{
				
				[_Uppercase_]
				letter-spacing: 2px;
				font-weight: bolder;
				font-size: 7pt; 
				font-family: "National-Regular,HelveticaNeueRegular,HelveticaNeue-Regular,"Helvetica Neue Regular",HelveticaNeue,"Helvetica Neue",TeXGyreHerosRegular,Helvetica,Tahoma,Geneva,Arial,sans-serif"; 
				vertical-align:top;  
				color:white;
				text-align: center;
				width: 100%;
				padding: 5px;  
			}
			.InfoLabel{ 
				[_Uppercase_]
				letter-spacing: 2px;
				font-weight: bolder;
				font-size: 4pt; 
				font-family: "National-Regular,HelveticaNeueRegular,HelveticaNeue-Regular,"Helvetica Neue Regular",HelveticaNeue,"Helvetica Neue",TeXGyreHerosRegular,Helvetica,Tahoma,Geneva,Arial,sans-serif"; 
				
				background-color: #FFF1C5;
				color:black; 
				vertical-align:top;
				display: table;
			}
			.InfoLabeltable{ 
				[_Uppercase_]
				letter-spacing: 2px;
				font-weight: bolder;
				font-size: 4pt; 
				font-family: "National-Regular,HelveticaNeueRegular,HelveticaNeue-Regular,"Helvetica Neue Regular",HelveticaNeue,"Helvetica Neue",TeXGyreHerosRegular,Helvetica,Tahoma,Geneva,Arial,sans-serif"; 
				
				background-color: #FFF1C5;
				color:black; 
				vertical-align:top;
			}
			.RegistroLabel{
				color: black;
				[_Uppercase_]
				letter-spacing: 2px;
				font-weight: 400;
				font-size: 6pt; 
				font-family: "National-Regular,HelveticaNeueRegular,HelveticaNeue-Regular,"Helvetica Neue Regular",HelveticaNeue,"Helvetica Neue",TeXGyreHerosRegular,Helvetica,Tahoma,Geneva,Arial,sans-serif"; 
				vertical-align:top;  
				color:black; 
				background-color: #F5F5F5; 
				padding: 0px 0px 0px 10px;
				border-bottom: .08mm solid #555555; 
			}
			.RegistroLabelLista{
				color: black;
				[_Uppercase_]
				letter-spacing: 2px;
				font-size: 6pt; 
				font-family: "National-Regular,HelveticaNeueRegular,HelveticaNeue-Regular,"Helvetica Neue Regular",HelveticaNeue,"Helvetica Neue",TeXGyreHerosRegular,Helvetica,Tahoma,Geneva,Arial,sans-serif"; 
				vertical-align:top;  
				color:black; 
				background-color: #F5F5F5; 
				padding: 0px 0px 0px 10px;
			}
			.RegistroLabelSN{
				color: black;
				[_Uppercase_]
				letter-spacing: 2px;
				font-weight: 400;
				font-size: 6pt; 
				font-family: "National-Regular,HelveticaNeueRegular,HelveticaNeue-Regular,"Helvetica Neue Regular",HelveticaNeue,"Helvetica Neue",TeXGyreHerosRegular,Helvetica,Tahoma,Geneva,Arial,sans-serif"; 
				vertical-align:top;  
				color:black; 
				background-color: #F5F5F5; 
				padding: 0px 0px 0px 10px; 
			}
			.RegistroLabelPax{
				color: black;
				[_Uppercase_]
				letter-spacing: 2px;
				font-weight: bolder;
				font-size: 4pt; 
				font-family: "National-Regular,HelveticaNeueRegular,HelveticaNeue-Regular,"Helvetica Neue Regular",HelveticaNeue,"Helvetica Neue",TeXGyreHerosRegular,Helvetica,Tahoma,Geneva,Arial,sans-serif"; 
				vertical-align:top; 
				background-color: #F5F5F5;
				color:black;
				vertical-align:top;
				padding: 2px 0px 2px 2px;
				border-bottom: .08mm solid #555555; 
			}
			.RegistroLabelPaxSNUPPER{
				color: black; 
				letter-spacing: 2px;
				font-weight: bolder;
				font-size: 4pt; 
				font-family: "National-Regular,HelveticaNeueRegular,HelveticaNeue-Regular,"Helvetica Neue Regular",HelveticaNeue,"Helvetica Neue",TeXGyreHerosRegular,Helvetica,Tahoma,Geneva,Arial,sans-serif"; 
				vertical-align:top; 
				background-color: #F5F5F5;
				color:black;
				vertical-align:top;
				padding: 2px 0px 2px 2px;
				border-bottom: .08mm solid #555555; 
			}

			.divTitulo{
				width: 100%;
				padding: 0px;
				background-color: #191919;
				padding: 3px;
				color: #262626;
				font-size: 8pt;
				display: table;
			}

			.divBody{
				width: 100%;
				padding: 2px 10px 2px 10px;
				background-color: #F8F8F8;
			}
			.divSeccion{
				border-spacing: 3px;
				border-collapse: separate;
			}
			.divSeccion20{
				border-spacing: 3px;
				border-collapse: separate;
				width: 20%;
			}
			.divSeccion60{
				border-spacing: 3px;
				border-collapse: separate;
				width: 50%;
			}
			.divSeccion100{
				border-spacing: 3px;
				border-collapse: separate;
				width: 100%
			}

			.pregunta{ 
				font-family: "National-Regular,HelveticaNeueRegular,HelveticaNeue-Regular,"Helvetica Neue Regular",HelveticaNeue,"Helvetica Neue",TeXGyreHerosRegular,Helvetica,Tahoma,Geneva,Arial,sans-serif";
				font-weight: bold;
				padding: 4px;
				font-size: 13px;
			}
			.respuesta{ 
				font-family: "National-Regular,HelveticaNeueRegular,HelveticaNeue-Regular,"Helvetica Neue Regular",HelveticaNeue,"Helvetica Neue",TeXGyreHerosRegular,Helvetica,Tahoma,Geneva,Arial,sans-serif";
				padding: 3px 7px 3px 7px;
			}

			table {page-break-inside: avoid;}
			/*li { list-style-image: url("https://www.softwaresada.dev/vw/apapachoviajes/ops/imagen.php?id_img=list_icon24.png"); } 

		</style> 
	</head>
	<body>
		<htmlpageheader name="MyHeader1">
			<table width="100%">
				<tr>
					<td style="width: 80px; padding: 0px;text-align: center;padding: 0px 5px 0px 5px;vertical-align:top;">
						[__Empresa_Logo__]
					</td>
					<td class="labelInfoEmpresa" style="vertical-align: middle;padding: 20px">
						<div class="labelInfoEmpresaUPPer">
							<b>[__Empresa_Nombre__]</b><br>
							[__Empresa_Slogan__]
						</div>
					</td>
					 
				</tr>
			</table>
	</htmlpageheader>
	 <htmlpageheader name="MyHeader2">
		<div style="border-bottom: 1px solid #000000; font-weight: bold;  font-size: 10pt;"></div>
	</htmlpageheader>
	 <htmlpagefooter name="MyFooter1">
		<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
			<tr>
				<td width="66%" align="left"><span style="font-weight: bold; font-style: italic;">[__Empresa_Nombre__] Encuesta [_**__Encuesta_Nombre__**_]</span></td>
				<td width="33%" align="right" style="font-weight: bold; font-style: italic;">Pág {PAGENO} de {nbpg}</td> 
			</tr>
		</table>
	</htmlpagefooter>
	<htmlpagefooter name="MyFooter2">
		<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
			<tr>
				<td width="66%" align="left"><span style="font-weight: bold; font-style: italic;">[__Empresa_Nombre__] Encuesta [_**__Encuesta_Nombre__**_]</span></td>
				<td width="33%" align="right" style="font-weight: bold; font-style: italic;">Pág {PAGENO} de {nbpg}</td> 
			</tr> 
		</table>
	</htmlpagefooter>
	<!--body-->
	<br>
	<div >
		<h3 style="padding: 0px;margin: 6px">[_**__Encuesta_Nombre__**_]</h3>
		<div class="divTitulo" style="text-align: center;">
		<b class="info">Encuesta</b>
		</div>
		<div class="divBody">
			[**__Cuestionario_Preguntas__**]
		</div>
		<div class="divTitulo"></div>
	</div>

	</body>
	</html> 