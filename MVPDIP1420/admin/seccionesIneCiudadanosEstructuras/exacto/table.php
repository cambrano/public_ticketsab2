<?php
	include __DIR__.'/../functions/security.php';
	@session_start();
	?>
	<script>
		function downloadExcel(){
			<?php
			if($pageService != $_COOKIE["pageService"]){
				if($pageService!=''){
					$_COOKIE["pageService"] = $pageService ;
				}
			}
			?>
			var searchTable = [];
			var data = {
					'ck' : 1, 
				}
			//!searchTable.push(data);
			//!var link="seccionesIneCiudadanos/excel/index.php?cot=<?=$_COOKIE['pageService']?>"; 
			// Crear un formulario oculto en el documento
			// Crear un formulario oculto en el documento
			var form = document.createElement('form');
			form.method = 'POST';
			form.action = 'seccionesIneCiudadanosEstructuras/excel/index.php?cot=<?=$_COOKIE['pageService']?>'; // URL de destino

			// Iterar a través del objeto data y crear campos de entrada
			for (var key in data) {
				if (data.hasOwnProperty(key)) {
					var input = document.createElement('input');
					input.type = 'hidden';
					input.name = 'form_excel_' + key; // Agrega el prefijo "form_excel_" al nombre
					input.id = 'form_excel_' + key; // Agrega el prefijo "form_excel_" al ID
					input.value = data[key]; // Asigna el valor desde el objeto data

					// Agregar el campo de entrada al formulario
					form.appendChild(input);
				}
			}

			// Agregar el formulario al cuerpo del documento (opcional)
			document.body.appendChild(form);

			// Función para abrir la nueva página y enviar el formulario
			function openNewPageAndSubmitForm() {
				// Abre una nueva página
				var nuevaVentana = window.open('about:blank');
				
				// Asigna el formulario al contenido de la nueva ventana
				nuevaVentana.document.body.appendChild(form);
				
				// Enviar el formulario en la nueva ventana
				form.submit();
			}

			// Llamar a la función para abrir la nueva página y enviar el formulario
			openNewPageAndSubmitForm();


			
			//window.open(link);
			//window.open(link,'newWindow','width=1280, height=460'); return false;
			//document.location = link;
			//!window.open(link); 
		}
		function downloadExcel1(){
			<?php
			if($pageService != $_COOKIE["pageService"]){
				if($pageService!=''){
					$_COOKIE["pageService"] = $pageService ;
				}
			}
			?>
			var searchTable = [];
			var data = {
					'ck' : 1, 
				}
			//!searchTable.push(data);
			//!var link="seccionesIneCiudadanos/excel/index.php?cot=<?=$_COOKIE['pageService']?>"; 
			// Crear un formulario oculto en el documento
			// Crear un formulario oculto en el documento
			var form = document.createElement('form');
			form.method = 'POST';
			form.action = 'seccionesIneCiudadanosEstructuras/excel/index_separada.php?cot=<?=$_COOKIE['pageService']?>'; // URL de destino

			// Iterar a través del objeto data y crear campos de entrada
			for (var key in data) {
				if (data.hasOwnProperty(key)) {
					var input = document.createElement('input');
					input.type = 'hidden';
					input.name = 'form_excel_' + key; // Agrega el prefijo "form_excel_" al nombre
					input.id = 'form_excel_' + key; // Agrega el prefijo "form_excel_" al ID
					input.value = data[key]; // Asigna el valor desde el objeto data

					// Agregar el campo de entrada al formulario
					form.appendChild(input);
				}
			}

			// Agregar el formulario al cuerpo del documento (opcional)
			document.body.appendChild(form);

			// Función para abrir la nueva página y enviar el formulario
			function openNewPageAndSubmitForm() {
				// Abre una nueva página
				var nuevaVentana = window.open('about:blank');
				
				// Asigna el formulario al contenido de la nueva ventana
				nuevaVentana.document.body.appendChild(form);
				
				// Enviar el formulario en la nueva ventana
				form.submit();
			}

			// Llamar a la función para abrir la nueva página y enviar el formulario
			openNewPageAndSubmitForm();


			
			//window.open(link);
			//window.open(link,'newWindow','width=1280, height=460'); return false;
			//document.location = link;
			//!window.open(link); 
		}
		function downloadExcel2(){
			<?php
			if($pageService != $_COOKIE["pageService"]){
				if($pageService!=''){
					$_COOKIE["pageService"] = $pageService ;
				}
			}
			?>
			var searchTable = [];
			var data = {
					'ck' : 1, 
				}
			//!searchTable.push(data);
			//!var link="seccionesIneCiudadanos/excel/index.php?cot=<?=$_COOKIE['pageService']?>"; 
			// Crear un formulario oculto en el documento
			// Crear un formulario oculto en el documento
			var form = document.createElement('form');
			form.method = 'POST';
			form.action = 'seccionesIneCiudadanosEstructuras/excel/index_lineal.php?cot=<?=$_COOKIE['pageService']?>'; // URL de destino

			// Iterar a través del objeto data y crear campos de entrada
			for (var key in data) {
				if (data.hasOwnProperty(key)) {
					var input = document.createElement('input');
					input.type = 'hidden';
					input.name = 'form_excel_' + key; // Agrega el prefijo "form_excel_" al nombre
					input.id = 'form_excel_' + key; // Agrega el prefijo "form_excel_" al ID
					input.value = data[key]; // Asigna el valor desde el objeto data

					// Agregar el campo de entrada al formulario
					form.appendChild(input);
				}
			}

			// Agregar el formulario al cuerpo del documento (opcional)
			document.body.appendChild(form);

			// Función para abrir la nueva página y enviar el formulario
			function openNewPageAndSubmitForm() {
				// Abre una nueva página
				var nuevaVentana = window.open('about:blank');
				
				// Asigna el formulario al contenido de la nueva ventana
				nuevaVentana.document.body.appendChild(form);
				
				// Enviar el formulario en la nueva ventana
				form.submit();
			}

			// Llamar a la función para abrir la nueva página y enviar el formulario
			openNewPageAndSubmitForm();


			
			//window.open(link);
			//window.open(link,'newWindow','width=1280, height=460'); return false;
			//document.location = link;
			//!window.open(link); 
		}
	</script>
<?php
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
				$resultado1 = $conexion->query($sql1);
				$numarray1 = 0;
				while($row1=$resultado1->fetch_assoc()){
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
				//echo "<pre>";
				//var_dump($contador_seccion_tipo_ciudadano);
				//echo "</pre>";
			?>
			<style>
				.accordion {
					max-width: 100%;
					margin: 0 auto;
				}

				.accordion-item {
					margin-bottom: 10px;
				}

				.accordion-header {
					background-color: #f4f4f4;
					padding: 10px;
					cursor: pointer;
				}

				.accordion-content {
					display: none;
					padding: 10px;
				}

				/* Agregar estilo para los diferentes niveles */
				.accordion-level-2 {
					margin-left: 20px;
				}

				.background-2{
					background-color:#ECEE81;
				}

				.accordion-level-3 {
					margin-left: 40px;
				}

				.background-3{
					background-color:#8DDFCB;
				}

				.accordion-level-4 {
					margin-left: 60px;
				}

				.background-4{
					background-color:#82A0D8;
				}

				.accordion-level-5 {
					margin-left: 80px;
				}

				.background-5{
					background-color:#EDB7ED;
				}

				.accordion-level-6 {
					margin-left: 100px;
				}

				.background-6{
					background-color:#F6F7C4;
				}

				.accordion-level-7 {
					margin-left: 120px;
				}

				.background-7{
					background-color:#F6D6D6;
				}
			</style>
			<div class="accordion">
				<?php
				foreach ($data as $nivel_1 => $datos_ciudadano_1) {
					foreach ($datos_ciudadano_1 as $key => $value) {
						?>
						<div class="accordion-item">
							<div class="accordion-header">
								<table style="width:100%;border-collapse: collapse;">
									<td style="padding:10px; border-right: thick double #32a1ce;width:40px"><?= $key+1 ?></td>
									<td style="padding:10px;">
										<table style="width: 100%;">
											<tr>
												<td><b>Plataforma</b> : <?= $value['datos_ciudadano']['plataforma'] ?></td>
												<td><b>Nivel 1</b> : <?= $value['datos_ciudadano']['tipo_ciudadano'] ?></td>
											</tr>
											
											<tr>
												<td colspan="2" style="text-align:left;">
													<table style="width:100%; table-layout:fixed;border-collapse: collapse;">
														<tr>
															<td colspan="3"><b>Municipio</b> : <?= $value['datos_ciudadano']['municipio'] ?></td>
														</tr>
														<tr>
														<td><b>Sección</b> : <?= $value['datos_ciudadano']['seccion'] ?></td>
														<td><b>DL</b> : <?= $value['datos_ciudadano']['distrito_local'] ?></td>
														<td><b>DF</b> : <?= $value['datos_ciudadano']['distrito_federal'] ?></td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td ><b>Clave SIC</b> : <?= $value['datos_ciudadano']['clave'] ?></td>
												<td ><b>FOLIO</b> : <?= $value['datos_ciudadano']['folio'] ?></td>
											</tr>
											<tr>
												<td colspan="2"><b>Nombre Completo</b> : <?= $value['datos_ciudadano']['nombre_completo'] ?></td>
											</tr>
											<tr>
												<td><b>Refereridos Directos</b> : <?= $value['datos_ciudadano']['referidos'] ?></td>
												<td><b>Refereridos Totales</b> : <?= $contador[$value['datos_ciudadano']['id']] ?></td>
											</tr>
											<tr>
												<td colspan="2">
													<?php
													foreach ($contador_seccion1[$value['datos_ciudadano']['id']] as $seccion => $totales) {
														echo "<b>Sección</b> : ".$seccion;
														echo "<blockquote>";
														foreach ($contador_seccion_tipo_ciudadano[$value['datos_ciudadano']['id']][$seccion] as $tipo_ciudadano => $totales_tipo) {
															?>
															<div style="border-bottom:1px solid;"><b><?= $tipos_ciudadanos_array[$tipo_ciudadano] ?></b> : <?= $totales_tipo ?></div>
															<?php
														}
														echo "<b>Total</b>: ".$totales;
														echo "</blockquote>";
														echo "<br>";
													}
													?>
												</td>
											</tr>
										</table>
									</td>
								</table>
							</div>
							<?php
							if(empty($value['nivel_2'])){
								?>
								<!--<div class="accordion-content">
									No tiene estructura mas abajo.
								</div>-->
								<?php
							}else{
								?>
								<div class="accordion-content">
									<div class="accordion accordion-level-2">
										<?php
										foreach ($value['nivel_2'] as $key2 => $value2) {
											?>
											<div class="accordion-item">
												<div class="accordion-header background-2">
													<table style="width:100%;border-collapse: collapse;">
														<td style="padding:10px; border-right: thick double #32a1ce;width:40px"><?= $key+1 ?></td>
														<td style="padding:10px;">
															<table style="width: 100%;">
																<tr>
																	<td><b>Plataforma</b> : <?= $value2['datos_ciudadano']['plataforma'] ?></td>
																	<td><b>Nivel 2</b> : <?= $value2['datos_ciudadano']['tipo_ciudadano'] ?></td>
																</tr>
																
																<tr>
																	<td colspan="2" style="text-align:left;">
																		<table style="width:100%; table-layout:fixed;border-collapse: collapse;">
																			<tr>
																				<td colspan="3"><b>Municipio</b> : <?= $value2['datos_ciudadano']['municipio'] ?></td>
																			</tr>
																			<tr>
																			<td><b>Sección</b> : <?= $value2['datos_ciudadano']['seccion'] ?></td>
																			<td><b>DL</b> : <?= $value2['datos_ciudadano']['distrito_local'] ?></td>
																			<td><b>DF</b> : <?= $value2['datos_ciudadano']['distrito_federal'] ?></td>
																			</tr>
																		</table>
																	</td>
																</tr>
																<tr>
																	<td ><b>Clave SIC</b> : <?= $value2['datos_ciudadano']['clave'] ?></td>
																	<td ><b>FOLIO</b> : <?= $value2['datos_ciudadano']['folio'] ?></td>
																</tr>
																<tr>
																	<td colspan="2"><b>Nombre Completo</b> : <?= $value2['datos_ciudadano']['nombre_completo'] ?></td>
																</tr>
																<tr>
																	<td><b>Refereridos Directos</b> : <?= $value2['datos_ciudadano']['referidos'] ?></td>
																	<td><b>Refereridos Totales</b> : <?= $contador[$value2['datos_ciudadano']['id']] ?></td>
																</tr>
																<tr>
																	<td colspan="2">
																		<?php
																		foreach ($contador_seccion1[$value2['datos_ciudadano']['id']] as $seccion => $totales) {
																			echo "<b>Sección</b> : ".$seccion;
																			echo "<blockquote>";
																			foreach ($contador_seccion_tipo_ciudadano[$value2['datos_ciudadano']['id']][$seccion] as $tipo_ciudadano => $totales_tipo) {
																				?>
																				<div style="border-bottom:1px solid;"><b><?= $tipos_ciudadanos_array[$tipo_ciudadano] ?></b> : <?= $totales_tipo ?></div>
																				<?php
																			}
																			echo "<b>Total</b>: ".$totales;
																			echo "</blockquote>";
																			echo "<br>";
																		}
																		?>
																	</td>
																</tr>
															</table>
														</td>
													</table>
												</div>
												<?php
												if(empty($value2['nivel_3'])){
													?>
													<!--<div class="accordion-content">
														No tiene estructura mas abajo.
													</div>-->
													<?php
												}else{
													?>
													<div class="accordion-content">
														<div class="accordion accordion-level-3">
															<?php
															foreach ($value2['nivel_3'] as $key3 => $value3) {
																?>
																<div class="accordion-item">
																	<div class="accordion-header background-3">
																		<table style="width:100%;border-collapse: collapse;">
																			<td style="padding:10px; border-right: thick double #32a1ce;width:40px"><?= $key+1 ?></td>
																			<td style="padding:10px;">
																				<table style="width: 100%;">
																					<tr>
																						<td><b>Plataforma</b> : <?= $value3['datos_ciudadano']['plataforma'] ?></td>
																						<td><b>Nivel 3</b> : <?= $value3['datos_ciudadano']['tipo_ciudadano'] ?></td>
																					</tr>
																					
																					<tr>
																						<td colspan="2" style="text-align:left;">
																							<table style="width:100%; table-layout:fixed;border-collapse: collapse;">
																								<tr>
																									<td colspan="3"><b>Municipio</b> : <?= $value3['datos_ciudadano']['municipio'] ?></td>
																								</tr>
																								<tr>
																								<td><b>Sección</b> : <?= $value3['datos_ciudadano']['seccion'] ?></td>
																								<td><b>DL</b> : <?= $value3['datos_ciudadano']['distrito_local'] ?></td>
																								<td><b>DF</b> : <?= $value3['datos_ciudadano']['distrito_federal'] ?></td>
																								</tr>
																							</table>
																						</td>
																					</tr>
																					<tr>
																						<td ><b>Clave SIC</b> : <?= $value3['datos_ciudadano']['clave'] ?></td>
																						<td ><b>FOLIO</b> : <?= $value3['datos_ciudadano']['folio'] ?></td>
																					</tr>
																					<tr>
																						<td colspan="2"><b>Nombre Completo</b> : <?= $value3['datos_ciudadano']['nombre_completo'] ?></td>
																					</tr>
																					<tr>
																						<td><b>Refereridos Directos</b> : <?= $value3['datos_ciudadano']['referidos'] ?></td>
																						<td><b>Refereridos Totales</b> : <?= $contador[$value3['datos_ciudadano']['id']] ?></td>
																					</tr>
																					<tr>
																						<td colspan="2">
																							<?php
																							foreach ($contador_seccion1[$value3['datos_ciudadano']['id']] as $seccion => $totales) {
																								echo "<b>Sección</b> : ".$seccion;
																								echo "<blockquote>";
																								foreach ($contador_seccion_tipo_ciudadano[$value3['datos_ciudadano']['id']][$seccion] as $tipo_ciudadano => $totales_tipo) {
																									?>
																									<div style="border-bottom:1px solid;"><b><?= $tipos_ciudadanos_array[$tipo_ciudadano] ?></b> : <?= $totales_tipo ?></div>
																									<?php
																								}
																								echo "<b>Total</b>: ".$totales;
																								echo "</blockquote>";
																								echo "<br>";
																							}
																							?>
																						</td>
																					</tr>
																				</table>
																			</td>
																		</table>
																	</div>
																	<?php
																	if(empty($value3['nivel_4'])){
																		?>
																		<!--<div class="accordion-content">
																			No tiene estructura mas abajo.
																		</div>-->
																		<?php
																	}else{
																		?>
																		<div class="accordion-content">
																			<div class="accordion accordion-level-4">
																				<?php
																				foreach ($value3['nivel_4'] as $key4 => $value4) {
																					?>
																					<div class="accordion-item">
																						<div class="accordion-header background-4">
																							<table style="width:100%;border-collapse: collapse;">
																								<td style="padding:10px; border-right: thick double #32a1ce;width:40px"><?= $key+1 ?></td>
																								<td style="padding:10px;">
																									<table style="width: 100%;">
																										<tr>
																											<td><b>Plataforma</b> : <?= $value4['datos_ciudadano']['plataforma'] ?></td>
																											<td><b>Nivel 4</b> : <?= $value4['datos_ciudadano']['tipo_ciudadano'] ?></td>
																										</tr>
																										
																										<tr>
																											<td colspan="2" style="text-align:left;">
																												<table style="width:100%; table-layout:fixed;border-collapse: collapse;">
																													<tr>
																														<td colspan="3"><b>Municipio</b> : <?= $value4['datos_ciudadano']['municipio'] ?></td>
																													</tr>
																													<tr>
																													<td><b>Sección</b> : <?= $value4['datos_ciudadano']['seccion'] ?></td>
																													<td><b>DL</b> : <?= $value4['datos_ciudadano']['distrito_local'] ?></td>
																													<td><b>DF</b> : <?= $value4['datos_ciudadano']['distrito_federal'] ?></td>
																													</tr>
																												</table>
																											</td>
																										</tr>
																										<tr>
																											<td ><b>Clave SIC</b> : <?= $value4['datos_ciudadano']['clave'] ?></td>
																											<td ><b>FOLIO</b> : <?= $value4['datos_ciudadano']['folio'] ?></td>
																										</tr>
																										<tr>
																											<td colspan="2"><b>Nombre Completo</b> : <?= $value4['datos_ciudadano']['nombre_completo'] ?></td>
																										</tr>
																										<tr>
																											<td><b>Refereridos Directos</b> : <?= $value4['datos_ciudadano']['referidos'] ?></td>
																											<td><b>Refereridos Totales</b> : <?= $contador[$value4['datos_ciudadano']['id']] ?></td>
																										</tr>
																										<tr>
																											<td colspan="2">
																												<?php
																												foreach ($contador_seccion1[$value4['datos_ciudadano']['id']] as $seccion => $totales) {
																													echo "<b>Sección</b> : ".$seccion;
																													echo "<blockquote>";
																													foreach ($contador_seccion_tipo_ciudadano[$value4['datos_ciudadano']['id']][$seccion] as $tipo_ciudadano => $totales_tipo) {
																														?>
																														<div style="border-bottom:1px solid;"><b><?= $tipos_ciudadanos_array[$tipo_ciudadano] ?></b> : <?= $totales_tipo ?></div>
																														<?php
																													}
																													echo "<b>Total</b>: ".$totales;
																													echo "</blockquote>";
																													echo "<br>";
																												}
																												?>
																											</td>
																										</tr>
																									</table>
																								</td>
																							</table>
																						</div>
																						<?php
																						if(empty($value4['nivel_5'])){
																							?>
																							<!--<div class="accordion-content">
																								No tiene estructura mas abajo.
																							</div>-->
																							<?php
																						}else{
																							?>
																							<div class="accordion-content">
																								<div class="accordion accordion-level-5">
																									<?php
																									foreach ($value4['nivel_5'] as $key5 => $value5) {
																										?>
																										<div class="accordion-item">
																											<div class="accordion-header background-5">
																												<table style="width:100%;border-collapse: collapse;">
																													<td style="padding:10px; border-right: thick double #32a1ce;width:40px"><?= $key+1 ?></td>
																													<td style="padding:10px;">
																														<table style="width: 100%;">
																															<tr>
																																<td><b>Plataforma</b> : <?= $value5['datos_ciudadano']['plataforma'] ?></td>
																																<td><b>Nivel 5</b> : <?= $value5['datos_ciudadano']['tipo_ciudadano'] ?></td>
																															</tr>
																															
																															<tr>
																																<td colspan="2" style="text-align:left;">
																																	<table style="width:100%; table-layout:fixed;border-collapse: collapse;">
																																		<tr>
																																			<td colspan="3"><b>Municipio</b> : <?= $value5['datos_ciudadano']['municipio'] ?></td>
																																		</tr>
																																		<tr>
																																		<td><b>Sección</b> : <?= $value5['datos_ciudadano']['seccion'] ?></td>
																																		<td><b>DL</b> : <?= $value5['datos_ciudadano']['distrito_local'] ?></td>
																																		<td><b>DF</b> : <?= $value5['datos_ciudadano']['distrito_federal'] ?></td>
																																		</tr>
																																	</table>
																																</td>
																															</tr>
																															<tr>
																																<td ><b>Clave SIC</b> : <?= $value5['datos_ciudadano']['clave'] ?></td>
																																<td ><b>FOLIO</b> : <?= $value5['datos_ciudadano']['folio'] ?></td>
																															</tr>
																															<tr>
																																<td colspan="2"><b>Nombre Completo</b> : <?= $value5['datos_ciudadano']['nombre_completo'] ?></td>
																															</tr>
																															<tr>
																																<td><b>Refereridos Directos</b> : <?= $value5['datos_ciudadano']['referidos'] ?></td>
																																<td><b>Refereridos Totales</b> : <?= $contador[$value5['datos_ciudadano']['id']] ?></td>
																															</tr>
																															<tr>
																																<td colspan="2">
																																	<?php
																																	foreach ($contador_seccion1[$value5['datos_ciudadano']['id']] as $seccion => $totales) {
																																		echo "<b>Sección</b> : ".$seccion;
																																		echo "<blockquote>";
																																		foreach ($contador_seccion_tipo_ciudadano[$value5['datos_ciudadano']['id']][$seccion] as $tipo_ciudadano => $totales_tipo) {
																																			?>
																																			<div style="border-bottom:1px solid;"><b><?= $tipos_ciudadanos_array[$tipo_ciudadano] ?></b> : <?= $totales_tipo ?></div>
																																			<?php
																																		}
																																		echo "<b>Total</b>: ".$totales;
																																		echo "</blockquote>";
																																		echo "<br>";
																																	}
																																	?>
																																</td>
																															</tr>
																														</table>
																													</td>
																												</table>
																											</div>
																											<?php
																											if(empty($value5['nivel_6'])){
																												?>
																												<!--<div class="accordion-content">
																													No tiene estructura mas abajo.
																												</div>-->
																												<?php
																											}else{
																												?>
																												<div class="accordion-content">
																													<div class="accordion accordion-level-6">
																														<?php
																														foreach ($value5['nivel_6'] as $key6 => $value6) {
																															?>
																															<div class="accordion-item">
																																<div class="accordion-header background-6">
																																	<table style="width:100%;border-collapse: collapse;">
																																		<td style="padding:10px; border-right: thick double #32a1ce;width:40px"><?= $key+1 ?></td>
																																		<td style="padding:10px;">
																																			<table style="width: 100%;">
																																				<tr>
																																					<td><b>Plataforma</b> : <?= $value6['datos_ciudadano']['plataforma'] ?></td>
																																					<td><b>Nivel 6</b> : <?= $value6['datos_ciudadano']['tipo_ciudadano'] ?></td>
																																				</tr>
																																				
																																				<tr>
																																					<td colspan="2" style="text-align:left;">
																																						<table style="width:100%; table-layout:fixed;border-collapse: collapse;">
																																							<tr>
																																								<td colspan="3"><b>Municipio</b> : <?= $value6['datos_ciudadano']['municipio'] ?></td>
																																							</tr>
																																							<tr>
																																							<td><b>Sección</b> : <?= $value6['datos_ciudadano']['seccion'] ?></td>
																																							<td><b>DL</b> : <?= $value6['datos_ciudadano']['distrito_local'] ?></td>
																																							<td><b>DF</b> : <?= $value6['datos_ciudadano']['distrito_federal'] ?></td>
																																							</tr>
																																						</table>
																																					</td>
																																				</tr>
																																				<tr>
																																					<td ><b>Clave SIC</b> : <?= $value6['datos_ciudadano']['clave'] ?></td>
																																					<td ><b>FOLIO</b> : <?= $value6['datos_ciudadano']['folio'] ?></td>
																																				</tr>
																																				<tr>
																																					<td colspan="2"><b>Nombre Completo</b> : <?= $value6['datos_ciudadano']['nombre_completo'] ?></td>
																																				</tr>
																																				<tr>
																																					<td><b>Refereridos Directos</b> : <?= $value6['datos_ciudadano']['referidos'] ?></td>
																																					<td><b>Refereridos Totales</b> : <?= $contador[$value6['datos_ciudadano']['id']] ?></td>
																																				</tr>
																																				<tr>
																																					<td colspan="2">
																																						<?php
																																						foreach ($contador_seccion1[$value6['datos_ciudadano']['id']] as $seccion => $totales) {
																																							echo "<b>Sección</b> : ".$seccion;
																																							echo "<blockquote>";
																																							foreach ($contador_seccion_tipo_ciudadano[$value6['datos_ciudadano']['id']][$seccion] as $tipo_ciudadano => $totales_tipo) {
																																								?>
																																								<div style="border-bottom:1px solid;"><b><?= $tipos_ciudadanos_array[$tipo_ciudadano] ?></b> : <?= $totales_tipo ?></div>
																																								<?php
																																							}
																																							echo "<b>Total</b>: ".$totales;
																																							echo "</blockquote>";
																																							echo "<br>";
																																						}
																																						?>
																																					</td>
																																				</tr>
																																			</table>
																																		</td>
																																	</table>
																																</div>
																															</div>
																															<?php
																															if(empty($value6['nivel_7'])){
																																?>
																																<!--<div class="accordion-content">
																																	No tiene estructura mas abajo.
																																</div>-->
																																<?php
																															}else{
																																?>
																																<div class="accordion-content">
																																	<div class="accordion accordion-level-7">
																																		<?php
																																		foreach ($value6['nivel_7'] as $key7 => $value7) {
																																			?>
																																			<div class="accordion-item">
																																				<div class="accordion-header background-7">
																																					<table style="width:100%;border-collapse: collapse;">
																																						<td style="padding:10px; border-right: thick double #32a1ce;width:40px"><?= $key+1 ?></td>
																																						<td style="padding:10px;">
																																							<table style="width: 100%;">
																																								<tr>
																																									<td><b>Plataforma</b> : <?= $value7['datos_ciudadano']['plataforma'] ?></td>
																																									<td><b>Nivel 7</b> : <?= $value7['datos_ciudadano']['tipo_ciudadano'] ?></td>
																																								</tr>
																																								
																																								<tr>
																																									<td colspan="2" style="text-align:left;">
																																										<table style="width:100%; table-layout:fixed;border-collapse: collapse;">
																																											<tr>
																																												<td colspan="3"><b>Municipio</b> : <?= $value7['datos_ciudadano']['municipio'] ?></td>
																																											</tr>
																																											<tr>
																																											<td><b>Sección</b> : <?= $value7['datos_ciudadano']['seccion'] ?></td>
																																											<td><b>DL</b> : <?= $value7['datos_ciudadano']['distrito_local'] ?></td>
																																											<td><b>DF</b> : <?= $value7['datos_ciudadano']['distrito_federal'] ?></td>
																																											</tr>
																																										</table>
																																									</td>
																																								</tr>
																																								<tr>
																																									<td ><b>Clave SIC</b> : <?= $value7['datos_ciudadano']['clave'] ?></td>
																																									<td ><b>FOLIO</b> : <?= $value7['datos_ciudadano']['folio'] ?></td>
																																								</tr>
																																								<tr>
																																									<td colspan="2"><b>Nombre Completo</b> : <?= $value7['datos_ciudadano']['nombre_completo'] ?></td>
																																								</tr>
																																								<tr>
																																									<td><b>Refereridos Directos</b> : <?= $value7['datos_ciudadano']['referidos'] ?></td>
																																									<td><b>Refereridos Totales</b> : <?= $contador[$value7['datos_ciudadano']['id']] ?></td>
																																								</tr>
																																								<tr>
																																									<td colspan="2">
																																										<?php
																																										foreach ($contador_seccion1[$value7['datos_ciudadano']['id']] as $seccion => $totales) {
																																											echo "<b>Sección</b> : ".$seccion;
																																											echo "<blockquote>";
																																											foreach ($contador_seccion_tipo_ciudadano[$value7['datos_ciudadano']['id']][$seccion] as $tipo_ciudadano => $totales_tipo) {
																																												?>
																																												<div style="border-bottom:1px solid;"><b><?= $tipos_ciudadanos_array[$tipo_ciudadano] ?></b> : <?= $totales_tipo ?></div>
																																												<?php
																																											}
																																											echo "<b>Total</b>: ".$totales;
																																											echo "</blockquote>";
																																											echo "<br>";
																																										}
																																										?>
																																									</td>
																																								</tr>
																																							</table>
																																						</td>
																																					</table>
																																				</div>
																																				<?php
																																				if(empty($value7['nivel_8'])){
																																					?>
																																					<div class="accordion-content">
																																						No tiene estructura mas abajo.
																																					</div>
																																					<?php
																																				}
																																				?>
																																			</div>
																																			<?php
																																		}
																																		?>
																																	</div>
																																</div>
																																<?php
																															}
																														}
																														?>
																													</div>
																												</div>
																											<?php
																											}
																											?>
																										</div>
																										<?php
																									}
																									?>
																								</div>
																							</div>
																							<?php
																						}
																						?>
																					</div>
																					<?php
																				}
																				?>
																			</div>
																		</div>
																		<?php
																	}
																	?>
																</div>
																<?php
															}
															?>
														</div>
													</div>
													<?php
												}
												?>
											</div>
											<?php
										}
										?>
									</div>
								</div>
								<?php
							}
							?>
							
						</div>
						<?php
					}
				}
				?>
			</div>
			<script>
				document.addEventListener('DOMContentLoaded', function() {
					const accordionHeaders = document.querySelectorAll('.accordion-header');

					accordionHeaders.forEach(function(header) {
						header.addEventListener('click', function() {
							const accordionItem = this.parentElement;
							const accordionContent = accordionItem.querySelector('.accordion-content');

							if (accordionContent.style.display === 'block') {
								accordionContent.style.display = 'none';
							} else {
								accordionContent.style.display = 'block';
							}
						});
					});
				});
			</script>

