<?php
	@session_start(); 
	include __DIR__."../../../functions/security.php";
	include __DIR__."../../../functions/configuracion.php";
	include __DIR__."../../../functions/secciones_ine_ciudadanos.php";
	include __DIR__."../../../functions/timemex.php";



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
	

	$id_seccion_ine_ciudadano_compartido = $_SESSION['reporte_Sistema']['searchTable']['id_seccion_ine_ciudadano_compartido'];

	if($id_seccion_ine_ciudadano_compartido != ""){
		$pagina_inicio = file_get_contents('../plantillas/pdf_relacionado.php');
	}else{
		$pagina_inicio = file_get_contents('../plantillas/pdf_normal.php');
	}
	$configuracionDatos = configuracionDatos();
	$seccion_ine_ciudadanoDatos = seccion_ine_ciudadanoDatos($id_seccion_ine_ciudadano_compartido);

	if($seccion_ine_ciudadanoDatos['nombre_completo']==""){
		$seccion_ine_ciudadanoDatos['nombre_completo'] = "Todos";
	}


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
		$img_logo='<img src="../../../ops/imagen.php?id_img=logo_principal.png" height="90px" >';
	}else{
		$img_logo='<img src="../../../ops/imagen.php?id_img=logo_principal.png" height="90px" >';
	}
	
	$empresa = array(
		"[__Empresa_Nombre__]" => $configuracionDatos['nombre'], 
		"[__Empresa_Slogan__]" => $configuracionDatos['slogan']."<br><br>",
		"[__Empresa_Registro_Nacional_Turismo__]" => "<b>Registro Nacional De Turismo:</b><br>".$configuracionDatos['registro_nacional_turismo']."<br>",
		"[__Empresa_Logo__]" => $img_logo,
	);

	$ciudadano_principal  = array(
		"[__Ciudadano_Principal_Nombre_]" => $seccion_ine_ciudadanoDatos['nombre_completo'], 
		"[__Ciudadano_Principal_Seccion__]" => $seccion_ine_ciudadanoDatos['seccion'], 
		"[__Ciudadano_Principal_Whastapp__]" => $seccion_ine_ciudadanoDatos['whatsapp'], 
		"[__Ciudadano_Principal_Telefono__]" => $seccion_ine_ciudadanoDatos['telefono'], 
		"[__Ciudadano_Principal_Celular__]" => $seccion_ine_ciudadanoDatos['celular'], 
		"[__Ciudadano_Principal_Direccion__]" => $seccion_ine_ciudadanoDatos['calle'].",".$seccion_ine_ciudadanoDatos['colonia'],
		"[__Ciudadano_Principal_Tipo_Ciudadano__]" => $seccion_ine_ciudadanoDatos['tipo_ciudadano'], 
	);

	$columna_nombres = "";
	if($id_seccion_ine_ciudadano_compartido == ""){
		foreach ($_SESSION['reporte_Sistema']['columnas_nombre'] as $key => $value) {
			$columna_nombres .='<td class="InfoLabeltable">'.$value.'</td> ';
		}
	}else{
		foreach ($_SESSION['reporte_Sistema']['columnas_nombre'] as $key => $value) {
			if($value != "Relacionado"){
				$columna_nombres .='<td class="InfoLabeltable">'.$value.'</td> ';
			}
		}
	}

	
	
	$columna_datos = "";
	$sql=$_SESSION['reporte_Sistema']['sql'];
	$result = $conexion->query($sql); 
	while($row=$result->fetch_assoc()){
		$columna_datos .= "
				<tr>
					<td class='RegistroLabelPax'>{$row['seccion']}</td>";
	if($id_seccion_ine_ciudadano_compartido == ""){
		if($row['relacionado']==""){
			$row['relacionado'] ="NO TIENE";
		}
		$columna_datos .="<td class='RegistroLabelPax'>{$row['relacionado']}</td>";
	}
	$columna_datos .="<td class='RegistroLabelPax'>{$row['nombre_completo']}</td>
					<td class='RegistroLabelPax'>{$row['sexo']}</td>
					<td class='RegistroLabelPax'>{$row['fecha_nacimiento']}</td>
					<td class='RegistroLabelPax'>{$row['whatsapp']}</td>
					<td class='RegistroLabelPax'>{$row['celular']}</td>
					<td class='RegistroLabelPax'>".$row['calle'].", ".$row['colonia']."</td>
					<td class='RegistroLabelPax'>{$row['observaciones']}</td>
				</tr>
			";
		
	}

	$tabla_ciudadanos = array(
		"[__Ciudadano_Tabla_Nombres_]" => $columna_nombres, 
		"[__Ciudadano_Tabla_Datos_]" => $columna_datos, 
	);

	

	$bodyHTML = strtr($pagina_inicio, array_merge($css,$impresion,$empresa,$ciudadano_principal,$tabla_ciudadanos));

	$bodyHTML;


	require_once('../../librerias/pdf/mpdf.php');
	//$mpdf = new mPDF(); 
	$mpdf->showImageErrors = true;
	//$mpdf -> writeHTML($bodyHTML);
	//$mpdf -> Output('reporte.pdf','I');
	$mpdf = new mPDF('c','A4'); 
	$mpdf->SetProtection(false);
	$mpdf->SetTitle("Ciudadanos - ".$seccion_ine_ciudadanoDatos['nombre_completo']);
	$mpdf->SetAuthor("Perfiles Net");
	//$mpdf->SetWatermarkText("Reporte");
	//$mpdf->showWatermarkText = true;
	//$mpdf->SetWatermarkImage("data:image/jpg;base64, ".$kad_photo);
	$mpdf->showWatermarkImage = true; 
	$mpdf->watermark_font = 'Helvetica Neue,Helvetica,Arial,sans-serif';
	$mpdf->watermarkTextAlpha = 0.001;
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->WriteHTML($bodyHTML);
	$mpdf->Output('Ciudadanos_-_'.$seccion_ine_ciudadanoDatos['nombre_completo'].'.pdf', 'I');


  

	/*
	'D': download the PDF file
	'I': serves in-line to the browser
	'S': returns the PDF document as a string
	'F': save as file $file_out
	
	<barcode code="MECARD:N:Norfi, Carrodeguas;TEL:5358167785;EMAIL:info@norfipc.com;URL:http://norfipc.com;" type="QR" class="barcode" size="1" error="Q" />


	*/













?>