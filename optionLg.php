<?php
	session_start();
	//header('Cache-Control: max-age=84600');
	$lg = $_GET['lg'];

	$icono = "images/logos/logo_pri_4_4.png";
	$logo = "images/logos/logo_pri_4_4.png";

	if($tipo_pagina == "Inicio"){

		$menu['Inicio'] = array('nombre' => 'Inicio','link' => '#home-section', );
		$menu['Nosotros'] = array('nombre' => 'Sobre Mi','link' => '#about-section', );
		$menu['Contacto'] = array('nombre' => 'Contacto','link' => '#contact-section', );

		include 'ops/db.php';



		////parido_principal
		$scriptPartido=" SELECT icono,logo FROM partidos_2021 WHERE principal=1 LIMIT 1";
		$resultadoPartido = $conexion->query($scriptPartido);
		$rowPartido=$resultadoPartido->fetch_assoc();
		$partido_icono = $rowPartido['icono'];
		$partido_logo = $rowPartido['logo'];

		$scriptCandidato=" SELECT * FROM candidato";
		$resultadoCandidato = $conexion->query($scriptCandidato);
		$candidatoDatos=$resultadoCandidato->fetch_assoc();

		$candidatoDatos['tipo_cartografia'];
		$sqlSeccionesWhere ='';
		if($candidatoDatos['tipo_cartografia']!='0' || $candidatoDatos['tipo_cartografia']!=''){
			if($candidatoDatos['tipo_cartografia']=='municipios'){
				$sqlSeccionesWhere = ' AND si.id_municipio = "'.$candidatoDatos['id_tipo_cartografia'].'"';
			}elseif($candidatoDatos['tipo_cartografia']=='distritos_locales'){
				$sqlSeccionesWhere = ' AND si.id_distrito_local = "'.$candidatoDatos['id_tipo_cartografia'].'"';
			}elseif($candidatoDatos['tipo_cartografia']=='distritos_federales'){
				$sqlSeccionesWhere = ' AND si.id_distrito_federal = "'.$candidatoDatos['id_tipo_cartografia'].'"';
			}else{
				$sqlSeccionesWhere = ' AND si.id = "'.$candidatoDatos['id_tipo_cartografia'].'"';
			}
		}

		//$secciones_ineDatosMapa = secciones_ineDatosMapa();
		$sqlSecciones = "SELECT * FROM secciones_ine si WHERE 1 ".$sqlSeccionesWhere;
		$resultSecciones = $conexion->query($sqlSecciones); 
		while($rowSecciones=$resultSecciones->fetch_assoc()){
			$secciones_ineDatosMapa[$rowSecciones['id']]=$rowSecciones;
		}

		if(!empty($sqlSeccionesWhere)){
			$sqlSeccionesParametrosWhere = 'AND EXISTS ('.$sqlSecciones.' AND si.id = sip.id_seccion_ine )';
		}

		//$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa();
		$sqlSeccionesParametros="SELECT sip.id_seccion_ine,sip.tipo,sip.orden,sip.latitud,sip.longitud FROM secciones_ine_parametros sip WHERE 1 ".$sqlSeccionesParametrosWhere;
		$resultSeccionesParametros = $conexion->query($sqlSeccionesParametros); 
		while($rowSeccionesParametros=$resultSeccionesParametros->fetch_assoc()){
			$secciones_ine_parametrosDatosMapa[$rowSeccionesParametros['id_seccion_ine']][$rowSeccionesParametros['tipo']][$rowSeccionesParametros['orden']]=$rowSeccionesParametros;
		}

		$sql='SELECT nombre,slogan,url_base FROM configuracion WHERE 1 = 1 LIMIT 1';
		$resultado = $conexion->query($sql);
		$configuracionDatos=$resultado->fetch_assoc();
		$img_logo='<img src="'.$configuracionDatos['url_base'].'ops/imagen.php?id_img=logo_principal.png" height="90px" >';

		$urlPath='https://'.$_SERVER['HTTP_HOST'];
		$pageTitulo ='Inicio';
		$page_titulo = $candidatoDatos['nombre_completo'];
		$page_descripcion = $candidatoDatos['descripcion'];

		$color_principal = '#'.$candidatoDatos['color_principal'];
		$color_secundario = '#'.$candidatoDatos['color_secundario'];

		$banners[0] = array(
			'video' => $candidatoDatos['link_video'],
			'sub' => 'Hola!',
			'titulo' => 'Yo soy <span style="color: '.$color_principal.'">'.$candidatoDatos['nombre_completo'].'</span>',
			'descripcion' => $candidatoDatos['descripcion_corta'],
			'imagen' => 'images/banners/bg_1.jpg',
		);
	}














