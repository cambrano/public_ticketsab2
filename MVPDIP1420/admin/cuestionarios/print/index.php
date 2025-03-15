<?php
	@session_start(); 
	include __DIR__."../../../functions/security.php";
	include __DIR__."../../../functions/configuracion.php";
	include __DIR__."../../../functions/encuestas.php";
	include __DIR__."../../../functions/cuestionarios.php";
	include __DIR__."../../../functions/cuestionarios_respuestas.php";
	include __DIR__."../../../functions/timemex.php";
	include __DIR__."../../../functions/efs.php";


	$id_encuesta=$_GET['cot'];
	$pageService=$_GET['cot2'];
	if($pageService==""){
		?>
		<script type="text/javascript">
			window.close();
		</script>
		<?php
	}
	if($_COOKIE['pageService'] != $pageService){
		?>
		<script type="text/javascript">
			window.close();
		</script>
		<?php
		die;
	}

	$pagina_inicio = file_get_contents('../plantillas/pdf_cuestionario.php');
	$configuracionDatos = configuracionDatos();

	$css = array(
		"[_Uppercase_]" => "text-transform: uppercase;",
		//"[_Uppercase_]" => "",
	);

	$impresion = array(
		"[__Impresion_Fecha_Hora__]" => $fechaH , 
	);

	$mostrarImagenBase64 = mostrarImagenBase64('logo_principal.png');
	$image = "data:image/png;base64,".$mostrarImagenBase64;
	$img_logo='<img src="'.$image.'" height="90px" >';
	$empresa = array(
		"[__Empresa_Nombre__]" => $configuracionDatos['nombre'], 
		"[__Empresa_Slogan__]" => $configuracionDatos['slogan']."<br><br>",
		"[__Empresa_Registro_Nacional_Turismo__]" => "<b>Registro Nacional De Turismo:</b><br>".$configuracionDatos['registro_nacional_turismo']."<br>",
		"[__Empresa_Logo__]" => $img_logo,
	);

	$encuestaDatos = encuestaDatos($id_encuesta);
	$encuesta = array(
		"[_**__Encuesta_Nombre__**_]" => $encuestaDatos['nombre'],
	);

	$cuestionariosIdDatos = cuestionariosIdDatos('',$id_encuesta,'orden asc');
	$cuestionario_respuestasIdDatos = cuestionario_respuestasIdDatos('','',$id_encuesta,'id_cuestionario,orden asc');


	$preguntas = "";
	$total_columnas = 4;
	foreach ($cuestionariosIdDatos as $key => $value) {
		$preguntas.= "<table width='100%'>";
		$preguntas.= "<tr>";
		$preguntas.= "<td colspan='4' class='pregunta'>".$value['pregunta']."</td>";
		$preguntas.= "</tr>";
		if($value['campo']=="text"){
			$preguntas.= "<tr>";
			$preguntas.= "<td colspan='4'><input type='text' style='width:100%;'></td>";
			$preguntas.= "</tr>";
		}else{
			$columna = 1;
			foreach ($cuestionario_respuestasIdDatos[$value['id']] as $keyT => $valueT) {
				if($columna ==1){
					$preguntas.= "<tr>";
				}
				if($columna <= $total_columnas){
					$preguntas.= "<td class='respuesta' >";
					if($value['campo']=="checkbox" || $value['campo']=="radio"){
						$preguntas.= '<input type="'.$value['campo'].'"> '.$valueT['respuesta'];
					}
					$preguntas.= "</td>";
				}
				if($columna == 4){
					$tr='';
					$preguntas.= "</tr>";
					$columna = 1;
				}else{ 
					$columna ++;
					$tr='1';
				}
			}
		}
		if($tr==1){
			$preguntas.= "</tr>";
		}
		$preguntas.= "</table>";



		$preguntas.= '<div style="float: left; width: 100%;padding: 10px 2px 10px 2px"><hr></hr></div>';
	}

	$cuestionario = array(
		"[**__Cuestionario_Preguntas__**]" => $preguntas, 
	);

	$bodyHTML = strtr($pagina_inicio, array_merge($css,$impresion,$empresa,$encuesta,$cuestionario));
	require_once('../../librerias/pdf/mpdf.php');
	//$mpdf = new mPDF(); 
	$mpdf->showImageErrors = true;
	//$mpdf -> writeHTML($bodyHTML);
	//$mpdf -> Output('reporte.pdf','I');
	$mpdf = new mPDF('c','A4'); 
	$mpdf->SetProtection(false);
	$mpdf->SetTitle("Encuesta - ".$encuestaDatos['clave']."-".$encuestaDatos['nombre']);
	$mpdf->SetAuthor("Ideas Net");
	$mpdf->shrink_tables_to_fit = 1;
	//$mpdf->SetWatermarkText("Reporte");
	//$mpdf->showWatermarkText = true;
	//$mpdf->SetWatermarkImage("data:image/jpg;base64, ".$kad_photo);
	$mpdf->showWatermarkImage = true; 
	$mpdf->watermark_font = 'Helvetica Neue,Helvetica,Arial,sans-serif';
	$mpdf->watermarkTextAlpha = 0.001;
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->WriteHTML($bodyHTML);
	$mpdf->Output("Encuesta - ".$encuestaDatos['clave']."-".$encuestaDatos['nombre'].'.pdf', 'I');
	/*
	'D': download the PDF file
	'I': serves in-line to the browser
	'S': returns the PDF document as a string
	'F': save as file $file_out
	
	<barcode code="MECARD:N:Norfi, Carrodeguas;TEL:5358167785;EMAIL:info@norfipc.com;URL:http://norfipc.com;" type="QR" class="barcode" size="1" error="Q" />
