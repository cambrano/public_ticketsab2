<?php
		function rutaEfs() {
			$carpeta_files = '/Volumes/efsczm/ftpFiles/files/';
			$carpeta_files = $_SERVER['DOCUMENT_ROOT'].'/MVPDIP1420/admin/ftpFiles/files/';
			return $carpeta_files;
		}

		function rutaEfsSaveFileInternos() {
			$carpeta_files = '/Volumes/efsczm/ftpFiles/documentosExport/';
			$carpeta_files = $_SERVER['DOCUMENT_ROOT'].'/MVPDIP1420/admin/ftpFiles/documentosExport/';
			return $carpeta_files;
		}

		function mostrarImagenBase64($archivo=null) {
			$carpeta_files = '/Volumes/efsczm/ftpFiles/files/';
			$no_file = '/Volumes/efsczm/ftpFiles/file_roto.gif';

			$carpeta_files = $_SERVER['DOCUMENT_ROOT'].'/MVPDIP1420/admin/ftpFiles/files/';
			$no_file = $_SERVER['DOCUMENT_ROOT'].'/MVPDIP1420/admin/ftpFiles/file_roto.gif';
			if($archivo != ""){
				if (file_exists($carpeta_files.$archivo)) {
					$imagen_data = file_get_contents($carpeta_files.$archivo);
					$base64 = base64_encode($imagen_data);
					
				} else {
					$imagen_data = file_get_contents($no_file);
					$base64 = base64_encode($imagen_data);
				}
			}else{
				$imagen_data = file_get_contents($no_file);
				$base64 = base64_encode($imagen_data);
			}
			return $base64;
		}

		function mostrarImagenBase64API($archivo=null) {
			$carpeta_files = '/Volumes/efsczm/ftpFiles/files/';
			$no_file = '/Volumes/efsczm/ftpFiles/file_roto.gif';

			$carpeta_files = $_SERVER['DOCUMENT_ROOT'].'/MVPDIP1420/admin/ftpFiles/files/';
			$no_file = $_SERVER['DOCUMENT_ROOT'].'/MVPDIP1420/admin/ftpFiles/file_roto.gif';
			if($archivo != ""){
				if (file_exists($carpeta_files.$archivo)) {
					$imagen_data = file_get_contents($carpeta_files.$archivo);
					$base64 = base64_encode($imagen_data);
					$status = 1;
				} else {
					$imagen_data = file_get_contents($no_file);
					$base64 = base64_encode($imagen_data);
					$status = 0;
				}
			}else{
				$imagen_data = file_get_contents($no_file);
				$base64 = base64_encode($imagen_data);
				$status = 0;
			}
			$dataimg['img'] = $base64;
			$dataimg['status'] = $status;
			return $dataimg;
		}

		function mostrarImagenNormal($archivo=null) {
			$carpeta_files = '/Volumes/efsczm/ftpFiles/files/';
			$no_file = '/Volumes/efsczm/ftpFiles/file_roto.gif';

			$carpeta_files = $_SERVER['DOCUMENT_ROOT'].'/MVPDIP1420/admin/ftpFiles/files/';
			$no_file = $_SERVER['DOCUMENT_ROOT'].'/MVPDIP1420/admin/ftpFiles/file_roto.gif';

			$ruta = $carpeta_files.$archivo;
			if (file_exists($ruta)) {
				$file_extension = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));
				$mime_types = [
					'pdf'  => 'application/pdf',
				    'gif'  => 'image/gif',
				    'jpg'  => 'image/jpeg',
				    'jpeg' => 'image/jpeg',
				    'png'  => 'image/png',
				    'bmp'  => 'image/bmp',
				    'tiff' => 'image/tiff',
				    'txt'  => 'text/plain',
				    'html' => 'text/html',
				    'xml'  => 'application/xml',
				    'json' => 'application/json',
				    'csv'  => 'text/csv',
				    'zip'  => 'application/zip',
		            // Add more MIME types as needed
		        ];
		        if (array_key_exists($file_extension, $mime_types)) {
		            $mime_type = $mime_types[$file_extension];
		        } else {
		            // Default to application/octet-stream for unknown file types
		            $mime_type = 'application/octet-stream';
		        }
		        header('Content-Transfer-Encoding: binary');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				header('Expires: 0');
		        header("Content-type: $mime_type");
		        header("Content-length: " . filesize($ruta));
		        header("Content-Disposition: inline; name={$archivo}; filename={$archivo}");
		        header('Content-Transfer-Encoding: binary');
		        ////readfile($ruta);
		        echo file_get_contents($ruta);
			} else {
		        $mime = mime_content_type($no_file);
		        header("Content-type: {$mime}");
		        header("Content-length: " . filesize($no_file));
		        header("Content-Disposition: inline; filename=$archivo");
		        readfile($no_file);
		    }
		}

		function mostrarImagenNormal1($archivo=null) {
			$carpeta_files = '/Volumes/efsczm/ftpFiles/files/';
			$no_file = '/Volumes/efsczm/ftpFiles/file_roto.gif';

			$carpeta_files = $_SERVER['DOCUMENT_ROOT'].'/MVPDIP1420/admin/ftpFiles/files/';
			$no_file = $_SERVER['DOCUMENT_ROOT'].'/MVPDIP1420/admin/ftpFiles/file_roto.gif';
			if ($archivo != "") {
				if (file_exists($carpeta_files . $archivo)) {
					$imagen_data = file_get_contents($carpeta_files . $archivo);
					$tipo_mime = mime_content_type($carpeta_files . $archivo);

					// Configurar las cabeceras según el tipo MIME
					header("Content-type: $tipo_mime");

					// Imprimir la imagen directamente
					echo $imagen_data;
					exit;
				} else {
					// Si el archivo no existe, mostrar la imagen de error
					$imagen_data = file_get_contents($no_file);
					$tipo_mime = mime_content_type($no_file);

					// Configurar las cabeceras según el tipo MIME
					header("Content-type: $tipo_mime");

					// Imprimir la imagen de error directamente
					echo $imagen_data;
					exit;
				}
			} else {
				// Si no se proporciona un nombre de archivo, mostrar la imagen de error
				$imagen_data = file_get_contents($no_file);
				$tipo_mime = mime_content_type($no_file);

				// Configurar las cabeceras según el tipo MIME
				header("Content-type: $tipo_mime");

				// Imprimir la imagen de error directamente
				echo $imagen_data;
				exit;
			}
		}

		function mostrarFilesPDF($archivo = null, $tipo = 'application/pdf') {
			$carpeta_files = '/Volumes/efsczm/ftpFiles/files/';
			$no_file = '/Volumes/efsczm/ftpFiles/file_roto.gif';

			$carpeta_files = $_SERVER['DOCUMENT_ROOT'].'/MVPDIP1420/admin/ftpFiles/files/';
			$no_file = $_SERVER['DOCUMENT_ROOT'].'/MVPDIP1420/admin/ftpFiles/file_roto.gif';

			if ($archivo != "") {
				if (file_exists($carpeta_files . $archivo)) {
					$archivo_data = file_get_contents($carpeta_files . $archivo);
					$base64 = base64_encode($archivo_data);
				} else {
					$archivo_data = file_get_contents($no_file);
					$base64 = base64_encode($archivo_data);
				}
			} else {
				$archivo_data = file_get_contents($no_file);
				$base64 = base64_encode($archivo_data);
			}
			$data['file'] = $base64;
			$data['tipo'] = $tipo;
			return $data;
		}

		function listadoFiles() {
			$carpeta_files = '/Volumes/efsczm/ftpFiles/files/';
			$carpeta_files = $_SERVER['DOCUMENT_ROOT'].'/MVPDIP1420/admin/ftpFiles/files/';
			// Obtener la lista de archivos en la carpeta
			$archivos = scandir($carpeta_files);

			// Filtrar los directorios especiales "." y ".."
			$archivos = array_filter($archivos, function($archivo) {
				return $archivo != "." && $archivo != ".." && $archivo != ".DS_Store";
			});
		
			// Devolver la lista de archivos como un array
			return $archivos;
		}


		function obtenerContenidoArchivo($archivo = null) {
			$carpeta_files = $_SERVER['DOCUMENT_ROOT'] . '/MVPDIP1420/admin/ftpFiles/files/';
			$rutaArchivo = $carpeta_files . $archivo;
		
			// Verificar si el archivo existe
			if (file_exists($rutaArchivo)) {
				// Obtener el tipo MIME del archivo de manera más fiable
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$tipoMIME = finfo_file($finfo, $rutaArchivo);
				finfo_close($finfo);
		
				// Obtener el peso del archivo en bytes
				$pesoArchivo = filesize($rutaArchivo);
		
				// Obtener el contenido del archivo
				$contenido = file_get_contents($rutaArchivo);
				// Convertir el contenido a base64
				$contenidoBase64 = base64_encode($contenido);
		
				// Devolver un arreglo con la información
				return array(
					'status' => 1,
					'contenido' => $contenidoBase64,
					'tipo' => $tipoMIME,
					'size' => $pesoArchivo,
				);
			} else {
				// Archivo no encontrado
				// header("HTTP/1.0 404 Not Found");
				return array(
					'status' => 0,
					'error' => "Archivo no encontrado.",
				);
			}
		}
		
		
?>