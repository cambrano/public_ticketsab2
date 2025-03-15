<?php
		function rutaEfs() {
			$carpeta_files = '/Volumes/efsczm/ftpFiles/files/';
			$carpeta_files = $_SERVER['DOCUMENT_ROOT'].'/MVPDIP1420/admin/ftpFiles/files/';
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


?>