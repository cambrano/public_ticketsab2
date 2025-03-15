<?php
	ini_set('max_execution_time', 60000);
	@session_start(); 
	
	include __DIR__."../../../functions/security.php";
	include __DIR__."../../../functions/configuracion.php";
	include __DIR__."../../../functions/timemex.php";
	include __DIR__."../../../functions/secciones_ine_ciudadanos.php";
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
	$id_seccion_ine_ciudadano = (openssl_decrypt($otra_variable, $algoritmo, $palabra_clave, 0, $iv));

	$sql = "SELECT 
				sc.id,
				sc.clave,
				sc.folio,
				sc.nombre_completo,
				sc.fecha_nacimiento,
				sc.nombre,
				sc.apellido_paterno,
				sc.apellido_materno,
				sc.sexo,
				(SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = sc.id_tipo_ciudadano ) tipo_ciudadano,
				(	SELECT 
						GROUP_CONCAT(tcc.nombre) 
					FROM secciones_ine_ciudadanos_categorias scc 
					LEFT JOIN tipos_categorias_ciudadanos tcc
					ON tcc.id = scc.id_tipo_categoria_ciudadano
					WHERE scc.id_seccion_ine_ciudadano = sc.id 
					LIMIT 1 
				) categorias,
				sc.telefono,
				sc.celular,
				sc.whatsapp,
				sc.correo_electronico,
				sc.calle,
				sc.num_ext,
				sc.num_int,
				sc.colonia,
				(SELECt lc.localidad FROM localidades lc WHERE lc.id = sc.id_localidad) localidad,
				(SELECt m.municipio FROM municipios m WHERE m.id = sc.id_municipio) municipio,
				sc.latitud,
				sc.longitud,
				sc.curp,
				sc.clave_elector,
				sc.vigencia,
				IF (s.tipo=0,'Rural','Urbana') tipo_seccion,
				s.numero AS seccion,
				sc.manzana,
				sc.distancia_km_r,
				s.clave_distrito_local,
				s.clave_distrito_federal,
				(SELECT COUNT(*) FROM secciones_ine_ciudadanos_seguimientos scg WHERE scg.id_seccion_ine_ciudadano = sc.id) total_seguimientos,
				(SELECT COUNT(*) FROM secciones_ine_ciudadanos_programas_apoyos scg WHERE scg.id_seccion_ine_ciudadano = sc.id) total_programas_sociales,
				(SELECT COUNT(*) FROM secciones_ine_ciudadanos_giras scg WHERE scg.id_seccion_ine_ciudadano = sc.id) total_participacion_giras,
				(SELECT COUNT(*) FROM secciones_ine_ciudadanos_encuestas scg WHERE scg.id_seccion_ine_ciudadano = sc.id) total_encuestas
			FROM secciones_ine_ciudadanos sc
			LEFT JOIN secciones_ine s
			ON sc.id_seccion_ine = s.id
			WHERE sc.id='{$id_seccion_ine_ciudadano}'
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


	

	$img_logo='<img src="../../ftpFiles/files/logo_principal.png" height="90px" >';
	$img_logo_page='<img src="../../ftpFiles/files/logo_principal.png" height="40px" >';
	$empresa = array(
		"[__Empresa_Logo__]" => $img_logo,
		"[__Empresa_Logo_Page__]" => $img_logo_page,
		"[__Empresa_Nombre__]" => $configuracion['nombre'],
		"[__Empresa_Slogan__]" => $configuracion['slogan'],
	);
	$documentento = array(
		"[__Documento_Titulo__]" => 'Expediente Digital',
		"[__Ciudadano_Clave__]" => $data['clave'],
		"[__Ciudadano_Folio__]" => $data['folio'],
	);
	$datos_ciudadano = array(
		"[__Ciudadano_Tipo_Ciudadano__]" => $data['tipo_ciudadano'],
		"[__Ciudadano_Categorias__]" => $data['categorias'],
		"[__Ciudadano_Sexo__]" => $data['sexo'],
		"[__Ciudadano_Fecha_Nacimiento__]" => $data['fecha_nacimiento']." [".edadAnos($data['fecha_nacimiento'])." Años]",
		"[__Ciudadano_Edad__]" => $data['edad'],
		"[__Ciudadano_Nombre__]" => $data['nombre'],
		"[__Ciudadano_Apellido_Paterno__]" => $data['apellido_paterno'],
		"[__Ciudadano_Apellido_Materno__]" => $data['apellido_materno'],
	);
	$datos_contacto = array(
		"[__Ciudadano_Telefono__]" => $data['telefono'],
		"[__Ciudadano_Celular__]" => $data['celular'],
		"[__Ciudadano_Whatsapp__]" => $data['whatsapp'],
		"[__Ciudadano_Correo_Electronico__]" => $data['correo_electronico'],
	);
	$datos_direccion = array(
		"[__Ciudadano_Calle__]" => $data['calle'],
		"[__Ciudadano_Num_Ext__]" => $data['num_ext'],
		"[__Ciudadano_Num_Int__]" => $data['num_int'],
		"[__Ciudadano_Colonia__]" => $data['colonia'],
		"[__Ciudadano_Localidad__]" => $data['localidad'],
		"[__Ciudadano_Municipio__]" => $data['municipio'],
		"[__Ciudadano_GPS__]" => $data['latitud'].','.$data['longitud'],
	);
	$datos_identificacion = array(
		"[__Ciudadano_CURP__]" => $data['curp'],
		"[__Ciudadano_Clave_Elector__]" => $data['clave_elector'],
		"[__Ciudadano_Clave_Elector_Vigencia__]" => $data['vigencia'],
	);
	$datos_territorial = array(
		"[__Ciudadano_Tipo_Seccion__]" => $data['tipo_seccion'],
		"[__Ciudadano_Seccion__]" => $data['seccion'],
		"[__Ciudadano_Manzana__]" => $data['manzana'],
		"[__Ciudadano_Distancia_A_Seccion__]" => $data['distancia_km_r'],
		"[__Ciudadano_DL__]" => $data['clave_distrito_local'],
		"[__Ciudadano_DF__]" => $data['clave_distrito_federal'],
	);
	
	$datos_mas_informacion = array(
		"[__Total_Seguimientos__]" => $data['total_seguimientos'],
		"[__Total_Programas_Sociales__]" => $data['total_programas_sociales'],
		"[__Total_Participaciones_Giras__]" => $data['total_participacion_giras'],
		"[__Total_Encuestas__]" => $data['total_encuestas'],
	);
	

	//! Seguimientos
	$sql = "SELECT fecha,hora,asunto,observaciones FROM secciones_ine_ciudadanos_seguimientos WHERE id_seccion_ine_ciudadano = '{$id_seccion_ine_ciudadano}' ORDER BY fecha_hora ASC ";
	$resultado = $conexion->query($sql);
	$tabla_seguimientos = '<table class="table_datos">';
	$tabla_seguimientos .= "<tr><td colspan='5' style='background-color:black;color:white;font-size:8px;padding:5px' >Seguimientos</td></tr>";
	$tabla_seguimientos .= "<tr><th>#</th><th>Fecha</th><th>Hora</th><th>Asunto</th><th>Observaciones</th></tr>";
	$num = 1;
	while($row=$resultado->fetch_assoc()){
		$tabla_seguimientos .= "<tr>";
		$tabla_seguimientos .= "<td>".$num."</td>";
		$tabla_seguimientos .= "<td>".fechaNormal_ES($row['fecha'])."</td>";
		$tabla_seguimientos .= "<td>".convertidorAMPM($row['hora'])."</td>";
		$tabla_seguimientos .= "<td>".$row['asunto']."</td>";
		$tabla_seguimientos .= "<td>".nl2br($row['observaciones'])."</td>";
		$tabla_seguimientos .= "</tr>";
		$num ++;
	}
	$tabla_seguimientos .= "</table>";
	if($num==1){
		$tabla_seguimientos = '';
	}
	//! Programas Apoyos
	$sql = "SELECT 
				sicpa.clave,
				sicpa.folio,
				pa.nombre,
				sicpa.fecha,
				sicpa.hora,
				sicpa.observaciones 
			FROM secciones_ine_ciudadanos_programas_apoyos sicpa 
			LEFT JOIN programas_apoyos pa 
			ON pa.id = sicpa.id_programa_apoyo  
			WHERE sicpa.id_seccion_ine_ciudadano = '{$id_seccion_ine_ciudadano}' 
			ORDER BY sicpa.fecha_hora ASC ";
	$resultado = $conexion->query($sql);
	$tabla_programa_apoyos = '<table class="table_datos">';
	$tabla_programa_apoyos .= "<thead>";
	$tabla_programa_apoyos .= "<tr style='page-break-inside: avoid; keep-together: always;'><td colspan='7' style='background-color:black;color:white;font-size:8px;padding:5px;' >Programas Sociales</td></tr>";
	$tabla_programa_apoyos .= "<tr style='page-break-inside: avoid; keep-together: always;'><th>#</th><th>Fecha</th><th>Hora</th><th>Clave</th><th>Folio</th><th>Programa</th><th>Observaciones</th></tr>";
	$num = 1;
	$tabla_programa_apoyos .= "</thead>";
	while($row=$resultado->fetch_assoc()){
		$tabla_programa_apoyos .= "<tr>";
		$tabla_programa_apoyos .= "<td>".$num."</td>";
		$tabla_programa_apoyos .= "<td>".fechaNormal_ES($row['fecha'])."</td>";
		$tabla_programa_apoyos .= "<td>".convertidorAMPM($row['hora'])."</td>";
		$tabla_programa_apoyos .= "<td>".$row['clave']."</td>";
		$tabla_programa_apoyos .= "<td>".$row['folio']."</td>";
		$tabla_programa_apoyos .= "<td>".$row['nombre']."</td>";
		$tabla_programa_apoyos .= "<td>".nl2br($row['observaciones'])."</td>";
		$tabla_programa_apoyos .= "</tr>";
		$num ++;
	}
	$tabla_programa_apoyos .= "</table>";
	if($num==1){
		$tabla_programa_apoyos = '';
	}

	//! Giras
	$sql = "SELECT 
			sicg.fecha,
			sicg.hora,
			sicg.clave,
			sicg.folio,
			sicg.observaciones,
			sig.nombre,
			s.clave as seccion
			FROM secciones_ine_ciudadanos_giras sicg
			LEFT JOIN secciones_ine_giras sig
			ON sig.id = sicg.id_seccion_ine_gira
			LEFT JOIN secciones_ine s
			ON sig.id_seccion_ine = s.id
			WHERE sicg.id_seccion_ine_ciudadano = '{$id_seccion_ine_ciudadano}' 
			ORDER BY sicg.fecha_hora ASC ";
	$resultado = $conexion->query($sql);
	$tabla_programa_giras = '<table class="table_datos">';
	$tabla_programa_giras .= "<tr><td colspan='8' style='background-color:black;color:white;font-size:8px;padding:5px' >Participaciones en Giras</td></tr>";
	$tabla_programa_giras .= "<tr><th>#</th><th>Fecha</th><th>Hora</th><th>Clave</th><th>Folio</th><th>Sección</th><th>Gira</th><th>Observaciones</th></tr>";
	$num = 1;
	while($row=$resultado->fetch_assoc()){
		$tabla_programa_giras .= "<tr>";
		$tabla_programa_giras .= "<td>".$num."</td>";
		$tabla_programa_giras .= "<td>".fechaNormal_ES($row['fecha'])."</td>";
		$tabla_programa_giras .= "<td>".convertidorAMPM($row['hora'])."</td>";
		$tabla_programa_giras .= "<td>".$row['clave']."</td>";
		$tabla_programa_giras .= "<td>".$row['folio']."</td>";
		$tabla_programa_giras .= "<td>".str_pad($row['seccion'], 4, "0", STR_PAD_LEFT)."</td>";
		$tabla_programa_giras .= "<td>".$row['nombre']."</td>";
		$tabla_programa_giras .= "<td>".nl2br($row['observaciones'])."</td>";
		$tabla_programa_giras .= "</tr>";
		$num ++;
	}
	$tabla_programa_giras .= "</table>";
	if($num==1){
		$tabla_programa_giras = '';
	}


	//! Militantes Partidos
	$sql = "
			SELECT 
				clave,folio,fecha,hora,whatsapp,telefono,celular,correo_electronico,observaciones,status
			FROM militantes_partidos 
			WHERE id_seccion_ine_ciudadano = '{$id_seccion_ine_ciudadano}' 
			order by fecha_hora DESC";
	$resultado = $conexion->query($sql);
	$tabla_militante_partido = '<table class="table_datos">';
	$tabla_militante_partido .= "<tr><td colspan='10' style='background-color:black;color:white;font-size:8px;padding:5px' >Registro Partido</td></tr>";
	$tabla_militante_partido .= "<tr><th>#</th><th>Fecha</th><th>Hora</th><th>Clave</th><th>Folio</th><th>Whatsapp</th><th>Teléfono</th><th>Celular</th><th>Correo Electrónico</th><th>Observaciones</th></tr>";
	$num = 1;
	while($row=$resultado->fetch_assoc()){
		$tabla_militante_partido .= "<tr>";
		$tabla_militante_partido .= "<td>".$num."</td>";
		$tabla_militante_partido .= "<td>".fechaNormal_ES($row['fecha'])."</td>";
		$tabla_militante_partido .= "<td>".convertidorAMPM($row['hora'])."</td>";
		$tabla_militante_partido .= "<td>".$row['clave']."</td>";
		$tabla_militante_partido .= "<td>".$row['folio']."</td>";
		$tabla_militante_partido .= "<td>".$row['whatsapp']."</td>";
		$tabla_militante_partido .= "<td>".$row['telefono']."</td>";
		$tabla_militante_partido .= "<td>".$row['celular']."</td>";
		$tabla_militante_partido .= "<td>".$row['correo_electronico']."</td>";
		$tabla_militante_partido .= "<td>".nl2br($row['observaciones'])."</td>";
		$tabla_militante_partido .= "</tr>";
		$num ++;
	}
	$tabla_militante_partido .= "</table>";
	if($num==1){
		$tabla_militante_partido = '';
	}

	//! Documentos Oficiales
	$sql ="SELECT
			docof.id,
			docof.fecha_emision,
			docof.fecha_vigencia,
			docof.id_seccion_ine_ciudadano,
			docof.tipo,
			docofim.name,
			docofim.type,
			docofim.tipo_imagen
		FROM documentos_oficiales docof
		LEFT JOIN documentos_oficiales_images docofim
		ON docofim.id_documento_oficial = docof.id
		WHERE docofim.id_seccion_ine_ciudadano = '{$id_seccion_ine_ciudadano}' ";
	$resultado = $conexion->query($sql);
	$tabla_documentos_oficiales = '<table class="table_datos">';
	$tabla_documentos_oficiales .= "<thead>";
	$tabla_documentos_oficiales .= "<tr style='page-break-inside: avoid; keep-together: always;' ><td colspan='8' style='background-color:black;color:white;font-size:8px;padding:5px' >Documentos Oficiales</td></tr>";
	$tabla_documentos_oficiales .= "<tr style='page-break-inside: avoid; keep-together: always;' ><th>#</th><th>Tipo</th><th>Emision</th><th>Vigencia</th></tr>";
	$tabla_documentos_oficiales .= "</thead>";
	$num = 1;
	// frente = 0
	// atras = 1
	// otros = 2
	while($row=$resultado->fetch_assoc()){
		if($row['tipo_imagen']=='frente'){
			$tipo_imagen = 0;
		}
		if($row['tipo_imagen']=='atras'){
			$tipo_imagen = 1;
		}
		if($row['tipo_imagen']=='otros'){
			$tipo_imagen = 2;
		}
		// Verifica si el tipo de imagen comienza con "image/"
		if (strpos($row['type'], 'image/') === 0) {
			$row['show'] =1;
		} else {
			$row['show'] =0;
		}
		$documentos_tipo[$row['id']]['datos'] = $row; 
		$documentos_tipo[$row['id']]['files'][$tipo_imagen]['name']=$row['name'];
		$documentos_tipo[$row['id']]['files'][$tipo_imagen]['show']=$row['show'];
	}
	foreach ($documentos_tipo as $key => $value) {
		$tabla_documentos_oficiales .= "<tr>";
		$tabla_documentos_oficiales .= "<td>".$num."</td>";
		$tabla_documentos_oficiales .= "<td>".strtoupper($value['datos']['tipo'])."</td>";
		$tabla_documentos_oficiales .= "<td>".$value['datos']['fecha_emision']."</td>";
		$tabla_documentos_oficiales .= "<td>".$value['datos']['fecha_vigencia']."</td>";
		$tabla_documentos_oficiales .= "</tr>";
		$tabla_documentos_oficiales .= "<tr><td colspan='4'>";
		$tabla_documentos_oficiales .= "<center>";
		foreach ($value['files'] as $tipo => $name) {
			if($name['show']==1){
				if($value['datos']['tipo']=='ine'){
					$tabla_documentos_oficiales .= '<img style="border-radius: 8px;padding: 5px;" src="../../ftpFiles/files/'.$name['name'].'" width="220">';
				}else{
					$tabla_documentos_oficiales .= '<img style="border-radius: 8px;padding: 5px;" src="../../ftpFiles/files/'.$name['name'].'" width="560">';
				}
			}
		}
		$tabla_documentos_oficiales .= "</center>";
		$tabla_documentos_oficiales .= "</td></tr>";
		$num ++;
	}
	
	$tabla_documentos_oficiales .= "</table>";
	if($num==1){
		$tabla_documentos_oficiales = '';
	}

	$documentos_oficiales = array(
		"[__Tabla_Documentos_Oficiales__]" => $tabla_documentos_oficiales,
	);


	$datos_tablas = array(
		"[__Tabla_Seguimientos__]" => $tabla_seguimientos,
		"[__Tabla_Programas_Apoyos__]" => $tabla_programa_apoyos,
		"[__Tabla_Giras__]" => $tabla_programa_giras,
		"[__Tabla_Militantes_Partidos__]" => $tabla_militante_partido,
	);

	$bodyHTML = strtr($pagina_inicio, array_merge($css,$impresion,$empresa,$documentento,$datos_ciudadano,$datos_contacto,$datos_direccion,$datos_identificacion,$datos_territorial,$datos_mas_informacion,$datos_tablas,$documentos_oficiales));
	//echo $bodyHTML;
	//die;

	$length=6; 
	$mk_id=time()*2*36*12;
	$gen_id3 = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890123456789"), 0, $length); 
	$filename = 'ExpedienteDigital_-_'.strtr($data['nombre_completo'], " ", "_").date("Ymd-His").'-'.$gen_id3.$mk_id.'.pdf';
	require_once('../../librerias/pdf/mpdf.php');
	//$mpdf = new mPDF(); 
	$mpdf->showImageErrors = true;
	//$mpdf -> writeHTML($bodyHTML);
	//$mpdf -> Output('reporte.pdf','I');
	$mpdf = new mPDF('c','A4'); 
	$mpdf->SetProtection(false);
	$mpdf->debug = true;
	$mpdf->SetTitle("Expediente Digital - ".$data['nombre_completo']);
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

	
