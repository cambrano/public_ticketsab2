<?php
		include '../functions/security.php'; 
		include '../functions/localidades.php';
		include '../functions/municipios.php'; 
		include '../functions/estados.php';
		include '../functions/paises.php'; 
		include '../functions/sucursales.php'; 
		if(!empty($_POST)){
			
			$tipo=$_POST['tipo'];

			if($tipo=="datos_formulario"){
				$id_pais=$_POST['id_pais'];
				$id_estado=$_POST['id_estado'];
				$id_municipio=$_POST['id_municipio'];
				$id_localidad=$_POST['id_localidad'];
				echo urlencode(paisNombre($id_pais));
				echo "+";
				echo urlencode(estadoNombre($id_estado));
				echo "+";
				echo urlencode(municipioNombre($id_municipio));
				echo "+";
				echo urlencode(localidadNombre($id_localidad));
			}
			if($tipo=="coordenadas" && !empty($_POST['id_estado'])){
				$id_estado=$_POST['id_estado'];
				$coordenadas= estadoCoordenadas($id_estado);
				$coordenadasJSON = json_encode($coordenadas);
				echo $coordenadasJSON;
			}
			if($tipo=="coordenadas" && !empty($_POST['id_municipio'])){
				$id_municipio=$_POST['id_municipio'];
				$coordenadas= municipioCoordenadas($id_municipio);
				$coordenadasJSON = json_encode($coordenadas);
				echo $coordenadasJSON;
			}

			if($tipo=="coordenadas" && !empty($_POST['id_localidad'])){
				$id_municipio=$_POST['id_localidad'];
				$coordenadas= localidadCoordenadas($id_municipio);
				$coordenadasJSON = json_encode($coordenadas);
				echo $coordenadasJSON;
			}
			if($tipo=="coordenadas" && !empty($_POST['id_sucursal'])){
				$id_sucursal=$_POST['id_sucursal'];
				$coordenadas= sucursalCoordenadas($id_sucursal);
				$coordenadasJSON = json_encode($coordenadas);
				echo $coordenadasJSON;
			}
			
		}