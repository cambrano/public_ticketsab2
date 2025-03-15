<?php
	ini_set('max_execution_time', 6000);
	@session_start();
	
	include __DIR__."../../../functions/security.php";
	include __DIR__."../../../functions/configuracion.php";
	include __DIR__."../../../functions/timemex.php";
	include __DIR__."../../../functions/secciones_ine_ciudadanos.php";
	include __DIR__."../../../functions/localidades.php";
	include __DIR__."../../../functions/municipios.php";
	include __DIR__."../../../functions/efs.php";
	
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
	$id_seccion_ine_ciudadano = $_GET["cot"];


	

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
				sc.telefono,
				sc.celular,
				sc.whatsapp,
				sc.correo_electronico,
				sc.calle,
				sc.num_ext,
				sc.num_int,
				sc.colonia,
				sc.latitud,
				sc.longitud,
				sc.curp,
				sc.clave_elector,
				sc.vigencia,
				sc.manzana,
				sc.distancia_km_r,
				sc.id_seccion_ine_ciudadano_compartido
			FROM secciones_ine_ciudadanos sc
			WHERE sc.id='{$id_seccion_ine_ciudadano}'
			LIMIT 1";
	$resultado = $conexion->query($sql);
	$data=$resultado->fetch_assoc();
	if(empty($data)){
		?>
		<script type="text/javascript">
			window.close();
		</script>
		<?php
	}

	$datos = seccion_ine_ciudadanoFamilia($data['id_seccion_ine_ciudadano_compartido']);
		$familia_line = "";
		if(!empty($datos)){
			$num = 0;
			foreach ($datos as $key => $value) {
				$familia[$num]['clave'] = $value['clave'];
				$familia[$num]['folio'] = $value['folio'];
				$familia[$num]['tipo_ciudadano'] = $value['tipo_ciudadano'];
				$familia[$num]['nombre_completo'] = $value['nombre_completo'];
				$num ++;
			}
			$familia_line = "<br><table border = 1>";
			$familia_line .= "<tr>";
				$familia_line .= "<td style='padding:5px;background-color: gray;color:black'>CLAVE<td>";
				$familia_line .= "<td style='padding:5px;background-color: gray;color:black'>FOLIO<td>";
				$familia_line .= "<td style='padding:5px;background-color: gray;color:black'>TIPO<td>";
				$familia_line .= "<td style='padding:5px;background-color: gray;color:black'>NOMBRE COMPLETO<td>";
				$familia_line .= "</tr>";
			foreach (array_reverse($familia) as $key => $value) {
				$familia_line .= "<tr>";
				$familia_line .= "<td style='padding:5px;background-color: white;color:black'>".$value['clave']."<td>";
				$familia_line .= "<td style='padding:5px;background-color: white;color:black'>".$value['folio']."<td>";
				$familia_line .= "<td style='padding:5px;background-color: white;color:black'>".$value['tipo_ciudadano']."<td>";
				$familia_line .= "<td style='padding:5px;background-color: white;color:black'>".$value['nombre_completo']."<td>";
				$familia_line .= "</tr>";
			}
			$familia_line .= "<tr>";
				$familia_line .= "<td style='padding:5px;background-color: #FDFD96;color:black'>".$data['clave']."<td>";
				$familia_line .= "<td style='padding:5px;background-color: #FDFD96;color:black'>".$data['folio']."<td>";
				$familia_line .= "<td style='padding:5px;background-color: #FDFD96;color:black'>".$data['tipo_ciudadano']."<td>";
				$familia_line .= "<td style='padding:5px;background-color: #FDFD96;color:black'>".$data['nombre_completo']."<td>";
				$familia_line .= "</tr>";
			$familia_line .= "</table>";
		}
		echo $familia_line;
		die;
	


	