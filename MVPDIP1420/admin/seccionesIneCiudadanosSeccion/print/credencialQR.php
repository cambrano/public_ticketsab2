<?php
	ini_set('max_execution_time', 60000);
	@session_start();
	
	include __DIR__."../../../functions/security.php";
	include __DIR__."../../../functions/configuracion.php";
	include __DIR__."../../../functions/timemex.php";
	include __DIR__."../../../functions/secciones_ine_ciudadanos.php";
	include __DIR__."../../../functions/localidades.php";
	include __DIR__."../../../functions/municipios.php";
	include __DIR__."../../../functions/efs.php";
	include __DIR__."../../../functions/genid.php";
	
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
	$otra_variable = $_GET["cot"];
	$id_seccion_ine_ciudadano = urlencode(openssl_decrypt($otra_variable, $algoritmo, $palabra_clave, 0, $iv));

	$sql = "SELECT  
			sic.id AS id_seccion_ine_ciudadano,
			sic.codigo_seccion_ine_ciudadano AS codigo_seccion_ine_ciudadano,
			sic.nombre,
			sic.apellido_paterno,
			sic.apellido_materno,
			sic.fecha_nacimiento,
			sic.sexo,
			sic.curp,
			sic.clave_elector,
			sic.clave AS clave_ciudadano,
			sic.folio,
			sic.calle,
			sic.num_ext,
			sic.num_int,
			sic.colonia,
			m.municipio
			FROM secciones_ine_ciudadanos sic
			LEFT JOIN municipios m
			ON sic.id_municipio = m.id
			WHERE sic.id='{$id_seccion_ine_ciudadano}'
			LIMIT 1";
	$resultado = $conexion->query($sql);
	$data=$resultado->fetch_assoc();
	foreach ($data as $key => $value) {
		if($value==''){
			$data[$key] = '-';
		}
	}


	//! Generador QR
	include __DIR__."../../../librerias/phpqrcode/qrlib.php";
	$codigo_seccion_ine_ciudadano = $data['codigo_seccion_ine_ciudadano'];
	$id_seccion_ine_ciudadano = $data['id_seccion_ine_ciudadano'];

	///Encriptamos el id

	$mostrarImagenBase64 = mostrarImagenBase64('logo_principal.png');
	$image = "data:image/png;base64,".$mostrarImagenBase64;

	$enlace_actual = $codigo_seccion_ine_ciudadano.'-'.$gen_id5.$id_seccion_ine_ciudadano.$gen_idSinNumero;
	$size = 200;
	$marge = 2;
	$link = $enlace_actual;
	$level = QR_ECLEVEL_Q;
	$enlace_comprobante = "";
	// Cambia el color del código QR (en este caso, verde)
	$color = array(
		0,  // Rojo
		0,    // Verde
		0     // Azul
	);
	// Captura la imagen del código QR en memoria con el nuevo color
	ob_start();
	QRcode::png($link, NULL, $level, $size, $marge, false, $color, 300, 2);
	$qr_image_data = ob_get_contents();
	ob_end_clean();
	// Codificar la imagen combinada en formato base64 para su uso en la etiqueta img
	$image_base64 = base64_encode($qr_image_data);
	$img_qr = '<img style="width: 121px;" src="data:image/png;base64,' . $image_base64 . '" alt="QR Code with Image">';


	$pagina_inicio = file_get_contents('../plantillas/credencialQR.php');
	$data['color_font'] = "black";
	$data['color_background'] = "#FFFFFF";
	$css = array(
		"[_Uppercase_]" => "text-transform: uppercase;",
		"[__Partido_Color_Font__]" => $data['color_font'],
		"[__Partido_Color_background__]" => $data['color_background'],
		//"[_Uppercase_]" => "",
	);
	
	$img_logo='<img src="'.$image.'" height="50px" >';
	$empresa = array(
		"[__Empresa_Logo__]" => $img_logo,
		"[__Empresa_Nombre__]" => $configuracion['nombre'],
		"[__Empresa_Slogan__]" => $configuracion['slogan'],
	);
	$datos_ciudadano = array(
		"[__Ciudadano_Folio__]" => $data['folio'],
		"[__Ciudadano_Clave__]" => $data['clave_ciudadano'],
		"[__Ciudadano_Tipo_Ciudadano__]" => $data['tipo_ciudadano'],
		"[__Ciudadano_Categorias__]" => $data['categorias'],
		"[__Ciudadano_Sexo__]" => $data['sexo'],
		"[__Ciudadano_Fecha_Nacimiento_Solo__]" => $data['fecha_nacimiento'],
		"[__Ciudadano_Edad__]" => $data['edad'],
		"[__Ciudadano_Nombre__]" => $data['nombre'],
		"[__Ciudadano_Apellido_Paterno__]" => $data['apellido_paterno'],
		"[__Ciudadano_Apellido_Materno__]" => $data['apellido_materno'],
		"[__Ciudadano_Colonia__]" => $data['colonia'],
		"[__Ciudadano_Municipio__]" => $data['municipio'],
		"[__Ciudadano_CURP__]" => $data['curp'],
		"[__Ciudadano_QR__]" => $img_qr,
	);

	

	$bodyHTML = strtr($pagina_inicio, array_merge(
		$css,
		$empresa,
		$datos_ciudadano
	));
	$bodyHTML;
	
	
	

	$length=6; 
	$mk_id=time()*2*36*12;
	$gen_id3 = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890123456789"), 0, $length); 
	$filename = 'Credencial-_'.strtr($data['clave_elector'], " ", "_").date("Ymd-His").'-'.$gen_id3.$mk_id.'.pdf';
	require_once('../../librerias/pdf/mpdf.php');
	// Inicializar mPDF
	$mpdf = new mPDF('c', array(81.0, 50.0)); // Tamaño personalizado para tarjeta PVC

	// Configurar propiedades
	$mpdf->showImageErrors = true;
	$mpdf->SetProtection(false);
	$mpdf->debug = true;
	$mpdf->SetTitle("Credencial Frente - " . $data['clave_elector']);
	$mpdf->SetAuthor("Ideas AB");
	$mpdf->shrink_tables_to_fit = 1;
	$mpdf->showWatermarkImage = true; 
	$mpdf->watermark_font = 'Helvetica Neue,Helvetica,Arial,sans-serif';
	$mpdf->watermarkTextAlpha = 0.001;
	$mpdf->SetDisplayMode('fullpage');
	// Establecer el tamaño de la página como una tarjeta PVC estándar
	//$mpdf->SetPageSize($anchoTarjetaPVC, $altoTarjetaPVC);
	$mpdf->WriteHTML($bodyHTML);
	$mpdf->Output($filename, 'I');
	/*
	'D': download the PDF file
	'I': serves in-line to the browser
	'S': returns the PDF document as a string
	'F': save as file $file_out
	
	<barcode code="MECARD:N:Norfi, Carrodeguas;TEL:5358167785;EMAIL:info@norfipc.com;URL:http://norfipc.com;" type="QR" class="barcode" size="1" error="Q" />

	
