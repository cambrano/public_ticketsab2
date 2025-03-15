<?php
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','militantes_partidos',$_COOKIE["id_usuario"]);
	if(empty($moduloAccionPermisos)){
		?>
		<script type="text/javascript">
			document.getElementById("mensaje").classList.add("mensajeError");
			$("#mensaje").html("No tiene permiso");
			urlink="home.php";
			dataString = 'urlink='+urlink; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			$("#homebody").load(urlink);
		</script>
		<?php
		die;
	}
?>	
	<style>
		.right {
			width: 50%; display: block; float: right; text-align: center;
		}
		.left {
			width: 50%; display: block; float: left; text-align: center;
		}
		@media only screen and (max-width:991px) {
			.left,
			.right {
				width: 100%; /* Ambos divs ocupan el 100% del ancho */
			}
		}
	</style>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Credencial</label>
		</div>
		<?php
		//require 'librerias/phpqrcode/qrlib.php';
		include __DIR__."/../librerias/phpqrcode/qrlib.php";

		$codigo_seccion_ine_ciudadano = $seccion_ine_ciudadanoDatos['codigo_seccion_ine_ciudadano'];
		$id_seccion_ine_ciudadano = $seccion_ine_ciudadanoDatos['id'];
		$id_militante = $militante_partidoDatos['id'];
		$enlace_actual = 'https://' . $_SERVER['HTTP_HOST'].'/credencialDigital/militante.php?cot='.$codigo_seccion_ine_ciudadano.'&sic='.$id_seccion_ine_ciudadano.'&hex='.$id_militante.'&tipo=digital';
		$size = 4;
		$marge = 1;
		$link = $enlace_actual;
		$level = QR_ECLEVEL_Q;

		$enlace_comprobante = "";

		// Cambia el color del código QR (en este caso, verde)
		$color = array(
			255,  // Rojo
			0,    // Verde
			0     // Azul
		);
		// Captura la imagen del código QR en memoria con el nuevo color
		ob_start();
		QRcode::png($link, NULL, $level, $size, $marge, false, $color, 10, 2);
		$qr_image_data = ob_get_contents();
		ob_end_clean();
		// Ruta de la imagen que deseas insertar en el código QR
		$image_path = __DIR__."/../images/logos_partidos/".$militante_partidoDatos['partido_logo'];

		// Abre y carga la imagen
		$image = imagecreatefrompng($image_path);

		// Obtén las dimensiones del código QR
		$qr_width = imagesx(imagecreatefromstring($qr_image_data));
		$qr_height = imagesy(imagecreatefromstring($qr_image_data));

		// Crear una nueva imagen de 100x100 para la imagen del partido
		// Crear una nueva imagen de 100x100 para la imagen del partido con fondo transparente
		$new_image = imagecreatetruecolor(100, 100);
		imagealphablending($new_image, false);
		imagesavealpha($new_image, true);

		$transparent_color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
		imagefill($new_image, 0, 0, $transparent_color);

		// Cargar la imagen del partido con fondo transparente
		$party_image = imagecreatefrompng($image_path);
		imagealphablending($party_image, true);

		// Copiar la imagen del partido en la nueva imagen
		imagecopyresampled($new_image, $party_image, 0, 0, 0, 0, 100, 100, imagesx($party_image), imagesy($party_image));

		// Obtener las dimensiones del código QR
		$qr_width = imagesx(imagecreatefromstring($qr_image_data));
		$qr_height = imagesy(imagecreatefromstring($qr_image_data));

		// Calcular la posición para centrar la imagen en el código QR
		$x = ($qr_width - 100) / 2;
		$y = ($qr_height - 100) / 2;

		// Combinar la imagen reducida con el código QR
		imagecopy($qr_image = imagecreatefromstring($qr_image_data), $new_image, $x, $y, 0, 0, 100, 100);

		// Capturar la imagen combinada en memoria
		ob_start();
		imagepng($qr_image);
		$image_data = ob_get_contents();
		ob_end_clean();

		// Habilitar la transparencia en la imagen final
		imagealphablending($qr_image, false);
		imagesavealpha($qr_image, true);

		// Codificar la imagen combinada en formato base64 para su uso en la etiqueta img
		$image_base64 = base64_encode($image_data);
		
		//$fotografia_data="../../ops/imagen.php?id_img=".$militante_partidoDatos['name'];
		$mostrarImagenBase64 = mostrarImagenBase64($militante_partidoDatos['name']);
		$fotografia_data = "data:image/png;base64,".$mostrarImagenBase64;
		//$fotografia_data = "ftpFiles/files/".$documento_oficial_imagesDatos[0]['name'];


		// Palabra clave para encriptar y desencriptar
		$palabra_clave = "sistemaRadarAB";

		// Algoritmo de encriptación
		$algoritmo = "AES-256-CBC";

		// Vector de inicialización
		$iv = 'AB';
		$otra_variable = $militante_partidoDatos["id"];
		$otra_variable = urlencode(openssl_encrypt($otra_variable, $algoritmo, $palabra_clave, 0, $iv));
		?>
		<script>
			function comprobanteMilitante(){
				var link="militantesPartidosTotalesCredenciales/print/comprobante.php?cot=<?= $otra_variable ?>";
				//window.open(link);
				//window.open(link,'pdf','width=1280, height=460'); return false;
				//document.location = link;
				window.open(link); 
				return false;
			}
			function credencialMilitanteFisica(){
				var link="militantesPartidosTotalesCredenciales/print/credencialQR.php?cot=<?= $otra_variable ?>";
				//window.open(link);
				//window.open(link,'pdf','width=1280, height=460'); return false;
				//document.location = link;
				window.open(link); 
				return false;
			}
			function credencialMilitanteDigital(){
				let idShield = '';
				for (let i = 0; i < 5; i++) {
					const indiceAleatorio = Math.floor(Math.random() * caracteres.length);
					idShield += caracteres.charAt(indiceAleatorio);
				}
				var link="<?= $enlace_actual ?>&shield="+idShield;
				//window.open(link);
				//window.open(link,'pdf','width=1280, height=460'); return false;
				//document.location = link;
				window.open(link); 
				return false;
			}
			const caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		</script>
		<div class="left">
			<img src="<?php echo $fotografia_data; ?>" alt="Fotografía" style="width: 150px;">
			<br>
			<br>
			<table style="width:100%;table-layout: fixed;padding:10px" >
				<tr>
					<td style="text-align: left;padding:10px;">Folio</td>
					<td style="text-align: left;padding:10px;font-size:18px"><b><?= $militante_partidoDatos['folio'] ?></b></td>
				</tr>
				<tr>
					<td style="text-align: left;padding:10px;">Nombre(s)</td>
					<td style="text-align: left;padding:10px;font-size:18px"><b><?= $seccion_ine_ciudadanoDatos['nombre'] ?></b></td>
				</tr>
				<tr>
					<td style="text-align: left;padding:10px">Apellidos</td>
					<td style="text-align: left;padding:10px;font-size:18px"><b><?= $seccion_ine_ciudadanoDatos['apellido_paterno']." ".$seccion_ine_ciudadanoDatos['apellido_materno'] ?></b></td>
				</tr>
				<tr>
					<td style="text-align: left;padding:10px">Fecha Registro</td>
					<td style="text-align: left;padding:10px;font-size:18px"><b><?= $militante_partidoDatos['fecha'] ?></b></td>
				</tr>
			</table>
		</div>
		<div class="right">
			<img style="width: 250px;" src="data:image/png;base64,<?php echo $image_base64; ?>" alt="QR Code with Image">
		</div>
		<div class="sucForm" style="width: 100%" ></div>
		<div class="sucForm">
			<a class="btn btn-primary bt_responsive" style="color:white" href="data:image/png;base64,<?php echo $image_base64; ?>" download="<?= $seccion_ine_ciudadanoDatos['clave_elector'] ?>.png">Descargar QR</a>
		</div>
		<div class="sucForm">
			<button class="btn btn-primary bt_responsive" onClick="credencialMilitanteDigital();">Credencial Digital</button>
		</div>
		<div class="sucForm">
			<button class="btn btn-primary bt_responsive" onClick="comprobanteMilitante();">Comprobante</button>
		</div>
		<div class="sucForm">
			<button class="btn btn-primary bt_responsive" onClick="credencialMilitanteFisica();">Imprimir Credencial</button>
		</div>
		
	</div>
	<script type="text/javascript">
		$(".myselect").select2();
	</script>