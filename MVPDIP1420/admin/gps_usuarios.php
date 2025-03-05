<?php
	$info['request_method'] = $_SERVER['REQUEST_METHOD'];
	$info['request_uri'] = $_SERVER['REQUEST_URI'];
	$info['script_name'] = $_SERVER['SCRIPT_NAME'];
	$info['php_self'] = $_SERVER['PHP_SELF'];
	$info['usuario_sesiones'] = '1';
	$info['id_usuario'] = $_COOKIE['id_usuario'] ;
	$info['tipo_usuario'] = 'usuario';
	$info['paguinaId'] = decrypt_ab_checkFinal($_COOKIE['paguinaId']).'-'.decrypt_ab_checkFinal($_COOKIE['paguinaId_1']).'-'.decrypt_ab_checkFinal($_COOKIE['paguinaId_2']).'-'.decrypt_ab_checkFinal($_COOKIE['paguinaId_3']).'-'.decrypt_ab_checkFinal($_COOKIE['paguinaId_4']).'-'.decrypt_ab_checkFinal($_COOKIE['paguinaId_5']).'-'.decrypt_ab_checkFinal($_COOKIE['paguinaId_6']);
	$info['Paguinasub'] = decrypt_ab_checkSin($_COOKIE['Paguinasub']);

	//echo "<pre>";
	//print_r($info);
	//echo "</pre>";


	$info;
?>
	<div id="msnGP" ></div>
	
	<div style="display: block;">
		<input type="hidden" name="latitud_script" value="" id="latitud_script">
		<input type="hidden" name="longitud_script" value="" id="longitud_script">
		<input type="hidden" name="precision_script" value="" id="precision_script">
		<input type="hidden" name="loc_script" value="" id="loc_script">
	</div>
	<script type="text/javascript">
		<?php
			if($_COOKIE['id_usuario']!=1){
				//5000 son 5 minutos
				echo 'setInterval(localize,5000);';
				//echo 'setInterval(localize,300000);';
			}
		?>
		//localize();
		function getTimestampInSeconds () {
			return Math.floor(Date.now() / 1000)
		}
		function set_cookie(name, value) {
			document.cookie = name +'='+ value +'; Path=/;';
		}
		function delete_cookie(name) {
			document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
		}
		function getCookie(cname) {
			let name = cname + "=";
			let decodedCookie = decodeURIComponent(document.cookie);
			let ca = decodedCookie.split(';');
			for(let i = 0; i <ca.length; i++) {
				let c = ca[i];
				while (c.charAt(0) == ' ') {
				c = c.substring(1);
				}
				if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
				}
			}
			return "";
		}
		var getCookie = getCookie('loadPage');
		if( getCookie == '' || getCookie == null  ){
			localize();
			timemex = getTimestampInSeconds();
			set_cookie('loadPage',timemex);
		}


		function localize(){
			var timemex_old = ('; '+document.cookie).split(`; loadPage=`).pop().split(';')[0];
			if(timemex_old==''){
				timemex_old=0;
			}
			timemex_new = getTimestampInSeconds();
			timemex = parseInt(timemex_new) - parseInt(timemex_old);
			//console.log(timemex)
			if(timemex > 300){
				set_cookie('loadPage',timemex_new);
				//console.log(timemex_new)
			}else{
				//console.log(timemex);
				//console.log('false');
				return false;
			}
			
			//console.log('go');
			if(navigator.geolocation){
				navigator.geolocation.getCurrentPosition(mapa,error);
			}else{
				//alert('Tu navegador no soporta geolocalizacion.');
				dataAB();
			}
		}
		function dataAB(){
			var info = []; 
			var data = {
					<?php
					foreach ($info as $key => $value) {
						echo '"'.$key.'" : "'.$value.'",';
					}

					?>
				}
			info.push(data);
			$.ajax({
				type: "POST",
				url: "../../aYd4a1558721019ko4vQ448911653472.php",
				data: {info:info},
				success: function(data) { 
					//$("#msnGP").html(data);
				}
			});
		}
		function mapa(pos) {
			/************************ Aqui están las variables que te interesan***********************************/
			//$("#mensaje").html('x');
			var latitud = pos.coords.latitude;
			var longitud = pos.coords.longitude;
			var precision = pos.coords.accuracy;
			var loc = latitud+','+longitud; 
			var location = []; 
			var data = {
					'latitud_script' : latitud,
					'longitud_script' : longitud,
					'precision_script' : precision,
					'loc_script' : loc, 
				}
			location.push(data);
			document.getElementById("latitud_script").value = latitud;
			document.getElementById("longitud_script").value = longitud;
			document.getElementById("precision_script").value = precision;
			document.getElementById("loc_script").value = loc;

			var info = []; 
			var data = {
					<?php
					foreach ($info as $key => $value) {
						echo '"'.$key.'" : "'.$value.'",';
					}
					?>
					'latitud_script' : latitud,
					'longitud_script' : longitud,
					'precision_script' : precision,
					'loc_script' : loc, 
				}
			info.push(data);
			$.ajax({
				type: "POST",
				url: "../../aYd4a1558721019ko4vQ448911653472.php",
				data: {location: location,info:info},
				success: function(data) { 
					//$("#msnGP").html(data);
				}
			});



		}
		function error(errorCode){
			if(errorCode.code == 1){
				//alert("Debes activar tu geolocation para poder trabajar mejor con usted.");
				document.getElementById("mensaje").innerHTML = "Debes activar tu geolocation para poder visualizar correctamente los mapas del sistema gracias.";
				document.getElementById("mensaje").style.borderBottom= "1px solid red";
				document.getElementById("mensaje").style.color = "Red";
			}
			else if (errorCode.code==2){
				//alert("Posicion no disponible,Debes activar tu geolocation para poder trabajar mejor con usted.");
				document.getElementById("mensaje").innerHTML = "Posicion no disponible,Debes activar tu geolocation para poder visualizar correctamente los mapas del sistema gracias.";
				document.getElementById("mensaje").style.borderBottom= "1px solid red";
				document.getElementById("mensaje").style.color = "Red";
			}
			else{
				//alert("Ha ocurrido un error,Debes activar tu geolocation para poder trabajar mejor con usted.");
				document.getElementById("mensaje").innerHTML = "Ha ocurrido un error,Debes activar tu geolocation para poder visualizar correctamente los mapas del sistema gracias.";
				document.getElementById("mensaje").style.borderBottom= "1px solid red";
				document.getElementById("mensaje").style.color = "Red";
			}
		}
	</script>