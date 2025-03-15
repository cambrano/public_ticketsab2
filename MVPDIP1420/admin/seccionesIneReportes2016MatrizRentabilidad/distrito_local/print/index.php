<?php
	ini_set('max_execution_time', 60000);
	@session_start(); 
	include __DIR__."../../../../functions/security.php";
	include __DIR__."../../../../functions/configuracion.php";
	include __DIR__."../../../../functions/timemex.php";
	include __DIR__."../../../../functions/efs.php";

	$configuracion = configuracionDatos();


	@session_start();
	include __DIR__."../../../../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','cartografias_distritos_locales_2016',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['download'] || $moduloAccionPermisos['all']){
		$pageService=$_GET['cot'];
		$_COOKIE['pageService'];
	}else{
		$pageService = "";
	}

	if($pageService=="" || $_COOKIE['pageService'] != $pageService ){
		?>
		<script type="text/javascript">
			window.close();
		</script>
		<?php
		die;
	}else{
		$_COOKIE['pageService'];
	}
	if($id_distrito_local==""){
		include __DIR__."../../../../functions/tool_xhpzab.php";
		$id_distrito_local = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	}
	$pagina_inicio = file_get_contents('../plantillas/reporteMatrizRentabilidad.php');
	$css = array(
		"[_Uppercase_]" => "text-transform: uppercase;",
		//"[_Uppercase_]" => "",
	);

	$impresion = array(
		"[__Impresion_Fecha_Hora__]" => $fechaH , 
	);
	$rutaEfs = rutaEfs();
	$archivo_json = $rutaEfs . 'datos_generales_distrito_local_2016_'.$id_distrito_local.'-'.$_COOKIE["id_usuario"].'.json';
	if (file_exists($archivo_json)) {
		// Lee el contenido del archivo JSON
		$json_data = file_get_contents($archivo_json);

		// Decodifica el JSON en un array asociativo
		$datos_generales = json_decode($json_data, true);

		if ($datos_generales === null) {
			// Manejar un error en la decodificación si es necesario
			//echo "Error al decodificar el JSON";
		} else {
			// Ahora tienes el array $datos_generales disponible para su uso
			//print_r($datos_generales);
		}
	} else {
		//echo "El archivo JSON no existe en la ruta especificada";
	}
	$archivo_json = $rutaEfs . 'datos_partidos_distrito_local_2016_'.$id_distrito_local.'-'.$_COOKIE["id_usuario"].'.json';
	if (file_exists($archivo_json)) {
		// Lee el contenido del archivo JSON
		$json_data = file_get_contents($archivo_json);

		// Decodifica el JSON en un array asociativo
		$datos_partidos = json_decode($json_data, true);

		if ($datos_partidos === null) {
			// Manejar un error en la decodificación si es necesario
			//echo "Error al decodificar el JSON";
		} else {
			// Ahora tienes el array $datos_partidos disponible para su uso
			//print_r($datos_partidos);
		}
	} else {
		//echo "El archivo JSON no existe en la ruta especificada";
	}
	$archivo_json = $rutaEfs . 'datos_secciones_ine_distrito_local_2016_'.$id_distrito_local.'-'.$_COOKIE["id_usuario"].'.json';
	if (file_exists($archivo_json)) {
		// Lee el contenido del archivo JSON
		$json_data = file_get_contents($archivo_json);

		// Decodifica el JSON en un array asociativo
		$datos_secciones_ine = json_decode($json_data, true);

		if ($datos_secciones_ine === null) {
			// Manejar un error en la decodificación si es necesario
			//echo "Error al decodificar el JSON";
		} else {
			// Ahora tienes el array $datos_secciones_ine disponible para su uso
			//print_r($datos_secciones_ine);
		}
	} else {
		//echo "El archivo JSON no existe en la ruta especificada";
	}


	$mostrarImagenBase64 = mostrarImagenBase64('logo_principal.png');
	$image = "data:image/png;base64,".$mostrarImagenBase64;
	$img_logo='<img src="'.$image.'" height="90px" >';
	$img_logo_page='<img src="'.$image.'" height="40px" >';
	$empresa = array(
		"[__Empresa_Logo__]" => $img_logo,
		"[__Empresa_Logo_Page__]" => $img_logo_page,
		"[__Empresa_Nombre__]" => $configuracion['nombre'],
		"[__Empresa_Slogan__]" => $configuracion['slogan'],
	);

	if($datos_generales['territorio_tipo'] == 0){
		$territorio_tipo = 'Municipio';
	}elseif ($datos_generales['territorio_tipo'] == 1) {
		$territorio_tipo = 'Distrito Local';
	}elseif ($datos_generales['territorio_tipo'] == 2) {
		$territorio_tipo = 'Distrito Federal';
	}elseif ($datos_generales['territorio_tipo'] == 3) {
		$territorio_tipo = 'Gobernador';
	}else{
		$territorio_tipo = 'Senador';
	}

	$documentento = array(
		"[__Documento_Titulo__]" => 'Análisis de Territorio',
		"[__Documento_Tipo_Territorio__]" => $territorio_tipo,
		"[__Documento_Territorio_Nombre__]" => $datos_generales['territorio_nombre'],
		"[__Documento_Eleccion__]" => $datos_generales['ano'],
	);

	$totales = array(
		"[__Lista_Nominal__]" => number_format($datos_generales['total_lista_nominal'], 0, '.', ','),
		"[__Secciones__]" => number_format($datos_generales['secciones'], 0, '.', ','),
		"[__Casillas__]" => number_format($datos_generales['casillas'], 0, '.', ','),
		"[__Votos_Validos__]" => number_format($datos_generales['votos_validos'], 0, '.', ','),
		"[__Votos_Nulos__]" => number_format($datos_generales['votos_nulos'], 0, '.', ','),
		"[__Votos_Can_Nreg__]" => number_format($datos_generales['votos_can_nreg'], 0, '.', ','),
		"[__Votos_Totales__]" => number_format($datos_generales['votos_totales'], 0, '.', ','),
		"[__Participacion_Ciudadano__]" => number_format($datos_generales['participacion_ciudadana'], 2, '.', ','),

		"[__Apoyo_Programas__]" => number_format($datos_generales['apoyos_programas'], 0, '.', ','),
		"[__Acciones_Obras__]" => number_format($datos_generales['acciones_obras'], 0, '.', ','),
		"[__Ciudadanos_Totales__]" => number_format($datos_generales['ciudadanos_totales'], 0, '.', ','),
		"[__Funcionarios__]" => number_format($datos_generales['funcionarios'], 0, '.', ','),
		"[__Militantes__]" => number_format($datos_generales['militantes'], 0, '.', ','),
		"[__Grupos_Intereses__]" => number_format($datos_generales['grupos_interes'], 0, '.', ','),
	);



	if($datos_partidos['orden_votos_individual']['sistema'] != ''){
		$espacio_sistema = '33';
		$float = 'left';
		$nombre_sistema = $datos_partidos['orden_votos_individual']['sistema'];
		$datos_sistema= $datos_partidos['partidos'][$nombre_sistema];
		$div_sistema ="
			<div style='float: left;width:".$espacio_sistema."%; margin: 0px 2px 0px 2px'>
				<table style='table-layout: fixed; width: 100%' cellspacing='0' cellpadding='0'border='1'>
					<thead>
						<tr>
							<tr>
								<td style='text-align: center;padding: 10px;background-color:#".$datos_sistema['color_background'].";color: white;font-weight: bold;'
									colspan='1'>".$datos_sistema['nombre_corto']."</td>
								<td style='text-align: center;padding: 10px;background-color:#".$datos_sistema['color_background'].";color: white;font-weight: bold;'
									colspan='1'>
									<img src='../../../images/logos_partidos/".$datos_sistema['logo']."' style='width: 10%'><br>
									<br>
									<br>
								</td>
							</tr>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style='text-align: center' colspan=2>Votos</td>
						</tr>
						<tr>
							<td style='text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white'>
								<font class='fontLabelReporte'>Individual:</font>
							</td>
							<td style='text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white'>
								<font class='fontDataReporte' style='font-size: 12px'>
									".number_format($datos_sistema['votos_individual'], 0, '.', ',')."
								</font>
							</td>
						</tr>
						<tr>
							<td colspan='2' style='text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.1); border-bottom: 1px solid white; height: 135px'>
								<font class='fontLabelReporte'>Coaliciones:</font>";
								
									if(!empty($datos_sistema['coaliciones_orden_votos_individual'])){
										unset($datos_sistema['coaliciones_orden_votos_individual'][$datos_sistema['nombre_corto']]);
										
										$div_sistema .="<table style='width: 100%;text-align: left;font-size: 10px;table-layout: fixed;border-collapse: collapse;' Cellspacing='0' cellpadding='0' >
											<tr>
												<td colspan='2' style='border:1px solid;padding: 2px;background-color: #dee3ed'>Partido</td>
												<td style='border:1px solid;padding: 2px;background-color: #dee3ed'>Votos</td>
												<td style='border:1px solid;padding: 2px;background-color: #dee3ed'>Diff.</td>
											</tr>";

											foreach ($datos_sistema['coaliciones_orden_votos_individual'] as $partido => $votos) {
												$div_sistema .="<tr>
															<td style='border:1px solid;padding: 2px 2px 2px 5px;text-align:center'>
																<img src='../../../images/logos_partidos/".$datos_partidos['partidos'][$partido]['logo']."' style='width: 8%' >
															</td>
															<td style='border:1px solid;padding: 2px 2px 2px 5px'>".$partido."</td>
															<td style='border:1px solid;padding: 2px 2px 2px 5px'>".number_format($votos, 0, '.', ',')."</td>
															<td style='border:1px solid;padding: 2px 2px 2px 5px'>".number_format($datos_sistema['votos_individual']-$votos, 0, '.', ',')."</td>
														</tr>";
											}
										$div_sistema .="</table>";
										
									}else{
										$div_sistema .="<font class='fontDataReporte' style='font-size: 12px'>No tiene. </font>";
									}
							$div_sistema .="</td>
						</tr>
						<tr>
							<td style='text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white'>
								<font class='fontLabelReporte'>Coalición Individual:</font>
							</td>
							<td
								style='text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white'>
								<font class='fontDataReporte' style='font-size: 12px'>
									".number_format($datos_sistema['votos_coaliciones_individual'], 0, '.', ',')."
								</font>
							</td>
						</tr>
						<tr>
							<td style='text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white'>
								<font class='fontLabelReporte'>Coalición Boleta:</font>
							</td>
							<td
								style='text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white'>
								<font class='fontDataReporte' style='font-size: 12px'>
									".number_format($datos_sistema['votos_coaliciones'], 0, '.', ',')."
								</font>
							</td>
						</tr>
						<tr>
							<td style='text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white'>
								<font class='fontLabelReporte'>Totales:</font>
							</td>
							<td style='text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white'>
								<font class='fontDataReporte' style='font-size: 12px'>
									".number_format($datos_sistema['votos_totales'], 0, '.', ',')."
								</font>
							</td>
						</tr>
						<tr>
							<td style='text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white'>
								<font class='fontLabelReporte'>Diferencia:</font>
							</td>
							<td
								style='text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white'>
								<font class='fontDataReporte' style='font-size: 12px'>
									".number_format($datos_partidos['orden_votos_individual']['diferencia_votos_sistema'], 0, '.', ',')."
								</font>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			";
	}else{
		$div_sistema = "";
		$espacio_sistema = '49';
		$float = 'right';
	}



	$nombre_primera_fuerza = $datos_partidos['orden_votos_individual']['primera_fuerza'];
	$datos_primera_fuerza = $datos_partidos['partidos'][$nombre_primera_fuerza];
	$div_primera_fuerza ="
		<div style='float: left;width:".$espacio_sistema."%; margin: 0px 2px 0px 2px'>
			<table style='table-layout: fixed; width: 100%' cellspacing='0' cellpadding='0'border='1'>
				<thead>
					<tr>
						<tr>
							<td style='text-align: center;padding: 10px;background-color:#".$datos_primera_fuerza['color_background'].";color: white;font-weight: bold;'
								colspan='1'>Primera Fuerza</td>
							<td style='text-align: center;padding: 10px;background-color:#".$datos_primera_fuerza['color_background'].";color: white;font-weight: bold;'
								colspan='1'>
								<img src='../../../images/logos_partidos/".$datos_primera_fuerza['logo']."' style='width: 10%'><br>".$datos_primera_fuerza['nombre_corto']."
							</td>
						</tr>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style='text-align: center' colspan=2>Votos</td>
					</tr>
					<tr>
						<td style='text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white'>
							<font class='fontLabelReporte'>Individual:</font>
						</td>
						<td style='text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white'>
							<font class='fontDataReporte' style='font-size: 12px'>
								".number_format($datos_primera_fuerza['votos_individual'], 0, '.', ',')."
							</font>
						</td>
					</tr>
					<tr>
						<td colspan='2' style='text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.1); border-bottom: 1px solid white; height: 135px'>
							<font class='fontLabelReporte'>Coaliciones:</font>";
							
								if(!empty($datos_primera_fuerza['coaliciones_orden_votos_individual'])){
									unset($datos_primera_fuerza['coaliciones_orden_votos_individual'][$datos_primera_fuerza['nombre_corto']]);
									
									$div_primera_fuerza .="<table style='width: 100%;text-align: left;font-size: 10px;table-layout: fixed;border-collapse: collapse;' Cellspacing='0' cellpadding='0' >
										<tr>
											<td colspan='2' style='border:1px solid;padding: 2px;background-color: #dee3ed'>Partido</td>
											<td style='border:1px solid;padding: 2px;background-color: #dee3ed'>Votos</td>
											<td style='border:1px solid;padding: 2px;background-color: #dee3ed'>Diff.</td>
										</tr>";

										foreach ($datos_primera_fuerza['coaliciones_orden_votos_individual'] as $partido => $votos) {
											$div_primera_fuerza .="<tr>
														<td style='border:1px solid;padding: 2px 2px 2px 5px;text-align:center'>
															<img src='../../../images/logos_partidos/".$datos_partidos['partidos'][$partido]['logo']."' style='width: 8%' >
														</td>
														<td style='border:1px solid;padding: 2px 2px 2px 5px'>".$partido."</td>
														<td style='border:1px solid;padding: 2px 2px 2px 5px'>".number_format($votos, 0, '.', ',')."</td>
														<td style='border:1px solid;padding: 2px 2px 2px 5px'>".number_format($datos_primera_fuerza['votos_individual']-$votos, 0, '.', ',')."</td>
													</tr>";
										}
									$div_primera_fuerza .="</table>";
									
								}else{
									$div_primera_fuerza .="<font class='fontDataReporte' style='font-size: 12px'>No tiene. </font>";
								}
						$div_primera_fuerza .="</td>
					</tr>
					<tr>
						<td style='text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white'>
							<font class='fontLabelReporte'>Coalición Individual:</font>
						</td>
						<td
							style='text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white'>
							<font class='fontDataReporte' style='font-size: 12px'>
								".number_format($datos_primera_fuerza['votos_coaliciones_individual'], 0, '.', ',')."
							</font>
						</td>
					</tr>
					<tr>
						<td style='text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white'>
							<font class='fontLabelReporte'>Coalición Boleta:</font>
						</td>
						<td
							style='text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white'>
							<font class='fontDataReporte' style='font-size: 12px'>
								".number_format($datos_primera_fuerza['votos_coaliciones'], 0, '.', ',')."
							</font>
						</td>
					</tr>
					<tr>
						<td style='text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white'>
							<font class='fontLabelReporte'>Totales:</font>
						</td>
						<td style='text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white'>
							<font class='fontDataReporte' style='font-size: 12px'>
								".number_format($datos_primera_fuerza['votos_totales'], 0, '.', ',')."
							</font>
						</td>
					</tr>
					<tr>
						<td style='text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white'>
							<font class='fontLabelReporte'>Diferencia:</font>
						</td>
						<td
							style='text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white'>
							<font class='fontDataReporte' style='font-size: 12px'>
								".number_format($datos_partidos['orden_votos_individual']['diferencia_votos_fuerzas'], 0, '.', ',')."
							</font>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
			";


	$nombre_segunda_fuerza = $datos_partidos['orden_votos_individual']['segunda_fuerza'];
	$datos_segunda_fuerza = $datos_partidos['partidos'][$nombre_segunda_fuerza];
	$div_segunda_fuerza ="
		<div style='float: ".$float.";width:".$espacio_sistema."%; margin: 0px 2px 0px 2px'>
			<table style='table-layout: fixed; width: 100%' cellspacing='0' cellpadding='0'border='1'>
				<thead>
					<tr>
						<tr>
							<td style='text-align: center;padding: 10px;background-color:#".$datos_segunda_fuerza['color_background'].";color: white;font-weight: bold;'
								colspan='1'>Segunda Fuerza</td>
							<td style='text-align: center;padding: 10px;background-color:#".$datos_segunda_fuerza['color_background'].";color: white;font-weight: bold;'
								colspan='1'>
								<img src='../../../images/logos_partidos/".$datos_segunda_fuerza['logo']."' style='width: 10%'><br>".$datos_segunda_fuerza['nombre_corto']."
							</td>
						</tr>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style='text-align: center' colspan=2>Votos</td>
					</tr>
					<tr>
						<td style='text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white'>
							<font class='fontLabelReporte'>individual:</font>
						</td>
						<td style='text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white'>
							<font class='fontDataReporte' style='font-size: 12px'>
								".number_format($datos_segunda_fuerza['votos_individual'], 0, '.', ',')."
							</font>
						</td>
					</tr>
					<tr>
						<td colspan='2' style='text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.1); border-bottom: 1px solid white; height: 135px'>
							<font class='fontLabelReporte'>Coaliciones:</font>";
							
								if(!empty($datos_segunda_fuerza['coaliciones_orden_votos_individual'])){
									unset($datos_segunda_fuerza['coaliciones_orden_votos_individual'][$datos_segunda_fuerza['nombre_corto']]);
									
									$div_segunda_fuerza .="<table style='width: 100%;text-align: left;font-size: 10px;table-layout: fixed;border-collapse: collapse;' Cellspacing='0' cellpadding='0' >
										<tr>
											<td colspan='2' style='border:1px solid;padding: 2px;background-color: #dee3ed'>Partido</td>
											<td style='border:1px solid;padding: 2px;background-color: #dee3ed'>Votos</td>
											<td style='border:1px solid;padding: 2px;background-color: #dee3ed'>Diff.</td>
										</tr>";

										foreach ($datos_segunda_fuerza['coaliciones_orden_votos_individual'] as $partido => $votos) {
											$div_segunda_fuerza .="<tr>
														<td style='border:1px solid;padding: 2px 2px 2px 5px;text-align:center'>
															<img src='../../../images/logos_partidos/".$datos_partidos['partidos'][$partido]['logo']."' style='width: 8%' >
														</td>
														<td style='border:1px solid;padding: 2px 2px 2px 5px'>".$partido."</td>
														<td style='border:1px solid;padding: 2px 2px 2px 5px'>".number_format($votos, 0, '.', ',')."</td>
														<td style='border:1px solid;padding: 2px 2px 2px 5px'>".number_format($datos_segunda_fuerza['votos_individual']-$votos, 0, '.', ',')."</td>
													</tr>";
										}
									$div_segunda_fuerza .="</table>";
									
								}else{
									$div_segunda_fuerza .="<font class='fontDataReporte' style='font-size: 12px'>No tiene. </font>";
								}
						$div_segunda_fuerza .="</td>
					</tr>
					<tr>
						<td style='text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white'>
							<font class='fontLabelReporte'>Coalición Individual:</font>
						</td>
						<td
							style='text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white'>
							<font class='fontDataReporte' style='font-size: 12px'>
								".number_format($datos_segunda_fuerza['votos_coaliciones_individual'], 0, '.', ',')."
							</font>
						</td>
					</tr>
					<tr>
						<td style='text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white'>
							<font class='fontLabelReporte'>Coalición Boleta:</font>
						</td>
						<td
							style='text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white'>
							<font class='fontDataReporte' style='font-size: 12px'>
								".number_format($datos_segunda_fuerza['votos_coaliciones'], 0, '.', ',')."
							</font>
						</td>
					</tr>
					<tr>
						<td style='text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white'>
							<font class='fontLabelReporte'>Totales:</font>
						</td>
						<td style='text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white'>
							<font class='fontDataReporte' style='font-size: 12px'>
								".number_format($datos_segunda_fuerza['votos_totales'], 0, '.', ',')."
							</font>
						</td>
					</tr>
					<tr>
						<td style='text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white'>
							<font class='fontLabelReporte'>Diferencia:</font>
						</td>
						<td
							style='text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white'>
							<font class='fontDataReporte' style='font-size: 12px'>
								".number_format($datos_partidos['orden_votos_individual']['diferencia_votos_fuerzas'], 0, '.', ',')."
							</font>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
			";


	$fuerzas = array(
		'[__Espacio_Sistema__]' => $espacio_sistema,
		'[__Partido_Sistema__]' => $div_sistema, 
		'[__Partido_Primera_Fuerza__]' => $div_primera_fuerza, 
		'[__Partido_Segunda_Fuerza__]' => $div_segunda_fuerza, 
	);


	require_once('../../../librerias/jpgraph/jpgraph.php');
	require_once('../../../librerias/jpgraph/jpgraph_pie.php');
	require_once('../../../librerias/jpgraph/jpgraph_pie3d.php');



	$data = $datos_partidos['orden_votos_individual']['graficas']['votos'];
	$partidos = $datos_partidos['orden_votos_individual']['graficas']['partidos'];
	$background = $datos_partidos['orden_votos_individual']['graficas']['background'];

	// Some data and the labels
	// Some data and the labels
	$data   = $data;
	foreach ($partidos as $key => $value) {
		$labels[] = $value."\n (%.1f%%)";
	}
	// Create the Pie Graph.
	$width=700;
	$height=700;
	$graph_pie = new PieGraph($width,$height,'auto');
	$graph_pie->SetShadow();
	//BORDER DE LA GRAFICA
	#$graph->clearTheme();
	// TITULO
	$graph_pie->title->Set('Porcentaje Partidos');
	$graph_pie->title->SetColor('black'); 
	// CREA LA GRAFICA
	$pie = new PiePlot($data);
	$pie->SetCenter(0.5,0.5);
	// TAMAÑO DE LA GRAFICA
	$pie->SetSize(0.3);
	// CREA LINEAS DE SEGUIMIENTO DE LA GRAFICA
	// (1) FALSE  SE QUITA LAS LINEAS Y SE PONE JUNTO A LA PARTE DE LA GRAFICA
	// (2) TRUE SE PONE LAS LINEAS
	$pie->SetGuideLines(true,true);
	// SE AJUSTA EL TAMAÑO ENTRE LAS LINEAS
	$pie->SetGuideLinesAdjust(2);
	// SE COLOCA EL ARRAY DE LOS LABES
	$pie->SetLabels($labels);
	// This method adjust the position of the labels. This is given as fractions
	// of the radius of the Pie. A value < 1 will put the center of the label
	// inside the Pie and a value >= 1 will pout the center of the label outside the
	// Pie. By default the label is positioned at 0.5, in the middle of each slice.
	$pie->SetLabelPos(1);
	// Setup the label formats and what value we want to be shown (The absolute)
	// or the percentage.
	// COLOCA EL VALOR DE LA GRAFICA
	$pie->SetLabelType(PIE_VALUE_PER);
	$pie->value->Show();
	// COLOR DE LAS LETRAS
	$pie->value->SetColor('black');
	// Add and stroke
	$graph_pie->Add($pie);
	//$graph->Stroke();
	//die;
	$pie->SetSliceColors($background);
	$img = $graph_pie->Stroke(_IMG_HANDLER);
	ob_start();
	imagepng($img);
	$grafica_pie_partidos = ob_get_contents();
	ob_end_clean();




	require_once('../../../librerias/jpgraph/jpgraph.php');
	require_once('../../../librerias/jpgraph/jpgraph_bar.php');

	
	krsort($data);
	krsort($partidos);
	krsort($background);

	foreach ($data as $key => $value) {
		$dataR[] = $value;
	}
	foreach ($partidos as $key => $value) {
		$partidosR[] = $value;
	}
	$num = 0;
	foreach ($background as $key => $value) {
		$backgroundR[$num][0] = $value;
		$backgroundR[$num][1] = $value;
		$backgroundR[$num][2] = GRAD_VER;
		$num ++;
	}

	function yLabelFormat($aLabel) {
	    // Format '1000 english style
	    //return number_format($aLabel);
	    // Format '1000 french style
		return number_format($aLabel, 0, '.', ',');
	}
	$datay=$dataR;
	// Size of graph
	$width=600;
	$height=500;
	// Set the basic parameters of the graph
	$graph = new Graph($width,$height,'auto');
	#$graph->title->font_size = 10;
	$graph->SetScale("textlin");
	$graph->setClipping(true);
	$top = 60;
	$bottom = 10;
	$left = 80;
	$right = 30;
	$graph->Set90AndMargin($left,$right,$top,$bottom);
	// Nice shadow
	$graph->SetShadow();
	// Setup labels
	$lbl = $partidosR;
	$graph->xaxis->SetTickLabels($lbl);
	$graph->xaxis->SetLabelFormatString('$%01.0f');
	// Label align for X-axis
	$graph->xaxis->SetLabelAlign('right','center','right');
	// Label align for Y-axis
	$graph->yaxis->SetLabelAlign('center','bottom');
	// Titles
	$graph->title->Set('Votos Partidos'); 
	// Create a bar pot
	$bplot = new BarPlot($datay); 
	$bplot->SetWidth(0.8); 
	$bplot->SetColor("white"); 
	$bplot->SetFillColor(array('red','black','green'));
	$bplot->SetFillgradient($backgroundR);
	// Must use TTF fonts if we want text at an arbitrary angle
	$bplot->value->SetFont(FF_ARIAL,FS_BOLD);
	$bplot->value->SetAngle(45);
	// Black color for positive values and darkred for negative values
	$bplot->value->SetColor("black","darkred");
	
	$graph->Add($bplot);
	$bplot->value->Show(); 
	$bplot->value->SetColor("black","darkred");
	$bplot->value->SetFormatCallback('yLabelFormat');
	$bplot->value->SetAngle(0);
	$bplot->value->HideZero();
	$graph->yaxis->SetLabelFormatCallback('yLabelFormat');
	
	
	$img = $graph->Stroke(_IMG_HANDLER);
	
	ob_start();
	imagepng($img);
	$grafica_bar_partidos = ob_get_contents();
	ob_end_clean();

	$graficas_totales = array(
		'[__Grafica_Partidos_Pie__]' => '<img src="data:image/png;base64,'.base64_encode($grafica_pie_partidos).'" />',
		'[__Grafica_Partidos_Barras__]' => '<img src="data:image/png;base64,'.base64_encode($grafica_bar_partidos).'" />',
	);

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	unset($background);
	$num=0;
	$contador=0;
	foreach ($datos_secciones_ine as $key => $value) {
		$color = $value['orden_votos_individual']['semaforo']['color'];
		if($color=='rojo'){
			$fillColor = '#FF0000';
		}elseif ($color=='amarillo') {
			$fillColor = '#ffff00';
		}elseif ($color=='gris') {
			$fillColor = '#808080';
		}elseif ($color=='verde') {
			$fillColor = '#008000';
		}else{
			$fillColor = '#000000';
		}
		$grupo_secciones[$num][]= array(
									'numero' => $value['numero'],
									'background' => array(
										0 => $fillColor, 
										1 => $fillColor, 
										2 => GRAD_VER, 
									),
									'competitividad' => $value['orden_votos_individual']['semaforo']['competitividad'],
									'ciudadanos_registrados' => $value['ciudadanos_registrados'],
									'apoyos_programas' => $value['apoyos_programas'],
									'acciones_obras' => $value['acciones_obras'],
									'militantes' => $value['militantes'],
									'funcionarios' => $value['funcionarios'],
								);

		if($contador == 10){
			$contador = 0;
			$num ++;
		}else{
			$contador ++;
		}
	}


	$paginas_reportes_secciones = '';
	foreach ($grupo_secciones as $id_seccion_ine => $data) {
		unset($secciones_numero);
		unset($background);
		unset($competitividad);
		unset($ciudadanos_registrados);
		unset($apoyos_programas);
		unset($acciones_obras);
		unset($militantes);
		unset($funcionarios);
		foreach ($data as $key => $value) {
			$secciones_numero[] = $value['numero'];
			$background[] = $value['background'];
			$competitividad[] = $value['competitividad'];
			$ciudadanos_registrados[] = $value['ciudadanos_registrados'];
			$apoyos_programas[] = $value['apoyos_programas'];
			$acciones_obras[] = $value['acciones_obras'];

			$militantes[] = $value['militantes'];
			$funcionarios[] = $value['funcionarios'];
		}



		$datay=$competitividad;
		// Size of graph
		$width=800;
		$height=350;
		// Set the basic parameters of the graph
		$graph = new Graph($width,$height,'auto');
		#$graph->title->font_size = 10;
		$graph->SetScale("textlin");
		$graph->setClipping(true);
		$top = 60;
		$bottom = 10;
		$left = 80;
		$right = 30;
		// Nice shadow
		$graph->SetMargin(20,0,0,20);
		$graph->SetShadow();
		$graph->yaxis->scale->SetGrace(20);
		// Setup labels
		$lbl = $secciones_numero;
		$graph->xaxis->SetTickLabels($lbl);
		$graph->xaxis->SetLabelFormatString('$%01.0f');
		// Label align for X-axis
		$graph->xaxis->SetLabelAlign('right','center','right');
		// Label align for Y-axis
		$graph->yaxis->SetLabelAlign('center','bottom');
		// Titles
		$graph->title->Set('Análisis de territorio');
		// Create a bar pot
		$bplot = new BarPlot($datay); 
		$bplot->SetWidth(0.8); 
		$bplot->SetColor("white"); 
		$bplot->SetFillColor(array('red','black','green'));
		$bplot->SetFillgradient($background);
		// Must use TTF fonts if we want text at an arbitrary angle
		$bplot->value->SetFont(FF_ARIAL,FS_BOLD);
		$bplot->value->SetAngle(45);
		// Black color for positive values and darkred for negative values
		$bplot->value->SetColor("black","darkred");
		$graph->Add($bplot);
		$bplot->value->Show(); 
		$bplot->value->SetColor("black","darkred");
		$bplot->value->SetFormatCallback('yLabelFormat');
		//$bplot->value->SetAngle(0);
		//$bplot->value->HideZero();
		$graph->yaxis->SetLabelFormatCallback('yLabelFormat');
		$img = $graph->Stroke(_IMG_HANDLER);
		ob_start();
		imagepng($img);
		$grafica_semaforo = ob_get_contents();
		ob_end_clean();
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ciudadanos_registrados;
		$apoyos_programas;
		$acciones_obras;

		
		// Size of graph
		$width=800;
		$height=320;
		// Set the basic parameters of the graph
		$graph = new Graph($width,$height,'auto');
		#$graph->title->font_size = 10;
		$graph->SetScale("textlin");
		$graph->setClipping(true);
		$top = 60;
		$bottom = 10;
		$left = 80;
		$right = 30;
		// Nice shadow
		$graph->SetMargin(20,20,0,20);
		$graph->SetShadow();
		$graph->yaxis->scale->SetGrace(20);
		// Setup labels
		$lbl = $secciones_numero;
		$graph->xaxis->SetTickLabels($lbl);
		$graph->xaxis->SetLabelFormatString('$%01.0f');
		// Label align for X-axis
		$graph->xaxis->SetLabelAlign('right','center','right');
		// Label align for Y-axis
		$graph->yaxis->SetLabelAlign('center','bottom');
		// Titles
		$graph->title->Set('Programas y Ciudadanos Registrados');
		// Create a bar pot
		$graph->yaxis->SetLabelFormatCallback('yLabelFormat');
		//////////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////
		$data = $ciudadanos_registrados;
		$bplot = new BarPlot($data); 
		$bplot->SetWidth(0.8); 
		$bplot->SetColor("white"); 
		$bplot->SetFillColor(array('red','black','green'));
		//$bplot->SetFillgradient($background);
		// Must use TTF fonts if we want text at an arbitrary angle
		$bplot->value->SetFont(FF_ARIAL,FS_BOLD);
		$bplot->value->SetAngle(45);
		// Black color for positive values and darkred for negative values
		$bplot->value->SetColor("black","darkred");
		$graph->Add($bplot);
		$bplot->value->Show(); 
		$bplot->value->SetColor("black","darkred");
		$bplot->value->SetFormatCallback('yLabelFormat');
		$bplot->SetLegend("Ciudadanos Registrados");
		//////////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////
		require_once('../../../librerias/jpgraph/jpgraph_line.php');
		$data = $apoyos_programas;
		$bline1 = new LinePlot($data); 

		$bline1->SetFillColor('yellow@0.5');
		$bline1->SetColor('yellow@0.7');
		$bline1->SetBarCenter();

		$bline1->mark->SetType(MARK_SQUARE);
		$bline1->mark->SetColor('yellow@0.5');
		$bline1->mark->SetFillColor('yellow');
		$bline1->mark->SetSize(6);
		//$bline1->SetFillgradient($background);
		// Must use TTF fonts if we want text at an arbitrary angle
		$bline1->value->SetFont(FF_ARIAL,FS_BOLD);
		$bline1->value->SetAngle(45);
		// Black color for positive values and darkred for negative values
		$graph->Add($bline1);
		$bline1->value->Show();  
		$bline1->value->SetFormatCallback('yLabelFormat');
		$bline1->SetLegend("Programas de gobierno");
		//////////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////
		$data = $acciones_obras;
		$bline2 = new LinePlot($data); 

		$bline2->SetFillColor('red@0.5');
		$bline2->SetColor('red@0.7');
		$bline2->SetBarCenter();

		$bline2->mark->SetType(MARK_SQUARE);
		$bline2->mark->SetColor('red@0.5');
		$bline2->mark->SetFillColor('red');
		$bline2->mark->SetSize(6);
		//$bline1->SetFillgradient($background);
		// Must use TTF fonts if we want text at an arbitrary angle
		$bline2->value->SetFont(FF_ARIAL,FS_BOLD);
		$bline2->value->SetAngle(45);
		// Black color for positive values and darkred for negative values
		$graph->Add($bline2);
		$bline2->value->Show();  
		$bline2->value->SetFormatCallback('yLabelFormat');
		$bline2->SetLegend("Programas de inversión");
		//////////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////

		
		$graph->legend->SetFrameWeight(1);
		$graph->legend->SetColumns(6);
		$graph->legend->SetColor('#4E4E4E','#00A78A','#ff0000');
		$graph->legend->SetPos(0.5,0.98,'center','bottom');
		//$bplot->value->SetAngle(0);
		//$bplot->value->HideZero();
		
		$img = $graph->Stroke(_IMG_HANDLER);
		ob_start();
		imagepng($img);
		$grafica_programas = ob_get_contents();
		ob_end_clean();
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ciudadanos_registrados;
		$militantes;
		$funcionarios;

		// Size of graph
		$width=800;
		$height=320;
		// Set the basic parameters of the graph
		$graph = new Graph($width,$height,'auto');
		#$graph->title->font_size = 10;
		$graph->SetScale("textlin");
		$graph->setClipping(true);
		$top = 60;
		$bottom = 10;
		$left = 80;
		$right = 30;
		// Nice shadow
		$graph->SetMargin(0,220,-2,0);
		$graph->SetShadow();
		$graph->yaxis->scale->SetGrace(20);
		// Setup labels
		$lbl = $secciones_numero;
		$graph->xaxis->SetTickLabels($lbl);
		$graph->xaxis->SetLabelFormatString('$%01.0f');
		// Label align for X-axis
		$graph->xaxis->SetLabelAlign('right','center','right');
		// Label align for Y-axis
		$graph->yaxis->SetLabelAlign('center','bottom');
		// Titles
		$graph->title->Set('Ciudadanos');
		// Create a bar pot
		$graph->yaxis->SetLabelFormatCallback('yLabelFormat');
		//////////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////
		$data = $ciudadanos_registrados;
		$bplot = new BarPlot($data); 
		$bplot->SetWidth(0.8); 
		$bplot->SetColor("white"); 
		$bplot->SetFillColor("#B0C4DE");
		$bplot->value->SetColor('black','darkred');
		$bplot->SetFillGradient('#B0C4DE', '#B0C4DE', GRAD_VER);  
		//$bplot->SetFillgradient($background);
		// Must use TTF fonts if we want text at an arbitrary angle
		$bplot->value->SetFont(FF_ARIAL,FS_BOLD);
		$bplot->value->SetAngle(45);
		// Black color for positive values and darkred for negative values
		$bplot->value->SetColor("black","darkred");
		$graph->Add($bplot);
		$bplot->value->Show(); 
		$bplot->value->SetColor("black","darkred");
		$bplot->value->SetFormatCallback('yLabelFormat');
		$bplot->SetLegend("Ciudadanos Registrados");
		//////////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////
		$data = $funcionarios;
		$bline1 = new LinePlot($data); 
		$bline1->SetFillColor('blue@0.7');
		$bline1->SetColor('blue@0.9');
		$bline1->SetBarCenter();

		$bline1->mark->SetType(MARK_SQUARE);
		$bline1->mark->SetColor('blue@0.5');
		$bline1->mark->SetFillColor('blue');
		$bline1->mark->SetSize(6);
		//$bline1->SetFillgradient($background);
		// Must use TTF fonts if we want text at an arbitrary angle
		$bline1->value->SetFont(FF_ARIAL,FS_BOLD);
		$bline1->value->SetAngle(45);
		// Black color for positive values and darkred for negative values
		$graph->Add($bline1);
		$bline1->value->Show();  
		$bline1->value->SetFormatCallback('yLabelFormat');
		$bline1->SetLegend("Funcionarios");
		//////////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////
		$data = $militantes;
		$bline2 = new LinePlot($data); 
		$bline2->SetFillColor('red@0.7');
		$bline2->SetColor('red@0.9');
		$bline2->SetBarCenter();

		$bline2->mark->SetType(MARK_SQUARE);
		$bline2->mark->SetColor('red@0.5');
		$bline2->mark->SetFillColor('red');
		$bline2->mark->SetSize(6);
		//$bline2->SetFillgradient($background);
		// Must use TTF fonts if we want text at an arbitrary angle
		$bline2->value->SetFont(FF_ARIAL,FS_BOLD);
		$bline2->value->SetAngle(45);
		// Black color for positive values and darkred for negative values
		$graph->Add($bline2);
		$bline2->value->Show();  
		$bline2->value->SetFormatCallback('yLabelFormat');
		$bline2->SetLegend("Militantes");
		
		$graph->legend->SetFrameWeight(1);
		$graph->legend->SetColumns(6);
		$graph->legend->SetColor('#4E4E4E','#00A78A');
		$graph->legend->SetPos(0.5,0.98,'center','bottom');
		//////////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////


		//$bplot->value->SetAngle(0);
		//$bplot->value->HideZero();
		
		$img = $graph->Stroke(_IMG_HANDLER);
		ob_start();
		imagepng($img);
		$grafica_ciudadanos = ob_get_contents();
		ob_end_clean();

		$paginas_reportes_secciones .= '		
		<div class="page_break" style="display:inline-block">
			<div style="margin-top: 10px;text-align:left;width:49%;display: table;float:left">
				<b>Secciones: </b> '.implode(', ',$secciones_numero).'
			</div>
			<div style="margin-top: 10px;text-align:center;width:49%;display: table;float:right">
				<table style="table-layout: fixed;border-color: white;" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<tr>
								<td
									style="text-align: center;padding: 2px;background-color: #a0a0a0;color: white;font-weight: bold;"
									colspan="8">Semáforo</td>
							</tr>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align: center; padding: 2px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<span class="dotRed">&nbsp;&nbsp;&nbsp;&nbsp;</span>
							</td>
							<td
								style="text-align: center; padding: 2px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									&#8804;49
								</font>
							</td>

							<td style="text-align: center; padding: 2px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<span class="dotYellow">&nbsp;&nbsp;&nbsp;&nbsp;</span>
							</td>
							<td
								style="text-align: center; padding: 2px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									&#8805; 50 & &#8804; 99 
								</font>
							</td>

							<td style="text-align: center; padding: 2px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<span class="dotGreen">&nbsp;&nbsp;&nbsp;&nbsp;</span>
							</td>
							<td
								style="text-align: center; padding: 2px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									&#8805;	100
								</font>
							</td>

							<td style="text-align: center; padding: 2px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<span class="dotGray">&nbsp;&nbsp;&nbsp;&nbsp;</span>
							</td>
							<td
								style="text-align: center; padding: 2px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									No Data
								</font>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div style="margin-top: 10px;text-align:center;width:100%;display: table;">
				<img src="data:image/png;base64,'.base64_encode($grafica_semaforo).'" />
			</div>
			<div style="margin-top: 10px;text-align:center;width:100%;display: table;">
				<img src="data:image/png;base64,'.base64_encode($grafica_programas).'" />
			</div>
			<div style="margin-top: 10px;text-align:center;width:100%;display: table;">
				<img src="data:image/png;base64,'.base64_encode($grafica_ciudadanos).'" />
			</div>
		</div>';
	}




	$paginas_reportes = array(
		'[__Paginas_Grupos_Secciones__]' => $paginas_reportes_secciones,
		//'[__Grafica_Semaforo__]' => '<img src="data:image/png;base64,'.base64_encode($grafica_semaforo).'" />',
		//'[__Grafica_Programas__]' => '<img src="data:image/png;base64,'.base64_encode($grafica_programas).'" />',
		//'[__Grafica_Ciudadanos__]' => '<img src="data:image/png;base64,'.base64_encode($grafica_ciudadanos).'" />',
	);

	$bodyHTML = strtr($pagina_inicio, array_merge($css,$impresion,$empresa,$documentento,$totales,$fuerzas,$graficas_totales,$paginas_reportes));
	//echo $bodyHTML;
	//die;



	require_once('../../../librerias/pdf/mpdf.php');
	//$mpdf = new mPDF(); 
	$mpdf->showImageErrors = true;
	//$mpdf -> writeHTML($bodyHTML);
	//$mpdf -> Output('reporte.pdf','I');
	$mpdf = new mPDF('c','A4'); 
	$mpdf->SetProtection(false);
	$mpdf->debug = true;
	$mpdf->SetTitle("Análisis Territorial");
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
	$mpdf->Output("Análisis_de_territorio_".$territorio_tipo."_".$datos_generales['territorio_nombre']." - .pdf", 'I');
	/*
	'D': download the PDF file
	'I': serves in-line to the browser
	'S': returns the PDF document as a string
	'F': save as file $file_out
	
	<barcode code="MECARD:N:Norfi, Carrodeguas;TEL:5358167785;EMAIL:info@norfipc.com;URL:http://norfipc.com;" type="QR" class="barcode" size="1" error="Q" />

	
