<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('perfiles','correos_electronicos',$_COOKIE["id_usuario"]);
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
						document.getElementById("usuario").value = data.usuario;
						document.getElementById("password").value = data.password;
					}else{
						document.getElementById("mensaje").classList.add("mensajeError");
						$("#mensaje").html('Error');
					}
				}
			});
		}
	</script>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Identidad</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" <?= $claveF['input'] ?> type="text" name="clave" autocomplete="off"  id="clave" value="<?= $correo_electronicoDatos['clave'] ?>" placeholder="" maxlength="120" onkeyup="clave(this.value)"/><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Identidad<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_identidad" <?= $disbale_id_pricipal ?> onchange="id_identidad();">
				<?php
				echo identidades($correo_electronicoDatos['id_identidad']);
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
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Correo Eléctronico</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Fecha Emision<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="fecha_emision" autocomplete="off"  id="fecha_emision" value="<?= $correo_electronicoDatos['fecha_emision'] ?>" placeholder="" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Hora<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="hora_emision" autocomplete="off"  id="hora_emision" value="<?= $correo_electronicoDatos['hora_emision'] ?>" placeholder="" /><br>
		</div>
		<div class="sucForm" style="width: 100%"></div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Servidor de Correo<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_servidor_correo">
				<?php
				echo servidores_correos($correo_electronicoDatos['id_servidor_correo']);
				?>
			</select><br>
		</div>
		<div class="sucForm" style="width: 100%"></div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Usuario<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="usuario" autocomplete="off"  id="usuario" value="<?= $correo_electronicoDatos['usuario'] ?>" maxlength="250" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Password<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="password" autocomplete="off"  id="password" value="<?= $correo_electronicoDatos['password'] ?>" maxlength="250" /><br>
		</div>
		<div class="sucForm" style="width: 100%"></div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Teléfono</label><br>
			<input class="inputlogin" type="text" name="telefono" autocomplete="off"  id="telefono" value="<?= $correo_electronicoDatos['telefono'] ?>" maxlength="250" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Correo Recuperación</label><br>
			<input class="inputlogin" type="text" name="correo_electronico_recuperacion" autocomplete="off"  id="correo_electronico_recuperacion" value="<?= $correo_electronicoDatos['correo_electronico_recuperacion'] ?>" maxlength="250" /><br>
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