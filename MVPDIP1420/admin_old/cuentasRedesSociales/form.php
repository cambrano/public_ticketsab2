<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('perfiles','cuentas_redes_sociales',$_COOKIE["id_usuario"]);
	if(empty($moduloAccionPermisos)){
		?>
		<script type="text/javascript">
			document.getElementById("mensaje").classList.add("mensajeError");
			$("#mensaje").html("No tiene permiso");
			$("#homebody").load('home.php');
		</script>
		<?php
		die;
	}
?>
	<script type="text/javascript">
		$( function() {
			$( "#fecha_emision" ).datepicker({ 
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd', 
				onSelect: function (date) { 
					document.getElementById("fecha_emision").style.border= "";
				}
			});
			$('#hora_emision').timepicker({ 
				timeFormat: 'H:i:s',
				showDuration: true,
				interval: 15,
				scrollDefault: "now",
				onSelect: function (date) { 
					document.getElementById("hora_emision").style.border= "";
				}
			}); 
		});
	</script>
	<script type="text/javascript">
		function doInsert(ctl)
		{
		    vInit = ctl.value;
		    ctl.value = ctl.value.replace(/[^a-f0-9:]/ig, "");
		    //ctl.value = ctl.value.replace(/:\s*$/, "");
		    vCurrent = ctl.value;
		    if(vInit != vCurrent)
		        return false;   

		    var v = ctl.value;
		    var l = v.length;
		    var lMax = 17;

		    if(l >= lMax)
		    {
		        return false;
		    }

		    if(l >= 2 && l < lMax)
		    {
		        var v1 = v;     
		        /* Removing all ':' to calculate get actaul text */
		        while(!(v1.indexOf(":") < 0)) { // Better use RegEx
		            v1 = v1.replace(":", "");           //console.log('v1:'+v1);
		        }

		        /* Insert ':' after ever 2 chars */     
		        var arrv1 = v1.match(/.{1,2}/g); // ["ab", "dc","a"]        
		        ctl.value = arrv1.join(":");
		    }
		}
		function id_identidad(){
			var id_identidad = document.getElementById("id_identidad").value;
			var tipo = 'nombre_completo';
			var metodo = 'json';
			var datos = []; 
			var data = {    
					'id_identidad' : id_identidad,
					'tipo' : tipo,
					'metodo' : metodo,
				}
			datos.push(data);
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "identidades/ajax.php",
				data: {datos: datos},
				success: function(data) {
					//$("#mensaje").html(data);
					//console.log(data);
					if(data.status=="success"){
						document.getElementById("nombre").value = data.nombre;
						document.getElementById("apellido_paterno").value = data.apellido_paterno;
						document.getElementById("apellido_materno").value = data.apellido_materno;
						document.getElementById("fecha_nacimiento").value = data.fecha_nacimiento; 
						document.getElementById("estado").value = data.estado; 
						document.getElementById("municipio").value = data.municipio; 
						document.getElementById("localidad").value = data.localidad;
						coordenadas = data.coordenadas;
						//console.log(coordenadas);
						zoom=14;
						myMap(data.coordenadas,zoom);

					}else{
						document.getElementById("mensaje").classList.add("mensajeError");
						$("#mensaje").html('Error');
					}
				}
			});
			var tipo = 'datos_correos_electronicos_select';
			var metodo = 'mostrar';
			var datos = []; 
			var data = {    
					'id_identidad' : id_identidad,
					'tipo' : tipo,
					'metodo' : metodo,
				}
			datos.push(data);
			$.ajax({
				type: "POST",
				url: "correosElectronicos/ajax.php",
				data: {datos: datos},
				success: function(data) {
					$("#id_correo_electronico").html(data);
				}
			});
		}
		function id_correo_electronico(){
			var id_correo_electronico = document.getElementById("id_correo_electronico").value;
			var tipo = 'datos_correo_electronico';
			var metodo = 'json';
			var datos = []; 
			var data = {    
					'id_correo_electronico' : id_correo_electronico,
					'tipo' : tipo,
					'metodo' : metodo,
				}
			datos.push(data);
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "correosElectronicos/ajax.php",
				data: {datos: datos},
				success: function(data) {
					//$("#mensaje").html(data);
					//console.log(data);
					if(data.status=="success"){
						document.getElementById("usuario").value = data.usuario;
						document.getElementById("password").value = data.password;
						document.getElementById("correo_electronico").value = data.usuario;
					}else{
						document.getElementById("mensaje").classList.add("mensajeError");
						$("#mensaje").html('Error');
					}
				}
			});
		}
		</script>
		<script type="text/javascript">
			function myMap(coordenadas=null,zoomCoordenada=null) {
				<?php
					if($position){
						?>
						latitud =<?= $latitud ?>;
						longitud =<?= $longitud ?>;
						zoomCoordenada = 18
						<?php
					}else{
						?>
						latitud=coordenadas.lat;
						longitud=coordenadas.lng;
						<?php
					}
				?>
				
				zoom=zoomCoordenada;
				var myLatlng = new google.maps.LatLng( latitud,longitud); 
				var myOptions = { 
					zoom: zoom, 
					center: myLatlng,  
				} 
				var map = new google.maps.Map(document.getElementById("googleMap"), myOptions); 
				marker = new google.maps.Marker({ 
					position: myLatlng,
					draggable: true,  
				});
				google.maps.event.addListener(marker, "dragend", function() { 
								getCoords(marker); 
				});
				 
				marker.setMap(map); 
				//getCoords(marker); 
			}
		</script>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Identidad</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" <?= $claveF['input'] ?> type="text" name="clave" autocomplete="off"  id="clave" value="<?= $cuenta_red_socialDatos['clave'] ?>" placeholder="" maxlength="120" onkeyup="clave(this.value)"/><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Identidad<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_identidad" <?= $disbale_id_pricipal ?> onchange="id_identidad();">
				<?php
				echo identidades($cuenta_red_socialDatos['id_identidad']);
				?>
			</select><br>
		</div>
		<div class="sucForm" style="width: 100%"></div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Nombre<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="" autocomplete="off"  id="nombre" value="<?= $identidadDatos['nombre'] ?>" placeholder="" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Apellido Paterno<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="" autocomplete="off"  id="apellido_paterno" value="<?= $identidadDatos['apellido_paterno'] ?>" placeholder="" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Apellido Materno<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="" autocomplete="off"  id="apellido_materno" value="<?= $identidadDatos['apellido_materno'] ?>" placeholder="" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Fecha Nacimiento<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="" autocomplete="off"  id="fecha_nacimiento" value="<?= $identidadDatos['fecha_nacimiento'] ?>" placeholder="" /><br>
		</div>
		<div class="sucForm" style="width: 100%"></div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Estado<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="" autocomplete="off"  id="estado" value="<?= $identidadDatos['estado'] ?>" placeholder="" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">municipio<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="" autocomplete="off"  id="municipio" value="<?= $identidadDatos['municipio'] ?>" placeholder="" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">localidad<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="" autocomplete="off"  id="localidad" value="<?= $identidadDatos['localidad'] ?>" placeholder="" /><br>
		</div>
		<div id="mapa">
			<div id="googleMap" style="width:100%;height:400px;"></div>
			<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI&callback=myMap"></script>
		</div>
		<div class="sucForm" style="width: 100%"></div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Correos Electronicos<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_correo_electronico" onchange="id_correo_electronico();">
				<?php echo correos_electronicos('',$cuenta_red_socialDatos['id_identidad']) ?>
			</select><br>
		</div>

		<div class="sucForm" style="width: 100%"></div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Red Social</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Fecha Emision<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="fecha_emision" autocomplete="off"  id="fecha_emision" value="<?= $cuenta_red_socialDatos['fecha_emision'] ?>" placeholder="" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Hora<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="hora_emision" autocomplete="off"  id="hora_emision" value="<?= $cuenta_red_socialDatos['hora_emision'] ?>" placeholder="" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Red Social<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_red_social">
				<?php
				echo redes_sociales($cuenta_red_socialDatos['id_red_social']);
				?>
			</select><br>
		</div>
		<div class="sucForm" style="width: 100%"></div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Usuario<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="usuario" autocomplete="off"  id="usuario" value="<?= $cuenta_red_socialDatos['usuario'] ?>" maxlength="250" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Password<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="password" autocomplete="off"  id="password" value="<?= $cuenta_red_socialDatos['password'] ?>" maxlength="250" /><br>
		</div>
		<div class="sucForm" style="width: 100%"></div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Correo Eléctronico<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="correo_electronico" autocomplete="off"  id="correo_electronico" value="<?= $cuenta_red_socialDatos['correo_electronico'] ?>" maxlength="250" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Teléfono<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="telefono" autocomplete="off"  id="telefono" value="<?= $cuenta_red_socialDatos['telefono'] ?>" maxlength="250" /><br>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">url<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="url" autocomplete="off"  id="url" value="<?= $cuenta_red_socialDatos['url'] ?>" maxlength="250" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Verificado<font color="#FF0004">*</font></label><br>
			<?php
			$select[$cuenta_red_socialDatos['verificado']]='selected="selected"';
			?>
			<select class="myselect" id="verificado">
				<option value="" >Seleccione</option>
				<option <?= $select['si'] ?> value="si" >Sí</option>
				<option <?= $select['no'] ?> value="no" >No</option>
			</select><br>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos de IP y Navegador Web</label>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">User Agent<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="user_agent" autocomplete="off"  id="user_agent" value="<?= $cuenta_red_socialDatos['user_agent'] ?>" maxlength="250" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">MAC ADDRESS<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="mac_address" autocomplete="off"  id="mac_address" value="<?= $cuenta_red_socialDatos['mac_address'] ?>" maxlength="17" onkeyup="doInsert(this)" onblur="aMays(event, this)"/><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">IP<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="ip" autocomplete="off"  id="ip" value="<?= $cuenta_red_socialDatos['ip'] ?>" maxlength="250" /><br>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Ubicación</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Latitud<font color="#FF0004">*</font></label><br>
			<input type="text" name="latitud_script" id="latitud_script" value="<?= $cuenta_red_socialDatos['latitud_script'] ?>">
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Longitud<font color="#FF0004">*</font></label><br>
			<input type="text" name="longitud_script" id="longitud_script" value="<?= $cuenta_red_socialDatos['longitud_script'] ?>">
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Presición<font color="#FF0004">*</font></label><br>
			<input type="text" name="precision_script" id="precision_script" value="<?= $cuenta_red_socialDatos['precision_script'] ?>">
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Location<font color="#FF0004">*</font></label><br>
			<input type="text" name="loc_script" id="loc_script" value="<?= $cuenta_red_socialDatos['loc_script'] ?>">
		</div>
		<div class="sucForm" style="width: 100%" >
			<br>
			<?php
			if($moduloAccionPermisos[$permiso] || $moduloAccionPermisos['all']){
				?>
				<input type="button" id="sumbmit" onclick="guardar()" value="Guardar">
				<?php
			}
			?>
			<!--<input type="button" onclick="ResetInput()" value="Borrar">-->
			<input type="button" value="Cancelar" onclick="cerrar()">
		</div>
	</div> 
	<script type="text/javascript">
		$(".myselect").select2();
	</script>