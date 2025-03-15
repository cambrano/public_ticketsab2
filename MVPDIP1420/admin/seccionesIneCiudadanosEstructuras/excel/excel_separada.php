<?php
	@session_start();
	$pageService=$_POST['pageService'];
	if($pageService=="" || $_POST['pageService'] != $pageService ){
		?>
		<script type="text/javascript">
			window.close();
		</script>
		<?php
		die;
	}else{
		$_COOKIE['pageService'];
	}
	$start_time = microtime(true);
	include_once("../../librerias/excel/xlsxwriter.class.php");
	include_once "../../functions/security.php";
	include_once "../../functions/usuario_permisos.php";
	include_once "../../functions/tool_xhpzab.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_estructuras',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['download'] || $moduloAccionPermisos['all']){
	}else{
		?>
		<script type="text/javascript">
			window.close();
		</script>
		<?php
		die;
	}

	$length=6; 
	$mk_id=time()*2*36*12;
	$gen_id3 = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890123456789"), 0, $length); 

	$filename = 'Estructuras_-_'.date("Ymd-His").'-'.$gen_id3.$mk_id.'.xlsx';
	$ruta_ftpExport ='../../ftpFiles/documentosExport/';
	/*
	header('Content-disposition: attachment;   filename="'.XLSXWriter::sanitize_filename('');
	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	*/
	$writer = new XLSXWriter();
	$keywords = array('Estructuras Segmentada','Data','Información');
	$writer->setTitle('Estructuras Segmentada');
	$writer->setSubject('Información de la base de datos');
	$writer->setAuthor('Ideas');
	$writer->setCompany('Ideas');
	$writer->setKeywords($keywords);
	$writer->setDescription('Información de la base de datos');
	$writer->setTempDir(sys_get_temp_dir());//set custom tempdir
	$columa = array(
		0 => array('row' => 'seccion' ,'nombre' => 'Sección' ,'tipo' => 'string','mostrar' => 1 ),
		1 => array('row' => 'clave' ,'nombre' => 'Clave' ,'tipo' => 'string','mostrar' => 1 ),
		2 => array('row' => 'folio' ,'nombre' => 'Folio' ,'tipo' => 'string','mostrar' => 1 ),
		3 => array('row' => 'tipo_ciudadano' ,'nombre' => 'Tipo Ciudadano' ,'tipo' => 'string','mostrar' => 1 ),
		4 => array('row' => 'nombre_completo' ,'nombre' => 'Nombre Completo' ,'tipo' => 'string','mostrar' => 1 ),
		5 => array('row' => 'clave_elector' ,'nombre' => 'Clave Elector' ,'tipo' => 'string','mostrar' => 1),
		6 => array('row' => 'municipio' ,'nombre' => 'Municipio' ,'tipo' => 'string','mostrar' => 1),
	);
	foreach ($columa as $key => $value) {
		$header[$value['nombre']]=$value['tipo'];
	}

	$search_database = $_POST['excel']['0'];
	$sql = "SELECT id,nombre FROM tipos_ciudadanos;";
	$resultado = $conexion->query($sql);
	while($row=$resultado->fetch_assoc()){
		$tipos_ciudadanos_array[$row['id']] = $row['nombre'];
	}
	$sql1 = "SELECT 
	sic.id,
	(SELECT p.nombre FROM plataformas p WHERE p.plataforma = sic.codigo_plataforma ) plataforma,
	sic.clave,
	sic.folio,
	sic.nombre_completo,
	sic.nombre,
	sic.apellido_paterno,
	sic.apellido_materno,
	sic.clave_elector,
	(SELECT LPAD(s.numero,4,0) FROM secciones_ine s WHERE s.id = sic.id_seccion_ine) seccion,
	sic.id_tipo_ciudadano,
	(SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = sic.id_tipo_ciudadano) tipo_ciudadano,
	(SELECT COUNT(*) FROM secciones_ine_ciudadanos sic1 WHERE sic1.id_seccion_ine_ciudadano_compartido = sic.id ) referidos,
	(SELECT dl.numero FROM distritos_locales dl WHERE dl.id = sic.id_distrito_local) distrito_local,
	(SELECT df.numero FROM distritos_federales df WHERE df.id = sic.id_distrito_federal) distrito_federal,
	(SELECT m.municipio FROM municipios m WHERE m.id = sic.id_municipio) municipio
	FROM secciones_ine_ciudadanos sic WHERE sic.id_tipo_ciudadano = 1";
	if(!empty($id_municipio)){
		//$sql1 .= " AND sic.id_municipio = {$id_municipio} ";
	}
	if(!empty($id_distrito_local)){
		//$sql1 .= " AND sic.id_distrito_local = {$id_distrito_local} ";
	}
	if(!empty($id_distrito_federal)){
		//$sql1 .= " AND sic.id_distrito_federal = {$id_distrito_federal} ";
	}
	$resultado1 = $conexion->query($sql1);
	$numarray1 = 0;
	while($row1=$resultado1->fetch_assoc()){
		$exel[]= array( 
						'color' => '#D9D9D9',
						'started' => '1',
						'datos' => array($row1['seccion'],$row1['clave'],$row1['folio'],$row1['tipo_ciudadano'],$row1['nombre_completo'],$row1['clave_elector'],$row1['municipio'])
						);
		$data['nivel_1'][$numarray1]['datos_ciudadano']= $row1;
		//!Nivel 2 ///////////////////////////////
		//! Buscams si tiene hijos
		$id1 = $row1['id'];
		$sql2 = "SELECT 
			sic.id,
			(SELECT p.nombre FROM plataformas p WHERE p.plataforma = sic.codigo_plataforma ) plataforma,
			sic.clave,
			sic.folio,
			sic.nombre_completo,
			sic.nombre,
			sic.apellido_paterno,
			sic.apellido_materno,
			sic.clave_elector,
			(SELECT LPAD(s.numero,4,0) FROM secciones_ine s WHERE s.id = sic.id_seccion_ine) seccion,
			sic.id_tipo_ciudadano,
			(SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = sic.id_tipo_ciudadano) tipo_ciudadano,
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos sic1 WHERE sic1.id_seccion_ine_ciudadano_compartido = sic.id ) referidos,
			(SELECT dl.numero FROM distritos_locales dl WHERE dl.id = sic.id_distrito_local) distrito_local,
			(SELECT df.numero FROM distritos_federales df WHERE df.id = sic.id_distrito_federal) distrito_federal,
			(SELECT m.municipio FROM municipios m WHERE m.id = sic.id_municipio) municipio
			FROM secciones_ine_ciudadanos sic WHERE sic.id_seccion_ine_ciudadano_compartido = {$id1}";
		$resultado2 = $conexion->query($sql2);
		$numarray2 = 0;
		//$data['nivel_1'][$numarray1]['datos_ciudadano']['referidos'] = $resultado2->num_rows;
		$contador[$id1] =  $resultado2->num_rows+$contador[$id1];
		while($row2=$resultado2->fetch_assoc()){
			$exel[]= array( 
				'color' => '#397cb5',
				'style' => array('fill'=>'#397cb5','color'=>'#FFFFFF','font-style'=>'bold'),
				'datos' => array('','Sección','Clave','Folio','Tipo Ciudadano','Nombre Completo','Clave Elector','Municipio')
				);
			$exel[]= array( 
				'color' => '#FDFEE1',
				'datos' => array('',$row2['seccion'],$row2['clave'],$row2['folio'],$row2['tipo_ciudadano'],$row2['nombre_completo'],$row2['clave_elector'],$row2['municipio'])
				);
			$data['nivel_1'][$numarray1]['nivel_2'][$numarray2]['datos_ciudadano'] = $row2;
			$contador_seccion[$id1][$row2['seccion']] = $contador_seccion[$id1][$row2['seccion']] + 1;
			$contador_seccion_tipo_ciudadano[$id1][$row2['seccion']][$row2['id_tipo_ciudadano']] = $contador_seccion_tipo_ciudadano[$id1][$row2['seccion']][$row2['id_tipo_ciudadano']] + 1;
			//!Nivel 3 ///////////////////////////////
			//! Buscams si tiene hijos
			$id2 = $row2['id'];
			$sql3 = "SELECT 
				sic.id,
				(SELECT p.nombre FROM plataformas p WHERE p.plataforma = sic.codigo_plataforma ) plataforma,
				sic.clave,
				sic.folio,
				sic.nombre_completo,
				sic.nombre,
				sic.apellido_paterno,
				sic.apellido_materno,
				sic.clave_elector,
				(SELECT LPAD(s.numero,4,0) FROM secciones_ine s WHERE s.id = sic.id_seccion_ine) seccion,
				sic.id_tipo_ciudadano,
				(SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = sic.id_tipo_ciudadano) tipo_ciudadano,
				(SELECT COUNT(*) FROM secciones_ine_ciudadanos sic1 WHERE sic1.id_seccion_ine_ciudadano_compartido = sic.id ) referidos,
				(SELECT dl.numero FROM distritos_locales dl WHERE dl.id = sic.id_distrito_local) distrito_local,
				(SELECT df.numero FROM distritos_federales df WHERE df.id = sic.id_distrito_federal) distrito_federal,
				(SELECT m.municipio FROM municipios m WHERE m.id = sic.id_municipio) municipio
				FROM secciones_ine_ciudadanos sic WHERE sic.id_seccion_ine_ciudadano_compartido = {$id2}";
			$resultado3 = $conexion->query($sql3);
			$numarray3 = 0;
			//$data['nivel_1'][$numarray1]['nivel_2'][$numarray2]['datos_ciudadano']['referidos'] = $resultado3->num_rows;
			$contador[$id1] =  $resultado3->num_rows+$contador[$id1];
			$contador[$id2] =  $resultado3->num_rows+$contador[$id2];
			while($row3=$resultado3->fetch_assoc()){
				$exel[]= array( 
					'color' => '#397cb5',
					'style' => array('fill'=>'#397cb5','color'=>'#FFFFFF','font-style'=>'bold'),
					'datos' => array('','','Sección','Clave','Folio','Tipo Ciudadano','Nombre Completo','Clave Elector','Municipio')
					);
				$exel[]= array( 
					'color' => '#EDFDE9',
					'datos' => array('','',$row3['seccion'],$row3['clave'],$row3['folio'],$row3['tipo_ciudadano'],$row3['nombre_completo'],$row3['clave_elector'],$row3['municipio'])
					);
				$data['nivel_1'][$numarray1]['nivel_2'][$numarray2]['nivel_3'][$numarray3]['datos_ciudadano'] = $row3;
				$contador_seccion[$id1][$row3['seccion']] = $contador_seccion[$id1][$row3['seccion']] + 1;
				$contador_seccion[$id2][$row3['seccion']] = $contador_seccion[$id2][$row3['seccion']] + 1;
				$contador_seccion_tipo_ciudadano[$id1][$row3['seccion']][$row3['id_tipo_ciudadano']] = $contador_seccion_tipo_ciudadano[$id1][$row3['seccion']][$row3['id_tipo_ciudadano']] + 1;
				$contador_seccion_tipo_ciudadano[$id2][$row3['seccion']][$row3['id_tipo_ciudadano']] = $contador_seccion_tipo_ciudadano[$id2][$row3['seccion']][$row3['id_tipo_ciudadano']] + 1;
				//!Nivel 4 ///////////////////////////////
				//! Buscams si tiene hijos
				$id3 = $row3['id'];
				$sql4 = "SELECT 
					sic.id,
					(SELECT p.nombre FROM plataformas p WHERE p.plataforma = sic.codigo_plataforma ) plataforma,
					sic.clave,
					sic.folio,
					sic.nombre_completo,
					sic.nombre,
					sic.apellido_paterno,
					sic.apellido_materno,
					sic.clave_elector,
					(SELECT LPAD(s.numero,4,0) FROM secciones_ine s WHERE s.id = sic.id_seccion_ine) seccion,
					sic.id_tipo_ciudadano,
					(SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = sic.id_tipo_ciudadano) tipo_ciudadano,
					(SELECT COUNT(*) FROM secciones_ine_ciudadanos sic1 WHERE sic1.id_seccion_ine_ciudadano_compartido = sic.id ) referidos,
					(SELECT dl.numero FROM distritos_locales dl WHERE dl.id = sic.id_distrito_local) distrito_local,
					(SELECT df.numero FROM distritos_federales df WHERE df.id = sic.id_distrito_federal) distrito_federal,
					(SELECT m.municipio FROM municipios m WHERE m.id = sic.id_municipio) municipio
					FROM secciones_ine_ciudadanos sic WHERE sic.id_seccion_ine_ciudadano_compartido = {$id3}";
				$resultado4 = $conexion->query($sql4);
				$numarray4 = 0;
				//$data['nivel_1'][$numarray1]['nivel_2'][$numarray2]['nivel_3'][$numarray3]['datos_ciudadano']['referidos'] = $resultado4->num_rows;
				$contador[$id1] =  $resultado4->num_rows+$contador[$id1];
				$contador[$id2] =  $resultado4->num_rows+$contador[$id2];
				$contador[$id3] =  $resultado4->num_rows+$contador[$id3];
				while($row4=$resultado4->fetch_assoc()){
					$exel[]= array( 
						'color' => '#397cb5',
						'style' => array('fill'=>'#397cb5','color'=>'#FFFFFF','font-style'=>'bold'),
						'datos' => array('','','','Sección','Clave','Folio','Tipo Ciudadano','Nombre Completo','Clave Elector','Municipio')
						);
					$exel[]= array( 
						'color' => '#d9e2f3',
						'datos' => array('','','',$row4['seccion'],$row4['clave'],$row4['folio'],$row4['tipo_ciudadano'],$row4['nombre_completo'],$row4['clave_elector'],$row4['municipio'])
						);
					$data['nivel_1'][$numarray1]['nivel_2'][$numarray2]['nivel_3'][$numarray3]['nivel_4'][$numarray4]['datos_ciudadano'] = $row4;
					$contador_seccion[$id1][$row4['seccion']] = $contador_seccion[$id1][$row4['seccion']] + 1;
					$contador_seccion[$id2][$row4['seccion']] = $contador_seccion[$id2][$row4['seccion']] + 1;
					$contador_seccion[$id3][$row4['seccion']] = $contador_seccion[$id3][$row4['seccion']] + 1;
					$contador_seccion_tipo_ciudadano[$id1][$row4['seccion']][$row4['id_tipo_ciudadano']] = $contador_seccion_tipo_ciudadano[$id1][$row4['seccion']][$row4['id_tipo_ciudadano']] + 1;
					$contador_seccion_tipo_ciudadano[$id2][$row4['seccion']][$row4['id_tipo_ciudadano']] = $contador_seccion_tipo_ciudadano[$id2][$row4['seccion']][$row4['id_tipo_ciudadano']] + 1;
					$contador_seccion_tipo_ciudadano[$id3][$row4['seccion']][$row4['id_tipo_ciudadano']] = $contador_seccion_tipo_ciudadano[$id3][$row4['seccion']][$row4['id_tipo_ciudadano']] + 1;
					//!Nivel 5 ///////////////////////////////
					//! Buscams si tiene hijos
					$id4 = $row4['id'];
					$sql5 = "SELECT 
						sic.id,
						(SELECT p.nombre FROM plataformas p WHERE p.plataforma = sic.codigo_plataforma ) plataforma,
						sic.clave,
						sic.folio,
						sic.nombre_completo,
						sic.nombre,
						sic.apellido_paterno,
						sic.apellido_materno,
						sic.clave_elector,
						(SELECT LPAD(s.numero,4,0) FROM secciones_ine s WHERE s.id = sic.id_seccion_ine) seccion,
						sic.id_tipo_ciudadano,
						(SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = sic.id_tipo_ciudadano) tipo_ciudadano,
						(SELECT COUNT(*) FROM secciones_ine_ciudadanos sic1 WHERE sic1.id_seccion_ine_ciudadano_compartido = sic.id ) referidos,
						(SELECT dl.numero FROM distritos_locales dl WHERE dl.id = sic.id_distrito_local) distrito_local,
						(SELECT df.numero FROM distritos_federales df WHERE df.id = sic.id_distrito_federal) distrito_federal,
						(SELECT m.municipio FROM municipios m WHERE m.id = sic.id_municipio) municipio
						FROM secciones_ine_ciudadanos sic WHERE sic.id_seccion_ine_ciudadano_compartido = {$id4}";
					$resultado5 = $conexion->query($sql5);
					$numarray5 = 0;
					//$data['nivel_1'][$numarray1]['nivel_2'][$numarray2]['nivel_3'][$numarray3]['nivel_4'][$numarray4]['datos_ciudadano']['referidos'] = $resultado5->num_rows;
					$contador[$id1] =  $resultado5->num_rows+$contador[$id1];
					$contador[$id2] =  $resultado5->num_rows+$contador[$id2];
					$contador[$id3] =  $resultado5->num_rows+$contador[$id3];
					$contador[$id4] =  $resultado5->num_rows+$contador[$id4];
					while($row5=$resultado5->fetch_assoc()){
						$exel[]= array( 
							'color' => '#397cb5',
							'style' => array('fill'=>'#397cb5','color'=>'#FFFFFF','font-style'=>'bold'),
							'datos' => array('','','','','Sección','Clave','Folio','Tipo Ciudadano','Nombre Completo','Clave Elector','Municipio')
							);
						$exel[]= array( 
							'color' => '#fbf0fb',
							'datos' => array('','','','',$row5['seccion'],$row5['clave'],$row5['folio'],$row5['tipo_ciudadano'],$row5['nombre_completo'],$row5['clave_elector'],$row5['municipio'])
							);
						$data['nivel_1'][$numarray1]['nivel_2'][$numarray2]['nivel_3'][$numarray3]['nivel_4'][$numarray4]['nivel_5'][$numarray5]['datos_ciudadano'] = $row5;
						$contador_seccion[$id1][$row5['seccion']] = $contador_seccion[$id1][$row5['seccion']] + 1;
						$contador_seccion[$id2][$row5['seccion']] = $contador_seccion[$id2][$row5['seccion']] + 1;
						$contador_seccion[$id3][$row5['seccion']] = $contador_seccion[$id3][$row5['seccion']] + 1;
						$contador_seccion[$id4][$row5['seccion']] = $contador_seccion[$id4][$row5['seccion']] + 1;
						$contador_seccion_tipo_ciudadano[$id1][$row5['seccion']][$row5['id_tipo_ciudadano']] = $contador_seccion_tipo_ciudadano[$id1][$row5['seccion']][$row5['id_tipo_ciudadano']] + 1;
						$contador_seccion_tipo_ciudadano[$id2][$row5['seccion']][$row5['id_tipo_ciudadano']] = $contador_seccion_tipo_ciudadano[$id2][$row5['seccion']][$row5['id_tipo_ciudadano']] + 1;
						$contador_seccion_tipo_ciudadano[$id3][$row5['seccion']][$row5['id_tipo_ciudadano']] = $contador_seccion_tipo_ciudadano[$id3][$row5['seccion']][$row5['id_tipo_ciudadano']] + 1;
						$contador_seccion_tipo_ciudadano[$id4][$row5['seccion']][$row5['id_tipo_ciudadano']] = $contador_seccion_tipo_ciudadano[$id4][$row5['seccion']][$row5['id_tipo_ciudadano']] + 1;
						//!Nivel 6 ///////////////////////////////
						//! Buscams si tiene hijos
						$id5 = $row5['id'];
						$sql6 = "SELECT 
							sic.id,
							(SELECT p.nombre FROM plataformas p WHERE p.plataforma = sic.codigo_plataforma ) plataforma,
							sic.clave,
							sic.folio,
							sic.nombre_completo,
							sic.nombre,
							sic.apellido_paterno,
							sic.apellido_materno,
							sic.clave_elector,
							(SELECT LPAD(s.numero,4,0) FROM secciones_ine s WHERE s.id = sic.id_seccion_ine) seccion,
							sic.id_tipo_ciudadano,
							(SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = sic.id_tipo_ciudadano) tipo_ciudadano,
							(SELECT COUNT(*) FROM secciones_ine_ciudadanos sic1 WHERE sic1.id_seccion_ine_ciudadano_compartido = sic.id ) referidos,
							(SELECT dl.numero FROM distritos_locales dl WHERE dl.id = sic.id_distrito_local) distrito_local,
							(SELECT df.numero FROM distritos_federales df WHERE df.id = sic.id_distrito_federal) distrito_federal,
							(SELECT m.municipio FROM municipios m WHERE m.id = sic.id_municipio) municipio
							FROM secciones_ine_ciudadanos sic WHERE sic.id_seccion_ine_ciudadano_compartido = {$id5}";
						$resultado6 = $conexion->query($sql6);
						$numarray6 = 0;
						//$data['nivel_1'][$numarray1]['nivel_2'][$numarray2]['nivel_3'][$numarray3]['nivel_4'][$numarray4]['nivel_5'][$numarray5]['datos_ciudadano']['referidos'] = $resultado6->num_rows;
						$contador[$id1] =  $resultado6->num_rows+$contador[$id1];
						$contador[$id2] =  $resultado6->num_rows+$contador[$id2];
						$contador[$id3] =  $resultado6->num_rows+$contador[$id3];
						$contador[$id4] =  $resultado6->num_rows+$contador[$id4];
						$contador[$id5] =  $resultado6->num_rows+$contador[$id5];
						while($row6=$resultado6->fetch_assoc()){
							$exel[]= array( 
								'color' => '#397cb5',
								'style' => array('fill'=>'#397cb5','color'=>'#FFFFFF','font-style'=>'bold'),
								'datos' => array('','','','','','Sección','Clave','Folio','Tipo Ciudadano','Nombre Completo','Clave Elector','Municipio')
								);
							$exel[]= array( 
								'color' => '#fefef9',
								'datos' => array('','','','','',$row6['seccion'],$row6['clave'],$row6['folio'],$row6['tipo_ciudadano'],$row6['nombre_completo'],$row6['clave_elector'],$row6['municipio'])
								);
							$data['nivel_1'][$numarray1]['nivel_2'][$numarray2]['nivel_3'][$numarray3]['nivel_4'][$numarray4]['nivel_5'][$numarray5]['nivel_6'][$numarray6]['datos_ciudadano'] = $row6;
							$contador_seccion[$id1][$row6['seccion']] = $contador_seccion[$id1][$row6['seccion']] + 1;
							$contador_seccion[$id2][$row6['seccion']] = $contador_seccion[$id2][$row6['seccion']] + 1;
							$contador_seccion[$id3][$row6['seccion']] = $contador_seccion[$id3][$row6['seccion']] + 1;
							$contador_seccion[$id4][$row6['seccion']] = $contador_seccion[$id4][$row6['seccion']] + 1;
							$contador_seccion[$id5][$row6['seccion']] = $contador_seccion[$id5][$row6['seccion']] + 1;
							$contador_seccion_tipo_ciudadano[$id1][$row6['seccion']][$row6['id_tipo_ciudadano']] = $contador_seccion_tipo_ciudadano[$id1][$row6['seccion']][$row6['id_tipo_ciudadano']] + 1;
							$contador_seccion_tipo_ciudadano[$id2][$row6['seccion']][$row6['id_tipo_ciudadano']] = $contador_seccion_tipo_ciudadano[$id2][$row6['seccion']][$row6['id_tipo_ciudadano']] + 1;
							$contador_seccion_tipo_ciudadano[$id3][$row6['seccion']][$row6['id_tipo_ciudadano']] = $contador_seccion_tipo_ciudadano[$id3][$row6['seccion']][$row6['id_tipo_ciudadano']] + 1;
							$contador_seccion_tipo_ciudadano[$id4][$row6['seccion']][$row6['id_tipo_ciudadano']] = $contador_seccion_tipo_ciudadano[$id4][$row6['seccion']][$row6['id_tipo_ciudadano']] + 1;
							$contador_seccion_tipo_ciudadano[$id5][$row6['seccion']][$row6['id_tipo_ciudadano']] = $contador_seccion_tipo_ciudadano[$id5][$row6['seccion']][$row6['id_tipo_ciudadano']] + 1;
							//!Nivel 7 ///////////////////////////////
							//! Buscams si tiene hijos
							$id6 = $row6['id'];
							$sql7 = "SELECT 
								sic.id,
								(SELECT p.nombre FROM plataformas p WHERE p.plataforma = sic.codigo_plataforma ) plataforma,
								sic.clave,
								sic.folio,
								sic.nombre_completo,
								sic.nombre,
								sic.apellido_paterno,
								sic.apellido_materno,
								sic.clave_elector,
								(SELECT LPAD(s.numero,4,0) FROM secciones_ine s WHERE s.id = sic.id_seccion_ine) seccion,
								sic.id_tipo_ciudadano,
								(SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = sic.id_tipo_ciudadano) tipo_ciudadano,
								(SELECT COUNT(*) FROM secciones_ine_ciudadanos sic1 WHERE sic1.id_seccion_ine_ciudadano_compartido = sic.id ) referidos,
								(SELECT dl.numero FROM distritos_locales dl WHERE dl.id = sic.id_distrito_local) distrito_local,
								(SELECT df.numero FROM distritos_federales df WHERE df.id = sic.id_distrito_federal) distrito_federal,
								(SELECT m.municipio FROM municipios m WHERE m.id = sic.id_municipio) municipio
								FROM secciones_ine_ciudadanos sic WHERE sic.id_seccion_ine_ciudadano_compartido = {$id6}";
							$resultado7 = $conexion->query($sql6);
							$numarray7 = 0;
							//$data['nivel_1'][$numarray1]['nivel_2'][$numarray2]['nivel_3'][$numarray3]['nivel_4'][$numarray4]['nivel_5'][$numarray5]['nivel_6'][$numarray6]['datos_ciudadano']['referidos'] = $resultado7->num_rows;
							$contador[$id1] =  $resultado7->num_rows+$contador[$id1];
							$contador[$id2] =  $resultado7->num_rows+$contador[$id2];
							$contador[$id3] =  $resultado7->num_rows+$contador[$id3];
							$contador[$id4] =  $resultado7->num_rows+$contador[$id4];
							$contador[$id5] =  $resultado7->num_rows+$contador[$id5];
							$contador[$id6] =  $resultado7->num_rows+$contador[$id6];
							while($row7=$resultado7->fetch_assoc()){
								$exel[]= array( 
									'color' => '#397cb5',
									'style' => array('fill'=>'#397cb5','color'=>'#FFFFFF','font-style'=>'bold'),
									'datos' => array('','','','','','','Sección','Clave','Folio','Tipo Ciudadano','Nombre Completo','Clave Elector','Municipio')
									);
								$exel[]= array( 
									'color' => '#fefafa',
									'datos' => array('','','','','','',$row7['seccion'],$row7['clave'],$row7['folio'],$row7['tipo_ciudadano'],$row7['nombre_completo'],$row7['clave_elector'],$row7['municipio'])
									);
								$data['nivel_1'][$numarray1]['nivel_2'][$numarray2]['nivel_3'][$numarray3]['nivel_4'][$numarray4]['nivel_5'][$numarray5]['nivel_6'][$numarray6]['nivel_7'][$numarray7]['datos_ciudadano'] = $row7;
								$contador_seccion[$id1][$row7['seccion']] = $contador_seccion[$id1][$row7['seccion']] + 1;
								$contador_seccion[$id2][$row7['seccion']] = $contador_seccion[$id2][$row7['seccion']] + 1;
								$contador_seccion[$id3][$row7['seccion']] = $contador_seccion[$id3][$row7['seccion']] + 1;
								$contador_seccion[$id4][$row7['seccion']] = $contador_seccion[$id4][$row7['seccion']] + 1;
								$contador_seccion[$id5][$row7['seccion']] = $contador_seccion[$id5][$row7['seccion']] + 1;
								$contador_seccion[$id6][$row7['seccion']] = $contador_seccion[$id6][$row7['seccion']] + 1;
								$contador_seccion_tipo_ciudadano[$id1][$row7['seccion']][$row7['id_tipo_ciudadano']] = $contador_seccion_tipo_ciudadano[$id1][$row7['seccion']][$row7['id_tipo_ciudadano']] + 1;
								$contador_seccion_tipo_ciudadano[$id2][$row7['seccion']][$row7['id_tipo_ciudadano']] = $contador_seccion_tipo_ciudadano[$id2][$row7['seccion']][$row7['id_tipo_ciudadano']] + 1;
								$contador_seccion_tipo_ciudadano[$id3][$row7['seccion']][$row7['id_tipo_ciudadano']] = $contador_seccion_tipo_ciudadano[$id3][$row7['seccion']][$row7['id_tipo_ciudadano']] + 1;
								$contador_seccion_tipo_ciudadano[$id4][$row7['seccion']][$row7['id_tipo_ciudadano']] = $contador_seccion_tipo_ciudadano[$id4][$row7['seccion']][$row7['id_tipo_ciudadano']] + 1;
								$contador_seccion_tipo_ciudadano[$id5][$row7['seccion']][$row7['id_tipo_ciudadano']] = $contador_seccion_tipo_ciudadano[$id5][$row7['seccion']][$row7['id_tipo_ciudadano']] + 1;
								$contador_seccion_tipo_ciudadano[$id6][$row7['seccion']][$row7['id_tipo_ciudadano']] = $contador_seccion_tipo_ciudadano[$id6][$row7['seccion']][$row7['id_tipo_ciudadano']] + 1;
								//////////////////////////////////////////
								$numarray7 ++;
							}
							//////////////////////////////////////////
							$numarray6 ++;
						}
						//////////////////////////////////////////
						$numarray5 ++;
					}
					//////////////////////////////////////////
					$numarray4 ++;
				}
				//////////////////////////////////////////
				$numarray3 ++;
			}
			//////////////////////////////////////////
			$numarray2 ++;
		}
		//////////////////////////////////////////
		$numarray1 ++;
		
	}
	if($RowPlataforma['tipo']!='x'){
		$sql .= " AND e.codigo_plataforma = '{$codigo_plataforma}' ";
		unset($search_database['plataforma']);
	}
	// getting records as per search parameters
	//$clave=$search_database['clave'];
	//if( $clave!="" ){   //name
	//	$post_search=true;
	//	$sql.=" AND e.clave LIKE '%{$clave}%' ";
	//	$sqlContador .= " AND e.clave LIKE '%{$clave}%' ";
	//} 
	$decryptedQuery = decrypt_ab_checkSin($_COOKIE['AB32BA51']);


	$numero =1;
	$page = 1;
	$result = $conexion->query($sql.$decryptedQuery);
	$color_reg = 1;
	// Suponiendo que $result es tu resultado de la consulta
	if($result->num_rows ==0){
		echo "NINGUN REGISTRO ENCONTRADO, VERIFIQUE SUS FILTROS.";
		die;
	}
	foreach ($exel as $key => $valueP) {
		
		foreach ($valueP['datos'] as $index => $rowP) {
			// Itera sobre cada fila de datos en $value['datos']
			$width = ceil(strlen($rowP) * 1.2); // Calcula el ancho de la celda (ajusta el factor según sea necesario)
			if($index==0){
				$maxWidths[$index] = 10; // Actualiza el ancho máximo si es necesario
			}else{
				if ($width > $maxWidths[$index]) {
					$maxWidths[$index] = $width; // Actualiza el ancho máximo si es necesario
				}
			}
		}
	}

	foreach ($exel as $key => $value) {
		
		if($value['started']==1){
			sleep(1);
			$txt = $value['datos'] [0]." - ".$value['datos'] [4];
			$page ++;
			//$writer->writeSheetHeader($txt, $header, [/*'auto_filter'=>true,*/ 'fill'=>'#397cb5','color'=>'#FFFFFF','font-style'=>'bold', 'widths'=>[10,20,30,40,50,60]] );
			$writer->writeSheetHeader($txt, $header, [/*'auto_filter'=>true,*/ 'fill'=>'#397cb5','color'=>'#FFFFFF','font-style'=>'bold', 'widths'=>$maxWidths] );
		}
		$numero ++;
		unset($styleRow);
		$marco = $color_reg % 2;
		foreach ($value['datos'] as $keyT => $valueT) {
			if(empty($value['style'])){
				if($valueT==''){
					$styleRow[] = array('fill' => '#FFFFFF','color'=>'#000000','border'=>'left,right,top,bottom');
				}else{
					$styleRow[] = array('fill' => $value['color'],'color'=>'#000000','border'=>'left,right,top,bottom');
				}	
			}else{
				if($valueT==''){
					$styleRow[] = array('fill' => '#FFFFFF','color'=>'#000000','border'=>'left,right,top,bottom');
				}else{
					$styleRow[] = array('fill'=>'#397cb5','color'=>'#FFFFFF','font-style'=>'bold');
				}
			}
		}
		$writer->writeSheetRow($txt, $value['datos'],$styleRow);
		// Calcular y ajustar el ancho de las columnas después de escribir la fila
		// Calcular y ajustar el ancho de las columnas después de escribir la fila
	}

	$writer->writeToFile($ruta_ftpExport.$filename); 
	//$writer->writeToStdOut();
	//echo '#'.floor((memory_get_peak_usage())/1024/1024)."MB"."\n";
	//exit();
	$end_time = microtime(true);
	$duration = $end_time - $start_time;
	$minutes = (int)($duration/60)-$hours*60;
	$seconds = (int)$duration-$hours*60*60-$minutes*60; 
	$tiempo =  "Tiempo para generar el archivo: <strong>" .$minutes.' minutos y '.$seconds.' segundos.</strong>';
?>
	<?= $tiempo ?>
	<br>
	<a href="<?= $ruta_ftpExport.$filename ?>" download="<?= $filename ?>"><?= $filename ?></a>
	<script type="text/javascript">
		document.getElementById("loader").style.display = "none";
		$('#text_sub').html('De click al link. Gracias');
		document.getElementById("stop").value=1;
		document.title = "Listo. descargelo";
	</script>