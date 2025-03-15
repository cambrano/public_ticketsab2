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
		$id_militante_partido = $militante_partidoDatos['id'];
		$link = 'https://' . $_SERVER['HTTP_HOST'].'/cd/mp.php?cot='.$id_seccion_ine_ciudadano.'&ck='.substr($codigo_seccion_ine_ciudadano, 0, 6).'&hex='.$id_militante_partido.'&tipo=dg';
		$level = QR_ECLEVEL_L;
		$enlace_comprobante = "";
		// Cambia el color del código QR (en este caso, verde)
		$color = array(
			0,  // Rojo
			0,    // Verde
			0     // Azul
		);
		// Captura la imagen del código QR en memoria con el nuevo color
		ob_start();
		$size = 100;
		$marge = 1;
		QRcode::png($link, NULL, $level, $size, $marge, false, $color, 300, 2);
		$qr_image_data = ob_get_contents();
		ob_end_clean();
		// Codificar la imagen combinada en formato base64 para su uso en la etiqueta img
		$image_base64 = base64_encode($qr_image_data);
		
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
				var link="<?= $link ?>&shield="+idShield;
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