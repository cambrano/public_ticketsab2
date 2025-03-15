<?php
	ini_set('max_execution_time', 60000);
	@session_start(); 
	
	include __DIR__."../../../functions/security.php";
	include __DIR__."../../../functions/configuracion.php";
	include __DIR__."../../../functions/timemex.php";
	include __DIR__."../../../functions/militantes_partidos.php";
	include __DIR__."../../../functions/localidades.php";
	include __DIR__."../../../functions/municipios.php";
	

	
	
	$configuracion = configuracionDatos();
	$pageService=$_GET['cot'];
	if($pageService==""){
		?>
		<script type="text/javascript">
			window.close();
		</script>
		<?php
	}

	// Palabra clave para encriptar y desencriptar
	$palabra_clave = "sistemaRadarAB";
	// Algoritmo de encriptación
	$algoritmo = "AES-256-CBC";
	// Vector de inicialización
	$iv = 'AB';
	$cot = $_GET['cot'];
	
	$otra_variable = $_GET['cot'];
	$id_militante_partido = (openssl_decrypt($otra_variable, $algoritmo, $palabra_clave, 0, $iv));	

	$sql = "SELECT 
			mp.clave,
			mp.folio,
			mp.fecha,
			mp.id_partido_legado,
			pl.nombre_corto AS partido_nombre_corto,
			pl.nombre AS partido_nombre,
			pl.logo AS partido_logo,
			pl.color_background AS color_background,
			pl.color_font AS color_font,
			sic.id AS id_seccion_ine_ciudadano,
			sic.codigo_seccion_ine_ciudadano AS codigo_seccion_ine_ciudadano,
			sic.nombre,
			sic.apellido_paterno,
			sic.apellido_materno,
			sic.fecha_nacimiento,
			sic.sexo,
			sic.curp,
			sic.clave_elector,
			s.clave AS seccion,
			mp.telefono,
			mp.whatsapp,
			mp.celular,
			mp.correo_electronico,
			sic.calle,
			sic.num_ext,
			sic.num_int,
			sic.colonia,
			m.municipio
			FROM militantes_partidos mp
			LEFT JOIN partidos_legados pl
			ON mp.id_partido_legado = pl.id
			LEFT JOIN secciones_ine_ciudadanos sic
			ON mp.id_seccion_ine_ciudadano = sic.id
			LEFT JOIN secciones_ine s
			ON sic.id_seccion_ine = s.id
			LEFT JOIN municipios m
			ON sic.id_municipio = m.id
			WHERE mp.id='{$id_militante_partido}'
			LIMIT 1";
	$resultado = $conexion->query($sql);
	$data=$resultado->fetch_assoc();
	foreach ($data as $key => $value) {
		if($value==''){
			$data[$key] = '-';
		}
	}

	$pagina_inicio = file_get_contents('../plantillas/expediente.php');
	$css = array(
		"[_Uppercase_]" => "text-transform: uppercase;",
		//"[_Uppercase_]" => "",
	);

	$impresion = array(
		"[__Impresion_Fecha_Hora__]" => $fechaH , 
	);

	if($data['partido_logo']==""){
		$data['partido_logo'] = "configurar_bien.png";
		$data['color_font'] = "white";
		$data['color_background'] = "#191919";
	}else{
		$data['color_background'] = "#".$data['color_background'];
		$data['color_font'] = "#".$data['color_font'];
	}
	$logo = $data['partido_logo'];


	$img_logo='<img src="../../images/logos_partidos/'.$logo.'" height="90px" >';
	$img_logo_page='<img src="../../images/logos_partidos/'.$logo.'" height="40px" >';


	//! Generador QR
	include __DIR__."../../../librerias/phpqrcode/qrlib.php";
	$codigo_seccion_ine_ciudadano = $data['codigo_seccion_ine_ciudadano'];
	$id_seccion_ine_ciudadano = $data['id_seccion_ine_ciudadano'];
	$enlace_actual = 'https://' . $_SERVER['HTTP_HOST'].'/credencialDigital/militante.php?cot='.$codigo_seccion_ine_ciudadano.'&sic='.$id_seccion_ine_ciudadano.'&hex='.$id_militante_partido.'&tipo=digital';

	$size = 4;
	$marge = 1;
	$link = $enlace_actual;
	$level = QR_ECLEVEL_Q;

	$enlace_comprobante = "";

	// Cambia el color del código QR (en este caso, verde)
	$color = array(
		255,  // Rojo
		0,    // Verde
		0     // Azul
	);
	// Captura la imagen del código QR en memoria con el nuevo color
	ob_start();
	QRcode::png($link, NULL, $level, $size, $marge, false, $color, 10, 2);
	$qr_image_data = ob_get_contents();
	ob_end_clean();
	// Ruta de la imagen que deseas insertar en el código QR
	$image_path = __DIR__."../../../images/logos_partidos/".$logo;
	// Abre y carga la imagen
	$image = imagecreatefrompng($image_path);

	// Obtén las dimensiones del código QR
	$qr_width = imagesx(imagecreatefromstring($qr_image_data));
	$qr_height = imagesy(imagecreatefromstring($qr_image_data));

	// Crear una nueva imagen de 100x100 para la imagen del partido
	// Crear una nueva imagen de 100x100 para la imagen del partido con fondo transparente
	$new_image = imagecreatetruecolor(100, 100);
	imagealphablending($new_image, false);
	imagesavealpha($new_image, true);

	$transparent_color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
	imagefill($new_image, 0, 0, $transparent_color);

	// Cargar la imagen del partido con fondo transparente
	$party_image = imagecreatefrompng($image_path);
	imagealphablending($party_image, true);

	// Copiar la imagen del partido en la nueva imagen
	imagecopyresampled($new_image, $party_image, 0, 0, 0, 0, 100, 100, imagesx($party_image), imagesy($party_image));

	// Obtener las dimensiones del código QR
	$qr_width = imagesx(imagecreatefromstring($qr_image_data));
	$qr_height = imagesy(imagecreatefromstring($qr_image_data));

	// Calcular la posición para centrar la imagen en el código QR
	$x = ($qr_width - 100) / 2;
	$y = ($qr_height - 100) / 2;

	// Combinar la imagen reducida con el código QR
	imagecopy($qr_image = imagecreatefromstring($qr_image_data), $new_image, $x, $y, 0, 0, 100, 100);

	// Capturar la imagen combinada en memoria
	ob_start();
	imagepng($qr_image);
	$image_data = ob_get_contents();
	ob_end_clean();

	// Habilitar la transparencia en la imagen final
	imagealphablending($qr_image, false);
	imagesavealpha($qr_image, true);

	// Codificar la imagen combinada en formato base64 para su uso en la etiqueta img
	$image_base64 = base64_encode($image_data);
	$img_qr = '<img style="width: 250px;" src="data:image/png;base64,' . $image_base64 . '" alt="QR Code with Image">';
	

	$partido = array(
		"[__Partido_Logo__]" => $img_logo,
		"[__Partido_Logo_Page__]" => $img_logo_page,
		"[__Partido_Nombre_Corto__]" => $data['partido_nombre_corto'],
		"[__Partido_Nombre__]" => $data['partido_nombre'],
		"[__Partido_Color_Font__]" => $data['color_font'],
		"[__Partido_Color_background__]" => $data['color_background'],
	);
	$documentento = array(
		"[__Documento_Titulo__]" => 'Credencialización',
		"[__Militante_Partido_Clave__]" => $data['clave'],
		"[__Militante_Partido_Folio__]" => $data['folio'],
	);
	$datos_militante_partido = array(
		"[__Militante_Partido_QR__]" => $img_qr,
		"[__Militante_Partido_Sexo__]" => $data['sexo'],
		"[__Militante_Partido_Fecha_Nacimiento__]" => $data['fecha_nacimiento']." [".edadAnos($data['fecha_nacimiento'])." Años]",
		"[__Militante_Partido_Edad__]" => $data['edad'],
		"[__Militante_Partido_Nombre__]" => $data['nombre'],
		"[__Militante_Partido_Apellido_Paterno__]" => $data['apellido_paterno'],
		"[__Militante_Partido_Apellido_Materno__]" => $data['apellido_materno'],
	);
	$datos_contacto = array(
		"[__Militante_Partido_Telefono__]" => $data['telefono'],
		"[__Militante_Partido_Celular__]" => $data['celular'],
		"[__Militante_Partido_Whatsapp__]" => $data['whatsapp'],
		"[__Militante_Partido_Correo_Electronico__]" => $data['correo_electronico'],
	);
	$datos_direccion = array(
		"[__Militante_Partido_Seccion__]" => str_pad($data['seccion'], 4, "0", STR_PAD_LEFT),
		"[__Militante_Partido_Calle__]" => $data['calle'],
		"[__Militante_Partido_Num_Ext__]" => $data['num_ext'],
		"[__Militante_Partido_Num_Int__]" => $data['num_int'],
		"[__Militante_Partido_Colonia__]" => $data['colonia'],
		"[__Militante_Partido_Municipio__]" => $data['municipio'],
	);
	$datos_identificacion = array(
		"[__Militante_Partido_CURP__]" => $data['curp'],
		"[__Militante_Partido_Clave_Elector__]" => $data['clave_elector'],
	);

	$bodyHTML = strtr($pagina_inicio, array_merge($css,$impresion,$partido,$documentento,$datos_militante_partido,$datos_contacto,$datos_direccion,$datos_identificacion));
	$bodyHTML;
	
	
	

	$length=6; 
	$mk_id=time()*2*36*12;
	$gen_id3 = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890123456789"), 0, $length); 
	$filename = 'ComprobanteCredencializacion-_'.strtr($data['clave_elector'], " ", "_").date("Ymd-His").'-'.$gen_id3.$mk_id.'.pdf';
	require_once('../../librerias/pdf/mpdf.php');
	//$mpdf = new mPDF(); 
	$mpdf->showImageErrors = true;
	//$mpdf -> writeHTML($bodyHTML);
	//$mpdf -> Output('reporte.pdf','I');
	$mpdf = new mPDF('c','A4'); 
	$mpdf->SetProtection(false);
	$mpdf->debug = true;
	$mpdf->SetTitle("Comprobante Credencialización - ".$data['clave_elector']);
	$mpdf->SetAuthor("Ideas AB");
	$mpdf->shrink_tables_to_fit = 1;
	//$mpdf->SetWatermarkText("Reporte");
	//$mpdf->showWatermarkText = true;
	//$mpdf->SetWatermarkImage("data:image/jpg;base64, ".$kad_photo);
	$mpdf->showWatermarkImage = true; 
	$mpdf->watermark_font = 'Helvetica Neue,Helvetica,Arial,sans-serif';
	$mpdf->watermarkTextAlpha = 0.001;
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->WriteHTML($bodyHTML);
	$mpdf->Output($filename, 'I');
	/*
	'D': download the PDF file
	'I': serves in-line to the browser
	'S': returns the PDF document as a string
	'F': save as file $file_out
	
	<barcode code="MECARD:N:Norfi, Carrodeguas;TEL:5358167785;EMAIL:info@norfipc.com;URL:http://norfipc.com;" type="QR" class="barcode" size="1" error="Q" />

	
