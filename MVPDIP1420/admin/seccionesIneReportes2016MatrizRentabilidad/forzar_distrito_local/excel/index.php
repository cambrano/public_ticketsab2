<?php
	@session_start();
	include '../../../functions/security.php';
	include '../../../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','cartografias_distritos_locales_2016',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['download'] || $moduloAccionPermisos['all']){
		$pageService=$_GET['cot'];
		$_COOKIE['pageService'];
	}else{
		$pageService = "";
	}

	if($pageService=="" || $_COOKIE['pageService'] != $pageService ){
		?>
		<script type="text/javascript">
			window.close();
		</script>
		<?php
		die;
	}else{
		$_COOKIE['pageService'];
	}
?>
	<title>Generando Archivo</title>
	<link rel="icon" href="favicon.ico" type="image/x-icon"/>
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
	<style type="text/css">
		html {font-size: 18px;}
		body { 
			font-family: 'Source Sans Pro', sans-serif;
			line-height: 1.6;
			font-size: 1em;
			padding: 0 20px;
			}
		.content {
			width: 100%;
			margin: 50px auto 10px auto;
			text-align: center;
		}
		h1 {
			font-weight: 300;
			font-size: 2.5em;
			color: #000;
			font-weight: bold;
		}
		p {
			font-family: 'Source Sans Pro', sans-serif;
			line-height: 1em;
			font-weight: 300;
			color: #333;
		}
		@media only screen 
			and (min-device-width: 320px) 
			and (max-device-width: 480px)
			and (-webkit-min-device-pixel-ratio: 2) {
			html {font-size: 12px;}
		}
		@media only screen 
			and (min-device-width: 320px) 
			and (max-device-width: 568px)
			and (-webkit-min-device-pixel-ratio: 2) {
			html {font-size: 14px;}
		}
	</style>
	<div class="content">
		<h1>Generando Archivo</h1>
		<h2 id="text_sub" style="margin: 0px">No cierre la ventana, en breve se generara el archivo.</h2>
		<img id="loader" src="loader.gif" style="width: 320px"><br>
		<img src="server.gif" style="width: 320px">
		<div id="script"></div>
		<div id="number"></div>
		<input type="hidden" id="stop" value="">
	</div>
	<script type="text/javascript" language="javascript" src="../../../js/jquery.js"></script> 
	<script>
		$('#script').load('excel.php?cot=<?= $_GET['cot'] ?>');
		var n = 0;
		var l = document.getElementById("number");
		var punto = ".";
		window.setInterval(function(){
			if(n==0){
				punto=".";
			}
			if(n==1){
				punto="..";
			}
			if(n==2){
				punto="...";
			}
			if(n==3){
				punto="....";
			}
			if(n==4){
				punto=".....";
				n=0;
			}
			var stop = document.getElementById("stop").value;
			if(stop==1){
				document.title = "Listo. descargelo";
				return false;
			}else{
				document.title = "Generando Archivo "+punto;
			}
			n++;
		},1000);
	</script>