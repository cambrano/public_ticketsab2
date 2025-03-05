<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/api_whatsapp_mensajes.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}


	$api_whatsapp_mensajesWhatsappDatos = api_whatsapp_mensajesWhatsappDatos($id,' id DESC');

	?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="apiWhatsappMensajes/index.php";
			dataString = 'urlink='+urlink; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			$("#homebody").load(urlink);
		}
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#mensaje").click(function(event) { 
				document.getElementById("mensaje").classList.remove("mensajeSucces");
				document.getElementById("mensaje").classList.remove("mensajeError");
				$("#mensaje").html("&nbsp");
			});
		});
	</script>
	<div class="bodymanager" id="bodymanager"> 
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Mensajes</font><br><br>
					<i class="fab fa-whatsapp" style="color: #00bb2d;font-size: 28px;font-weight: bold;"></i><?= $id ?>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;"></font><br><br>
				</label>
				<input type="button" onclick="cerrar()" value="Salir"><br>
			</div>
		</div>
		<style type="text/css">
			.chat {
				width: 100%;
				display: table;
			}
			.bubble_you {
				background-color: #0d1418;
				border-radius: 5px;
				box-shadow: 0 0 6px #B2B2B2;
				display: inline-block;
				padding: 10px 18px;
				position: relative;
				vertical-align: top;
			}
			.bubble_you::before {
				background-color: #0d1418;
				content:"\00a0";
				display: block;
				height: 16px;
				position: absolute;
				top: 0px;
				transform: rotate(29deg) skew(-35deg);
				-moz-transform: rotate(29deg) skew(-35deg);
				-ms-transform: rotate(29deg) skew(-35deg);
				-o-transform: rotate(29deg) skew(-35deg);
				-webkit-transform: rotate(29deg) skew(-35deg);
				width: 20px;
			}
			.bubble_me {
				background-color: #056162;
				border-radius: 5px;
				box-shadow: 0 0 6px #B2B2B2;
				display: inline-block;
				padding: 10px 18px;
				position: relative;
				vertical-align: top;
			}
			.bubble_me::before {
				background-color: #056162;
				content:"\00a0";
				display: block;
				height: 16px;
				position: absolute;
				top: 0px;
				transform: rotate(29deg) skew(-35deg);
				-moz-transform: rotate(29deg) skew(-35deg);
				-ms-transform: rotate(29deg) skew(-35deg);
				-o-transform: rotate(29deg) skew(-35deg);
				-webkit-transform: rotate(29deg) skew(-35deg);
				width: 20px;
			}
			.you {
				background-color: #0d1418;
				float: left;
				clear: both;
				margin: 5px 45px 5px 20px;
			}
			.you::before {
				box-shadow: -2px 2px 2px 0 rgba(178, 178, 178, .4);
				left: -9px;
			}
			.me {
				background-color: #056162;
				float: right;
				clear: both;
				margin: 5px 20px 5px 45px;
			}
			.me::before {
				box-shadow: 2px -2px 2px 0 rgba(178, 178, 178, .4);
				right: -9px;
			}
			.parallax {
				/* The image used */
				background-color:rgba(0, 0, 0,1);
				background-image: url('img/bg_whatsapp.png');
				/*background-color: #cccccc;*/ /* Used if the image is unavailable */
				background-repeat: repeat;
				background-size: auto;
				color: #f1f1f2; 
			}
			.wa_link:link{
				color: #68bbe4;
			}
			.wa_link:visited{
				color: #68bbe4;
			}
			.wa_link:hover{
				color: #68bbe4;
				text-decoration: underline;
			}
			.wa_link:active{
				color: #68bbe4;
			}
		</style> 
		<div class="bodyinput">
			<br>
			<div class="parallax">
				<div style="background-color:rgba(0, 0, 0,0.8);">
					<div class="chat" style="padding: 20px 0px 20px 0px">
						<?php
						foreach ($api_whatsapp_mensajesWhatsappDatos as $key => $value) {
							if($value['fecha_hora_envio']!=''){
								$palomita_1 = true;
							}
							if($value['fecha_hora_entrega']!=''){
								$palomita_2 = true;
							}
							if($value['fecha_hora_leido']!=''){
								$palomita_3 = true;
							}
							if($value['Latitude']!=''){
								$gps = '<br><br> <i class="fas fa-globe-americas"></i> <b>Lat:</b> '.$value['Latitude'].' | <b>Lng:</b> '.$value['Longitude'];
								$gps .='<br><a class="wa_link" target="_blank" href="https://www.google.com/maps/search/?api=1&query='.$value['Latitude'].','.$value['Longitude'].'">GoogleMaps</a>';
							}else{
								$gps = '';
							}

							if($value['MediaUrl0']!=''){
								if( 
									$value['MediaContentType0'] =='image/jpeg' || 
									$value['MediaContentType0'] =='image/png' || 
									$value['MediaContentType0'] =='image/jpg' || 
									$value['MediaContentType0'] =='image/gif'
								){
									$MediaUrl0 ='<img src="'.$value['MediaUrl0'].'" style="width: 100%">';
									$MediaUrl0 .= '<br><br>';
								}elseif( 
									$value['MediaContentType0'] =='video/mp4' || 
									$value['MediaContentType0'] =='video/mpeg' || 
									$value['MediaContentType0'] =='video/ogg' || 
									$value['MediaContentType0'] =='video/webm'
								){
									$MediaUrl0 ='<video width="320px" controls><source src="'.$value['MediaUrl0'].'" type="'.$value['MediaContentType0'].'">';
									$MediaUrl0 .= '<br><br>';
								}else{
									$MediaUrl0 = '<a class="wa_link" href="'.$value['MediaUrl0'].'" download>
													Descargar Archivo<i class="fas fa-download"></i>
												</a>';
									$MediaUrl0 .= '<br>';
								}
							}else{
								$MediaUrl0 = '';
							}
							if($value['ProfileName'] == 'Sistema'){
								?>
								<div class="bubble_me me">
									<strong style="font-size: 10px;font-style: italic;"><?= $value['ProfileName'] ?>:</strong><br>
									<?= $value['body'] ?>
									<div style="width: 100%">
										<?php
											if($palomita_1){
												?>
												<div style="width: 100%;text-align: right;padding: 0px;float: right;">
													<table align="right">
														<tr>
															<td>
																<strong style="font-size: 10px;font-style: italic;"><?= $value['fecha_hora_envio'] ?></strong><br>
															</td>
															<td style="width: 40px">
																<img style="width: 18px" src="img/whatsapp_envio.png">
															</td>
														</tr>
													</table>
												</div>
												<?php
											}
										?>
										<?php
											if($palomita_2){
												?>
												<div style="width: 100%;text-align: right;padding: 0px;float: right;">
													<table align="right">
														<tr>
															<td>
																<strong style="font-size: 10px;font-style: italic;text-align: right;"><?= $value['fecha_hora_entrega'] ?></strong><br>
															</td>
															<td style="width: 40px">
																<img style="width: 18px" src="img/whatsapp_entrega.png">
															</td>
														</tr>
													</table>
												</div>
												<?php
											}
										?>
										<?php
											if($palomita_3){
												?>
												<div style="width: 100%;text-align: right;padding: 0px;float: right;">
													<table align="right">
														<tr>
															<td>
																<strong style="font-size: 10px;font-style: italic;text-align: right;"><?= $value['fecha_hora_leido'] ?></strong><br>
															</td>
															<td style="width: 40px">
																<img style="width: 18px" src="img/whatsapp_leido.png">
															</td>
														</tr>
													</table>
												</div>
												<?php
											}
										?>
									</div>
								</div>
								<?php
							}else{
								?>
								<div class="bubble_you you">
									<strong style="font-size: 10px;font-style: italic;">
										WA: <?= $value['whatsapp'] ?> 
										<br>
										<?= $value['ProfileName'] ?>:
									</strong><br>
									<?= $MediaUrl0.$value['body'].$gps ?>
									<div style="width: 100%">
										<strong style="font-size: 10px;font-style: italic;"><?= $value['fechaR'] ?></strong>
									</div>
								</div>
								<?php
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>