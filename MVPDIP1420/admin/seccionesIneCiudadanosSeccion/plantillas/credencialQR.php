<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <style>
        @page {  
            header: html_MyHeader2;
            footer: html_MyFooter2;
            margin: 0px; 
        }
        h3{
				font-family: 'Arial', 'Helvética', 'Verdana', 'Tahoma', 'Trebuchet MS', sans-serif; 
			}
			body{
				font-family: 'Arial', 'Helvética', 'Verdana', 'Tahoma', 'Trebuchet MS', sans-serif; 
			}

        div.noheader {
            page-break-before: always;
            page: noheader;
        }
        .titulos_cuadros{
            text-align: center;
            /*padding: 5px;*/
            background-color: [__Partido_Color_background__];
            color: [__Partido_Color_Font__];
            font-size:10px;
            width: 100%;
        }
        .titulos_cuadros_blanco{
            text-align: center;
            color: black;
            font-size:10px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="titulos_cuadros" >
        <table  style="table-layout: fixed;width:100%; border:0.5px solid #ddd;border-collapse: collapse; padding:5px ">
            <tr>
                <td style="padding:5px; padding:2px 5px 2px 5px;width:60px">[__Empresa_Logo__]</td>
                <td style="font-size:12px;text-align: left;padding:10px;">
                    [__Empresa_Nombre__]<br>
                    <div style="font-size:6px;font-style: italic; " >
                        [__Empresa_Slogan__]
                    </div>
                </td>
                <td style="color: [__Partido_Color_Font__];font-size:8px;text-align:left;padding:2px;width:70px ">
                    [__Ciudadano_Folio__]<br>
                    [__Ciudadano_Clave__]
                </td>
            </tr>
        </table>
    </div>
    <div class="titulos_cuadros_blanco" >
        <table style="table-layout: fixed;width:100%">
            <tr>
                <td style="text-align:center;background-color:[__Partido_Color_Font__]">[__Ciudadano_QR__]</td>
                <td style="font-size:9px; padding-left:10px;vertical-align: top;">
                    <b style="color:[__Partido_Color_Font__]">C.U.R.P:</b> [__Ciudadano_CURP__]<br>    
                    <br>
                    <b style="color:[__Partido_Color_Font__]">Nombre(s):</b> [__Ciudadano_Nombre__]<br>
                    <b style="color:[__Partido_Color_Font__]">Paterno:</b> [__Ciudadano_Apellido_Paterno__]<br>
                    <b style="color:[__Partido_Color_Font__]">Materno:</b> [__Ciudadano_Apellido_Materno__]<br>
                    <b style="color:[__Partido_Color_Font__]">Fecha Nacimiento:</b> [__Ciudadano_Fecha_Nacimiento_Solo__]<br>
                    <br>
                    <b style="color:[__Partido_Color_Font__]">Colonia:</b> [__Ciudadano_Colonia__]<br>
                    <b style="color:[__Partido_Color_Font__]">Municipio:</b> [__Ciudadano_Municipio__]<br>
                    
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
