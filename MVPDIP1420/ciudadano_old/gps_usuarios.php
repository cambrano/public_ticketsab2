<?php
	$info['request_method'] = $_SERVER['REQUEST_METHOD'];
	$info['request_uri'] = $_SERVER['REQUEST_URI'];
	$info['script_name'] = $_SERVER['SCRIPT_NAME'];
	$info['php_self'] = $_SERVER['PHP_SELF'];
	$info['usuario_sesiones'] = '1';
	$info['id_usuario'] = $_COOKIE['id_usuario'] ;
	$info['tipo_usuario'] = 'ciudadano';
	$info;
?>
	<div id="msnGP" ></div>
	<script type="text/javascript">
		<?php
			if($_COOKIE['id_usuario']!='1'){
				echo 'setInterval(localize,300000);';
			}
		?>
		localize();
		function localize(){
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
	<div style="display: block;">
		<input type="hidden" name="latitud_script" value="" id="latitud_script">
		<input type="hidden" name="longitud_script" value="" id="longitud_script">
		<input type="hidden" name="precision_script" value="" id="precision_script">
		<input type="hidden" name="loc_script" value="" id="loc_script">
	</div>