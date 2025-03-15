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
            padding: 5px;
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
        <table  style="table-layout: auto;width:100%; border:0.5px solid #ddd;border-collapse: collapse; padding:5px ">
            <tr>
                <td style="padding:5px ">[__Partido_Logo__]</td>
                <td style="color: [__Partido_Color_Font__];padding:5px ">[__Partido_Nombre__]</td>
                <td style="color: [__Partido_Color_Font__];font-size:8px;text-align:left;padding:5px ">
                    [__Militante_Partido_Clave__]<br>
                    [__Seccion_Ine_CIudadano_Clave__]
                </td>
            </tr>
        </table>
    </div>
    <div class="titulos_cuadros_blanco" >
        <table style="table-layout: fixed;width:100%">
            <tr>
                <td style="text-align:center;background-color:[__Partido_Color_background__]">[__Militante_Partido_QR__]</td>
                <td style="font-size:9px; padding-left:10px;vertical-align: top;">
                    <div style="font-size:8px">
                        <b style="color:[__Partido_Color_background__]">Registro:</b> [__Militante_Fecha_Registro__]<br>
                    </div>
                    <b style="color:[__Partido_Color_background__]">Nombre(s):</b> [__Militante_Partido_Nombre__]<br>
                    <b style="color:[__Partido_Color_background__]">Paterno:</b> [__Militante_Partido_Apellido_Paterno__]<br>
                    <b style="color:[__Partido_Color_background__]">Materno:</b> [__Militante_Partido_Apellido_Materno__]<br>
                    <b style="color:[__Partido_Color_background__]">Fecha Nacimiento:</b> [__Militante_Partido_Fecha_Nacimiento_Solo__]<br>
                    <br>
                    <b style="color:[__Partido_Color_background__]">Sección:</b> [__Militante_Partido_Seccion__]<br>
                    <b style="color:[__Partido_Color_background__]">Municipio:</b> [__Militante_Partido_Municipio__]<br>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
